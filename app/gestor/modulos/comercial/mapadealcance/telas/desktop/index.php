<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style>
.box-conteudo {
	padding: 0px !important;
	position:relative;
}
.box-fullscreen {
	position: fixed;
	z-index: 20001;
	top: 30px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	background-color:#096;
}
.box-fullscreen .iframeMap {
	height: 500px;
}
#dibBtnFull {
	position: absolute;
	right: 110px;
	top: 6px;
	z-index: 1;
	width: 110px;
	text-align: center;
	color: rgb(0, 0, 0);
	font-family: Roboto, Arial, sans-serif;
	-webkit-user-select: none;
	font-size: 11px;
	background-color: rgb(255, 255, 255);
	padding: 1px 6px;
	border-bottom-left-radius: 2px;
	border-top-left-radius: 2px;
	-webkit-background-clip: padding-box;
	background-clip: padding-box;
	border: 1px solid rgba(0, 0, 0, 0.14902);
	-webkit-box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;
	box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;
	min-width: 22px;
	font-weight: 500;
	cursor:pointer;
}
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1>
        <?= $idioma["pagina_titulo"]; ?>
        &nbsp;<small>
        <?= $idioma["pagina_subtitulo"]; ?>
        </small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
      <? if($_GET["q"]) { ?>
      <li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li>
      <? } ?>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?>
      <?= date("d/m/Y H\hi"); ?>
      </span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div id="boxMapa" class="box-conteudo">
        <div id="dibBtnFull"> Aumentar o mapa <i class="icon-fullscreen"></i> </div>
        <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/mapa" frameborder="0" width="100%" height="100%" style="height:100%; min-height:480px" class="iframeMap"></iframe>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
</div>
<script>
$( document ).ready(function() {
  	$('#dibBtnFull').click(function() {
    	$("#boxMapa").toggleClass( "box-fullscreen" );
        if(1 === $(this).html().indexOf('Aumentar')){
                $(this).html(' Diminuir o mapa <i class="icon-fullscreen"></i> ');
            } else {
                $(this).html(' Aumentar o mapa <i class="icon-fullscreen"></i> ');
         }
	});
});

</script>
</body>
</html>