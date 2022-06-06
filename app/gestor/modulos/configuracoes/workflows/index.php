<?php
require '../classes/workflow.class.php';
require 'config.php';
require 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';

$linhaObj = new Workflow();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$bloqueio_workflow = !($linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|' . $url[3] . '_alterar', false) && $config["workflow"]);

if ($config['workflows'][$url[3]]) {
    if (!$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|' . $url[3] . '_alterar', false)) {
        $config["workflow"] = false;
    }

	$workflow = $config['workflows'][$url[3]];
	$config['banco'] = array('tabela' => $workflow['banco']);
	$linhaObj->Set('tabela', $workflow['tabela'])
        ->Set('tipos', $workflow['tipos'])
        ->Set('flags',$workflow['flags'])
        ->Set('config', $config);

	$dados = $linhaObj->retonarDados();
	$linhaObj->Set('idusuario', $usuario['idusuario']);
	$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);
}

if ('json' == $url[4] && 'gravar' == $_POST['acao']) {
	if ($bloqueio_workflow) {
        incluirLib("sempermissao", $config, $usuario);
        exit();
    }
	$retorno = $linhaObj->salvarDados();
	echo json_encode($retorno);
	exit;
}

if ('json' == $url[4] && 'gravar' <> $_POST['acao']) {
	require 'telas/' . $config['tela_padrao'] . '/json.php';
	exit;
}

if($config['workflows'][$url[3]]) {
    require 'telas/' . $config['tela_padrao'] . '/workflow.php';
} else {
    require 'telas/' . $config['tela_padrao'] . '/index.php';
}
