<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Curso.php';
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

$cursoObj = new Curso($funcoesComuns);
$matriculaObj = new Matricula($funcoesComuns);

define('INTERFACE_CURSOS', retornarInterface('cursos')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_CURSOS, 'S');

try {
    $retorno = [];
    $retorno['codigo'] = 200;

    $aluno = $funcoesComuns->autenticarPessoaPorToken($token);

    $cursoObj->campos = 'idcurso,
            nome,
            abreviacao,
            codigo,
            tipo,
            livre,
            email,
            carga_horaria_presencial,
            carga_horaria_distancia,
            carga_horaria_total,
            exibir_historico,
            cofeci,
            se_quilometragem,
            ordem,
            nota_max,
            documentos_obrigatorios';

    if (isset($url[3])) {
        if (!intval($url[3])) {
            $funcoesComuns->adicionarCabecalhoJson();
            $retorno = ['codigo' => '401', 'mensagem' => $idioma['erro_parametro_incorreto']];
            echo json_encode($retorno);
            exit;
        }
        $cursoObj->id = $url[3];

        $curso = $cursoObj->retornar();
        if ($curso){
            $curso['tipo'] = $tipo_disciplina[$GLOBALS['config']["idioma_padrao"]][$curso['tipo']];
            $curso['livre'] = $curso_livre[$GLOBALS['config']["idioma_padrao"]][$curso['livre']];
        }

        $retorno['dados'] = $curso;
    } else {

        if (!empty($_GET['nome'])) {
            $cursos = $cursoObj->retornarPorNome($_GET['nome']);
            foreach ($cursos as $key => $curso) {
                $cursos[$key]['tipo'] = $tipo_disciplina[$GLOBALS['config']["idioma_padrao"]][$curso['tipo']];
                $cursos[$key]['livre'] = $curso_livre[$GLOBALS['config']["idioma_padrao"]][$curso['livre']];
            }
            $retorno['dados'] = $cursos;
        } else {

            $avas = $matriculaObj->retornarAvasAluno($aluno['idpessoa']);

            $cursoObj->campos = 'idcurso,
            nome,
            abreviacao,
            codigo,
            tipo,
            livre,
            email,
            carga_horaria_presencial,
            carga_horaria_distancia,
            carga_horaria_total,
            exibir_historico,
            cofeci,
            se_quilometragem,
            ordem,
            nota_max,
            documentos_obrigatorios';

            $cursos = [];
            foreach ($avas as $key => $ava) {
                $cursoObj->id = $ava['idcurso'];
                $cursos[$ava['curso']] = $cursoObj->retornar();
            }

            foreach ($cursos as $key => $curso) {
                $curso['tipo'] = $tipo_disciplina[$GLOBALS['config']["idioma_padrao"]][$curso['tipo']];
                $curso['livre'] = $curso_livre[$GLOBALS['config']["idioma_padrao"]][$curso['livre']];
                $retorno['dados'][] = $curso;
            }
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

