<?

    //include("../classes/provassolicitadas.class.php");
    include("config.php");
    include("config.formulario.php");
    include("config.listagem.php");


    //Incluimos o arquivo com variaveis padrão do sistema.
    include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

    $linhaObj = new SolicitacoesDeclaracoes();
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

    $linhaObj->Set("idusuario",$usuario["idusuario"]);
    $linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

    $matriculaObj = new Matriculas();
    $matriculaObj->Set("modulo",$url[0]);
    $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
    $matriculaObj->Set("idusuario",$usuario["idusuario"]);
    $matriculaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

    $declaracaoObj = new Declaracoes();

    if ($_POST["acao"] == "indeferirsolicitacao"){
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
        $linhaObj->Set("id",(int) $_POST['idsolicitacao']);
        $linhaObj->Set("post",$_POST);
        $remover = $linhaObj->indeferirSolicitacao();
        if($remover["sucesso"]){
            $linhaObj->Set("pro_mensagem_idioma","indeferir_sucesso");
            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
            $linhaObj->Processando();
        }
    }

    if ($_POST["acao"] == "gerar_declaracao") {
        $linhaObj->Set("id",(int) $_POST['idsolicitacao']);
        $salvar = $linhaObj->deferirSolicitacao();
        if ($salvar['sucesso']) {
            $matriculaObj->Set("post",$_POST);
            $gerar = $matriculaObj->gerarDeclaracaoSolicitacao(null, (int) $_POST['idsolicitacao']);
            if ($gerar['sucesso']) {
                $linhaObj->Set("pro_mensagem_idioma",$gerar['mensagem']);
                $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
                $linhaObj->Processando();
            }
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
            $linhaObj->Set("campos","sd.*, d.nome as declaracao");
            $linha = $linhaObj->Retornar();
            if($linha) {
                $linhaObj->Set("idmatricula",(int) $linha['idmatricula']);
                switch ($url[4]) {
                    case "gerardeclaracao":
                        include("idiomas/".$config["idioma_padrao"]."/gerar.declaracao.php");
                        include("telas/".$config["tela_padrao"]."/gerar.declaracao.php");
                        exit;
                    break;
                    /*case "deferirdeclaracao":
                        $linhaObj->Set("id",(int) $url[3]);
                        $salvar = $linhaObj->deferirSolicitacao();
                        if ($salvar['sucesso']) {
                            $gerar = $matriculaObj->gerarDeclaracaoSolicitacao(null, $url[3]);
                            if ($gerar['sucesso']) {
                                $linhaObj->Set("pro_mensagem_idioma",$gerar['mensagem']);
                                $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
                                $linhaObj->Processando();
                            }
                        }
                    break;*/
                    case "indeferirdeclaracao":
                        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
                        $linhaObj->Set("id",$url[3]);
                        $remover = $linhaObj->indeferirSolicitacao();
                        if($remover["sucesso"]){
                            $linhaObj->Set("pro_mensagem_idioma","indeferir_sucesso");
                            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
                            $linhaObj->Processando();
                        }
                    break;
                    case "opcoes":
                        include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                        include("telas/".$config["tela_padrao"]."/opcoes.php");
                    break;
                    case "vermotivo":
                        include("idiomas/".$config["idioma_padrao"]."/motivo.php");
                        include("telas/".$config["tela_padrao"]."/motivo.php");
                    break;
                    case "baixardeclaracao":
                        $matriculaObj->Set("id",$url[6]);
                        $declaracao = $matriculaObj->retornarDeclaracao($url[5]);
                        //$data_matricula = new DateTime($declaracao['data_matricula']);
                        $arquivo = "/storage/matriculas_declaracoes/" . $declaracao['arquivo_pasta'] . "/" . $declaracao["idmatricula"] . "/" . $declaracao["idmatriculadeclaracao"].".html";
                        $arquivoServidor = $_SERVER["DOCUMENT_ROOT"].$arquivo;
                        if(file_exists($arquivoServidor)) {
                            $saida = file_get_contents($arquivoServidor);
                        }

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
                    case "json":
                        $_GET['data_solicitacao'] = $linha['data_solicitacao'];
                        $linhaObj->Set("idmatricula",$linha['idmatricula']);
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
        $linhaObj->Set("campos","sd.*, pe.nome as aluno, pe.idpessoa, d.nome as declaracao");
        $dadosArray = $linhaObj->ListarTodas();
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
    }

?>