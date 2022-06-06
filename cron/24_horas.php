<?php

$caminhoApp = realpath(dirname(__FILE__) . '/../');

// Includes gerais
require_once $caminhoApp . '/app/includes/config.php';
require_once $caminhoApp . '/app/especifico/inc/config.especifico.php';
require_once $caminhoApp . '/app/includes/funcoes.php';
require_once 'includes/funcoes.php';

// Classe PHPMailer (e-mail)
require_once $caminhoApp . '/app/classes/PHPMailer/PHPMailerAutoload.php';

// Classe Core (classe pai)
require_once $caminhoApp . '/app/classes/core.class.php';
$coreObj = new Core;
require_once $caminhoApp.'/app/classes/pessoas.class.php';
// require_once $caminhoApp . '/classes/matriculas_novo.class.php';
require_once $caminhoApp . '/app/classes/escolas.class.php';
require_once $caminhoApp . '/app/classes/motivoscancelamento.class.php';

include '24_horas/cancelar_pedidos.php';
include '24_horas/matriculas_cancelar.php';
include '24_horas/bloqueia_cfc_vencido.php';
include '24_horas/limpar_temp.php';
include '24_horas/emails_automaticos.php';
include '24_horas/migrar.log.php';
include '24_horas/cancelamentos_fastconnect.php';
include '24_horas/homologar_certificados.php';
include '24_horas/cancelar_matricula_30dias.php';
include '24_horas/matriculas_cancelar_vencimento_ava.php';
include '24_horas/associar_folha.php';
