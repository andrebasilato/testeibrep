<?php
$linhaObj->Set("id",(int)$url[3]);
$linhaObj->Set("get", $_GET);

if($url[5] == "curso") {
	echo $linhaObj->RetornarCursos((int) $_GET["idoferta"], true);
} elseif($url[5] == "pessoas") {
    echo $linhaObj->BuscarPessoas();
    exit;
} elseif($url[5] == "matriculas") {
    echo $linhaObj->BuscarMatriculas();
    exit;
}
?>