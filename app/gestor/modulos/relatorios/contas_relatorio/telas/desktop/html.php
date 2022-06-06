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
  padding: 4px;
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
<script type="text/javascript">
  $(document).ready(function(){ 	
	$('a[rel*=facebox]').facebox();	
  });
  var regras = new Array();
  regras.push("required,nome,<?= $idioma['nome_obrigatorio']; ?>");
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80">
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?= $config['logo_pequena']; ?>" />*/?></td>
        </tr>
      </table>
    </td>
    <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td align="right" valign="top">
      <table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
        <tr>
          <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
          <td><a href="javascript:window.print();"><?= $idioma["imprimir"]; ?></a></td>		
          <td>
			<a class="btn" href="#link_salvar" rel="facebox" ><?= $idioma['salvar_relatorio'] ?></a>
			<div id="link_salvar" style="display:none;"> 
              <div style="width:300px;">
                <form method="post" onSubmit="return validateFields(this, regras)">
                  <input type="hidden" name="acao" value="salvar_relatorio" />
                  <label for="nome"><strong><?= $idioma['tabela_nome']; ?>:</strong></label>
                  <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                  <input type="submit" class="btn" value="<?= $idioma['salvar_relatorio'] ?>" />
				</form>
              </div>
			</div>
          </td>
          <td>
			<form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?= $_SERVER["QUERY_STRING"]; ?>">  
              <input class="btn" type="submit" value="<?= $idioma['baixar_planilha'] ?>" />
			</form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php if($mensagem_sucesso) { ?>
  <div class="alert alert-success fade in">
    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
    <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
  </div>
<?php } else if($mensagem_erro) { ?>
  <div class="alert alert-error fade in">
    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
    <strong><?= $idioma[$mensagem_erro]; ?></strong>
  </div>
<?php } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td>Registros encontrados: <?= count($dadosArray); ?></td>
  </tr>
</table>
<?php $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
<table width="2000" border="1" id="sortTableExample">
  <thead>
    <tr>
      <?php 
	  $totalColspan1 = 0;
	  foreach($colunas as $ind => $coluna) { 
		if(in_array($ind,$_GET['colunas'])) {
		  if($ind < 11) {
			$totalColspan1++;
		  }
		  ?>
          <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma[$coluna]; ?></div></td>
		<?php }
	  } ?>
    </tr>
  </thead>
  <tbody>  
	<?php if(count($dadosArray) == 0){ ?>	
      <tr>
         <td colspan="34">Nenhum informação foi encontrada.</td>
      </tr>
	<?php
	} else {
	
	  $totalValor = 0;
	  $totalJuros = 0;
	  $totalMulta = 0;
	  $totalDescontos = 0;
	  $totalValorLiquido = 0;
	  $numerosEventosFinanceiros = array();
	  $numerosCentrosDeCustos = array();
	  
	  foreach($dadosArray as $i => $linha){		
		$totalValor += $linha["valor"];
		$totalJuros += $linha["valor_juros"];
		$totalMulta += $linha["valor_multa"];
		$totalDescontos += $linha["valor_desconto"]; 
		
		$linha["valor_liquido"] = ($linha["valor"] + $linha["valor_juros"] + $linha["valor_multa"]) - $linha["valor_desconto"];
		$totalValorLiquido += $linha["valor_liquido"];
		
		if($linha['idevento']) {
		  $numerosEventosFinanceiros[$linha['idevento']]['parcelas']++;
		  $numerosEventosFinanceiros[$linha['idevento']]['total'] += $linha["valor"];
		  $numerosEventosFinanceiros[$linha['idevento']]['desconto'] += $linha["valor_desconto"]; 
		}
		
		if(count($linha['centros_custos']) > 0) {
		  foreach($linha['centros_custos'] as $idcentro_custo => $porcentagem) {
			  $valorContaCentroCusto = ($linha["valor"] * $porcentagem['porcentagem']) / 100;
			  
			  $numerosCentrosDeCustos[$idcentro_custo]['contas']++;
			  $numerosCentrosDeCustos[$idcentro_custo]['total'] += $valorContaCentroCusto;
			  $numerosCentrosDeCustos['total'] += $valorContaCentroCusto;
		  }
		}
		
		$color = '000';
		if($linha["valor"] < 0) {
		  $color = '900';
		} elseif($linha["valor"] > 0) {
		  $color = '037203';
		}
		$linha["valor"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor"], 2, ',', '.').'</td>
							</tr>
						   </table>';
		
		$color = '000';
		if($linha["valor_juros"] < 0) {
		  $color = '900';
		} elseif($linha["valor_juros"] > 0) {
		  $color = '037203';
		}
		$linha["valor_juros"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor_juros"], 2, ',', '.').'</td>
							</tr>
						   </table>';
						   
		$color = '000';
		if($linha["valor_multa"] < 0) {
		  $color = '900';
		} elseif($linha["valor_multa"] > 0) {
		  $color = '037203';
		}
		$linha["valor_multa"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor_multa"], 2, ',', '.').'</td>
							</tr>
						   </table>';
						   
		$color = '000';
		if($linha["valor_desconto"] < 0) {
		  $color = '900';
		} elseif($linha["valor_desconto"] > 0) {
		  $color = '037203';
		}
		$linha["valor_desconto"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor_desconto"], 2, ',', '.').'</td>
							</tr>
						   </table>';
		
		$color = '000';
		if($linha["valor_liquido"] < 0) {
		  $color = '900';
		} elseif($linha["valor_liquido"] > 0) {
		  $color = '037203';
		}
		$linha["valor_liquido"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor_liquido"], 2, ',', '.').'</td>
							</tr>
						   </table>';
		
		
		$linha["parcela"] = $linha["parcela"]."/".$linha["total_parcelas"];

        if(isset($linha["valor_contrato"])) {
          $linha["valor_contrato"] = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							  <td style="color:#999">R$</td>
							  <td align="right" style="text-align:right;color:#'.$color.'">'.number_format($linha["valor_contrato"], 2, ',', '.').'</td>
							</tr>
						   </table>';
        }

		$linha["adimplente"] = "Sim";
		if($linha["data_vencimento"] < date('Y-m-d') && (!$linha["data_pagamento"] || $linha["data_pagamento"] == '0000-00-00')) {
		  $linha["adimplente"] = "Não";
		}
		
		$linha["data_vencimento"] = formataData($linha["data_vencimento"],"br",0);		
		
		if($linha["data_pagamento"]) {
		  $linha["data_pagamento"] = formataData($linha["data_pagamento"],"br",0);
		} else {
		  $linha["data_pagamento"] = "--";
		}
		
		$linha["forma_pagamento"] = $forma_pagamento_conta[$config["idioma_padrao"]][$linha["forma_pagamento"]];
		
		$linha["data1_cheque_alinea"] = "--";
		if($linha["data1_cheque_alinea"]) {
		  $linha["data1_cheque_alinea"] = formataData($linha["data1_cheque_alinea"],"br",0);
		}
		$linha["data2_cheque_alinea"] = "--";
		if($linha["data2_cheque_alinea"]) {
		  $linha["data2_cheque_alinea"] = formataData($linha["data2_cheque_alinea"],"br",0);
		}
		$linha["data3_cheque_alinea"] = "--";
		if($linha["data3_cheque_alinea"]) {
		  $linha["data3_cheque_alinea"] = formataData($linha["data3_cheque_alinea"],"br",0);
		}
                
                if(!$linha['vendedor']) {
		  $linha['vendedor'] = '--';
		}
		?>  
        <tr>

		  <?php foreach($colunas as $ind => $coluna) { 
			if(in_array($ind,$_GET['colunas'])) { ?>
			    <?php if ($ind != 40) { ?>
					<td><?= $linha[$coluna]; ?></td>
			    <?php } else { ?>
					<td><a href="/<?= $url[0]; ?>/financeiro/contas/idconta/<?= $linha['idconta']; ?>/pastavirtual" target="_blank">Arquivos</a></td>
				<?php } ?>
			<?php }
		  } ?>
        </tr>

          <!--   Centro de custos por conta   -->
          <?php

          $centroDeCustoContas = $relatorioObj->retornarCentrosDeCustosPorConta($linha['idconta']);

          if(! empty($centroDeCustoContas) && empty($_GET['idcentro_custo'])){

              ?>
              <tr>
                  <td colspan="<?= count($_GET['colunas']) ?>">
                      <table>
                          <thead>
                          <tr bgcolor="#F4F4F4" style="border: 1px solid; padding: 7px; font-family: normal;">
                              <th style="border: 1px solid; padding: 7px;"><?= $idioma['centro_custo']; ?></th>
                              <th style="border: 1px solid; padding: 7px;"><?= $idioma['porcentagem']; ?></th>
                              <th colspan="2" style="border: 1px solid; padding: 7px;"><?= $idioma['valor']; ?></th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          foreach ($centroDeCustoContas as $centros) {
                              ?>
                              <tr style="border: 1px solid; padding: 7px;">
                                  <td style="border: 1px solid; padding: 7px;">
                                      <?= $centros['nome'] ?>
                                  </td>
                                  <td style="border: 1px solid; padding: 7px;">
                                      <?= $centros['porcentagem'] ?>
                                  </td>
                                  <td style="color:#999">R$</td>
                                  <td align="right" style="text-align:right;"><?= number_format($centros["valor"], 2, ',', '.') ?></td>
                              </tr>
                              <?php
                          }
                          ?>
                          </tbody>
                      </table>
                  </td>
              </tr>
    <?php } ?>

  <?php } ?>
      <tr>
        <?php if($totalColspan1) { ?>
          <td colspan="<?= $totalColspan1; ?>" style="text-align:right"><?= $idioma['total']; ?>:</td>
        <?php } ?>
        <?php if(in_array(11,$_GET['colunas'])) { ?>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td style="color:#999">R$</td>
                <td align="right" style="text-align:right;<?php if($totalValor < 0) { echo " color:#900;"; } elseif($totalValor > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalValor, 2, ',', '.'); ?></td>
              </tr>
            </table>
          </td>
		<?php } ?>
        <?php if(in_array(12,$_GET['colunas'])) { ?>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($totalJuros < 0) { echo " color:#900;"; } elseif($totalJuros > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalJuros, 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <?php } ?>
        <?php if(in_array(13,$_GET['colunas'])) { ?>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($totalMulta < 0) { echo " color:#900;"; } elseif($totalMulta > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalMulta, 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <?php } ?>
        <?php if(in_array(14,$_GET['colunas'])) { ?>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($totalDescontos < 0) { echo " color:#900;"; } elseif($totalDescontos > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalDescontos, 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <?php } ?>
        <?php if(in_array(15,$_GET['colunas'])) { ?>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($totalValorLiquido < 0) { echo " color:#900;"; } elseif($totalValorLiquido > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalValorLiquido, 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <?php } ?>		
        <?php if(in_array(11,$_GET['colunas']) || in_array(12,$_GET['colunas']) || in_array(13,$_GET['colunas']) || in_array(14,$_GET['colunas']) || in_array(15,$_GET['colunas'])) { ?>
          <td colspan="34">&nbsp;</td>
        <?php } ?>
      </tr>
	<?php } ?>
  </tbody>
</table>
<br />
<br />
<table border="1" id="sortTableExample">
  <thead>
    <tr>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['evento_financeiro']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['parcelas']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['total']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['desconto']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['previsao_real']; ?></div></td>
    </tr>
    <?php 
	$previsaoReal = 0;
	foreach($eventosFinanceiros as $eventoFinanceiro) { 
	  $previsaoReal = $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'] - $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'];
	  ?>
      <tr>
        <td><?= $eventoFinanceiro['nome']; ?></td>
        <td><?php if($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['parcelas']) echo $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['parcelas']; else echo 0; ?></td>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'] < 0) { echo " color:#900;"; } elseif($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'] > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'], 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'] < 0) { echo " color:#900;"; } elseif($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'] > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'], 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($previsaoReal < 0) { echo " color:#900;"; } elseif($previsaoReal > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($previsaoReal, 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
      </tr>
    <?php } ?>
  </thead>
</table>
<br />
<br />
<table border="1" id="sortTableExample">
  <thead>
    <tr>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['centro_custo']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['contas']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['total']; ?></div></td>
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?= $idioma['porcentagem']; ?></div></td>
    </tr>
  </thead>
  <tbody>  
    <?php
	foreach($centrosDeCustos as $centroDeCusto) { 
	  $porcentagem = ($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'] * 100 ) / $numerosCentrosDeCustos['total'];
	  ?>
      <tr>
        <td><?= $centroDeCusto['nome']; ?></td>
        <td><?php if($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['contas']) echo $numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['contas']; else echo 0; ?></td>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td style="color:#999">R$</td>
              <td align="right" style="text-align:right;<?php if($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'] < 0) { echo " color:#900;"; } elseif($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'] > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'], 2, ',', '.'); ?></td>
            </tr>
          </table>
        </td>
        <td style="text-align:right;"><?= number_format($porcentagem, 2, ',', '.'); ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td style="text-align:right;" colspan="2"><?= $idioma['total'].':'; ?></td>
      <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td style="color:#999">R$</td>
            <td style="text-align:right;<?php if($numerosCentrosDeCustos['total'] < 0) { echo " color:#900;"; } elseif($numerosCentrosDeCustos['total'] > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($numerosCentrosDeCustos['total'], 2, ',', '.'); ?></td>
          </tr>
        </table>
      </td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
<br />
<br />
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
</body>
</html>