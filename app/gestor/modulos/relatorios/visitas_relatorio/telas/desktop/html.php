<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body, td, th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	color: #000;
}
body {
	background-color: #FFF;
	background-image:none;
	padding-top: 0px;
	margin-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
}
a:link {
	color: #000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000;
}
a:hover {
	text-decoration: underline;
	color: #000;
}
a:active {
	text-decoration: none;
	color: #000;
}
table.zebra-striped {
	padding: 0;
	font-size: 9px;
	border-collapse: collapse;
}
table.zebra-striped th, table td {
	padding: 8px 8px 7px;
	line-height: 16px;
	text-align: left;
}
table.zebra-striped th {
	padding-top: 9px;
	font-weight: bold;
	vertical-align: middle;
}
table.zebra-striped td {
	vertical-align: top;
	border: 1px solid #000;
}
table.zebra-striped tbody th {
	border-top: 1px solid #ddd;
	vertical-align: top;
}
.zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
	background-color: #F4F4F4;
}
.zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
	background-color: #E4E4E4;
}
</style>
<style type="text/css" media="print">
body, td, th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 9px;
	color: #000;
}
.impressao {
	display:none;	
}
</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script>
$(document).ready(function(){ 	
	$('a[rel*=facebox]').facebox();	
});
var regras = new Array();
regras.push("required,nome,<?php echo $idioma['nome_obrigatorio']; ?>");
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="8">
      <tr>
        <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
      </tr>
    </table></td>
    <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td  align="right" valign="top"><table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
      <tr>
        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
        <td><a href="javascript:window.print();">
          <?= $idioma["imprimir"]; ?>
        </a></td>
		
		<td>
			<a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
			<div id="link_salvar" style="display:none;"> 
				<div style="width:300px;">
				<form method="post" onsubmit="return validateFields(this, regras)">
					<input type="hidden" name="acao" value="salvar_relatorio" />
					<label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
					<input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
					<input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
				</form>
				</div>
			</div>
		</td>
		<td>
			<form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?php echo $_SERVER["QUERY_STRING"]; ?>">  
				<input class="btn" type="submit" value="<?php echo $idioma['baixar_planilha'] ?>" />
			</form>
		</td>
		
      </tr>
    </table></td>
  </tr>
</table>

<? if($mensagem_sucesso) { ?>
	<div class="alert alert-success fade in">
		<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
		<strong><?= $idioma[$mensagem_sucesso]; ?></strong>
	</div>
<? } else if($mensagem_erro) { ?>
	<div class="alert alert-error fade in">
		<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
		<strong><?= $idioma[$mensagem_erro]; ?></strong>
	</div>
<? } ?>

<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Registros encontrados: <?= count($dadosArray); ?></td>
  </tr>
</table>
<?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
</body>
</html>