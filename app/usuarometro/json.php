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

## Total de usuários online por modulo
$sql = 'select count(*) as total from usuarios_adm where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['gestor'] = $total['total'];

$sql = 'select count(*) as total from escolas where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['cfc'] = $total['total'];

$sql = 'select count(*) as total from vendedores where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['vendedor'] = $total['total'];

$sql = 'select count(*) as total from pessoas where date_format(ultimo_view, "%Y%m%d%H%i%s") >= "'.$data.'" and ativo = "S"';
$total = $coreObj->retornarLinha($sql);
$json['onlines']['total'] += $json['onlines']['aluno'] = $total['total'];
## /Total de usuários online por modulo

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo json_encode($json);