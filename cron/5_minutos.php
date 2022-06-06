<?php

$caminhoApp = realpath(dirname(__FILE__) . '/../');

// Includes gerais
require $caminhoApp . '/app/includes/config.php';
require $caminhoApp . '/app/especifico/inc/config.especifico.php';
require $caminhoApp . '/app/includes/funcoes.php';
require $caminhoApp . '/cron/includes/funcoes.php';

// Classe PHPMailer (e-mail)
require $caminhoApp . '/app/classes/PHPMailer/PHPMailerAutoload.php';

// Classe Core (classe pai)
require $caminhoApp . '/app/classes/core.class.php';
$coreObj = new Core;
include '5_minutos/fila_mailings.php';
include '5_minutos/status_sms.php';
include '5_minutos/fila_pesquisas.php';
include '5_minutos/retorno_pagarme.php';
include '5_minutos/retorno_pagseguro.php';
include '5_minutos/retorno_fastconnect.php';
