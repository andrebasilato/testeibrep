<?php
include("../classes/cobrancas.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Cobrancas();
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
} elseif ($_POST["acao"] == "salvar_cobranca") {
    if ($_POST["mensagem"] && $_POST["proxima_acao"]) {
        $linhaObj->Set("post", $_POST);
        $salvar = $linhaObj->adicionarCobranca();
        if ($salvar["sucesso"]) {
            $linhaObj->Set("pro_mensagem_idioma", "cobranca_adicionada_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
            $linhaObj->Processando();
        } else {
            $cobranca["erro"] = $salvar["mensagem"];
        }
    } else {
        $salvar["sucesso"] = false;
        $salvar["erros"][] = "cobranca_vazia";
    }
} elseif ($_POST["acao"] == "remover_cobranca") {
    if ($_POST["idcobranca"]) {
        $remover = $linhaObj->removerCobranca(intval($_POST["idcobranca"]));
        if ($remover["sucesso"]) {
            $linhaObj->Set("pro_mensagem_idioma", $remover["mensagem"]);
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4] . "/" . $url[5]);
            $linhaObj->Processando();
        } else {
            $cobranca["erro"] = $remover["mensagem"];
        }
    } else {
        $cobranca["erro"] = "cobranca_remover_vazio";
    }
}

if (isset($url[3])) {
    if (isset($url[4])) {
        if ($url[4] == 'descricao') {
            $_GET["idcobranca"] = $url[3];
            $linhaObj->Set("campos", "c.mensagem");
            $mensagem = $linhaObj->ListarTodas();
            include("telas/" . $config["tela_padrao"] . "/descricao.php");
            exit();
            //print_r2($mensagem[0]['mensagem'],true);
        }
    }
    switch ($url[3]) {
        case "json":
            include("telas/" . $config["tela_padrao"] . "/json.php");
            break;
        case "administrar":
            $contas_matricula = $linhaObj->RetornarContas($url[4]);
            $linhaObj->Set("campos", "c.*, p.nome as aluno, ua.nome as usuario, ua.idusuario");
            $_GET["idmatricula"] = $url[4];
            $cobrancasMatricula = $linhaObj->ListarTodas();
            
            $dadosPessoa = $linhaObj->retornarPessoaPorMatricula($url[4]);
            $contatosArray = $linhaObj->retornarContatosPorMatricula($url[4]);
            
            include("idiomas/" . $config["idioma_padrao"] . "/administrar.php");
            include("telas/" . $config["tela_padrao"] . "/administrar.php");
            break;
        default:

            $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
            $linhaObj->Set("pagina", $_GET["pag"]);
            if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
            $linhaObj->Set("ordem", $_GET["ord"]);
            if (!$_GET["qtd"]) $_GET["qtd"] = 30;
            $linhaObj->Set("limite", intval($_GET["qtd"]));
            if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
            $linhaObj->Set("ordem_campo", $_GET["cmp"]);
            $linhaObj->Set("campos", "c.*, p.nome as aluno, ua.nome as usuario");
            $linhaObj->Set("id", intval($url[3]));
            $_GET["todas"] = 1;
            $cobrancasArray = $linhaObj->ListarTodas();
            include("idiomas/" . $config["idioma_padrao"] . "/index.php");
            include("telas/" . $config["tela_padrao"] . "/index.php");
            exit();
    }
} else {

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "c.*, p.nome as aluno, ua.nome as usuario");
    $linhaObj->Set("id", intval($url[3]));
    $_GET["todas"] = 1;
    $cobrancasArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
    exit();
}
?>