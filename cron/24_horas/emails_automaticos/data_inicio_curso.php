<?php

#SINAIS INVERTIDOS - NÃO COMPARA A DATA DO SCRIPT, E SIM A DATA DE INÍCIO DA AULA.
$data_inicio_curso_dia = new DateTime();
if ($email['dia']) {
	if ($email['dia'] < 0) {
		$data_inicio_curso_dia->modify('+' . abs($email['dia']) . ' days');
	} else if ($email['dia'] > 0) {
		$data_inicio_curso_dia->modify('-' . abs($email['dia']) . ' days');
	}
}

$sql = '
	select 
		p.*, 
		m.idcurso,
		m.idoferta,
                m.idmatricula
	from 
		matriculas m
	inner join 
		pessoas p 
			on 
				m.idpessoa = p.idpessoa
	inner join 
		ofertas_cursos oc
			on
				oc.idoferta = m.idoferta and
				oc.idcurso = m.idcurso and
				oc.ativo = "S"
	where 
		m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
		m.ativo = "S" and 
		DATE_FORMAT(oc.data_inicio_aula,"%Y-%m-%d") = "' . $data_inicio_curso_dia->format('Y-m-d') . '" ';

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
		p.idpessoa, 
		m.idcurso ';
//echo $email['idemail'].' - '.$sql;
//echo '<br><br>';
$resultado = mysql_query($sql);	
