<?php
$linhaObj->Set("id", intval($url[3]));
$linhaObj->Set("get", $_GET);

if ($url[5] == "matriculas") {
    echo $linhaObj->RetornarMatriculas();
} else if ($url[5] == "associar_matricula") {
    echo $linhaObj->BuscarMatricula();
}
?>