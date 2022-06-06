<?

    include("../classes/declaracoes.class.php");
    include("config.php");
    include("config.formulario.php");
    include("config.listagem.php");


    //Incluimos o arquivo com variaveis padrão do sistema.
    include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

    $linhaObj = new Declaracoes();
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

    $linhaObj->Set("idusuario",$usuario["idusuario"]);
    $linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


    if($_POST["acao"] == "salvar"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
        if($_POST["acao_url"]){
            $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
        }else{
            $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
        }
        if($_FILES) {
            foreach($_FILES as $ind => $val) {
              $_POST[$ind] = $val;
            }
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
    } elseif($_POST["acao"] == "associar_sindicato") {
      $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
      $linhaObj->Set("id",intval($url[3]));
      $linhaObj->Set("post",$_POST);
      $salvar = $linhaObj->AssociarSindicato();
      if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","associar_sindicato_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
      }
    } elseif ($_POST['acao'] == 'salvar_imagens') {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
        include("idiomas/".$config["idioma_padrao"]."/imagens.php");
        $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
        $linhaObj->Set("id",$url[3]);
        $linhaObj->Set("files",$_FILES);
        $erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
        $salvar = $linhaObj->CadastrarImagens($erros);

        if($salvar["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","imagem_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
            $linhaObj->Processando();
        }
    } elseif($_POST["acao"] == "remover_imagem"){

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
        $linhaObj->Set("id",$_POST['remover']);
        $remover = $linhaObj->RemoverImagens();

        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","remover_imagem_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
            $linhaObj->Processando();
        }
    } elseif($_POST["acao"] == "remover_sindicato") {
      $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
      $linhaObj->Set("post",$_POST);
      $remover = $linhaObj->DesassociarSindicato();

      if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_sindicato_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
      }
    } elseif($_POST["acao"] == "associar_curso") {
      $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");
      $linhaObj->Set("id",intval($url[3]));
      $linhaObj->Set("post",$_POST);
      $salvar = $linhaObj->AssociarCurso();
      if($salvar["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","associar_curso_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
      }
    } elseif($_POST["acao"] == "remover_curso") {
      $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");
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

            $linhaObj->Set("id",(int)$url[3]);
            $linhaObj->Set("campos","d.*");
            $linha = $linhaObj->Retornar();

            if($linha) {

                switch ($url[4]) {
                    case "editar":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

                        $linhaObj->Set("campos","iddeclaracao_imagem, nome");
                        $linhaObj->Set("limite",-1);
                        $linhaObj->Set("groupby",'iddeclaracao_imagem');
                        $linhaObj->Set("ordem_campo",'iddeclaracao_imagem');
                        $imagensArray = $linhaObj->RetornarImagens();

                        include("idiomas/".$config["idioma_padrao"]."/formulario.php");
                        include("telas/".$config["tela_padrao"]."/formulario.php");
                        break;
                    case "imagens":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

                        $linhaObj->Set("campos","*");
                        $linhaObj->Set("limite",-1);
                        $linhaObj->Set("groupby",'iddeclaracao_imagem');
                        $linhaObj->Set("ordem_campo",'iddeclaracao_imagem');
                        $imagensArray = $linhaObj->RetornarImagens();

                        include("idiomas/".$config["idioma_padrao"]."/imagens.php");
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
                            $linhaObj->Set("id",(int) $url[3]);
                            if($url[5] == 'background'){
                                $imagem = $linhaObj->RetornarImagemDownloadBackground();
                                include("telas/".$config["tela_padrao"]."/download.background.php");
                            }
                            else{	
                                $imagem = $linhaObj->RetornarImagemDownload();
                                include("telas/".$config["tela_padrao"]."/download.php");
                            }
                        
                            break;
                    case "visualiza_imagem":
                        $linhaObj->Set("id",(int) $url[5]);
                        $imagem = $linhaObj->RetornarImagemDownload();
                        include("telas/".$config["tela_padrao"]."/visualiza_imagem.php");
                        break;
                    case "preview":
                        include("../assets/plugins/MPDF54/mpdf.php");

                        $marginLeft = $linha['margem_left'] * 10;
                        $marginRight = $linha['margem_right'] * 10;
                        $marginHeader = $linha['margem_top'] * 10;
                        $marginFooter = $linha['margem_bottom'] * 10;

                        $mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
                        // margin-left,margin_right,margin_top,margin_bottom,margin_header,margin_footer
                        $mpdf->ignore_invalid_utf8 = true;
                        $mpdf->simpleTables = true;
                        set_time_limit (0);

                        $linha['declaracao'] = str_ireplace("[[QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>",$linha['declaracao']);
                        $css = ".quebra_pagina {page-break-after:always;}";

                        $mpdf->defaultfooterline = 0;
                        $mpdf->SetFooter("{PAGENO}");
                        $mpdf->WriteHTML($css,1);

                        //declaracao - dados
                        $linhaDeclaracaoObj = new Declaracoes();
                        $linhaDeclaracaoObj->Set("id",$url[3]);
                        $declaracao = $linhaDeclaracaoObj->Retornar();
                        //declaracao

                        //background - dados
                        $linhaDeclaracaoObj->Set("id",intval($declaracao['iddeclaracao']));
                        $linhaDeclaracaoObj->Set("campos","*");
                        $declaracao_background = $linhaDeclaracaoObj->Retornar();
                        if($declaracao_background['background_servidor']) {
                            $css = "body{font-family:Arial;background:url(../storage/declaracoes_background/".$declaracao_background['background_servidor'].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
                            $mpdf->WriteHTML($css,1);
                        }

                        //background
                        $mpdf->defaultfooterline = 0;
                        $mpdf->SetFooter("{PAGENO}");

                        $mpdf->WriteHTML($linha['declaracao']);
                        $arquivo_nome = "../storage/temp/".$linha['iddeclaracao']."_preview.pdf";
                        $mpdf->Output($arquivo_nome,"F");

                        /*header("Content-type: ".filetype($arquivo_nome));
                        header('Content-Disposition: attachment; filename="'.basename($arquivo_nome).'"');
                        header('Content-Length: '.filesize($arquivo_nome));
                        header('Expires: 0');
                        header('Pragma: no-cache');*/
                        header('Content-type: application/pdf');
                        readfile($arquivo_nome);
                        exit;
                    case "sindicatos":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
                        $linhaObj->Set("id",intval($url[3]));
                        $linhaObj->Set("campos","i.*, di.iddeclaracao_sindicato");
                        $sindicatos = $linhaObj->ListarSindicatosAssociadas();
                        include("idiomas/".$config["idioma_padrao"]."/sindicatos.php");
                        include("telas/".$config["tela_padrao"]."/sindicatos.php");
                        break;
                    case "cursos":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
                        $linhaObj->Set("id",intval($url[3]));
                        $linhaObj->Set("campos","c.*, dc.iddeclaracao_curso");
                        $cursos = $linhaObj->ListarCursosAssociados();
                        include("idiomas/".$config["idioma_padrao"]."/cursos.php");
                        include("telas/".$config["tela_padrao"]."/cursos.php");
                        break;
                    case "json":
                      include("idiomas/".$config["idioma_padrao"]."/json.php");
                      include("telas/".$config["tela_padrao"]."/json.php");
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
        $linhaObj->Set("campos","d.*");
        $dadosArray = $linhaObj->ListarTodas();
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
    }

?>