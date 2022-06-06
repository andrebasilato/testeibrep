<?php

$sql = '
	select 
		p.*, 
		m.idcurso,
                m.idmatricula
	from 
		matriculas m
	inner join 
		pessoas p 
			on 
				m.idpessoa = p.idpessoa
	inner join 
		matriculas_notas mn
			on 
				m.idmatricula = mn.idmatricula and 
				mn.ativo = "S" and
				DATE_FORMAT(mn.data_cad,"%Y-%m-%d") = "' . $data_atual->format('Y-m-d') . '"
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
	group by 
		p.idpessoa, 
		m.idcurso ';
$resultado = mysql_query($sql);	
