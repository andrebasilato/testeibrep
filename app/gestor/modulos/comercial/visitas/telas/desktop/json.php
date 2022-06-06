<?php
$linhaObj->Set("id",intval($url[3]));
$linhaObj->Set("get",$_GET);

if($url[5] == "cursos") {
  echo $linhaObj->RetornarCursosVendedor($url[6]);
} else if($url[5] == "pessoas") {
	echo $linhaObj->BuscarPessoa();
}
?>