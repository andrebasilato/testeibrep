<?php
ini_set('soap.wsdl_cache_enabled', '0');
define('INTERFACE_DETRAN_SE_LIBERACAO', retornarInterface('detran_se_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$codTransacao = 431;//Consulta Processo do aluno
$matriculaObj = new Matriculas();
$siglaEstado = 'SE';

$sql = 'SELECT
        m.idmatricula, p.documento, e.detran_codigo, c.idcurso,
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
    limit 10';
$query = $matriculaObj->executaSql($sql);

$categoria = str_pad('', 1, ' ');
$codCurso = str_pad('', 2, ' ');
while ($linha = mysql_fetch_assoc($query)) {
    try {
        $matriculaObj->executaSql('BEGIN');

        $linha['detran_tipo_aula'] = $detran_tipo_aula[$siglaEstado][$linha['idcurso']];

        $stringEnvio = $codTransacao . $linha['documento'] . $linha['detran_tipo_aula']['CODIGO'] .
            $categoria . $codCurso . 'CFC' . $linha['detran_codigo'];

        $opcoesSOAP = array(
            'trace' => 1,
            array('exceptions' => true)
        );
        $soapCliente = new SoapClient($config['detran'][$siglaEstado]['urlWsl'], $opcoesSOAP);

        $dadosSOAP = [
            'executaTransacao' => [
                'pUsuario' => $config['detran'][$siglaEstado]['pUsuario'],
                'pSenha' => $config['detran'][$siglaEstado]['pSenha'],
                'pAmbiente' => $config['detran'][$siglaEstado]['pAmbiente'],
                'pMensagem' => $stringEnvio,
            ]
        ];
        $options = ['location' => $config['detran'][$siglaEstado]['urlSoap']];

        $transacoes->iniciaTransacao(INTERFACE_DETRAN_SE_LIBERACAO, 'E', $dadosSOAP);
        $respostaSoap = $soapCliente->__soapCall('executaTransacao', $dadosSOAP, $options);

        if (substr($respostaSoap->executaTransacaoResult, 0, 3) == 999) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI", data_inicio_curso = NOW() WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
            $transacoes->finalizaTransacao(null, 2, null, $respostaSoap->executaTransacaoResult);

        } elseif (substr($respostaSoap->executaTransacaoResult, 0, 3) == 998) {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
            $transacoes->finalizaTransacao(null, 5, null, $respostaSoap->executaTransacaoResult);
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $respostaSoap->executaTransacaoResult, $stringEnvio);

        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $excecao) {
        $transacoes->finalizaTransacao(
            null,
            3,
            json_encode(['codigo' => $excecao->getCode(), 'mensagem' => $excecao->getMessage()])
        );
        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
    }
}
