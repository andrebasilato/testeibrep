<?php
if($url[5] == "associar_cursos") {
	echo $linhaObj->BuscarCurso();
} elseif($url[5] == "associar_ofertas") {
	echo $linhaObj->BuscarOferta();
}
elseif($url[5] == "associar_sindicatos") {
	echo $linhaObj->BuscarSindicato();
}
?>