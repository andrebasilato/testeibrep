<?php
include("../classes/formulasnotas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
	
	
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
$linhaObj = new Formulas_Notas();
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
} elseif($_POST["acao"] == "validar"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $validar = $linhaObj->validarFormula();
  //if($validar){
	//$linhaObj->Set("pro_mensagem_idioma","validar_sucesso");
	//$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
	//$linhaObj->Processando();
  //}
} elseif($_POST["acao"] == "associar_sindicato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");		  
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->AssociarSindicato();
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","associar_sindicato_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_sindicato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");  
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->DesassociarSindicato();
		
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sindicato_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} 

if(isset($url[3])){	
  if($url[3] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	include("idiomas/".$config["idioma_padrao"]."/formulario.php");
	include("telas/".$config["tela_padrao"]."/formulario.php");
	exit();
  } else {
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","*");	
	$linha = $linhaObj->Retornar();
			
	if($linha) {				
	  switch ($url[4]) {
		case "editar":
		  include("../classes/tiposnotas.class.php");
		  $tiposNotasObj = new Tipos_Notas();
		  $linhaObj->Set("pagina",$_GET["pag"]);
		  $linhaObj->Set("limite", -1);
		  $linhaObj->Set("ordem_campo",'nome');
		  $linhaObj->Set("campos","*");	
		  $tipos_notas = $tiposNotasObj->ListarTodas();		  

		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.php");
		  include("telas/".$config["tela_padrao"]."/formulario.php");
		break;
		case "validar":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
		  include("idiomas/".$config["idioma_padrao"]."/validar.php");
		  include("telas/".$config["tela_padrao"]."/validar.php");
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
		case "sindicatos":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
		  $linhaObj->Set("id",intval($url[3]));
		  $linhaObj->Set("campos","*");	
		  $sindicatos = $linhaObj->ListarSindicatosAssociadas();
		  include("idiomas/".$config["idioma_padrao"]."/sindicatos.php");
		  include("telas/".$config["tela_padrao"]."/sindicatos.php");
		break;
		case "json":
		  include("telas/".$config["tela_padrao"]."/json.php");
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
  $linhaObj->Set("campos","*");	
  $dadosArray = $linhaObj->ListarTodas();		
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
?>