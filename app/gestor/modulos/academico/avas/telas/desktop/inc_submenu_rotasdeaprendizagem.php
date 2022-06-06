<style type="text/css">
  .menu {
	border-radius: 3px 3px 3px 3px;
	color: #FFFFFF;
	cursor: pointer;
	font-size: 9px;
	font-weight: bold;
	line-height: 30px;
	margin-right: 5px;
	padding: 5px;
	text-transform: uppercase;
	white-space: nowrap;
	color:#FFFFFF !important;
	text-decoration:none !important;
  }
  .ativo {
	background-color: #0055D5;
  }
  .inativo {
	background-color: #838383;
  }
</style>
<div style="padding-top:15px; padding-bottom:15px;">
  <a class="menu <?php if($url[4] == "rotasdeaprendizagem" && !$url[5]) { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/rotasdeaprendizagem"><?php echo $idioma["menu_editar"]; ?></a>
  <a class="menu <?php if($url[4] == "rotasdeaprendizagem" && $url[6] == "objetos") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $linha["idrota_aprendizagem"]; ?>/objetos"><?php echo $idioma["menu_objetos"]; ?></a>
</div>