<?php
$linhaObj->Set('id',intval($url[3]));
$linhaObj->Set('get',$_GET);

if ($url[5] == 'associar_area') {
    echo $linhaObj->BuscarArea();
} elseif ($url[5] == 'associar_sindicato') {
    echo $linhaObj->BuscarSindicato();
}
