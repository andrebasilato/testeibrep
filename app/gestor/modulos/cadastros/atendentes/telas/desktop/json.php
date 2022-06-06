<?php
if ($url[5] == "desativar_login") {
    echo $linhaObj->AtivarLogin($_POST["ativo_login"]);
    exit;
} elseif ($url[5] == "bloquear_vendas") {
    echo $linhaObj->BloquearVendas($_POST["ativo_login"]);
    exit;
} elseif ($url[5] == "resetar_senha") {
    echo $linhaObj->ResetarSenha($_POST["confirmacao"], $_POST["enviar_email"], $_POST["exibir_nova_senha"]);
    exit;
} elseif ($url[5] == "associar_sindicatos") {
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("get", $_GET);
    echo $linhaObj->BuscarSindicatos();
    exit;
} elseif ($url[5] == "associar_cfc") {
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("get", $_GET);
    echo $linhaObj->BuscarEscolas();
    exit;
}
?>