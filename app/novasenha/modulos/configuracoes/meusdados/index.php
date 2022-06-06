<?php
include("../classes/novasenha.class.php");
include("config.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

if($url[3] == 'cfc'){
    $url[3] = 'escola';
}

$nome = ', nome';
if ($url[3] == 'escola') {
	$nome = ', nome_fantasia AS nome';
}

$linhaObj = new NovaSenha();
$linhaObj->Set("modulo",$url[3]);
$linhaObj->Set("id",$url[4]);
$linhaObj->Set("hash",$url[5]);
$linhaObj->Set("campos","ss.*" . $nome);	
$linha = $linhaObj->Retornar();
$horas = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");

if($_POST["acao"] == "salvar") {
  if($linha["idsolicitacao_senha"] && $linha["ativo"] == "S" && !$linha["data_modificacao"] && $horas <= 6) { 
	$linhaObj->Set("post",$_POST);
	$modificou = $linhaObj->alterarSenha();
  
	if($modificou){
	  $_POST["msg"] = "modificar_senha_sucesso";
	}
  }
}

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>