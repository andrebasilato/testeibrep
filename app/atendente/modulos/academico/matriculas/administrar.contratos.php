<?php

if($_POST["acao"] == "gerar_contrato") {
   if($matricula["situacao"]["visualizacoes"][11]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $contratoAux = explode("|",$_POST["idcontrato"]);
        if($contratoAux[0] == "contrato"){
            $_POST["idcontrato"] = intval($contratoAux[1]);
            $matriculaObj->Set("post",$_POST);
            include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.contrato.php");
            $gerar = $matriculaObj->gerarContrato($idioma);
        } else {
            include("../classes/gruposcontratos.class.php");
            $gruposContratoObj = new Grupos_Contratos();
            $_POST["idgrupo"] = intval($contratoAux[1]);
            $contratos = $gruposContratoObj->ListarContratosGrupo($_POST["idgrupo"]);
            foreach($contratos as $contrato){
                $_POST["idcontrato"] = $contrato["idcontrato"];
                $matriculaObj->Set("post",$_POST);
                $gerar = $matriculaObj->gerarContrato();
            }
        }

        if ($gerar["sucesso"]) {
            $matriculaObj->alterarSituacaoContratosAceitos((int)$matricula['idmatricula'],true);
            $matriculaObj->Set("pro_mensagem_idioma",$gerar["mensagem"]);
            $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $matriculaObj->Set("ancora","contratosmatricula");
            $matriculaObj->Processando();
        } else {
            $mensagem["erro"] = $gerar["mensagem"];
        }

    } else {
        $gerar["sucesso"] = false;
        $gerar["mensagem"] = "mensagem_permissao_workflow";;
    }
} elseif($_POST["acao"] == "enviar_contrato") {
  if($matricula["situacao"]["visualizacoes"][11]) {
    $matriculaObj->Set("id", $matricula["idmatricula"]);
    $matriculaObj->Set("post", $_POST);
    $adicionar = $matriculaObj->enviarContrato();
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","contratosmatricula");
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "validar_contrato") {

  if($matricula["situacao"]["visualizacoes"][13]) {
    $adicionar = $matriculaObj->validarContrato($_POST["idmatricula_contrato"],$_POST["situacao"]);
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","contratosmatricula");
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "assinar_contrato") {

  if($matricula["situacao"]["visualizacoes"][12]) {
    $adicionar = $matriculaObj->assinarContrato($_POST["idmatricula_contrato"],$_POST["situacao"]);
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","contratosmatricula");
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "cancelar_contrato") {

  if($matricula["situacao"]["visualizacoes"][14]) {
    $adicionar = $matriculaObj->cancelarContrato($_POST["situacao"],$_POST["justificativa"],$_POST["idmatricula_contrato"]);
  } else {
    $adicionar["sucesso"] = false;
    $adicionar["mensagem"] = "mensagem_permissao_workflow";;
  }
  if($adicionar["sucesso"]){
    $matriculaObj->alterarSituacaoContratosAceitos((int)$matricula['idmatricula']);
    $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","contratosmatricula");
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "reenviar_email") {

  $matriculaObj->Set("id", $matricula["idmatricula"]);
  $reenviar = $matriculaObj->reenviarEmailContrato();

  if($reenviar){
    $matriculaObj->Set("pro_mensagem_idioma",'reenviar_email_contrato_sucesso');
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = 'reenviar_email_contrato_erro';
  }
}

if($url[6]) {
    switch ($url[6]) {
        case "gerarcontrato":

          include("../classes/contratos.class.php");
          $matriculaObj->Set("ordem","asc");
          $matriculaObj->Set("limite",-1);
          $matriculaObj->Set("ordem_campo","nome");
          $matriculaObj->Set("campos","c.idcontrato, c.nome, 'contrato' as tipo");
          $matriculaObj->Set("campos_2","gc.idgrupo, gc.nome, 'grupo' as tipo");
          $contratos = $matriculaObj->RetornarContratosGrupos();

          include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.gerar.contrato.php");
          exit;
        break;
        case "cancelarcontrato":

          include("idiomas/".$config["idioma_padrao"]."/administrar.cancelar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.cancelar.contrato.php");
          exit;
        break;
        case "contrato":
          $matriculaObj->Set("id",$matricula["idmatricula"]);
          $contrato = $matriculaObj->retornarContrato(intval($url[7]));

          include("idiomas/".$config["idioma_padrao"]."/administrar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.contrato.php");
          exit;
        break;
        case "contratodownload":
              $matriculaObj->Set("id",$matricula["idmatricula"]);
              $contrato = $matriculaObj->retornarContrato(intval($url[7]));

              include("telas/".$config["tela_padrao"]."/administrar.download.contrato.php");
              exit;
            break;
        case "contratopdf":
          $matriculaObj->Set("id",$matricula["idmatricula"]);
          $contrato = $matriculaObj->retornarContrato(intval($url[7]));
          //$data_matricula = new DateTime($matricula['data_cad']);
          $arquivo = "/storage/matriculas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idmatricula"]."/".$contrato["idmatricula_contrato"].".html";
          $arquivoServidor = $_SERVER["DOCUMENT_ROOT"].$arquivo;
          if(file_exists($arquivoServidor)) {
            $saida = file_get_contents($arquivoServidor);
          }

          include("../classes/contratos.class.php");
          $contratoObj = new Contratos();
          $contratoObj->Set("id",$contrato["idcontrato"]);
          $contratoObj->Set("campos","*");
          $contratoBackground = $contratoObj->Retornar();

          include("../assets/plugins/MPDF54/mpdf.php");
          $marginLeft = $contratoBackground["margem_left"] * 10;
          $marginRight = $contratoBackground["margem_right"] * 10;
          $marginHeader = $contratoBackground["margem_top"] * 10;
          $marginFooter = $contratoBackground["margem_bottom"] * 10;

          $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
          $mpdf->ignore_invalid_utf8 = true;
          $mpdf->simpleTables = true;
          $mpdf->SetFooter('{PAGENO}');
          if($contratoBackground["background_servidor"]) {
            $css = "body{font-family:Arial;background:url(../storage/contratos_background/".$contratoBackground["background_servidor"].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
            $mpdf->WriteHTML($css,1);
          }

          $mpdf->defaultfooterline = 0;
          $mpdf->WriteHTML($saida);
          $arquivoNome = "../storage/temp/".$contrato["idmatricula_contrato"].".pdf";
          $mpdf->Output($arquivoNome,"F");

          header('Content-type: application/pdf');
          readfile($arquivoNome);
          exit;
        break;
    }
}

$matricula["contratos"] = $matriculaObj->RetornarContratos();

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

require("../classes/tiposcontratos.class.php");
$tiposContratosObj = new Tipos_Contratos();
$tiposContratosObj->Set("campos","*");
$tiposContratosObj->Set("ordem","asc");
$tiposContratosObj->Set("ordem_campo","nome");
$tiposContratosObj->Set("limite","-1");
$tiposContratos = $tiposContratosObj->ListarTodas();

$contratosObj = new Contratos();
$contratos = $contratosObj->retornarContratosSindicato($matricula['idsindicato']);

include("idiomas/".$config["idioma_padrao"]."/administrar.contratos.php");
include("telas/".$config["tela_padrao"]."/administrar.contratos.php");
