<?php

$retorno = [];
switch ($url[4]) {
    case 'contas_correntes':
        #$retorno = $linhaObj->();
        #echo json_encode($retorno);
        if (empty($_GET['idescolas'][0])) {
            unset($_GET['idescolas'][0]);
        }
        $retorno = $linhaObj->retornarContasCorrentes($_GET['idescolas']);
        break;
}

echo json_encode($retorno);