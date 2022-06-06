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
class VideotecaPastas
{

	 const CURRENT_TABLE = 'videotecas_pastas';

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
     * Receive a instance of class Core ans storage that to
     * create a access layer to Core Object
     *
     * @param  \Core $core
     *
     * @return \VideotecaPastas object
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
     * Get all data whe that method can fetch from database
     *
     * @return multidimensional array
     */
    public function ListarTodas()
    {
        $this->_coreClass->set(
            'sql',
            'SELECT  '.$this->_coreClass->get('campos')
                .'  FROM '.self::CURRENT_TABLE
                .' WHERE ativo = "S"'
        );

        $this->applySearchFilter();

        return $this->_coreClass->set('campos', 'idpasta, nome')
            ->set('groupby', 'idpasta')
            ->retornarLinhas();
    }

    /**
     *
     */
    public function renomear()
    {
        if (
            mysql_query(
            'UPDATE '.self::CURRENT_TABLE.'
                SET nome="'.addslashes($_POST['nome']).'"
                WHERE idpasta='. (int) $_POST['id']
            )
        ){
            return 'Pasta renomeada com sucesso!';
        };
    }

    /**
     * Desactivate one row in database. It's no delete the row, only set the
     * "ativo" column to 'N'
     */
    public function remover()
    {
        if (! $_POST['id']) {
            $this->errorHandler = true;
            return false;
        }

        $directoryPath = $this->_getPathNameById($_POST['id']);

        $path = realpath(dirname(__FILE__).'/../../storage/videoteca')
            . DIRECTORY_SEPARATOR . $directoryPath->caminho;

        if ($this->_checkDirectory($path)) {

            return json_encode(
                array(
                    'error' => (int) $this->_errorHandler,
                    'alert' => $this->_removeDirectory($path)
                )
            );
        }

        return json_encode(
            array(
                'error' => (int) true,
                'alert' => 'Pasta /storage/'.$directoryPath->caminho.' não encontrada!'
            )
        );

    }

	/**
	 * Persiste a folder on DB. If it fail, storage a errorHandler.
	 *
	 * @return boolean
	 */
	public function save()
	{
		if (! $_POST['nome']) {
			$this->errorHandler = true;
			return false;
		}

        $nome = $_POST['nome'];

        $source = $this->_sanitizePathString($_POST['nome']);
        $path = realpath(dirname(__FILE__).'/../../storage/videoteca');

        if(mkdir($path .'/'.$source, 0777)) {

            $query = 'INSERT INTO `'.self::CURRENT_TABLE.'` SET
                nome= "'. addslashes($nome). '",
                caminho="'.addslashes($source).'"';

            if (mysql_query($query)) {
                return json_encode(
                    array(
                        'id' => mysql_insert_id(),
                        'name' => $nome
                    )
                );
            }
        }

	}

    private function _sanitizePathString($path)
    {
        return strtr(
            $path,
            'áàâãäéèêëíìîïóòôõöúùûüçÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÇ#!$@%¨&*()-+/^~][´`ç.,=<>\?: ',
            'aaaaeeeeiiiiooooouuuuucAAAAAEEEEIIIIOOOOOUUUUC_____________________________'
        ) . '_' . md5(uniqid());
    }

    private function _getPathNameById($idOfPath)
    {
        return mysql_fetch_object(
            mysql_query(
               'SELECT caminho FROM '.self::CURRENT_TABLE. ' WHERE idpasta='.$idOfPath
            )
        );

    }

    private function _checkDirectory($directoryRealPath)
    {
        if (! is_dir($directoryRealPath)) {
            return false;
        }

        return true;
    }

    private function _removeDirectory($path)
    {
        if (! class_exists('DirectoryIterator')) {
            $this->_errorHandler = true;
            return 'A classe SPL DirectoryIterator não foi encontrada!';
        }

        $dirIterator = new DirectoryIterator($path);

        foreach ($dirIterator as $file) {
            if (! $file->isDot()) {
                $filesContainer[] = $file->getFilename();
            }
        }

        if (0 != count($filesContainer)) {
            $this->_errorHandler = true;
            return 'A pasta não pode ser deletada, pois contém arquivos!';
        }

        if (rmdir($path)) {
            $this->_removePathFromDatabase($_POST['id']);
            $this->_errorHandler = false;
            return 'Pasta deletada com sucesso!';
        }
    }

    private function _removePathFromDatabase($folderId)
    {
        return mysql_query(
            'UPDATE '.self::CURRENT_TABLE
            .' SET ativo="N" WHERE idpasta='.$folderId
        );
    }

    public function getPathNameById($idOfPath)
    {

        return mysql_fetch_object(
            mysql_query(
               'SELECT caminho FROM '.self::CURRENT_TABLE. ' WHERE idpasta='.$idOfPath
            )
        );
    }

    public static function getName($idOfPath)
    {

        $pastaInfo = mysql_fetch_object(
            mysql_query(
               'SELECT nome FROM '.self::CURRENT_TABLE. ' WHERE idpasta='.$idOfPath
            )
        );

        return $pastaInfo->nome;
    }
}