<?php

include("../classes/usuarios.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Usuarios();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);

if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }
    $linhaObj->Set("post", $_POST);
    if ($_POST[$config["banco"]["primaria"]])
        $salvar = $linhaObj->Modificar();
    else
        $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");

    $linhaObj->Set("post", $_POST);

    $remover = $linhaObj->Remover();
    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "resetar_senha") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");

    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->ResetarSenha();

    if (!$salvar["sucesso"]) {
        if ($salvar["tela_senha"]) {
            $linhaObj->Set("pro_mensagem_idioma", "sucesso_senha_tela");
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "sucesso");
        }
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarSindicato();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_cfc") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarCfc();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_cfc_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_cfc") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarCfc();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_cfc_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "gestor_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");

    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->GestorSindicato(intval($url[3]));

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "gestor_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "gestor_cfc") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");

    $aa = $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->GestorCfc(intval($url[3]));

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "gestor_cfc_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "gerar_token") {
    $gestaoTokenObj = new AwOra\orio\GestaoTokens;
    $gerarNovoToken = $gestaoTokenObj
        ->novoToken(
            $usuario['idusuario'],
            $url[0],
            !empty($_POST['descricao']) ? $_POST['descricao'] : ''
        );

    if ($gerarNovoToken["sucesso"]) {
        $linhaObj->set("pro_mensagem_idioma", "novo_token_adicionado")
            ->set("url", "/" . implode('/', $url))
            ->processando();
    }
} 

if (isset($url[3])) {
    if ($url[4] == "ajax_cidades") {
        if ($_REQUEST['idestado']) {
            $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
        } else {
            $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
        }
        exit();
    }

    if ($url[3] == "cadastrar") {
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "u.*, p.nome as perfil, c.nome as cidade, e.nome as estado");
        $linha = $linhaObj->Retornar();
        if (is_array($linha))
            $linha = array_map(stripslashes, $linha);

        if ($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
                    break;
                case "desativar_login":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    include("idiomas/" . $config["idioma_padrao"] . "/desativar_login.php");
                    include("telas/" . $config["tela_padrao"] . "/desativar_login.php");
                    break;
                case "emails":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
                    include("idiomas/" . $config["idioma_padrao"] . "/emails.php");
                    include("telas/" . $config["tela_padrao"] . "/emails.php");
                    break;
                case "resetar_senha":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
                    include("idiomas/" . $config["idioma_padrao"] . "/resetar_senha.php");
                    include("telas/" . $config["tela_padrao"] . "/resetar_senha.php");
                    break;
                case "remover":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "json":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $sindicatos = $linhaObj->ListarSindicatosAssociadas();
                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "cfcs":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $cfcs = $linhaObj->ListarEscolasAssociadas();
                    include("idiomas/" . $config["idioma_padrao"] . "/cfcs.php");
                    include("telas/" . $config["tela_padrao"] . "/cfcs.php");
                    break;
                case "download":
                    include("telas/" . $config["tela_padrao"] . "/download.php");
                    break;
                case "excluir":
                    include("idiomas/" . $config["idioma_padrao"] . "/excluir.arquivo.php");
                    $linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
                    break;
                case "tokens":
                    $config['listagem'] = $config['listagem_tokens'];
                    $linhaObj->config = $config;

                    $gestaoTokenObj = new AwOra\orio\GestaoTokens;

                    $dadosArray = $gestaoTokenObj
                        ->retornarPorUsuario($usuario['idusuario'], 'gestor');

                    include("idiomas/" . $config["idioma_padrao"] . "/tokens.php");
                    include("telas/" . $config["tela_padrao"] . "/tokens.php");
                    break;
                default:
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
                    exit();
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
            exit();
        }
    }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = "u.idusuario";
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "u.*, p.nome as perfil");
    $dadosArray = $linhaObj->ListarTodas();

    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>