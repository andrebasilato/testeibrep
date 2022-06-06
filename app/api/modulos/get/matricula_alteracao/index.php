<?php

$diretorio = dirname(__FILE__);


require_once DIR_APP . '/classes/core.class.php';
require_once $diretorio . '/idioma.php';

$coreObj = new Core();
$coreObj->config = $config;

adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_SERVER['HTTP_EMAIL']) || !isset($_SERVER['HTTP_EMAIL'])) {
    adicionarCabecalhoJson();
    $retorno = ['codigo' => '400', 'mensagem' => $idioma['erro_parametros_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$email_escape = addslashes(strtolower($_SERVER['HTTP_EMAIL']));
$senha = senhaSegura($_SERVER['HTTP_SENHA'], $config['chaveLogin']);

try {
    adicionarCabecalhoJson();

    $sql = "SELECT 
                    idusuario,
                    nome
                FROM 
                    usuarios_adm
                WHERE 
                    email='{$email_escape}' AND
                    senha='{$senha}' AND 
                    ativo='S' AND 
                    ativo_login = 'S'";

    $usuario = $coreObj->retornarLinha($sql);

    if (!$usuario) {
        throw new \Exception("erro_senha_incorreta", 401);
    }

    $retorno = [];
    $retorno['codigo'] = 200;

    $ontem = \DateTime::createFromFormat('Y-m-d', (new \DateTime())->format('Y-m-d'))
        ->modify('-1 days');

    $coreObj->sql = "SELECT
              idmatricula 
           FROM 
              matriculas_historicos 
           WHERE 
              data_cad >= '" . $ontem->format('Y-m-d') . "'
           GROUP BY idmatricula";

    $coreObj->set('ordem_campo', "data_cad");
    $coreObj->set('ordem', "ASC");
    $coreObj->set('limite', -1);
    $matriculas = $coreObj->retornarLinhas();

    foreach ($matriculas as $matricula) {
        $linha = [];

        $coreObj->sql = "SELECT
                            m.idmatricula,
                            m.idpessoa,
                            mc.nome AS motivo_cancelamento,
                            mw.nome AS situacao,
                            s.nome AS sindicato,
                            c.nome AS curso,
                            e.nome_fantasia AS cfc,
                            v.nome AS atendente,
                            m.data_cad AS data_matricula,
                            m.data_registro,
                            m.data_conclusao,
                            m.data_expedicao,
                            m.bolsa,
                            m.combo,
                            em.nome AS empresa,
                            m.valor_contrato
                        FROM
                            matriculas m
                            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
                            INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                            INNER JOIN ofertas_cursos oc ON o.idoferta = oc.idoferta
                                                        AND c.idcurso = oc.idcurso
                                                        AND oc.possui_financeiro = 'S'
                            INNER JOIN escolas e ON (m.idescola = e.idescola)
                            INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao)
                            INNER JOIN sindicatos s ON (m.idsindicato = s.idsindicato)
                            LEFT JOIN vendedores v ON (m.idvendedor = v.idvendedor)
                            LEFT JOIN motivos_cancelamento mc ON (m.idmotivo_cancelamento = mc.idmotivo)
                            LEFT JOIN empresas em ON (m.idempresa = em.idempresa)
                        WHERE
                            m.ativo = 'S' AND
                            m.idmatricula = '" . intval($matricula['idmatricula']) ."'";


        $dados_matricula = $coreObj->retornarLinha($coreObj->sql);

        $coreObj->sql = "SELECT 
                            c.idconta, cw.nome AS situacao
                        FROM
                            contas c
                                INNER JOIN
                            contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                        WHERE
                            c.idmatricula = '" . intval($matricula['idmatricula']) ."'";

        $coreObj->set('ordem_campo', "c.idconta");
        $coreObj->set('ordem', "ASC");
        $coreObj->set('limite', -1);

        $parcelas = $coreObj->retornarLinhas();

        foreach ($parcelas as $parcela) {
            $dados_matricula['parcelas'][$parcela['idconta']]['situacao'] = $parcela['situacao'];
        }

        $coreObj->sql = "SELECT 
                            md.iddocumento, td.nome as tipo, md.arquivo_nome as nome
                        FROM
                            matriculas_documentos md
                                INNER JOIN
                            tipos_documentos td ON (md.idtipo = td.idtipo)
                        WHERE
                            md.idmatricula = '" . intval($matricula['idmatricula']) ."'";

        $coreObj->set('ordem_campo', "md.iddocumento");
        $coreObj->set('ordem', "ASC");
        $coreObj->set('limite', -1);

        $documentos = $coreObj->retornarLinhas();

        foreach ($documentos as $documento) {
            $dados_matricula['documentos'][$documento['iddocumento']]['tipo'] = $documento['tipo'];
            $dados_matricula['documentos'][$documento['iddocumento']]['nome'] = $documento['nome'];
        }

        $coreObj->sql = "SELECT 
                            data_cad
                        FROM
                            matriculas_historicos
                        WHERE
                            idmatricula = '" . intval($matricula['idmatricula']) ."'
                        ORDER BY
                            data_cad
                        DESC
                        LIMIT 1";

        $dados_matricula['data_ultima_modificacao'] = formataData($coreObj->retornarLinha($coreObj->sql)['data_cad'], 'br', 1);

        $coreObj->sql = "SELECT
                            p.idpessoa as idaluno,
                            p.nome,
                            p.sexo as genero,
                            p.data_nasc as data_nascimento,
                            p.cnh,
                            p.categoria,
                            p.rg,
                            p.ato_punitivo,
                            c.nome as cidade,
                            e.nome as estado, 
                            p.email,
                            p.celular,
                            p.data_cad as data_registro,
                            p.ultimo_acesso, 
                            l.nome as logradouro, 
                            cep, 
                            endereco, 
                            numero, 
                            bairro,
                            p.biometria
                        FROM
                            pessoas p
                            INNER JOIN cidades c ON p.idcidade = c.idcidade
                            INNER JOIN estados e ON p.idestado = e.idestado
                            LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
                        WHERE
                            p.idpessoa = '" . $dados_matricula['idpessoa'] . "' AND
                            p.ativo = 'S'";

        $dados_pessoa = $coreObj->retornarLinha($coreObj->sql);

        unset($dados_matricula['idpessoa']);
        $dados_matricula['data_matricula'] = formataData($dados_matricula['data_matricula'], 'br', 1);
        $dados_matricula['data_registro'] = formataData($dados_matricula['data_registro'], 'br', 0);
        $dados_matricula['data_conclusao'] = formataData($dados_matricula['data_conclusao'], 'br', 0);
        $dados_matricula['data_expedicao'] = formataData($dados_matricula['data_expedicao'], 'br', 0);
        $dados_matricula['biometria'] = $dados_pessoa['biometria'];

        $dados_pessoa['genero'] = ($dados_pessoa['genero'] == 'F') ? 'Feminino' : 'Masculino';
        $dados_pessoa['data_nascimento'] = formataData($dados_pessoa['data_nascimento'], 'br', 0);
        $dados_pessoa['data_registro'] = formataData($dados_pessoa['data_registro'], 'br', 1);
        $dados_pessoa['ultimo_acesso'] = formataData($dados_pessoa['ultimo_acesso'], 'br', 1);
        $dados_pessoa['endereco_completo'] = $dados_pessoa['logradouro'] . ' ' . $dados_pessoa['endereco'] . ', ' . $dados_pessoa['numero'] . ', ' . $dados_pessoa['bairro'] . ' - ' . $dados_pessoa['cep'] . ', ' . $dados_pessoa['cidade'] . ', ' . $dados_pessoa['estado'];
        unset($dados_pessoa['logradouro'], $dados_pessoa['endereco'], $dados_pessoa['numero'], $dados_pessoa['bairro'], $dados_pessoa['cep'], $dados_pessoa['cidade'], $dados_pessoa['estado'], $dados_pessoa['biometria']);

        $retorno['dados'][] = ['matricula' => $dados_matricula, 'aluno' => $dados_pessoa];
    }

    adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    adicionarCabecalhoJson($retorno['codigo']);
    echo json_encode($retorno);
}
