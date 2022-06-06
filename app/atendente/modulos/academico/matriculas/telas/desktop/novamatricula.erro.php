<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style> 
.hidden {
	margin-top:-75px;
}
legend {
	font-size: 10px;
}
#mensagem {
	margin-bottom: 20px;	
}
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>        
      <li class="active"><?= $idioma["nav_novamatricula"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:35px;">
        <ul id="navegacao_passos" style="margin-bottom:20px;">
          <li class="frist inprogress"><?= $idioma["nav_oferta_curso_escola"]; ?></li>
          <li><?= $idioma["nav_aluno"]; ?><span></span></li>
          <li><?= $idioma["nav_vendedor"]; ?><span></span></li>
          <li><?= $idioma["nav_financeiro"]; ?><span></span></li>
          <li class="last"><?= $idioma["nav_concluida"]; ?><span></span></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">    
            <div id="mensagem">
              <h3><?=$idioma["msg_matricula_erro"];?></h3>
              <span><?=$idioma["msg_matricula_erro_descricao"];?></span>
            </div>    
            <section id="empreendimento">
              <legend><?= $idioma["erro_label"];?></legend>          
              <ul class="nav nav-tabs nav-stacked">
                <? foreach($erro as $ind => $erro_idioma) { ?>
                   <li><a><?= $idioma[$erro_idioma]; ?></a></li> 
                <? } ?>
              </ul>          
              <br />          
              <a class="btn btn-large btn-primary" style="color:#FFFFFF" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?= $idioma["iniciar_nova_matricula"]?></a>
              <br />
            </section> 
          </div>
        </div>    
        <div class="clearfix"></div>                                  
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>
</body>
</html>