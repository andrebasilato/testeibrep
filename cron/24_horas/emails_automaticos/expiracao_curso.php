<?php

$data_chat_dia = new DateTime();
if ($email['dia']) {
	if ($email['dia'] < 0) {
		$data_chat_dia->modify('+' . abs($email['dia']) . ' days');
	} else if ($email['dia'] > 0) {
		$data_chat_dia->modify('-' . abs($email['dia']) . ' days');
	}
}

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
			ofertas_cursos_escolas ocp
				on 
					m.idoferta = ocp.idoferta and
					m.idcurso = ocp.idcurso and
					m.idescola = ocp.idescola
		where 
			m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
			m.ativo = "S" and
			ocp.dias_para_ava IS NOT NULL and
			DATEDIFF(DATE_ADD(m.data_cad, INTERVAL ocp.dias_para_ava DAY), NOW()) = ' . $email['dia'] . '
			 ';
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
	group by 
		p.idpessoa ';

$resultado = mysql_query($sql);
