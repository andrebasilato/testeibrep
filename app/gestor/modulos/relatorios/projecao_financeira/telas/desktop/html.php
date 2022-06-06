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
    <td width="120" colspan="2" rowspan="2" align="center" style="background-color:#E4E4E4; text-align:center;"><strong style="text-transform:uppercase;">EVENTO FINANCEIRO</strong><strong style="text-transform:uppercase;"></strong></td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?> 
    <td colspan="2" align="center" style="background-color:#E4E4E4; text-align:center; width: 120px;"><font title="Idsindicato: <?= $sindicato['idsindicato']; ?>"><strong style="text-transform:uppercase;"><?= $sindicato['nome_abreviado']; ?></strong></font></td>
    <? } ?>
    <td colspan="2" align="center" bgcolor="#FFFAD3" style="background-color:#FFFAD3; text-align:center; width: 120px;"><strong style="text-transform:uppercase;">TOTAL</strong></td>
  </tr>
  <tr>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; width: 40px;"><strong>PAR.</strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; width: 80px;"><strong>TOTAL</strong></td>
    <? } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; width: 40px;"><strong>PAR.</strong></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; width: 80px;"><strong>TOTAL</strong></td>    
  </tr>
<? 
$subtotal = array();
foreach($dadosArray['cursos'] as $idcurso => $curso) { ?> 
    <? foreach($dadosArray['eventos'] as $ind => $evento) { ?>    
    <tr>
      <? if($ind == 0) { ?>
      <td rowspan="<?= count($dadosArray['eventos'])+1; ?>" align="center" style="text-align:center"><font title="Idcurso: <?= $idcurso; ?>"><?= $curso; ?></font></td>
      <? } ?>
      <td align="center"><font title="Idevento: <?= $evento['idevento']; ?>"><?= strtoupper($evento['nome']); ?></font></td>
          <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { 
                $subtotal[$sindicato['idsindicato']][$idcurso]["parcelas"] += $dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['parcelas'];
                $subtotal[$sindicato['idsindicato']][$idcurso]["total"] += $dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['total'];                  

                $subtotal_eventos[$idcurso][$evento['idevento']]["parcelas"] += $dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['parcelas'];
                $subtotal_eventos[$idcurso][$evento['idevento']]["total"] += $dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['total'];           
          ?>
          <td align="center" style="text-align:center;"><?= $relatorioObj->FormataNumero($dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['parcelas']); ?></td>
      <td align="center" style="text-align:center;"><? $relatorioObj->FormataValor($dadosArray['matriculas'][$sindicato['idsindicato']][$idcurso][$evento['idevento']]['total']); ?></td>
          <? } ?>
          <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero($subtotal_eventos[$idcurso][$evento['idevento']]["parcelas"]); ?></td>
          <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($subtotal_eventos[$idcurso][$evento['idevento']]["total"]); ?></td>          
    </tr>
    <? } ?>
  <tr>
    <td align="center" bgcolor="#FFFAD3" style="text-align:right"><strong>Subtotal</strong></td>
        <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero($subtotal[$sindicato['idsindicato']][$idcurso]["parcelas"], true); ?></td>
        <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($subtotal[$sindicato['idsindicato']][$idcurso]["total"], true); ?></td>
        <? 
                $total[$sindicato['idsindicato']]["parcelas"] += $subtotal[$sindicato['idsindicato']][$idcurso]["parcelas"];
                $total[$sindicato['idsindicato']]["total"] += $subtotal[$sindicato['idsindicato']][$idcurso]["total"];        
       
                $total_geral["parcelas"] += $subtotal[$sindicato['idsindicato']][$idcurso]["parcelas"];
                $total_geral["total"] += $subtotal[$sindicato['idsindicato']][$idcurso]["total"];        
       
                $total_cursos[$idcurso]["parcelas"] += $subtotal[$sindicato['idsindicato']][$idcurso]["parcelas"];
                $total_cursos[$idcurso]["total"] += $subtotal[$sindicato['idsindicato']][$idcurso]["total"];  
        } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero($total_cursos[$idcurso]["parcelas"], true); ?></td>
        <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($total_cursos[$idcurso]["total"], true); ?></td>        
  </tr>
<? } ?>  
  <tr>
    <td rowspan="3" align="center" style="text-align:center"><font title="Idestado: <?= $dados['idestado']; ?>">
      Contas
    </font></td>
    <td align="center">A RECEBER</td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) {                
           
                $subtotal_contas_areceber["parcelas"] += $dadosArray['contas'][$sindicato['idsindicato']]['receita']['parcelas'];
                $subtotal_contas_areceber["total"] += $dadosArray['contas'][$sindicato['idsindicato']]['receita']['total'];            

                $subtotal_contas[$sindicato['idsindicato']]["parcelas"] = $subtotal_contas[$sindicato['idsindicato']]["parcelas"]+$dadosArray['contas'][$sindicato['idsindicato']]['receita']['parcelas'];
                $subtotal_contas[$sindicato['idsindicato']]["total"] = $subtotal_contas[$sindicato['idsindicato']]["total"]+$dadosArray['contas'][$sindicato['idsindicato']]['receita']['total'];     
    
    ?>
    <td align="center" style="text-align:center;"><?= $relatorioObj->FormataNumero($dadosArray['contas'][$sindicato['idsindicato']]['receita']['parcelas']); ?></td>
    <td align="center" style="text-align:center;"><? $relatorioObj->FormataValor($dadosArray['contas'][$sindicato['idsindicato']]['receita']['total']); ?></td>
    <? } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero( $dadosArray['contas'][$sindicato['idsindicato']]['receita']['parcelas']); ?></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor( $dadosArray['contas'][$sindicato['idsindicato']]['receita']['total']); ?></td>
  </tr>
  <tr>
    <td align="center">A PAGAR</td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) {            
    
                $subtotal_contas_apagar["parcelas"] += $dadosArray['contas'][$sindicato['idsindicato']]['despesa']['parcelas'];
                $subtotal_contas_apagar["total"] += $dadosArray['contas'][$sindicato['idsindicato']]['despesa']['total'];
                
                $subtotal_contas[$sindicato['idsindicato']]["parcelas"] = $subtotal_contas[$sindicato['idsindicato']]["parcelas"]+$dadosArray['contas'][$sindicato['idsindicato']]['despesa']['parcelas'];
                $subtotal_contas[$sindicato['idsindicato']]["total"] = $subtotal_contas[$sindicato['idsindicato']]["total"]+$dadosArray['contas'][$sindicato['idsindicato']]['despesa']['total'];                      
   
    ?>
    <td align="center" style="text-align:center;"><?= $relatorioObj->FormataNumero($dadosArray['contas'][$sindicato['idsindicato']]['despesa']['parcelas']); ?></td>
    <td align="center" style="text-align:center;"><? $relatorioObj->FormataValor($dadosArray['contas'][$sindicato['idsindicato']]['despesa']['total']); ?></td>
    <? 
    }  ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero( $subtotal_contas_apagar['parcelas']); ?></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor( $subtotal_contas_apagar['total']); ?></td>    
  </tr>
  <tr>
    <td align="center" bgcolor="#FFFAD3" style="text-align:right"><strong>Subtotal</strong></td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
<td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero($subtotal_contas[$sindicato['idsindicato']]["parcelas"], true); ?></td>
        <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($subtotal_contas[$sindicato['idsindicato']]["total"], true); ?></td>
    <? 
                $subtotal_contas_geral["parcelas"] += $subtotal_contas[$sindicato['idsindicato']]["parcelas"];
                $subtotal_contas_geral["total"] += $subtotal_contas[$sindicato['idsindicato']]["total"];     
    
                $total[$sindicato['idsindicato']]["parcelas"] += $subtotal_contas[$sindicato['idsindicato']]["parcelas"];
                $total[$sindicato['idsindicato']]["total"] += $subtotal_contas[$sindicato['idsindicato']]["total"];  
            
                $total_geral["parcelas"] += $subtotal_contas[$sindicato['idsindicato']]["parcelas"];
                $total_geral["total"] += $subtotal_contas[$sindicato['idsindicato']]["total"];  
    } ?>
<td align="center" bgcolor="#FFFAD3" style="text-align:center;"><?= $relatorioObj->FormataNumero($subtotal_contas_geral["parcelas"], true); ?></td>
        <td align="center" bgcolor="#FFFAD3" style="text-align:center;"><? $relatorioObj->FormataValor($subtotal_contas_geral["total"], true); ?></td>    
  </tr>
  <tr>
    <td colspan="2" align="right" style="text-align:right;"><strong>Total</strong></td>
    <? foreach($dadosArray['sindicatos'] as $indSindicato => $sindicato) { ?>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><strong><?= $relatorioObj->FormataNumero($total[$sindicato['idsindicato']]["parcelas"], true); ?></strong></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total[$sindicato['idsindicato']]["total"], true); ?></td>
    <? } ?>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; border-top: 2px #000 solid;"><strong><?= $relatorioObj->FormataNumero($total_geral["parcelas"], true); ?></strong></td>
    <td align="center" bgcolor="#FFFAD3" style="text-align:center; border-top: 2px #000 solid;"><? $relatorioObj->FormataValor($total_geral["total"], true); ?></td>    
  </tr>
  
</table>
* Somente contas canceladas e renegociadas não são contabilizadas nesse relatório.<br>
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