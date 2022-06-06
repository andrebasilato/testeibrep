<?php
include("../classes/professores.class.php");
$linhaObj = new Professores();

$linhaObj->Set("id",$usu_professor['idprofessor']);
$linhaObj->Set("campos","c.*");
$cursos = $linhaObj->ListarCursosAss();
$linhaObj->Set("campos","o.*, ow.nome as situacao, ow.cor_bg, ow.cor_nome");
$ofertas = $linhaObj->ListarOfertasAss();
$linhaObj->Set("campos","a.*");
$avas = $linhaObj->ListarAvasAss();

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>