<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
<style type="text/css">

.tituloEdicao {
  font-size:45px;
}
legend {
  line-height:25px;
  margin-bottom: 5px;
  margin-top: 20px;
}
.botao {
  height:100px;
  margin-top: 15px;
  padding-bottom:0px;
  float:left;
  padding-top:40px;
  height:58px;
  text-transform:uppercase;
}
legend {
  background-color: #F4F4f4;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  padding: 5px 5px 5px 15px;
  width: 98%;
}


legend span {
  font-size: 9px;
  float: right;
  margin-right: 15px;
  color: #999;
}

</style>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:20px">

        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
        </div>
 

		<table border="0" cellspacing="0" cellpadding="15">
		  <tr>
			<td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
			<td style="padding: 0px 0px 0px 8px;" valign="top">        <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
				  <br />
				  <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
				</h2></td>
		  </tr>
		</table>
		
		<? incluirTela("administrar.menu",$config,$matricula); ?>
		
        <div class="row-fluid">
        
          <div class="span12">

            <?php if($mensagem["erro"]) { ?>
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
            
            <section id="financeiromatricula">

				<legend><?= $idioma["label_financeiro_massa"]; ?></legend>
			
			
        <form method="post" onsubmit="return validateFields(this, regras_financeiro)" action="" target="_parent" class="form-horizontal" >

              <input name="acao" type="hidden" value="editar_pagamento_massa" />
                
              <?php foreach ($contas_editar as $key => $formapagamento_editar) { ?>
              <input name="idcontas[<?=$formapagamento_editar["idconta"];?>]" type="hidden" value="<?=$formapagamento_editar["idconta"];?>" />
                <table cellpadding="5" cellspacing="0" class="table table-bordered  tabelaSemTamanho">
                     <tr>
                      <td bgcolor="#F4F4F4"><h3>#<?=$formapagamento_editar["idconta"];?></h3></td>
                     </tr>
                    <tr>
                    <td>
                  <div class="control-group">
                      <label for="form_nome"><strong>* Descrição:</strong></label>
                      <input class="span6" id="nome<?=$formapagamento_editar["idconta"];?>" name="nome[<?=$formapagamento_editar["idconta"];?>]" type="text" value="<? echo $formapagamento_editar['nome']; ?>">
                      <br />
                  </div>
                <div class="control-group" style="float:left; padding-right:10px;">
                            <label for="form_nome"><strong>Data de Pagamento:</strong></label>
                            <input class="span2 data_picker" id="data_pagamento<?=$formapagamento_editar["idconta"];?>" name="data_pagamento[<?=$formapagamento_editar["idconta"];?>]" type="text" value="<? echo formataData($formapagamento_editar['data_pagamento'],'pt',0); ?>">
                            <br />
                        </div>
                <div class="control-group">
                            <label for="form_nome"><strong>Documento:</strong></label>
                            <input class="span3" id="documento<?=$formapagamento_editar["idconta"];?>" name="documento[<?=$formapagamento_editar["idconta"];?>]" type="text" value="<? echo $formapagamento_editar['documento']; ?>">
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
                        <select name="idevento[<?=$formapagamento_editar["idconta"];?>]" id="idevento<?=$formapagamento_editar["idconta"];?>" style="width:auto;">
                          <option value=""><?= $idioma["selecione_idevento"]; ?></option>
                          <?php foreach($eventosFinanceiros as $eventoFinanceiro) { ?>
                            <option value="<?= $eventoFinanceiro['idevento']; ?>" <?php if($formapagamento_editar['idevento'] == $eventoFinanceiro['idevento']) { ?>selected="selected"<?php } ?>><?= $eventoFinanceiro['nome']; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td>
                        <select name="forma_pagamento[<?=$formapagamento_editar["idconta"];?>]" id="forma_pagamento<?=$formapagamento_editar["idconta"];?>" style="width:auto;" onchange="liberaCamposFinanceiro(this.options[this.selectedIndex].value,<?=$formapagamento_editar["idconta"];?>);">
                          <option value=""><?= $idioma["selecione_forma_pagamento"]; ?></option>
              <? foreach($GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                            <option value="<?= $ind; ?>" <?php if($formapagamento_editar['forma_pagamento'] == $ind) { ?>selected="selected"<?php } ?>><?= $val; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="vencimento[<?=$formapagamento_editar["idconta"];?>]" type="text" id="vencimento<?=$formapagamento_editar["idconta"];?>" maxlength="13" class="span2 data_picker" value="<? echo formataData($formapagamento_editar['data_vencimento'], "br", 0) ?>" /></td>
                      <td><input name="valor[<?=$formapagamento_editar["idconta"];?>]" type="text" id="valor<?=$formapagamento_editar["idconta"];?>" maxlength="13" class="span2 decimal_valor" value="<? echo number_format($formapagamento_editar["valor"], 2, ",", ".");?>"/></td>
                      <td><input name="parcela[<?=$formapagamento_editar["idconta"];?>]" type="text" id="parcela<?=$formapagamento_editar["idconta"];?>" maxlength="2" class="span1 apenasnumero" value="<?php echo $formapagamento_editar["parcela"]; ?>"/></td>
                      <td><input name="total_parcelas[<?=$formapagamento_editar["idconta"];?>]" type="text" id="total_parcelas<?=$formapagamento_editar["idconta"];?>" maxlength="2" class="span1 apenasnumero" value="<?php echo $formapagamento_editar["total_parcelas"]; ?>"/></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cartao<?=$formapagamento_editar["idconta"];?>" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;" >
                    <tr>
                      <td bgcolor="#F4F4F4" colspan="2" style="text-transform:uppercase;"><strong><?=$idioma["financeiro_informacoes_cartao"];?></strong></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_bandeira_cartao"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_autorizacao_cartao"];?></strong></td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idbandeira[<?=$formapagamento_editar["idconta"];?>]" id="idbandeira<?=$formapagamento_editar["idconta"];?>" style="width:auto;">
                          <option value=""><?= $idioma["selecione_bandeira_cartao"]; ?></option>
              <? foreach($bandeirasCartoes as $bandeiraCartao) { ?>
                            <option value="<?= $bandeiraCartao["idbandeira"]; ?>" <?php if($formapagamento_editar['idbandeira'] == $bandeiraCartao["idbandeira"]) { ?>selected="selected"<?php } ?>><?= $bandeiraCartao["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="autorizacao_cartao[<?=$formapagamento_editar["idconta"];?>]" type="text" id="autorizacao_cartao<?=$formapagamento_editar["idconta"];?>" maxlength="40" class="span2" value="<? echo $formapagamento_editar['autorizacao_cartao']; ?>" /></td>
                    </tr>
                  </table>
                  <table id="financeiro_informacoes_cheque<?=$formapagamento_editar["idconta"];?>" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;" >
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
                        <select name="idbanco[<?=$formapagamento_editar["idconta"];?>]" id="idbanco<?=$formapagamento_editar["idconta"];?>" class="span3">
                          <option value=""><?= $idioma["selecione_banco_cheque"]; ?></option>
              <? foreach($bancos as $banco) { ?>
                            <option value="<?= $banco["idbanco"] ?>" <?php if($formapagamento_editar['idbanco'] == $banco["idbanco"]) { ?>selected="selected"<?php } ?>><?= $banco["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input name="agencia_cheque[<?=$formapagamento_editar["idconta"];?>]" type="text" id="agencia_cheque<?=$formapagamento_editar["idconta"];?>" maxlength="20" class="span2" value="<? echo $formapagamento_editar['agencia_cheque']; ?>"/></td>
                      <td><input name="cc_cheque[<?=$formapagamento_editar["idconta"];?>]" type="text" id="cc_cheque<?=$formapagamento_editar["idconta"];?>" maxlength="20" class="span2" value="<? echo $formapagamento_editar['cc_cheque']; ?>" /></td>
                      <td><input name="numero_cheque[<?=$formapagamento_editar["idconta"];?>]" type="text" id="numero_cheque<?=$formapagamento_editar["idconta"];?>" maxlength="20" class="span2 apenasnumero" value="<? echo $formapagamento_editar['numero_cheque']; ?>"/></td>
                      <td><input name="emitente_cheque[<?=$formapagamento_editar["idconta"];?>]" type="text" id="emitente_cheque<?=$formapagamento_editar["idconta"];?>" maxlength="100" class="span2" value="<? echo $formapagamento_editar['emitente_cheque']; ?>" /></td>
                    </tr>
                  </table>
                   </td>
                   </tr>
                  </table>
                  <?php } ?>

                  <input class="btn" type="submit" value="<?=$idioma["btn_salvar"];?>" />
          </form> 

            </section>            

          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
</div>

<script>


  $(".data_picker").mask("99/99/9999");
  $(".data_picker").datepicker($.datepicker.regional["pt-BR"]);
  $(".apenasnumero").keypress(isNumber);
  $(".apenasnumero").blur(isNumberCopy);
  $(".decimal_valor").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});


  var regras_financeiro = new Array();
  <?php 
    foreach ($contas_editar as $key => $formapagamento_editar) { ?>
      regras_financeiro.push("required,idevento<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_idevento_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
      regras_financeiro.push("required,forma_pagamento<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_forma_pagamento_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
      regras_financeiro.push("required,vencimento<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_vencimento_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
      regras_financeiro.push("required,valor<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_valor_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
      regras_financeiro.push("required,parcela<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_qtd_parcelas_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
      regras_financeiro.push("required,total_parcelas<?php echo $formapagamento_editar['idconta']?>,<?=$idioma["financeiro_qtd_parcelas_vazio"].' da Conta #'.$formapagamento_editar['idconta'];?>");
 <?php }?> 
  

  function liberaCamposFinanceiro(valor,idconta) {
    var contemBandeiraCartao = false;
    var contemAutorizacaoCartao = false;
    var contemBancoCheque = false;
    var contemAgenciaCheque = false;
    var contemCcCheque = false;
    var contemNumeroCheque = false;
    var contemEmitenteCheque = false;
    $("#parcela"+idconta).attr('readonly', false);
    $("#total_parcelas"+idconta).attr('readonly', false);
    if (valor != -1) {
        if(valor == 2 || valor == 3) {
        if(valor == 3) {
          $("#parcela"+idconta).val(1);
          $("#parcela"+idconta).attr('readonly', true);
          $("#total_parcelas"+idconta).val(1);
          $("#total_parcelas"+idconta).attr('readonly', true);
        }
        $("#financeiro_informacoes_cheque"+idconta).hide("fast");
        $("#financeiro_informacoes_cartao"+idconta).show("fast");
        for (var i = 0; i < regras_financeiro.length; i++) {
          if(regras_financeiro[i] == "required,idbanco"+idconta+",<?= $idioma["banco_cheque_vazio"] ?>") {
          regras_financeiro.splice(i, 1);
          }
          if(regras_financeiro[i] == "required,agencia_cheque"+idconta+",<?= $idioma["agencia_cheque_vazio"] ?>") {
          regras_financeiro.splice(i, 1);
          }
          if(regras_financeiro[i] == "required,cc_cheque"+idconta+",<?= $idioma["cc_cheque_vazio"] ?>") {
          regras_financeiro.splice(i, 1);
          }
          if(regras_financeiro[i] == "required,numero_cheque"+idconta+",<?= $idioma["numero_cheque_vazio"] ?>") {
          regras_financeiro.splice(i, 1);
          }
          if(regras_financeiro[i] == "required,emitente_cheque"+idconta+",<?= $idioma["emitente_cheque_vazio"] ?>") {
          regras_financeiro.splice(i, 1);
          }
          if (regras_financeiro[i] == "required,idbandeira"+idconta+",<?= $idioma["bandeira_cartao_vazio"] ?>") {
          contemBandeiraCartao = true;
          }
          if (regras_financeiro[i] == "required,autorizacao_cartao"+idconta+",<?= $idioma["autorizacao_cartao_vazio"] ?>") {
          contemAutorizacaoCartao = true;
          }
        }
        if (!contemBandeiraCartao) {
          regras_financeiro.push("required,idbandeira"+idconta+",<?= $idioma["bandeira_cartao_vazio"] ?>");
        }
        if (!contemAutorizacaoCartao) {
          regras_financeiro.push("required,autorizacao_cartao"+idconta+",<?= $idioma["autorizacao_cartao_vazio"] ?>");
        }
        } else {
        if(valor == 4) {
          $("#financeiro_informacoes_cartao"+idconta).hide("fast");
          $("#financeiro_informacoes_cheque"+idconta).show("fast");
          for (var i = 0; i < regras_financeiro.length; i++) {
          if(regras_financeiro[i] == "required,idbandeira"+idconta+",<?= $idioma["bandeira_cartao_vazio"] ?>") {
            regras_financeiro.splice(i, 1);
          }
          if(regras_financeiro[i] == "required,autorizacao_cartao"+idconta+",<?= $idioma["autorizacao_cartao_vazio"] ?>") {
            regras_financeiro.splice(i, 1);
          }
          if (regras_financeiro[i] == "required,idbanco"+idconta+",<?= $idioma["banco_cheque_vazio"] ?>") {
            contemBancoCheque = true;
          }
          if (regras_financeiro[i] == "required,agencia_cheque"+idconta+",<?= $idioma["agencia_cheque_vazio"] ?>") {
            contemAgenciaCheque = true;
          }
          if (regras_financeiro[i] == "required,cc_cheque"+idconta+",<?= $idioma["cc_cheque_vazio"] ?>") {
            contemCcCheque = true;
          }
          if (regras_financeiro[i] == "required,numero_cheque"+idconta+",<?= $idioma["numero_cheque_vazio"] ?>") {
            contemNumeroCheque = true;
          }
          if (regras_financeiro[i] == "required,emitente_cheque"+idconta+",<?= $idioma["emitente_cheque_vazio"] ?>") {
            contemEmitenteCheque = true;
          }
          }
          if (!contemBancoCheque) {
          regras_financeiro.push("required,idbanco"+idconta+",<?= $idioma["banco_cheque_vazio"] ?>");
          }
          if (!contemAgenciaCheque) {
          regras_financeiro.push("required,agencia_cheque"+idconta+",<?= $idioma["agencia_cheque_vazio"] ?>");
          }
          if (!contemCcCheque) {
          regras_financeiro.push("required,cc_cheque"+idconta+",<?= $idioma["cc_cheque_vazio"] ?>");
          }
          if (!contemNumeroCheque) {
          regras_financeiro.push("required,numero_cheque"+idconta+",<?= $idioma["numero_cheque_vazio"] ?>");
          }
          if (!contemEmitenteCheque) {
          regras_financeiro.push("required,emitente_cheque"+idconta+",<?= $idioma["emitente_cheque_vazio"] ?>");
          }
        } else {
          if(valor == 5) {
            $("#parcela"+idconta).val(1);
            $("#parcela"+idconta).attr('readonly', true);
            $("#total_parcelas"+idconta).val(1);
            $("#total_parcelas"+idconta).attr('readonly', true);
          }
          $("#financeiro_informacoes_cartao"+idconta).hide("fast");
          $("#financeiro_informacoes_cheque"+idconta).hide("fast");
          for (var i = 0; i < regras_financeiro.length; i++) {
            if(regras_financeiro[i] == "required,idbandeira"+idconta+",<?= $idioma["bandeira_cartao_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,autorizacao_cartao"+idconta+",<?= $idioma["autorizacao_cartao_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,idbanco"+idconta+",<?= $idioma["banco_cheque_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,agencia_cheque"+idconta+",<?= $idioma["agencia_cheque_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,cc_cheque"+idconta+",<?= $idioma["cc_cheque_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,numero_cheque"+idconta+",<?= $idioma["numero_cheque_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
            if(regras_financeiro[i] == "required,emitente_cheque"+idconta+",<?= $idioma["emitente_cheque_vazio"] ?>") {
              regras_financeiro.splice(i, 1);
            }
          }
        }
      }
    }
  }

  function calcularParcelas(idconta) {
    var valor = document.getElementById("valor"+idconta).value;
    valor = valor.replace(".","");
    valor = valor.replace(".","");
    valor = valor.replace(",",".");
    valor = parseFloat(valor);
    var quantidade = document.getElementById('quantidade_parcelas'+idconta).value;
    if(valor && quantidade) {
    valorParcela = number_format(parseFloat(valor/quantidade), 2, ',', '.');
    document.getElementById('valor_parcela'+idconta).value = valorParcela;
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
  
$("#vencimento").change(function() {
if($("#vencimento").val() != '') {
  valordata = $("#vencimento").val();
  date= valordata;
  ardt= new Array;
  ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
  ardt=date.split("/");
  erro=false;
  if ( date.search(ExpReg)==-1){
  erro = true;
  } else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
  erro = true;
  else if ( ardt[1]==2) {
  if ((ardt[0]>28)&&((ardt[2]%4)!=0))
	erro = true;
  if ((ardt[0]>29)&&((ardt[2]%4)==0))
	erro = true;
  }
  if (erro) {
  alert("\""+valordata+"\" <?php echo $idioma["financeiro_primeiro_vencimento_invalido"]; ?>");
  $('#vencimento').focus();
  $("#vencimento").val('');
  return false;
  }
  return true;
}
});

$(".data_picker").datepicker({
    currentText: 'Now'/*,
    minDate:'Now'*/
    });

 
  $(document).ready(function(){
    <?php 
    foreach ($contas_editar as $key => $formapagamento_editar) { 
        if($formapagamento_editar['forma_pagamento']) {?>
            liberaCamposFinanceiro(<?php echo $formapagamento_editar['forma_pagamento']?>,<?php echo $formapagamento_editar['idconta']?>);
       <?php }
      }?>
  });

</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>