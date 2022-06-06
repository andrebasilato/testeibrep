<?php
/**
 * Class to Response HTTP requests
 * This use Singleton pattern to create
 * the single instance of HTTP Fundation
 * 
 * You too can call this with a fluent interface
 * 
 * Use this like it:
 * 
 * <code>
 * $responseHeader = Response::getInstance();
 * 
 * $responseHeader->setStatusCode(200)
 *                ->setMessage('OK')
 *                ->send();
 * </code> 
 * 
 * @since 2.9
 * @author Jefersson Nathan
 * @package Alfama Oraculo
 * @copyright 2013 AlfamaWeb
 */ 
class Response
{
    /**
     * @var $instance object Response or null
     */ 
    protected static $instance = null;

    /**
     * @var $statusCode integer
     */ 
    private $statusCode;

    /**
     * @var $message String
     */ 
    private $message;

    /**
     * @var $contentType String
     */ 
    private $contentType;

    /**
     * Cannot instanciate it directly 
     */
    private function __construct(){}

    /**
     * @return a instance of Response class
     */ 
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance =  new self();
        }

        return self::$instance;
    }

    /**
     * Set Status code of response 
     * 
     * @param $code string
     * @return object self 
     */
    public function setStatusCode($code)
    {
        $this->statusCode = (int) $code;
        return $this;
    }

    /**
     * Configure the message of response
     * It's displayed with statuscode
     * 
     * @param $message string
     * @return object self 
     */ 
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set content-type header for response
     * 
     * @param $type string 
     * @return object self 
     */
    public function setContentType($type)
    {
        $this->contentType = $type;
        return $this;
    }

    /**
     * Send Header informations to proxy
     */ 
    public function send()
    {
        header("HTTP/1.1 {$this->statusCode} {$this->message}", true);

        if (null !== $this->contentType) {
            header("Content-Type: {$this->contentType}; charset=utf-8", true);
        }
    }
}