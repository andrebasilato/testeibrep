<?php
include("../classes/relacionamentopedagogico.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
	
	
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
$linhaObj = new RelacionamentoPedagogico();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	

//include("../classes/pessoas.class.php");
$pessoaObj = new Pessoas();
$pessoaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

//$vendedorObj = new Vendedores();
//$vendedorObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
	
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
} elseif($_POST["acao"] == "salvar_mensagem") {
	if($_POST["mensagem"] && $_POST["proxima_acao"]) {
	  $linhaObj->Set("post",$_POST);
	  $salvar = $linhaObj->adicionarMensagem();
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","mensagem_adicionada_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
		$linhaObj->Processando();
	  } else {	
		$mensagem["erro"] = $salvar["mensagem"];
	  }		
	} else {
	  $salvar["sucesso"] = false;
	  $salvar["erros"][] = "mensagem_vazia";
	}
} elseif($_POST["acao"] == "remover_mensagem") {
	if($_POST["idmensagem"]) {
	  $remover = $linhaObj->removerMensagem(intval($_POST["idmensagem"]));
	  if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
		$linhaObj->Processando();
	  } else {	
		$mensagem["erro"] = $remover["mensagem"];
	  }
	} else {
	  $mensagem["erro"] = "mensagem_remover_vazio";
	}
}	

if(isset($url[3])){	
	if(isset($url[4])){
		if($url[4]=='descricao'){
			$_GET["idmensagem"] = $url[3];
			$linhaObj->Set("campos","c.mensagem");
			$mensagem = $linhaObj->ListarTodas();
			include("telas/".$config["tela_padrao"]."/descricao.php");
			exit();
		}
	}
	switch ($url[3]) {
	  case "json":
		include("telas/".$config["tela_padrao"]."/json.php");
	  break;
	  case "administrar":
	  	//$vendedorObj->Set("campos","idvendedor, nome");
	  	//$vendedores = $vendedorObj->ListarTodas();
	  	$pessoaObj->Set("campos","p.*, pa.nome as pais");
	  	$pessoaObj->Set("id",$url[4]);
	  	$dadospessoais = $pessoaObj->Retornar();
		$linhaObj->Set("campos","rp.*, p.nome as pessoa, ua.nome as usuario, 
		ua.idusuario");
		$_GET["idpessoa"] = $url[4];
		$mensagensPessoa = $linhaObj->ListarTodas();
		include("idiomas/".$config["idioma_padrao"]."/administrar.php");
		include("telas/".$config["tela_padrao"]."/administrar.php");
	  break;		
	  default:

	  	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		$linhaObj->Set("pagina",$_GET["pag"]);
		if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
		$linhaObj->Set("ordem",$_GET["ord"]);
		if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		$linhaObj->Set("limite",intval($_GET["qtd"]));
		if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","rp.*, p.nome as pessoa, ua.nome as usuario");
		$linhaObj->Set("id",intval($url[3]));
		$_GET["todas"] = 1;
		$mensagensArray = $linhaObj->ListarTodas();
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	  exit();
	}
} else {

	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	$linhaObj->Set("pagina",$_GET["pag"]);
	if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
	$linhaObj->Set("ordem",$_GET["ord"]);
	if(!$_GET["qtd"]) $_GET["qtd"] = 30;
	$linhaObj->Set("limite",intval($_GET["qtd"]));
	if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
	$linhaObj->Set("ordem_campo",$_GET["cmp"]);
	$linhaObj->Set("campos","rp.*, p.nome as pessoa, ua.nome as usuario");
	$linhaObj->Set("id",intval($url[3]));
	$_GET["todas"] = 1;
	$mensagensArray = $linhaObj->ListarTodas();
	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
	exit();
}
?>