<?php
include("../classes/turmas.class.php");
include("config.php");
include("config.listagem.php");
	
	
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
$linhaObj = new Turmas();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);




if(isset($url[3])){	

	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","*");	
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
	
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $campos = "ot.*, o.nome as oferta, o.data_cad as data_cad_oferta";
  $campos .= ", (select count(idmatricula) from matriculas m where m.idoferta = o.idoferta and m.idturma = ot.idturma ) as matriculas";  
  $linhaObj->Set("campos",$campos);	
  $dadosArray = $linhaObj->ListarTodas();		
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
?>