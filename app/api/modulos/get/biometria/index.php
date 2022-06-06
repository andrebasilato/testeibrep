<?php

header('Content-Type: application/json');

$retorno                  = array();
$retorno["sucesso"]       = false;
$retorno["erro"]          = true;
$retorno["erro_mensagem"] = "Erro desconhecido";

include("login.php");

switch ($url[3]) {
    case "login":
        echo json_encode($retorno);
        exit();  
    case "aluno":
        include("aluno.php");
        break;
    default:
       $retorno["erro_mensagem"] = "Opção inválida";
       echo json_encode($retorno);
}