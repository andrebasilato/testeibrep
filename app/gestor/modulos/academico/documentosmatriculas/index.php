<?php
include("config.php");
include("config.listagem.php");

include("../classes/documentosmatriculas.class.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Documentos_Matriculas();
$linhaObj->Set("modulo",$url[0]);
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

if($_POST["acao"] == "aprovar_documento") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

	$linhaObj->Set("id", intval($_POST["idmatricula"]));
	$linhaObj->Set("iddocumento", intval($_POST["iddocumento"]));
	$linhaObj->Set("post", $_POST);
	$adicionar = $linhaObj->aprovarDocumento();

	if ($adicionar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
		$linhaObj->Processando();
	} else {
		$mensagem["erro"] = $adicionar["mensagem"];
	}
}

if($url[3]) {
    switch ($url[3]) {
		case "validardocumento":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

			$linhaObj->Set("id", intval($url[5]));
			$linhaObj->Set("iddocumento", intval($url[4]));
			$documento = $linhaObj->retornarDocumento();
			include("idiomas/".$config["idioma_padrao"]."/administrar.validar.documento.php");
			include("telas/".$config["tela_padrao"]."/administrar.validar.documento.php");
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
if(!$_GET["cmp"]) $_GET["cmp"] = "ma.idmatricula";
$linhaObj->Set("ordem_campo",$_GET["cmp"]);
$linhaObj->Set("campos","ma.idmatricula,
						ma.idpessoa,
						ma.idsituacao,
						md.data_cad,
						mw.nome as situacao_nome,
						mw.cor_bg,
						mw.cor_nome,
						of.nome as oferta,
						cu.nome as curso,
						po.nome_fantasia as escola,
						pe.nome as aluno,
						md.arquivo_nome,
						td.nome as tipo,
						md.arquivo_tipo,
						md.iddocumento,
						md.situacao as situacao_documento");
$dadosArray = $linhaObj->ListarTodas();
include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>