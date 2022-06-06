<?php
		include_once("../classes/murais.class.php");		
		$linhaObj = new Murais();
		$linhaObj->Set("ordem","DESC");
		$linhaObj->Set("limite",5);
		$linhaObj->Set("ordem_campo",'m.idmural');
		$linhaObj->Set("campos","*");
		$muralArray = $linhaObj->ListarTodasDisponiveis("idusuario_adm", $informacoes["idusuario"]);


		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
?>