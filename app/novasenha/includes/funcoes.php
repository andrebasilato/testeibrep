<?php	
function incluirLib($lib,$config,$informacoes = null){
	$url = $GLOBALS["url"];
	$lib = "lib/".$lib."/index.php";
	if(file_exists($lib)){
		include($lib);
	} else {
		echo "<strong>Erro ao tentar incluir a LIB, verifique o código.</strong> <br> LIB: $lib";
	}
}

function incluirTela($tela,$config,$informacoes = null){
	$url = $GLOBALS["url"];
	$root = $_SERVER['DOCUMENT_ROOT']."/novasenha/modulos/".$url[1]."/".$url[2];
	$tela_arquivo = $root."/telas/".$config["tela_padrao"]."/".$tela.".php";
	$tela_idioma = $root."/idiomas/".$config["idioma_padrao"]."/".$tela.".php";
	if(file_exists($tela_arquivo) && file_exists($tela_idioma)){
		include($tela_idioma);
		include($tela_arquivo);
	} else {
		echo "<strong>Erro ao tentar incluir a TELA, verifique o código.</strong> <br> TELA: $tela_arquivo <br> Idioma: $tela_idioma";
	}
}
?>