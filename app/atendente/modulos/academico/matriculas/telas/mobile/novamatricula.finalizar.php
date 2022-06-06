<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
  <style type="text/css"> 
    legend {
      font-size: 10px;
    }
  </style>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
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
      
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
      <div class="box-conteudo" style="padding:35px;">
        <div class="row-fluid">
          <div class="span12">
            <section id="matricula">
              <legend><?= $idioma["label_matricula"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:30px; line-height:35px; text-transform:uppercase;"># <?= $matricula["idmatricula"]; ?></h2> 
              <legend><?=$idioma["label_cliente"];?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["pessoa"]["nome"]; ?> - <span style="color:#666666"><?= $matricula["pessoa"]["documento"]; ?></span></h2>
              <legend><?=$idioma["label_oferta"];?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["oferta"]["nome"]; ?></h2>                           
              <legend><?=$idioma["label_curso"];?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["curso"]["nome"]; ?></h2>                                     
              <legend><?=$idioma["label_escola"];?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["escola"]["nome_fantasia"]; ?></h2>	  
			  <legend><?= $idioma["label_turma"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["turma"]["nome"]; ?></h2>
              <legend><?=$idioma["label_vendedor"];?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $matricula["vendedor"]["nome"]; ?></h2>
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