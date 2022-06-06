<?php
function incluirLib($lib,$config,$informacoes = null){
	$lib = dirname(__DIR__) . '/lib/' . $lib . '/index.php';
	if (file_exists($lib)) {
		include $lib;
	} else {
		echo '<strong>Erro ao tentar incluir a LIB, verifique o código.</strong> <br> LIB: ' . $lib;
	}
}
