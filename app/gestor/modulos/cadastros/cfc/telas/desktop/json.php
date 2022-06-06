<?php

switch($url[5]){
    case 'bloquear':
        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("idusuario",$usuario['idusuario']);
        $retorno = $linhaObj->bloquear($_POST['bloquear']);
        break;
    case 'bloquear_historico':
        $linhaObj->Set("id",intval($url[3]));
        $retorno = $linhaObj->retornarHistoricoBloqueado();
        break;
    case 'associar_contratos':
        $linhaObj->Set("id",intval($url[3]));
        $linhaObj->Set("get",$_GET);
        $retorno = $linhaObj->BuscarContratos();
        break;
    case 'associar_estados_cidades':
        $linhaObj->set('id', (int) $url[3]);
        $linhaObj->set('get', $_GET);
        $retorno = $linhaObj->buscarEstadosCidades();
        break;
}

echo json_encode($retorno);
