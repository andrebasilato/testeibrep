<?php

require_once '../classes/pagseguro.class.php';
require_once '../classes/fastconnect.class.php';

$matricula['sindicato'] = $matriculaObj->retornarSindicato();
$matricula['pessoa'] = $matriculaObj->retornarPessoa();
$matricula['visualizacoes'] = $matriculaObj->retornarVisualizacoesSituacao($matricula['idsituacao']);
if ($_POST['acao'] == 'editar_pagamento') {
    $tiposContasObj = new Contas();
    $salvar = $tiposContasObj->set('idusuario', $usuario['idusuario'])
        ->set('modulo', $url[0])
        ->set('post', $_POST)
        ->alterarPagamento();

    if ($salvar['sucesso']) {
        $matriculaObj->adicionarHistorico($matriculaObj->idusuario, 'parcela', 'modificou', NULL, NULL, $_POST['idconta']);

        $tiposContasObj->set('pro_mensagem_idioma', 'editar_pagamento_sucesso')
            ->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/' . $url[5])
            ->set('ancora', 'financeiromatricula')
            ->processando();
    } elseif ($salvar['mensagem']) {
        $mensagem['erro'] = $salvar['mensagem'];
    } else {
        $mensagem['erro'] = 'editar_pagamento_erro';
    }
} elseif ($_POST['acao'] == 'editar_pagamento_massa') {
    $tiposContasObj = new Contas();
    $tiposContasObj->set("idusuario",$usuario["idusuario"]);
    $tiposContasObj->set("modulo",$url[0]);
    $salvar = $tiposContasObj->alterarPagamentoMassa($_POST);

    if ($salvar["sucesso"]) {
        foreach($_POST['idcontas'] as $ind => $val) {
            $matriculaObj->AdicionarHistorico($matriculaObj->idusuario, "parcela", "modificou", NULL, NULL, $val);
        }
        $tiposContasObj->set("pro_mensagem_idioma","editar_pagamentomassa_sucesso");
        $tiposContasObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $tiposContasObj->set("ancora","financeiromatricula");
        $tiposContasObj->processando();
    } else {
        $mensagem["erro"] = "editar_pagamentomassa_erro";
    }
} elseif ($_POST["acao"] == "alterar_situacao_conta") {
    $tiposContasObj = new Contas();
    $tiposContasObj->set("idusuario",$usuario["idusuario"]);
    $tiposContasObj->set("modulo",$url[0]);
    $tiposContasObj->set("id",intval($_POST['idconta']));
    $tiposContasObj->set("campos","c.*");
    $conta = $tiposContasObj->Retornar();

    if ($conta["situacao"]["visualizacoes"][1]) {
        $salvar = $tiposContasObj->AlterarSituacao($conta["idsituacao"],$_POST["situacao_para"]);
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }

    if ($salvar["sucesso"]) {
        $matriculaObj->AdicionarHistorico($matriculaObj->idusuario, "parcela_situacao", "modificou", $conta["idsituacao"], $_POST["situacao_para"], $_POST['idconta']);

        $tiposContasObj->set("pro_mensagem_idioma",$salvar["mensagem"].'_parcela');
        $tiposContasObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $tiposContasObj->set("ancora","financeiromatricula");
        $tiposContasObj->processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
} elseif ($_POST['acao'] == 'adicionar_financeiro') {

    $matriculaObj->verificaPermissao($perfil['permissoes'], $url[2].'|9');

    if ($matricula['situacao']['visualizacoes'][5]) {
        $matriculaObj->set('id', $matricula['idmatricula']);

        $_POST['idmantenedora'] = $matricula['sindicato']['idmantenedora'];
        $_POST['idsindicato'] = $matricula['sindicato']['idsindicato'];
        $_POST['idpessoa'] = $matricula['pessoa']['idpessoa'];
        $_POST['fastconnect_client_code'] = $matricula['escola']['fastconnect_client_code'];
        $_POST['fastconnect_client_key'] = $matricula['escola']['fastconnect_client_key'];
        $_POST['idescola'] = $matricula['escola']['idescola'];

        $matriculaObj->set('post', $_POST);
        $salvar = $matriculaObj->cadastrarFormaPagamento();

        if ($salvar['sucesso']) {
            $matriculaObj->set('pro_mensagem_idioma',$salvar['mensagem']);
            $matriculaObj->set('url','/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5]);
            $matriculaObj->set('ancora','financeiromatricula');
            $matriculaObj->processando();
        } else {
            $mensagem['erro'] = $salvar['mensagem'];
        }
    } else {
        $mensagem['erro'] = 'mensagem_permissao_workflow';
    }
} elseif ($_POST["acao"] == "renegociar_parcelas_salvar") {
  $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19");
  if ($matricula["situacao"]["visualizacoes"][20]) {

    $matriculaObj->set("id", $matricula["idmatricula"]);

    $_POST["idmantenedora"] = $matricula["sindicato"]["idmantenedora"];
    $_POST["idsindicato"] = $matricula["sindicato"]["idsindicato"];
    $_POST["idpessoa"] = $matricula["pessoa"]["idpessoa"];
    $_POST["idevento"] = $url[7];
    $matriculaObj->set("post", $_POST);

    $salvar = $matriculaObj->renegociarParcelasMatricula();
  } else {
    $salvar["sucesso"] = false;
    $salvar["mensagem"] = "mensagem_permissao_workflow";
  }
  if ($salvar["sucesso"]) {
    /*$matriculaObj->set("pro_mensagem_idioma",$salvar["mensagem"]);
    $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
    $matriculaObj->set("ancora","financeiromatricula");
    $matriculaObj->processando();*/
  } else {
    $mensagem["erro"] = $salvar["mensagem"];
  }
} elseif ($_POST["acao"] == "transferir_parcelas_salvar") {
  $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");

  if ($matricula["situacao"]["visualizacoes"][26]) {
    $matriculaObj->set("id", $matricula["idmatricula"]);

    /*$_POST["idmantenedora"] = $matricula["sindicato"]["idmantenedora"];
    $_POST["idsindicato"] = $matricula["sindicato"]["idsindicato"];
    $_POST["idpessoa"] = $matricula["pessoa"]["idpessoa"];
    $_POST["idevento"] = $url[6];*/
    $matriculaObj->set("post", $_POST);
    $salvar = $matriculaObj->transferirParcelasMatricula();
  } else {
    $salvar["sucesso"] = false;
    $salvar["mensagem"] = "mensagem_permissao_workflow";
  }
  if ($salvar["sucesso"]) {
    /*$matriculaObj->set("pro_mensagem_idioma",$salvar["mensagem"]);
    $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
    $matriculaObj->set("ancora","financeiromatricula");
    $matriculaObj->processando();*/
  } else {
    $matriculaObj->set("pro_mensagem_idioma",$salvar["mensagem"]);
    $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/transferir_parcelas");
    $matriculaObj->processando();
  }
} elseif ($_POST["acao"] == "alterar_negativacao_matricula") {
  #$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  if ($matricula["situacao"]["visualizacoes"][2]) {
    $salvar = $matriculaObj->alterarNegativacao($matricula["idmatricula"], $_POST);
  } else {
    $salvar["sucesso"] = false;
    $salvar["mensagem"] = "mensagem_permissao_workflow";
  }
  if ($salvar["sucesso"]) {
    $matriculaObj->set("pro_mensagem_idioma",'alterar_negativacao_sucesso');
    $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->set("ancora","financeiromatricula");
    $matriculaObj->processando();
  } else {
    $mensagem["erro"] = $salvar["mensagem"];
  }
} elseif ($_POST['acao'] == 'salvar_pagamento') {
    switch ($_POST['tipo_pagamento']) {
        case 'PS'://Se for PagSeguro
            $pagSeguroObj = new PagSeguro(null, $_POST['idconta']);
            $salvar = $pagSeguroObj->set('post', $_POST)
                ->set('idusuario', $usuario['idusuario'])
                ->set('modulo',  $url[0])
                ->criarTransacao();

            if ($salvar['sucesso']) {
                $pagSeguroObj->set('pro_mensagem_idioma', 'salvar_pagamento_sucesso')
                    ->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/' . $url[5])
                    ->processando();
            }
        break;
    }
}

if ($url[6]) {
    switch ($url[6]) {
        case "editar_varios":
          include("../classes/bandeirascartoes.class.php");
          $bandeirasCartoesObj = new Bandeiras_Cartoes();
          $bandeirasCartoesObj->set("campos","*");
          $bandeirasCartoesObj->set("ordem","asc");
          $bandeirasCartoesObj->set("ordem_campo","nome");
          $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

          include("../classes/bancos.class.php");
          $bancosObj = new Bancos();
          $bancosObj->set("campos","*");
          $bancosObj->set("ordem","asc");
          $bancosObj->set("ordem_campo","nome");
          $bancosObj->set("limite","-1");
          $bancos = $bancosObj->ListarTodas();

          include_once("../classes/contas.class.php");
          $tiposContasObj = new Contas();
          $tiposContasObj->set("campos","c.*");

          asort($_GET['contaseditar']);

          foreach ($_GET['contaseditar'] as $key => $value) {
            $tiposContasObj->set("id",$value);
            $contas_editar[] = $tiposContasObj->Retornar();
          }

          if (count($contas_editar) == 0) {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            exit;
          }

          require("../classes/eventos.financeiros.class.php");
          $eventosFinanceirosObj = new EventosFinanceiros();
          $eventosFinanceirosObj->set("ordem","asc");
          $eventosFinanceirosObj->set("limite",-1);
          $eventosFinanceirosObj->set("ordem_campo","nome");
          $eventosFinanceirosObj->set("campos","idevento, nome");
          $_GET["q"]["1|ativo_painel"] = "S";
          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
          unset($_GET["q"]["1|ativo_painel"]);
         //contaseditar

          include("idiomas/".$config["idioma_padrao"]."/administrar.editar.pagamento.varios.php");
          include("telas/".$config["tela_padrao"]."/administrar.editar.pagamento.varios.php");
          exit;
          break;
        case "editarpagamento":
            include("../classes/bandeirascartoes.class.php");
            $bandeirasCartoesObj = new Bandeiras_Cartoes();
            $bandeirasCartoesObj->set("campos","*");
            $bandeirasCartoesObj->set("ordem","asc");
            $bandeirasCartoesObj->set("ordem_campo","nome");
            $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

            include("../classes/bancos.class.php");
            $bancosObj = new Bancos();
            $bancosObj->set("campos","*");
            $bancosObj->set("ordem","asc");
            $bancosObj->set("ordem_campo","nome");
            $bancosObj->set("limite","-1");
            $bancos = $bancosObj->ListarTodas();

            include_once("../classes/contas.class.php");
            $tiposContasObj = new Contas();
            $tiposContasObj->set("campos","c.*, cw.pago");
            $tiposContasObj->set("id",$url[7]);
            $formapagamento_editar = $tiposContasObj->Retornar();
            $situacaoWorkflow = $tiposContasObj->RetornarSituacoesWorkflow();
            $situacaoWorkflowRelacionamento = array();
            foreach($tiposContasObj->RetornarRelacionamentosWorkflow($formapagamento_editar['idsituacao']) as $sit) {
                $situacaoWorkflowRelacionamento[] = $sit['idsituacao_para'];
            }

            require("../classes/eventos.financeiros.class.php");
            $eventosFinanceirosObj = new EventosFinanceiros();
            $eventosFinanceirosObj->set("ordem","asc");
            $eventosFinanceirosObj->set("limite",-1);
            $eventosFinanceirosObj->set("ordem_campo","nome");
            $eventosFinanceirosObj->set("campos","idevento, nome");
            $_GET["q"]["1|ativo_painel"] = "S";
            $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
            unset($_GET["q"]["1|ativo_painel"]);

            include 'idiomas/' . $config['idioma_padrao'] . '/administrar.editar.pagamento.php';
            include 'telas/' . $config['tela_padrao'] . '/administrar.editar.pagamento.php';
            exit;
            break;
        case 'dados_transacao':
            include_once("../classes/contas.class.php");
            $contaObj = new Contas();
            $conta = $contaObj->set('campos', 'm.idescola, c.forma_pagamento')
                ->set('id', $url[7])
                ->retornar(true, false);

            if ($conta['forma_pagamento'] == 10) {//PagSeguro
                $pagSeguroObj = new PagSeguro($conta['idescola']);
                $transacao = $pagSeguroObj->buscarTransacao($url[8]);

                unset(
                    $transacao['ativo'],
                    $transacao['gatewaySystem'],
                    $transacao['retorno_pagseguro'],
                    $transacao['cron']
                );
            }

            include 'idiomas/' . $config['idioma_padrao'] . '/administrar.dados.transacao.php';
            include 'telas/' . $config['tela_padrao'] . '/administrar.dados.transacao.php';
            exit;
            break;
        case "renegociar":
          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19");

          require("../classes/eventos.financeiros.class.php");
          $eventosFinanceirosObj = new EventosFinanceiros();
          $eventosFinanceirosObj->set("ordem","asc");
          $eventosFinanceirosObj->set("limite",-1);
          $eventosFinanceirosObj->set("ordem_campo","nome");
          $eventosFinanceirosObj->set("campos","*");
          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

          include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.tipo.php");
          include("telas/".$config["tela_padrao"]."/administrar.renegociar.tipo.php");
          exit;
        case "transferir":
          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");

          require("../classes/eventos.financeiros.class.php");
          $eventosFinanceirosObj = new EventosFinanceiros();
          $eventosFinanceirosObj->set("ordem","asc");
          $eventosFinanceirosObj->set("limite",-1);
          $eventosFinanceirosObj->set("ordem_campo","nome");
          $eventosFinanceirosObj->set("campos","*");
          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

          include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.tipo.php");
          include("telas/".$config["tela_padrao"]."/administrar.transferir.tipo.php");
          exit;
        case "renegociar_parcelas_selecionadas":
          if (!$_POST['parcelas_selecionadas'] && !$salvar['sucesso']) {
            $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/renegociar_parcelas/".$url[8]);
            $matriculaObj->processando();
          }

          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19");
          $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], array_keys($_POST['parcelas_selecionadas']), $url[8]);

          require("../classes/eventos.financeiros.class.php");
          $eventosFinanceirosObj = new EventosFinanceiros();
          $eventosFinanceirosObj->set("ordem","asc");
          $eventosFinanceirosObj->set("limite",-1);
          $eventosFinanceirosObj->set("ordem_campo","nome");
          $eventosFinanceirosObj->set("campos","*");
          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

          require("../classes/bandeirascartoes.class.php");
          $bandeirasCartoesObj = new Bandeiras_Cartoes();
          $bandeirasCartoesObj->set("campos","*");
          $bandeirasCartoesObj->set("ordem","asc");
          $bandeirasCartoesObj->set("ordem_campo","nome");
          $bandeirasCartoesObj->set("limite","-1");
          $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

          require("../classes/bancos.class.php");
          $bancosObj = new Bancos();
          $bancosObj->set("campos","*");
          $bancosObj->set("ordem","asc");
          $bancosObj->set("ordem_campo","nome");
          $bancosObj->set("limite","-1");
          $bancos = $bancosObj->ListarTodas();

          include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.parcelas.selecionadas.php");
          include("telas/".$config["tela_padrao"]."/administrar.renegociar.parcelas.selecionadas.php");
          exit;
          break;
        case "transferir_parcelas_selecionadas":
          if (!$_POST['parcelas_selecionadas'] && !$salvar['sucesso']) {
            $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/transferir_parcelas/".$url[8]);
            $matriculaObj->processando();
          }

          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");
          $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], array_keys($_POST['parcelas_selecionadas']), $url[8]);

          require("../classes/eventos.financeiros.class.php");
          $eventosFinanceirosObj = new EventosFinanceiros();
          $eventosFinanceirosObj->set("ordem","asc");
          $eventosFinanceirosObj->set("limite",-1);
          $eventosFinanceirosObj->set("ordem_campo","nome");
          $eventosFinanceirosObj->set("campos","*");
          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

          require("../classes/bandeirascartoes.class.php");
          $bandeirasCartoesObj = new Bandeiras_Cartoes();
          $bandeirasCartoesObj->set("campos","*");
          $bandeirasCartoesObj->set("ordem","asc");
          $bandeirasCartoesObj->set("ordem_campo","nome");
          $bandeirasCartoesObj->set("limite","-1");
          $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

          require("../classes/bancos.class.php");
          $bancosObj = new Bancos();
          $bancosObj->set("campos","*");
          $bancosObj->set("ordem","asc");
          $bancosObj->set("ordem_campo","nome");
          $bancosObj->set("limite","-1");
          $bancos = $bancosObj->ListarTodas();

          $situacaoInicial = $matriculaObj->retornarSituacaoInicial();

          include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.parcelas.selecionadas.php");
          include("telas/".$config["tela_padrao"]."/administrar.transferir.parcelas.selecionadas.php");
          exit;
        case "renegociar_parcelas":
          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19");
          $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], null, $url[7]);

          include("idiomas/".$config["idioma_padrao"]."/administrar.renegociar.parcelas.php");
          include("telas/".$config["tela_padrao"]."/administrar.renegociar.parcelas.php");
          exit;
        case "transferir_parcelas":
          $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");
          $matricula_contas = $matriculaObj->retornarContasNaoPagasMatricula($url[3], null, $url[7]);

          include("idiomas/".$config["idioma_padrao"]."/administrar.transferir.parcelas.php");
          include("telas/".$config["tela_padrao"]."/administrar.transferir.parcelas.php");
          exit;
        case "infocartao":
            include_once("../classes/contas.class.php");
            $tiposContasObj = new Contas();
            $tiposContasObj->set("campos","c.*");
            $tiposContasObj->set("id",$url[7]);
            $pagamento = $tiposContasObj->retornarPagamentoConta();

            include("idiomas/".$config["idioma_padrao"]."/administrar.financeiro.infocartao.php");
            include("telas/".$config["tela_padrao"]."/administrar.financeiro.infocartao.php");
            exit;
            break;
    }
}

//Se tiver o transactionCode é porque retornou do PagSeguro, assim já submete o formulário
if (! empty($_GET['transactionCode'])) {
    $pagSeguroObj = new PagSeguro($_GET['idescola']);
    $retornoTransacao = $pagSeguroObj->retornaTransacao($_GET['transactionCode']);

    $_POST['acao'] = 'salvar_pagamento';
    $_POST['idconta'] = str_replace('C_', '', $retornoTransacao['xml']->reference);
    $_POST['tipo_pagamento'] = 'PS';
    $_POST['codigo_transacao_pagseguro'] = $_GET['transactionCode'];
    $informacoes['url'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/' . $url[5];
    incluirLib('processar_post', $config, $informacoes);
    exit;
}

require("../classes/bandeirascartoes.class.php");
$bandeirasCartoesObj = new Bandeiras_Cartoes();
$bandeirasCartoesObj->set("campos","*");
$bandeirasCartoesObj->set("ordem","asc");
$bandeirasCartoesObj->set("ordem_campo","nome");
$bandeirasCartoesObj->set("limite","-1");
$bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

require("../classes/bancos.class.php");
$bancosObj = new Bancos();
$bancosObj->set("campos","*");
$bancosObj->set("ordem","asc");
$bancosObj->set("ordem_campo","nome");
$bancosObj->set("limite","-1");
$bancos = $bancosObj->ListarTodas();

require("../classes/eventos.financeiros.class.php");
$eventosFinanceirosObj = new EventosFinanceiros();
$eventosFinanceirosObj->set("ordem","asc");
$eventosFinanceirosObj->set("limite",-1);
$eventosFinanceirosObj->set("ordem_campo","nome");
$eventosFinanceirosObj->set("campos","idevento, nome");
$_GET["q"]["1|ativo_painel"] = "S";
$eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
unset($_GET["q"]["1|ativo_painel"]);

$situacaoRenegociadaConta = $matriculaObj->retornarSituacaoRenegociadaConta();
$situacaoCanceladaConta = $matriculaObj->retornarSituacaoCanceladaConta();
$situacaoTransferidaConta = $matriculaObj->retornarSituacaoTransferidaConta();

$matricula['contas'] = $matriculaObj->retornarContas();
$total_mensalidades = $matriculaObj->matricula["total_mensalidades"];

$matricula['oferta'] = $matriculaObj->retornarOferta();
$matricula['curso'] = $matriculaObj->retornarCurso();
$matricula['escola'] = $matriculaObj->retornarEscola();

include 'idiomas/' . $config['idioma_padrao'] . '/administrar.financeiro.php';
include 'telas/' . $config['tela_padrao'] . '/administrar.financeiro.php';
