<?php 
if($url[4] == "subassunto") {
	include("../classes/atendimentos.class.php");
	include("../classes/assuntosatendimentos.class.php");
	
	$linhaObjAssunto = new Assuntos_Atendimentos();	
	$linhaObj = new Atendimentos();
	
	$dados = $linhaObj->RetornarSubassuntos(intval($_GET["idassunto"]), false);

	$linhaObjAssunto->Set("id",intval($_GET["idassunto"]));
	$linhaObjAssunto->Set("campos","subassunto_obrigatorio, reserva_obrigatoria");
	$assunto = $linhaObjAssunto->RetornarAssunto();
	
	$dadosJson = array();
	$dadosJson["subassunto"] = $dados;
	
	echo json_encode($dadosJson);	
} 
?>