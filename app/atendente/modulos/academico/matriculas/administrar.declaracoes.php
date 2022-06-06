<?php

if ($_POST["acao"] == "gerar_declaracao") {

    if($matricula["situacao"]["visualizacoes"][19]) {

	   $matriculaObj->Set("id", $matricula["idmatricula"]);
	   $declaracaoAux = explode("|",$_POST["iddeclaracao"]);

	   if($declaracaoAux[0] == "declaracao"){

	       $_POST["iddeclaracao"] = (int) $declaracaoAux[1];
	       $matriculaObj->Set("post",$_POST);
	       include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.declaracao.php");

	       $gerar = $matriculaObj->gerarDeclaracao($idioma);

	   } else {

	       include("../classes/gruposdeclaracoes.class.php");
	       $gruposdeclaracaoObj = new Grupos_declaracaos();
	       $_POST["idgrupo"] = intval($declaracaoAux[1]);
	       $declaracaos = $gruposdeclaracaoObj->ListardeclaracoesGrupo($_POST["idgrupo"]);
	       foreach($declaracoes as $declaracao){
		      $_POST["iddeclaracao"] = $declaracao["iddeclaracao"];
		      $matriculaObj->Set("post",$_POST);
		      $gerar = $matriculaObj->gerarDeclaracao();
	       }

	   }
    } else {
	   $gerar["sucesso"] = false;
	   $gerar["mensagem"] = "mensagem_permissao_workflow";;
    }

    if($gerar["sucesso"]){
	   $matriculaObj->Set("pro_mensagem_idioma",$gerar["mensagem"]);
	   $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
	   $matriculaObj->Set("ancora","declaracoesmatricula");
	   $matriculaObj->Processando();
    } else {
	   $mensagem["erro"] = $gerar["mensagem"];
    }
} elseif($_POST["acao"] == "alterar_visibilidade_declaracao") {
      $salvar = $matriculaObj->alterarVisibilidadeDeclaracao($_POST['idmatriculadeclaracao'], $_POST['situacao_alteracao']);
      if($salvar){
        $matriculaObj->Set("pro_mensagem_idioma","visibilidade_declaracao_alterada_sucesso");
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","declaracoesmatricula");
        $matriculaObj->Processando();
      } else {
        $mensagem["erro"] = $salvar["mensagem"];
      }
}

if($url[6]) {
    switch ($url[6]) {
		case "gerardeclaracao":
			  include("../classes/declaracoes.class.php");
			  $matriculaObj->Set("ordem","asc");
			  $matriculaObj->Set("limite",-1);
			  $matriculaObj->Set("ordem_campo","nome");
			  $matriculaObj->Set("campos","d.iddeclaracao, d.nome, 'declaracao' as tipo");
			  $matriculaObj->Set("campos_2","gd.idgrupo, gd.nome, 'grupo' as tipo");
			  $declaracoes = $matriculaObj->RetornarDeclaracoesGrupos();

			  include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.declaracao.php");
			  include("telas/".$config["tela_padrao"]."/administrar.gerar.declaracao.php");
			  exit;
			break;
		case "declaracao":
		  $matriculaObj->Set("id",$matricula["idmatricula"]);
		  $declaracao = $matriculaObj->retornarDeclaracao(intval($url[7]));
		  include("idiomas/".$config["idioma_padrao"]."/administrar.declaracao.php");
		  include("telas/".$config["tela_padrao"]."/administrar.declaracao.php");
		  exit;
		break;
		case "declaracaodownload":
		  $matriculaObj->Set("id",$matricula["idmatricula"]);
		  $declaracao = $matriculaObj->retornarDeclaracao(intval($url[7]));
		  include("telas/".$config["tela_padrao"]."/administrar.download.declaracao.php");
		  exit;
		break;
		case "declaracaopdf":
		  $matriculaObj->Set("id",$matricula["idmatricula"]);
		  $declaracao = $matriculaObj->retornarDeclaracao(intval($url[7]));
		  //$data_matricula = new DateTime($matricula['data_cad']);
		  $arquivo = "/storage/matriculas_declaracoes/" . $declaracao['arquivo_pasta'] . "/" . $declaracao["idmatricula"]."/".$declaracao["idmatriculadeclaracao"].".html";
		  $arquivoServidor = $_SERVER["DOCUMENT_ROOT"].$arquivo;
		  if(file_exists($arquivoServidor)) {
			$saida = file_get_contents($arquivoServidor);
		  }

		  include("../classes/declaracoes.class.php");
		  $declaracaoObj = new Declaracoes();
		  $declaracaoObj->Set("id",$declaracao["iddeclaracao"]);
		  $declaracaoObj->Set("campos","*");
		  $declaracaoBackground = $declaracaoObj->Retornar();

		  include("../assets/plugins/MPDF54/mpdf.php");
		  $marginLeft = $declaracaoBackground["margem_left"] * 10;
		  $marginRight = $declaracaoBackground["margem_right"] * 10;
		  $marginHeader = $declaracaoBackground["margem_top"] * 10;
		  $marginFooter = $declaracaoBackground["margem_bottom"] * 10;

		  $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
		  $mpdf->ignore_invalid_utf8 = true;
		  $mpdf->simpleTables = true;
		  $mpdf->SetFooter('{PAGENO}');
		  if($declaracaoBackground["background_servidor"]) {
			$css = "body{font-family:Arial;background:url(../storage/declaracoes_background/".$declaracaoBackground["background_servidor"].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
			$mpdf->WriteHTML($css,1);
		  }

		  $mpdf->defaultfooterline = 0;
		  $mpdf->WriteHTML($saida);
		  $arquivoNome = "../storage/temp/".$declaracao["idmatriculadeclaracao"].".pdf";
		  $mpdf->Output($arquivoNome,"F");

		  header('Content-type: application/pdf');
		  readfile($arquivoNome);
		  exit;
		break;
	}
}

$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();

$matricula["declaracoes"] = $matriculaObj->RetornarDeclaracoes();

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

require("../classes/declaracoes.class.php");
$matriculaObj->Set("ordem","asc");
$matriculaObj->Set("limite",-1);
$matriculaObj->Set("ordem_campo","nome");
$matriculaObj->Set("campos","d.iddeclaracao, d.nome, 'declaracao' as tipo");
$matriculaObj->Set("campos_2","gd.idgrupo, gd.nome, 'grupo' as tipo");
$declaracoes = $matriculaObj->RetornarDeclaracoesGrupos();

include("idiomas/".$config["idioma_padrao"]."/administrar.declaracoes.php");
include("telas/".$config["tela_padrao"]."/administrar.declaracoes.php");
	
?>