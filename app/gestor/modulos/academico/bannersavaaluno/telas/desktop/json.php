<?php

switch ($url[5]) {

    case "associar_sindicatos":
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("get", $_GET);
        echo $linhaObj->BuscarSindicatos();
        break;

    case "associar_escolas":
        echo $linhaObj->BuscarEscola();
        break;

    case "associar_cursos":
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("get", $_GET);
        echo $linhaObj->BuscarCursos();
        break;
}
