<?php
$coreObj = new Core();

$dataAtual = new DateTime();
$dataAtual->modify('-5 minutes');
$data = $dataAtual->format('YmdHis');

$json['onlines'] = array();
$json['onlines']['total'] = 0;

## Total de usuários online por modulo
$sql = 'select count(*) as total from usuarios_adm where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['gestor'] = $total['total'];

$sql = 'select count(*) as total from professores where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['professor'] = $total['total'];

$sql = 'select count(*) as total from vendedores where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['vendedor'] = $total['total'];

$sql = 'select count(*) as total from pessoas where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['aluno'] = $total['total'];
## /Total de usuários online por modulo

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

$coreObj->limite = 4;
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