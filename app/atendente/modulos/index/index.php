<?php
include("../classes/vendedores.class.php");
require '../classes/bannersavaaluno.class.php';
$linhaObj = new Vendedores();

$linhaObj->Set("id",$usu_vendedor['idvendedor']);
$linhaObj->Set("campos","c.nome, c.codigo, i.nome_abreviado, c.carga_horaria_total, c.tipo");
$cursos = $linhaObj->ListarCursosAss();

$bannerObj = new Banners_Ava_Aluno;
$bannerObj->set('idvendedor', $usu_vendedor['idvendedor']);
$banners = $bannerObj->retornarBannersAtendente();

include("idiomas/".$config["idioma_padrao"]."/index.php");
include("telas/".$config["tela_padrao"]."/index.php");
?>
