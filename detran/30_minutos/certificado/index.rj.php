<?php
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl',0);

$codTransacao = 10; //Cadastro certificado

$matriculaObj = new Matriculas();
$siglaEstado = 'RJ';
define('INTERFACE_DETRAN_RJ_CERTIFICADO', retornarInterface('detran_rj_certificado')['id']);


$numeroSequencial = mysql_fetch_assoc($matriculaObj->executaSql('SELECT (COUNT(*)+1) as proximo FROM detran_logs'));

$parteFixa = [
    'sequencial' => str_pad($numeroSequencial['proximo'], 6, '0', STR_PAD_LEFT),
    'cod-transacao' => str_pad($codTransacao, 3, 0, STR_PAD_LEFT),
    'modalidade' => '4',
    'cliente' => str_pad($config['detran'][$siglaEstado]['pUsuario'], 11),
    'uf-transa' => 'BR',
    'uf-origem' => 'BR',
    'uf-destino' => 'RJ',
    'tipo' => '0',
    'tamanho' => '0054',
    'retorno' => '00',
    'juliano' => str_pad(date('z')+1, 3, '0', STR_PAD_LEFT),
];

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idRioJaneiro = $estadosDetran[$siglaEstado];
$sql = 'SELECT
        m.idmatricula,
        m.data_matricula,
        m.data_conclusao,
        m.renach,
        p.documento,
        p.categoria,
        e.detran_codigo,
        c.idcurso,
        m.idmatricula,
        p.data_nasc,
        e.detran_codigo,
        c.idcurso,
        frdm.idfolha_matricula,
        m.idoferta,
        m.idcurso,
        m.idescola,
        m.data_primeiro_acesso,
        m.data_inicio_curso,
        (
            SELECT oca.idava
            FROM ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = "S")
            WHERE oce.idoferta = o.idoferta
                AND oce.idcurso = c.idcurso
                AND oce.idescola = e.idescola
                AND d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
            LIMIT 1
        ) AS idava,
        (
            SELECT mh.acao
            FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula
            AND mh.tipo = "detran_certificado"
            ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad
            FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula
            AND mh.tipo = "detran_certificado"
            ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico
    FROM matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S" AND frdm.cancelado <> "S")
    WHERE
        m.renach IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idRioJaneiro . '
        AND m.ativo = "S"
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
$query = $matriculaObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($query)) {
    $detran->CertificadoRJ($linha);
}
