<?php
include("../classes/feriados.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Feriados();
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
} elseif ($_POST["acao"] == "associar_cidade") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $salvar = $linhaObj->AssociarCidades(intval($url[3]), $_POST["cidades"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_cidade_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cidades");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_associacao_cidade") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarCidades();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_cidade_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cidades");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_estado") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $salvar = $linhaObj->AssociarEstados(intval($url[3]), $_POST["estados"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_estado_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/estados");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_associacao_estado") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarEstados();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_estado_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/estados");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_escola") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $salvar = $linhaObj->AssociarEscolas(intval($url[3]), $_POST["escolas"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_escola_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cfc");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_associacao_escola") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarEscolas();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_escola_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cfc");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|14");
    $salvar = $linhaObj->AssociarSindicatos(intval($url[3]), $_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_associacao_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|15");
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
                case "cidades":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "nome");
                    $linhaObj->Set("campos", "fc.idferiado_cidade, fc.idferiado, c.idcidade, c.nome");
                    $associacoesArray = $linhaObj->ListarCidadesAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/cidades.php");
                    include("telas/" . $config["tela_padrao"] . "/cidades.php");
                    break;
                case "estados":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "nome");
                    $linhaObj->Set("campos", "fe.idferiado_estado, fe.idferiado, e.idestado, e.nome");
                    $associacoesArray = $linhaObj->ListarEstadosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/estados.php");
                    include("telas/" . $config["tela_padrao"] . "/estados.php");
                    break;
                case "cfc":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "nome_fantasia");
                    $linhaObj->Set("campos", "fp.idferiado_escola, fp.idferiado, p.idescola, p.nome_fantasia");
                    $associacoesArray = $linhaObj->ListarEscolasAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/escolas.php");
                    include("telas/" . $config["tela_padrao"] . "/escolas.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|13");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "nome");
                    $linhaObj->Set("campos", "fi.idferiado_sindicato, fi.idferiado, i.idsindicato, i.nome_abreviado as nome");
                    $associacoesArray = $linhaObj->ListarSindicatosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "json":
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