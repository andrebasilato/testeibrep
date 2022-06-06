<?php
if ($url[5] == "associar_escolas") {
    echo $linhaObj->BuscarEscola();
} elseif($url[5] == "associar_cursos"){
	echo $linhaObj->BuscarCurso();
}
?>