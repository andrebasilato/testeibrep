<style>
    .cabecalho-subsecao {
        background-color: #F4F4F4;	
		-webkit-border-top-left-radius: 8px;
		-webkit-border-top-right-radius: 8px;
		-moz-border-radius-topleft: 8px;
		-moz-border-radius-topright: 8px;
		border-top-left-radius: 8px;
		border-top-right-radius: 8px;
        padding:15px;
		border-top: 1px solid #ddd;
    }
    .cabecalho-subsecao .tituloEdicao {
        font-size: 18px;
        padding-bottom: 5px;
    }
    .tituloOpcao {
        padding-left: 15px;
        margin-top: 15px;
        border-bottom: 1px #E4E4E4 solid;
    }
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
<div>
   <a class="menu <?php if($url[6] == "academico") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/academico"><?php echo $idioma["menu_academico"]; ?></a>
   <a class="menu <?php if($url[6] == "comercial") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/comercial"><?php echo $idioma["menu_comercial"]; ?></a>
   <a class="menu <?php if($url[6] == "financeiro") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/financeiro"><?php echo $idioma["menu_financeiro"]; ?></a>
  <a class="menu <?php if($url[6] == "remover") { echo "ativo"; } else { echo "inativo"; } ?>" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/remover"><?php echo $idioma["menu_remover"]; ?></a>
</div>