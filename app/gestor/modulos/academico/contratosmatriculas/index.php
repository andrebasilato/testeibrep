<?php
include("config.php");
include("config.listagem.php");

include("../classes/contratosmatriculas.class.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Contratos_Matriculas();
$linhaObj->Set("modulo",$url[0]);
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

if($_POST["acao"] == "validar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  $matricula = $linhaObj->visualizacoesWorkflow($_POST["idmatricula"]);
  if($matricula["situacao"]["visualizacoes"][13]) {
    $adicionar = $linhaObj->validarContrato($_POST["idmatricula"],$_POST["idmatricula_contrato"],$_POST["situacao"]);
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
    $linhaObj->Set("ancora","contratosmatricula");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "cancelar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
  $matricula = $linhaObj->visualizacoesWorkflow($_POST["idmatricula"]);
  if($matricula["situacao"]["visualizacoes"][14]) {
    $adicionar = $linhaObj->cancelarContrato($_POST["idmatricula"],$_POST["situacao"],$_POST["justificativa"],$_POST["idmatricula_contrato"]);
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]);
    $linhaObj->Set("ancora","contratosmatricula");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
}

if($url[3]) {
    switch ($url[3]) {
		case "cancelarcontrato":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
          include("idiomas/".$config["idioma_padrao"]."/cancelar.contrato.php");
          include("telas/".$config["tela_padrao"]."/cancelar.contrato.php");
          exit;
        break;
    }
}
	
$idSituacaoCancelada = $linhaObj->retornarIdSituacaoCancelada();
$idSituacaoFim = $linhaObj->retornarIdSituacaoFim();
$idSituacaoInativa = $linhaObj->retornarIdSituacaoInativa();
	
$linhaObj->Set("pagina",$_GET["pag"]);
if(!$_GET["ord"]) $_GET["ord"] = "desc";
$linhaObj->Set("ordem",$_GET["ord"]);
if(!$_GET["qtd"]) $_GET["qtd"] = 30;
$linhaObj->Set("limite",intval($_GET["qtd"]));
if(!$_GET["cmp"]) $_GET["cmp"] = "mc.data_cad DESC, ma.idmatricula";
$linhaObj->Set("ordem_campo",$_GET["cmp"]);
$linhaObj->Set("campos","ma.idmatricula,
						ma.idpessoa,
						ma.idsituacao,
						mw.nome as situacao_nome,
						mw.cor_bg,
						mw.cor_nome,
						i.nome_abreviado as sindicato,
						po.nome_fantasia as escola,
						cu.nome as curso,
						pe.nome as aluno,
            ve.nome as vendedor,
						mc.idmatricula_contrato,
						mc.idcontrato,
						mc.arquivo,
						mc.assinado,
						mc.assinado_devedor,
						mc.validado,
						mc.nao_validado,
						mc.cancelado,
						mc.justificativa,
						co.nome as contrato");
$dadosArray = $linhaObj->ListarTodas();
include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>