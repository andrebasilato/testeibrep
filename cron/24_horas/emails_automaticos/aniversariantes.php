<?php

$sql = '
	select 
		p.*,
                m.idmatricula
	from 
		matriculas m
	inner join 
		pessoas p 
			on 
				m.idpessoa = p.idpessoa
	where 
		m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
		m.ativo = "S" 
		and DATE_FORMAT(p.data_nasc,"%m-%d") = "' . date('m-d') . '" ';
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
