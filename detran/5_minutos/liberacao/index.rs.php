<?php
require_once $caminho . '/app/classes/matriculas.class.php';
define('INTERFACE_DETRAN_RS_LIBERACAO', retornarInterface('detran_rs_liberacao')['id']);
$siglaEstado = 'RS';
$codTransacao = 431; //Consulta Processo do aluno

$matriculaObj = new Matriculas();

$sql = 'SELECT
        m.idmatricula,
        p.documento,
        e.detran_codigo,
        m.idcurso,
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
    limit 20';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    try {
    $arrayEnvio = [
        'codEmpresa' => $config['detran'][$siglaEstado]['codEmpresa'],
        'codCurso' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']],
        'cpfProfissional' => $config['detran'][$siglaEstado]['registro_instrutor'],
        'cpfAluno' => $linha['documento']
    ];

    $_POST = json_encode($arrayEnvio);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['detran'][$siglaEstado]['urlJSON'] . '/validaAluno');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'organizacao: ' . $config['detran'][$siglaEstado]['organizacao'],
            'matricula: ' . $config['detran'][$siglaEstado]['matricula'],
            'senha:' . $config['detran'][$siglaEstado]['senha']
        )
    );
    $transacoes->iniciaTransacao(INTERFACE_DETRAN_RS_LIBERACAO, 'E');
    $chResult = curl_exec($ch);
    $retorno = $chResult;
    $retornoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($chResult === false) {
        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            $transacoes->set("json", $retorno);
            $transacoes->finalizaTransacao(null, 3);
            $transacoes->set("json", null);
        }
        continue;
    }

    $matriculaObj->executaSql('BEGIN');

    if (! empty($retornoHttpCode)) {
        switch ($retornoHttpCode) {
            case 200:
                $json = json_decode($retorno);
                $sql = '
                    UPDATE matriculas
                SET
                    detran_situacao = "LI",
                    cod_ticket = "' . $json->codTicket . '",
                    codigo_diploma = "' . $json->renach . '",
                    data_inicio_curso = NOW()
                WHERE idmatricula = ' . $linha['idmatricula'];

                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
                $transacoes->set("json", $retorno);
                $transacoes->finalizaTransacao(null, 2);
                $transacoes->set("json", null);
                break;
            case 400:
                $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
                $transacoes->set("json", $retorno);
                $transacoes->finalizaTransacao(null, 5);
                $transacoes->set("json", null);

                break;
        }
    }

    salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $_POST);
    $matriculaObj->executaSql('COMMIT');
    } catch (Exception $e) {
        $transacoes->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
        echo $e->getMessage();
    }
}
