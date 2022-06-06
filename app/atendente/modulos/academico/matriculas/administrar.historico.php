<?php

$matricula["historicos"] = $matriculaObj->RetornarHistoricos();

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

include("idiomas/".$config["idioma_padrao"]."/administrar.historico.php");
include("telas/".$config["tela_padrao"]."/administrar.historico.php");
	
?>