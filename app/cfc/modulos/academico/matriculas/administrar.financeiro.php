<?php

$matricula['sindicato'] = $matriculaObj->RetornarSindicato();
$matricula['pessoa'] = $matriculaObj->RetornarPessoa();

if($_POST["acao"] == "editar_pagamento") {

    $tiposContasObj = new Contas();
    $tiposContasObj->Set("idescola",$usuario["idescola"]);
    $tiposContasObj->Set("modulo",$url[0]);
    $tiposContasObj->Set("post",$_POST);
    $salvar = $tiposContasObj->alterarPagamento();

    if($salvar["sucesso"]){

        $matriculaObj->AdicionarHistorico(NULL, "parcela", "modificou", NULL, NULL, $_POST['idconta']);

        $tiposContasObj->Set("pro_mensagem_idioma","editar_pagamento_sucesso");
        $tiposContasObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $tiposContasObj->Set("ancora","financeiromatricula");
        $tiposContasObj->Processando();
    } else {
        $mensagem["erro"] = "editar_pagamento_erro";
    }

} elseif($_POST["acao"] == "editar_pagamento_massa") {
    $tiposContasObj = new Contas();
    $tiposContasObj->Set("idescola",$usuario["idescola"]);
    $tiposContasObj->Set("modulo",$url[0]);
    $salvar = $tiposContasObj->alterarPagamentoMassa($_POST);

    if($salvar["sucesso"]){
        foreach($_POST['idcontas'] as $ind => $val){
            $matriculaObj->AdicionarHistorico(NULL, "parcela", "modificou", NULL, NULL, $val);
        }
        $tiposContasObj->Set("pro_mensagem_idioma","editar_pagamentomassa_sucesso");
        $tiposContasObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $tiposContasObj->Set("ancora","financeiromatricula");
        $tiposContasObj->Processando();
    } else {
        $mensagem["erro"] = "editar_pagamentomassa_erro";
    }

} elseif($_POST["acao"] == "alterar_situacao_conta") {
    $tiposContasObj = new Contas();
    $tiposContasObj->Set("idescola",$usuario["idescola"]);
    $tiposContasObj->Set("modulo",$url[0]);
    $tiposContasObj->Set("id",intval($_POST['idconta']));
    $tiposContasObj->Set("campos","c.*");
    $conta = $tiposContasObj->Retornar();

    if($conta["situacao"]["visualizacoes"][1]) {
        $salvar = $tiposContasObj->AlterarSituacao($conta["idsituacao"],$_POST["situacao_para"]);
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }

    if($salvar["sucesso"]){
        $matriculaObj->AdicionarHistorico(NULL, "parcela_situacao", "modificou", $conta["idsituacao"], $_POST["situacao_para"], $_POST['idconta']);

        $tiposContasObj->Set("pro_mensagem_idioma",$salvar["mensagem"].'_parcela');
        $tiposContasObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $tiposContasObj->Set("ancora","financeiromatricula");
        $tiposContasObj->Processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
} elseif($_POST["acao"] == "adicionar_financeiro") {
    if($matricula["situacao"]["visualizacoes"][54]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);

        $_POST["idmantenedora"] = $matricula["sindicato"]["idmantenedora"];
        $_POST["idsindicato"] = $matricula["sindicato"]["idsindicato"];
        $_POST["idpessoa"] = $matricula["pessoa"]["idpessoa"];
        $_POST["fastconnect_client_code"] = $matricula["escola"]["fastconnect_client_code"];
        $_POST["fastconnect_client_key"] = $matricula["escola"]["fastconnect_client_key"];

        $matriculaObj->Set("post", $_POST);
        $salvar = $matriculaObj->cadastrarFormaPagamento();

        if($salvar["sucesso"]) {
            $matriculaObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
            $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
            $matriculaObj->Set("ancora","financeiromatricula");
            $matriculaObj->Processando();
        } else {
            $mensagem["erro"] = $salvar["mensagem"];
        }
    } else {
        $mensagem["erro"] = "mensagem_permissao_workflow";
    }
} elseif ($_POST["acao"] == "renegociar_parcelas_salvar") {

    if($matricula["situacao"]["visualizacoes"][61]) {

        $matriculaObj->Set("id", $matricula["idmatricula"]);

        $_POST["idmantenedora"] = $matricula["sindicato"]["idmantenedora"];
        $_POST["idsindicato"] = $matricula["sindicato"]["idsindicato"];
        $_POST["idpessoa"] = $matricula["pessoa"]["idpessoa"];
        $_POST["idevento"] = $url[7];
        $matriculaObj->Set("post", $_POST);

        $salvar = $matriculaObj->renegociarParcelasMatricula();
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }
    if(!$salvar["sucesso"]){
        $mensagem["erro"] = $salvar["mensagem"];
    }
} elseif ($_POST["acao"] == "transferir_parcelas_salvar") {

    if ($matricula["situacao"]["visualizacoes"][65]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);

        /*$_POST["idmantenedora"] = $matricula["sindicato"]["idmantenedora"];
        $_POST["idsindicato"] = $matricula["sindicato"]["idsindicato"];
        $_POST["idpessoa"] = $matricula["pessoa"]["idpessoa"];
        $_POST["idevento"] = $url[6];*/
        $matriculaObj->Set("post", $_POST);
        $salvar = $matriculaObj->transferirParcelasMatricula();
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }
    if(!$salvar["sucesso"]){

        $matriculaObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/transferir_parcelas");
        $matriculaObj->Processando();
    }
} elseif ($_POST["acao"] == "alterar_negativacao_matricula") {
    if($matricula["situacao"]["visualizacoes"][51]) {
        $salvar = $matriculaObj->alterarNegativacao($matricula["idmatricula"], $_POST);
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }
    if($salvar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",'alterar_negativacao_sucesso');
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","financeiromatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
}

if($url[6]) {
    switch ($url[6]) {
        case "editar_varios":
            include("../classes/bandeirascartoes.class.php");
            $bandeirasCartoesObj = new Bandeiras_Cartoes();
            $bandeirasCartoesObj->Set("campos","*");
            $bandeirasCartoesObj->Set("ordem","asc");
            $bandeirasCartoesObj->Set("ordem_campo","nome");
            $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

            include("../classes/bancos.class.php");
            $bancosObj = new Bancos();
            $bancosObj->Set("campos","*");
            $bancosObj->Set("ordem","asc");
            $bancosObj->Set("ordem_campo","nome");
            $bancosObj->Set("limite","-1");
            $bancos = $bancosObj->ListarTodas();

            include_once("../classes/contas.class.php");
            $tiposContasObj = new Contas();
            $tiposContasObj->Set("campos","c.*");

            asort($_GET['contaseditar']);

            foreach ($_GET['contaseditar'] as $key => $value) {
                $tiposContasObj->Set("id",$value);
                $contas_editar[] = $tiposContasObj->Retornar();
            }

            if(count($contas_editar) == 0){
                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                exit;
            }

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","idevento, nome");
            $_GET["q"]["1|ativo_painel"] = "S";
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
            unset($_GET["q"]["1|ativo_painel"]);
            //contaseditar
            //print_r2($formapagamento_editar);exit;

            include("idiomas/".$config["idioma_padrao"]."/administrar.editar.pagamento.varios.php");
            include("telas/".$config["tela_padrao"]."/administrar.editar.pagamento.varios.php");
            exit;
            break;
        case "editarpagamento":
            include("../classes/bandeirascartoes.class.php");
            $bandeirasCartoesObj = new Bandeiras_Cartoes();
            $bandeirasCartoesObj->Set("campos","*");
            $bandeirasCartoesObj->Set("ordem","asc");
            $bandeirasCartoesObj->Set("ordem_campo","nome");
            $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

            include("../classes/bancos.class.php");
            $bancosObj = new Bancos();
            $bancosObj->Set("campos","*");
            $bancosObj->Set("ordem","asc");
            $bancosObj->Set("ordem_campo","nome");
            $bancosObj->Set("limite","-1");
            $bancos = $bancosObj->ListarTodas();

            include_once("../classes/contas.class.php");
            $tiposContasObj = new Contas();
            $tiposContasObj->Set("campos","c.*");
            $tiposContasObj->Set("id",$url[7]);
            $formapagamento_editar = $tiposContasObj->Retornar();
            $situacaoWorkflow = $tiposContasObj->RetornarSituacoesWorkflow();
            $situacaoWorkflowRelacionamento = array();
            foreach($tiposContasObj->RetornarRelacionamentosWorkflow($formapagamento_editar['idsituacao']) as $sit)
                $situacaoWorkflowRelacionamento[] = $sit['idsituacao_para'];

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","idevento, nome");
            $_GET["q"]["1|ativo_painel"] = "S";
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
            unset($_GET["q"]["1|ativo_painel"]);

            include("idiomas/".$config["idioma_padrao"]."/administrar.editar.pagamento.php");
            include("telas/".$config["tela_padrao"]."/administrar.editar.pagamento.php");
            exit;
            break;
        case "renegociar":

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","*");
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

            //print_r2($matricula_contas,true);
            include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.tipo.php");
            include("telas/".$config["tela_padrao"]."/administrar.renegociar.tipo.php");
            exit;
        case "transferir":

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","*");
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

            include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.tipo.php");
            include("telas/".$config["tela_padrao"]."/administrar.transferir.tipo.php");
            exit;
        case "renegociar_parcelas_selecionadas":
            if(!$_POST['parcelas_selecionadas'] && !$salvar['sucesso']){
                $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/renegociar_parcelas/".$url[8]);
                $matriculaObj->Processando();
            }

            $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], array_keys($_POST['parcelas_selecionadas']), $url[8]);

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","*");
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

            require("../classes/bandeirascartoes.class.php");
            $bandeirasCartoesObj = new Bandeiras_Cartoes();
            $bandeirasCartoesObj->Set("campos","*");
            $bandeirasCartoesObj->Set("ordem","asc");
            $bandeirasCartoesObj->Set("ordem_campo","nome");
            $bandeirasCartoesObj->Set("limite","-1");
            $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

            require("../classes/bancos.class.php");
            $bancosObj = new Bancos();
            $bancosObj->Set("campos","*");
            $bancosObj->Set("ordem","asc");
            $bancosObj->Set("ordem_campo","nome");
            $bancosObj->Set("limite","-1");
            $bancos = $bancosObj->ListarTodas();

            //print_r2($matricula_contas,true);
            include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.parcelas.selecionadas.php");
            include("telas/".$config["tela_padrao"]."/administrar.renegociar.parcelas.selecionadas.php");
            exit;
            break;
        case "transferir_parcelas_selecionadas":
            if(!$_POST['parcelas_selecionadas'] && !$salvar['sucesso']){
                $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/transferir_parcelas/".$url[8]);
                $matriculaObj->Processando();
            }

            $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], array_keys($_POST['parcelas_selecionadas']), $url[8]);

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->Set("ordem","asc");
            $eventosFinanceirosObj->Set("limite",-1);
            $eventosFinanceirosObj->Set("ordem_campo","nome");
            $eventosFinanceirosObj->Set("campos","*");
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

            require("../classes/bandeirascartoes.class.php");
            $bandeirasCartoesObj = new Bandeiras_Cartoes();
            $bandeirasCartoesObj->Set("campos","*");
            $bandeirasCartoesObj->Set("ordem","asc");
            $bandeirasCartoesObj->Set("ordem_campo","nome");
            $bandeirasCartoesObj->Set("limite","-1");
            $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

            require("../classes/bancos.class.php");
            $bancosObj = new Bancos();
            $bancosObj->Set("campos","*");
            $bancosObj->Set("ordem","asc");
            $bancosObj->Set("ordem_campo","nome");
            $bancosObj->Set("limite","-1");
            $bancos = $bancosObj->ListarTodas();

            $situacaoInicial = $matriculaObj->retornarSituacaoInicial();

            include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.parcelas.selecionadas.php");
            include("telas/".$config["tela_padrao"]."/administrar.transferir.parcelas.selecionadas.php");
            exit;
        case "renegociar_parcelas":
            $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], null, $url[7]);
            //print_r2($matricula_contas,true);
            include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.parcelas.php");
            include("telas/".$config["tela_padrao"]."/administrar.renegociar.parcelas.php");
            exit;
        case "transferir_parcelas":
            $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], null, $url[7]);

            include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.parcelas.php");
            include("telas/".$config["tela_padrao"]."/administrar.transferir.parcelas.php");
            exit;
        case "infocartao":
            include_once("../classes/contas.class.php");
            $tiposContasObj = new Contas();
            $tiposContasObj->Set("campos","c.*");
            $tiposContasObj->Set("id",$url[7]);
            $pagamento = $tiposContasObj->retornarPagamentoConta();

            include("idiomas/".$config["idioma_padrao"]."/administrar.financeiro.infocartao.php");
            include("telas/".$config["tela_padrao"]."/administrar.financeiro.infocartao.php");
            exit;
            break;
    }
}

require("../classes/bandeirascartoes.class.php");
$bandeirasCartoesObj = new Bandeiras_Cartoes();
$bandeirasCartoesObj->Set("campos","*");
$bandeirasCartoesObj->Set("ordem","asc");
$bandeirasCartoesObj->Set("ordem_campo","nome");
$bandeirasCartoesObj->Set("limite","-1");
$bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

require("../classes/bancos.class.php");
$bancosObj = new Bancos();
$bancosObj->Set("campos","*");
$bancosObj->Set("ordem","asc");
$bancosObj->Set("ordem_campo","nome");
$bancosObj->Set("limite","-1");
$bancos = $bancosObj->ListarTodas();

require("../classes/eventos.financeiros.class.php");
$eventosFinanceirosObj = new EventosFinanceiros();
$eventosFinanceirosObj->Set("ordem","asc");
$eventosFinanceirosObj->Set("limite",-1);
$eventosFinanceirosObj->Set("ordem_campo","nome");
$eventosFinanceirosObj->Set("campos","idevento, nome");
$_GET["q"]["1|ativo_painel"] = "S";
$eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
unset($_GET["q"]["1|ativo_painel"]);

$situacaoRenegociadaConta = $matriculaObj->retornarSituacaoRenegociadaConta();
$situacaoCanceladaConta = $matriculaObj->retornarSituacaoCanceladaConta();
$situacaoTransferidaConta = $matriculaObj->retornarSituacaoTransferidaConta();

$matricula["contas"] = $matriculaObj->RetornarContas();
$total_mensalidades = $matriculaObj->matricula["total_mensalidades"];

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

include("idiomas/".$config["idioma_padrao"]."/administrar.financeiro.php");
include("telas/".$config["tela_padrao"]."/administrar.financeiro.php");
?>