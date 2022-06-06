<?php
// Includes gerais
$raizSistema = dirname(__DIR__);
require $raizSistema . '/app/includes/config.php';
require $raizSistema . '/app/especifico/inc/config.especifico.php';
require $raizSistema . '/app/includes/funcoes.php';
require $raizSistema . '/cron/includes/funcoes.php';
// Classe PHPMailer (e-mail)
require $raizSistema . '/app/classes/PHPMailer/PHPMailerAutoload.php';

// Classe Core (classe pai)
require $raizSistema . '/app/classes/core.class.php';
$coreObj = new Core;
$diaDaSemana = intval(date('w'));
$mes = intval(date('d'));
if ($diaDaSemana === 1 || $mes === 01) {
    include '24_horas/gerar_faturas.php';
}


