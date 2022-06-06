<?php
include("../classes/professores.class.php");

include("config.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Professores();

$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if(isset($url[4])){
    include_once("../classes/avas.mensagem_instantanea.class.php");
    $acessoAvaObj = new MensagemInstantanea();
    $acessoAvaObj->set("idava",intval($url[3]));
    $acessoAvaObj->set("idprofessor",$usu_professor["idprofessor"]);
    $acessoAva = $acessoAvaObj->VerificarAcessoAvaProfessor();

    if ($acessoAva){
        switch ($url[4]) {
            case "json":
                include("idiomas/".$config["idioma_padrao"]."/json.php");
                include("telas/".$config["tela_padrao"]."/json.php");

            case "opcoes":
                include("../classes/avas.class.php");
                $linhaObjAva = new Ava();
                $linhaObjAva->Set("idprofessor",$usu_professor["idprofessor"]);
                $linhaObjAva->Set("id",intval($url[3]));
                $linhaObjAva->Set("campos","*");
                $linhaAva = $linhaObjAva->Retornar();

                include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
                include("telas/".$config["tela_padrao"]."/opcoes.php");
                break;

            case "mensagem_instantanea":
               
                $mensagemObj = new MensagemInstantanea();
                $mensagemObj->set("idava",intval($url[3]));
                $mensagemObj->set("idprofessor",$usu_professor["idprofessor"]);

                if(isset($url[6]) && $url[7] == 'download') {
                    $download = $mensagemObj->RetornarArquivoConversa((int) $url[6]);
                    include("telas/".$config["tela_padrao"]."/mensagens_instantaneas.download.php");
                    exit();
                }

                if (isset($url[5]) && $url[5] == 'enviar_arquivo_instantanea') {
                    $mensagemObj->set("post",$_POST);
                    $salvar = $mensagemObj->enviarArquivoMensagem(isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0,isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0,isset($_REQUEST["name"]) ? $_REQUEST["name"] : '',$_SESSION["idmensagem_instantanea_conversa"]);

                    if ($salvar["erro"]) {
                       include("idiomas/".$config["idioma_padrao"]."/mensagem_instantanea.php");
                       foreach ($salvar["erros"] as $ind => $var) {
                           $salvar["erros"][$ind] = $idioma[$var];
                       }
                    }               
                                    
                    echo json_encode($salvar);
                    exit();
                }
                
                if ($_POST["acao"] == "iniciar_chat") {
                    $mensagemObj->set("post",$_POST);
                    $salvar = $mensagemObj->iniciarConversa();

                    if ($salvar["sucesso"]) {
                        $url_retorno = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$salvar["idmensagem_instantanea"];
                        echo "<script>
                                window.opener.location.href=\"".$url_retorno."\";
                                window.close();
                            </script>";
                          
                    }
                } elseif ($_POST["acao"] == "enviar_mensagem_instantanea") {
                    
                    $mensagemObj->set("post",$_POST);
                    $salvar = $mensagemObj->salvarNovaConversa();

                    if ($salvar["erro"]) {
                       include("idiomas/".$config["idioma_padrao"]."/mensagem_instantanea.php");
                       foreach ($salvar["erros"] as $ind => $var) {
                           $salvar["erros"][$ind] = $idioma[$var];
                       }
                    }               
                    $_SESSION["idmensagem_instantanea_conversa"] = $salvar['idmensagem_instantanea_conversa'];
                
                    echo json_encode($salvar);
                    exit();
                } elseif ($_POST["acao"] == "atualizaConversas") {
                    //Retorna as conversas de uma mensagem instantânea da pessoa
                    $mensagemObj->set("idmensagem_instantanea",$_POST["idmensagem_instantanea"]);
                    $mensagemObj->set("ultimaIdMensagem",$_POST["ultimaIdMensagem"]);
                    $conversasMensagem = $mensagemObj->ListarConversasMensagemInstantanea();
                    echo json_encode($conversasMensagem);
                    exit;
                } elseif ($_POST["acao"] == "adicionar_usuario") {
                    $mensagemObj->set("post",$_POST);
                    $mensagemObj->set("idmensagem_instantanea",intval($url[5]));
                    $salvar = $mensagemObj->adicionarUsuarioMensagem();

                    if ($salvar["sucesso"]) {
                        $url_retorno = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5];
                        echo "<script>
                                window.opener.location.href=\"".$url_retorno."\";
                                window.close();
                            </script>";
                          
                    }
                } /*elseif ($_POST["acao"] == "sair_conversa") {
                    if (intval($url[5])) {
                        $mensagemObj->set("idmensagem_instantanea",intval($url[5]));
                        $remover = $mensagemObj->sairConversa();

                        if ($remover["sucesso"]) {
                            $url_retorno = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4];
                            $mensagemObj->Set("pro_mensagem_idioma","sair_conversa_sucesso");
                            $mensagemObj->Set("url",$url_retorno);
                            $mensagemObj->Processando();
                        }
                    }
                }*/           

                if ($url[5] == "nova_mensagem") {
                    include("idiomas/".$config["idioma_padrao"]."/nova_mensagem_instantanea.php");
                    include("telas/".$config["tela_padrao"]."/nova_mensagem_instantanea.php");
                    exit;
                } elseif ($url[6] == "adicionar_pessoa" && intval($url[5])) {
                    include("idiomas/".$config["idioma_padrao"]."/adicionar_pessoa_mensagem_instantanea.php");
                    include("telas/".$config["tela_padrao"]."/adicionar_pessoa_mensagem_instantanea.php");
                    exit;
                }

                //Retorna as mensagens instantâneas que o aluno participa e os integrantes
                $mensagensIntegrantes = $mensagemObj->ListarMensagensInstantaneasPessoa();

                if ($url[5]) {
                    
                    if($url[6] == 'ativarsinalizador') {
                        $sinalizador = $mensagemObj->Sinalizador(intval($url[5]), 'S');
                        if ($sinalizador) {
                            $url_retorno = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4];
                            $mensagemObj->Set("pro_mensagem_idioma","ativar_sinalizador_sucesso");
                            $mensagemObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
                            $mensagemObj->Processando();
                        }
                    } elseif($url[6] == 'desativarsinalizador') {
                        $sinalizador = $mensagemObj->Sinalizador(intval($url[5]), 'N');
                        if ($sinalizador) {
                            $url_retorno = "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4];
                            $mensagemObj->Set("pro_mensagem_idioma","desativar_sinalizador_sucesso");
                            $mensagemObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
                            $mensagemObj->Processando();
                        }
                    }
                    //Retorna as conversas de uma mensagem instantânea da pessoa
                    $mensagemObj->set("idmensagem_instantanea",$url[5]);
                    $mensagemInstantanea = $mensagemObj->RetornarMensagemInstantanea();
                    $conversasMensagem = $mensagemObj->ListarConversasMensagemInstantanea();
                }

                include("idiomas/".$config["idioma_padrao"]."/mensagem_instantanea.php");
                include("telas/".$config["tela_padrao"]."/mensagem_instantanea.php");
                exit;
            break;

            default:
                header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
                exit();
        }
    } else{
        header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
        exit();
    }
} else {
            
    $linhaObj->Set("pagina",$_GET["pag"]);
    $linhaObj->Set("ordem_campo",($_GET["cmp"]) ? $_GET["cmp"] : $config["banco"]["primaria"]);
    $linhaObj->Set("ordem",($_GET["ord"]) ? $_GET["ord"] : "DESC");
    $linhaObj->Set("limite",(intval($_GET["qtd"])) ? intval($_GET["qtd"]) : 30);
    $linhaObj->Set("campos","a.*");
    $linhaObj->Set("id",$usu_professor["idprofessor"]);
    $dadosArray = $linhaObj->ListarAvasAss();

    include("idiomas/".$config["idioma_padrao"]."/index.php");
    include("telas/".$config["tela_padrao"]."/index.php");
}