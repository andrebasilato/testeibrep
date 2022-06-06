<?php
include("../classes/empresas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
	
	
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
$linhaObj = new Empresas();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


if($_POST["acao"] == "salvar"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  $linhaObj->Set("post",$_POST);
  if($_POST[$config["banco"]["primaria"]]) 
	$salvar = $linhaObj->Modificar();
  else 
	$salvar = $linhaObj->Cadastrar();
  if($salvar["sucesso"]){
	if($_POST[$config["banco"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->Remover();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
	$linhaObj->Processando();
  }
}		

if(isset($url[3])){
  if($url[4] == "ajax_cidades"){
	if($_REQUEST['idestado']) {
	  $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
	} else { 
	  $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
	}
	exit;
  } elseif($url[3] == "cadastrar") {
	if($url[4] == "json") {
	  include("idiomas/".$config["idioma_padrao"]."/json.php");
	  include("telas/".$config["tela_padrao"]."/json.php");
	  exit;
	} else {
	  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	  include("idiomas/".$config["idioma_padrao"]."/formulario.php");
	  include("telas/".$config["tela_padrao"]."/formulario.php");
	  exit();
	}
  } else {
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","e.*, i.nome as sindicato");	
	$linha = $linhaObj->Retornar();
			
	if($linha) {				
	  switch ($url[4]) {
		case "editar":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.php");
		  include("telas/".$config["tela_padrao"]."/formulario.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		  include("idiomas/".$config["idioma_padrao"]."/remover.php");
		  include("telas/".$config["tela_padrao"]."/remover.php");
		break;
		case "opcoes":			
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.php");
		break;
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
		  exit();
	  }	
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
	  exit();
	}			
  }
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","e.*, i.nome as sindicato");	
  $dadosArray = $linhaObj->ListarTodas();		
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
?>