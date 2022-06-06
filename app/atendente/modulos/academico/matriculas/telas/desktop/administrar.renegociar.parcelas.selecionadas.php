<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
  margin:20px;
}
body {
	min-width: 500px;
}
.container-fluid {
	min-width: 500px;
}
</style>
</head>
<body>
<div class="row-fluid" >
  <div class="span12">
  	 <legend><?=$idioma["label_titulo"]; ?></legend>
			
			<? if($mensagem["erro"]) { ?>
              <div class="alert alert-error"> 
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?= $idioma[$mensagem["erro"]]; ?>
              </div>
			  <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
            <? } ?>
            <? if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in"> 
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
				<strong><?= $idioma[$_POST["msg"]]; ?></strong> 
              </div>
			  <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <? } ?>
			
			<?php 
			if($salvar['sucesso']) { ?>
				<div class="alert alert-success fade in"> 
					<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
					<strong><?= 'Parcelas renegociadas com sucesso!'; ?></strong> 
                </div>
				<br /><br />
				<a class="btn btn-large btn-primary" style="color:#FFFFFF" target="_parent" onclick="javascript:window.opener.location.href='/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>';window.close();" >Clique aqui para voltar à matrícula</a>
				<br />				
			<?php exit; } ?>
	 
            <?php 
			if(!$_POST['parcelas_selecionadas']){
				$matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/renegociar_parcelas/".$url[8]);
				$matriculaObj->Processando();
			}
			?>
            
            <form method="post" action="" style="padding-top:15px;" onsubmit="return validateFields(this, regras_financeiro)">
                <input name="acao" type="hidden" value="renegociar_parcelas_salvar" />
				
                <? if(count($matricula_contas) > 0) { ?>
				
				<? foreach($matricula_contas as $contas) {
					unset($valor_total);
				?>
                  <h4><?= $contas[0]["evento"]; ?></h4>
                  <br />                  
				  <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_id"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_vencimento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_situacao"];?></strong></td>
                    </tr>
                    <? 
                    foreach($contas as $conta) {					  
                      ?>
                      <tr>
                        <td>
							<input type="hidden" name="parcelas_renegociadas[<?php echo $conta["idconta"]; ?>]" />
							<?php echo $conta["idconta"]; ?>
						</td>
                        <td><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?></td>
                        <td>
							<span style="color:#999">R$</span> 
							
								<?php
									if($conta['valor_matricula']) {
										$valor_total += $valor_parcela;
										$valor_parcela = ($conta["valor_matricula"]/$conta['total_contas_compartilhadas']);
										echo '<strong>'.number_format($valor_parcela, 2, ",", ".").'</strong> <span style="color:#999"> / '. number_format($conta["valor"], 2, ",", ".").'</span>';
									} else {
										$valor_total += $conta["valor"];
										echo '<strong>'.number_format($conta["valor"], 2, ",", ".").'</strong>';
									} 
								?>
							
						</td>
                        <td><?php echo formataData($conta["data_vencimento"],'br',0); ?></td>
                        <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                      </tr>
                      <?php } ?>                    
                  	
					  <tr>
						  <td colspan="2"></td>
						  <td><span style="color:#999">R$</span> <strong><?=number_format($valor_total, 2, ",", ".");?></strong></td>
						  <td colspan="2"></td>
                      </tr>
					
					</table><br />
                <?php } ?>
			  
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <?php/*<td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_idevento"];?></strong></td>*/?>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_primeiro_vencimento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_quantidade_parcelas"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor_parcela"];?></strong></td>
                    </tr>
                    <tr>
                      <?php/*<td>
                        <select name="idevento" id="idevento" style="width:auto;" disabled="disabled">
                          <option value=""><?= $idioma["selecione_idevento"]; ?></option>
						  <?php foreach($eventosFinanceiros as $eventoFinanceiro) { ?>
                            <option value="<?= $eventoFinanceiro['idevento']; ?>" <?php if($eventoFinanceiro['mensalidade'] == 'S') echo 'selected="selected"'; ?> ><?= $eventoFinanceiro['nome']; ?></option>
                          <? } ?>
                        </select>
                      </td>*/?>
                      <td>
                        <select name="forma_pagamento" id="forma_pagamento" style="width:auto;" onchange="liberaCamposFinanceiro(this.options[this.selectedIndex].value);">
                          <option value=""><?= $idioma["selecione_forma_pagamento"]; ?></option>
						  <? foreach($GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                            <option value="<?= $ind; ?>"><?= $val; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="vencimento" type="text" id="vencimento" maxlength="13" class="span2" /></td>
                      <td><input name="quantidade_parcelas" type="text" id="quantidade_parcelas" maxlength="3" class="span1" value="1" onkeyup="calcularParcelas()" /></td>
                      <td><input name="valor" type="text" id="valor" maxlength="13" class="span2" onkeyup="calcularParcelas()" /></td>
                      <td><input name="valor_parcela" type="text" id="valor_parcela" maxlength="13" class="span2" disabled="disabled" /></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cartao" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;">
                    <tr>
                      <td bgcolor="#F4F4F4" colspan="2" style="text-transform:uppercase;"><strong><?=$idioma["financeiro_informacoes_cartao"];?></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_bandeira_cartao"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_autorizacao_cartao"];?></strong></td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idbandeira" id="idbandeira" style="width:auto;">
                          <option value=""><?= $idioma["selecione_bandeira_cartao"]; ?></option>
						  <? foreach($bandeirasCartoes as $bandeiraCartao) { ?>
                            <option value="<?= $bandeiraCartao["idbandeira"]; ?>"><?= $bandeiraCartao["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="autorizacao_cartao" type="text" id="autorizacao_cartao" maxlength="40" class="span2" /></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cheque" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;">
                    <tr>
                      <td bgcolor="#F4F4F4" colspan="5" style="text-transform:uppercase;"><strong><?=$idioma["financeiro_informacoes_cheque"];?></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_banco_cheque"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_agencia_cheque"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_cc_cheque"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_numero_cheque"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_emitente_cheque"];?></strong></td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idbanco" id="idbanco" style="width:auto;">
                          <option value=""><?= $idioma["selecione_banco_cheque"]; ?></option>
						  <? foreach($bancos as $banco) { ?>
                            <option value="<?= $banco["idbanco"] ?>"><?= $banco["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="agencia_cheque" type="text" id="agencia_cheque" maxlength="20" class="span2" /></td>
                      <td><input name="cc_cheque" type="text" id="cc_cheque" maxlength="20" class="span2" /></td>
                      <td><input name="numero_cheque" type="text" id="numero_cheque" maxlength="20" class="span2" /></td>
                      <td><input name="emitente_cheque" type="text" id="emitente_chequ" maxlength="100" class="span2" /></td>
                    </tr>
                  </table>
                  <input class="btn" type="submit" value="<?=$idioma["btn_renegociar"];?>" />
            </form>
			  
			  
			  <?php } else { ?>
				  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["sem_parcelas_selecionadas"]; ?></strong>
                  </div>
			  <?php } ?>
      </div>
  </div>
</div>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>

<script src="/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-modal.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-scrollspy.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-popover.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-carousel.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-typeahead.js"></script>
<script src="/assets/js/mousetrap.min.js"></script>

<script src="/assets/js/construtor.js"></script>

<script src="/assets/plugins/portamento/portamento-min.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script> 
<script>

$("#vencimento").mask("99/99/9999");
$("#vencimento").datepicker($.datepicker.regional["pt-BR"]);
$("#quantidade_parcelas").keypress(isNumber);
$("#quantidade_parcelas").blur(isNumberCopy);
$("#valor").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});

var regras_financeiro = new Array();
//regras_financeiro.push("required,idevento,<?=$idioma["financeiro_idevento_vazio"];?>");
regras_financeiro.push("required,forma_pagamento,<?=$idioma["financeiro_forma_pagamento_vazio"];?>");
regras_financeiro.push("required,vencimento,<?=$idioma["financeiro_vencimento_vazio"];?>");
regras_financeiro.push("required,quantidade_parcelas,<?=$idioma["financeiro_parcelas_vazio"];?>");
regras_financeiro.push("required,valor,<?=$idioma["financeiro_valor_vazio"];?>");

	function liberaCamposFinanceiro(valor) {	 
	  var contemBandeiraCartao = false;
	  var contemAutorizacaoCartao = false;
	  var contemBancoCheque = false;
	  var contemAgenciaCheque = false;
	  var contemCcCheque = false;
	  var contemNumeroCheque = false;
	  var contemEmitenteCheque = false;
	  $("#quantidade_parcelas").attr('readonly', false);
    if (valor != -1) {
    	  if(valor == 2 || valor == 3) {
    		if(valor == 3) {
    		  $("#quantidade_parcelas").val(1);
    		  $("#quantidade_parcelas").attr('readonly', true);
    		}
    		$("#financeiro_informacoes_cheque").hide("fast");
    		$("#financeiro_informacoes_cartao").show("fast");
    		for (var i = 0; i < regras_financeiro.length; i++) { 
    		  if(regras_financeiro[i] == "required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>") {
    			regras_financeiro.splice(i, 1);
    		  }
    		  if(regras_financeiro[i] == "required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
    			regras_financeiro.splice(i, 1);
    		  }
    		  if(regras_financeiro[i] == "required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
    			regras_financeiro.splice(i, 1);
    		  }
    		  if(regras_financeiro[i] == "required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
    			regras_financeiro.splice(i, 1);
    		  }
    		  if(regras_financeiro[i] == "required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
    			regras_financeiro.splice(i, 1);
    		  }
    		  if (regras_financeiro[i] == "required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>") {
    			contemBandeiraCartao = true;
    		  }
    		  if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
    			contemAutorizacaoCartao = true;
    		  }
    		}
    		if (!contemBandeiraCartao) {
    		  regras_financeiro.push("required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>"); 					
    		}
    		if (!contemAutorizacaoCartao) {
    		  regras_financeiro.push("required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>"); 					
    		}
    	  } else {
    		if(valor == 4) {
    		  $("#financeiro_informacoes_cartao").hide("fast");
    		  $("#financeiro_informacoes_cheque").show("fast");
    		  for (var i = 0; i < regras_financeiro.length; i++) { 
    			if(regras_financeiro[i] == "required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>") {
    			  regras_financeiro.splice(i, 1);
    			}
    			if(regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
    			  regras_financeiro.splice(i, 1);
    			}
    			if (regras_financeiro[i] == "required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>") {
    			  contemBancoCheque = true;
    			}
    			if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
    			  contemAgenciaCheque = true;
    			}
    			if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
    			  contemCcCheque = true;
    			}
    			if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
    			  contemNumeroCheque = true;
    			}
    			if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
    			  contemEmitenteCheque = true;
    			}
    		  }
    		  if (!contemBancoCheque) {
    			regras_financeiro.push("required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>"); 					
    		  }
    		  if (!contemAgenciaCheque) {
    			regras_financeiro.push("required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>"); 					
    		  }
    		  if (!contemCcCheque) {
    			regras_financeiro.push("required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>"); 					
    		  }
    		  if (!contemNumeroCheque) {
    			regras_financeiro.push("required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>"); 					
    		  }
    		  if (!contemEmitenteCheque) {
    			regras_financeiro.push("required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>"); 					
    		  }
    		} else {
    		  if(valor == 5) {
      			$("#quantidade_parcelas").val(1);
      			$("#quantidade_parcelas").attr('readonly', true);
    		  }
    			$("#financeiro_informacoes_cartao").hide("fast");
    			$("#financeiro_informacoes_cheque").hide("fast");
    			for (var i = 0; i < regras_financeiro.length; i++) { 
    			  if(regras_financeiro[i] == "required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    			  if(regras_financeiro[i] == "required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
    				  regras_financeiro.splice(i, 1);
    			  }
    		  }
    		}
    	}
    }
	}
	
	function calcularParcelas() {
	  var valor = document.getElementById('valor').value;
	  valor = valor.replace(".","");
	  valor = valor.replace(".","");
	  valor = valor.replace(",",".");
	  valor = parseFloat(valor);
	  var quantidade = document.getElementById('quantidade_parcelas').value;
	  if(valor && quantidade) {
		valorParcela = number_format(parseFloat(valor/quantidade), 2, ',', '.');
		document.getElementById('valor_parcela').value = valorParcela;
	  }
	}
	
	
	function number_format( number, decimals, dec_point, thousands_sep ) {
	  // %     nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
	  // *     exemplo 1: number_format(1234.56);
	  // *     retorno 1: '1,235'
	  // *     exemplo 2: number_format(1234.56, 2, ',', ' ');
	  // *     retorno 2: '1 234,56'
	  // *     exemplo 3: number_format(1234.5678, 2, '.', '');
	  // *     retorno 3: '1234.57'
	  // *     exemplo 4: number_format(67, 2, ',', '.');
	  // *     retorno 4: '67,00'
	  // *     exemplo 5: number_format(1000);
	  // *     retorno 5: '1,000'
	  // *     exemplo 6: number_format(67.311, 2);
	  // *     retorno 6: '67.31'
   
	  var n = number, prec = decimals;
	  n = !isFinite(+n) ? 0 : +n;
	  prec = !isFinite(+prec) ? 0 : Math.abs(prec);
	  var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
	  var dec = (typeof dec_point == "undefined") ? '.' : dec_point;
   
	  var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
   
	  var abs = Math.abs(n).toFixed(prec);
	  var _, i;
   
	  if (abs >= 1000) {
		  _ = abs.split(/\D/);
		  i = _[0].length % 3 || 3;
   
		  _[0] = s.slice(0,i + (n < 0)) +
				_[0].slice(i).replace(/(\d{3})/g, sep+'$1');
   
		  s = _.join(dec);
	  } else {
		  s = s.replace('.', dec);
	  }
   
	  return s;
	}
</script>

</body>
</html>