<?

include("../classes/gruposusuariosadm.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Grupos_Usuarios_Adm();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);


if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    if ($_POST["acao_url"]) {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "?" . base64_decode($_POST["acao_url"]);
    } else {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2];
    }

    $linhaObj->Set("post", $_POST);
    if ($_POST[$config["banco"]["primaria"]]) $salvar = $linhaObj->Modificar();
    else $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"]) {
        if ($_POST[$config["banco"]["primaria"]]) {
            $linhaObj->Set("pro_mensagem_idioma", "modificar_sucesso");
            $linhaObj->Set("url", $url_redireciona);
        } else {
            $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
            $linhaObj->Set("url", $url_redireciona);
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
} elseif ($_POST["acao"] == "associar_usuario") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");

    $salvar = $linhaObj->AssociarUsuarios(intval($url[3]), $_POST["usuarios"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_usuario_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/usuarios");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_usuario") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");

    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarUsuarios();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_usuario_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/usuarios");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_assunto") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");

    $salvar = $linhaObj->AssociarAssuntos(intval($url[3]), $_POST["assuntos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_assunto_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/assuntos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_assunto") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");

    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarAssuntos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_assunto_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/assuntos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_subassunto") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");

    $salvar = $linhaObj->AssociarSubassuntos(intval($url[3]), $_POST["subassuntos"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_subassunto_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/subassuntos");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_subassunto") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");

    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSubassuntos();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_subassunto_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/subassuntos");
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
                    $usuariosAssociados = $linhaObj->ListarUsuariosAss();
                    include("idiomas/" . $config["idioma_padrao"] . "/remover.php");
                    include("telas/" . $config["tela_padrao"] . "/remover.php");
                    break;
                case "opcoes":
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.php");
                    break;
                case "usuarios":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "u.nome");
                    $linhaObj->Set("campos", "*");
                    $usuariosArray = $linhaObj->ListarUsuariosAss();
                    include("idiomas/" . $config["idioma_padrao"] . "/usuarios.php");
                    include("telas/" . $config["tela_padrao"] . "/usuarios.php");
                    break;
                case "assuntos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "a.nome");
                    $linhaObj->Set("campos", "aag.*, a.nome");
                    $assuntosArray = $linhaObj->ListarAssuntosAss();
                    include("idiomas/" . $config["idioma_padrao"] . "/assuntos.php");
                    include("telas/" . $config["tela_padrao"] . "/assuntos.php");
                    break;
                case "subassuntos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "ass.nome, s.nome");
                    $linhaObj->Set("campos", "asg.*, s.nome, ass.nome as assunto");
                    $subassuntosArray = $linhaObj->ListarSubassuntosAss();
                    include("idiomas/" . $config["idioma_padrao"] . "/subassuntos.php");
                    include("telas/" . $config["tela_padrao"] . "/subassuntos.php");
                    break;
                case "associar_usuarios":
                    echo $linhaObj->BuscarUsuarios($url[3]);
                    break;
                case "associar_assuntos":
                    echo $linhaObj->BuscarAssuntos($url[3]);
                    break;
                case "associar_subassuntos":
                    echo $linhaObj->BuscarSubassuntos($url[3]);
                    break;
                default:
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
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "*");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}

?>