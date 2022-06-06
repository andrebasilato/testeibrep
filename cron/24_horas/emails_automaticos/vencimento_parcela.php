<?php

$data = new DateTime();
if ($email['dia']) {
	if ($email['dia'] < 0) {
		$data->modify('+' . abs($email['dia']) . ' days');
	} else if ($email['dia'] > 0) {
		$data->modify('-' . abs($email['dia']) . ' days');
	}
}	

$sql = '
	select 
		p.*, m.idcurso, m.idmatricula
	from 
		matriculas m
	inner join 
		pessoas p 
			on 
				m.idpessoa = p.idpessoa
	where 
		m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
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
						c.idsituacao not in (' . implode(',', $situacoes_nao_inadimplentes) . ') and
						c.data_vencimento = "' . $data->format('Y-m-d') . '" and
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
					inner join 
						pagamentos_compartilhados pc 
							on 
								pcm.idpagamento = pc.idpagamento
					inner join 
						contas c 
							on 
								pc.idpagamento = c.idpagamento_compartilhado
					where 
						pcm.idmatricula = m.idmatricula and
						c.idsituacao not in (' . implode(',', $situacoes_nao_inadimplentes) . ') and
						c.data_vencimento = "' . $data->format('Y-m-d') . '" and
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
				eal.data_cad = "' . $data->format('Y-m-d') . '" and 
				eal.ativo = "S" and 
				eal.tipo = "inadi"
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
