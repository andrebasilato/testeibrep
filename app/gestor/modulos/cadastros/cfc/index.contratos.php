<?php

if($_POST["acao"] == "gerar_contrato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
    $linhaObj->Set("id", $linha["idescola"]);
    $contratoAux = explode("|",$_POST["idcontrato"]);
    if($contratoAux[0] == "contrato"){
        $_POST["idcontrato"] = intval($contratoAux[1]);
        $linhaObj->Set("post",$_POST);
        include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.contrato.php");
        $gerar = $linhaObj->gerarContrato($idioma);
    } else {
        include("../classes/gruposcontratos.class.php");
        $gruposContratoObj = new Grupos_Contratos();
        $_POST["idgrupo"] = intval($contratoAux[1]);
        $contratos = $gruposContratoObj->ListarContratosGrupo($_POST["idgrupo"]);
        foreach($contratos as $contrato){
            $_POST["idcontrato"] = $contrato["idcontrato"];
            $linhaObj->Set("post",$_POST);
            $gerar = $linhaObj->gerarContrato();
        }
    }

    if ($gerar["sucesso"]) {
        $linhaObj->alterarSituacaoContratosAceitos((int) $linha["idescola"], true);
        $linhaObj->Set("pro_mensagem_idioma",$gerar["mensagem"]);
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Set("ancora","contratosescola");
        $linhaObj->Processando();
    } else {
        $mensagem["erro"] = $gerar["mensagem"];
    }


} elseif($_POST["acao"] == "enviar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $linhaObj->Set("id", $linha["idescola"]);
  $linhaObj->Set("post", $_POST);
  $adicionar = $linhaObj->enviarContrato();

  if($adicionar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Set("ancora","contratosescola");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "validar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $adicionar = $linhaObj->validarContrato($_POST["idescola_contrato"],$_POST["situacao"]);

  if($adicionar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $linhaObj->Set("ancora","contratosescola");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "assinar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $adicionar = $linhaObj->assinarContrato($_POST["idescola_contrato"],$_POST["situacao"]);

  if($adicionar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Set("ancora","contratosescola");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "cancelar_contrato") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $adicionar = $linhaObj->cancelarContrato($_POST["situacao"],$_POST["justificativa"],$_POST["idescola_contrato"]);

  if($adicionar["sucesso"]){
    $linhaObj->alterarSituacaoContratosAceitos((int) $linha["idescola"]);
    $linhaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Set("ancora","contratosescola");
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "reenviar_email") {
  //$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");
  $linhaObj->Set("id", $linha["idescola"]);
  $reenviar = $linhaObj->reenviarEmailContrato();

  if($reenviar){
    $linhaObj->Set("pro_mensagem_idioma",'reenviar_email_contrato_sucesso');
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = 'reenviar_email_contrato_erro';
  }
}

if($url[5]) {
    switch ($url[5]) {
        case "gerarcontrato":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
          //include("../classes/contratos.class.php");
          $linhaObj->Set("ordem","asc");
          $linhaObj->Set("limite",-1);
          $linhaObj->Set("ordem_campo","nome");
          $linhaObj->Set("campos","c.idcontrato, c.nome, 'contrato' as tipo");
          $linhaObj->Set("campos_2","gc.idgrupo, gc.nome, 'grupo' as tipo");
          $contratos = $linhaObj->RetornarContratosGrupos();

          include("idiomas/".$config["idioma_padrao"]."/administrar.gerar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.gerar.contrato.php");
          exit;
        break;
        case "cancelarcontrato":
          $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
          include("idiomas/".$config["idioma_padrao"]."/administrar.cancelar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.cancelar.contrato.php");
          exit;
        break;
        case "contrato":
          $linhaObj->Set("id",$linha["idescola"]);
          $contrato = $linhaObj->retornarContrato(intval($url[6]));

          include("idiomas/".$config["idioma_padrao"]."/administrar.contrato.php");
          include("telas/".$config["tela_padrao"]."/administrar.contrato.php");
          exit;
        break;
        case "contratodownload":
              $linhaObj->Set("id",$linha["idescola"]);
              $contrato = $linhaObj->retornarContrato(intval($url[6]));

              include("telas/".$config["tela_padrao"]."/administrar.download.contrato.php");
              exit;
            break;
        case "contratopdf":
          $linhaObj->Set("id",$linha["idescola"]);
          $contrato = $linhaObj->retornarContrato(intval($url[6]));
          //$data_escola = new DateTime($linha['data_cad']);
          $arquivo = "/storage/escolas_contratos/" . $contrato['arquivo_pasta'] . "/" . $contrato["idescola"]."/".$contrato["idescola_contrato"].".html";
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
          $arquivoNome = "../storage/temp/".$contrato["idescola_contrato"].".pdf";
          $mpdf->Output($arquivoNome,"F");

          header('Content-type: application/pdf');
          readfile($arquivoNome);
          exit;
        break;
    }
}

$linha["contratos"] = $linhaObj->RetornarContratos();

require("../classes/tiposcontratos.class.php");
$tiposContratosObj = new Tipos_Contratos();
$tiposContratosObj->Set("campos","*");
$tiposContratosObj->Set("ordem","asc");
$tiposContratosObj->Set("ordem_campo","nome");
$tiposContratosObj->Set("limite","-1");
$_GET['q']['1|ativo_painel'] = 'S';
$tiposContratos = $tiposContratosObj->ListarTodas();

/*require("../classes/contratos.class.php");
$linhaObj->Set("ordem","asc");
$linhaObj->Set("limite",-1);
$linhaObj->Set("ordem_campo","nome");
$linhaObj->Set("campos","c.idcontrato, c.nome, 'contrato' as tipo");
$linhaObj->Set("campos_2","gc.idgrupo, gc.nome, 'grupo' as tipo");
$contratos = $linhaObj->RetornarContratosGrupos();*/
$_GET['q']['1|gerar_cfc'] = 'S';
$_GET['q']['1|ativo_painel'] = 'S';
$contratosObj = new Contratos();
$contratos = $contratosObj->ListarTodas();

//$existeDevedorSolidario = $linhaObj->existeDevedorSolidario();

include("idiomas/".$config["idioma_padrao"]."/administrar.contratos.php");
include("telas/".$config["tela_padrao"]."/administrar.contratos.php");
