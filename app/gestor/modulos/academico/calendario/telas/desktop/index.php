<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/css/calendario.css" type="text/css" media="screen" />
<script type="text/javascript">
	function radio_filtro_mes(obj) {
		id = obj.id;
		var array = document.getElementById('radio_mes').getElementsByTagName('span');
		var t = array.length;
		document.getElementById('mes').value = obj.name;
		document.getElementById('form_filtro').submit();
	}

	function radio_filtro_ano(obj) {
		id = obj.id;
		var array = document.getElementById('radio_ano').getElementsByTagName('span');
		var t = array.length;
		document.getElementById('ano').value = obj.value;
		document.getElementById('form_filtro').submit();
	}
	function filtrarSindicato(select) {
		document.getElementById('idsindicato').value = document.getElementById('idsindicato').options[document.getElementById('idsindicato').selectedIndex].value;
		document.getElementById('form_filtro').submit();
	}
</script>
</head>
<body>
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
      </div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
            <?php
			if(!$_GET['mes']) $_GET['mes'] = date("m"); 
			if(!$_GET['ano']) $_GET['ano'] = date("Y");
			?>
		    <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" method="get" id="form_filtro">
				<input name="mes" id="mes" type="hidden" value="<?php echo $_GET['mes']; ?>" />
				<input name="ano" id="ano" type="hidden" value="<?php echo $_GET['ano']; ?>" />
                <select id="idsindicato" name="idsindicato" onchange="filtrarSindicato(this)">
                    <option value="">Todas os estados</option>
					  <?php foreach ($sindicatos as $sindicato) { ?>
						<option value="<?php echo $sindicato['idsindicato']; ?>" <?php if ($_GET['idsindicato'] == $sindicato['idsindicato']) echo 'selected="selected"'; ?> ><?php echo $sindicato['nome_abreviado']; ?></option>
					  <?php } ?>
				</select>
                <div class="btn-toolbar"> 
                    <div class="btn-group" id="radio_ano">
                    <?php for($i = -1; $i < 2; $i++) { ?>
                        <input type="button" class="btn  btn-small <?php if($_GET['ano'] == ($_GET['ano'] + $i)) { echo 'btn-primary'; } ?>" onclick="radio_filtro_ano(this)" value="<?php echo ($_GET['ano'] + $i);?>"/>
                    <? } ?>
                    </div>        
                    <div class="btn-group" id="radio_mes">
                    <?php for($i = 1; $i < 13; $i++) { $i = str_pad($i, 2, "0", STR_PAD_LEFT); ?>
                      <input type="button" class="btn btn-small <?php if($_GET['mes'] == $i){ echo 'btn-primary'; } ?>" name="<?php echo $i; ?>" onclick="radio_filtro_mes(this)" value="<?php echo $meses_min_idioma[$config['idioma_padrao']][$i]; ?>"/>
                    <?php } ?>  
                    </div> 
                </div>
			</form>
			<?php $linhaObj->gerarCalendario($idioma, $_GET['mes'], $_GET['ano'], $_GET['idsindicato']); ?>
          <div class="clearfix"></div>                                  
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
		$(document).ready(function(){ 
			$('div[rel*=tooltip]').tooltip({
				// live: true
			});
		});
	</script>
  </div>
</body>
</html>