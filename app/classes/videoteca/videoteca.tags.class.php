<?php
/**
 * Create and interage with the videoteca tags session
 * Without use iheritance :3
 *
 * @author  Jefersson Nathan <jeferssonn@alfamweb.com.br>
 * @package Alfama_Oraculo
 * @since   2013
 * @license (c) Alfama 2013
 * @version $Id$
 */
class VideotecaTags
{

	 const CURRENT_TABLE = 'videotecas_tags';

    /**
     * Storage the Class CORE. This make more sense on classes and
     * oriented object design programming, because Videoteca is not
     * a Core.
     *
     * @var
     */
    private $_coreClass;

	/**
	 * Set a error Hanlder if error is accoured
	 *
	 * @var
	 */
	private $_errorHandler = false;

    /**
     * Storage the video id
     *
     * @var
     */
    private $_video = null;

    /**
     * Receive a instance of class Core ans storage that to
     * create a access layer to Core Object
     *
     * @param  \Core $core
     *
     * @return \VideotecaTags object
     */
    public function __construct(Core $core)
    {
        $this->_coreClass = $core;

        $this->_coreClass->campos = ' * ';
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
     * Get all data whe that method can fetch from database
     *
     * @return multidimensional array
     */
    public function ListarTodas()
    {
        $this->_coreClass->set(
            'sql',
            'SELECT * FROM `'.self::CURRENT_TABLE.'` WHERE ativo="S"'
        );

        return $this->_coreClass
            ->set('campos', 'idtag')
            ->set('ordem', 'asc')
            ->set('ordem_campo', 'nome')
            ->set('groupby', 'idtag')
            ->set('limite', 800)
            ->retornarLinhas();
    }

    /**
     * If a tag describe on array $<tags>'s not storaged on db yet, we can
     * regisrty it by private method <VideotecaTags::_save(array $tags)>, but
     * first I check if exists by <VideotecaTags::_hasTag(string $tagName)>
     * method
     *
     * @param array $tags
     * @return object \VideotecaTags
     */
    public function registerTagsIfNotExists(array $tags)
    {
        $tags = array_map('strtolower', $tags);

        foreach ($tags as $tag) {
            if (! $this->_hasTag($tag)) {
                $this->_save($tag);
            }
        }

        return $this;
    }

    /**
     * Verify if tag already regitred on database and return informations about
     * she.
     *
     * @param string $tagName
     * @return object|boolean
     */
    private function _hasTag($tagName)
    {
        $queryStmt = 'SELECT * FROM '
            .self::CURRENT_TABLE.' WHERE nome="'.$tagName.'"';

        return mysql_fetch_object(mysql_query($queryStmt));
    }

    /**
     * Save a tag on database, its no filter or validade the tag name.
     *
     * @param string $tagName
     * @return boolean
     */
    private function _save($tagName)
    {
        $queryString = 'INSERT INTO '.self::CURRENT_TABLE
            .'(nome) VALUES("'.addslashes($tagName).'")';

        return mysql_query($queryString);
    }

    /**
     * Save the video Id to register tags ;)
     *
     * @param integer $videoId
     *
     * @return $this
     */
    public function toTheVideo($videoId)
    {
        $this->_video = (int) $videoId;
        return $this;
    }

    /**
     * Regiter tags for the $<this->_video> property of VideotecaTags class
     *
     * @param array $tags
     */
    public function registerTags(array $tags)
    {
        $this->_removeTagIfItsNotInUse();
        $this->_cleanAllTagsToTheVideo();
        $tags = array_map('strtolower', $tags);
        foreach ($tags as $tag) {
            $this->_insertTagToVideo($tag);
        }
    }

    /**
     * Execute and mount query to insert vídeo on database ;B
     *
     * @param string $tag
     */
    private function _insertTagToVideo($tag)
    {
        if ($TagInformation = $this->_hasTag($tag)) {
            $queryString = 'INSERT INTO `videotecas_tags_videos`(idvideo, idtag)
                VALUES('.$this->_video.', '.$TagInformation->idtag.')';
            mysql_query($queryString);
        }
    }

    /**
     * Get all tags of a specificly video
     *
     * @param string|integer $videoId
     * @return array
     */
    public function listTagsForVideoId($videoId)
    {
        $queryString = 'SELECT b.nome as title, c.idtagvideo
            as value FROM videotecas_tags_videos AS c
                INNER JOIN videotecas_tags AS b
                ON b.idtag=c.idtag
            WHERE ativo="S"
                AND c.idvideo='.$videoId;

        $statemment = mysql_query($queryString);

        while ($tag = mysql_fetch_object($statemment)) {
            $tagList[] = $tag;
        }

        return $tagList;
    }

    /**
     * Remove all tags of video
     *
     * @return boolean
     */
    private function _cleanAllTagsToTheVideo()
    {
        $delQuery = 'DELETE FROM videotecas_tags_videos WHERE idvideo='.$this->_video;
        return mysql_query($delQuery);
    }

    /**
     *
     *
     * @return boolean
     */
    private function _removeTagIfItsNotInUse()
    {
        $tagList = $this->listTagsForVideoId($this->_video);
        foreach ($tagList as $value) {
            if (! $this->countTag($value->title)) {
                $this->removeTag($value->value);
            }
        }
    }

    public function removeTag($id)
    {
        return mysql_query(
            'DELETE FROM `'.self::CURRENT_TABLE.'` WHERE nome="'.$id.'"'
        );
    }

    /**
     *
     * @param $id
     *
     * @internal param $name
     * @return bool
     */
    public function countTag($id)
    {
        if (! $id) {
            return false;
        }

        $sqlCount = 'SELECT COUNT(*) as total FROM `videotecas_tags_videos` WHERE ';
        $whereClausule = ' idtag="'.addslashes($id).'"';

        $result = mysql_fetch_object(
            mysql_query(
                $sqlCount . $whereClausule
            )
        );

        return $result->total;
    }

    public static function getName($tagId)
    {
        $queryStmt = 'SELECT nome FROM '
            .self::CURRENT_TABLE.' WHERE idtag="'.$tagId.'"';

        $tagInfo = mysql_fetch_object(mysql_query($queryStmt));
        return $tagInfo->nome;
    }
}