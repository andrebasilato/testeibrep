<?php
if($url[5] == "associar_ofertas") {
	echo $linhaObj->BuscarOferta();
} else if($url[5] == "associar_escolas") {
	echo $linhaObj->BuscarEscola();
}else if($url[5] == "associar_cursos") {
	echo $linhaObj->BuscarCurso();
}
?>