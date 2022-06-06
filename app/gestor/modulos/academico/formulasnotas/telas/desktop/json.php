<?php
if($url[5] == "associar_sindicatos") {
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("get",$_GET);
  echo $linhaObj->BuscarSindicatos();
  exit;
}
?>