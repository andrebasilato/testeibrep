<?php
$linhaObj->Set("config",$config);				
	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);

if($_POST["acao"] == "salvar_turmas_sindicatos"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");		
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->salvarTurmasSindicatos();
  
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","salvar_turmas_sindicatos_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/turmas_sindicatos");
	$linhaObj->Processando();
  }
}

$dadosArray = $linhaObj->ListarTurmasSindicatos();

include("idiomas/".$config["idioma_padrao"]."/index.turmas.sindicatos.php");
include("telas/".$config["tela_padrao"]."/index.turmas.sindicatos.php");

?>