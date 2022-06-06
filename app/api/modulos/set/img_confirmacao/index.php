<?php

	$idLog = substr($url[3], 0, -4); // Retiramos a extensao
	
	$sql = "update emails_log set data_leitura = now() where idemail='".$idLog."'";
	$informar_leitura = mysql_query($sql) or die(incluirLib("erro",$this->config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));	
	
	$imagem = $_SERVER['DOCUMENT_ROOT'].'/assets/img/transparente.png';
	header('Content-type: image/jpg');
	header('Content-length: '.filesize($imagem));
	readfile($imagem);

?>