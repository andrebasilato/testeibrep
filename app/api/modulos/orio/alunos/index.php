<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Pessoa.php';
require_once $diretorio . '/../classes/Matricula.php';
$funcoesComuns = new \OrIO\FuncoesComuns();

$funcoesComuns->adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

$token = $funcoesComuns->getHeaderToken();

if (!isset($token)) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '401', 'mensagem' => $idioma['erro_token_nao_informado']];
    echo json_encode($retorno);
    exit;
}

$pessoaObj = new Pessoa($funcoesComuns);
$matriculaObj = new Matricula($funcoesComuns);
$transacoesObj = new Transacoes();

define('INTERFACE_ALUNO', retornarInterface('alunos')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_ALUNO, 'S');

try {
    $retorno = [];
    $retorno['codigo'] = 200;

    $aluno = $funcoesComuns->autenticarPessoaPorToken($token);

    $pessoaObj->campos = '
        p.idpessoa,
        p.documento as cpf,
        p.nome,
        p.rg,
        p.rg_data_emissao,
        p.data_nasc as data_nascimento,
        p.telefone,
        p.email,
        pa.nome as pais,
        e.nome as estado,
        c.nome as cidade,
        p.estado_civil
        ';

    if (isset($url[3])) {
        if (!intval($url[3])) {
            $funcoesComuns->adicionarCabecalhoJson();
            $retorno = ['codigo' => '401', 'mensagem' => $idioma['erro_parametro_incorreto']];
            echo json_encode($retorno);
            exit;
        }

        $pessoaObj->id = $url[3];

        $consultaAluno = $pessoaObj->retornar();

        $consultaAluno['estado_civil'] = $estadocivil[$GLOBALS['config']["idioma_padrao"]][$consultaAluno['estado_civil']];

        $retorno['dados'] = $consultaAluno;
    } else {
        if (!empty($_GET['cpf'])) {
            $aluno = $pessoaObj->retornarPorCPF($_GET['cpf']);
            $aluno['estado_civil'] = $estadocivil["pt_br"][$aluno['estado_civil']];
            $retorno['dados'] = $aluno;
        } else if (!empty($_GET['nome'])) {
            $alunos = $pessoaObj->retornarPorNome($_GET['nome']);

            foreach ($alunos as $key => $aluno) {
                $alunos[$key]['estado_civil'] = $estadocivil["pt_br"][$aluno['estado_civil']];
            }

            $retorno['dados'] = $alunos;
        } else {
            $avas = $matriculaObj->retornarAvasAluno($aluno['idpessoa']);

            $colegas = [];
            foreach ($avas as $key => $ava) {
                if (!empty($ava['idava'])) {
                    $retornarColegas = $matriculaObj->retornarColegas($ava['idava'], $aluno['idpessoa']);
                    if (empty($retornarColegas)) {
                        continue;
                    }

                    $colegas[$ava['curso']] = $retornarColegas;
                }
            }

            $retorno['dados'] = $colegas;
        }
    }

    $transacoesObj->finalizaTransacao(null, 2);
    $funcoesComuns->adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    $transacoesObj->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
    $funcoesComuns->adicionarCabecalhoJson($retorno['codigo']);
    echo json_encode($retorno);
}

