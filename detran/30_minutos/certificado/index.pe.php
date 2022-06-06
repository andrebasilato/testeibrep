<?php
define('INTERFACE_DETRAN_PE_CERTIFICADO', retornarInterface('detran_pe_certificado')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';

$siglaEstado = 'PE';
$codTransacao = 427;//Cadastro certificado
$valorResultado = "CursoDistanciaConcluirResult";

$matriculaObj = new Matriculas();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();


if (is_array($detran_tipo_aula[$siglaEstado])) {
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idPernambuco = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula, m.data_matricula, p.documento, p.data_nasc, e.detran_codigo, c.idcurso, frdm.idfolha_matricula,
       m.idoferta, m.idcurso, m.idescola, e.documento as documentoCfc, m.data_inicio_curso, m.data_conclusao,
        (
            SELECT
                oca.idava
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND
                                                           oca.idcurriculo = oce.idcurriculo AND
                                                           oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina
                                                 AND d.ativo = "S")
            WHERE
                oce.idoferta = o.idoferta AND
                oce.idcurso = c.idcurso AND
                oce.idescola = e.idescola AND
                d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
            LIMIT 1
        ) AS idava,
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
        e.detran_codigo IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        AND m.detran_creditos = "S"
        AND m.detran_finalizar = "S"
        AND cw.fim = "S"
        '.$cursosIn.'
        AND e.idestado = ' .$idPernambuco. '
        AND m.ativo = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';

$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CertificadoPE($linha);
}
