<?php
set_time_limit(0);

$coreObj = new Core();
$matriculaObj = new Matriculas();
			
$sql = "SELECT                    
			m.idmatricula,
			c.porcentagem_ava AS porc_minima_ava,
			c.dias_minimo,
			m.pode_aprovar
		FROM
			matriculas m
			INNER JOIN matriculas_workflow mw ON m.idsituacao = mw.idsituacao AND mw.ativa = 'S' AND mw.ativo = 'S'
			INNER JOIN ofertas_cursos_escolas ocp ON ( m.idoferta = ocp.idoferta AND m.idcurso = ocp.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = 'S' )
			INNER JOIN curriculos c ON ( ocp.idcurriculo = c.idcurriculo )
		WHERE
			m.ativo = 'S' AND 
			m.pode_aprovar IS NULL AND
			(c.dias_minimo <= DATEDIFF('" . date('Y-m-d') . "', m.data_cad) OR c.dias_minimo IS NULL)";	
$resultado1 = $coreObj->executaSql($sql);
while ($matricula = mysql_fetch_assoc($resultado1)) {
	$matriculaObj->Set('id', $matricula['idmatricula']);
	$andamento = $matriculaObj->retornarAndamento();
	if ($matricula['porc_minima_ava'] <= $andamento['porc_aluno']) { 
		if ($matriculaObj->verificaMatriculaAprovadaNotas()) {
			$sql = 'update matriculas set pode_aprovar = "S" where idmatricula = ' . $matricula['idmatricula'];
			$resultado2 = $coreObj->executaSql($sql);
			if ($resultado2) {
				$sql = 'insert into matriculas_historicos 
							set 
								data_cad = NOW(),
								tipo = "permissao_aprovacao",
								acao = "modificou",
								para = "S",
								idmatricula = ' . $matricula['idmatricula'];
				if ($matricula['pode_aprovar'])
					$sql .= ', de = "' . $matricula['pode_aprovar'] . '"';	
				$resultado3 = $coreObj->executaSql($sql);
			}
		}
	}
}
?>