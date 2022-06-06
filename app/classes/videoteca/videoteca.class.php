<?php
/**
 * Cria e interage com a seção de videotecas
 * Sem usar herança
 *
 * @author  Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 * @package Alfama_Oraculo
 * @since   2013
 * @license (c) Alfama 2013
 * @version $Id$
 */
class Videoteca
{

   const CURRENT_TABLE = 'videotecas';

    /**
     * Guarda uma instância da classe CORE. Isso faz mais sentido que usar
     * herança aqui, e nos proporciona um melhor design para o nosso objeto
     * Videoteca não é um Core.
     *
     * @var
     */
    private $_coreClass;

    /**
     * Recebe uma instancia da classe Core e a guarda para usar como uma camada
     * de acesso a dados e métodos provenientes da classe CORE
     *
     * @param  \Core $core
     * @return \Videoteca object
     */
    public function __construct(Core $core)
    {
        $this->_coreClass = $core;

        $this->_coreClass->campo = ' * ';
        return $this;
    }

    /**
     * Cria uma interface de acesso a dados entre o Objeto Videoteca
     * e o Objeto Core guardado na propriedade <$_coreClass>
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array(
            array($this->_coreClass, $method),
            $args
        );
    }


    /**
     * Obtém todos os dados que forem possíveis do banco de dados
     *
     * @return multidimensional array
     */
    public function ListarTodas()
    {
        $this->_coreClass->set(
            'sql',
            sprintf(
                'SELECT %s FROM %s WHERE ativo = "S"',
                $this->_coreClass->get('campos'),
                self::CURRENT_TABLE
            )
        );

        $this->applySearchFilter();

        return $this->_coreClass->retornarLinhas();
    }

    /**
     * Retorna informações referente a um vídeo com o "idvideo" passado para a
     * variável $id
     *
     * @param integer $id
     *
     * @return array
     */
    public function retornar($id = null)
    {
        $queryString = 'SELECT %s FROM %s WHERE ativo="S" and idvideo=%d';

        $queryBinded = sprintf(
            $queryString,
            $this->_coreClass->campos,
            self::CURRENT_TABLE,
            ($id) ? $id : $this->_coreClass->id
        );

        return $this->_coreClass->retornarLinha(
            $queryBinded
        );
    }

    /**
     * Desativa uma linha no banco de dados. Isso não deleta a linha, somente
     * modificao estado da coluna "ativo" para "N"
     */
    public function remover()
    {
        $this->_coreClass->removerDados();
    }

    /**
     * Você pode remover multiplos vídeos de uma única vez passando um array com
     * os respectivos id para essse método
     *
     * @param array $idvideos
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function removermultiplos($idvideos = array())
    {
        if (! $idvideos) {
            return 'Erro ao ler id do vídeo! Por favor, tente novamente.';
        }

        foreach ($idvideos as $id) {
            mysql_query(
                'UPDATE '.self::CURRENT_TABLE.' SET ativo="N" WHERE idvideo='.$id
            );
        }

        return 'Os vídeos foram removidos com sucesso!';
    }

    /**
     * Aplica filtros criado pela busca do Contrutor
     * ESSE MÉTODO FOI CALISTHENICSED
     *
     * @return boolean
     */
    private function applySearchFilter()
    {
        // Obtém url
        $url = ltrim($_SERVER['REQUEST_URI'], '/');
        $url = explode('/', $url);
        $url[4] = current(explode('?', $url[4]));

        // Configuração padrão
        $this->_coreClass->set('campos', 'idvideo')
            ->set('groupby', 'idvideo');

        // configuração para paginação
        if (isset($_GET['qtd'])) {
            $this->_coreClass->set('limite', (int) $_GET['qtd'])
                ->set('groupby', '*')
                ->set('campos', '*');
        }

        // usando filtros
        if ('filtro' == $url[4]) {
            $this->_coreClass->sql .= ' AND idpasta='.(int) $url[3];
        }

        // usando caixa de busca
        if (isset($_GET['termo'])) {
            $this->_coreClass->sql .= ' AND titulo LIKE "%'.addslashes($_GET['termo']).'%"';
        }

        // usando tags
        if ($_GET['tag']) {

            $this->_coreClass->sql =  preg_replace(
                '/SELECT (\*) (FROM) (\w+) (.+)/',

                'SELECT $1 $2 $3 INNER JOIN videotecas_tags_videos as vt
                    ON vt.idvideo = videotecas.idvideo
                $4 AND vt.idtag='. (int) $_GET['tag'],

                $this->_coreClass->sql
            );

            $this->_coreClass->set('campos', '*')
                ->set('groupby', '*')
                ->set('ordem_campo', 'videotecas.idvideo');
        }

        return true;
    }

    /**
     * Pega a duração de um vídeo identificado pelo $videoId
     *
     * @param $videoId
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function getDuration($videoId)
    {
        $result = mysql_fetch_object(
            mysql_query(
                sprintf(
                    'SELECT %s FROM %s WHERE ativo = "S" AND idvideo="%d"',
                    'duracao',
                    self::CURRENT_TABLE,
                    $videoId
                )
            )
        );

        return $result->duracao;
    }

    /**
     * Pega o arquivo referente ao vídeo de identificador $videoId
     *
     * @param $videoId
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function getFile($videoId)
    {
        $result = mysql_fetch_object(
            mysql_query(
                sprintf(
                    'SELECT %s FROM %s WHERE ativo = "S" AND idvideo="%d"',
                    'arquivo',
                    self::CURRENT_TABLE,
                    $videoId
                )
            )
        );

        return $result->arquivo;
    }

    /**
     * Retorna o nome da pasta referente ao vídeo de identificador $videoId
     *
     * @param $videoId
     * @param string $field
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function getFolder($videoId, $field = 'nome')
    {
        $result = mysql_fetch_object(
            mysql_query(
                sprintf(
                    'SELECT %s FROM %s %s WHERE idvideo="%d"',
                    $field,
                    'videotecas_pastas AS vp',
                    'INNER JOIN videotecas AS v ON v.idpasta = vp.idpasta',
                    $videoId
                )
            )
        );

        return $result->$field;
    }


    /**
     * Pega o imagem referente ao vídeo de identificador $videoId
     *
     * @param $videoId
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function getImage($videoId)
    {

        $result = mysql_fetch_object(
            mysql_query(
                sprintf(
                    'SELECT %s FROM %s WHERE ativo = "S" AND idvideo="%d"',
                    'imagem',
                    self::CURRENT_TABLE,
                    $videoId
                )
            )
        );

        return $result->imagem;
    }


    /**
     * Retorna o número de páginas
     *
     * @return integer
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function returnPageNumber()
    {
        return $this->_coreClass->get('paginas');
    }

    /**
     * Força download de um vídeo
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function downloadVideo($idVideo)
    {
        $fileWay = FileSystem::getBasePath(
            sprintf(
                '/storage/videoteca/%s/%d/%s_hd.mp4',
                self::getFolder($idVideo, 'caminho'),
                $idVideo,
                self::getFile($idVideo)
            )
        );

        $fsize = filesize($fileWay);

        header("Content-Type: video/mp4; charset=utf-8", true);
        header("Content-Length: $fsize", true);
        header("Content-Disposition: attachment; filename=".basename($fileWay), true);

        readfile($fileWay);
    }

    /**
     * Gera paginação
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    function GerarPaginacao($idioma, $mobile = null) {

        $this->_coreClass->retorno = "";
        $menos = $this->_coreClass->pagina - 1;
        $mais  = $this->_coreClass->pagina + 1;

        $valor_utilizado = 5;

        if ($mobile) {
            $valor_utilizado = $mobile;
            unset($idioma["pag_anterior"]);
            unset($idioma["pag_proxima"]);
        }

        $uri = parse_url($_SERVER['REQUEST_URI']);
        parse_str($uri['query'], $uri);

        $uri['qtd'] = $this->_coreClass->limite;
        $uri['ord'] = $this->_coreClass->ordem;

        unset($uri['pag']);

        $link = '?'.http_build_query($uri);

        if($_GET["q"]){
            foreach($_GET["q"] as $tipo => $valor) {
                $link .= "&q[$tipo]=$valor";
            }
        }
        $core = $this->_coreClass;

        if ($core->paginas > 1) {

            if ($menos > 0) {
                $core->retorno .= '<li class="prev"><a href="'.$link.'&pag='.$menos.'">&#8592; '.$idioma["pag_anterior"].' </a></li>';
            }

            if (($core->pagina - $valor_utilizado) < 1) {
                $anterior = 1;
            } else {
                $anterior = $core->pagina - $valor_utilizado;
            }

            if (($core->pagina+$valor_utilizado) > $core->paginas) {
                $posterior = $core->paginas;
            } else {
                $posterior = $core->pagina + $valor_utilizado;
            }

            for ($i = $anterior; $i <= $posterior; $i++) {
                if ($i != $core->pagina) {
                    $core->retorno .= '<li><a href="'.$link.'&pag='.$i.'">'.$i.'</a></li>';
                } else {
                    $core->retorno .= '<li class="active"><a href="'.$link.'&pag='.$i.'">'.$i.'</a></li>';
                }
            }

            if ($mais <= $core->paginas) {
                $core->retorno .= '<li class="next"><a href="'.$link.'&pag='.$mais.'">'.$idioma["pag_proxima"].' &#8594;</a></li>';
            }
        }
        return $this->_coreClass->retorno;
    }
}