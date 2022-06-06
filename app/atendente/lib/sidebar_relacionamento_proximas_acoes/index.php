<?php
		include_once("../classes/relacionamentoscomerciais.class.php");		
		$linhaObj = new RelacionamentosComerciais();
        $linhaObj->Set("idvendedor",(int) $_SESSION['usu_vendedor_idvendedor']);
		$linhaObj->Set("ordem","DESC");
		$linhaObj->Set("limite",7);
		$linhaObj->Set("campos","rc.*, rcm.*");
		$proximasAcoesArray = $linhaObj->ListarProximas();

		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
?>