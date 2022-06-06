<?php

class RequisicaoJson
{
    private static function criarCabecalho()
    {
        header('Content-Type: application/json');
        header('Expires: 0');
        header('Pragma: no-cache');
    }

    public static function terminarComErro($status, $message)
    {
        static::criarCabecalho();
        header("HTTP/1.0 $status $message");
        return json_encode(['codigo' => $status, 'mensagem' => $message]);
    }

    public static function terminar($dados)
    {
        static::criarCabecalho();
        return json_encode($dados);
    }
}
