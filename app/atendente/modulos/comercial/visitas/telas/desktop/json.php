<?php
$linhaObj->Set("id",intval($url[3]));
$linhaObj->Set("get",$_GET);

if($url[5] == "cursos") {
  echo $linhaObj->RetornarCursosVendedor($usu_vendedor['idvendedor']);
} elseif($url[5] == "pessoas") {
  echo $linhaObj->BuscarPessoa();
}
?>