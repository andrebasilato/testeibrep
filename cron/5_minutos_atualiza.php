<?php

$caminhoApp = realpath(dirname(__FILE__) . '/../');

// Includes gerais
include($caminhoApp . "/app/includes/config.php");
include($caminhoApp . "/app/especifico/inc/config.especifico.php");
include($caminhoApp . "/app/includes/funcoes.php");
include($caminhoApp . "/cron/includes/funcoes.php");

// Classe PHPMailer (e-mail)
include($caminhoApp . "/app/classes/PHPMailer/PHPMailerAutoload.php");

// Classe Core (classe pai)
include($caminhoApp . "/app/classes/core.class.php");

$coreObj   = new Core();

include '5_minutos/atualiza_qtd_parcelas.php';
