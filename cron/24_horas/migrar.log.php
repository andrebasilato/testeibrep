<?php

	set_time_limit(0);

	$dias_atras = 60;

	$logObj = new Core();

	//SMS
	$sql = 'DELETE FROM log_sms
			WHERE date_format(data_cad,"%Y-%m-%d") <=  ' . date('Y-m-d', strtotime(' - ' . $dias_atras . ' days')) . ' AND enviado = "S" ';
	$resultado2 = $logObj->executaSql($sql);

	$dias_atras = 180;
	$sql = "UPDATE emails_log SET layout = '', mensagem = '', cabecalho = ''
    		WHERE date_format(data_cad,'%Y-%m-%d') <=  '" . date('Y-m-d', strtotime(' - ' . $dias_atras . ' days')) . "' AND enviado = 'S'";
	$resultado1 = $logObj->executaSql($sql);

	//MONITORAMENTO
	$dias_atras = 1;
	$sql = "DELETE FROM monitora_adm_log
			WHERE idmonitora IN (SELECT idmonitora FROM monitora_adm WHERE date_format(data_cad,'%Y-%m-%d') <= '" . date('Y-m-d', strtotime(' - ' . $dias_atras . ' year')) . "')";
	$resultado3 = $logObj->executaSql($sql);

	$sql = "DELETE FROM monitora_adm
    		WHERE date_format(data_cad,'%Y-%m-%d') <= '" . date('Y-m-d', strtotime(' - ' . $dias_atras . ' year')) . "'";
	$resultado4 = $logObj->executaSql($sql);
