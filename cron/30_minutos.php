<?php
/**
 * CronTab implementation for user synchronize to COFECI
 *
 * How to use it:
 *
 * <code>
 *
 * </code>
 *
 * OBS: Please, if you are looking for modify it, follow
 * Object Calisthenics concepts
 *
 * @author  Jefersson Nathan
 * @package Alfama Oráculo
 * @since   v2.9/2013
*/

//  Adiciona o diretorio 'classes' ao includePath
$baseDirectory = realpath(dirname(__FILE__) . '/../');

set_include_path(
    get_include_path(). PATH_SEPARATOR .
    $baseDirectory . DIRECTORY_SEPARATOR . 'classes'
);

require $baseDirectory . '/app/classes/phpbuglost.php';
// Includes gerais
require $baseDirectory . '/app/includes/config.php';
require $baseDirectory . '/app/especifico/inc/config.especifico.php';
require $baseDirectory . '/app/includes/funcoes.php';

// Classe PHPMailer (e-mail)
include($baseDirectory . "/app/classes/PHPMailer/PHPMailerAutoload.php");

// Classe Core (classe pai)
require $baseDirectory . '/app/classes/core.class.php';
$coreObj = new Core;
