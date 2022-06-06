<?php
include("../classes/vendedores.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Vendedores();
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
    $datetime_class = new DateTime('now');

    if ($_POST[$config["banco"]["primaria"]]) {
        if($_POST['senha'] != '' && $_POST['confirma'] != '' && $_POST['senha'] == $_POST['confirma'])
            $_POST['ultima_senha'] = $datetime_class->format('Y-m-d H:i:s');
        $linhaObj->Set("post", $_POST);
        $salvar = $linhaObj->Modificar();
    }else {
        $_POST['ultima_senha'] = $datetime_class->format('Y-m-d H:i:s');
        $linhaObj->Set("post", $_POST);
        $salvar = $linhaObj->Cadastrar();
    }

    if ($salvar["sucesso"]) {
        if (isset($_POST['atendente_padrao']) && $_POST['atendente_padrao'] == 'S') {
            $linhaObj->definirAtendentePadrao($salvar['id']);
        }

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
} elseif ($_POST["acao"] == "adicionar_contato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");

    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->adicionarContato();

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_contato") {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");

    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->RemoverContato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarSindicato();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_sindicato") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarSindicato();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sindicato_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_escola") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9");
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AssociarEscola();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_escola_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_escola") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10");
    $linhaObj->Set("post", $_POST);
    $remover = $linhaObj->DesassociarEscola();

    if ($remover["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_escola_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {
    if ($url[4] == "ajax_cidades") {
        if ($_REQUEST['idestado']) {
            $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
        } else {
            $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
        }
        exit;
    } elseif ($url[3] == "cadastrar") {
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
                case "acessarcomo":

                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|11");

                    $_SESSION["usu_vendedor_email"] = $linha["email"];
                    $_SESSION["usu_vendedor_senha"] = $linha["senha"];
                    $_SESSION["usu_vendedor_idvendedor"] = $linha["idvendedor"];
                    $_SESSION["usu_vendedor_nome"] = $linha["nome"];
                    $_SESSION["usu_vendedor_ultimoacesso"] = $linha["ultimo_acesso"];
                    $_SESSION["usu_vendedor_gestor"] = $usuario["idusuario"];

                    $sql = "SELECT e.idescola,e.avatar_servidor
                    FROM 
                        vendedores_escolas ve, escolas e
                    WHERE
                    ve.idvendedor='{$linha["idvendedor"]}' AND
                    ve.ativo='S' AND
                    ve.idescola=e.idescola AND
                    e.ativo='S' ORDER BY ve.idvendedor_escola DESC";
                    $queryEscolas = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                    $_SESSION["usu_vendedor_escolas"] = array();
                    while ($escola = mysql_fetch_assoc($queryEscolas)) {
                        $_SESSION['logo_cfc'] = $escola['avatar_servidor'];
                        $_SESSION["usu_vendedor_escolas"][$escola["idescola"]] = $escola["idescola"];
                    }

                    if (count($_SESSION["usu_vendedor_escolas"]) > 0) {
                        $_SESSION["usu_vendedor_escolas"] = implode(",", $_SESSION["usu_vendedor_escolas"]);
                    } else {
                        unset($_SESSION["usu_vendedor_escolas"]);
                    }

                    if ($_SESSION['logo_cfc']) {
                       define('URL_LOGO_PEGUENA', "/api/get/imagens/escolas_avatar/x/50/{$_SESSION["logo_cfc"]}?qualidade=80");
                    } else {
                        defined('URL_LOGO_PEGUENA') or define("URL_LOGO_PEGUENA", "/assets/img/logo_pequena.png");
                    }

                    $sql = "SELECT i.idsindicato, i.idmantenedora FROM vendedores_sindicatos vi, sindicatos i WHERE vi.idvendedor='" . $linha["idvendedor"] . "' AND vi.ativo='S' AND vi.idsindicato=i.idsindicato AND i.ativo='S'";
                    $querySindicatos = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                    $_SESSION["usu_vendedor_sindicatos"] = array();
                    $_SESSION["usu_vendedor_mantenedoras"] = array();
                    while ($sindicato = mysql_fetch_assoc($querySindicatos)) {
                        $_SESSION["usu_vendedor_sindicatos"][$sindicato["idsindicato"]] = $sindicato["idsindicato"];
                        $_SESSION["usu_vendedor_mantenedoras"][$sindicato["idmantenedora"]] = $sindicato["idmantenedora"];
                    }
                    if (count($_SESSION["usu_vendedor_sindicatos"]) <= 0) {
                        $_POST["msg"] = "Vendedor sem sindicatos associadas.";
                        echo $_POST["msg"];
                        exit();
                    } else {
                        $_SESSION["usu_vendedor_sindicatos"] = implode(",", $_SESSION["usu_vendedor_sindicatos"]);
                        $_SESSION["usu_vendedor_mantenedoras"] = implode(",", $_SESSION["usu_vendedor_mantenedoras"]);
                    }


                    $linhaObj->Set("monitora_oque", "9");
                    $linhaObj->Set("monitora_qual", $linha["idvendedor"]);
                    $linhaObj->Set("monitora_dadosnovos", $linhaNova);
                    $linhaObj->Monitora();

                    $linhaObj->Set("url", "/atendente");
                    $linhaObj->Processando();

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
                case "desativar_login":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    include("idiomas/" . $config["idioma_padrao"] . "/desativar_login.php");
                    include("telas/" . $config["tela_padrao"] . "/desativar_login.php");
                    break;
                case "resetar_senha":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
                    include("idiomas/" . $config["idioma_padrao"] . "/resetar_senha.php");
                    include("telas/" . $config["tela_padrao"] . "/resetar_senha.php");
                    break;
                case "contatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("ordem", "asc");
                    $linhaObj->Set("limite", -1);
                    $linhaObj->Set("ordem_campo", "tc.nome");
                    $linhaObj->Set("campos", "c.*, tc.nome as tipo");
                    $associacoesArray = $linhaObj->ListarContatos();
                    $tiposArray = $linhaObj->ListarTiposContatos();
                    include("idiomas/" . $config["idioma_padrao"] . "/contatos.php");
                    include("telas/" . $config["tela_padrao"] . "/contatos.php");
                    break;
                case "bloquear_vendas":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7");
                    include("idiomas/" . $config["idioma_padrao"] . "/bloquear_vendas.php");
                    include("telas/" . $config["tela_padrao"] . "/bloquear_vendas.php");
                    break;
                case "json":
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                case "sindicatos":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|8");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $sindicatos = $linhaObj->ListarSindicatosAssociadas();
                    include("idiomas/" . $config["idioma_padrao"] . "/sindicatos.php");
                    include("telas/" . $config["tela_padrao"] . "/sindicatos.php");
                    break;
                case "cfc":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|12");
                    $linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $escolas = $linhaObj->ListarEscolasAssociadas();
                    include("idiomas/" . $config["idioma_padrao"] . "/cfc.php");
                    include("telas/" . $config["tela_padrao"] . "/cfc.php");
                    break;
                case "download":
                    include("telas/" . $config["tela_padrao"] . "/download.php");
                    break;
                case "excluir":
                    include("idiomas/" . $config["idioma_padrao"]."/excluir.php");
                    $linhaObj->RemoverImgAvatar("vendedores", $url[5], $linha, $idioma);
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
    $linhaObj->Set("campos", "v.idvendedor, v.nome, v.email, v.ativo_login, v.ultimo_acesso, v.data_cad, e.nome as estado, c.nome as cidade,
        (
            SELECT
                GROUP_CONCAT(es.nome_fantasia ORDER BY es.nome_fantasia SEPARATOR ', ')
            FROM
                vendedores_escolas ve
                INNER JOIN escolas es ON (ve.idescola = es.idescola)
            WHERE
                ve.idvendedor = v.idvendedor AND
                ve.ativo = 'S'
        ) AS cfc");
    $dadosArray = $linhaObj->ListarTodas();
    foreach ($dadosArray as $array => $vendedor) {//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
        if (!mb_strpos($vendedor["nome"], ' ')) {
            $vendedor['nome'] = wordwrap($vendedor["nome"], 30, " ", true);
            $dadosArray[$array]['nome'] = $vendedor['nome'];
        }
    }
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>