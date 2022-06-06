<?php
$linhaObj->Set("id", intval($url[3]));
$linhaObj->Set("get", $_GET);

if ($url[5] == "associar_fornecedores") {
    echo $linhaObj->BuscarFornecedores();
    exit;
}
?>