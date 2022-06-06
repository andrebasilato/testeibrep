<?php
if ($url[5] == "associar_cursos") {
    echo $linhaObj->BuscarCurso($url[3]);
} elseif ($url[5] == "associar_sindicatos") {
    echo $linhaObj->BuscarSindicato($url[3]);
} elseif ($url[5] == "associar_sindicatos_agendamento") {
    echo $linhaObj->BuscarSindicatoAgendamento($url[3]);
}