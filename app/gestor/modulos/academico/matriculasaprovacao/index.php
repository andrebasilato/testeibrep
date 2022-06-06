<?php
include("config.php");
include("config.listagem.php");

include("../classes/matriculasaprovacao.class.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Matriculas_Aprovacao();
$linhaObj->Set("modulo",$url[0]);
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

/*if($_POST["acao"] == "aprovarDocumento") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  $adicionar = $linhaObj->aprovarDocumento($_POST["iddocumento"],$_POST["idmatricula"]);
  if($adicionar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
	$linhaObj->Processando();
  } else {	
	$mensagem["erro"] = $adicionar["mensagem"];
  }	
}*/
if($_POST["acao"] == "aprovar"){ 
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	
	$linhaObj->Set("id",$_POST["validacao"]);
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->aprovarMatricula($url[3]);
		
	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma", $salvar["mensagem"]);
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
		$linhaObj->Processando();
	}
}

if(isset($url[3])){	

	/*$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","v.*, u.nome as usuario_validou, p.nome");	
	$linha = $linhaObj->RetornarValidacao();
	
	if($linha) {*/
		
		switch ($url[4]) {
			case "aprovar":
				$liberacao = $linhaObj->retornarDataLiberacaoAprovacao($url[3]);
			
				include("idiomas/".$config["idioma_padrao"]."/aprovar.php");
				include("telas/".$config["tela_padrao"]."/aprovar.php");
				break;		
			default:
			   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
			   exit();
		}
		
	/*} else {
	   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
	   exit();
	}*/

} else {
	
	$linhaObj->Set("pagina",$_GET["pag"]);
	if(!$_GET["ord"]) $_GET["ord"] = "desc";
	$linhaObj->Set("ordem",$_GET["ord"]);
	if(!$_GET["qtd"]) $_GET["qtd"] = 30;
	$linhaObj->Set("limite",intval($_GET["qtd"]));
	if(!$_GET["cmp"]) $_GET["cmp"] = "ma.idmatricula";
	$linhaObj->Set("ordem_campo",$_GET["cmp"]);
	$linhaObj->Set("campos","ma.*, mw.nome as situacao_nome, mw.cor_bg, mw.cor_nome, of.nome as oferta, cu.nome as curso, po.nome_fantasia as escola, pe.nome as aluno, i.nome_abreviado as sindicato_sigla");	
    $linhaObj->Set("gestor_sindicato",$usuario["gestor_sindicato"]);
	$dadosArray = $linhaObj->ListarTodas();	
	
	#echo getcwd(); - IDIOMA incluído para mostrar os erros do workflow
	include("/modulos/academico/matriculas/idiomas/".$config["idioma_padrao"]."/administrar.php");
	
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");

}
?>