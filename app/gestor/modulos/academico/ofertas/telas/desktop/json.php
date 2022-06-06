<?php
$linhaObj->Set("id",intval($url[3]));
$linhaObj->Set("get",$_GET);

if($url[5] == "associar_curso") {
  echo $linhaObj->BuscarCurso();
}elseif($url[7] == "associar_escolas") {
	echo $linhaObj->BuscarEscola();
}
?>