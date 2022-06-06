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
include '8_horas/pagamento_nao_salvo_pagseguro.php';
