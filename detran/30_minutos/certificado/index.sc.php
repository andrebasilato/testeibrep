<?php
ini_set('soap.wsdl_cache_enabled', 0);
define('INTERFACE_DETRAN_SC_CERTIFICADO', retornarInterface('detran_sc_certificado')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$matriculaObj = new Matriculas();
$siglaEstado = 'SC';
$codTransacao = 427; //Cadastro certificado

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idSantaCatarina = $estadosDetran[$siglaEstado];

$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();

$sql = 'SELECT
        m.idmatricula,    
        m.data_conclusao,
        p.documento,       
        p.categoria,             
        m.idcurso,     
        frdm.numero_ordem,
        e.detran_codigo,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "detran_situacao" AND mh.para = "LI"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_liberacao,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mh.para = ' . $situacaoEmCurso['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)       
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S" AND frdm.cancelado <> "S")
    WHERE        
        m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idSantaCatarina . '
        AND m.ativo = "S"
        AND cw.fim = "S"
        AND m.data_conclusao IS NOT NULL       
        AND e.detran_codigo IS NOT NULL       
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CertificadoSC($linha);
}
