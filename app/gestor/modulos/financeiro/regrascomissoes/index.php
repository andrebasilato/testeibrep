<?php
include("../classes/regrascomissoes.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Regras_Comissoes();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);


if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    if ($_POST[$config["banco"]["primaria"]]) {
        if ($_POST["acao_todos"] == "sindicato") {
            $linhaObj->config["formulario"] = $config["formulario_todas_sindicatos"];
            if (!$_POST["todas_sindicatos"]) $_POST["todas_sindicatos"] = "N";
        } elseif ($_POST["acao_todos"] == "curso") {
            $linhaObj->config["formulario"] = $config["formulario_todos_cursos"];
            if (!$_POST["todos_cursos"]) $_POST["todos_cursos"] = "N";
        }
        $linhaObj->Set("post", $_POST);
        $salvar = $linhaObj->Modificar();
    } else {
        $linhaObj->Set("post", $_POST);
        $salvar = $linhaObj->Cadastrar();
    }
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
} elseif ($_POST["acao"] == "salvar_valor_regra") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->cadastrarValor();

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "salvar_valor_regra_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/regras");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_valor_regra") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->removerValor();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_valor_regra_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/regras");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_curso") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $linhaObj->Set("id", intval($url[3]));
    $salvar = $linhaObj->AssociarCursos($_POST["cursos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_curso_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cursos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_curso") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarCursos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_curso_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cursos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $linhaObj->Set("id", intval($url[3]));
    $salvar = $linhaObj->AssociarSindicatos($_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicatos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {
    if ($url[3] == "cadastrar") {
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
                case "regras":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "valor, porcentagem");
                    $linhaObj->Set("campos", "*");
                    $valoresRegra = $linhaObj->ListarValores();
                    include("idiomas/" . $config["idioma_padrao"] . "/regras.php");
                    include("telas/" . $config["tela_padrao"] . "/regras.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");
                    $linhaObj->Set("campos", "cri.*, i.nome_abreviado");
                    $sindicatos = $linhaObj->ListarSindicatosAssociadas();
                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "cursos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");
                    $linhaObj->Set("campos", "crc.*, c.nome");
                    $cursos = $linhaObj->ListarCursosAssociados();
                    include("idiomas/" . $config["idioma_padrao"] . "/cursos.php");
                    include("telas/" . $config["tela_padrao"] . "/cursos.php");
                    break;
                case "json":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    //include("idiomas/".$config["idioma_padrao"]."/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
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