<?php
/**
 * Class Request
 */
class Request
{
    /**
     *
     * @param  string $resource Recurso HTTP a ser validado
     *
     * @return array|boolean false      Resultado do match na URL
     */
    public static function get($resource)
    {
        $resource = str_replace(array('/'), array('\\/'), $resource);
        $_SERVER['REQUEST_URI'] = str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        preg_match("/{$resource}/", $_SERVER['REQUEST_URI'], $match);
        return $match;
    }

    /**
     *
     * @param $variable
     *
     * @return boolean|mixed
     */
    public static function post($variable)
    {
        if (isset($_POST[$variable])) {
            return $_POST[$variable];
        }
        return false;
    }

    /**
     * @param $variable
     *
     * @return bool
     */
    public static function files($variable)
    {
        if (isset($_FILES[$variable])) {
            return $_FILES[$variable];
        }
        return false;
    }

    /**
     * @param $param
     * @param string $glue
     *
     * @return bool|string
     */
    public static function url($param, $glue = '')
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);

        if (false !== strpos($param, '-')) {
            $resource = '';
            $range = explode('-', $param);

            for (; $range[0] <= $range[1]; $range[0]++) {
                $resource .= $url[$range[0]] . $glue;
            }
            return $resource;
        }

        return isset($url[$param]) ? $url[$param] : false;
    }
}
