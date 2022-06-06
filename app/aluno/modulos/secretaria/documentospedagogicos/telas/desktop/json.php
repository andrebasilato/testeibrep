<?php
$json = NULL;

if($url[4] == 'declaracoes') {
	$json = $matriculaObj->Set('id',(int) $_GET['idmatricula'])
						  ->Set('ordem','asc')
						  ->Set('limite',-1)
						  ->Set('ordem_campo','nome')
						  ->Set('campos','d.iddeclaracao, d.nome')
						  ->RetornarDeclaracoesAlunoPodeSolicitar();
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo $json;

exit;