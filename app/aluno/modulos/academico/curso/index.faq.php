<?php
require_once '../classes/core.class.php';
require_once '../classes/avas.class.php';
require_once '../classes/avas.faqs.class.php';
$faqObj = new Faq();
$faqObj->set('idava',$ava['idava'])
			->set('idmatricula',$matricula['idmatricula'])
			->set('idpessoa',$usuario['idpessoa'])
			->set('modulo', $url[0]);

if(isset($url[5])) {
	$faqs = $faqObj->listarFaqsAva($ava['idava']);
} 
require 'idiomas/'.$config['idioma_padrao'].'/faq.php';
require 'telas/'.$config['tela_padrao'].'/faq.php';
exit;