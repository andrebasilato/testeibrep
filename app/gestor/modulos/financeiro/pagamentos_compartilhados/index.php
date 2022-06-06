<?php
include("../classes/pagamentoscompartilhados.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Pagamentos_Compartilhados();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);
$linhaObj->Set("modulo", $url[0]);

if ($_POST["acao"] == "salvar" && !$_POST['parcelas_definidas']) { //&& $_POST['parcelas'] > 1
    $_POST['valor'] = str_replace(',', '.', str_replace('.', '', $_POST['valor']));
    $_POST['valor_pago'] = str_replace(',', '.', str_replace('.', '', $_POST['valor_pago']));
    $matriculas = $linhaObj->RetornarMatriculasEscolhidas($_POST['matriculas']);

    require("../classes/eventos.financeiros.class.php");
    $eventosFinanceirosObj = new EventosFinanceiros();
    $eventosFinanceirosObj->Set("ordem", "asc");
    $eventosFinanceirosObj->Set("limite", -1);
    $eventosFinanceirosObj->Set("ordem_campo", "nome");
    $eventosFinanceirosObj->Set("campos", "*");
    $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

    require("../classes/bandeirascartoes.class.php");
    $bandeirasCartoesObj = new Bandeiras_Cartoes();
    $bandeirasCartoesObj->Set("campos", "*");
    $bandeirasCartoesObj->Set("ordem", "asc");
    $bandeirasCartoesObj->Set("ordem_campo", "nome");
    $bandeirasCartoesObj->Set("limite", "-1");
    $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

    require("../classes/bancos.class.php");
    $bancosObj = new Bancos();
    $bancosObj->Set("campos", "*");
    $bancosObj->Set("ordem", "asc");
    $bancosObj->Set("ordem_campo", "nome");
    $bancosObj->Set("limite", "-1");
    $bancos = $bancosObj->ListarTodas();

    include("../classes/sindicatos.class.php");
    $linhaInsObj = new Sindicatos();
    $linhaInsObj->Set("idusuario", $usuario["idusuario"]);
    $linhaInsObj->Set("limite", -1);
    $linhaInsObj->Set("ordem_campo", 'i.nome');
    $linhaInsObj->Set("ordem", 'asc');
    $linhaInsObj->Set("campos", "i.idsindicato, i.nome, m.nome_fantasia as mantenedora");
    $sindicatosArray = $linhaInsObj->ListarTodas();

    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
    include("telas/" . $config["tela_padrao"] . "/formulario.php");
    exit();
} else if ($_POST["acao"] == "salvar") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->Cadastrar();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        $linhaObj->Processando();
    }

    $_POST['valor'] = str_replace(',', '.', str_replace('.', '', $_POST['valor']));
    $_POST['valor_pago'] = str_replace(',', '.', str_replace('.', '', $_POST['valor_pago']));
    $matriculas = $linhaObj->RetornarMatriculasEscolhidas($_POST['matriculas']);

} elseif ($_POST["acao"] == "remover") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->Remover();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "associar_matricula") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $salvar = $linhaObj->AssociarMatriculas(intval($url[3]), $_POST["matriculas"]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "associar_matricula_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "remover_matricula") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->RemoverMatriculas($url[3]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "remover_matricula_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "salvar_valores_matriculas") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->SalvarValoresMatriculas($url[3]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "salvar_valores_matricula_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST["acao"] == "editar_contas") {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->SalvarDadosContas($url[3]);

    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "salvar_dados_contas_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
        $linhaObj->Processando();
    }
}  elseif($_POST["acao"] == "alterar_situacao_conta") {

    $tiposContasObj = new Contas();
    $tiposContasObj->Set("idusuario",$usuario["idusuario"]);
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
        //$matriculaObj->AdicionarHistorico($matriculaObj->idusuario, "parcela_situacao", "modificou", $conta["idsituacao"], $_POST["situacao_para"], $_POST['idconta']);

        $tiposContasObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
        $tiposContasObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        //$tiposContasObj->Set("ancora","financeiromatricula");
        $tiposContasObj->Processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
}

if (isset($url[3]) && $url[3] != "apagar" && $url[3] != "areceber") {
    if ($url[3] == "cadastrar") {
        if ($url[4] == "json") {
            include("idiomas/" . $config["idioma_padrao"] . "/json.php");
            include("telas/" . $config["tela_padrao"] . "/json.php");
            exit;
        }

        require("../classes/eventos.financeiros.class.php");
        $eventosFinanceirosObj = new EventosFinanceiros();
        $eventosFinanceirosObj->Set("ordem", "asc");
        $eventosFinanceirosObj->Set("limite", -1);
        $eventosFinanceirosObj->Set("ordem_campo", "nome");
        $eventosFinanceirosObj->Set("campos", "*");
        $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();

        require("../classes/bandeirascartoes.class.php");
        $bandeirasCartoesObj = new Bandeiras_Cartoes();
        $bandeirasCartoesObj->Set("campos", "*");
        $bandeirasCartoesObj->Set("ordem", "asc");
        $bandeirasCartoesObj->Set("ordem_campo", "nome");
        $bandeirasCartoesObj->Set("limite", "-1");
        $bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

        require("../classes/bancos.class.php");
        $bancosObj = new Bancos();
        $bancosObj->Set("campos", "*");
        $bancosObj->Set("ordem", "asc");
        $bancosObj->Set("ordem_campo", "nome");
        $bancosObj->Set("limite", "-1");
        $bancos = $bancosObj->ListarTodas();

        include("../classes/sindicatos.class.php");
        $linhaInsObj = new Sindicatos();
        $linhaInsObj->Set("idusuario", $usuario["idusuario"]);
        $linhaInsObj->Set("limite", -1);
        $linhaInsObj->Set("ordem_campo", 'i.nome');
        $linhaInsObj->Set("ordem", 'asc');
        $linhaInsObj->Set("campos", "i.idsindicato, i.nome, m.nome_fantasia as mantenedora");
        $sindicatosArray = $linhaInsObj->ListarTodas();

        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
        exit();
    } else {
        $linhaObj->Set("id", intval($url[3]));
        $linhaObj->Set("campos", "pc.*, i.nome as sindicato");
        $linha = $linhaObj->Retornar();

        if ($linha) {
            switch ($url[4]) {
                case "editar":
                
                    if ($url[6] == 'editar_pagamento') {
                          /*include("../classes/bandeirascartoes.class.php");
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
                          $bancos = $bancosObj->ListarTodas();*/

                          include_once("../classes/contas.class.php");
                          $tiposContasObj = new Contas();
                          $tiposContasObj->Set("campos","c.*");
                          $tiposContasObj->Set("id",$url[5]);
                          $formapagamento_editar = $tiposContasObj->Retornar();
                          $situacaoWorkflow = $tiposContasObj->RetornarSituacoesWorkflow();
                          $situacaoWorkflowRelacionamento = array();
                          foreach($tiposContasObj->RetornarRelacionamentosWorkflow($formapagamento_editar['idsituacao']) as $sit)
                            $situacaoWorkflowRelacionamento[] = $sit['idsituacao_para'];

                          /*require("../classes/eventos.financeiros.class.php");
                          $eventosFinanceirosObj = new EventosFinanceiros();
                          $eventosFinanceirosObj->Set("ordem","asc");
                          $eventosFinanceirosObj->Set("limite",-1);
                          $eventosFinanceirosObj->Set("ordem_campo","nome");
                          $eventosFinanceirosObj->Set("campos","idevento, nome");
                          $_GET["q"]["1|ativo_painel"] = "S";
                          $eventosFinanceiros = $eventosFinanceirosObj->ListarTodas();
                          unset($_GET["q"]["1|ativo_painel"]);*/

                          include("idiomas/".$config["idioma_padrao"]."/editar.pagamento.php");
                          include("telas/".$config["tela_padrao"]."/editar.pagamento.php");
                          exit;
                    }
                
                    $linhaObj->Set("config", $config);
                    $contas = $linhaObj->RetornarContas($url[3]);

                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    include("idiomas/" . $config["idioma_padrao"] . "/editar.php");
                    include("telas/" . $config["tela_padrao"] . "/editar.php");
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
                    include("idiomas/" . $config["idioma_padrao"] . "/json.php");
                    include("telas/" . $config["tela_padrao"] . "/json.php");
                    break;
                case "matriculas":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");

                    $associacoesArray = $linhaObj->ListarMatriculasAssociadas($url[3]);

                    include("idiomas/" . $config["idioma_padrao"] . "/matriculas.php");
                    include("telas/" . $config["tela_padrao"] . "/matriculas.php");
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
    $linhaObj->Set("campos", "pc.*");
    $linhaObj->Set("tipo_conta", $url[3]);
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>
