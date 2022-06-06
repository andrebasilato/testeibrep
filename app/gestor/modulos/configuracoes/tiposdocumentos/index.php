<?php
include("../classes/tiposdocumentos.class.php");
include_once("../classes/matriculas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Tipos_Documentos();
$matricula = new Matriculas();
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
        $matricula->alterarEnvioDocumentoFotoOficial();
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
        $matricula->alterarEnvioDocumentoFotoOficial();
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_curso") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_curso_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cursos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_curso") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarCursos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_curso_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cursos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "todos_cursos_obrigatorio") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->TodosCursosObrigatorio(intval($url[3]));

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "todos_curso_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/cursos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $salvar = $linhaObj->AssociarSindicatos(intval($url[3]), $_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicatos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "todas_sindicatos_obrigatorio") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->TodasSindicatosObrigatorio(intval($url[3]));

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "todas_sindicatos_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato_agendamento") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $salvar = $linhaObj->AssociarSindicatosAgendamento(intval($url[3]), $_POST["sindicatos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos_agendamento");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato_agendamento") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicatosAgendamento();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos_agendamento");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "todas_sindicatos_agendamento_obrigatorio") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->TodasSindicatosAgendamentoObrigatorio(intval($url[3]));

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "todas_sindicatos_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/sindicatos_agendamento");
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
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "json":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                case "cursos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "c.nome");
                    $linhaObj->Set("campos", "c.idcurso, c.nome, tc.idtipo_curso");
                    $cursosArray = $linhaObj->ListarCursosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/cursos.php");
                    include("telas/" . $config["tela_padrao"] . "/cursos.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "i.nome_abreviado");
                    $linhaObj->Set("campos", "i.idsindicato, i.nome_abreviado, ti.idtipo_sindicato");
                    $sindicatosArray = $linhaObj->ListarSindicatosAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "sindicatos_agendamento":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");

                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "i.nome_abreviado");
                    $linhaObj->Set("campos", "i.idsindicato, i.nome_abreviado, ti.idtipo_sindicato");
                    $sindicatosArray = $linhaObj->ListarSindicatosAgendamentoAss();

                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos_agendamento.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos_agendamento.php");
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
