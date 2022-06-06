<?php

require_once $caminho . '/app/classes/matriculas.class.php';
define('INTERFACE_DETRAN_PE_CREDITOS', retornarInterface('detran_pe_creditos')['id']);
$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();

$siglaEstado = 'PE';
if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ') AND ';
}
$idPernambuco = $estadosDetran[$siglaEstado];

$sql = 'SELECT
        m.idmatricula, p.documento, p.data_nasc, e.detran_codigo, o.idoferta, c.idcurso, e.idescola, data_inicio_curso, data_conclusao,
        (
            SELECT
                COUNT(d.iddisciplina)
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = "S")
            WHERE
                oce.idoferta = o.idoferta AND
                oce.idcurso = c.idcurso AND
                oce.idescola = e.idescola AND
                d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
        ) AS total_disciplinas,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = "detran_creditos" ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "situacao" AND mh.para = ' . $situacaoEmCurso['idsituacao'] . '
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
    WHERE
        '.$cursosIn.'
        e.idestado =' . $idPernambuco . ' AND
        m.ativo = "S" AND
        m.detran_situacao = "LI" AND
        m.detran_creditos = "N" AND
        m.detran_finalizar = "N" AND
        e.detran_codigo IS NOT NULL AND
        cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    limit 25';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CreditosPE($linha);
}
