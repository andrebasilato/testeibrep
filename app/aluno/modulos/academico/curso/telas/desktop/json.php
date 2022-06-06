<?php
$json = NULL;

$matriculaObj->Set("post",$_POST);	

switch ($url['6']) {
	case 'contabilizar':
		$matriculaObj->set('idpessoa', $usuario['idpessoa']);
		$json = $matriculaObj->contabilizarRota();
	break;
	case 'favoritar':
		$json = $matriculaObj->favoritar();	
	break;
	case 'anotacao':
		if($url['7'] == 'cadastrar')  
			$json = $matriculaObj->cadastrarAnotacao();
		else
			$json = $matriculaObj->deletarAnotacao();
	break;
	case 'participantes_mensagem':
		require '../classes/avas.mensagem_instantanea_novo.class.php';
		$participantesObj = new MensagemInstantanea();
		$participantesObj->set('idava', $url[4]);
		$participantesObj->set('idpessoa', $usuario['idpessoa']);
		echo $participantesObj->buscarParticipantes();
	break;
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo $json;
exit;
