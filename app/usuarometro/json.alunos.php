<?php
// Includes gerais
require '../includes/config.php';
require '../includes/funcoes.php';

$coreObj = new Core();

$dataAtual = new DateTime();
$dataAtual->modify('-5 minutes');
$data = $dataAtual->format('YmdHis');

$json['onlines'] = array();
$json['onlines']['total'] = 0;

## Ultimos X[$coreObj->limite] alunos online
$coreObj->sql = 'select 
					p.idpessoa,
					substring(p.nome,1,16) as nome, 
					p.avatar_servidor, 
					substring(c.nome,1,16) as cidade,
					substring(e.nome,1,16) as estado 
				from 
					pessoas p
					left outer join cidades c on (p.idcidade = c.idcidade) 
					left outer join estados e on (p.idestado = e.idestado) 
				where 
					p.ativo = "S"';

$coreObj->limite = 10;
$url[3] = (int) $url[3];
if($url[3]) {
	if($url[3] > 50) $url[3] = 50;
	$coreObj->limite = (int) $url[3];
}

$coreObj->ordem = 'asc';
$coreObj->ordem_campo = 'p.ultimo_view desc, p.nome';

$json['alunos'] = $coreObj->retornarLinhas();
## /Ultimos X[$qtdAlunosOnlines] alunos online

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo json_encode($json);