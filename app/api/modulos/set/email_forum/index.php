<?php
include '../classes/avas.class.php';
include '../classes/avas.foruns.class.php';

$forumObj = new Foruns();
if(!empty($_GET["tick"]) && strlen($_GET["tick"]) == 128){	
	$linha = $forumObj->RetornaDadosForumDesativar($_GET["tick"]);
	if($_POST["acao"] == "naoreceber"){
	  $salvar = $forumObj->DesabilitaEmailForum(intval($linha["idassinatura_mensagem"]));
	  
	  if($salvar["sucesso"]){
	  	$informacoes["msg"] = 1;
		$informacoes["url"] = "/".$url[0]."/".$url[1]."/email_forum?tick=".$_GET["tick"];	 
	  }else{
	  	$informacoes["erro"] = 2;
		$informacoes["url"] = "/".$url[0]."/".$url[1]."/email_forum?tick=".$_GET["tick"];
	  }	  
	  include("processando.php");	  
	}	
}
include("tela.php");
 

?>