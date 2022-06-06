<?php
class FileSystem
{
    private function __construct(){}

    public static function getBasePath($path = null)
    {
        return realpath(dirname(__FILE__).'/..') . $path;
    }
}