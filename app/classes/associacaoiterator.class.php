<?php
/**
 * `AssociacaoIterator`
 *
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class AssociacaoIterator implements Iterator
{
    /**
     * @var
     */
    private $_mysqlResource;

    /**
     * @var
     */
    private $_data;

    /**
     * @param $mysqlResource
     *
     * @throws InvalidArgumentException
     */
    public function __construct($mysqlResource)
    {
        if (false === is_resource($mysqlResource)) {
            throw new InvalidArgumentException('Esperando um recurso válido (mysql)');
        }

        $this->_mysqlResource = $mysqlResource;
    }

    /**
     * (PHP 5 <= 5.0.0)
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->_data;
    }

    /**
     * (PHP 5 <= 5.0.0)
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_data = mysql_fetch_object($this->_mysqlResource);
    }

    /**
     * (PHP 5 <= 5.0.0)
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
       return key($this->_data);
    }

    /**
     * (PHP 5 <= 5.0.0)
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->_data;
    }

    /**
     * (PHP 5 <= 5.0.0)
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->next();
    }
}