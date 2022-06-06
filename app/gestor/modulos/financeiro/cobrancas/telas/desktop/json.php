<?php
$linhaObj->Set("get", $_GET);
if ($url[4] == "pesquisar_matriculas") {
    echo $linhaObj->BuscarMatricula();
}
?>