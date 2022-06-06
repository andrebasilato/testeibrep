<?php 
$coreObj = new Core();
$pessoasObj = new Pessoas();
$matriculasObj = new Matriculas();

$coreObj->sql = '
	select 
		ua.*
	from 
		usuarios_adm ua
	where					 
		ua.ativo = "S" and
		ua.receber_email_matricula_situacao = "S"	';
$coreObj->limite = -1;
$coreObj->ordem_campo = false;
$coreObj->ordem = false;
$usuarios = $coreObj->retornarLinhas(); 
  
$coreObj->sql = '
	select 
		ea.*,
		(	
			select 
				count(1) 
			from 
				emails_automaticos_cursos_adm eac 
			where 
				eac.idemail = ea.idemail and 
				eac.ativo = "S" 
		) as cursos_associados
	from 
		emails_automaticos_adm ea
	where					 
		ea.ativo = "S" and 
		ea.ativo_painel = "S"
	group by 
		ea.idemail	';
$coreObj->limite = -1;
$coreObj->ordem_campo = false;
$coreObj->ordem = false;
$emails = $coreObj->retornarLinhas();

$data_atual = new DateTime();

foreach ($emails as $email) {
	unset($cursos_associados);
	if ($email['cursos_associados']) {
		$sql = '
			select 
				idcurso 
			from 
				emails_automaticos_cursos_adm 
			where 
				idemail = ' . $email['idemail'] . ' and 
				ativo = "S" ';
		$resultado_cursos = mysql_query($sql);
		while ($curso = mysql_fetch_assoc($resultado_cursos)) {
			$cursos_associados[] = $curso['idcurso'];
		}
	}

	if ($email['tipo'] == 'qdmsa') {
	
		if ($email['dia_semanal']) {
			if ($data_atual->format('N') != $email['dia_semanal']) {			
				continue;
			}
		}
	
		$data_situacao_matricula_dia = new DateTime();
		if ($email['dia']) {
			if ($email['dia'] > 0) {
				$data_situacao_matricula_dia->modify('+' . abs($email['dia']) . ' days');
				$filtar_dia = true;
			} else if ($email['dia'] < 0) {
				$data_situacao_matricula_dia->modify('-' . abs($email['dia']) . ' days');
				$filtar_dia = true;
			}
		}
	
		$sql = '
			select 
				p.nome,
				m.idmatricula,
				m.idcurso,
				c.nome as curso
			from 
				matriculas m
			inner join cursos c
				on
					m.idcurso = c.idcurso
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
				m.idsituacao = ' . $email['idsituacao_matricula'] . ' and 
				m.ativo = "S" and 
				DATE_FORMAT(mh.data_cad,"%Y-%m-%d") = "' . $data_situacao_matricula_dia->format('Y-m-d') . '"
				and
				(
					select 
						idhistorico
					from
						matriculas_historicos mhi
					where
						m.idmatricula = mhi.idmatricula and 
						mhi.tipo = "situacao" and 
						mhi.acao = "modificou" and 
						mhi.para = m.idsituacao
					order by
						idhistorico DESC
					limit 1
				) = mh.idhistorico	';
		if (count($cursos_associados)) {
			$sql .= ' and m.idcurso in (' . implode(',', $cursos_associados) . ') ';
		}
		$sql .= ' 
			group by 
				m.idmatricula, 
				m.idcurso ';
		$resultado = mysql_query($sql);	
		while ($matricula = mysql_fetch_assoc($resultado)) { 
			$email['texto'] .= '<br /><a href="http://' . $config['urlSistema'] . '/gestor/academico/matriculas/'.$matricula['idmatricula'].'/administrar" target="_blank">' . $matricula['idmatricula'] . ' - ' . $matricula['curso'] . ' - ' . $matricula['nome'] . '</a>';
		}

		foreach ($usuarios as $usuario) { 
			enviarEmailAutomaticoUsuario($email, $usuario, $usuariosObj, $coreObj, $config);
		}
		
	}
	
}

function enviarEmailAutomaticoUsuario($email, $usuario, $usuariosObj, $coreObj, $config) { #print_r2($usuario);return 1;
	$nomeDe = utf8_decode($config['tituloEmpresa']);
	$emailDe = $config['emailSistema'];

	$nomePara = utf8_decode($usuario['nome']);
	$emailPara = $usuario['email'];
	$assunto = $email['nome'];
	$message = $email['texto'];

	if ($coreObj->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout')) {
		$sql_update = '
			update 
				emails_automaticos_log_adm 
			set 
				ativo = "N" 
			where 
				tipo = "' . $email['tipo'] . '" and 
				idusuario = "' . $usuario['idusuario'] . '" ';
		if ($usuario['idcurso']) {
			$sql_update .= ' and idcurso = "' . $usuario['idcurso'] . '" ';
		}
		mysql_query($sql_update);
	
		$sql_log = '
			insert into 
				emails_automaticos_log_adm 
			set 
				data_cad = NOW(),
				tipo = "' . $email['tipo'] . '",
				idusuario = "' . $pessoa['idusuario'] . '"';
		if ($pessoa['idcurso']) {
			$sql_log .= ', idcurso = "' . $pessoa['idcurso'] . '" ';
		}
		mysql_query($sql_log);
		$idchave = mysql_insert_id();		
		if($email['corpo_sms'] and $usuario['celular'] and $GLOBALS['config']['integrado_com_sms']){
			enviarSmsAutomaticoApi($email, $usuario,$idchave);
		}
	}
}


function enviarSmsAutomaticoApi($email, $pessoa,$idchave) { #print_r2($pessoa);return 1;
		
		include("../../classes/sms.class.php");
		$smsobj = new Sms(); 
		$nomePara = ($pessoa['nome']);
		
		$smsobj->Set('idchave',$idchave);
		$smsobj->Set('origem','EADM');
		$smsobj->Set('url_webservicesms',$GLOBALS['config']['linkapiSMS']);
		$dados_gateway = array(
							  'loginSMS' => $GLOBALS['config']['loginSMS'],
							  'tokenSMS' => $GLOBALS['config']['tokenSMS'],
							  'celular' => $pessoa["celular"],
							  'nome' => $nomePara,
							  'mensagem' => $email["corpo_sms"]
							  );
		$smsobj->Set('dado_seguro',$dados_gateway);
		$smsobj->ExecutaIntegraSMS();
}



?>