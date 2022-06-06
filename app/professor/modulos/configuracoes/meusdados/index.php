<?php
include("../classes/professores.class.php");
include("config.php");

$linhaObj = new Professores();
$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_POST["acao"] == "salvar") {

  if(senhaSegura($_POST["senha_antiga"], $config["chaveLogin"]) == $usu_professor["senha"]) {
	$_POST["idprofessor"] = $usu_professor["idprofessor"];
	$_POST["email_antigo"] = $usu_professor["email"];
	$linhaObj->Set("onde","P"); //Informa de que modulo foi feita a modificação dos dadps da pessoa 
	$linhaObj->Set("idsolicita_alteracao",$usu_professor["idprofessor"]);// Id do usuário de fez a modificação
	$linhaObj->Set("post", $_POST);
	$salvar = $linhaObj->Modificar();
	
	if($salvar["sucesso"]){	
	  if($salvar["validacao"]) {
		$linhaObj->Set("pro_mensagem_idioma","modificar_validacao_sucesso");
	  } else {
		$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  }
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
	  $linhaObj->Processando();
	}
  } else {
	$salvar["sucesso"] = false;
	$salvar["erros"][] = "senha_antiga_invalida";
  }
}

if($url[2] == "meusdados") {
	$linhaObj->Set("id",intval($usu_professor["idprofessor"]));
	$linhaObj->Set("campos","p.*");
	$linha = $linhaObj->Retornar();

	if(is_array($linha)) $linha = array_map(stripslashes,$linha);
	
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
} else {
	header("Location: /".$url[0]."/".$url[1]);
	exit();
}
?>