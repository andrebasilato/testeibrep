<?php

ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_SE_CERTIFICADO', retornarInterface('detran_se_certificado')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';

$matriculaObj = new Matriculas();
$siglaEstado = 'SE';

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idSergipe = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula, m.data_matricula, m.data_conclusao, p.documento, e.detran_codigo, c.idcurso, m.data_inicio_curso,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_certificado" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        AND m.detran_creditos = "S"
        '.$cursosIn.'
        AND e.idestado = ' . $idSergipe . '
        AND m.ativo = "S"
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    limit 10';
$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CertificadoSE($linha);
}
