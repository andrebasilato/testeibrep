<?php
include('config.php');
include('classe.class.php');
include('../classes/relatorios.class.php');
$relatoriosObj = new Relatorios();
$relatoriosObj->Set('idusuario',$usuario['idusuario']);

$relatorioObj = new Relatorio();
$relatorioObj->Set('idusuario',$usuario['idusuario']);
$relatorioObj->Set('monitora_onde',1);
$relatorioObj->Set('config',$config);
$relatorioObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

if ($_POST['acao'] == 'salvar_relatorio') {
	$relatoriosObj->Set("post",$_POST);
	$salvar = $relatoriosObj->salvarRelatorio();

	if ($salvar['sucesso']) {
		$mensagem_sucesso = "salvar_relatorio_sucesso";
	} else {
		$mensagem_erro = $salvar['erro_texto'];
	}
}

if ($url[3] == 'html' || $url[3] == 'xls') {
	$relatorioObj->Set('pagina',1);
	$relatorioObj->Set('ordem','DESC');
	$relatorioObj->Set('limite',-1);
	$relatorioObj->Set('ordem_campo','u.idusuario');
	$relatorioObj->Set('campos','u.idusuario,
								UPPER(u.nome) AS usuario,
								u.email,
								UPPER(p.nome) AS perfil,
								if(
									u.gestor_sindicato = "S",
									"Gestor da Institução",
									(
										SELECT
											GROUP_CONCAT(i.nome_abreviado ORDER BY i.nome_abreviado DESC SEPARATOR " / ")
										FROM
											sindicatos i
											INNER JOIN usuarios_adm_sindicatos uai ON (uai.idsindicato = i.idsindicato AND uai.ativo = "S")
										WHERE
											uai.idusuario = u.idusuario
									)
								) AS sindicatos');		
	$dadosArray = $relatorioObj->gerarRelatorio();
}

switch ($url[3]) {
	case 'html':
		$relatoriosObj->atualiza_visualizacao_relatorio();
		include('idiomas/'.$config['idioma_padrao'].'/html.php');
		include('telas/'.$config['tela_padrao'].'/html.php');
		break;

	case 'xls':		
		include('idiomas/'.$config['idioma_padrao'].'/xls.php');
		include('telas/'.$config['tela_padrao'].'/xls.php');
		break;

	default:
		include('idiomas/'.$config['idioma_padrao'].'/index.php');
		include('telas/'.$config['tela_padrao'].'/index.php');
}