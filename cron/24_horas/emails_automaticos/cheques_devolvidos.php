<?php

if ($email['dia_semanal']) {
	if ($data_atual->format('N') != $email['dia_semanal']) {			
		continue;
	}
} elseif ($email['dia_mensal']) {
	if (str_pad($email['dia_mensal'], 2, "0", STR_PAD_LEFT) != $data_atual->format('d')) {			
		continue;
	}
}

$sql = '
	select 
		p.*, m.idcurso, m.idmatricula 
	from 
		matriculas m
	inner join 
		pessoas p on m.idpessoa = p.idpessoa
	where 
		m.ativo = "S" and
		(
			(
				(
					select 
						count(1) 
					from 
						contas c 
					where 
						c.idmatricula = m.idmatricula and
						c.idsituacao = '.$email['idsituacao_conta'].' and
						c.ativo = "S" and
						c.ativo_painel = "S"
				) > 0
			)
			or
			(
				(
					select 
						count(1) 
					from 
						pagamentos_compartilhados_matriculas pcm
						inner join pagamentos_compartilhados pc on pcm.idpagamento = pc.idpagamento
						inner join contas c on pc.idpagamento = c.idpagamento_compartilhado
					where 
						pcm.idmatricula = m.idmatricula and
						c.idsituacao = '.$email['idsituacao_conta'].' and
						c.ativo = "S" and
						c.ativo_painel = "S"
				) > 0
			)
		)						
		and
		(
			select 
				count(1) 
			from 
				emails_automaticos_log eal 
			where 
				eal.idpessoa = m.idpessoa and 
				eal.idcurso = m.idcurso and
				date_format(eal.data_cad, "%Y%m%d") = "' . $data_atual->format('Ymd') . '" and 
				eal.ativo = "S" and 
				eal.tipo = "chdev"
		) = 0	';
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
