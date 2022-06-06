<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$informacoes); ?>
</head>
<body>
<?php incluirLib("topo",$config,$informacoes); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        
      <h2><?= $idioma["sem_acesso"]; ?></h2>  
      <p><a href="javascript:history.back(-1);"><strong><?= $idioma["voltar"]; ?></strong></a></p>
      
      <p><?= $idioma["administrador"]; ?></p>
      
<div class="clearfix"></div>                                  
        </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$informacoes); ?>
</div>
</body>
</html>