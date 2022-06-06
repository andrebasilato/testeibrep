<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
	background-color: #FFF !important;
	background-image:none;
	padding-top:0px !important;
}
body {
	min-width: 500px;
}
.container-fluid {
	min-width: 500px;
}
.status {
	cursor:pointer;  
	color:#FFF;
	font-size:9px;
	font-weight:bold;
	padding:5px;
	text-transform: uppercase;
	white-space: nowrap;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	margin-right:5px;
	line-height:30px;
}
.ativo {
	font-size:15px;		
}
.inativo {
	background-color:#838383;	
}
</style>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="row-fluid" >
    <div class="span12">
        <? if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">�</a>
                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
            </div>
		<? } ?>
		<? if(count($salvar["erros"]) > 0){ ?>
            <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">�</a>
                <strong><?= $idioma["form_erros"]; ?></strong>
				<? foreach($salvar["erros"] as $ind => $val) { ?>
                    <br />
					<?php echo $idioma[$val]; ?>
				<? } ?>
            </div>
		<? } ?>
        <section id="situacao_conta">
            <legend><?=$idioma['label_situacao_conta'];?></legend>
            <div id="divSituacoes" style="padding-top:15px; padding-bottom:15px; width:90%">
				<? foreach($situacaoWorkflow as $ind => $val){ ?>
                    <span id="<?=$ind;?>" <? ($ind == $formapagamento_editar['idsituacao']) ? print 'class="status ativo" style="background-color: #'.$val["cor_bg"].'"' : print 'class="status inativo"'; ?>
						<? if (in_array($ind, $situacaoWorkflowRelacionamento) && $formapagamento_editar["situacao"]["visualizacoes"][1]) { ?>onclick="modificarSituacao('<?=$ind;?>','<?=$val["nome"];?>');"<? } else { ?>data-original-title="<?=$idioma['indisponivel']; ?>" style="background-color:#CCC" rel="tooltip"<? } ?>>
						<?=$val["nome"];?>
                    </span>
				<? } ?>
            </div>                
			<?php if($formapagamento_editar["situacao"]["visualizacoes"][1]) { ?>    
				<script>
					function modificarSituacao(para,nome){
						var de = "<?= $formapagamento_editar["idsituacao"]; ?>";
						var msg = "<?=$idioma['confirma_altera_situacao_conta'];?>";
						msg = msg.replace("[[idconta]]", "<?=$formapagamento_editar["idconta"];?>");
						msg = msg.replace("[[nome]]", nome);
						var confirma = confirm(msg);
						if(confirma){
							document.getElementById('situacao_para').value = para;
							document.getElementById('form_situacao').submit();
						} else {
							return false;
						}
					}
                </script>
                <form method="post" id="form_situacao" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/financeiro#situacao" target="_parent">
                    <input name="idconta" type="hidden" value="<?php echo $formapagamento_editar['idconta'];?>" />
                    <input name="acao" type="hidden" value="alterar_situacao_conta" />  
                    <input name="situacao_para" id="situacao_para" type="hidden" value="" />    
                </form>     
			<?php } ?>             
        </section>
     <legend><?=$idioma["label_titulo_editar"]; ?></legend>
     <!--<form method="post" action="" style="padding-top:15px;" onsubmit="return validateFields(this, regras_financeiro)">-->
      <form method="post" onsubmit="return validateFields(this, regras_financeiro)" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/financeiro" target="_parent" class="form-horizontal" >
                  <input name="acao" type="hidden" value="editar_pagamento" />
                  <input name="idconta" type="hidden" value="<?php echo $formapagamento_editar['idconta'];?>" />
                  <div class="control-group">
                      <label for="form_nome"><strong>* Descrição:</strong></label>
                      <input class="span6" id="nome" name="nome" type="text" value="<? echo $formapagamento_editar['nome']; ?>">
                      <br />
                  </div>
				  <div class="control-group" style="float:left; padding-right:10px;">
                      <label for="form_nome"><strong>* Data de Pagamento:</strong></label>
                      <input class="span2" id="data_pagamento" name="data_pagamento" type="text" value="<? echo formataData($formapagamento_editar['data_pagamento'],'pt',0); ?>">
                      <br />
                  </div>
				  <div class="control-group">
                      <label for="form_nome"><strong>Documento:</strong></label>
                      <input class="span3" id="documento" name="documento" type="text" value="<? echo $formapagamento_editar['documento']; ?>">
                      <br />
                      <br />
                  </div>
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_idevento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_primeiro_vencimento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_parcela"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_quantidade_parcelas"];?></strong></td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idevento" id="idevento" style="width:auto;">
                          <option value=""><?= $idioma["selecione_idevento"]; ?></option>
                          <?php foreach($eventosFinanceiros as $eventoFinanceiro) { ?>
                            <option value="<?= $eventoFinanceiro['idevento']; ?>" <?php if($formapagamento_editar['idevento'] == $eventoFinanceiro['idevento']) { ?>selected="selected"<?php } ?>><?= $eventoFinanceiro['nome']; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td>
                        <select name="forma_pagamento" id="forma_pagamento" style="width:auto;" onchange="liberaCamposFinanceiro(this.options[this.selectedIndex].value);">
                          <option value=""><?= $idioma["selecione_forma_pagamento"]; ?></option>
              <? foreach($GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                            <option value="<?= $ind; ?>" <?php if($formapagamento_editar['forma_pagamento'] == $ind) { ?>selected="selected"<?php } ?>><?= $val; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="vencimento" type="text" id="vencimento" maxlength="13" class="span2" value="<? echo formataData($formapagamento_editar['data_vencimento'], "br", 0) ?>" /></td>
                      <td><input name="valor" type="text" id="valor" maxlength="13" class="span2" value="<? echo number_format($formapagamento_editar["valor"], 2, ",", ".");?>"/></td>
                      <td><input name="parcela" type="text" id="parcela" maxlength="2" class="span1" value="<?php echo $formapagamento_editar["parcela"]; ?>"/></td>
                      <td><input name="total_parcelas" type="text" id="total_parcelas" maxlength="2" class="span1" value="<?php echo $formapagamento_editar["total_parcelas"]; ?>"/></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cartao" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;" >
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
                            <option value="<?= $bandeiraCartao["idbandeira"]; ?>" <?php if($formapagamento_editar['idbandeira'] == $bandeiraCartao["idbandeira"]) { ?>selected="selected"<?php } ?>><?= $bandeiraCartao["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="autorizacao_cartao" type="text" id="autorizacao_cartao" maxlength="40" class="span2" value="<? echo $formapagamento_editar['autorizacao_cartao']; ?>" /></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cheque" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;" >
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
                        <select name="idbanco" id="idbanco" class="span3">
                          <option value=""><?= $idioma["selecione_banco_cheque"]; ?></option>
              <? foreach($bancos as $banco) { ?>
                            <option value="<?= $banco["idbanco"] ?>" <?php if($formapagamento_editar['idbanco'] == $banco["idbanco"]) { ?>selected="selected"<?php } ?>><?= $banco["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="agencia_cheque" type="text" id="agencia_cheque" maxlength="20" class="span2" value="<? echo $formapagamento_editar['agencia_cheque']; ?>"/></td>
                      <td><input name="cc_cheque" type="text" id="cc_cheque" maxlength="20" class="span2" value="<? echo $formapagamento_editar['cc_cheque']; ?>" /></td>
                      <td><input name="numero_cheque" type="text" id="numero_cheque" maxlength="20" class="span2" value="<? echo $formapagamento_editar['numero_cheque']; ?>"/></td>
                      <td><input name="emitente_cheque" type="text" id="emitente_chequ" maxlength="100" class="span2" value="<? echo $formapagamento_editar['emitente_cheque']; ?>" /></td>
                    </tr>
                  </table>
                  <input class="btn" type="submit" value="<?=$idioma["btn_salvar"];?>" />
      </form> 
      </div>
  </div>
</div>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>

<script src="/assets/js/construtor.js"></script>
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
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script> 
<script src="/assets/plugins/portamento/portamento-min.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/js/construtor.js"></script>
<script type="text/javascript">
  $("#vencimento").mask("99/99/9999");
  $("#vencimento").datepicker($.datepicker.regional["pt-BR"]);
  $("#valor").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
  $("#data_pagamento").mask("99/99/9999");
  $("#data_pagamento").datepicker($.datepicker.regional["pt-BR"]);

  var regras_financeiro = new Array();
  regras_financeiro.push("required,nome,<?=$idioma["financeiro_nome_vazio"];?>");
  regras_financeiro.push("required,idevento,<?=$idioma["financeiro_idevento_vazio"];?>");
  regras_financeiro.push("required,forma_pagamento,<?=$idioma["financeiro_forma_pagamento_vazio"];?>");
  regras_financeiro.push("required,vencimento,<?=$idioma["financeiro_vencimento_vazio"];?>");
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
        	//regras_financeiro.push("required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>");          
        }
        if (!contemAgenciaCheque) {
        	//regras_financeiro.push("required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>");           
        }
        if (!contemCcCheque) {
        	//regras_financeiro.push("required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>");           
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
  
  $(document).ready(function(){
    <?php 
      if($formapagamento_editar['forma_pagamento']) {?>
          tipoPagamento = <?php echo $formapagamento_editar['forma_pagamento']?>;
          liberaCamposFinanceiro(tipoPagamento);
     <?php }else{?>
          tipoPagamento = -1;
      <?php } ?>

  });
</script>
</body>
</html>