<?php
include("../classes/contratos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Contratos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_FILES) {
    foreach($_FILES as $ind => $val) {
      $_POST[$ind] = $val;
    }
}
if($_POST["acao"] == "salvar"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    if($_POST["acao_url"]){
        $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
    }else{
        $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
    }

    $linhaObj->Set("post",$_POST);
    if($_POST[$config["banco"]["primaria"]]) { $salvar = $linhaObj->Modificar();
    } else { $salvar = $linhaObj->Cadastrar(); }

    if($salvar["sucesso"]){
        if($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
            $linhaObj->Set("url",$url_redireciona);
        } else {
            $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
            $linhaObj->Set("url",$url_redireciona);
        }
        $linhaObj->Processando();
    }
}elseif($_POST["acao"] == "remover"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->Remover();
    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == 'cadastrar_imagem') {
    if(mb_strpos($_POST['background']['type'], 'image') !== false)
    {
        $linhaObj->Set("post",$_POST);
        $status = $linhaObj->cadastrarImagem();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $salvar = $linhaObj->AssociarSindicatos(intval($url[3]), $_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_associacao_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicatos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
}elseif($_POST["acao"] == "associar_curso") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->AssociarCurso();
  if($salvar["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","associar_curso_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_curso") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->DesassociarCurso();

  if($remover["sucesso"]){
    $linhaObj->Set("pro_mensagem_idioma","remover_curso_sucesso");
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
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
                    include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                    include("telas/".$config["tela_padrao"]."/formulario.php");
                    break;
                case "imagens":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

                    $config["banco"] = array(
                        "tabela" => Contratos::IMAGES_TABLE,
                        "primaria" => "idcontrato"
                    );

                    $linhaObj->Set("pagina",$_GET["pag"]);
                    if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
                    $linhaObj->Set("ordem",$_GET["ord"]);
                    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
                    $linhaObj->Set("limite",intval($_GET["qtd"]));
                    if(!$_GET["cmp"]) $_GET["cmp"] = 'idimagem';
                    $linhaObj->Set("ordem_campo",$_GET["cmp"]);
                    $linhaObj->Set("campos","*");
                    $dadosArray = $linhaObj->listarImagens(['idcontrato' => $linhaObj->id]);

                    $linhaObj->config['listagem_imagem'] = $config['listagem_imagem'];

                    include("idiomas/".$config["idioma_padrao"]."/imagem.php");
                    include("telas/".$config["tela_padrao"]."/imagens.php");
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
                case "excluir":
                    include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
                    $linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
                    break;
                case "download":
                    include("telas/".$config["tela_padrao"]."/download.php");
                    break;
                case "preview":
                    include("../assets/plugins/MPDF54/mpdf.php");

                    $marginLeft = $linha['margem_left'] * 10;
                    $marginRight = $linha['margem_right'] * 10;
                    $marginHeader = $linha['margem_top'] * 10;
                    $marginFooter = $linha['margem_bottom'] * 10;



                    $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
                    $mpdf->ignore_invalid_utf8 = true;
                    $mpdf->simpleTables = true;
                    set_time_limit (0);

                    $linha['contrato'] = str_ireplace("[[QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>",$linha['contrato']);
                    $css = ".quebra_pagina {page-break-after:always;}";

                    $mpdf->defaultfooterline = 0;
                    $mpdf->SetFooter("{PAGENO}");
                    $mpdf->WriteHTML($css,1);

                    //contrato - dados
                    $linhaContratoObj = new Contratos();
                    $linhaContratoObj->Set("id",$url[3]);
                    $contrato = $linhaContratoObj->Retornar();
                    //contrato

                    //background - dados
                    $linhaContratoObj->Set("id",intval($contrato['idcontrato']));
                    $linhaContratoObj->Set("campos","*");
                    $contrato_background = $linhaContratoObj->Retornar();
                    if($contrato_background['background_servidor']) {
                        $css = "body{font-family:Arial;background:url(../storage/contratos_background/".$contrato_background['background_servidor'].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
                        $mpdf->WriteHTML($css,1);
                    }

                    //background
                    $mpdf->defaultfooterline = 0;
                    $mpdf->SetFooter("{PAGENO}");

                    $mpdf->WriteHTML($linha['contrato']);
                    $arquivo_nome = "../storage/temp/".$linha['idcontrato']."_preview.pdf";
                    $mpdf->Output($arquivo_nome,"F");

                    header('Content-type: application/pdf');
                    readfile($arquivo_nome);
                    exit;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "i.nome_abreviado");
                    $linhaObj->Set("campos", "ci.idcontrato_sindicato, ci.idcontrato, i.idsindicato, i.nome_abreviado");
                    $associacoesArray = $linhaObj->ListarSindicatosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "cursos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
                    $linhaObj->Set("id",intval($url[3]));
                    $linhaObj->Set("campos","*");
                    $cursos = $linhaObj->ListarCursosAssociadas();
                    include("idiomas/".$config["idioma_padrao"]."/cursos.php");
                    include("telas/".$config["tela_padrao"]."/cursos.php");
                    break;
                case "visualizarsindicatos":
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "i.nome_abreviado");
                    $linhaObj->Set("campos", "ci.idcontrato_sindicato, ci.idcontrato, i.idsindicato, i.nome_abreviado");
                    $associacoesArray = $linhaObj->ListarSindicatosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/visualizar.sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/visualizar.sindicatos.php");
                    break;
                case "json":
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                default:
                   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
                   exit();
            }

        } else {
           header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
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
