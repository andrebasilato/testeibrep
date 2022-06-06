<?php

$data_forum_dia = new DateTime();
if ($email['dia']) {
    if ($email['dia'] < 0) {
        $data_forum_dia->modify('+' . abs($email['dia']) . ' days');
    } else if ($email['dia'] > 0) {
        $data_forum_dia->modify('-' . abs($email['dia']) . ' days');
    }
}

$sql = 'select
			*
		from
			avas_foruns
		where					
			DATE_FORMAT(periode_de,"%Y-%m-%d") = "' . $data_forum_dia->format('Y-m-d') . '" and
			exibir_ava = "S"	';

$resultado = mysql_query($sql) or die(mysql_error());
while ($linha = mysql_fetch_assoc($resultado)) {
    $avas[] = $linha['idava'];
}

if (count($avas) > 0) {
    $sql = 'select 
                p.*,
                m.idmatricula
            from 
                matriculas m
            inner join 
                pessoas p 
                    on 
                        m.idpessoa = p.idpessoa
            inner join
                ofertas_cursos_escolas ocp on 
                    m.idoferta = ocp.idoferta and 
                    m.idcurso = ocp.idcurso and 
                    m.idescola = ocp.idescola
            inner join
                ofertas_curriculos_avas oca on
                    ocp.idoferta = oca.idoferta and
                    ocp.idcurriculo = oca.idcurriculo
            where 
                m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
                m.ativo = "S" ';
    if (count($cursos_associados)) {
        $sql .= ' and m.idcurso in (' . implode(',', $cursos_associados) . ') ';
    }
    if (count($ofertas_associadas)) {
        $sql .= ' and m.idoferta in (' . implode(',', $ofertas_associadas) . ') ';
    }
    if (count($sindicatos_associadas)) {
        $sql .= ' and m.idsindicato in (' . implode(',', $sindicatos_associadas) . ') ';
    }
    $sql .= ' 
        and oca.idava in (' . implode(',', $avas) . ')
        group by 
            p.idpessoa ';

    $resultado = mysql_query($sql);
}