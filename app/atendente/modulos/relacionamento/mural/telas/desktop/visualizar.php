<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?=$linha["titulo"]; ?></li>
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span9">
        <div class="box-conteudo">
          <div class="section-body">
            <div id="blog-posts">                
              <h4 class="post-title"><?=$linha['titulo']; ?></a></h4><small class="muted"><?=formataData($linha['data_cad'],'pt',1); ?></small><br /><br />
              <p><?=$linha['descricao']; ?></p>
            </div>
          </div>
          <div class="clearfix"></div>  
          <a href="javascript:void(0);" class="btn btn-primary" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><i class="icon-arrow-left icon-white"></i><?= $idioma["btn_voltar"]; ?></a>                                
        </div>
    </div>
    <div class="span3">
      <?php incluirLib("sidebar_mural",$config,$usu_vendedor); ?>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
</body>
</html>