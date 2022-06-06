<?php

$data_inatividade = new DateTime();
	
$sql = 'select 
			p.*, m.idmatricula 
		from 
			matriculas m
		inner join 
			pessoas p 
				on 
					m.idpessoa = p.idpessoa
		where 
			m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
			m.ativo = "S" and 
			';

if ($email['dia']) {			
	$data_inatividade->modify('-' . abs($email['dia']) . ' days');
	$sql .= ' DATE_FORMAT(p.ultimo_acesso,"%Y-%m-%d") <= "' . $data_inatividade->format('Y-m-d') . '" ';
} else {
	$sql .= ' p.ultimo_acesso is null ';
	$data_inatividade->modify('-10 days'); # CASO NÃO HAJA DIAS, SÓ ENVIAR O E-MAIL SE JÁ ESTIVER SE PASSADO DEZ DIAS
}	

$sql .= '	and
			(
				select 
					count(1) 
				from 
					emails_automaticos_log eal 
				where 
					eal.idpessoa = m.idpessoa and
					eal.data_cad >= "' . $data_inatividade->format('Y-m-d') . '" and 
					eal.ativo = "S" and 
					eal.tipo = "inati"
			) = 0 ';
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
