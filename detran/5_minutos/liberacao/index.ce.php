<?php

require_once $caminho . '/app/classes/matriculas.class.php';

$siglaEstado = 'CE';
$codTransacao = 431; //Consulta Processo do aluno

$matriculaObj = new Matriculas();

$sql = 'SELECT
        m.idmatricula,
        p.documento,
        c.idcurso,
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
        cw.fim = "N" AND
        cw.inativa = "N" AND
        cw.cancelada = "N"
    ORDER BY data_ultimo_historico ASC
    limit 10';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {

    $arrayEnvio = [
        'chaveAcesso' => $config['detran'][$siglaEstado]['chaveAcesso'],
        'cpf' => $linha['documento'],
        'codCurso' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']]
    ];

    $jsonEnvio = json_encode($arrayEnvio);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['detran'][$siglaEstado]['urlJSON'] . '/matricularCondutor');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEnvio);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'User-Agent: Chrome/84.0.4147.125 Safari/537.36',
            'chaveAcesso: ' . $config['detran'][$siglaEstado]['chaveAcesso']
        )
    );

    $chResult = curl_exec($ch);
    $retorno = $chResult;
    $retornoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($chResult === false) {
        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
        continue;
    }

    $matriculaObj->executaSql('BEGIN');

    if ($retorno['mensagemRetorno']['codigo'] == 'E000') {
                $json = json_decode($retorno);
                $sql = '
                    UPDATE matriculas
                SET 
                    detran_situacao = "LI",
                    detran_numero = "' . $json->idMatricula . '"
                WHERE idmatricula = ' . $linha['idmatricula'];

                $matriculaObj->executaSql($sql);

                $matriculaObj->set('id', $linha['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);

                break;
    } else {
        $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
        $matriculaObj->executaSql($sql);

        $matriculaObj->set('id', $linha['idmatricula'])
            ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
        break;
    }

    salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $jsonEnvio);
    $matriculaObj->executaSql('COMMIT');
}
