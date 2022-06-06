<?php
$linhaObj->Set("get",$_GET);
if($url[4] == "pesquisar_pessoas") {
  echo $linhaObj->BuscarMatricula();
}
?>