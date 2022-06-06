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
	padding: 6px 6px 5px;
	line-height: 12px;
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
				<form method="post" onSubmit="return validateFields(this, regras)">
					<input type="hidden" name="acao" value="salvar_relatorio" />
					<label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
					<input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
					<input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
				</form>
				</div>
			</div>
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
<? if(count($dadosArray["erros"]) > 0){ ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma["form_erros"]; ?></strong>
            <? foreach($dadosArray["erros"] as $ind => $val) { ?>
                <br />
                <?php echo $idioma[$val]; ?>
            <? } ?>
        </strong>
    </div>
  <? } ?>
  
<?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
<table border="1">
  <tr>
    <td width="160" rowspan="2" align="center" style="background-color:#E4E4E4; text-align:center;"><strong style="text-transform:uppercase;">CATEGORIA FINANCEIRO</strong><strong style="text-transform:uppercase;"></strong></td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?> 
    <td colspan="3" align="center" style="background-color:#E4E4E4; text-align:center; width: 240px;"><font title="Idsindicato: <?= $sindicato['idsindicato']; ?>"><strong style="text-transform:uppercase;"><?= $sindicato['nome_abreviado']; ?></strong></font></td>
    <? } ?>
    <td colspan="3" align="center" bgcolor="#FFFAD3" style="background-color:#FFFAD3; text-align:center; width: 240px;"><strong style="text-transform:uppercase;">TOTAL</strong></td>
  </tr>
  <tr>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; width: 80px;"><strong>ORÇADO</strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; width: 80px;"><strong>PREVISTO</strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; width: 80px;"><strong>SALDO</strong></td>
    <? } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; width: 80px;"><strong>ORÇADO</strong></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; width: 80px;"><strong>PREVISTO</strong></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; width: 80px;"><strong>SALDO</strong></td>    
  </tr>
    <? foreach($dadosArray['categorias'] as $ind => $categoria) { ?>    
    <tr>
      <td align="center"><font title="Idcategoria: <?= $categoria['idcategoria']; ?>"><?= strtoupper($categoria['nome']); ?></font></td>
          <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { 
               
			    $total['sindicato'][$sindicato['idsindicato']]["parcelas"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total['sindicato'][$sindicato['idsindicato']]["total"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'];             

			    $total['categoria'][$categoria['idcategoria']]["parcelas"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total['categoria'][$categoria['idcategoria']]["total"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['total']; 
 
			    $total['geral']["parcelas"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total['geral']["total"] += $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'];  

			    $total_orcado['sindicato'][$sindicato['idsindicato']]["parcelas"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total_orcado['sindicato'][$sindicato['idsindicato']]["total"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'];             

			    $total_orcado['categoria'][$categoria['idcategoria']]["parcelas"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total_orcado['categoria'][$categoria['idcategoria']]["total"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total']; 
 
			    $total_orcado['geral']["parcelas"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas'];
                $total_orcado['geral']["total"] += $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total']; 
				
		 
		  ?>
          <td align="center" bgcolor="#F4F4F4" style="text-align:center;" title="<?= (int) $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas']; ?> Registro(s)"><? $relatorioObj->FormataValor($dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'], false, 9999999999); ?></td>
      	  <td align="center" style="text-align:center;" title="<?= (int) $dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['parcelas']; ?> Registro(s)"><? $relatorioObj->FormataValor($dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'], false, $dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total']); ?></td>
          <td align="center" style="text-align:center;"><? $relatorioObj->FormataSaldo($dadosArray['orcamentos'][$sindicato['idsindicato']][$categoria['idcategoria']]['total']+$dadosArray['previsoes'][$sindicato['idsindicato']][$categoria['idcategoria']]['total'], false, 0); ?></td>
		  <? } ?>
          <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($total_orcado['categoria'][$categoria['idcategoria']]["total"], false, 9999999999); ?></td>
      <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($total['categoria'][$categoria['idcategoria']]["total"], false, $total_orcado['categoria'][$categoria['idcategoria']]["total"]); ?></td>          
    	  <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataSaldo($total_orcado['categoria'][$categoria['idcategoria']]["total"]+$total['categoria'][$categoria['idcategoria']]["total"], false, 0); ?></td>
  </tr>
    <? } ?>
  <tr>
    <td align="right" style="text-align:right;"><strong>Total</strong></td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total_orcado['sindicato'][$sindicato['idsindicato']]["total"], true, 9999999999); ?></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total['sindicato'][$sindicato['idsindicato']]["total"], true, $total_orcado['sindicato'][$sindicato['idsindicato']]["total"]); ?></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataSaldo($total_orcado['sindicato'][$sindicato['idsindicato']]["total"]+$total['sindicato'][$sindicato['idsindicato']]["total"], true); ?></td>
	<? } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total_orcado['geral']["total"], true, 9999999999); ?></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total['geral']["total"], true, $total_orcado['geral']["total"]); ?></td>    
  <td align="center" bgcolor="#FFFAD3" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataSaldo($total_orcado['geral']["total"]+$total['geral']["total"], true, 0); ?></td>
  </tr>
  
</table>
<?php /*if (!$_GET['situacao']) { ?>
* Somente contas canceladas, renegociadas e transferidas não são contabilizadas nesse relatório.<br>
<?php }*/ ?>
<br>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>


<?php /*?><pre>
	<?= print_r($dadosArray); ?>
</pre><?php */?>

</body>
</html>