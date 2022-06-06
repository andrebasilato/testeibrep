<?php
ini_set('soap.wsdl_cache_enabled', '0');
define('INTERFACE_DETRAN_SE_CREDITOS', retornarInterface('detran_se_creditos')['id']);
ini_set("default_socket_timeout", 15);

require_once $caminho . '/app/classes/matriculas.class.php';

$codTransacao = 424;//Envio de créditos de aula

$matriculaObj = new Matriculas();
$siglaEstado = 'SE';
if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND ';
}
$idSergipe = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula, p.documento, e.detran_codigo, o.idoferta, c.idcurso, e.idescola, mr.probabilidade_datavalid, data_inicio_curso,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN matriculas_reconhecimentos mr ON (m.idmatricula = mr.idmatricula AND mr.probabilidade_datavalid >= 0.85)
    WHERE
        '.$cursosIn.'
        e.idestado =' . $idSergipe . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "LI" AND
        m.detran_creditos = "N" AND
        e.detran_codigo IS NOT NULL AND
        cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    limit 10';
$query = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($query)) {
    $detran->CreditosSE($linha);
}
