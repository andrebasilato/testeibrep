<?php
ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_AL_LIBERACAO', retornarInterface('detran_al_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$matriculaObj = new Matriculas();
$siglaEstado = 'AL';

$sql = 'SELECT
        m.idmatricula,
        m.idcurso,
        m.renach,
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
        cw.fim = "N" AND
        cw.inativa = "N" AND
        cw.cancelada = "N" AND
        m.renach IS NOT NULL
    ORDER BY data_ultimo_historico ASC
    limit 10';


$query = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($query)) {
    try {
        $opts = array(
            'ssl' => array(
                'ciphers' => 'RC4-SHA',
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        $params = array(
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_1,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 180,
            'stream_context' => stream_context_create($opts)
        );

        $conexaoSOAP = new SoapClient($config['detran'][$siglaEstado]['urlWslLiberacao'], $params);

        $dadosSOAP = [
            'CFCNW020' => [
                'ENTRADA' => [
                    'CPF-E' => str_pad($linha['documento'], 11),
                    'CODIGO-CFC-E' => $config['detran'][$siglaEstado]['codigo_cfc_e'] ? $config['detran'][$siglaEstado]['codigo_cfc_e'] : str_pad($linha['detran_codigo'], 3),
                    'TIPO-CURSO-E' => 'T',
                    'CODIGO-CURSO-E' => $detran_tipo_aula[$siglaEstado][$linha['idcurso']],
                    'CATEGORIA-CURSO-E' => '',
                    'MATRICULA-E' => $config['detran'][$siglaEstado]['matricula'],
                ]
            ]
        ];
        $transacoes->iniciaTransacao(INTERFACE_DETRAN_AL_LIBERACAO, 'E', $dadosSOAP);
        $respostaSoap = $conexaoSOAP->__soapCall('CFCNW020', $dadosSOAP);
        $stringEnvio = json_encode($dadosSOAP);
        $retorno = json_encode(['codigo' => $respostaSoap->MATRICULA->RETORNO->CODIGO, 'mensagem' => $respostaSoap->MATRICULA->RETORNO->MENSAGEM], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $matriculaObj->executaSql('BEGIN');

        if ($respostaSoap->MATRICULA->RETORNO->CODIGO == 0) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI", data_inicio_curso = NOW() WHERE idmatricula = ' . $linha['idmatricula'];
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

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $retorno, $stringEnvio);
        $matriculaObj->executaSql('COMMIT');

    } catch (Exception $ex) {
        $transacoes->set("json", json_encode(['codigo' => $ex->getCode(), 'mensagem' => $ex->getMessage()]));
        $transacoes->finalizaTransacao(null, 5);
        $transacoes->set("json", null);
        echo $ex->getMessage();
    }
}
