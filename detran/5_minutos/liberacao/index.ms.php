<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
define('INTERFACE_DETRAN_MS_LIBERACAO', retornarInterface('detran_ms_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
require_once $caminho . '/app/includes/retornos.matogrossodosul.php';

$matriculaObj = new Matriculas();
$siglaEstado = 'MS';
$codTransacao = 431;
$sql = 'SELECT
        m.idmatricula,
        m.idcurso,
        p.documento,
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
    $anoAtual = (new \DateTime())->format('Y');
    $mesAtual = (new \DateTime())->format('m');
    $diaAtual = (new \DateTime())->format('d');
    $minutoAtual = (new \DateTime())->format('i');

    $_POST = 'cpf=' . str_pad($linha['documento'], 11, '0', STR_PAD_LEFT) .
        '&tpCur=' . $detran_tipo_aula[$siglaEstado][$linha['idcurso']] .
        '&codSeg=' . ((intval($anoAtual) + intval($mesAtual) + intval($diaAtual)) * $minutoAtual);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $config['detran'][$siglaEstado]['urlLiberacao'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $_POST,
        CURLOPT_INTERFACE => $config['detran'][$siglaEstado]['ip'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $retorno = ($err) ? 'cURL Error #:' . $err : $response;

    $arrRetorno = json_decode(json_encode(simplexml_load_string($retorno)),true);

    $transacoes->iniciaTransacao(INTERFACE_DETRAN_MS_LIBERACAO, 'E');
    if ($arrRetorno ||$err) {
        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'],
            $arrRetorno['consulta']['condutor']['codRet'] . ' - ' . $retornoLiberacao[$arrRetorno['consulta']['condutor']['codRet']],
            $_POST);
        $matriculaObj->executaSql('BEGIN');
        if (in_array($arrRetorno['consulta']['condutor']['codRet'], ['03', '04', '09'])) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI", data_inicio_curso = NOW() WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);

            $matriculaObj->executaSql('COMMIT');
            $transacoes->finalizaTransacao(null, 2, null, $arrRetorno);
        } else {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);

            $matriculaObj->executaSql('COMMIT');
            $transacoes->finalizaTransacao(null, 5, null, json_encode($arrRetorno ? $arrRetorno : $retorno));
        }
    }
}
