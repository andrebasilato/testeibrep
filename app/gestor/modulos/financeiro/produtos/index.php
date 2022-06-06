<?php
include("../classes/produtos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Produtos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);


if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
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
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_fornecedor") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarFornecedores();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_fornecedor_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_fornecedor") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarFornecedor();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_fornecedor_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {

    if ($url[3] == "verificar") {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

        if ($_GET["nome"]) {
            $linhaObj->Set("pagina", 1);
            $linhaObj->Set("ordem", "asc");
            $linhaObj->Set("limite", 30);
            $linhaObj->Set("ordem_campo", "nome");
            $linhaObj->Set("campos", "*");
            $_GET["q"]["2|nome"] = $_GET["nome"];
            $dadosArray = $linhaObj->ListarTodas();

            if (count($dadosArray) == 0) {
                $_POST["nome"] = $_GET["nome"];
                include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                include("telas/" . $config["tela_padrao"] . "/formulario.php");
                exit();
            } else {
                include("idiomas/" . $config["idioma_padrao"] . "/verifica.php");
                include("telas/" . $config["tela_padrao"] . "/verifica.php");
                exit();
            }

        } else {
            include("idiomas/" . $config["idioma_padrao"] . "/verifica.php");
            include("telas/" . $config["tela_padrao"] . "/verifica.php");
            exit();
        }

    } elseif ($url[3] == "cadastrar") {
        $_POST["nome"] = $_GET["nome"];
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "*");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4]) {
                case "editar":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.php");
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
                case "fornecedores":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $fornecedores = $linhaObj->ListarFornecedoresAssociados();
                    include("idiomas/" . $config["idioma_padrao"] . "/fornecedores.php");
                    include("telas/" . $config["tela_padrao"] . "/fornecedores.php");
                    break;
                case "json":
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                default:
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                    exit();
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            exit();
        }
    }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "*");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>