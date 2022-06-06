<?php

$sql = 'SELECT
        m.idmatricula,
        m.idcurso,
        m.idsindicato,
        oca.idava,
        p.*
    FROM
        matriculas m
        INNER JOIN matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
        INNER JOIN pessoas p ON (p.idpessoa = m.idpessoa)
        INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idescola = m.idescola AND ocp.idoferta = m.idoferta AND ocp.idcurso = m.idcurso)
        INNER JOIN curriculos c ON (c.idcurriculo = ocp.idcurriculo)
        INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = ocp.idoferta AND oca.idcurriculo = c.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
    WHERE
        m.ativo = "S" AND
        (m.cancelada = "N" OR m.cancelada IS NULL) AND
        (m.desistente = "N" OR m.desistente IS NULL) AND
        (m.trancada = "N" OR m.trancada IS NULL) AND
        (m.tranferido = "N" OR m.tranferido IS NULL) AND
        mw.diploma = "N" AND
        mw.cancelada = "N" AND
        mw.diploma_expedido = "N" AND
        mw.fim = "N" AND
        mw.inativa = "N" AND
        IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) >= IFNULL(c.porcentagem_ava, 100) AND
        (
            DATE_ADD(m.data_cron_lembrete_prova_final, INTERVAL ' . (int) $email['dia'] . ' DAY) <= DATE_FORMAT(NOW(), "%Y-%m-%d") OR
            m.data_cron_lembrete_prova_final IS NULL
        ) AND
        (
            SELECT
                COUNT(ma.idprova)
            FROM
                matriculas_avaliacoes ma
                INNER JOIN avas_avaliacoes aa ON (aa.idavaliacao = ma.idavaliacao)
            WHERE
                ma.idmatricula = m.idmatricula AND
                aa.idava = oca.idava AND
                ma.ativo = "S"
        ) = 0';

if (! empty($cursos_associados) && count($cursos_associados)) {
    $sql .= ' AND m.idcurso IN (' . implode(',', $cursos_associados) . ')';
}

if (! empty($ofertas_associadas) && count($ofertas_associadas)) {
    $sql .= ' AND m.idoferta IN (' . implode(',', $ofertas_associadas) . ')';
}

if (! empty($sindicatos_associadas) && count($sindicatos_associadas)) {
    $sql .= ' AND m.idsindicato IN (' . implode(',', $sindicatos_associadas) . ')';
}
$sql .= ' GROUP BY m.idmatricula';
$resultado = $coreObj->executaSql($sql);

while ($linha = mysql_fetch_assoc($resultado)) {
    $emailEnviar = $email;
    $link = $config['urlSistema'] . '/aluno/academico/curso/' . $linha['idmatricula'] . '/' . $linha['idava'] . '/avaliacoes';
    $emailEnviar['texto'] .= '<br />Clique <a href="' . $link . '" target="_blank">aqui</a> para acessar suas avalia&ccedil;&otilde;es.';

    if ($emailEnviar['salvar_log'] == 'N') {
        $coreObj->naoSalvarLogEmail = true;
    } else {
        $coreObj->naoSalvarLogEmail = false;
    }

    enviarEmailAutomaticoPessoa($emailEnviar, $linha, $pessoasObj, $coreObj);

    $sql = 'UPDATE matriculas SET data_cron_lembrete_prova_final = DATE_FORMAT(NOW(), "%Y-%m-%d")
       WHERE idmatricula = '.$linha['idmatricula'];
    $coreObj->executaSql($sql);
}