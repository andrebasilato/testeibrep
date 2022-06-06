<?php
/**
 * Class Videos
 */
class Videos
{
    /** Tabela em que serão feita a maioria das operações */
    const CURRENT_TABLE = 'avas_videotecas';

    /**
     * Acesso a instancia da classe core
     *
     * @var Core
     */
    private $_coreClass;

    /**
     * Guarda uma instancia do Zend_Db_Select que irá montar os query para nos.
     * Funcionará como um dataacces, porém quem irá executar as querys será a
     * classe Core.
     *
     * @var string
     */
    private $_queryBuild;

    /**
     * Guarda uma instancia de core para ser usada como mediador entre o banco
     * de dados e nossa classe vídeo. Partindo desse ponto, nossa camada de
     * abstração seria o objeto guardado internamente pela classe em $<_coreClass>
     *
     * @return \Videos
     *
     * @param \Core $coreObject - A Core instance
     * @param Zend_Db_Select $_queryBuild
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function __construct(Core $coreObject, Zend_Db_Select $_queryBuild)
    {
        $this->verificaDependencias();
        $this->_queryBuild = $_queryBuild;
        $this->_coreClass = $coreObject;
        $this->_coreClass->set('campos', ' * ');
    }

    /**
     * Delega a execução de métodos não assinados nessa classe para a nossa
     * instancia em $<_coreClass>
     *
     * @return mixed
     *
     * @param $method String - nome do método que foi chamado
     * @param $args   Array  - argumentos a ser passados para a chamada do método
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_coreClass, $method), $args);
    }

    /**
     * Verifica se as dependencias que permitem o funcionamento da classe estão
     * definidas no contexto de seu uso
     *
     * @return boolean
     *
     * @throws DependenciesNotFound
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function verificaDependencias()
    {
        if (
            ! class_exists('Zend_Db_MySql')
            || ! class_exists('Zend_Db_Select')
        ) {
            require 'DependenciesNotFound.class.php';
            throw new DependenciesNotFound('Dependencias não encontradas ...');
        }
        return true;
    }

    /**
     * Lista todos os videos encontrados no ava atual
     * Se tiver conteudo e tudo ocorrer bem, será retornado um array.
     * Caso contrario, um "false" é retornado
     *
     * @return array|boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function listarTodasVideo()
    {
        $this->_queryBuild->select();
        $this->_queryBuild->from(
            array('v' => self::CURRENT_TABLE),
            array(
                'v.idvideo as video_id',
                'v.data_cad as cadastrado_em',
				'v.idvideoteca'
				
            )
        )
        ->join(
            array('a' => 'avas'),
            'v.idava = a.idava'
        )
        ->join(
            array('m' => 'videotecas'),
            'm.idvideo = v.idvideoteca'
        )
        ->where('v.ativo = ?', 'S')
        ->where('a.idava = ?', $this->get('idava'));

        $this->_aplicaFiltrosDaBusca();

        preg_match('/SELECT (.+) FROM/i', (string) $this->_queryBuild, $fields);

        // $this->_queryBuild->group('v.idvideo');
        $this->set('ordem_campo', 'v.idvideo');
        $this->set('sql', (string) $this->_queryBuild)
            ->set('campos', trim($fields[1]))
            ->set('groupby', 'a.idava');

        return $this->retornarLinhas();
    }

    /**
     * Aplica filtros de busca setados pelo usuario ao conteudo listado.
     *
     * @deprecated
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    protected function _aplicaFiltrosDaBusca()
    {
        if (! is_array($_GET['q'])) {
            return false;
        }

        foreach ($_GET["q"] as $campo => $valor) {
            $campo = explode('|', $campo);
            $valor = str_replace('\'', '', $valor);

            if(('0' === $valor || $valor) and 'todos' != $valor) {

                if (1 == $campo[0]) {
                    $this->_queryBuild->where($campo[1].' = ?', $valor);
                }

                if (2 == $campo[0])  {
                    $busca = str_replace("\\'", '', $valor);
                    $busca = str_replace('\\', '', $busca);
                    $busca = explode(' ', $busca);

                    foreach($busca as $buscar){
                        $this->_queryBuild->where(
                            $campo[1].' like ?',
                            '%'.urldecode($buscar).'%'
                        );
                    }
                }

                if (3 == $campo[0])  {
                    $this->_queryBuild->where(
                        'date_format('.$campo[1].',\'%d/%m/%Y\') = ?',
                        $valor
                    );
                }
            }
        }
    }

    /**
     * Retorna apenas dados de um determinado vídeo
     *
     * @return array
     */
    public function retornarVideo()
    {

        $this->_queryBuild->reset();

        $this->_queryBuild->select();
        $this->_queryBuild->from(array('v' => self::CURRENT_TABLE), 'v.idvideo as video_id')
            ->join(
                array('a' => 'avas'),
                'v.idava = a.idava'
            )
            ->join(
                array('m' => 'videotecas'),
                'm.idvideo = v.idvideoteca'
            )
            ->where('v.ativo = ?', 'S')
            ->where('v.idvideo = ?', $this->get('id'))
            ->where('a.idava = ?', $this->get('idava'));

        $this->set('sql', $this->_queryBuild->__toString());

        return $this->retornarLinha($this->get('sql'));
    }

    public function _getConnection()
    {

    }
    /**
     * Persiste o vídeo em questão no banco de dados MySql
     *
     * @return bool
     */
    public function cadastrarVideo()
    {
        unset($_POST['acao'], $_POST['idpasta']);
        $_POST['ativo_painel'] = $this->_queryBuild->quote($_POST['ativo_painel']);

        $insertQuery = $this->_queryBuild->insert(self::CURRENT_TABLE, $_POST);

        if (mysql_query($insertQuery)) {
            return true;
        }
        return false;
    }
   /**
     *  informações referentes ao vídeo em questão
     *
     * @return mixed
     */
    public function modificarVideo() {
        return $this->SalvarDados();
    }

    /**
     * Remove o vídeo da listagem e operações. Porém, ele é mantido no banco de dados
     * com a flag (ativo = 'N')
     *
     * @return mixed
     */
    public function removerVideo()
    {
        $this->_queryBuild->reset();

        preg_match('#videos/(\d+)#', $_SERVER['REQUEST_URI'], $_relationId);
        $_relationId = $_relationId[1];

        $_POST = array('ativo' => "'N'");

        $updateQuery = $this->_queryBuild->update(
            self::CURRENT_TABLE,
            $_POST,
            'idvideo = ' . $_relationId
        );

        mysql_query($updateQuery);
        return $this->RemoverDados();
    }
}