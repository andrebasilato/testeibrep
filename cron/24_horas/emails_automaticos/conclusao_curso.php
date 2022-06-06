<?php

$data_ontem = new DateTime();
$data_ontem->modify('-1 days');

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
		matriculas_historicos mh 
			on 
				m.idmatricula = mh.idmatricula and 
				mh.tipo = "situacao" and 
				mh.acao = "modificou" and 
				mh.para = m.idsituacao
	where 
		m.idsituacao = ' . $situacao_concluido['idsituacao'] . ' and 
		m.ativo = "S" and 
		DATE_FORMAT(mh.data_cad,"%Y-%m-%d") = "' . $data_ontem->format('Y-m-d') . '" ';
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
