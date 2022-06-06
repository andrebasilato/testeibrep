<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
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

        .pagamento {
            color: #000000;
        }
    </style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma['pagina_titulo']; ?>&nbsp;<small><?= $idioma['pagina_subtitulo']; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma['nav_inicio']; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma['nav_modulo']; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/matriculas"><?= $idioma['pagina_titulo']; ?></a> <span class="divider">/</span></li>
            <li><?= $idioma['nav_matricula']; ?> #<?= $matricula['idmatricula']; ?> <span class="divider">/</span></li>
            <li class="active"><?= $idioma['nav_administrar']; ?></a></li>
            <span class="pull-right" style="color:#999"><?= $idioma['hora_servidor']; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo" style="padding:20px">
                <div class=" pull-right">
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma['btn_sair']; ?></a>
                </div>
                <table border="0" cellspacing="0" cellpadding="15">
                    <tr>
                        <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?= $matricula['pessoa']['avatar_servidor']; ?>" class="img-circle"></td>
                        <td style="padding: 0px 0px 0px 8px;" valign="top">
                            <h2 class="tituloEdicao"><?= $idioma['matricula']; ?> #<?= $matricula['idmatricula']; ?>
                                <br /><small style="text-transform:uppercase;">Aluno: <?= $matricula['pessoa']['nome']; ?></small>
                            </h2>
                        </td>
                    </tr>
                </table>
                <?php incluirTela("administrar.menu",$config,$matricula); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        if ($mensagem['erro']) {
                            ?>
                            <div class="alert alert-error">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <?= $idioma[$mensagem['erro']]; ?>
                            </div>
                            <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem['erro']]); ?>');</script>
                            <?php
                        }

                        if ($_POST['msg']) {
                            ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma[$_POST['msg']]; ?></strong>
                            </div>
                            <script>alert('<?= $idioma[$_POST['msg']]; ?>');</script>
                            <?php
                        }

                        if (count($matricula['contas']) > 0) {
                            foreach ($matricula['contas'] as $indEvento => $varEvento) {
                                foreach ($varEvento as $indConta => $varConta) {
                                    if ($varConta['situacao_pagseguro'] == 'S') {
                                        $dataReceber = (new \DateTime($varConta['historico_pagseguro']['data_cad']))
                                            ->modify('+28 days')
                                            ->format('d/m/Y');
                                        ?>
                                        <div class="alert alert-success fade in">
                                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                            <strong><?= sprintf($idioma['data_capturado_pagseguro'], $varConta['idconta'], $dataReceber) ?></strong>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                        <?php if($matricula['visualizacoes'][80]){ ?>
                        <section id="xmlmatricula">
                            <legend><?= $idioma["label_xml"]; ?></legend>
                            <form method="get" action="/gestor/financeiro/fechamento_caixa/xmlporperiodo"
                                  target="_blank" onsubmit="return validarCampos()">
                                <label class="control-label" for="form_tipo_periodo"><strong><?= $idioma["form_tipo_periodo"]; ?></strong></label>
                                <div class="controls">
                                    <select name="tipo_periodo" id="form_tipo_periodo" class="span3"
                                            onchange="verificaData(this, 'div_de', 'div_ate', 'periodo_inicio', 'periodo_final')">
                                        <option value="PER"><?= $idioma["periodo_PER"]; ?></option>
                                        <option value="HOJ"><?= $idioma["periodo_HOJ"]; ?></option>
                                        <option value="SET"><?= $idioma["periodo_SET"]; ?></option>
                                        <option value="MAT"><?= $idioma["periodo_MAT"]; ?></option>
                                        <option value="MPR"><?= $idioma["periodo_MPR"]; ?></option>
                                        <option value="MAN"><?= $idioma["periodo_MAN"]; ?></option>
                                    </select>
                                </div>
                                <div class="control-group" style="float:left; padding-right:25px;" id="div_de">
                                    <label class="control-label" for="periodo_inicio"><strong><?= $idioma["form_de"]; ?></strong></label>

                                    <div class="controls"><input class="span2" id="periodo_inicio" name="periodo_inicio" type="text" value=""/>
                                    </div>
                                </div>
                                <div class="control-group" id="div_ate">
                                    <label class="control-label" for="periodo_final"><strong><?= $idioma["form_ate"]; ?></strong></label>

                                    <div class="controls">
                                        <input class="span2" id="periodo_final" name="periodo_final" type="text" value=""/>
                                    </div>
                                </div>
                                <input type="hidden" value="<?=$matricula['idmatricula'];?>" name="matricula">
                                <br/>
                                <input type="submit" class="btn btn-primary" name="gerar" value="<?= $idioma["btn_gerar"]; ?>"/>
                            </form>
                        </section>
                        <?php } ?>
                        <section id="financeiromatricula">
                            <legend><?= $idioma['label_financeiro']; ?></legend>
                            <form method="post" class="form-horizontal">
                                <?php
                                if ($matricula['situacao']['visualizacoes'][2]) {
                                    ?>
                                    <input name="acao" type="hidden" value="alterar_negativacao_matricula" />
                                    <?php
                                }
                                ?>
                                <strong><?= $idioma['dados_financeiro_data_negativada']; ?></strong>
                                <br />
                                <input <?php if (!$matricula['situacao']['visualizacoes'][2] || $matricula['negativada'] == 'S') { ?> disabled="disabled" <?php } ?> id="form_negativacao_matricula" class="span2 inputGrande" type="text" value="<?= formataData($matricula['data_negativacao'], "br", 0); ?>" name="data_negativacao" />
                                &nbsp;
                                <?php
                                if ($matricula['negativada'] != 'S') {
                                    ?>
                                    <input name="acao_negativar" type="hidden" value="S" />
                                    <input id="btn_submit" class="btn btn-primary" type="submit" value="<?= $idioma['btn_negativar']; ?>" />
                                    <?php
                                } else {
                                    ?>
                                    <input name="acao_desnegativar" type="hidden" value="S" />
                                    <input id="btn_submit" class="btn btn-primary" type="submit" value="<?= $idioma['btn_desnegativar']; ?>" />
                                    <?php
                                }
                                ?>
                            </form>
                            <?php

                            $matriculavalorContrato = $matricula['valor_contrato'];
                            if(is_null($matriculavalorContrato)){ //Evitar o erro ao comparar 0 com null
                                $matriculavalorContrato = 0;
                            }
                            if ($matriculavalorContrato != $total_mensalidades ) {
                                ?>
                                <div class="alert alert-error" style="margin-top:10px;">
                                    <?php printf($idioma['texto_mensalidades_contratos_diferente'], number_format($total_mensalidades, 2, ",", "."), number_format($matricula['valor_contrato'], 2, ",", ".")); ?>
                                </div>
                                <?php
                            }

                            if ($pagamento['status_transacao'] == 'CAP') {
                                ?>
                                <div class="alert alert-success" style="margin-top:10px;"><?php printf($idioma['texto_cartao_autorizado'], $pagamento['tid']); ?></div>
                                <?php
                            }

                            if ($matricula['situacao']['visualizacoes'][5]) {
                                ?>
                                <form method="post" action="" style="padding-top:15px;" onsubmit="return validateFields(this, regras_financeiro)">
                                    <input name="acao" type="hidden" value="adicionar_financeiro" />
                                    <div class="control-group">
                                        <label for="form_nome"><strong> Descrição:</strong></label>
                                        <input class="span6" id="nome" name="nome" type="text" value="">
                                        <br />
                                    </div>
                                    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_idevento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_forma_pagamento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_primeiro_vencimento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_quantidade_parcelas']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_valor']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_valor_parcela']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="idevento" id="idevento" style="width:auto;">
                                                    <option value=""><?= $idioma['selecione_idevento']; ?></option>
                                                    <?php
                                                    foreach ($eventosFinanceiros as $eventoFinanceiro) {
                                                        ?>
                                                        <option value="<?= $eventoFinanceiro['idevento']; ?>"><?= $eventoFinanceiro['nome']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="forma_pagamento" id="forma_pagamento" style="width:auto;" onchange="liberaCamposFinanceiro(this.options[this.selectedIndex].value);">
                                                    <option value=""><?= $idioma['selecione_forma_pagamento']; ?></option>
                                                    <?php
                                                    foreach ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']] as $ind => $val) {
                                                        ?>
                                                        <option value="<?= $ind; ?>"><?= $val; ?></option>
                                                        <?php
                                                    }
                                                    ?>
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
                                            <td bgcolor="#F4F4F4" colspan="2" style="text-transform:uppercase;"><strong><?= $idioma['financeiro_informacoes_cartao']; ?> </strong></td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_bandeira_cartao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_autorizacao_cartao']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="idbandeira" id="idbandeira" style="width:auto;">
                                                    <option value=""><?= $idioma['selecione_bandeira_cartao']; ?></option>
                                                    <?php
                                                    foreach ($bandeirasCartoes as $bandeiraCartao) {
                                                        ?>
                                                        <option value="<?= $bandeiraCartao['idbandeira']; ?>"><?= $bandeiraCartao['nome']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input name="autorizacao_cartao" type="text" id="autorizacao_cartao" maxlength="40" class="span2" /></td>
                                        </tr>
                                    </table>
                                    <table id="financeiro_informacoes_cheque" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="display:none;">
                                        <tr>
                                            <td bgcolor="#F4F4F4" colspan="5" style="text-transform:uppercase;">
                                                <strong><?= $idioma['financeiro_informacoes_cheque']; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_banco_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_agencia_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_cc_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_numero_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_emitente_cheque']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="idbanco" id="idbanco" style="width:auto;">
                                                    <option value=""><?= $idioma['selecione_banco_cheque']; ?></option>
                                                    <?php
                                                    foreach ($bancos as $banco) {
                                                        ?>
                                                        <option value="<?= $banco['idbanco'] ?>"><?= $banco['nome']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input name="agencia_cheque" type="text" id="agencia_cheque" maxlength="20" class="span2" /></td>
                                            <td><input name="cc_cheque" type="text" id="cc_cheque" maxlength="20" class="span2" /></td>
                                            <td><input name="numero_cheque" type="text" id="numero_cheque" maxlength="20" class="span2" /></td>
                                            <td><input name="emitente_cheque" type="text" id="emitente_chequ" maxlength="100" class="span2" /></td>
                                        </tr>
                                    </table>
                                    <input class="btn" type="submit" value="<?= $idioma['btn_adicionar']; ?>" />
                                </form>
                                <?php
                            }

                            if (count($matricula['contas']) > 0) {
                                ?>
                                <br /><br />
                                <?php
                                foreach ($matricula['contas'] as $contas) {
                                    ?>
                                    <h4><?= $contas[0]['evento']; ?></h4>
                                    <br />
                                    <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><input type="checkbox" onclick="marcartodos(<?= $contas[0]['idevento']; ?>);" id="checkevento<?= $contas[0]['idevento']; ?>" /></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_id']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_forma_pagamento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_valor']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_vencimento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_situacao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_status_transacao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_pagar']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_data_pagamento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_documento']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_descricao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_bandeira_cartao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_autorizacao_cartao']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_banco_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_agencia_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_cc_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_numero_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4"><strong><?= $idioma['financeiro_emitente_cheque']; ?></strong></td>
                                            <td bgcolor="#F4F4F4">&nbsp;</td>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        $total_compartilhado = 0;
                                        $totalDesconto = 0;
                                        foreach ($contas as $conta) {
                                            if ($conta['valor_matricula']) {
                                                $valor_parcela = ($conta['valor_matricula']/$conta['total_contas_compartilhadas']);
                                            }

                                            $strike = '';
                                            if ($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao']  || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                                $strike = 'style="text-decoration:line-through;"';
                                            } else {
                                                if ($conta['valor_matricula']) {
                                                    $total_compartilhado += $valor_parcela;
                                                } else {
                                                    $total += $conta['valor'];
                                                }
                                            }
                                            $totalDesconto += $conta['desconto'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if (
                                                        $situacaoRenegociadaConta['idsituacao'] != $conta['idsituacao']
                                                        && $situacaoCanceladaConta['idsituacao'] != $conta['idsituacao']
                                                        && $situacaoTransferidaConta['idsituacao'] != $conta['idsituacao']
                                                    ) {
                                                        ?>
                                                        <input class="checbxvalor<?= $contas[0]['idevento']; ?>" type="checkbox" name="contaseditar[]" value="<?= $conta['idconta']; ?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $conta['idconta']; ?></td>
                                                <td>
                                                    <?php
                                                    echo $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$conta['forma_pagamento']];

                                                    if ($conta['idpagamento_compartilhado']) {
                                                        ?>
                                                        <span style="color:red;">(<?= $idioma['conta_compartilhada'] ?>)</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span style="color:#999">R$</span>
                                                    <?php
                                                    if ($conta['valor_matricula']) {
                                                        echo '<strong '.$strike.' >' . number_format($valor_parcela, 2, ',', '.').'</strong>
                                                            <span style="color:#999"> / ' . number_format($conta['valor'], 2, ',', '.').'</span>';
                                                    } else {
                                                        echo '<strong '.$strike.' >' . number_format($conta['valor'], 2, ',', '.').'</strong>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $data_vencimento_banco = formataData($conta['data_vencimento'],'en',0);

                                                    if (
                                                        $conta['situacao_cancelada'] == 'N'
                                                        && $conta['situacao_renegociada'] == 'N'
                                                        && $conta['situacao_transferida'] == 'N'
                                                        && $conta['situacao_paga'] == 'N'
                                                    ) {
                                                        $style_fonte = true;
                                                        if (date('Y-m-d') > $data_vencimento_banco) {
                                                            $style_vencimento = 'color:#FF0000;font-weight:bold;';
                                                        } elseif (date('Y-m-d') == $data_vencimento_banco) {
                                                            $style_vencimento = 'color:#FFA500;font-weight:bold;';
                                                        } else {
                                                            $style_vencimento = '';
                                                        }
                                                    } elseif (
                                                        $situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao']
                                                        || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao']
                                                        || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']
                                                    ) {
                                                        $style_fonte = true;
                                                        $style_vencimento = 'text-decoration:line-through;';
                                                    }

                                                    if ($style_fonte) {
                                                        ?>
                                                        <font style="<?= $style_vencimento; ?>" >
                                                        <?php
                                                    }

                                                    echo formataData($conta['data_vencimento'], 'br', 0);

                                                    if ($style_fonte) {
                                                        ?>
                                                        </font>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span
                                                    data-original-title="<?= $conta['situacao']; ?>"
                                                    class="label"
                                                    style="background:#<?= $conta['cor_bg']; ?>;color:#<?= $conta['cor_nome']; ?>"
                                                    data-placement="left"
                                                    rel="tooltip">
                                                        <?= $conta['situacao']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($conta['fastConnect']['idsituacao']) {
                                                        ?>
                                                        <span
                                                        data-original-title="<?= $GLOBALS['situacoesTransacaoFastConnect'][$GLOBALS['config']['idioma_padrao']][$conta['fastConnect']['idsituacao']]; ?>"
                                                        class="label"
                                                        style="background:<?= $GLOBALS['statusTransacaoPagSeguroCor'][$conta['pagSeguro']['status']]; ?>; color:#FFF;"
                                                        data-placement="left"
                                                        rel="tooltip">
                                                            <?= $GLOBALS['situacoesTransacaoFastConnect'][$GLOBALS['config']['idioma_padrao']][$conta['fastConnect']['idsituacao']]; ?>
                                                        </span>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (
                                                        $conta['situacao_paga'] == 'N'
                                                        && $conta['situacao_cancelada'] == 'N'
                                                        && $conta['valor'] > 0
                                                    ) {
                                                        if (
                                                            $conta['fastConnect']['fastconnect_client_code']
                                                            && $conta['fastConnect']['fastconnect_client_key']
                                                            && $conta['forma_pagamento'] == 11
                                                        ) {
                                                            $textoFastConnect = '';

                                                            //Se o método de pagamento for 2:Boleto e tiver o link do boleto
                                                            if ($conta['fastConnect']['tipo'] == "boleto" && $conta['fastConnect']['link_pagamento'] && $conta['situacao_fastconnect'] == "N") {
                                                                $textoFastConnect .= '<a
                                                                    class="btn btn-mini"
                                                                    data-original-title="' . $idioma['financeiro_boleto_tooltip'] . '"
                                                                    href="' . $conta['fastConnect']['link_pagamento'] . '"
                                                                    target="_blank"
                                                                    data-placement="left"
                                                                    rel="tooltip">
                                                                        ' . $idioma['financeiro_boleto'] . '
                                                                    </a>';
                                                            }

                                                            if ($conta['totalPagamentosAbertos'] == 0 && $conta['fastconnect_url_link'] && $conta['situacao_fastconnect'] == "N") {

                                                                $textoFastConnect .= '<a class="btn btn-mini" data-original-title="' . $idioma['financeiro_pagar_tooltip'] . '" data-placement="left" rel="tooltip" href="'. $conta['fastconnect_url_link'] .'" target="_blank">' . $idioma['financeiro_pagar'] . '</a>';
                                                            }

                                                            echo $textoFastConnect;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['data_pagamento'] && $conta['data_pagamento'] != '0000-00-00') ? formataData($conta['data_pagamento'], 'br', 0) :  '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['documento']) ? $conta['documento'] :  '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['nome']) ? $conta['nome'] :  '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['bandeira_cartao']) ? $conta['bandeira_cartao'] :  '--'; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($conta['autorizacao_cartao']) {
                                                        ?>
                                                        <a
                                                        data-original-title="Clique para ver mais informações."
                                                        href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/infocartao/<?= $conta['idconta']; ?>"
                                                        rel="tooltip facebox">
                                                            <?= $conta['autorizacao_cartao'];  ?>
                                                        </a>
                                                        <?php
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['banco']) ? $conta['banco'] : '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['agencia_cheque']) ? $conta['agencia_cheque'] : '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['cc_cheque']) ? $conta['cc_cheque'] : '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['numero_cheque']) ? $conta['numero_cheque'] : '--'; ?>
                                                </td>
                                                <td>
                                                    <?= ($conta['emitente_cheque']) ? $conta['emitente_cheque'] : '--'; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($situacaoRenegociadaConta['idsituacao'] != $conta['idsituacao'] && $situacaoCanceladaConta['idsituacao'] != $conta['idsituacao'] && $situacaoTransferidaConta['idsituacao'] != $conta['idsituacao']) {
                                                        if (! $conta['idpagamento_compartilhado']) {
                                                            ?>
                                                            <a class="btn btn-mini" href="#editarpagamento<?= $conta['idconta']; ?>" rel="facebox">
                                                                <?= $idioma['financeiro_editar']; ?>
                                                            </a>
                                                            <div id="editarpagamento<?= $conta['idconta']; ?>" style="display:none">
                                                                <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/editarpagamento/<?= $conta['idconta']; ?>" width="900" height="500" frameborder="0"></iframe>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a
                                                            class="btn btn-mini"
                                                            href="/<?= $url[0]; ?>/financeiro/pagamentos_compartilhados/<?= $conta['idpagamento_compartilhado']; ?>/editar"
                                                            target="_blank">
                                                                <?= $idioma['financeiro_editar_compartilhado']; ?>
                                                            </a>
                                                            <?php
                                                        }

                                                        if ($conta['pagSeguro']['status']) {
                                                            $idTransacao = $conta['pagSeguro']['idpagseguro'];
                                                            ?>
                                                            <a class="btn btn-mini" href="#dados_transacao<?= $conta['idconta']; ?>" rel="facebox">
                                                                <?= $idioma['financeiro_dados_transacao']; ?>
                                                            </a>
                                                            <div id="dados_transacao<?= $conta['idconta']; ?>" style="display:none">
                                                                <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/dados_transacao/<?= $conta['idconta']; ?>/<?= $idTransacao; ?>" width="900" height="600" frameborder="0"></iframe>
                                                            </div>
                                                            <?php
                                                        }
                                                    } elseif ($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao']) {
                                                        echo $conta['parcelas_renegociadas'];
                                                    } elseif ($situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                                        echo $conta['matricula_transferida'] . ' (' . $conta['idconta_transferida'] . ')';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                <button class="btn btn-mini" type="button" onclick="editarVarios(<?= $contas[0]['idevento']; ?>)">
                                                    <b><?= $idioma['financeiro_editarsel']; ?></b>
                                                </button>
                                            </td>
                                            <td>
                                                <span style="color:#999">R$</span>
                                                <?php
                                                if ($total_compartilhado) {
                                                    echo '<strong>' . number_format(($total_compartilhado+$total), 2, ',', '.') . '</strong> ';
                                                } else {
                                                    echo '<strong>' . number_format($total, 2, ',', '.') . '</strong>';
                                                }
                                                ?>
                                            </td>
                                            <td colspan="15">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <br />
                                    <?php
                                }
                                ?>
                                <script type="text/javascript">
                                    function MM_openBrWindow(theURL,winName,features) {
                                        window.open(theURL,winName,features);
                                    }
                                </script>
                                <?php
                                if ($matricula['situacao']['visualizacoes'][20]) {
                                    ?>
                                    <a class="btn btn-mini" onclick="MM_openBrWindow('<?= 'http://'.$_SERVER['SERVER_NAME'].'/'.$url['0']."/".$url['1']."/".$url['2']."/".$url[3]."/".$url[4]."/".$url[5].'/renegociar'; ?>','situacao_LI','status=yes,scrollbars=yes,resizable=yes,width=800,height=400')" ><?= $idioma['financeiro_renegociar']; ?></a>
                                    <?php
                                }

                                if ($matricula['situacao']['visualizacoes'][26]) {
                                    ?>
                                    <a class="btn btn-mini" onclick="MM_openBrWindow('<?= 'http://'.$_SERVER['SERVER_NAME'].'/'.$url['0']."/".$url['1']."/".$url['2']."/".$url[3]."/".$url[4]."/".$url[5].'/transferir'; ?>','situacao_LI','status=yes,scrollbars=yes,resizable=yes,width=800,height=400')" ><?= $idioma['financeiro_transferir']; ?></a>
                                    <?php
                                }
                            }
                            ?>
                        </section>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
</div>
<script>
    function editarVarios(idevento) {
        if (validarFormEditar(idevento)) {
            var camposContas = $('input[type=checkbox][class=checbxvalor'+idevento+']:checked');

            var get = '';
            for (var i = 0; i < camposContas.length; i++) {console.log(camposContas[i].value);
                if (i > 0) {
                    get += '&contaseditar[]=' + camposContas[i].value;
                } else {
                    get += 'contaseditar[]=' + camposContas[i].value;
                }
            }

            location.href = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/financeiro/editar_varios?' + get;
        }
    }

    function marcartodos(num) {
        $("#checkevento"+num).change(function () {
            $(".checbxvalor"+num).prop('checked', $(this).prop("checked"));
        });
    }

    function validarFormEditar(num){
        if ($('input[type=checkbox][class=checbxvalor'+num+']:checked').length == 0){
            alert('<?= $idioma['financeiro_editarsel_vazio']; ?>');
            return false;
        }
        return true;
    }

    $("#form_negativacao_matricula").mask("99/99/9999");
    $("#form_negativacao_matricula").datepicker($.datepicker.regional['pt-BR']);

    $("#vencimento").mask("99/99/9999");
    $("#vencimento").datepicker($.datepicker.regional['pt-BR']);
    $("#quantidade_parcelas").keypress(isNumber);
    $("#quantidade_parcelas").blur(isNumberCopy);
    $("#valor").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
    $("#numero_cheque").keypress(isNumber);
    $("#numero_cheque").blur(isNumberCopy);

    var regras_financeiro = new Array();
    regras_financeiro.push("required,idevento,<?= $idioma['financeiro_idevento_vazio']; ?>");
    regras_financeiro.push("required,forma_pagamento,<?= $idioma['financeiro_forma_pagamento_vazio']; ?>");
    regras_financeiro.push("required,vencimento,<?= $idioma['financeiro_vencimento_vazio']; ?>");
    regras_financeiro.push("required,quantidade_parcelas,<?= $idioma['financeiro_qtd_parcelas_vazio']; ?>");
    regras_financeiro.push("required,valor,<?= $idioma['financeiro_valor_vazio']; ?>");

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
                    if (regras_financeiro[i] == "required,idbanco,<?= $idioma['banco_cheque_vazio'] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma['agencia_cheque_vazio'] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma['cc_cheque_vazio'] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma['numero_cheque_vazio'] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma['emitente_cheque_vazio'] ?>") {
                        regras_financeiro.splice(i, 1);
                    }
                    if (regras_financeiro[i] == "required,idbandeira,<?= $idioma['bandeira_cartao_vazio'] ?>") {
                        contemBandeiraCartao = true;
                    }
                    if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma['autorizacao_cartao_vazio'] ?>") {
                        contemAutorizacaoCartao = true;
                    }
                }
                if (!contemBandeiraCartao) {
                    regras_financeiro.push("required,idbandeira,<?= $idioma['bandeira_cartao_vazio'] ?>");
                }
                if (!contemAutorizacaoCartao) {
                    regras_financeiro.push("required,autorizacao_cartao,<?= $idioma['autorizacao_cartao_vazio'] ?>");
                }
            } else {
                if (valor == 4) {
                    $("#financeiro_informacoes_cartao").hide("fast");
                    $("#financeiro_informacoes_cheque").show("fast");
                    for (var i = 0; i < regras_financeiro.length; i++) {
                        if (regras_financeiro[i] == "required,idbandeira,<?= $idioma['bandeira_cartao_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma['autorizacao_cartao_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,idbanco,<?= $idioma['banco_cheque_vazio'] ?>") {
                            contemBancoCheque = true;
                        }
                        if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma['agencia_cheque_vazio'] ?>") {
                            contemAgenciaCheque = true;
                        }
                        if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma['cc_cheque_vazio'] ?>") {
                            contemCcCheque = true;
                        }
                        if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma['numero_cheque_vazio'] ?>") {
                            contemNumeroCheque = true;
                        }
                        if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma['emitente_cheque_vazio'] ?>") {
                            contemEmitenteCheque = true;
                        }
                    }
                    if (!contemBancoCheque) {
                        regras_financeiro.push("required,idbanco,<?= $idioma['banco_cheque_vazio'] ?>");
                    }
                    if (!contemAgenciaCheque) {
                        regras_financeiro.push("required,agencia_cheque,<?= $idioma['agencia_cheque_vazio'] ?>");
                    }
                    if (!contemCcCheque) {
                        regras_financeiro.push("required,cc_cheque,<?= $idioma['cc_cheque_vazio'] ?>");
                    }
                    if (!contemNumeroCheque) {
                        regras_financeiro.push("required,numero_cheque,<?= $idioma['numero_cheque_vazio'] ?>");
                    }
                    if (!contemEmitenteCheque) {
                        regras_financeiro.push("required,emitente_cheque,<?= $idioma['emitente_cheque_vazio'] ?>");
                    }
                } else {
                    if (valor == 5) {
                        $("#quantidade_parcelas").val(1);
                        $("#quantidade_parcelas").attr('readonly', true);
                    }
                    $("#financeiro_informacoes_cartao").hide("fast");
                    $("#financeiro_informacoes_cheque").hide("fast");
                    for (var i = 0; i < regras_financeiro.length; i++) {
                        if (regras_financeiro[i] == "required,idbandeira,<?= $idioma['bandeira_cartao_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,autorizacao_cartao,<?= $idioma['autorizacao_cartao_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,idbanco,<?= $idioma['banco_cheque_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,agencia_cheque,<?= $idioma['agencia_cheque_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,cc_cheque,<?= $idioma['cc_cheque_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,numero_cheque,<?= $idioma['numero_cheque_vazio'] ?>") {
                            regras_financeiro.splice(i, 1);
                        }
                        if (regras_financeiro[i] == "required,emitente_cheque,<?= $idioma['emitente_cheque_vazio'] ?>") {
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
        if (valor && quantidade) {
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

    $("#vencimento").change(function() {
        if ($("#vencimento").val() != '') {
            valordata = $("#vencimento").val();
            date= valordata;
            ardt= new Array;
            ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
            ardt=date.split("/");
            erro=false;
            if ( date.search(ExpReg)==-1) {
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
                alert("\""+valordata+"\" <?= $idioma['financeiro_primeiro_vencimento_invalido']; ?>");
                $('#vencimento').focus();
                $("#vencimento").val('');
                return false;
            }
            return true;
        }
    });

    $("#vencimento").datepicker({
        currentText: 'Now'/*,
        minDate:'Now'*/
    });
</script>
<?php incluirTela("cabecalho_info", $config, $matricula); ?>

<?php
if ($config['pagSeguro']['urlStc']) {
    $_SESSION['pagseguro']['retorno'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/' . $url[5];
    ?>
    <form id="formPagseguro" method="post">
        <input type="hidden" id="acao" name="acao" value="salvar_pagamento" />
        <input type="hidden" id="idconta" name="idconta" value="" />
        <input type="hidden" id="tipo_pagamento" name="tipo_pagamento" value="PS">
        <input type="hidden" id="codigo_transacao_pagseguro" name="codigo_transacao_pagseguro" value="">
    </form>

    <!-- SCRIPT PAGSEGURO -->
    <script type="text/javascript" src="<?= $config['pagSeguro']['urlStc']; ?>/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
    <script type="text/javascript">
        $('.pagseguro').click(function() {
            var idconta = $(this).attr('conta');
            var code = $(this).attr('code');

            if (idconta && code) {
                isOpenLightbox = PagSeguroLightbox({
                    code: code
                }, {
                    success : function(transactionCode) {
                        //Caso continue o pagamento com sucesso
                        $('#formPagseguro #codigo_transacao_pagseguro').val(transactionCode);
                        $('#formPagseguro #idconta').val(idconta);
                        $('#formPagseguro').submit();
                    },
                    abort : function(transactionCode) {
                        //Se fechar o modal do pagseguro sem efetuar o pagamento
                    }
                });
            }
        });

        $(function () {
            $("#periodo_inicio").datepicker({
                currentText: 'Now',
                dateFormat: 'dd/mm/yy',
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
                monthNames: ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                alignment: 'bottomLeft',
                buttonImageOnly: true,
                buttonImage: '/assets/img/calendar.png',
                showStatus: true
            });
            $("#periodo_final").datepicker({
                currentText: 'Now',
                dateFormat: 'dd/mm/yy',
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
                monthNames: ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                alignment: 'bottomLeft',
                buttonImageOnly: true,
                buttonImage: '/assets/img/calendar.png',
                showStatus: true
            });
        });

        function verificaData(obj, div_de, div_ate, periodo_inicio, periodo_final) {
            if (obj.value == 'PER') {
                document.getElementById(div_de).style.display = 'block';
                document.getElementById(div_ate).style.display = 'block';
            } else {
                document.getElementById(div_de).style.display = 'none';
                document.getElementById(div_ate).style.display = 'none';
                document.getElementById(periodo_inicio).value = '';
                document.getElementById(periodo_final).value = '';
            }
        }

        const validarCampos = () =>
        {
            let dataDe = document.getElementById('periodo_inicio').value;
            let dataAte = document.getElementById('periodo_final').value;
            let displayDe = document.getElementById('div_de').style.display;
            let displayAte = document.getElementById('div_ate').style.display;
            if (displayDe === 'none' && displayAte === 'none') {
                return true;
            } else {
                if (!dataDe || !dataAte) {
                    alert('Período de data(s) não pode ser vazio.');
                    return false;
                } else if(dataDe > dataAte){
                    alert('Data do campo De maior que data do campo Ate');
                    return false;
                } else {
                    return true;
                }
            }
        }
    </script>
    <?php
}
?>
</body>
</html>
