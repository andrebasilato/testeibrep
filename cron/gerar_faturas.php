<?php
set_time_limit(0);

// Includes gerais
require_once '../app/includes/config.php';
require_once '../app/especifico/inc/config.especifico.php';
require_once '../app/includes/funcoes.php';
require_once '../app/includes/funcoes.php';
require '../app/classes/PHPMailer/PHPMailerAutoload.php';

include_once('../app/classes/escolas.class.php');
include_once('../app/classes/contas.class.php');

$escolaObj = new Escolas();

$escolaObj->Set('campos', 'p.idsindicato, p.idescola');
$escolaObj->Set('ordem_campo', 'p.idescola');
$escolaObj->Set('ordem', 'ASC');
$escolaObj->Set('limite', -1);
$escolas = $escolaObj->listarTodas();

foreach ($escolas as $ind => $var) {
	$contasObj = new Contas();
	$contasObj->gerarFatura($var['idescola'], $var['idsindicato']);
}
