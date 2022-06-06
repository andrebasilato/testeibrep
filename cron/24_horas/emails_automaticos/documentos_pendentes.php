<?php

$data_pendencia_documentos = new DateTime();		
$data_pendencia_documentos->modify('-10 days');

$data_matricula = new DateTime();
if ($email['dia']) {
	if ($email['dia'] < 0) {
		$data_matricula->modify('-' . abs($email['dia']) . ' days');
	} else if ($email['dia'] > 0) {
		$data_matricula->modify('+' . abs($email['dia']) . ' days');
	}
}

$coreObj->sql = '
	select 
		td.idtipo, 
		td.todos_cursos_obrigatorio 
	from 
		tipos_documentos td
	where 
		(
			(
				todos_cursos_obrigatorio = "S"
				or 
				(
					select 
						count(1) 
					from 
						tipos_documentos_cursos tdc 
					where 
						tdc.ativo = "S" and 
						tdc.idtipo = td.idtipo
				)
			)
			and
			(
				todas_sindicatos_obrigatorio = "S"
				or 
				(
					select 
						count(1) 
					from 
						tipos_documentos_sindicatos tdi 
					where 
						tdi.ativo = "S" and 
						tdi.idtipo = td.idtipo
				)
			)
		)
		and td.ativo = "S" ';
$tipos = $coreObj->retornarLinhas();
foreach ($tipos as $tipo) {
	$tipos_obr[] = $tipo['idtipo'];

	$sql = '
		select 
			idcurso 
		from 
			tipos_documentos_cursos 
		where 
			idtipo = ' . $tipo['idtipo'] . ' and 
			ativo = "S" ';
	$resultado_cursos = mysql_query($sql);
	while ($curso = mysql_fetch_assoc($resultado_cursos)) {
		$cursos_associados_tipo[] = $curso['idcurso'];
	}

	$sql = '
		select 
			idsindicato 
		from 
			tipos_documentos_sindicatos 
		where 
			idtipo = ' . $tipo['idtipo'] . ' and 
			ativo = "S" ';
	$resultado_sindicatos = mysql_query($sql);
	while ($sindicato = mysql_fetch_assoc($resultado_sindicatos)) {
		$sindicatos_associadas_tipo[] = $sindicato['idsindicato'];
	}
}

if (count($tipos_obr)) {		
	$sql = '
		select 
			p.*, 
			m.idcurso,
                        m.idmatricula
		from 
			matriculas m
		inner join
			escolas pol 
				on 
					m.idescola = pol.idescola
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
			m.idsituacao = ' . $situacao_matriculado['idsituacao'] . ' and 
			m.ativo = "S" and 
			(
				m.idcurso is not null and 
				m.idcurso <> 0
			)
			AND DATE_FORMAT(mh.data_cad,"%Y-%m-%d") = "' . $data_matricula->format('Y-m-d') . '" ';

	if (count($cursos_associados)) {
		$sql .= ' and m.idcurso in (' . implode(',', $cursos_associados) . ') ';
	}

	if (count($ofertas_associadas)) {
		$sql .= ' and m.idoferta in (' . implode(',', $ofertas_associadas) . ') ';
	}

	if (count($cursos_associados_tipo)) {
		$sql .= ' and m.idcurso in (' . implode(',', $cursos_associados_tipo) . ') ';
	}

	if (count($sindicatos_associadas_tipo)) {
		$sql .= ' and pol.idsindicato in (' . implode(',', $sindicatos_associadas_tipo) . ') ';
	}

	$sql .= ' 
		and
		(
			select 
				count(1) 
			from 
				emails_automaticos_log eal 
			where 
				eal.idpessoa = m.idpessoa and 
				eal.idcurso = m.idcurso and
				eal.data_cad >= "' . $data_pendencia_documentos->format('Y-m-d') . '" and 
				eal.ativo = "S" and 
				eal.tipo = "docup"
		) = 0
		and
		(
			select 
				count(1) 
			from 
				matriculas_documentos md
			inner join 
				tipos_documentos td 
					on 
						md.idtipo = td.idtipo
			where 
				md.idmatricula = m.idmatricula and 
				md.idtipo in (' . implode(',', $tipos_obr) . ') and
				md.ativo = "S" and 
				md.situacao = "aprovado" and 
				md.idtipo_associacao is null 
				and
				(
					(
						(
							select 
								count(1) 
							from 
								tipos_documentos_cursos tdc 
							where 
								tdc.ativo = "S" and 
								tdc.idtipo = md.idtipo and 
								tdc.idcurso = m.idcurso
						) > 0
						or
						td.todos_cursos_obrigatorio = "S"
					)
					or
					(
						(
							select 
								count(1) 
							from 
								tipos_documentos_sindicatos tdi 
							where 
								tdi.ativo = "S" and 
								tdi.idtipo = md.idtipo and 
								tdi.idsindicato = pol.idsindicato
						) > 0
						or
						td.todas_sindicatos_obrigatorio = "S"
					)
				)										
		) = 0
		';

	$sql .= ' 
		group by 
			p.idpessoa, 
			m.idcurso ';

	$resultado = mysql_query($sql);
}
