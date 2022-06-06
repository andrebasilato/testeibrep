<?php

$caminho = dirname(__DIR__);

require_once $caminho.'/app/includes/config.php';
$config['urlSistema'] = 'http://'. $config['url'];

require_once $caminho.'/app/includes/funcoes.php';
require_once $caminho.'/detran/includes/funcoes.php';
require_once $caminho.'/app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/app/classes/core.class.php';
require_once $caminho.'/app/classes/orio/Transacoes.php';
require_once $caminho.'/app/includes/config.interfaces.php';
require_once $caminho.'/app/classes/detran.class.php';
$detran = new Detran();
$estadosDetran = $detran->listarEstadosIntegrados();

$coreObj = new Core;
$transacoes = new Transacoes();
require_once '5_minutos/liberacao.php';
