<?php
$linhaObj->Set("id",intval($url[3]));
$linhaObj->Set("get",$_GET);

if($url[5] == "curso") {
	echo $linhaObj->RetornarCursos(intval($_GET["idoferta"]), true);
} elseif($url[5] == "sindicato") {
	echo $linhaObj->RetornarSindicatos(intval($_GET["idcurso"]), intval($_GET["idoferta"]), true);
}
?>