<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style>
    .invisivel {
        display: none !important;
    }
</style>
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>.hidden {
            margin-top: -75px;
        }</style>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
</head>
<body>
<? incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
<section id="global">
    <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
            <small><?= $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span>
        </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                class="divider">/</span></li>
        <? if ($url[3] == "cadastrar") { ?>
            <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
            <li class="active"><?php echo $linha["nome"]; ?></li>
        <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span12">
<div class="box-conteudo">
<div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i
            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
<?php if ($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
<div class="tabbable tabs-left">
<?php if ($url[3] != "cadastrar") {
    incluirTela("inc_menu_edicao", $config, $linha);
} ?>
<div class="tab-content">
<div class="tab-pane active" id="tab_editar">
<h2 class="tituloOpcao"><?php if ($url[3] == "cadastrar") {
        echo $idioma["titulo_opcao_cadastar"];
    } else if ($url[4] == "quitar") {
        echo $idioma["titulo_opcao_quitar"];
    } else {
        echo $idioma["titulo_opcao_editar"];
    } ?></h2>
<? if ($_POST["msg"]) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
    </div>
<? } ?>
<? if (count($salvar["erros"]) > 0) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma["form_erros"]; ?></strong>
        <? foreach ($salvar["erros"] as $ind => $val) { ?>
            <br/>
            <?php echo $idioma[$val]; ?>
        <? } ?>
    </div>
<? } ?>
<form action="#salvar_parcelas" method="post" onsubmit="return validateFields(this, regras_financeiro)"
      enctype="multipart/form-data">

    <input name="acao" type="hidden" value="salvar"/>
    <br/>

    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
            <?php /*<td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_idevento"];?></strong></td>*/ ?>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_forma_pagamento"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_primeiro_vencimento"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_quantidade_parcelas"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_valor"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_valor_parcela"]; ?></strong></td>
        </tr>
        <tr>
            <?php /*<td>
							<select name="idevento" id="idevento" style="width:auto;" disabled="disabled">
							  <option value=""><?= $idioma["selecione_idevento"]; ?></option>
							  <?php foreach($eventosFinanceiros as $eventoFinanceiro) { ?>
								<option value="<?= $eventoFinanceiro['idevento']; ?>" <?php if($eventoFinanceiro['mensalidade'] == 'S') echo 'selected="selected"'; ?> ><?= $eventoFinanceiro['nome']; ?></option>
							  <? } ?>
							</select>
						  </td>*/
            ?>
            <td>
                <select name="forma_pagamento_post" id="forma_pagamento_post" style="width:auto;"
                        onchange="liberaCamposFinanceiro(this.options[this.selectedIndex].value);">
                    <option value=""><?= $idioma["selecione_forma_pagamento"]; ?></option>
                    <? foreach ($GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                        <option
                            value="<?= $ind; ?>" <?php if ($_POST['forma_pagamento_post'] == $ind) echo 'selected="selected"'; ?> ><?= $val; ?></option>
                    <? } ?>
                </select>
            </td>
            <td><input name="vencimento" type="text" id="vencimento" maxlength="13" class="span2"
                       value="<?= $_POST['vencimento'] ?>"/></td>
            <td><input name="quantidade_parcelas" type="text" id="quantidade_parcelas" maxlength="3" class="span1"
                       value="<?= ($_POST['quantidade_parcelas']) ? $_POST['quantidade_parcelas'] : 1 ?>"
                       onkeyup="calcularParcelas()"/></td>
            <td><input name="valor" type="text" id="valor" maxlength="13" class="span2" onkeyup="calcularParcelas()"
                       value="<?= number_format($_POST['valor'], 2, ',', '.') ?>"/></td>
            <td><input name="valor_parcela" type="text" id="valor_parcela" maxlength="13" class="span2"
                       disabled="disabled"
                       value="<?= ($_POST['valor']) ? number_format(($_POST['valor'] / $_POST['quantidade_parcelas']), 2, ',', '.') : '0,00' ?>"/>
            </td>
        </tr>
    </table>

    <table id="financeiro_informacoes_cartao" cellpadding="5" cellspacing="0"
           class="table table-bordered table-condensed tabelaSemTamanho"
           style="<?php if ($_POST['forma_pagamento_post'] == 3 || $_POST['forma_pagamento_post'] == 2) { ?>display:inline-block;<?php } else { ?>display:none;<?php } ?>">
        <tr>
            <td bgcolor="#F4F4F4" colspan="2" style="text-transform:uppercase;">
                <strong><?= $idioma["financeiro_informacoes_cartao"]; ?></strong></td>
        </tr>
        <tr>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_bandeira_cartao"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_autorizacao_cartao"]; ?></strong></td>
        </tr>
        <tr>
            <td>
                <select name="idbandeira" id="idbandeira" style="width:auto;">
                    <option value=""><?= $idioma["selecione_bandeira_cartao"]; ?></option>
                    <? foreach ($bandeirasCartoes as $bandeiraCartao) { ?>
                        <option
                            value="<?= $bandeiraCartao["idbandeira"]; ?>" <?php if ($_POST['idbandeira'] == $bandeiraCartao["idbandeira"]) echo 'selected="selected"'; ?> ><?= $bandeiraCartao["nome"]; ?></option>
                    <? } ?>
                </select>
            </td>
            <td><input name="autorizacao_cartao" type="text" id="autorizacao_cartao" maxlength="40" class="span2"
                       value="<?= $_POST['autorizacao_cartao'] ?>"/></td>
        </tr>
    </table>

    <table id="financeiro_informacoes_cheque" cellpadding="5" cellspacing="0"
           class="table table-bordered table-condensed tabelaSemTamanho"
           style="<?php if ($_POST['forma_pagamento_post'] == 4) { ?>display:inline-block;<?php } else { ?>display:none;<?php } ?>">
        <tr>
            <td bgcolor="#F4F4F4" colspan="5" style="text-transform:uppercase;">
                <strong><?= $idioma["financeiro_informacoes_cheque"]; ?></strong></td>
        </tr>
        <tr>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_banco_cheque"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_agencia_cheque"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_cc_cheque"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_numero_cheque"]; ?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_emitente_cheque"]; ?></strong></td>
        </tr>
        <tr>
            <td>
                <select name="idbanco" id="idbanco" style="width:auto;">
                    <option value=""><?= $idioma["selecione_banco_cheque"]; ?></option>
                    <? foreach ($bancos as $banco) { ?>
                        <option
                            value="<?= $banco["idbanco"] ?>" <?php if ($_POST['idbanco'] == $banco["idbanco"]) echo 'selected="selected"'; ?> ><?= $banco["nome"]; ?></option>
                    <? } ?>
                </select>
            </td>
            <td><input name="agencia_cheque" type="text" id="agencia_cheque" maxlength="20" class="span2"
                       value="<?= $_POST['agencia_cheque'] ?>"/></td>
            <td><input name="cc_cheque" type="text" id="cc_cheque" maxlength="20" class="span2"
                       value="<?= $_POST['cc_cheque'] ?>"/></td>
            <td><input name="numero_cheque" type="text" id="numero_cheque" maxlength="20" class="span2"
                       value="<?= $_POST['numero_cheque'] ?>"/></td>
            <td><input name="emitente_cheque" type="text" id="emitente_chequ" maxlength="100" class="span2"
                       value="<?= $_POST['emitente_cheque'] ?>"/></td>
        </tr>
    </table>

    <br/>

    <p><strong><?= $idioma["form_sindicato"]; ?></strong></p>
    <select id="idsindicato" name="idsindicato">
        <option value=""><?php echo $idioma['escolha_sindicato']; ?></option>
        <?php foreach ($sindicatosArray as $ind => $sindicato) { ?>
            <option
                value="<?php echo $sindicato["idsindicato"]; ?>" <?php if ($sindicato["idsindicato"] == $_POST['idsindicato']) echo 'selected="selected"'; ?> ><?php echo $sindicato["mantenedora"] . ' - ' . $sindicato["nome_abreviado"]; ?></option>
        <?php } ?>
    </select>
    <br/><br/>

    <p><?= $idioma["form_associar"]; ?></p>
    <select id="matriculas" name="matriculas"></select>
    <br/><br/>


    <?php
    if ($_POST['matriculas']) {
        ?>
        <section id="salvar_valores">
            <legend>Valores para cada Matrícula</legend>
            <p class="help-block">Soma dos valores deve ser igual ao valor total do pagamento

            <h3>R$ <?= number_format(($_POST['valor']), 2, ',', '.') ?></h3> </p><br/>
            <table border="0" width="100%" class="table table-bordered table-condensed tabelaSemTamanho">
                <tr>
                    <th bgcolor="#F4F4F4">Matrícula</th>
                    <th bgcolor="#F4F4F4">Pessoa</th>
                    <th bgcolor="#F4F4F4">Documento</th>
                    <th bgcolor="#F4F4F4">Valor</th>
                </tr>

                <?php foreach ($matriculas as $linha_mat) { ?>
                    <tr>
                        <td><?php echo $linha_mat['idmatricula']; ?></td>
                        <td><?php echo $linha_mat['nome']; ?></td>
                        <td><?php echo $linha_mat['documento']; ?></td>
                        <td>
                            <input class="span2" type="text"
                                   id="matriculas_valor_<?php echo $linha_mat['idmatricula']; ?>"
                                   name="matriculas_array[<?= $linha_mat['idmatricula']; ?>][valor]" value=""/>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <input type="hidden" name="parcelas_definidas" value="1"/>
            <br/>
        </section>
    <?php
    } ?>


    <?php
    if ($_POST['quantidade_parcelas']) {

        $total = $_POST['quantidade_parcelas'];
        $valor_parcela = ($_POST['valor'] / $total);
        $valor_pago_parcela = ($_POST['valor_pago'] / $total);
        ?>
        <section id="salvar_parcelas">
            <legend>Parcelas</legend>
            <br/>
            <table border="0" width="100%" class="table table-bordered table-condensed tabelaSemTamanho">
                <tr>
                    <th bgcolor="#F4F4F4"></th>
                    <th bgcolor="#F4F4F4">Nome</th>
                    <th bgcolor="#F4F4F4">Vencimento</th>
                    <th bgcolor="#F4F4F4">Valor</th>
                    <?php /*<th bgcolor="#F4F4F4">Pagamento</th>
									<th bgcolor="#F4F4F4">Valor pago</th>*/
                    ?>
                </tr>
                <?php
                $vencimento = $_POST['vencimento'];

                $dia_v = substr($vencimento, -10, 2);
                $mes_v = substr($vencimento, -7, 2);
                $ano_v = substr($vencimento, -4, 4);
                ?>

                <?php for ($i = 1; $i <= $total; $i++) { ?>
                    <tr>
                        <td style="padding:10px;">
                            <?php echo $i; ?>
                            <input type="hidden" name="parcelas_array[<?= $i; ?>][numero]" value="<?php echo $i; ?>"/>
                        </td>
                        <td>
                            <input class="span3" type="text" name="parcelas_array[<?= $i; ?>][nome]"
                                   value="<?php echo 'Compartilhamento ' . $i . '/' . $total; ?>"/>
                        </td>
                        <td>
                            <input class="span2" type="text" id="parcelas_vencimento_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][vencimento]"
                                   value="<?php echo date("d/m/Y", mktime(date("H"), date("i"), date("s"), $mes_v + ($i - 1), $dia_v, $ano_v)); ?>"/>
                        </td>
                        <td>
                            <input class="span2" type="text" id="parcelas_valor_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor]"
                                   value="<?php if ($i == 1) echo number_format(($valor_parcela + $valor_resto_total), 2, ',', '.'); else echo number_format($valor_parcela, 2, ',', '.'); ?>"/>
                        </td>
                        <?php /*<td>
										<input class="span2" type="text" id="parcelas_pagamento_<?php echo $i; ?>" name="parcelas_array[<?= $i; ?>][data_pagamento]" value="<?php echo $_POST['data_pagamento']; ?>" />
									  </td>
									  <td>
										<input class="span2" type="text" id="parcelas_pago_<?php echo $i; ?>" name="parcelas_array[<?= $i; ?>][valor_pago]" value="<?php echo number_format($valor_pago_parcela,2,',','.'); ?>" />
									  </td>*/
                        ?>
                    </tr>
                <?php } ?>
            </table>
            <input type="hidden" name="parcelas_definidas" value="1"/>
        </section>
    <?php
    } ?>

    <div class="form-actions">
        <?php if ($url[3] == 'cadastrar' || $situacao_pago != $linha['idsituacao']) { ?>
            <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
            <input type="reset" class="btn"
                   onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"
                   value="<?= $idioma["btn_cancelar"]; ?>"/>
        <?php } else { ?>
            <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma["erro_conta_paga"]; ?></strong>
            </div>
        <?php } ?>
    </div>
</form>
</div>
</div>
</div>
</div>
</div>

</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>

<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#matriculas").fcbkcomplete({
            json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_matricula/" + document.getElementById('idsindicato').value,
            addontab: true,
            height: 10,
            maxshownitems: 10,
            cache: true,
            maxitems: 20,
            filter_selected: true,
            firstselected: true,
            input_min_size: 1,
            complete_text: "<?= $idioma["mensagem_select"]; ?>",
            addoncomma: true
        });

        <?php if(count($matriculas)) {
            foreach($matriculas as $mat) { ?>
        $("#matriculas").trigger("addItem", [
            {"title": "<?=$mat["idmatricula"].' : '.$mat["nome"].' - '.$mat["documento"]?>", "value": "<?=$mat["idmatricula"]?>"}
        ]);
        $("#<?= 'matriculas_valor_'.$mat["idmatricula"]; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
        <?php } } ?>

        $("#idsindicato").change(function () {
            $("#matriculas").trigger("destroy");
            $("#matriculas").fcbkcomplete({
                json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_matricula/" + document.getElementById('idsindicato').value,
                addontab: true,
                height: 10,
                maxshownitems: 10,
                cache: true,
                maxitems: 20,
                filter_selected: true,
                firstselected: true,
                input_min_size: 1,
                complete_text: "<?= $idioma["mensagem_select"]; ?>",
                addoncomma: true
            });
        });

    });

</script>

<script>

$("#vencimento").mask("99/99/9999");
$("#vencimento").datepicker($.datepicker.regional["pt-BR"]);
$("#quantidade_parcelas").keypress(isNumber);
$("#quantidade_parcelas").blur(isNumberCopy);
$("#valor").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});

var regras_financeiro = new Array();
regras_financeiro.push("required,forma_pagamento_post,<?=$idioma["financeiro_forma_pagamento_vazio"];?>");
regras_financeiro.push("required,vencimento,<?=$idioma["financeiro_vencimento_vazio"];?>");
regras_financeiro.push("required,quantidade_parcelas,<?=$idioma["financeiro_parcelas_vazio"];?>");
regras_financeiro.push("required,valor,<?=$idioma["financeiro_valor_vazio"];?>");
regras_financeiro.push("required,idsindicato,<?=$idioma["sindicato_obrigatoria"];?>");

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
        if (valor == 2 || valor == 3) {
            if (valor == 3) {
                $("#quantidade_parcelas").val(1);
                $("#quantidade_parcelas").attr('readonly', true);
            }
            $("#financeiro_informacoes_cheque").hide("fast");
            $("#financeiro_informacoes_cartao").show("fast");
            for (var i = 0; i < regras_financeiro.length; i++) {
                if (regras_financeiro[i] == "required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>") {
                    regras_financeiro.splice(i, 1);
                }
                if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
                    regras_financeiro.splice(i, 1);
                }
                if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
                    regras_financeiro.splice(i, 1);
                }
                if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
                    regras_financeiro.splice(i, 1);
                }
                if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
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
            if (valor == 4) {
                $("#financeiro_informacoes_cartao").hide("fast");
                $("#financeiro_informacoes_cheque").show("fast");
                for (var i = 0; i < regras_financeiro.length; i++) {
                    if (regras_financeiro[i] == "required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
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
                if (valor == 5) {
                    $("#quantidade_parcelas").val(1);
                    $("#quantidade_parcelas").attr('readonly', true);
                }
                $("#financeiro_informacoes_cartao").hide("fast");
                $("#financeiro_informacoes_cheque").hide("fast");
                for (var i = 0; i < regras_financeiro.length; i++) {
                    if (regras_financeiro[i] == "required,idbandeira,<?= $idioma["bandeira_cartao_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,idbanco,<?= $idioma["banco_cheque_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                }
            }
        }
    }
}

function calcularParcelas() {
    var valor = document.getElementById('valor').value;
    valor = valor.replace(".", "");
    valor = valor.replace(".", "");
    valor = valor.replace(",", ".");
    valor = parseFloat(valor);
    var quantidade = document.getElementById('quantidade_parcelas').value;
    if (valor && quantidade) {
        valorParcela = number_format(parseFloat(valor / quantidade), 2, ',', '.');
        document.getElementById('valor_parcela').value = valorParcela;
    }
}


function number_format(number, decimals, dec_point, thousands_sep) {
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

        _[0] = s.slice(0, i + (n < 0)) +
            _[0].slice(i).replace(/(\d{3})/g, sep + '$1');

        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    return s;
}

<?php for($i=1; $i <= $total; $i++) { ?>
$("#<?= 'parcelas_vencimento_'.$i; ?>").datepicker($.datepicker.regional["pt-BR"]);
$("#<?= 'parcelas_pagamento_'.$i; ?>").datepicker($.datepicker.regional["pt-BR"]);
$("#<?= 'parcelas_vencimento_'.$i; ?>").mask("99/99/9999");
$("#<?= 'parcelas_pagamento_'.$i; ?>").mask("99/99/9999");
$("#<?= 'parcelas_valor_'.$i; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
$("#<?= 'parcelas_pago_'.$i; ?>").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});
<?php } ?>
</script>

</div>
</body>
</html>