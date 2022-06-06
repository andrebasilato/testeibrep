<?php
require_once $caminho . '/app/classes/matriculas.class.php';
define('INTERFACE_DETRAN_RS_CERTIFICADO', retornarInterface('detran_rs_certificado')['id']);
$siglaEstado = 'RS';
$matriculaObj = new Matriculas();
if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idRioGrandeSul = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        e.detran_codigo,
        c.idcurso,
        c.carga_horaria_total,
        m.cod_ticket,
        m.renach,
        m.idoferta,
        m.idcurso,
        m.idescola,
        m.data_inicio_curso,
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
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S" AND frdm.cancelado <> "S")
    WHERE
        m.cod_ticket IS NOT NULL
        AND m.renach IS NOT NULL
        AND e.detran_codigo IS NOT NULL
        AND m.renach <> ""
        AND e.detran_codigo <> ""
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idRioGrandeSul . '
        AND m.ativo = "S"
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 20';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
   $detran->CertificadoRS($linha);
}
