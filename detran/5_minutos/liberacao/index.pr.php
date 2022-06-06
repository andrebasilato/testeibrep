<?php
require_once $caminho . '/app/classes/matriculas.class.php';
require_once $caminho . '/detran/lib/restsecurity/ConnectionFactory.php';
define('INTERFACE_DETRAN_PR_LIBERACAO', retornarInterface('detran_pr_liberacao')['id']);
use detran\restsecurity\ConnectionFactory as ConnectionFactory;

$siglaEstado = 'PR';
$codTransacao = 431; //Consulta Processo do aluno

$matriculaObj = new Matriculas();

$sql = 'SELECT
        m.idmatricula,
        p.documento,
        e.detran_codigo,
        p.documento,
        p.cnh,
        p.data_nasc,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_situacao" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND
        e.idestado = ' . $estadosDetran[$siglaEstado] . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "AL" AND
        e.detran_codigo IS NOT NULL AND
        cw.fim = "N" AND
        cw.inativa = "N" AND
        cw.cancelada = "N"
    ORDER BY data_ultimo_historico ASC
    limit 25';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    try {
        $connection = ConnectionFactory::getConnection(
            $config['detran'][$siglaEstado]['id'],
            $config['detran'][$siglaEstado]['chave']
        );

        $_GET = $config['detran'][$siglaEstado]['urlJSON'] .
                    "/rest/servico/reciclagem/ead/certificado/verificar/{$linha['cnh']}/{$linha['documento']}/{$linha['data_nasc']}";
        $result = $connection->get($_GET);
        $connection = null;
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_PR_LIBERACAO, 'E');
        $retorno = $result->getBody();
        $retornoHttpCode = $result->getHttpCode();

        if ($retornoHttpCode != 200) {
            $matriculaObj->executaSql('BEGIN');
            if ($linha['acao_historico'] != 'detran_nao_respondeu') {
                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $_GET);
            $transacoes->finalizaTransacao(null, 3, null, $retorno);
            $matriculaObj->executaSql('COMMIT');
            continue;
        }

        $matriculaObj->executaSql('BEGIN');

        if (!empty($retornoHttpCode)) {
            switch ($retornoHttpCode) {
                case 200:
                    $json = json_decode($retorno);
                    if ($json->validacaoOk) {
                        $sql = '
                        UPDATE matriculas
                    SET
                        detran_situacao = "LI",
                        data_inicio_curso = NOW()
                    WHERE idmatricula = ' . $linha['idmatricula'];

                        $matriculaObj->executaSql($sql);
                        $matriculaObj->set('id', $linha['idmatricula'])
                            ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                        $transacoes->set("json", $retorno);
                        $transacoes->finalizaTransacao(null, 2);
                        $transacoes->set("json", null);

                    } else {
                        $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
                        $matriculaObj->executaSql($sql);

                        $matriculaObj->set('id', $linha['idmatricula'])
                            ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);

                        $transacoes->set("json", $retorno);
                        $transacoes->finalizaTransacao(null, 5);
                        $transacoes->set("json", null);
                    }
                    break;
            }
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $_GET);
        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $e) {
        $transacoes->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
        echo $e->getMessage();
    }
}
