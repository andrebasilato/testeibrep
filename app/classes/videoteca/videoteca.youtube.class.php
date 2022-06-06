<?php
/**
 * Create and interage with the videoteca session
 * Without use iheritance :3
 *
 * @author  Jefersson Nathan <jeferssonn@alfamweb.com.br>
 * @package Alfama_Oraculo
 * @since   2013
 * @license (c) Alfama 2013
 * @version $Id$
 */
class VideotecaYoutube
{

	const CURRENT_TABLE = 'videotecas';

    const API_URI = 'http://gdata.youtube.com/feeds/api/videos/%s?v=2&alt=json';

    /**
     * Storage the Class CORE. This make more sense on classes and
     * oriented object design programming, because Videoteca is not
     * a Core.
     *
     * @var
     */
    private $_coreClass;

    /**
     * Receive a instance of class Core ans storage that to
     * create a access layer to Core Object
     *
     * @param  \Core $core
     *
     * @return \VideotecaYoutube object
     */
    public function __construct(Core $core)
    {
        $this->_coreClass = $core;

        $this->_coreClass->campo = ' * ';
        return $this;
    }

    /**
     * Create a interface of data access between Videoteca Object
     * and Core object storaged in <$_coreClass> variable
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __Call($method, $args)
    {
        return call_user_func_array(
            array($this->_coreClass, $method),
            $args
        );
    }

    /**
     * Return the informations referer of a videoteca with the value
     * passed in the variable $id
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
     * This try registre a video on local system, if it is allowed
     * if not, return a error to the user. First get information from video by
     * API_URI constant MATCH declared on top of this class. Like gdata return a
     * json code, here I have used json_decode to transform it in a php internal
     * type.
     *
     * So, agroup information's a filter to get important data from getted json.
     * To finish, the <code>_saveAll()</code> method, call the other methods to
     * mount the query, so it try persist that data.
     *
     * @param string $videoUri
     * @return string
     */
    public function tryRegistre($videoUri)
    {
       $this->_filterUrl($videoUri);
       $data = json_decode($this->_getVideoInformation($videoUri));
       $information = $this->_agroupInformations($data, $videoUri);

       return $this->_saveAll($information);
    }

    /**
     * Consult on GData API to get information of a specicly video.
     * If have success, it return a json code to the php work with it.
     *
     * @param string $videoUri unique identify for the video
     * @return string json
     */
    private function _getVideoInformation($videoUri)
    {
        return file_get_contents(
            sprintf(
                self::API_URI,
                $videoUri
            )
        );
    }

    /**
     * Get a reference to the $<videoUri> param and search for a pattern youtube
     * uri, try to get a Unique Identify for a video and store it on $videoUri
     * This not use return because reference is used here.
     *
     * @param string $videoUri
     */
    private function & _filterUrl(&$videoUri)
    {
        preg_match(
            "#\?v=([a-zA-Z0-9-_]+)|v\/([a-zA-Z0-9-_]+)#",
            $videoUri,
            $match
        );

        $videoUri = $match[1];
    }

    private function _saveAll(array $relation)
    {
        if (mysql_query($this->_mountInsert($relation))) {
            return 'Vídeo cadastrado com sucesso!';
        }
        return 'Não foi possivel cadastrar o vídeo!';
    }

    /**
     * Here is mounted the query to persist this informations on database.
     * the keys of array is used like COLUMNS of mySql and the value's is scaped
     * using array_map solution appliang the <code>addSlashes();</code> function
     * to all elements os array $<relation>.
     *
     * @param array $relation
     * @return string
     */
    private function _mountInsert(array $relation)
    {
        $keys = array_keys($relation);
        $query = 'INSERT INTO %s(%s) VALUES(%s)';

        $relation = array_map('addSlashes', $relation);

        return sprintf(
            $query,
            self::CURRENT_TABLE,
            join(', ', $keys),
            "'".join("', '", $relation)."'"
        );
    }

    /**
     * Received the json converted in array provide by Google, and get the more
     * importants informations to our system, the CV. This mout the array with
     * that informations and return this.
     *
     * Note: This array's to be used on <_mountInsert()> ;)
     *
     * @param array $data
     * @param string $videoUri
     * @return array
     */
    private function _agroupInformations($data, $videoUri)
    {
        $entry = $data->entry;
        $media = $entry->{'media$group'};

        $information = array();
        $information['titulo'] = $entry->title->{'$t'};
        $information['data_cad'] = strtotime($entry->published->{'$t'});
        $information['imagem'] = 'http://i1.ytimg.com/vi/'.$videoUri.'/hqdefault.jpg';
        $information['arquivo'] = 'http://www.youtube.com/embed/'.$videoUri;
        $information['variavel'] = 'youtube';
        $information['idpasta'] = $_POST['pasta'];

        $information['descricao'] = htmlentities(
            $media->{'media$description'}->{'$t'}
        );

        $information['duracao'] = gmdate(
            'H:i:s',
            $media->{'media$content'}[0]->duration
        );

        return $information;
    }
}