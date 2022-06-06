<?php
/**
 * Class DiscoVirtual
 */
class DiscoVirtual
{
    /** Tabela usada nas operações atuais com o banco de dados */
    const CURRENT_TABLE = 'avas_discosvirtuais';

    /** Tabela usada em operações auxiliares */
    const AUXILIAR_TABLE = 'avas_dicosvirtuais_pastas';

    /**
     * @var Core
     */
    protected $_coreClass;

    /**
     * @var Zend_Db_Select
     */
    protected $_queryBuild;

    /**
     * @var bool
     */
    private $_halt = false;

    /**
     * @param Core $coreInstance
     * @param Zend_Db_Select $sqlMount
     */
    public function __construct(Core $coreInstance, Zend_Db_Select $sqlMount)
    {
        $this->_coreClass = $coreInstance;
        $this->set('campos', ' * ');

        $this->_queryBuild = $sqlMount;
    }

    /**
     * @param $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->_coreClass, $method), $args);
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        if ($this->_downloadFileRequested()) {
            return $this->_download();
        }

        if ($this->_isInFolder()) {
            return $this->_getFilesForCurrentFolder();
        }
        return $this->_getFoldersList();
    }


    /**
     * Foi feito o pedido de download de um arquivo?
     *
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    protected function _downloadFileRequested()
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        return 'download' == $url[7] ? true : false;
    }

    protected function _getFoldersList()
    {
        $this->_queryBuild->reset();
        $this->_queryBuild->select();
        $this->_queryBuild->from(self::AUXILIAR_TABLE)
            ->where('idava = ?', $this->_getCurrentAvaId())
            ->where('ativo = ?', 'S');

        $this->set('sql', (string)$this->_queryBuild)
            ->set('ordem', 'DESC')
            ->set('ordem_campo', 'id_pasta')
            ->set('campos', '`' . self::AUXILIAR_TABLE . '`.*')
            ->set('groupby', '*')
            ->set('pagina', $_GET['pag'])
            ->set('cmp', 'id_pasta')
            ->set('limite', (!empty($_GET['qtd'])) ? $_GET['qtd'] : 30);
        
        $this->_includeSearch();
        return $this->retornarLinhas();
    }

    protected function _getFilesForCurrentFolder()
    {
        $this->_queryBuild->select();
        $this->_queryBuild->from(self::CURRENT_TABLE)
            ->where('idava = ?', $this->_getCurrentAvaId())
            ->where('ativo = ?', 'S')
            ->where('idpasta = ?', (int)$this->_isInFolder());

        $this->set('sql', (string)$this->_queryBuild)
            ->set('ordem', 'DESC')
            ->set('ordem_campo', 'id_discovirtual')
            ->set('campos', '`' . self::CURRENT_TABLE . '`.*')
            ->set('groupby', '*')
            ->set('pagina', $_GET['pag'])
            ->set('cmp', 'id_discovirtual')
            ->set('limite', (!empty($_GET['qtd'])) ? $_GET['qtd'] : 30);

        $this->_includeSearch();
        $dados = $this->retornarLinhas();
        return $dados;
    }

    /**
     * Returna true se estivermos dentro de uma pasta. Caso contrario retorna um false
     *
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function _isInFolder()
    {
        $isFolderRequested = (int)end(explode('/', $_SERVER['REQUEST_URI']));
        return $isFolderRequested ? $isFolderRequested : false;
    }

    /**
     * Retorna configurações usadas pela classe Core
     * guardada em DiscoVirtual::$_queryBuild
     *
     * @return array
     */
    public function getDboConfig()
    {
        $config = new StdClass;
        $config->banco['tabela'] = self::CURRENT_TABLE;
        $config->banco['primaria'] = 'id_discovirtual';

        return (array)$config;
    }

    /**
     * Se se uma requisição post for feita com a intenção se persistir
     * novos dados no banco de dados do disco virtual
     *
     * @return bool
     */
    public function hasPostRequest()
    {
        if (
            isset($_POST['saveVirtualDisk'])
            || isset($_POST['delete'])
            || isset($_POST['saveFolder'])
        ) {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function processPostRequest()
    {
        if ($_POST['delete']) {
            $this->_halt = false;


            $queryStatement = 'DELETE FROM ';

            if ('folder' == $_POST['tipo']) {
                $_POST['msg'] = 'msg_pasta_delete';
                $queryStatement .= self::AUXILIAR_TABLE;
                $campo = 'id_pasta';
            } else {
                $_POST['msg'] = 'msg_arquivo_delete';
                $queryStatement .= self::CURRENT_TABLE;
                $campo = 'id_discovirtual';
            }

            $queryStatement .= ' WHERE ' . $campo . ' = ' . $_POST['id'];
        }

        if ($_POST['saveFolder']) {
            $this->_halt = false;

            $_POST['msg'] = 'msg_pasta';

            $queryStatement = $this->_queryBuild->insert(self::AUXILIAR_TABLE, array(
                    'idava' => $this->_getCurrentAvaId(),
                    'nome' => $this->_queryBuild->quote(addslashes($_POST['nome']))
                )
            );
        }

        if ($_POST['saveVirtualDisk']) {
            $fileLocation = FileSystem::getBasePath(
                '/storage/discovirtual/' . $this->_getCurrentAvaId()
            );

            $_POST['msg'] = 'msg_arquivo';

            mkdir($fileLocation, 0777);
            $fileLocation .= '/' . $_POST['pasta'];

            if ($_FILES['arquivo']['error']) {
                echo 'Um arquivo precisa ser enviado - ' . $_FILES['arquivo']['error'];
                return $this;
            }

            mkdir($fileLocation, 0777);

            if (is_uploaded_file($tmpFile = $_FILES['arquivo']['tmp_name'])) {
                move_uploaded_file($tmpFile, $fileLocation . '/' . $_FILES['arquivo']['name']);
            }

            $queryStatement = $this->_queryBuild->insert(self::CURRENT_TABLE, array(
                    'nome_do_arquivo' => $this->_queryBuild->quote($_POST['nome']),
                    'tipo' => $this->_queryBuild->quote($_FILES['arquivo']['type']),
                    'tamanho' => $_FILES['arquivo']['size'],
                    'nome_no_disco' => $this->_queryBuild->quote($_FILES['arquivo']['name']),
                    'idava' => $this->_getCurrentAvaId(),
                    'idpasta' => $this->_queryBuild->quote($_POST['pasta'])
                )
            );
        }

        mysql_query($queryStatement);
        return $this;
    }

    /**
     *
     */
    public function haltIfNecessary()
    {
        if ($this->_halt) {
            exit;
        }
    }

    /**
     * @return mixed
     */
    private function _getCurrentAvaId()
    {
        preg_match('/avas\/(\d+)/', $_SERVER['REQUEST_URI'], $match);
        return $match[1];
    }

    /**
     * @return mixed
     */
    public function fetchFolderList()
    {
        $this->_queryBuild->reset();
        $this->_queryBuild->select();
        $this->_queryBuild->from(self::AUXILIAR_TABLE)
            ->where('idava = ?', $this->_getCurrentAvaId())
            ->where('ativo = ?', 'S');

        $queryStatement = mysql_query((string)$this->_queryBuild);

        $_folderList = array();
        while ($tmp = mysql_fetch_assoc($queryStatement)) {
            $_folderList[] = $tmp;
        }
        return $_folderList;
    }

    /**
     * Força o download de um arquivo
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function _download()
    {
        $url = end(explode('/', $_SERVER['REQUEST_URI']));

        $this->_queryBuild->reset();
        $this->_queryBuild->select();
        $this->_queryBuild->from(self::CURRENT_TABLE)
            ->where('id_discovirtual = ?', $url);

        $result = mysql_fetch_object(mysql_query((string)$this->_queryBuild));

        $fileWay = FileSystem::getBasePath(
            sprintf(
                '/storage/discovirtual/%d/%d/%s',
                $result->idava,
                $result->idpasta,
                $result->nome_no_disco
            )
        );

        header("Content-Type: " . $result->tipo . "; charset=utf-8", true);
        header("Content-Length: " . $result->tamanho, true);
        header("Content-Disposition: attachment; filename=" . basename($fileWay), true);

        readfile($fileWay);
        exit;
    }

    /**
     * Retorna o nome da pagina especificada pelo id
     *
     * @param $idFolder
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function getFolderName($idFolder)
    {
        $result = mysql_fetch_object(
            mysql_query(
                'SELECT * FROM ' . self::AUXILIAR_TABLE . ' WHERE id_pasta = ' . $idFolder
            )
        );

        return $result->nome;
    }

    /**
     * Include filter of search to the query select. This is used on list of data
     * , if you use a filter and click on "Busca" button. You's using this method
     * to make the search.
     *
     * @return boolean always return true (._. )
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _includeSearch()
    {
        if (!is_array($_GET['q'])) {
            return false;
        }

        if ($_GET['qtd']) {
            $this->set('limite', (int)$_GET['qtd']);
        }

        if ($_GET['pag']) {
            $this->set('pagina', $_GET['pag']);
        }

        if ($_GET['cmp']) {
            $this->set('ordem_campo', $_GET['cmp']);
        }

        foreach ($_GET['q'] as $campo => $valor) {

            $campo = explode('|', $campo);
            $valor = str_replace('\'', '', $valor);

            if (($valor || '0' === $valor)
                and 'todos' != $valor
            ) {

                if (1 == $campo[0]) {
                    $this->_coreClass->sql .= " and {$campo[1]} = '{$valor}' ";
                }

                if (2 == $campo[0]) {
                    $busca = str_replace(
                        array("\\'", '\\'),
                        array('', ' '),
                        $valor
                    );

                    $busca = explode(" ", $busca);

                    foreach ($busca as $ind => $buscar) {
                        $this->_coreClass->sql .= " and {$campo[1]} like '%" . urldecode($buscar) . "%' ";
                    }
                }

                if (3 == $campo[0]) {
                    $this->_coreClass->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                }
            }
        }
        return true;
    }
}