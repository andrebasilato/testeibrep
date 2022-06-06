<?php
		include_once("../classes/murais.class.php");		
		$linhaObj = new Murais();
		$linhaObj->Set("ordem","DESC");
		$linhaObj->Set("limite",5);
		$linhaObj->Set("ordem_campo",'m.idmural');
		$linhaObj->Set("campos","m.idmural, m.titulo, m.resumo, m.data_cad,mf.data_lido");
		$muralArray = $linhaObj->ListarTodasDisponiveis("idprofessor", $informacoes['idprofessor']);

		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
?>