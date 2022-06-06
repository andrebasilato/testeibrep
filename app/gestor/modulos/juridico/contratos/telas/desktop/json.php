<?php
if ($url[5] == "associar_sindicatos") {
    echo $linhaObj->BuscarSindicato();
}elseif($url[5] == "associar_cursos") {
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("get",$_GET);
  echo $linhaObj->BuscarCursos();
  exit;
}