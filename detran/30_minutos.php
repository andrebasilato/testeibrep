<?php
$caminho = dirname(__DIR__);

require_once $caminho.'/app/includes/config.php';
require_once $caminho.'/app/includes/funcoes.php';
require_once $caminho.'/detran/includes/funcoes.php';
require_once $caminho.'/app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/app/classes/core.class.php';
require_once $caminho.'/app/classes/detran.class.php';
require_once $caminho.'/app/includes/config.interfaces.php';
$coreObj = new Core;
$detran = new Detran();
$estadosDetran = $detran->listarEstadosIntegrados();
$detran->rotinaCron = true;

try {
    require_once '30_minutos/manter_conexao.php';
} catch (Exception $e) {
    echo "Exceção capturada no manter_conexao.php: ", $e->getMessage(), "\n";
}
require_once '30_minutos/creditos.php';
require_once '30_minutos/certificado.php';
