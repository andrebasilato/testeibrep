<?php

if ($email['dia_semanal'] && $data_atual->format('N') != $email['dia_semanal']) {		
	continue;
}

$data_prova_dia = new DateTime();
if ($email['dia']) {
	if ($email['dia'] < 0) {
		$data_prova_dia->modify('+' . abs($email['dia']) . ' days');
		$filtar_dia = true;
	} else if ($email['dia'] > 0) {
		$data_prova_dia->modify('-' . abs($email['dia']) . ' days');
		$filtar_dia = true;
	}
}		

$sql = "SELECT                    
			oc.porcentagem_minima AS porc_min_solicitar_prova,
			oc.qtde_minima_dias AS qtde_min_dias_solicitar_prova,
			m.idmatricula,
			p.*
		  FROM
			matriculas m
			INNER JOIN pessoas p ON p.idpessoa = m.idpessoa
			INNER JOIN ofertas_cursos oc
			ON (
				m.idoferta = oc.idoferta AND
				m.idcurso = oc.idcurso AND
				oc.ativo = 'S'
				)
		  WHERE
			m.ativo = 'S' AND
			(oc.qtde_minima_dias <= DATEDIFF(m.data_cad, ".date('Y-m-d').") or oc.qtde_minima_dias is null)  AND					
			m.idsituacao = ".$situacao_matriculado['idsituacao'];	

if ($filtar_dia) {
	$sql .= " AND DATE_FORMAT(m.data_cad,'%Y-%m-%d') = '" . $data_prova_dia->format('Y-m-d') . "' ";
}

if (count($cursos_associados)) {
	$sql .= ' and m.idcurso in (' . implode(',', $cursos_associados) . ') ';
}
if (count($ofertas_associadas)) {
	$sql .= ' and m.idoferta in (' . implode(',', $ofertas_associadas) . ') ';
}
if (count($sindicatos_associadas)) {
	$sql .= ' and m.idsindicato in (' . implode(',', $sindicatos_associadas) . ') ';
}

$resultado = mysql_query($sql);
while ($linha = mysql_fetch_assoc($resultado)) {
	$andamento = $matriculasObj->retornarAndamento();
	if(!$andamento['porc_aluno']) {
		$andamento['porc_aluno'] = 0;
	}
	if ($linha['porc_min_solicitar_prova'] <= $andamento['porc_aluno']) {
		if (!$email['porcentagem'] || ($email['porcentagem'] == $andamento['porc_aluno'])) {
			enviarEmailAutomaticoPessoa($email, $linha, $pessoasObj, $coreObj);
		}
	}			
}
continue;
