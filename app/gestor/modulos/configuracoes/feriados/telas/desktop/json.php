<?php
if ($url[5] == "associar_cidades") {
    echo $linhaObj->BuscarCidade();
} elseif ($url[5] == "associar_estados") {
    echo $linhaObj->BuscarEstado();
} elseif ($url[5] == "associar_escolas") {
    echo $linhaObj->BuscarEscola();
} elseif ($url[5] == "associar_sindicatos") {
    echo $linhaObj->BuscarSindicato();
}
?>