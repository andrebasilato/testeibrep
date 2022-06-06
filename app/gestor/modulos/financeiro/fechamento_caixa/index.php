<?php

require '../classes/fechamentoscaixa.class.php';
require 'config.php';
require 'config.listagem.php';

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Fechamentos_Caixa();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);

if ($_POST['btn_buscar']) {
    include('../classes/contascorrentes.class.php');
    $linhaConta = new Contas_Correntes();
    $contas_correntes = $linhaConta->ListarTodasContasCorrentesEscola();

    $array_contas = $linhaObj->retornarContas();
} elseif ($_POST['btn_fechar']) {
    $salvar = $linhaObj->fecharCaixa();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "fechar_caixa_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    } else {
        include('../classes/contascorrentes.class.php');
        $linhaConta = new Contas_Correntes();
        $contas_correntes = $linhaConta->ListarTodasContasCorrentesEscola();

        $array_contas = $linhaObj->retornarContas();
    }
} elseif ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    if ($_POST["acao_url"]) {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "?" . base64_decode($_POST["acao_url"]);
    } else {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2];
    }
    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->Cadastrar();

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
        $linhaObj->Set("url", $url_redireciona);
        $linhaObj->Processando();
    }
}


if (empty($url[3])) {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "fc.*, ua.nome as responsavel");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
    exit;
}

switch ($url[3]) {
    case 'cadastrar':
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

        require_once '../classes/sindicatos.class.php';
        require_once '../classes/eventos.financeiros.class.php';
        $eventoFinanceiro = new EventosFinanceiros();
        $eventosFinanceiros = $eventoFinanceiro->ListarTodas();
        $sindicatoObj = new Sindicatos();
        $sindicatoObj->set('campos','i.idsindicato, i.nome_abreviado')
            ->set('ordem_campo', 'i.nome_abreviado')
            ->set('ordem','ASC')
            ->set('limite', -1);
        $sindicatosArray = $sindicatoObj->ListarTodas();

        include 'idiomas/' . $config['idioma_padrao'] . '/gerar.php';
        include 'telas/' . $config['tela_padrao'] . '/gerar.php';
        exit;
    case 'xmlporperiodo':
        if ($_GET['gerar'] == 'Gerar' && $_GET['tipo_periodo']) {
            if (
                (
                    $_GET['tipo_periodo'] == 'PER'
                    && $_GET['periodo_inicio']
                    && $_GET['periodo_final']
                )
                || $_GET['tipo_periodo'] != 'PER'
            ) {
                if(is_numeric($_GET['matricula']))
                {
                    $situacoes = $linhaObj->retornarSituacoesFiltradas(["cancelada" => "!= 'S'"]);
                    $contas = $linhaObj->retornarContasXMLPeriodo($_GET['matricula'], $situacoes);
                } else {
                    $contas = $linhaObj->retornarContasXMLPeriodo();
                }
                $unidade = $linhaObj->retornarUnidadeXMLPeriodo();
                if(empty($contas))
                {
                    echo '<script>alert("Não existe nenhuma conta cadastrada no período informado."); window.close();</script>';
                } else {
                include("telas/" . $config["tela_padrao"] . "/xml.php");
                }
            } else {
                echo '<script>alert("Informe o período!"); window.close();</script>';
            }
        } else {
            $linhaInsObj = new Escolas();
            $linhaInsObj->Set("campos","*");
            $escolas = $linhaInsObj->ListarTodas();

            include 'idiomas/' . $config['idioma_padrao'] . '/xmlporperiodo.php';
            include 'telas/' . $config['tela_padrao'] . '/xmlporperiodo.php';
        }
        exit;
    case 'json':
        include("telas/" . $config["tela_padrao"] . "/json.php");
        exit;
}

if ($url[4]) {
    $linhaObj->Set("id", (int)$url[3]);
    $linhaObj->Set("campos", "*");
    $linha = $linhaObj->Retornar();

    if (!$linha) {
        header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        exit();
    }

    switch ($url[4]) {
        case "xml":
            $contas = $linhaObj->retornarContaFechamento($linha['idfechamento']);
            $unidade = $linhaObj->retornarUnidadeXML($linha['idfechamento']);
            include("telas/" . $config["tela_padrao"] . "/xml.php");
            break;
        case "txt":
            include("telas/" . $config["tela_padrao"] . "/txt.php");
            break;
        default:
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
            exit();
    }
}

