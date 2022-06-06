<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<script type="text/javascript" src="/assets/plugins/highcharts/js/jquery.min.js"></script>
<script src="/assets/plugins/highcharts/js/highcharts.js"></script>
<script src="/assets/plugins/highcharts/js/modules/exporting.js"></script>
<script type="text/javascript">
	function filtrar(select) {
		document.getElementById('i').value = document.getElementById('i').options[document.getElementById('i').selectedIndex].value;
		document.getElementById('c').value = document.getElementById('c').options[document.getElementById('c').selectedIndex].value;
		document.getElementById('form_filtro').submit();
	}
</script>
</head>
<body >
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
      <li><a href="/<?= $url[0]; ?>">
        <?= $idioma["nav_inicio"]; ?>
        </a> <span class="divider">/</span></li>
      <li>
        <?= $idioma["modulo"]; ?>
      </li>
      <span class="pull-right" style="color:#999">
      <?= $idioma["hora_servidor"]; ?>
      <?= date("d/m/Y H\hi"); ?>
      </span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span9">

    <div class="box-conteudo">

        <h1>Conteúdo da página principal</h1>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>
