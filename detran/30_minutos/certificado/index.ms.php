<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

require_once $caminho . '/app/classes/matriculas.class.php';
define('INTERFACE_DETRAN_MS_CERTIFICADO', retornarInterface('detran_ms_certificado')['id']);
$matriculaObj = new Matriculas();
$siglaEstado = 'MS';
$codTransacao = 427; //Cadastro certificado

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idMatoGrossoSul = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.idsituacao,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        p.nome,
        p.data_nasc,
        p.rg,
        p.rg_orgao_emissor,
        p.idcidade,
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
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN cidades ci ON (p.idcidade = ci.idcidade)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S" AND frdm.cancelado <> "S")
    WHERE
    	e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        AND '.$cursosIn.'
        AND e.idestado = ' . $idMatoGrossoSul . '
        AND m.ativo = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 20';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CertificadoMS($linha);
}
