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

$dataHoje = new DateTime();
$horario = $dataHoje->format('G');

if ($horario == 4) {
    include("1_hora/apagar_tabelas.php");
}

if ($horario == 19) {
    include("1_hora/enviar_relatorio_gerencial_adm.php");
}

if ($horario == 8 || $horario == 10 || $horario == 12 || $horario == 14 || $horario == 16 || $horario == 18) {
    include("1_hora/alterar_situacao_detran.php");
}

include '1_hora/clonar_perguntas.php';
