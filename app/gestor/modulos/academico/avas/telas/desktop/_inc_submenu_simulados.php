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
  <a class="menu <?php if($url[6] == "editar") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/editar"><?php echo $idioma["menu_editar"]; ?></a>
  <a class="menu <?php if($url[6] == "perguntas") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/perguntas"><?php echo $idioma["menu_perguntas"]; ?></a>
  <a class="menu <?php if($url[6] == "remover") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/remover"><?php echo $idioma["menu_remover"]; ?></a>
</div>