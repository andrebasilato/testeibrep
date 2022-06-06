<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
  <style type="text/css">
    legend {
      font-size: 10px;
    }
  </style>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
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


        <?php //INTERRUPÇÃO #174911 - ITEM (Informações Gerais) NO MENU INDICADOR DE NAVEGAÇÃO ?>
        <ul id="navegacao_passos" style="margin-bottom:20px;">
          <li class="frist fdone"><?= $idioma["nav_oferta_curso_escola"]; ?></li>
          <li class="done"><?= $idioma["nav_aluno"]; ?><span></span></li>
          <li class="done"><?= $idioma["nav_vendedor"]; ?><span></span></li>
          <li class="done"><?= $idioma["nav_informacoes"]; ?><span></span></li>
          <li class="last ldone"><?= $idioma["nav_concluida"]; ?><span></span></li>
        </ul>


        <div class="row-fluid">
          <div class="span12">
            <section id="matricula">

              <?php if ($_POST['gerar_visita'] == 'S' && $alerta_visita[0]) {?>
                  <ul class="nav nav-tabs nav-stacked">
                    <? //foreach($alerta_visita as $ind => $erro_idioma) { ?>
                       <li><a><?= $idioma[$alerta_visita[0]]; ?></a></li>
                    <? //} ?>
                  </ul>
              <?php } ?>

              <legend><?= $idioma["label_matricula"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:30px; line-height:35px; text-transform:uppercase;"># <?= $matricula["idmatricula"]; ?></h2>
              <a class="btn btn-small" href="/<?= $url[0]; ?>/academico/matriculas/<?= $matricula["idmatricula"]; ?>/administrar" style="margin-bottom:10px; margin-right:10px;"><?= $idioma["btn_administrar"]; ?></a>
              <br />
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
  <? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>
