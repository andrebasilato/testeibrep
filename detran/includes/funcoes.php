<?php
function incluirLib($lib, $config, $informacoes = null)
{
    $lib = dirname(__DIR__) . "/lib/" . $lib . "/index.php";
    if (file_exists($lib)) {
        include($lib);
    } else {
        echo '<strong>Erro ao tentar incluir a LIB, verifique o código.</strong> <br> LIB: ' . $lib;
    }
}

function salvarLogDetran($obj, $codTransacao, $idMatricula, $retorno, $stringEnvio = null)
{
    $sql = 'INSERT INTO detran_logs SET
        data_cad = NOW(),
        cod_transacao = "' . $codTransacao . '",
        idmatricula = ' . $idMatricula . ',
        retorno = "' . addslashes($retorno) . '"';

    if (!empty($stringEnvio)) {
        $sql .= ', string_envio = "' . addslashes($stringEnvio) . '"';
    }

    return $obj->executaSql($sql);
}