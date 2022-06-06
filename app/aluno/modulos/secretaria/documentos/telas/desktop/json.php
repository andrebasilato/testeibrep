<?php
$json = NULL;
if($url[4] == 'tipos') {
	$matriculaObj->Set("id", intval($_GET['idmatricula']));
    $matricula = $matriculaObj->Retornar();

	$matricula['curso'] = $matriculaObj->RetornarCurso();
	$matricula['escola'] = $matriculaObj->RetornarEscola();

	require_once("../classes/tiposdocumentos.class.php");
	$tiposDocumentosObj = new Tipos_Documentos();
    $tiposDocumentos = $tiposDocumentosObj->set('idmatricula', intval($_GET['idmatricula']))
											->retornarTodosObrigatorios($matricula["escola"]["idsindicato"], $matricula["curso"]["idcurso"]);
    $json = json_encode($tiposDocumentos);
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo $json;

exit;