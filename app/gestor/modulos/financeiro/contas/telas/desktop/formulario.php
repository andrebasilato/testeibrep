<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style type="text/css">
        .status {
            cursor: pointer;
            color: #FFF;
            font-size: 9px;
            font-weight: bold;
            padding: 5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right: 5px;
            line-height: 30px;
        }

        .ativo {
            font-size: 15px;
        }

        .inativo {
            background-color: #838383;
        }

        .divCentralizada {
            position: relative;
            width: 700px;
            height: 150px;
            left: 15%;
            top: 50%;
        }
    </style>
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
    } else if ($url[5] == "quitar") {
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
<?php if ($url[3] != 'cadastrar') { ?>
    <?php if($linha["fatura"] != "S"){ ?>
    <section id="situacao_conta">
        <legend><?= $idioma['label_situacao_conta']; ?></legend>
        <div id="divSituacoes" style="padding-top:15px; padding-bottom:15px; width:90%">
            <? foreach ($situacaoWorkflow as $ind => $val) { ?>
                <span
                    id="<?= $ind; ?>" <? ($ind == $linha['idsituacao']) ? print 'class="status ativo" style="background-color: #' . $val["cor_bg"] . '"' : print 'class="status inativo"'; ?>
                    <? if (in_array($ind, $situacaoWorkflowRelacionamento) && $linha["situacao"]["visualizacoes"][1]) { ?>onclick="modificarSituacao('<?= $ind; ?>','<?= $val["nome"]; ?>');"<? } else { ?>data-original-title="<?= $idioma['indisponivel']; ?>" style="background-color:#CCC" rel="tooltip"<? } ?>>
                                                    <?= $val["nome"]; ?>
                                                </span>
            <? } ?>
        </div>
        <?php if ($linha["situacao"]["visualizacoes"][1]) { ?>
            <script type="text/javascript">
                function modificarSituacao(para, nome) {
                    var de = "<?= $linha["idsituacao"]; ?>";
                    var msg = "<?=$idioma['confirma_altera_situacao_conta'];?>";
                    msg = msg.replace("[[idconta]]", "<?=$url[3];?>");
                    msg = msg.replace("[[nome]]", nome);
                    var confirma = confirm(msg);
                    if (confirma) {
                        document.getElementById('situacao_para').value = para;
                        document.getElementById('form_situacao').submit();
                    } else {
                        return false;
                    }
                }
            </script>
            <form method="post" action="#situacao" id="form_situacao">
                <input name="acao" type="hidden" value="alterarSituacao"/>
                <input name="situacao_para" id="situacao_para" type="hidden" value=""/>
            </form>
        <?php } ?>
    </section>
<?php } } ?>
<form action="#salvar_parcelas" method="post" onsubmit="return validateFields(this, regras)"
      enctype="multipart/form-data" class="form-horizontal">
    <? if ($url[5] == "editar") { ?>
        <input name="acao" type="hidden" value="salvar"/>
        <?php echo '<input type="hidden" name="' . $config["banco"]["primaria"] . '" id="' . $config["banco"]["primaria"] . '" value="' . $linha[$config["banco"]["primaria"]] . '" />';
        foreach ($config["banco"]["campos_unicos"] as $campoid => $campo) {
            ?>
            <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden"
                   value="<?= $linha[$campo["campo_banco"]]; ?>"/>
        <?
        }
        $linhaObj->GerarFormulario("formulario", $linha, $idioma);
    } elseif ($url[5] == "quitar") {
        ?>
        <input name="acao" type="hidden" value="quitar"/>
        <?php echo '<input type="hidden" name="' . $config["banco"]["primaria"] . '" id="' . $config["banco"]["primaria"] . '" value="' . $linha[$config["banco"]["primaria"]] . '" />';
        foreach ($config["banco"]["campos_unicos"] as $campoid => $campo) {
            ?>
            <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden"
                   value="<?= $linha[$campo["campo_banco"]]; ?>"/>
        <?
        }
        $linhaObj->GerarFormulario("formulario_quitar", $linha, $idioma);
    } else {
        ?> <input name="acao" type="hidden" value="salvar"/> <?php
        $linhaObj->GerarFormulario("formulario", $_POST, $idioma);
    }
    ?>
    <section id="salvar_parcelas">
        <?php
        if ($_POST['parcelas'] > 1) {

            $total = $_POST['parcelas'];
            $valor_liquido = ($_POST['valor'] + $_POST['valor_juros'] + $_POST['valor_multa'] + $_POST['valor_outro'] - $_POST['valor_desconto']);
            $valor_parcela = ($_POST['valor'] / $total);
			$valor_parcela_liquido = ($valor_liquido / $total);
            $valor_pago_parcela = ($_POST['valor_pago'] / $total);

            $valor_juros = ($_POST['valor_juros'] / $total);
            $valor_multa = ($_POST['valor_multa'] / $total);
            $valor_outro = ($_POST['valor_outro'] / $total);
            $valor_desconto = ($_POST['valor_desconto'] / $total);

            $valor_resto_total = ($_POST['valor'] - ($total * str_replace(',', '', number_format($valor_parcela, 2))));
			$valor_resto_total_liquido = ($valor_liquido - ($total * str_replace(',', '', number_format($valor_parcela_liquido, 2))));

            $valor_resto_juros = ($_POST['valor_juros'] - ($total * str_replace(',', '', number_format($valor_juros, 2))));
            $valor_resto_multa = ($_POST['valor_multa'] - ($total * str_replace(',', '', number_format($valor_multa, 2))));
            $valor_resto_outro = ($_POST['valor_outro'] - ($total * str_replace(',', '', number_format($valor_outro, 2))));
            $valor_resto_desconto = ($_POST['valor_desconto'] - ($total * str_replace(',', '', number_format($valor_desconto, 2))));
            ?>
            <legend>Parcelas</legend>
            <br/>
            <table border="0" width="100%" class="table table-bordered table-condensed tabelaSemTamanho">
                <tr>
                    <th bgcolor="#F4F4F4"></th>
                    <th bgcolor="#F4F4F4">Descrição</th>
                    <th bgcolor="#F4F4F4">Vencimento</th>
                    <th bgcolor="#F4F4F4">Valor</th>
                    <th bgcolor="#F4F4F4">Pagamento</th>
                    <th bgcolor="#F4F4F4">Valor pago</th>
                    <th bgcolor="#F4F4F4">Valor Juros</th>
                    <th bgcolor="#F4F4F4">Valor Multa</th>
                    <th bgcolor="#F4F4F4">Valor Outros</th>
                    <th bgcolor="#F4F4F4">Valor Desconto</th>
					<th bgcolor="#F4F4F4">Valor Líquido</th>
                </tr>
                <?php
                $vencimento = $_POST['data_vencimento'];

                $dia_v = substr($vencimento, -10, 2);
                $mes_v = substr($vencimento, -7, 2);
                $ano_v = substr($vencimento, -4, 4);

                $diaMes = array(
                    '01'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '02'          => array(
                        '28' => '28', '29' => '28', '30' => '28', '31' => '28'
                    ),
                    '02_bissexto' => array(
                        '28' => '28', '29' => '29', '30' => '29', '31' => '29'
                    ),
                    '03'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '04'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '30'
                    ),
                    '05'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '06'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '30'
                    ),
                    '07'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '08'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '09'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '30'
                    ),
                    '10'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                    '11'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '30'
                    ),
                    '12'          => array(
                        '28' => '28', '29' => '29', '30' => '30', '31' => '31'
                    ),
                );

                for ($i = 1; $i <= $total; $i++) {
                    $mesVencimentoParcela = date("m", mktime(date("H"), date("i"), date("s"), $mes_v + ($i - 1), 1, $ano_v));
                    $anoVencimentoParcela = date("Y", mktime(date("H"), date("i"), date("s"), $mes_v + ($i - 1), 1, $ano_v));

                    if ($dia_v >= 1 && $dia_v <= 27) {
                        $vencimentoParcela = $dia_v . '/' . $mesVencimentoParcela . '/' . $anoVencimentoParcela;
                    } elseif ($dia_v >= 28 && $dia_v <= 31) {
                        if ($mesVencimentoParcela == 2) {
                            $anoBissextos = false;
                            if ($anoVencimentoParcela % 4 == 0) {
                                $vencimentoParcela = $diaMes[$mesVencimentoParcela . '_bissexto'][$dia_v] . '/' . $mesVencimentoParcela . '/' . $anoVencimentoParcela;
                            } else {
                                $vencimentoParcela = $diaMes[$mesVencimentoParcela][$dia_v] . '/' . $mesVencimentoParcela . '/' . $anoVencimentoParcela;
                            }
                        } else {
                            $vencimentoParcela = $diaMes[$mesVencimentoParcela][$dia_v] . '/' . $mesVencimentoParcela . '/' . $anoVencimentoParcela;
                        }
                    } else {
                        $vencimentoParcela = '';
                    }
                    ?>
                    <tr>
                        <td style="padding:10px;">
                            <?php echo $i; ?>
                            <input type="hidden" name="parcelas_array[<?= $i; ?>][numero]" value="<?php echo $i; ?>"/>
                        </td>
                        <td><input class="span2" type="text" name="parcelas_array[<?= $i; ?>][nome]"
                                   value="<?php echo $_POST['nome'] . ' ' . $i . '/' . $_POST['parcelas']; ?>"/></td>
                        <td><input class="span2 class_data" type="text" id="parcelas_vencimento_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][data_vencimento]"
                                   value="<?php echo $vencimentoParcela; ?>"/></td>
                        <td><input class="span2 class_decimal" type="text" id="parcelas_valor_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor]"
                                   value="<?php if ($i == 1) echo number_format(($valor_parcela + $valor_resto_total), 2, ',', '.'); else echo number_format($valor_parcela, 2, ',', '.'); ?>"/>
                        </td>
                        <td><input class="span2 class_data" type="text" id="parcelas_pagamento_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][data_pagamento]"
                                   value="<?php echo $_POST['data_pagamento']; ?>"/></td>
                        <td><input class="span1 class_decimal" type="text" id="parcelas_pago_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor_pago]"
                                   value="<?php echo number_format($valor_pago_parcela, 2, ',', '.'); ?>"/></td>
                        <td><input class="span1 class_decimal" type="text" id="parcelas_juros_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor_juros]"
                                   value="<?php if ($i == 1) echo number_format(($valor_juros + $valor_resto_juros), 2, ',', '.'); else echo number_format($valor_juros, 2, ',', '.'); ?>"/>
                        </td>
                        <td><input class="span1 class_decimal" type="text" id="parcelas_multa_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor_multa]"
                                   value="<?php if ($i == 1) echo number_format(($valor_multa + $valor_resto_multa), 2, ',', '.'); else echo number_format($valor_multa, 2, ',', '.'); ?>"/>
                        </td>
                        <td><input class="span1 class_decimal" type="text" id="parcelas_outro_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor_outro]"
                                   value="<?php if ($i == 1) echo number_format(($valor_outro + $valor_resto_outro), 2, ',', '.'); else echo number_format($valor_outro, 2, ',', '.'); ?>"/>
                        </td>
                        <td><input class="span1 class_decimal" type="text" id="parcelas_desconto_<?php echo $i; ?>"
                                   name="parcelas_array[<?= $i; ?>][valor_desconto]"
                                   value="<?php if ($i == 1) echo number_format(($valor_desconto + $valor_resto_desconto), 2, ',', '.'); else echo number_format($valor_desconto, 2, ',', '.'); ?>"/>
                        </td>
						<td>
							<?php 
							if ($i == 1) 
								echo number_format(($valor_parcela_liquido + $valor_resto_total_liquido), 2, ',', '.'); 
							else 
								echo number_format($valor_parcela_liquido, 2, ',', '.'); 
							?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <input type="hidden" name="parcelas_definidas" value="1"/>
        <?php } ?>
    </section>
    <section id="salvar_centros_curso" <?= ($url[5] == 'editar' && count($associacoesArray) > 1) ? 'style="display: none;"' : ''; ?> >
        <?php
        if ($_POST['quantidade_centro_custo'] > 1) {
            $total = $_POST['quantidade_centro_custo'];
            $valor_liquido = ($_POST['valor'] + $_POST['valor_juros'] + $_POST['valor_multa'] + $_POST['valor_outro'] - $_POST['valor_desconto']);
            $valor_parcela_liquido = ($valor_liquido / $total);
            $valor_resto_total_liquido = ($valor_liquido - ($total * str_replace(',', '', number_format($valor_parcela_liquido, 2))));

            $porcentagem_parcela = (100 / $total);
            $porcentagem_resto_total = (100 - ($total * number_format($porcentagem_parcela, 2)));
            ?>
            <legend>Centros de custo</legend>
            <br/>
			
			<div style="padding-left:10px;padding-bottom:20px;">
				<strong>Valor Líquido:</strong> <?php echo number_format($valor_liquido, 2, ',' ,'.'); ?>
			</div>	
			
            <table border="0" width="100%" class="table table-bordered table-condensed tabelaSemTamanho" id="tabela_centros_custos">
                <tr>
                    <th bgcolor="#F4F4F4"></th>
                    <th bgcolor="#F4F4F4">Centro de custo</th>
					<th bgcolor="#F4F4F4">Valor ($)</th>
                    <th bgcolor="#F4F4F4">Porcentagem (%)</th>
                </tr>
                <?php for ($i = 1; $i <= $total; $i++) { ?>
                    <tr>
                        <td style="padding:10px;"><?php echo $i; ?></td>
                        <td>
                            <select name="centros_array[<?= $i; ?>][idcentro_custo]" id="idcentros<?= $i; ?>">
                                <option value=""><?php echo $idioma['selecione_centro_custo']; ?></option>
                                <?php foreach ($centros_custos as $centro_custo) { ?>
                                    <option
                                        value="<?php echo $centro_custo['idcentro_custo']; ?>"><?php echo $centro_custo['nome']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
						<td><input class="span2 class_decimal valor" type="text" id="parcelas_valor_centro_<?php echo $i; ?>"
                                   name="centros_array[<?= $i; ?>][valor]"
                                   value="<?php if ($i == 1) echo number_format(($valor_parcela_liquido + $valor_resto_total_liquido), 2, ',', '.'); else echo number_format($valor_parcela_liquido, 2, ',', '.'); ?>" onkeyup="alteraPorcentagem()" />
                        </td>
                        <td><input class="span2 class_decimal porcentagem" type="text" id="parcelas_porcentagem_<?php echo $i; ?>"
                                   name="centros_array[<?= $i; ?>][porcentagem]"
                                   value="<?php if ($i == 1) echo number_format(($porcentagem_parcela + $porcentagem_resto_total), 2, ',', '.'); else echo number_format($porcentagem_parcela, 2, ',', '.'); ?>" onkeyup="alteraValor()" />
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <input type="hidden" name="parcelas_definidas" value="1"/>
            <?php
        } elseif ($url[5] == 'editar' && count($associacoesArray) > 1) {//Usado para quando editar o valor da conta. Se só tiver um centro de custo, irá atualizar automáticamente para o valor total
            $valor_liquido = abs($linha['valor']) + $linha['valor_juros'] + $linha['valor_multa'] + $linha['valor_outro'] - $linha['valor_desconto'];
            ?>
            <legend>Centros de custo</legend>
            <br/>
            <div style="padding-left:10px;padding-bottom:20px;">
                <strong>Valor Líquido:</strong> <span id="valorLiquido"><?= number_format($valor_liquido, 2, ',' ,'.'); ?></span>
            </div>  
            
            <table border="0" width="100%" class="table table-bordered table-condensed tabelaSemTamanho" id="tabela_centros_custos">
                <tr>
                    <th bgcolor="#F4F4F4">Centro de custo</th>
                    <th bgcolor="#F4F4F4">Valor ($)</th>
                    <th bgcolor="#F4F4F4">Porcentagem (%)</th>
                </tr>
                <?php
                $totalValorCentroCusto = 0;
                $totalPorcentagemCentroCusto = 0;

                foreach ($associacoesArray as $ind => $associacao) {
                    $totalValorCentroCusto += $associacao['valor'];
                    $totalPorcentagemCentroCusto += $associacao['porcentagem'];
                    ?>
                    <tr>
                        <td><?= $associacao['nome']; ?></td>
                        <td>
                            <input class="span2 class_decimal valor" type="text"
                                id="parcelas_valor_centro_<?= $associacao['idcentro_custo']; ?>"
                                name="centros_custos_array[<?= $associacao['idconta_centro_custo']; ?>][valor]"
                                value="<?= number_format($associacao['valor'], 2, ',', '.'); ?>" onkeyup="alteraPorcentagem()" />
                        </td>
                        <td>
                            <input class="span2 class_decimal porcentagem" type="text"
                                id="parcelas_porcentagem_<?= $associacao['idcentro_custo']; ?>"
                                name="centros_custos_array[<?= $associacao['idconta_centro_custo']; ?>][porcentagem]"
                                value="<?= number_format($associacao['porcentagem'], 2, ',', '.'); ?>" 
                                onkeyup="alteraValor()" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <input type="hidden" name="parcelas_definidas" value="1"/>
            <?php
        }
        ?>
    </section>
    <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
        <input type="reset" class="btn"
               onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"
               value="<?= $idioma["btn_cancelar"]; ?>"/>
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
<script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
<link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8"/>
<script type="text/javascript">
    var regrasQuantidade = 0;    
    var regras = new Array();
    <?php
    if ($url[5] == 'quitar') {
        $config_formulario = $config["formulario_quitar"];
    } else {
        $config_formulario = $config['formulario'];
    }

    foreach ($config_formulario as $fieldsetid => $fieldset) {
        foreach ($fieldset["campos"] as $campoid => $campo) {
            if (is_array($campo["validacao"])) {
                foreach ($campo["validacao"] as $tipo => $mensagem) {
                    if ($campo["tipo"] == "file") {
                        ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                        <?php
                    } else {
                        ?>
                        regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
                        regrasQuantidade++;
                        <?php
                    }
                }
            }
        }
    }
    ?>

    jQuery(document).ready(function ($) {
        $('#nome_unidade').parent(".controls").parent(".control-group").hide("fast");
        $('#desc_unidade').parent(".controls").parent(".control-group").hide("fast");
        $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");

        <?php
        if ($url[5] == 'editar' && count($associacoesArray) > 1) {
            if (strnatcasecmp($totalValorCentroCusto, $valor_liquido) != 0 || strnatcasecmp($totalPorcentagemCentroCusto, 100) != 0) {
                ?>
                $('#salvar_centros_curso').show("fast");
                <?php
            }
            ?>
            function exibeOcultaCentroCusto() {
                $('#valorLiquido').html($('#form_valor_liquido').val());

                //Pega o valor liquído e substitui todas ocorrências de "." e substitui por vazio, e a "," substitui por '.'
                var valorLiquido = $('#form_valor_liquido').val().replace(/\./gi, "").replace(",", ".");
                if (valorLiquido != <?= $valor_liquido; ?>) {
                    $('#salvar_centros_curso').show("fast");
                } else {
                    $('#salvar_centros_curso').hide("fast");
                }
            }

            $('#form_valor').blur(function() {
                exibeOcultaCentroCusto();
            });

            $('#form_valor_juros').blur(function() {
                exibeOcultaCentroCusto();
            });

            $('#form_valor_multa').blur(function() {
                exibeOcultaCentroCusto();
            });

            $('#form_valor_outro').blur(function() {
                exibeOcultaCentroCusto();
            });

            $('#form_valor_desconto').blur(function() {
                exibeOcultaCentroCusto();
            });
            <?php
        }

        foreach($config["formulario"] as $fieldsetid => $fieldset) {
            foreach($fieldset["campos"] as $campoid => $campo) {
                if ($campo["mascara"]) {
                    ?>
                    <?php
                    if ($campo["mascara"] == "99/99/9999") {
                        ?>
                        $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
                        $('#<?= $campo["id"]; ?>').change(function () {
                            if ($('#<?= $campo["id"]; ?>').val() != '') {
                                valordata = $("#<?= $campo["id"]; ?>").val();
                                date = valordata;
                                ardt = new Array;
                                ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
                                ardt = date.split("/");
                                erro = false;
                                if (date.search(ExpReg) == -1) {
                                    erro = true;
                                }
                                else if (((ardt[1] == 4) || (ardt[1] == 6) || (ardt[1] == 9) || (ardt[1] == 11)) && (ardt[0] > 30))
                                    erro = true;
                                else if (ardt[1] == 2) {
                                    if ((ardt[0] > 28) && ((ardt[2] % 4) != 0))
                                        erro = true;
                                    if ((ardt[0] > 29) && ((ardt[2] % 4) == 0))
                                        erro = true;
                                }
                                if (erro) {
                                    alert("\"" + valordata + "\" não é uma data válida!!!");
                                    $('#<?= $campo["id"]; ?>').focus();
                                    $("#<?= $campo["id"]; ?>").val('');
                                    return false;
                                }
                                return true;
                            }
                        });
                        <?php
                    } elseif ($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") {
                        ?>
                        $('#<?= $campo["id"]; ?>').focusout(function () {
                            var phone, element;
                            element = $(this);
                            element.unmask();
                            phone = element.val().replace(/\D/g, '');
                            if (phone.length > 10) {
                                element.mask("(99) 99999-999?9");
                            } else {
                                element.mask("(99) 9999-9999?9");
                            }
                        }).trigger('focusout');
                        <?php
                    } else {
                        ?>
                        $("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
                        <?php
                    }
                }

                if ($campo["datepicker"]) {
                    ?>
                    $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
                    <?php
                }

                if ($campo["numerico"]) {
                    ?>
                    $("#<?= $campo["id"]; ?>").keypress(isNumber);
                    $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
                    <?php
                }

                if ($campo["decimal"]) {
                    ?>
                    $("#<?= $campo["id"]; ?>").maskMoney({symbol: "R$", decimal: ",", thousands: "."});
                    <?php
                }

                if ($campo["json"]) {
                    if (!$linha[$campo["valor"]] && $_POST[$campo["valor"]]) {
                        $linha[$campo["valor"]] = $_POST[$campo["valor"]];
                    }

                    if (!$linha[$campo["json_idpai"]] && $_POST[$campo["json_idpai"]]) {
                        $linha[$campo["json_idpai"]] = $_POST[$campo["json_idpai"]];
                    }
                    ?>
                    $('#<?=$campo["json_idpai"];?>').change(function () {
                        if ($(this).val()) {
                            $.getJSON('<?=$campo["json_url"];?>', {
                                <?=$campo["json_idpai"];?>:$(this).val(),
                                ajax:'true'
                            },function (json) {
                                var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';

                                for (var i = 0; i < json.length; i++) {
                                    var selected = '';
                                    if (json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                                        var selected = 'selected';
                                    options += '<option value="' + json[i].<?=$campo["valor"];?> + '" ' + selected + '>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                                }

                                $('#<?=$campo["id"];?>').html(options);
                            });
                        } else {
                            $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                        }
                    });

                    $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', {
                        <?= $campo["json_idpai"]; ?>: '<?=$linha[$campo["json_idpai"]];?>',
                        ajax: 'true'
                    },function (json) {
                        var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
                        if (null != json) {
                            for (var i = 0; i < json.length; i++) {
                                var selected = '';
                                if (json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                                    var selected = 'selected';
                                options += '<option value="' + json[i].<?=$campo["valor"];?> + '" ' + selected + '>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                            }
                        }

                        $('#<?=$campo["id"];?>').html(options);
                    });
                    <?php
                }

                if ($campo["botao_hide"]) {
                    if ($campo['tipo'] == 'select') {
                        ?>
                        var aux_d = $('#<?= $campo["id"]; ?>').attr('value');
                        var idCampo = "<?= $campo["id"]; ?>";
                        if (idCampo == 'form_tipo') {
                            if (aux_d == 'despesa') {
                                $('#<?= $campo["id"]; ?> option[value="despesa"]').attr('selected', 'selected');
                                $('#div_<?= $campo["iddiv2"]; ?>').show();
                                $('#div_<?= $campo["iddiv3"]; ?>').show();
                            } else {
                                if (aux_d == 'receita') {
                                    $('#<?= $campo["id"]; ?> option[value="receita"]').attr('selected', 'selected');
                                    $('#div_<?= $campo["iddiv"]; ?>').show();
                                }
                            }
                        } else if (idCampo == 'form_forma_pagamento') {
                            liberaCamposFormasPagamento(aux_d);
                        }

                        $('#<?= $campo["id"]; ?>').change(function () {
                            aux_d = $('#<?= $campo["id"]; ?>').attr('value');
                            idCampo = "<?= $campo["id"]; ?>";
                            if (idCampo == 'form_tipo') {
                                if (aux_d == 'receita') {
                                    $('#div_<?= $campo["iddiv"]; ?>').show("fast");
                                    $('#div_<?= $campo["iddiv2"]; ?>').hide("fast");
                                    $('#div_<?= $campo["iddiv3"]; ?>').hide("fast");
                                    $('#<?= $campo["iddiv2"]; ?>').attr("value", "");
                                    $('#<?= $campo["iddiv3"]; ?>').attr("value", "");
                                    ChecaOrdemDeCompra("",0);
                                } else {
                                    if (aux_d == 'despesa') {
                                        $('#div_<?= $campo["iddiv2"]; ?>').show("fast");
                                        $('#div_<?= $campo["iddiv3"]; ?>').show("fast");
                                        $('#div_<?= $campo["iddiv"]; ?>').hide("fast");
                                        $('#<?= $campo["iddiv"]; ?>').attr("value", "");
                                        ChecaOrdemDeCompra("",0);
                                    } else {
                                        $('#div_<?= $campo["iddiv"]; ?>').hide("fast");
                                        $('#div_<?= $campo["iddiv2"]; ?>').hide("fast");
                                        $('#div_<?= $campo["iddiv3"]; ?>').hide("fast");
                                        $('#<?= $campo["iddiv"]; ?>').attr("value", "");
                                        $('#<?= $campo["iddiv2"]; ?>').attr("value", "");
                                        $('#<?= $campo["iddiv3"]; ?>').attr("value", "");
                                        ChecaOrdemDeCompra("",0);
                                    }
                                }
                            } else if (idCampo == 'form_forma_pagamento') {
                                liberaCamposFormasPagamento(aux_d);
                            }
                        });
                        <?php
                    }
                }
            }
        }

        if ($url[3] <> "cadastrar") {
            ?>
            $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_categorias/', {
        		'idsindicato':
        		'<?=$linha['idsindicato'];?>', ajax
        		:
        		'true'
        		},
        		function (json) {
        			var options = '<option value="">- Selecione uma Categoria -</option>';
        			if (null != json) {
        				for (var i = 0; i < json.length; i++) {
        					var selected = '';
        					if (json[i].idcategoria == <?=intval($linha['idcategoria']);?>)
        						var selected = 'selected';
        					options += '<option value="' + json[i].idcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
        				}
        			}
        		
        			$('#idcategoria').html(options);
        		}
            );

            $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_subcategorias/', {
        		'idcategoria':<?=$linha['idcategoria'];?>, 'idsindicato' : <?=$linha['idsindicato'];?>, ajax:'true'
        		},
        		function (json) {
        			var options = '<option value="">– Selecione uma Subcategoria –</option>';
        			for (var i = 0; i < json.length; i++) {
        				var selected = '';
        				if (json[i].idsubcategoria == <?=intval($linha['idsubcategoria']);?>)
        					var selected = 'selected';
        				options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
        			}
        			$('#idsubcategoria').html(options);
        		}
            );
            <?php
        }

        if ($_POST['idsindicato']) {
            ?>
            $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_centros_custos/', {
            	'idsindicato':
            	'<?=$_POST['idsindicato'];?>', ajax
            	:
            	'true'
            	},
            	function (json) {
            		var options = '<option value="">- Selecione um centro de custo -</option>';
            		if (null != json) {
            			for (var i = 0; i < json.length; i++) {
            				var selected = '';
            				if (json[i].idcentro_custo == <?=intval($_POST['idcentro_custo']);?>)
            					var selected = 'selected';
            				options += '<option value="' + json[i].idcentro_custo + '" ' + selected + '>' + json[i].nome + '</option>';
            			}
            		}
            	
            		$('#form_idcentro_custo').html(options);
            	}
            );

            $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_categorias/', {
            	'idsindicato':
            	'<?=$_POST['idsindicato'];?>', ajax
            	:
            	'true'
            	},
            	function (json) {
            		var options = '<option value="">- Selecione uma Categoria -</option>';
            		if (null != json) {
            			for (var i = 0; i < json.length; i++) {
            				var selected = '';
            				if (json[i].idcategoria == <?=intval($_POST['idcategoria']);?>)
            					var selected = 'selected';
            				options += '<option value="' + json[i].idcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
            			}
            		}
            	
            		$('#idcategoria').html(options);
            	}
            );

            $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_subcategorias/', {
            	'idcategoria':<?=$_POST['idcategoria'];?>, 'idsindicato' : <?=$_POST['idsindicato'];?>, ajax:'true'
            	},
            	function (json) {
            		var options = '<option value="">– Selecione uma Subcategoria –</option>';
            		for (var i = 0; i < json.length; i++) {
            			var selected = '';
            			if (json[i].idsubcategoria == <?=intval($_POST['idsubcategoria']);?>)
            				var selected = 'selected';
            			options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
            		}
            		$('#idsubcategoria').html(options);
            	}
            );
            <?php
        }
        ?>

        $('#idcategoria').change(function () {
            if ($(this).val()) {
        		$.getJSON('/gestor/financeiro/contas/cadastrar/ajax_subcategorias/', {
        		'idcategoria':$(this).val(), 'idsindicato' : $("#idsindicato").val(), ajax:'true'
        		},function (json) {
        			var options = '<option value="">– Selecione uma Subcategoria –</option>';
        			for (var i = 0; i < json.length; i++) {
        				var selected = '';
        				options += '<option value="' + json[i].idsubcategoria + '" ' + selected + '>' + json[i].nome + '</option>';
        			}
        			$('#idsubcategoria').html(options);
                    $('#nome_unidade').parent(".controls").parent(".control-group").hide("fast");
                    $('#desc_unidade').parent(".controls").parent(".control-group").hide("fast");
        		});
            } else {
                $('#idsubcategoria').html('<option value="">– Escolha uma Categoria –</option>');
                $('#nome_unidade').parent(".controls").parent(".control-group").hide("fast");
                $('#desc_unidade').parent(".controls").parent(".control-group").hide("fast");   
            }
            ChecaOrdemDeCompra("",0);
        });

        $('#idsubcategoria').change(function () {
            if ($(this).val()) {
        		$.getJSON('/gestor/financeiro/contas/cadastrar/ajax_unidade/', {
        		'idsubcategoria':$(this).val(), 'idsindicato' : $("#idsindicato").val(), ajax:'true'
        		},function (json) {
                    if (json) {
                        nome_unidade.value = json[0].nome;
                        desc_unidade.value = json[0].descricao;
                        $('#nome_unidade').parent(".controls").parent(".control-group").show("fast");
                        $('#desc_unidade').parent(".controls").parent(".control-group").show("fast");
                    } else {
                        nome_unidade.value = " ";
                        desc_unidade.value = " ";
                        $('#nome_unidade').parent(".controls").parent(".control-group").hide("fast");
                        $('#desc_unidade').parent(".controls").parent(".control-group").hide("fast");
                    }
        		});
            }

            ChecaOrdemDeCompra("",0);
        });

        $('#form_idcentro_custo').change(function () {
            if ($('#form_idcentro_custo').val() == -100) {
                $('#div_form_quantidade_centro_custo').show("fast");
            } else {
                $('#div_form_quantidade_centro_custo').hide("fast");
            }
            ChecaOrdemDeCompra("",0);
        });

        <?php
        if ($url[3] == "cadastrar") {
            ?>
            function ChecaOrdemDeCompra(Busca , Quant) {
                if ($('#form_tipo').val() == 'despesa') {
                    if ($('#idsubcategoria').val()) {
                        $.getJSON('/gestor/financeiro/contas/cadastrar/checa_sub/', {'idsubcategoria' : $('#idsubcategoria').val() , ajax : 'true'},
                                function (json) {
                                    if (json[0].tipo_despesas == 'N') {
                                        <?php if ($_POST['quantidade_centro_custo'] > 1) { ?>
                                            var Sql = "0";
                                            for(i=1;i<=Quant;i++) {
                                                if (Busca[i] != "" && Busca[i]) {
                                                    Sql = Sql + "  , "+Busca[i];
                                                }
                                            }
                                            if (Sql != "0") {
                                                var Checa = false;
                                                for (var i = 0; i < regras.length; i++) {
                                                    if (regras[i] == "required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>") {
                                                        Checa = true;
                                                        break;
                                                    }
                                                }
                                                if (Checa == false) {
                                                    regras.push("required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>");
                                                }
                                                PreencheOrdemDeCompra(Sql);
                                            } else {
                                                $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                                                RemoveOrdemDeCompra();
                                                //alert("nada selecionado");
                                            }
                                        <?php } else { ?>
                                            if ( (($('#form_idcentro_custo').val() != -100) && $('#form_idcentro_custo').val())) {
                                                var Checa = false;
                                                for (var i = 0; i < regras.length; i++) {
                                                    if (regras[i] == "required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>") {
                                                        Checa = true;
                                                        break;
                                                    }
                                                }
                                                if (Checa == false) {
                                                    regras.push("required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>");
                                                }
                                                PreencheOrdemDeCompra(false);
                                            } else {
                                                $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                                                RemoveOrdemDeCompra();
                                                //alert(json[0].tipo_despesas);
                                            }
                                        <?php } ?>
                                    } else {
                                        $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                                        RemoveOrdemDeCompra();
                                        //alert('ne provisionada nao');
                                    }
                                }
                        );
                    } else {
                        $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                        RemoveOrdemDeCompra();
                        //alert('entrou no 1 mas nao e despesa');
                    }    
                } else {
                    $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                    RemoveOrdemDeCompra();
                    //alert('eu nao entrei nem no primeiro');
                }
            }
            <?php
        } elseif ($url[5] == "editar") {
            ?>
            function Seleciona() {
                $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_ordem_editar/', { 'idconta' : <?= $url[4] ?> },
                            function (json) {
                                if (json) {
                                    var selected = 'selected';
                                    var option = '<option value="' + json[0].idordemdecompra + '" ' + selected + '>' + json[0].nome + '</option>';

                                    $('#idordemdecompra').append(option);
                                    $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                                }
                        });
            }

            function ChecaOrdemDeCompra(Buscador , Quant) {
                var Busca = new Array();
                
                Quant = <?= count($associacoesArray) ?>;
                <?php for($i = 0;$i<count($associacoesArray);$i++) { ?>
                      Busca[<?= $i ?>] = <?php echo " '".$associacoesArray[$i]["idcentro_custo"]."'"; ?>;
                <?php } ?>   
                if ($('#form_tipo').val() == 'despesa') { 
                    if ($('#idsubcategoria').val()) {
                        $.getJSON('/gestor/financeiro/contas/cadastrar/checa_sub/', {'idsubcategoria' : $('#idsubcategoria').val() , ajax : 'true'},
                                function (json) {
                                    if (json[0].tipo_despesas == 'N') {
                                            var Sql = "0";
                                            for(i=0;i<Quant;i++) { 
                                                if (Busca[i] != "" && Busca[i]) {
                                                    Sql = Sql + "  , "+Busca[i];
                                                }
                                            }
                                            if (Sql != "0") {
                                                var Checa = false;
                                                for (var i = 0; i < regras.length; i++) {
                                                    if (regras[i] == "required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>") {
                                                        Checa = true;
                                                        break;
                                                    }
                                                }
                                                if (Checa == false) {
                                                    regras.push("required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>");
                                                }
                                                PreencheOrdemDeCompra(Sql);
                                            } else {
                                                $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                                                RemoveOrdemDeCompra();
                                            }
                                    } else {
                                        $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                                        RemoveOrdemDeCompra();
                                    }
                                }
                        );
                    } else {
                        $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                        RemoveOrdemDeCompra();
                    }    
                } else {
                    $('#idordemdecompra').parent(".controls").parent(".control-group").hide("fast");
                    RemoveOrdemDeCompra();
                }
            }
            <?php
        }
        ?>

        function RemoveOrdemDeCompra() {
            for (var i = 0; i < regras.length; i++) {
                if (regras[i] == "required,idordemdecompra,<?= $idioma["idordemdecompra_vazio"] ?>") {
                    regras.splice(i,1);
                    break;
                }
            }
        }

        function PreencheOrdemDeCompra(Sql) {
            <?php
            if ($_POST['quantidade_centro_custo'] > 1) {
                ?>
                $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_ordem_sql/', {'idsubcategoria' : $('#idsubcategoria').val() ,
                                                                                  'idcategoria' : $('#idcategoria').val() ,
                                                                                  'idsindicato' : $('#idsindicato').val() , 
                                                                                  'idescola' : $('#form_idescola').val() ,
                                                                                  'Sql' : Sql },
                function (json) {
                    if (json) {
                        var options = '<option value="">– Selecione uma Ordem de compra –</option>';
                        for (var i = 0; i < json.length; i++) {
                            var selected = '';
                            options += '<option value="' + json[i].idordemdecompra + '" ' + selected + '>' + json[i].nome + '</option>';
                        }
                        $('#idordemdecompra').html(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    } else {
                        var options = '<option value="">– Sem Ordens de compra disponíveis –</option>';
                        $('#idordemdecompra').html(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    }
                });
                <?php
            } elseif ($url[5] == "editar") {
                ?> 
                $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_ordem_sql/', {'idsubcategoria' : $('#idsubcategoria').val() ,
                                                                                  'idcategoria' : $('#idcategoria').val() ,
                                                                                  'idsindicato' : $('#idsindicato').val() , 
                                                                                  'idescola' : $('#form_idescola').val() ,
                                                                                  'Sql' : Sql },
                function (json) {
                    if (json) {
                        var options = '<option value="">– Selecione uma Ordem de compra –</option>';
                        for (var i = 0; i < json.length; i++) {
                            var selected = '';
                            options += '<option value="' + json[i].idordemdecompra + '" ' + selected + '>' + json[i].nome + '</option>';
                        }
                        $('#idordemdecompra').append(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    } else {
                        var options = '<option value="">– Selecione uma Ordem de compra –</option>';
                        $('#idordemdecompra').append(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    }
                });
            
                Seleciona();
                <?php
            } else {
                ?>
                $.getJSON('/gestor/financeiro/contas/cadastrar/ajax_ordem/', {'idsubcategoria' : $('#idsubcategoria').val() ,
                                                                              'idcategoria' : $('#idcategoria').val() ,
                                                                              'idcentro_custo' : $('#form_idcentro_custo').val() , 
                                                                              'idsindicato' : $('#idsindicato').val() , 
                                                                              'idescola' : $('#form_idescola').val() },
                function (json) {
                    if (json) {
                        var options = '<option value="">– Selecione uma Ordem de compra –</option>';
                        for (var i = 0; i < json.length; i++) {
                            var selected = '';
                            options += '<option value="' + json[i].idordemdecompra + '" ' + selected + '>' + json[i].nome + '</option>';
                        }
                        $('#idordemdecompra').html(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    } else {
                        var options = '<option value="">– Sem Ordens de compra disponíveis –</option>';
                        $('#idordemdecompra').html(options);
                        $('#idordemdecompra').parent(".controls").parent(".control-group").show("fast");
                    }    
                });
                <?php
            }
            ?>
        }

        <?php 
        if ($_POST['quantidade_centro_custo'] > 1) { 
            $QuantidadeCentros = $_POST['quantidade_centro_custo'];
            for($i = 1; $i<=$QuantidadeCentros; $i++) {
                echo "  $('#idcentros".$i."').change(function () {
                       Quantidade = ".$QuantidadeCentros."
                       ArrayCentros = new Array(); ";
                for($j = 1; $j<=$QuantidadeCentros; $j++) {
                    echo "      ArrayCentros[".$j."] = $('#idcentros".$j."').val();\n
                               ";
                } 
                echo "  ChecaOrdemDeCompra(ArrayCentros,Quantidade);  }); ";
             } 
        } 
        ?>
                                                
        <?php
        if ($url[5] == "editar") {
            ?>
            setTimeout(function(){ChecaOrdemDeCompra("",0);},1100);
            <?php
        } else {
            ?>
              ChecaOrdemDeCompra("",0);  
            <?php
        }
        ?>
    });

    function liberaCamposFormasPagamento(valorCampo) {
        var contemBandeiraCartao = false;
        var contemAutorizacaoCartao = false;
        var contemBancoCheque = false;
        var contemAgenciaCheque = false;
        var contemCcCheque = false;
        var contemNumeroCheque = false;
        var contemEmitenteCheque = false;
        var indBandeira = -1;
        var indAutoriza = -1;
        var indBanco = -1;
        var indAgencia = -1;
        var indCCCheque = -1;
        var indNumero = -1;
        var indEmitente = -1;
        var qtd_removidos = 0;
        if (valorCampo == 2 || valorCampo == 3) {
            $('#form_idbanco').attr("value", "");
            $('#div_form_idbanco').hide("fast");
            $('#form_agencia_cheque').attr("value", "");
            $('#div_form_agencia_cheque').hide("fast");
            $('#form_cc_cheque').attr("value", "");
            $('#div_form_cc_cheque').hide("fast");
            $('#form_numero_cheque').attr("value", "");
            $('#div_form_numero_cheque').hide("fast");
            $('#form_emitente_cheque').attr("value", "");
            $('#div_form_emitente_cheque').hide("fast");

            $('#div_form_idbandeira').show("fast");
            $('#div_form_autorizacao_cartao').show("fast");

            for (var i = 0; i < regras.length; i++) {
                if (regras[i] == "required,form_idbanco,<?= $idioma["idbanco_vazio"] ?>") {
                    indBanco = i;
                }
                if (regras[i] == "required,form_agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
                    indAgencia = i;
                }
                if (regras[i] == "required,form_cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
                    indCCCheque = i;
                }
                if (regras[i] == "required,form_numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
                    indNumero = i;
                }
                if (regras[i] == "required,form_emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
                    indEmitente = i;
                }
                if (regras[i] == "required,form_idbandeira,<?= $idioma["idbandeira_vazio"] ?>") {
                    contemBandeiraCartao = true;
                }
                if (regras[i] == "required,form_autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
                    contemAutorizacaoCartao = true;
                }
            }

            if (indBanco > -1) {
                regras.splice(indBanco, 1);
                qtd_removidos++;
            }
            if (indAgencia > -1) {
                regras.splice((indAgencia - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indCCCheque > -1) {
                regras.splice((indCCCheque - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indNumero > -1) {
                regras.splice((indNumero - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indEmitente > -1) {
                regras.splice((indEmitente - qtd_removidos), 1);
            }

            if (!contemBandeiraCartao) {
                regras.push("required,form_idbandeira,<?= $idioma["idbandeira_vazio"] ?>");
            }
            if (!contemAutorizacaoCartao) {
                regras.push("required,form_autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>");
            }
        } else if (valorCampo == 4) {
            $('#form_idbandeira').attr("value", "");
            $('#div_form_idbandeira').hide("fast");
            $('#form_autorizacao_cartao').attr("value", "");
            $('#div_form_autorizacao_cartao').hide("fast");

            $('#div_form_idbanco').show("fast");
            $('#div_form_agencia_cheque').show("fast");
            $('#div_form_cc_cheque').show("fast");
            $('#div_form_numero_cheque').show("fast");
            $('#div_form_emitente_cheque').show("fast");

            for (var i = 0; i < regras.length; i++) {
                if (regras[i] == "required,form_idbandeira,<?= $idioma["idbandeira_vazio"] ?>") {
                    indBandeira = i;
                }
                if (regras[i] == "required,form_autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
                    indAutoriza = i;
                }
                if (regras[i] == "required,form_idbanco,<?= $idioma["idbanco_vazio"] ?>") {
                    contemBancoCheque = true;
                }
                if (regras[i] == "required,form_agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
                    contemAgenciaCheque = true;
                }
                if (regras[i] == "required,form_cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
                    contemCcCheque = true;
                }
                if (regras[i] == "required,form_numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
                    contemNumeroCheque = true;
                }
                if (regras[i] == "required,form_emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
                    contemEmitenteCheque = true;
                }
            }

            if (indBandeira > -1) {
                regras.splice(indBandeira, 1);
                qtd_removidos++;
            }
            if (indAutoriza > -1) {
                regras.splice((indAutoriza - qtd_removidos), 1);
            }

            if (!contemBancoCheque) {
                regras.push("required,form_idbanco,<?= $idioma["idbanco_vazio"] ?>");
            }
            if (!contemAgenciaCheque) {
                regras.push("required,form_agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>");
            }
            if (!contemCcCheque) {
                regras.push("required,form_cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>");
            }
            if (!contemNumeroCheque) {
                regras.push("required,form_numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>");
            }
            if (!contemEmitenteCheque) {
                regras.push("required,form_emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>");
            }
        } else {
            $('#form_idbandeira').attr("value", "");
            $('#div_form_idbandeira').hide("fast");
            $('#form_autorizacao_cartao').attr("value", "");
            $('#div_form_autorizacao_cartao').hide("fast");
            $('#form_idbanco').attr("value", "");
            $('#div_form_idbanco').hide("fast");
            $('#form_agencia_cheque').attr("value", "");
            $('#div_form_agencia_cheque').hide("fast");
            $('#form_cc_cheque').attr("value", "");
            $('#div_form_cc_cheque').hide("fast");
            $('#form_numero_cheque').attr("value", "");
            $('#div_form_numero_cheque').hide("fast");
            $('#form_emitente_cheque').attr("value", "");
            $('#div_form_emitente_cheque').hide("fast");

            for (var i = 0; i < regras.length; i++) {
                if (regras[i] == "required,form_idbandeira,<?= $idioma["idbandeira_vazio"] ?>") {
                    indBandeira = i;
                }
                if (regras[i] == "required,form_autorizacao_cartao,<?= $idioma["autorizacao_cartao_vazio"] ?>") {
                    indAutoriza = i;
                }
                if (regras[i] == "required,form_idbanco,<?= $idioma["idbanco_vazio"] ?>") {
                    indBanco = i;
                }
                if (regras[i] == "required,form_agencia_cheque,<?= $idioma["agencia_cheque_vazio"] ?>") {
                    indAgencia = i;
                }
                if (regras[i] == "required,form_cc_cheque,<?= $idioma["cc_cheque_vazio"] ?>") {
                    indCCCheque = i;
                }
                if (regras[i] == "required,form_numero_cheque,<?= $idioma["numero_cheque_vazio"] ?>") {
                    indNumero = i;
                }
                if (regras[i] == "required,form_emitente_cheque,<?= $idioma["emitente_cheque_vazio"] ?>") {
                    indEmitente = i;
                }
            }
            if (indBandeira > -1) {
                regras.splice((indBandeira), 1);
                qtd_removidos++;
            }
            if (indAutoriza > -1) {
                regras.splice((indAutoriza - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indBanco > -1) {
                regras.splice(indBanco - qtd_removidos, 1);
                qtd_removidos++;
            }
            if (indAgencia > -1) {
                regras.splice((indAgencia - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indCCCheque > -1) {
                regras.splice((indCCCheque - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indNumero > -1) {
                regras.splice((indNumero - qtd_removidos), 1);
                qtd_removidos++;
            }
            if (indEmitente > -1) {
                regras.splice((indEmitente - qtd_removidos), 1);
            }
        }
    }

    //FUNÇÃO QUE CALCULA A PARCELA DA CONTA
    function calcularParcelas() {
        var valor = document.getElementById('form_valor').value;
        valor = valor.replace(".", "");
        valor = valor.replace(".", "");
        valor = valor.replace(",", ".");
        valor = parseFloat(valor);
        var quantidade = document.getElementById('form_parcelas').value;
        /*if (valor && quantidade) {
            document.getElementById('form_valor_parcela').value = parseFloat(valor / quantidade);
            formata_valor('form_valor_parcela');
        }*/
        calcularLiquido();
    }

    //FUNÇÃO QUE CALCULA O VALOR LÍQUIDO DA CONTA
    function calcularLiquido() {
        var valor = document.getElementById('form_valor').value;
        valor = valor.replace(".", "");
        valor = valor.replace(".", "");
        valor = valor.replace(",", ".");
        valor = parseFloat(valor);

        if (valor) {
            var liquido = 0;

            var juros = document.getElementById('form_valor_juros').value;
            juros = juros.replace(".", "");
            juros = juros.replace(".", "");
            juros = juros.replace(",", ".");
            juros = parseFloat(juros);
            if (juros)
                liquido += parseFloat(juros);

            var multa = document.getElementById('form_valor_multa').value;
            multa = multa.replace(".", "");
            multa = multa.replace(".", "");
            multa = multa.replace(",", ".");
            multa = parseFloat(multa);
            if (multa)
                liquido += parseFloat(multa);

            var outro = document.getElementById('form_valor_outro').value;
            outro = outro.replace(".", "");
            outro = outro.replace(".", "");
            outro = outro.replace(",", ".");
            outro = parseFloat(outro);
            if (outro)
                liquido += parseFloat(outro);

            var desconto = document.getElementById('form_valor_desconto').value;
            desconto = desconto.replace(".", "");
            desconto = desconto.replace(".", "");
            desconto = desconto.replace(",", ".");
            desconto = parseFloat(desconto);
            if (desconto)
                liquido -= parseFloat(desconto);

            document.getElementById('form_valor_liquido').value = parseFloat(valor + liquido);
            formata_valor('form_valor_liquido');
        }
    }
    <?php
    if ($url[5] == 'editar') {
        ?>
        calcularLiquido();
        <?php
    }
    ?>

    //FUNÇÃO PARA FORMATAR VALOR EM JAVASCRIPT
    function formata_valor(id) {
        var val = document.getElementById(id).value;

        var c = 0;
        part = new Array();
        array = new Array();

        ar = new Array();
        ar = val.split(".");
        val = ar[0];
        var t = val.length;

        for (i = t - 1; i >= 0; i--) {
            part[c] = val[i];
            c++;
            if (c == 3) {
                c = 0;
                array[array.length] = part.reverse().join("");
                part = new Array();
            }
        }

        if (part.length > 0)
            array[array.length] = part.reverse().join("");

        if (!ar[1])
            document.getElementById(id).value = array.reverse().join(".") + ',00';
        else {
            if (ar[1].length == 1)
                ar[1] += '0';
            document.getElementById(id).value = array.reverse().join(".") + ',' + ar[1];
        }
    }

    $(".class_data").datepicker($.datepicker.regional["pt-BR"]);
    $(".class_data").mask("99/99/9999");
    $(".class_decimal").maskMoney({decimal: ",", thousands: ".", precision: 2, allowZero: true});

    <?php
    if ($url[5] != 'editar') {
        ?>
    	var select_centro_custo = document.getElementById("form_idcentro_custo");
    	var option = document.createElement("option");
    	option.text = "- Escolher mais de um centro de custo -";
    	option.value = -100;
    	select_centro_custo.add(option, select_centro_custo[1]);

    	<?php
        if ($_POST['idcentro_custo']) {
            ?>
    		var post_centro_curso = '<?php echo $_POST['idcentro_custo']; ?>';
    		if (post_centro_curso == -100) {
    			select_centro_custo.selectedIndex = 1;
    			$('#div_form_quantidade_centro_custo').show("fast");
    		}
        	<?php
        }
    }
    ?>

    function alteraPorcentagem() {
    	array_inputs = new Array();
    	var valor_total = 0;
    	var total_centros = <?php echo count($associacoesArray); ?>;
    	array_inputs = document.getElementById('tabela_centros_custos').getElementsByTagName('input');
    	tamanho = array_inputs.length;
    	for (i=0; i<tamanho; i++) {
    		if (array_inputs[i].type == 'text') {
    			if (array_inputs[i].className.indexOf("valor") > 0) {
    				var valor = array_inputs[i].value;
    				valor = valor.replace(".", "");
    				valor = valor.replace(".", "");
    				valor = valor.replace(",", ".");
    				valor = parseFloat(valor);
    				valor_total += valor;
    				total_centros++;
    			}
    		}				
    	}

    	for (i=0; i<tamanho; i++) {
    		if (array_inputs[i].type == 'text') {
    			if (array_inputs[i].className.indexOf("valor") > 0) {
    				var valor = array_inputs[i].value;
    				valor = valor.replace(".", "");
    				valor = valor.replace(".", "");
    				valor = valor.replace(",", ".");
    				valor = parseFloat(valor);
    				
    				porc_valor = ((valor*100)/valor_total);
    				
    				id_array = array_inputs[i].id.split("_");
    				id_alterar = 'parcelas_porcentagem_' + id_array[id_array.length-1];						
    				
    				nova_porcentagem = number_format(porc_valor, 2, ',', '.');
    				document.getElementById(id_alterar).value = nova_porcentagem;
    			}
    		}				
    	}
    }

    function alteraValor() {
    	array_inputs = new Array();
    	var valor_total = <?= (float) $valor_liquido; ?>;
        <?php
        //Se estiver editando e tiver mais de um centro de custo, irá alterar o valor_total para o valor que estiver na conta
        if ($url[5] == 'editar' && count($associacoesArray) > 1) {
            ?>
            //Pega o valor liquído e substitui todas ocorrências de "." e substitui por vazio, e a "," substitui por '.'
            valor_total = $('#form_valor_liquido').val().replace(/\./gi, "").replace(",", ".");
            <?php
        }
        ?>
    	var total_centros = <?= count($associacoesArray); ?>;
    	array_inputs = document.getElementById('tabela_centros_custos').getElementsByTagName('input');
    	tamanho = array_inputs.length;
    	/*for (i=0; i<tamanho; i++) {
    		if (array_inputs[i].type == 'text') {
    			if (array_inputs[i].className.indexOf("valor") > 0) {
    				var valor = array_inputs[i].value;
    				valor = valor.replace(".", "");
    				valor = valor.replace(".", "");
    				valor = valor.replace(",", ".");
    				valor = parseFloat(valor);
    				valor_total += valor;
    				total_centros++;
    			}
    		}				
    	}*/

    	for (i=0; i<tamanho; i++) {
    		if (array_inputs[i].type == 'text') {
    			if (array_inputs[i].className.indexOf("porcentagem") > 0) {
    				var porcentagem = array_inputs[i].value;
    				porcentagem = porcentagem.replace(".", "");
    				porcentagem = porcentagem.replace(".", "");
    				porcentagem = porcentagem.replace(",", ".");
    				porcentagem = parseFloat(porcentagem);
    				
    				porc_valor = ((porcentagem*valor_total)/100);

    				id_array = array_inputs[i].id.split("_");
    				id_alterar = 'parcelas_valor_centro_' + id_array[id_array.length-1];						
    				
    				novo_valor = number_format(porc_valor, 2, ',', '.');
    				//console.log(valor_total + ' - ' + array_inputs[i].value + ' - ' + novo_valor);
    				document.getElementById(id_alterar).value = novo_valor;
    			}
    		}				
    	}
    }
</script>
</div>
</body>
</html>