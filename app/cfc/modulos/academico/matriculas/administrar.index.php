<?php
$diploma = $matriculaObj->hasDiploma(Request::url(4));
if ($_POST['acao'] == 'alterar_situacao') {
    if ($matricula['situacao']['visualizacoes'][50]) {
        $situacaoInicial = $matriculaObj->retornarSituacaoInicial();

        if ($matricula['idsituacao'] != $situacaoInicial['idsituacao']) {
            $matriculaObj->Set('post',$_POST);
            $alterar = $matriculaObj->alterarSituacao($matricula['idsituacao'], $_POST['situacao_para']);
        } else {
            $alterar['sucesso'] = false;
            $alterar['mensagem'] = 'erro_mudar_situacao_situacao_inicial';
        }
    } else {
        $alterar['sucesso'] = false;
        $alterar['mensagem'] = 'mensagem_permissao_workflow';
    }

    if($alterar['sucesso']){
        $matriculaObj->Set('pro_mensagem_idioma', $alterar['mensagem']);
        $matriculaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $matriculaObj->Set('ancora', 'situacaomatricula');
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $alterar["mensagem"];
    }
} elseif ($_POST['acao'] == 'aprovar_matricula') {
    if ($matricula['situacao']['visualizacoes'][50]) {
        $salvar = $matriculaObj->aprovarMatricula($matricula['idmatricula']);
    } else {
        $salvar['sucesso'] = false;
        $salvar['mensagem'] = 'mensagem_permissao_workflow';
    }

    if ($salvar['sucesso']){
        $matriculaObj->Set('pro_mensagem_idioma', $salvar['mensagem']);
        $matriculaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $matriculaObj->Set('ancora', 'situacaomatricula');
        $matriculaObj->Processando();
    } else {
        $mensagem['erro'] = $salvar['mensagem'];
    }
} elseif($_POST["acao"] == "alterar_dados_matricula") {
    if($matricula["situacao"]["visualizacoes"][51]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("post",$_POST);
        $salvar = $matriculaObj->editarDadosMatricula();
    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }
    if($salvar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $matriculaObj->Set("ancora","dadosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
} elseif($_POST["acao"] == "editar_dados_aluno") {

    require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/config.formulario.php');

    $config["formulario"] = $config["formulario"];
    $config["banco"] = $config["banco_pessoas"];

    unset($config['formulario'][1]['campos'][0]);
    unset($config['formulario'][1]['campos'][1]);
    unset($config['formulario'][1]['campos'][2]);
    unset($config['formulario'][5]);
    unset($config['formulario'][8]);

    // remove acesso a edição de senhas
    if ($config['formulario'][5]) {
        unset($config['formulario'][5]);
    }

    // remove avatar do formulário
    if ($config['formulario'][8]) {
        unset($config['formulario'][8]);
    }

    $pessoaObj = new Pessoas();
    $pessoaObj->set("idescola", $usuario["idescola"]);
    $pessoaObj->set("monitora_onde", $config["monitoramento_pessoa"]["onde"]);
    $pessoaObj->set("config", $config);
    $pessoaObj->set("post", $_POST);
    $salvar = $pessoaObj->modificar();

    if($salvar["sucesso"]){
        $matriculaObj->set("id",intval($url[3]));
        $matricula = $matriculaObj->Retornar();

        $matriculaObj->set("pro_mensagem_idioma","editar_dados_aluno_secesso");
        $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $matriculaObj->set("ancora","dadosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = "editar_dados_aluno_erro";
    }
} elseif($_POST["acao"] == "editar_dados_associado") {
    require realpath(
        dirname(__FILE__).
        '/../../../../telascompartilhadas/cadastros/pessoas/config.formulario.php');
    $pessoaObj = new Pessoas();

    //onfig["formulario"] = $config["formulario"];
    //onfig["banco"] = $config["banco_pessoas"];

    $pessoaObj->config['formulario'][0] = $config['formulario_pessoas'][0];
    $pessoaObj->config["banco"] = $config["banco_pessoas"];
    //print_r2($pessoaObj->config['formulario'], true);

    unset($pessoaObj->config['formulario'][1]);
    unset($pessoaObj->config['formulario'][5]);
    unset($pessoaObj->config['formulario'][8]);

    if( $_POST["documento_tipo"] == 'cpf'){
        unset($pessoaObj->config['formulario'][0]['campos'][0]);
        unset($pessoaObj->config['formulario'][0]['campos'][1]);
        unset($pessoaObj->config['formulario'][0]['campos'][2]);
        //unset($pessoaObj->config['formulario'][0]['campos'][3]);
        //unset($pessoaObj->config['formulario'][0]['campos'][4]);
        //unset($pessoaObj->config['formulario'][0]['campos'][5]);
        unset($pessoaObj->config['formulario'][0]['campos'][6]);
        unset($pessoaObj->config['formulario'][0]['campos'][7]);
        unset($pessoaObj->config['formulario'][0]['campos'][8]);
        unset($pessoaObj->config['formulario'][0]['campos'][14]);
        unset($pessoaObj->config['formulario'][0]['campos'][26]);

        unset($pessoaObj->config['formulario'][1]['campos'][6]);
        unset($pessoaObj->config['formulario'][1]['campos'][7]);
        unset($pessoaObj->config['formulario'][1]['campos'][8]);
        unset($pessoaObj->config['formulario'][1]['campos'][15]);
    }else{
        // Removendo dados PF
        unset($pessoaObj->config['formulario'][2]);
        unset($pessoaObj->config['formulario'][9]);
        unset($pessoaObj->config['formulario'][0]['campos'][2]);
        unset($pessoaObj->config['formulario'][0]['campos'][3]);
        unset($pessoaObj->config['formulario'][0]['campos'][4]);
        unset($pessoaObj->config['formulario'][0]['campos'][5]);
        unset($pessoaObj->config['formulario'][0]['campos'][10]);
        unset($pessoaObj->config['formulario'][0]['campos'][11]);
        unset($pessoaObj->config['formulario'][0]['campos'][12]);
        unset($pessoaObj->config['formulario'][0]['campos'][14]);
        unset($pessoaObj->config['formulario'][0]['campos'][15]);
        unset($pessoaObj->config['formulario'][0]['campos'][16]);
        unset($pessoaObj->config['formulario'][0]['campos'][17]);
        unset($pessoaObj->config['formulario'][0]['campos'][18]);
        unset($pessoaObj->config['formulario'][0]['campos'][19]);
        unset($pessoaObj->config['formulario'][0]['campos'][20]);
        unset($pessoaObj->config['formulario'][7]['campos'][1]);
        unset($pessoaObj->config['formulario'][0]['campos'][26]);
        unset($pessoaObj->config['formulario'][6]['campos'][4]);
    }

    //$pessoaObj->set("formulario",$_POST);

    $pessoaObj->set("idescola",$usuario["idescola"]);
    $pessoaObj->set("monitora_onde",$config["monitoramento_pessoa"]["onde"]);
    //$pessoaObj->set("config",$config);
    $pessoaObj->set("post",$_POST);

    $salvar = $pessoaObj->modificar();

    if($salvar["sucesso"]){
        $matriculaObj->set("id",intval($url[3]));
        $matricula = $matriculaObj->Retornar();

        $matriculaObj->set("pro_mensagem_idioma","editar_dados_associado_secesso");
        $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $matriculaObj->set("ancora","associados");
        $matriculaObj->processando();
    } else {
        $mensagem["erro"] = "editar_dados_aluno_erro";
    }
} elseif($_POST["acao"] == "aprovar_comercial") {
    if($matricula["situacao"]["visualizacoes"][59]) {
        $aprovar = $matriculaObj->aprovarComercialMatricula();
    } else {
        $aprovar["sucesso"] = false;
        $aprovar["mensagem"] = "mensagem_permissao_workflow";
    }
    if($aprovar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$aprovar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $matriculaObj->Set("ancora","mensagensmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $aprovar["mensagem"];
    }
}


if($url[6]) {
    switch ($url[6]) {
        case "editardadosaluno":
            $pessoaObj = new Pessoas();

            require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/config.formulario.php');
            require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/idiomas/pt_br/formulario.php');
            $config["formulario"] = $config["formulario"];
            $config["banco"] = $config["banco_pessoas"];

            unset($config['formulario'][1]['campos'][0]);
            unset($config['formulario'][1]['campos'][2]);
            unset($config['formulario'][5]);
            unset($config['formulario'][8]);

            $config['formulario'][1]['campos'][3]['input_hidden']  = 0;
            $config['formulario'][1]['campos'][4]['input_hidden']  = 0;
            $config['formulario'][1]['campos'][5]['input_hidden']  = 0;
            $config['formulario'][1]['campos'][6]['input_hidden']  = 0;


            // remove acesso a edição de senhas
            if ($config['formulario'][5]) {
                unset($config['formulario'][5]);
            }

            // remove avatar do formulário
            if ($config['formulario'][8]) {
                unset($config['formulario'][8]);
            }

            $pessoaObj->Set("config", $config);
            $pessoaObj->Set("idescola", $usuario["idescola"]);
            $pessoaObj->Set("campos", "p.*, pa.nome as pais");
            $pessoaObj->Set("id", $matricula["idpessoa"]);
            $aluno = $pessoaObj->Retornar();

            include("idiomas/".$config["idioma_padrao"]."/administrar.dadosaluno.php");
            include("telas/".$config["tela_padrao"]."/administrar.dadosaluno.php");
            exit;
            break;
        case "editardadosassociado":
            $pessoaObj = new Pessoas;

            require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/config.formulario.php');
            require realpath(dirname(__FILE__).'/../../../../telascompartilhadas/cadastros/pessoas/idiomas/pt_br/formulario.php');

            $pessoaObj->Set("idescola",$usuario["idescola"]);
            $pessoaObj->Set("campos","p.*, pa.nome as pais");
            $pessoaObj->Set("id",intval($url[7]));
            $aluno = $pessoaObj->Retornar();

            $pessoaObj->config['formulario_pessoas'][1] = $config['formulario'][1];
            //$pessoaObj->config['formulario_pessoas'][4] = $config['formulario'][4];
            $pessoaObj->config['formulario_pessoas'][6] = $config['formulario'][6];
            $pessoaObj->config['formulario_pessoas'][7] = $config['formulario'][7];

            $pessoaObj->config["banco"] = $config["banco_pessoas"];

            unset($pessoaObj->config['formulario_pessoas'][5]);
            unset($pessoaObj->config['formulario_pessoas'][8]);

            if( $aluno["documento_tipo"] == 'cpf'){
                //unset($pessoaObj->config['formulario_pessoas'][0]['campos'][0]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][1]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][2]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][3]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][4]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][5]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][6]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][7]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][8]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][14]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][26]);


                $pessoaObj->config['formulario_pessoas'][1]['campos'][1]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][3]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][4]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][5]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][6]['input_hidden'] = 0;

                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][2]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][7]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][8]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][9]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][15]);
                unset($pessoaObj->config['formulario_pessoas'][7]['campos'][4]);

            }else{
                unset($pessoaObj->config['formulario_pessoas'][9]);
                //unset($pessoaObj->config['formulario_pessoas'][2]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][2]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][3]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][4]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][5]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][10]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][11]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][12]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][14]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][15]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][16]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][17]);
                unset($pessoaObj->config['formulario_pessoas'][0]['campos'][26]);

                // Removendo dados PF
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][1]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][3]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][4]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][5]);
                unset($pessoaObj->config['formulario_pessoas'][1]['campos'][6]);
                unset($pessoaObj->config['formulario_pessoas'][6]['campos'][3]);
                unset($pessoaObj->config['formulario_pessoas'][6]['campos'][4]);
                unset($pessoaObj->config['formulario_pessoas'][6]['campos'][5]);
                unset($pessoaObj->config['formulario_pessoas'][7]['campos'][0]);
                unset($pessoaObj->config['formulario_pessoas'][7]['campos'][1]);
                unset($pessoaObj->config['formulario_pessoas'][7]['campos'][4]);

                $pessoaObj->config['formulario_pessoas'][1]['campos'][7]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][8]['input_hidden'] = 0;
                $pessoaObj->config['formulario_pessoas'][1]['campos'][9]['input_hidden'] = 0;
            }
            $config["formulario"] = $pessoaObj->config['formulario_pessoas'];

            include("idiomas/".$config["idioma_padrao"]."/administrar.dadosassociado.php");
            include("telas/".$config["tela_padrao"]."/administrar.dadosassociado.php");
            exit;
            break;
        case "cancelarmatricula":
            include("../classes/motivoscancelamento.class.php");
            $motivosCancelamentoObj = new Motivos_Cancelamento();
            $motivosCancelamentoObj->Set("ordem","asc");
            $motivosCancelamentoObj->Set("limite","-1");
            $motivosCancelamentoObj->Set("ordem_campo","nome");
            $motivosCancelamentoObj->Set("campos","*");
            $_GET["q"]["1|ativo_painel"] = "S";
            $motivosCancelamento = $motivosCancelamentoObj->ListarTodas();
            unset($_GET["q"]["1|ativo_painel"]);
            $situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();
            include("idiomas/".$config["idioma_padrao"]."/administrar.cancelar.php");
            include("telas/".$config["tela_padrao"]."/administrar.cancelar.php");
            exit;
            break;
        case "inativarmatricula":
            include("../classes/motivosinatividade.class.php");
            $motivosInatividadObj = new Motivos_Inatividade();
            $motivosInatividadObj->Set("ordem","asc");
            $motivosInatividadObj->Set("limite","-1");
            $motivosInatividadObj->Set("ordem_campo","nome");
            $motivosInatividadObj->Set("campos","*");
            $_GET["q"]["1|ativo_painel"] = "S";
            $motivosInativar = $motivosInatividadObj->ListarTodas();
            unset($_GET["q"]["1|ativo_painel"]);
            $situacaoInativa = $matriculaObj->retornarSituacaoInativa();
            include("idiomas/".$config["idioma_padrao"]."/administrar.inativar.php");
            include("telas/".$config["tela_padrao"]."/administrar.inativar.php");
            exit;
            break;
        case "editardadosmatricula":
            include("idiomas/".$config["idioma_padrao"]."/administrar.dadosmatricula.php");
            include("telas/".$config["tela_padrao"]."/administrar.dadosmatricula.php");
            exit;
            break;
    }
}

$matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);
$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();
$matricula['turma'] = $matriculaObj->RetornarTurma();
$matricula['sindicato'] = $matriculaObj->RetornarSindicato();
$matricula['vendedor'] = $matriculaObj->RetornarVendedor();
//$matricula['associados'] = $matriculaObj->RetornarAssociados();
$matricula['curriculo'] = $matriculaObj->RetornarCurriculo();

$situacaoWorkflow = $matriculaObj->RetornarSituacoesWorkflow();
$situacaoWorkflowRelacionamento = array();
foreach($matriculaObj->RetornarRelacionamentosWorkflow($matricula['idsituacao']) as $situacao) {
    $situacaoWorkflowRelacionamento[] = $situacao['idsituacao_para'];
}
$situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();
$situacaoInativa = $matriculaObj->retornarSituacaoInativa();

$relacionamentoComercialObj = new RelacionamentosComerciais();
$relacionamentoComercial = $relacionamentoComercialObj->verificaExisteRelacionamento($matricula['pessoa']['email']);

require('../classes/solicitantesbolsas.class.php');
$solicitanteObj = new Solicitantes_Bolsas();
$solicitanteObj->Set('ordem','asc');
$solicitanteObj->Set('limite',-1);
$solicitanteObj->Set('ordem_campo','nome');
$solicitanteObj->Set('campos','idsolicitante, nome');
$solicitantes = $solicitanteObj->ListarTodas();

require('../classes/empresas.class.php');
$empresaObj = new Empresas();
$empresaObj->Set('ordem','asc');
$empresaObj->Set('limite',-1);
$empresaObj->Set('ordem_campo','e.nome');
$empresaObj->Set('campos','e.idempresa, e.nome');
$empresas = $empresaObj->ListarTodas();

require('../classes/ofertas.class.php');
$ofertaObj = new Ofertas();
$ofertaObj->Set('idescola',$usuario['idescola']);
$ofertaObj->Set('id',$matricula['oferta']['idoferta']);
$ofertaObj->Set('ordem','asc');
$ofertaObj->Set('limite',-1);
$ofertaObj->Set('ordem_campo','p.nome_fantasia');
$ofertaObj->Set('campos','ocp.*, p.nome_fantasia as escola');
$_GET['q']['1|ocp.idcurso'] = $matricula['curso']['idcurso'];
$_GET['q']['1|ocp.ignorar'] = 'N';
$escolas = $ofertaObj->ListarCursosEscolasMatricula();
unset($_GET['q']['1|ocp.idcurso']);
unset($_GET['q']['1|ocp.ignorar']);

require('../classes/vendedores.class.php');
$vendedorObj = new Vendedores();
$vendedores = $vendedorObj->retornarVendedoresSindicatos($matricula['idsindicato']);

include("../classes/bandeirascartoes.class.php");
$bandeirasCartoesObj = new Bandeiras_Cartoes();
$bandeirasCartoesObj->Set("campos","*");
$bandeirasCartoesObj->Set("ordem","asc");
$bandeirasCartoesObj->Set("ordem_campo","nome");
$bandeirasCartoes = $bandeirasCartoesObj->ListarTodas();

$matriculasAlunoObj = new Matriculas();
$_GET["q"]['1|m.idpessoa'] = $matricula['idpessoa'];
$matriculaObj->Set("naotraz",$matricula['idmatricula']);
$matriculaObj->Set("ordem", "desc");
$matriculaObj->Set("limite", -1);
$matriculaObj->Set("ordem_campo", $config["banco"]["primaria"]);
$matriculaObj->Set("campos", "m.* ");
$dadosMatriculasAluno = $matriculaObj->ListarTodas();

$situacaoInicial = $matriculaObj->retornarSituacaoInicial();

include('idiomas/' . $config['idioma_padrao'] . '/administrar.php');
include('telas/' . $config['tela_padrao'] . '/administrar.php');
