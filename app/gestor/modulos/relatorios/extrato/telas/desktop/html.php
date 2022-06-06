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
  regras.push("required,nome,<?php echo $idioma['nome_obrigatorio']; ?>"); 
</script> 
</head> 
<body> 
<table width="100%" border="0" cellpadding="10" cellspacing="0"> 
  <tr> 
    <td height="80"> 
      <table border="0" cellspacing="0" cellpadding="8"> 
        <tr> 
          <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td> 
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
          <td> 
            <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?php echo $_SERVER["QUERY_STRING"]; ?>">   
              <input class="btn" type="submit" value="<?php echo $idioma['baixar_planilha'] ?>" /> 
            </form> 
          </td> 
        </tr> 
      </table> 
    </td> 
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
<table width="100%" border="1" id="sortTableExample"> 
  <thead> 
    <tr> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['banco']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['agencia']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['conta_corrente']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['data_vencimento']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['data_pagamento']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['forma_pagamento']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['valor']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['valor_desconto']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['valor_pago']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['documento']; ?></div></td> 
      <td bgcolor="#F4F4F4" class="headerSortReloca"><div class='headerNew'><?php echo $idioma['descricao']; ?></div></td> 
    </tr> 
  </thead> 
  <tbody>   
    <? if(count($dadosArray) == 0){ ?>     
      <tr> 
        <td colspan="34">Nenhum informação foi encontrada.</td> 
      </tr> 
    <? 
    } else { 
      
      $totalValor = 0; 
      $totalDesconto = 0; 
      $totalPago = 0; 
      $totalDinheiro = 0; 
      $totalBoleto = 0; 
      $totalCheque = 0; 
      $totalCartaoCredito = 0; 
      $totalCartaoDebito = 0; 
      $totalOutros = 0; 
        
      foreach($dadosArray as $i => $linha){       
          
        //$valor = ($linha["valor"] + $linha["valor_juros"] + $linha["valor_multa"] + $linha["valor_outro"]) - $linha["valor_desconto"]; 
        $totalValor += $linha['valor']; 
        $totalDesconto += $linha['valor_desconto']; 
        $totalPago += $linha['valor_pago']; 
          
        $color = '000'; 
        if($linha['valor'] < 0) { 
          $color = '900'; 
        } elseif($linha['valor'] > 0) { 
          $color = '037203'; 
        }

        $colorDesconto = '000'; 
        if($linha['valor_desconto'] > 0) { 
          $colorDesconto = '900'; 
        } 

        $colorPago = '000'; 
        if($linha['valor_pago'] < 0) { 
          $colorPago = '900'; 
        } elseif($linha['valor_pago'] > 0) { 
          $colorPago = '037203'; 
        }  
          
        switch($linha['forma_pagamento']) { 
          case 1: 
            $totalBoleto += $linha['valor_pago']; 
          break; 
          case 2: 
            $totalCartaoCredito += $linha['valor_pago']; 
          break; 
          case 3: 
            $totalCartaoDebito += $linha['valor_pago']; 
          break; 
          case 4: 
            $totalCheque += $linha['valor_pago']; 
          break; 
          case 5: 
            $totalDinheiro += $linha['valor_pago']; 
          break; 
          default: 
            $totalOutros += $linha['valor_pago']; 
          break; 
        } 
          
        $linha["data_vencimento"] = formataData($linha["data_vencimento"],"br",0); 
          
        $linha["data_pagamento"] = formataData($linha["data_pagamento"],"br",0);     
          
        $linha["forma_pagamento"] = $forma_pagamento_conta[$config["idioma_padrao"]][$linha["forma_pagamento"]];     
        ?>   
        <tr> 
          <td><?= $linha['banco']; ?></td> 
          <td><?= $linha['agencia']; ?></td> 
          <td><?= $linha['conta']; ?></td> 
          <td><?= $linha['data_vencimento']; ?></td> 
          <td><?= $linha['data_pagamento']; ?></td> 
          <td><?= $linha['forma_pagamento']; ?></td> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
              <tr> 
                <td style="color:#999">R$</td> 
                <td align="right" style="text-align:right;color:#<?php echo $color; ?>"><?php echo number_format($linha['valor'], 2, ',', '.'); ?></td> 
              </tr> 
            </table> 
          </td>
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
              <tr> 
                <td style="color:#999">R$</td> 
                <td align="right" style="text-align:right;color:#<?php echo $colorDesconto; ?>"><?php echo number_format($linha['valor_desconto'], 2, ',', '.'); ?></td> 
              </tr> 
            </table> 
          </td>
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
              <tr> 
                <td style="color:#999">R$</td> 
                <td align="right" style="text-align:right;color:#<?php echo $colorPago; ?>"><?php echo number_format($linha['valor_pago'], 2, ',', '.'); ?></td> 
              </tr> 
            </table> 
          </td> 
          <td><?= $linha['documento']; ?></td> 
          <td><?= $linha['nome']; ?></td> 
        </tr> 
      <?php } ?>  
      <tr> 
        <td colspan="5" style="border-bottom:hidden; border-left:hidden;">&nbsp;</td> 
        <td style="text-align:right"><?= $idioma['total']; ?>:</td> 
        <td> 
          <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
            <tr> 
              <td style="color:#999">R$</td> 
              <td align="right" style="text-align:right;color:#<?php if($totalValor > 0) { echo '037203'; } elseif($totalValor < 0) { echo '900'; } else { echo '000'; }; ?>"><?php echo number_format($totalValor, 2, ',', '.'); ?></td> 
            </tr> 
          </table> 
        </td>
        <td> 
          <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
            <tr> 
              <td style="color:#999">R$</td> 
              <td align="right" style="text-align:right;color:#<?php if($totalDesconto < 0) { echo '037203'; } elseif($totalDesconto > 0) { echo '900'; } else { echo '000'; }; ?>"><?php echo number_format($totalDesconto, 2, ',', '.'); ?></td> 
            </tr> 
          </table> 
        </td>
        <td> 
          <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
            <tr> 
              <td style="color:#999">R$</td> 
              <td align="right" style="text-align:right;color:#<?php if($totalPago > 0) { echo '037203'; } elseif($totalPago < 0) { echo '900'; } else { echo '000'; }; ?>"><?php echo number_format($totalPago, 2, ',', '.'); ?></td> 
            </tr> 
          </table> 
        </td> 
        <td colspan="2" style="border-bottom:hidden; border-right:hidden;">&nbsp;</td> 
      </tr> 
    <?php } ?> 
  </tbody> 
</table> 
<br /> 
<br /> 
<table border="1" id="sortTableExample"> 
  <thead> 
    <tr> 
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?php echo $idioma['forma_pagamento']; ?></div></td> 
      <td bgcolor="#F4F4F4" class=" headerSortReloca"><div class='headerNew'><?php echo $idioma['total']; ?></div></td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['dinheiro']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalDinheiro < 0) { echo " color:#900;"; } elseif($totalDinheiro > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalDinheiro, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['boleto']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalBoleto < 0) { echo " color:#900;"; } elseif($totalBoleto > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalBoleto, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['cheque']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalCheque < 0) { echo " color:#900;"; } elseif($totalCheque > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalCheque, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['cartao_credito']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalCartaoCredito < 0) { echo " color:#900;"; } elseif($totalCartaoCredito > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalCartaoCredito, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['cartao_debito']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalCartaoDebito < 0) { echo " color:#900;"; } elseif($totalCartaoDebito > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalCartaoDebito, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td><?php echo $idioma['outros']; ?></td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalOutros < 0) { echo " color:#900;"; } elseif($totalOutros > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalOutros, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
    <tr> 
      <td style="text-align:right"><?php echo $idioma['total']; ?>:</td> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
          <tr> 
            <td style="color:#999">R$</td> 
            <td align="right" style="text-align:right;<? if($totalValor < 0) { echo " color:#900;"; } elseif($totalValor > 0) { echo " color:#037203;"; } else { echo " color:#000;"; } ?>"><?= number_format($totalValor, 2, ',', '.'); ?></td> 
          </tr> 
        </table> 
      </td> 
    </tr> 
  </thead> 
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