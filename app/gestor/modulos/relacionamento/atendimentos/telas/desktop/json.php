<?php
if($url[5] == "subassunto") {
	echo $linhaObj->RetornarSubassuntos(intval($_GET["idassunto"]), true);
} else if($url[5] == "avaliar") {
	echo $linhaObj->avaliar($_POST["avaliar"]);
} else if($url[5] == "resposta_automatica") {
	echo $linhaObj->listarRespostasAutomaticas($_POST['id']);
} else if($url[5] == "excluir_arquivo") {
	echo $linhaObj->ExcluirArquivo($_POST["idarquvio"], "atendimentos_arquivos");
}
?>