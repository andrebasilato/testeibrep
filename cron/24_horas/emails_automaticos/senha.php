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
		solicitacoes_senhas ss
			on 
				p.idpessoa = ss.id and 
				ss.ativo = "S" and
				ss.modulo = "aluno" and
				DATE_FORMAT(ss.data_cad,"%Y-%m-%d") = "' . $data_atual->format('Y-m-d') . '"
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
