<?php
$linhaObj->Set("id",intval($url[3]));
$linhaObj->Set("get",$_GET);

if($url[5] == "ativar_desativar_arquivos_cursos") {
	echo $linhaObj->ativarDesativarArquivosCursos($_POST["curriculo"], $_POST["arquivo"]);
} elseif($url[5] == "associar_tipos_notas") {
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("get",$_GET);
  echo $linhaObj->BuscarTiposNotas();
  exit;
}
?>