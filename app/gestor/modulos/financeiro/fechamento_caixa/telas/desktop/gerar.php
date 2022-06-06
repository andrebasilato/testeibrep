<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen"
          charset="utf-8"/>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
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
        <li class="active"><?php echo $idioma["gerar"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span12">
<div class="box-conteudo">
<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i
            class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
<h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

<div class="tabbable tabs-left" style="width: 100%;">
<?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
<div class="tab-content" style="width: 100%;">
<div class="tab-pane active" id="tab_editar" style="width: 100%;">
<h3 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h3>

<div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
<?php if ($_POST["msg"]) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
    </div>
<? } ?>
<?php if (count($salvar["erros"]) > 0) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma["form_erros"]; ?></strong>
        <? foreach ($salvar["erros"] as $ind => $val) { ?>
            <br/>
            <?php echo $idioma[$val]; ?>
        <? } ?>
    </div>
<? } ?>
<form class="well" method="post">

<p><?= $idioma['form_sindicato']; ?></p>
<select id="idsindicato" name="idsindicato[]" multiple class="span5">
    <option value=""><?= $idioma['escolha_sindicato']; ?></option>
    <?php
    foreach ($sindicatosArray as $ind => $var) {
        ?>
        <option
        value="<?= $var['idsindicato']; ?>"
        <?= (in_array($var['idsindicato'], $_POST['idsindicato'])) ? 'selected' : null; ?>>
            <?= $var['nome_abreviado']; ?>
        </option>
        <?php
    }
    ?>
</select>
<br/><br/>

<legend>Contas à receber</legend>
<div class="control-group">
    <label class="control-label"
           for="form_ordenacao_data_receber"><?php echo $idioma["form_ordenacao_data_receber"]; ?></label>

    <div class="controls">
        <select name="ordenacao_data_receber" class="span3">
            <option value=""></option>
            <option
                value="c.idconta"<?php if ($_POST['ordenacao_data_receber'] == 'c.idconta') echo 'selected="selected"'; ?> >
                Código
            </option>
            <option
                value="c.idmatricula"<?php if ($_POST['ordenacao_data_receber'] == 'c.idmatricula') echo 'selected="selected"'; ?> >
                Matrícula
            </option>
            <option
                value="p.nome"<?php if ($_POST['ordenacao_data_receber'] == 'p.nome') echo 'selected="selected"'; ?> >
                Nome
            </option>
            <option
                value="c.data_vencimento"<?php if ($_POST['ordenacao_data_receber'] == 'c.data_vencimento') echo 'selected="selected"'; ?> >
                Vencimento
            </option>
            <option
                value="c.nome"<?php if ($_POST['ordenacao_data_receber'] == 'c.nome') echo 'selected="selected"'; ?> >
                Descrição
            </option>
            <option
                value="c.numero_cheque"<?php if ($_POST['ordenacao_data_receber'] == 'c.numero_cheque') echo 'selected="selected"'; ?> >
                Cheque
            </option>
            <option
                value="c.valor"<?php if ($_POST['ordenacao_data_receber'] == 'c.valor') echo 'selected="selected"'; ?> >
                Valor
            </option>
        </select>
    </div>
    <br/>

    <label class="control-label"
           for="form_ordenacao_data_receber"><?php echo $idioma["form_forma_pagamento_receber"]; ?></label>

    <div class="controls">
        <select name="forma_pagamento_receber" class="span3">
            <option value=""></option>
            <?php foreach ($forma_pagamento_conta[$config['idioma_padrao']] as $idoforma => $forma) { ?>
                <option
                    value="<?php echo $idoforma; ?>" <?php if ($_POST['forma_pagamento_receber'] == $idoforma) echo 'selected="selected"'; ?> ><?php echo $forma; ?></option>
            <?php } ?>
        </select>
    </div>
    <br/>
    <label class="control-label" for="form_ordenacao_data_receber">
        <?= $idioma["form_evento_financeiro_receber"]; ?>
    </label>
    <div class="controls">
        <select name="valor_evento_financeiro" class="span3">
            <option value=""></option>
            <?php
            foreach ($eventosFinanceiros as $eventoFinanceiro) {
                ?>
                <option value="<?= $eventoFinanceiro['idevento']; ?>" <?php if ($_POST['valor_evento_financeiro'] == $eventoFinanceiro['idevento']) echo 'selected="selected"'; ?> ><?= $eventoFinanceiro['nome']; ?></option>
                <?php
            }
            ?>
        </select>
    </div>
    <br/>

    <label class="control-label"
           for="form_tipo_data_filtro_receber"><?php echo $idioma["form_tipo_periodo_receber"]; ?></label>

    <div class="controls">
        <select name="tipo_data_receber" id="form_tipo_data_filtro_receber" class="span3"
                onchange="verificaData(this, 'div_de_receber', 'div_ate_receber', 'periodo_inicio_receber', 'periodo_final_receber')">
            <option value="PER">Periodo definido pelo usuário</option>
            <option value="HOJ" <?php if ($_POST['tipo_data_receber'] == 'HOJ') echo 'selected="selected"'; ?> >Hoje
            </option>
            <option value="SET" <?php if ($_POST['tipo_data_receber'] == 'SET') echo 'selected="selected"'; ?> >Últimos
                7 dias
            </option>
            <option value="MAT" <?php if ($_POST['tipo_data_receber'] == 'MAT') echo 'selected="selected"'; ?> >Mês
                atual
            </option>
            <option value="MPR" <?php if ($_POST['tipo_data_receber'] == 'MPR') echo 'selected="selected"'; ?> >Próximo
                mês
            </option>
            <option value="MAN" <?php if ($_POST['tipo_data_receber'] == 'MAN') echo 'selected="selected"'; ?> >Mês
                anterior
            </option>
        </select> <?php /*&nbsp;<input type="submit" class="btn" name="btn_buscar" value="<?= $idioma["btn_gerar"]; ?>" />*/ ?>
    </div>
</div>
<?php if ($_POST['tipo_data_receber'] && $_POST['tipo_data_receber'] != 'PER') $display_receber = 'display:none;'; ?>
<div class="control-group" style="float:left; padding-right:25px; <?= $display_receber ?>" id="div_de_receber">
    <label class="control-label" for="periodo_inicio_receber"><?php echo $idioma["form_de_receber"]; ?></label>

    <div class="controls"><input class="span2" id="periodo_inicio_receber" name="periodo_inicio_receber" type="text"
                                 value="<?php echo $_POST['periodo_inicio_receber']; ?>"/></div>
</div>
<div class="control-group" id="div_ate_receber" style="<?= $display_receber ?>">
    <label class="control-label" for="periodo_final_receber"><?php echo $idioma["form_ate_receber"]; ?></label>

    <div class="controls"><input class="span2" id="periodo_final_receber" name="periodo_final_receber" type="text"
                                 value="<?php echo $_POST['periodo_final_receber']; ?>"/></div>
</div>
<br/>


<?php
//CONTAS RECEITA
if (count($array_contas['receita']) > 0) {//$_POST['btn_buscar'] &&
    ?>
<div style="overflow:auto;">
    <table class="table">
        <tr>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;">Cod.</th>
            <th style="background-color:#f5f5f5;">Matricula</th>
            <th style="background-color:#f5f5f5;">Aluno</th>
            <th style="background-color:#f5f5f5;">CFC</th>
            <th style="background-color:#f5f5f5;">Sindicato</th>
            <th style="background-color:#f5f5f5;">Vencimento</th>
            <th style="background-color:#f5f5f5;">Descrição</th>
            <th style="background-color:#f5f5f5;">Situação</th>
            <th style="background-color:#f5f5f5;">Cheque</th>
            <th style="background-color:#f5f5f5;">Valor</th>
			<th style="background-color:#f5f5f5;">Doc. referência</th>
			<th style="background-color:#f5f5f5;">Autorização do cartão</th>
            <th style="background-color:#f5f5f5;">C/C destino</th>
        </tr>
        <tr>
            <th style="background-color:#f5f5f5;"><input type="checkbox" onclick="alterarValorTotal('totalReceber', 'receber_contas')" id="alterar_todas_receber" onchange="alterar_elementos('alterar_todas_receber', 'receber_contas')"/>
            </th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;">
                <select id="alterar_todas_correntes_receber"
                        onchange="alterar_elementos('alterar_todas_correntes_receber', 'receber_contas_correntes')">
                    <option value=""> - Selecione uma conta -</option>
                    <?php foreach ($contas_correntes as $corrente) { ?>
                        <option value="<?= $corrente['idconta_corrente'] ?>"><?= $corrente['nome'] ?></option>
                    <?php } ?>
                </select>
            </th>
        </tr>
        <?php
        $totalReceita = 0;
		foreach ($array_contas['receita'] as $receita) {
             $totalReceita += $receita['valor'];
			?>
            <tr>
                <td><input class="receber_contas" type="checkbox" onclick="alterarValorTotal('totalReceber', 'receber_contas')" data-valor="<?= $receita['valor']; ?>" name="receber_contas[<?= $receita['idconta']; ?>]" value="<?= $receita['idsindicato']; ?>" <?php if($_POST['receber_contas'][$receita['idconta']]) echo 'checked="checked"'; ?> /></td>
                <td><?= $receita['idconta']; ?></td>
                <td><?= $receita['idmatricula']; ?></td>
                <td><?= $receita['pessoa']; ?></td>
                <td><?= $receita['cfc']; ?></td>
                <td><?= $receita['sindicato']; ?></td>
                <td><?= formataData($receita['data_vencimento'], 'pt', 0); ?></td>
                <td><?= $receita['nome']; ?></td>
                <td><?= $receita['situacao']; ?></td>
                <td><?= $receita['numero_cheque']; ?></td>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td style="color:#999; border-top: 0px">R$</td>
                            <td align="right"
                                style="text-align:right; border-top: 0px"><?= number_format($receita['valor'], 2, ',', '.'); ?></td>
                        </tr>
                    </table>
                </td>
				<td><?= ($receita['documento']) ? $receita['documento'] : '--'; ?></td>
				<td><?= ($receita['autorizacao_cartao']) ? $receita['autorizacao_cartao'] : '--'; ?></td>
                <td class="correntes">
                    <?php
                    //if($receita['conta_corrente']) {
                    //echo $receita['conta_corrente'];
                    //} else {
                    ?>
                    <select name="receber_contas_correntes[<?= $receita['idconta']; ?>]"
                            class="receber_contas_correntes">
                        <option value=""> - Selecione uma conta -</option>
                        <?php foreach ($contas_correntes as $corrente) { ?>
                            <option value="<?= $corrente['idconta_corrente'] ?>"
                                    <?php if ($corrente['idconta_corrente'] == $receita['idconta_corrente']
												||
											  $_POST['receber_contas_correntes'][$receita['idconta']]) { ?>selected="selected"<?php } ?>><?= $corrente['nome'] ?></option>
                        <?php } ?>
                    </select>
                    <?php
                    //}
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="10"></td>
            <td colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td style="color:#999; border-top: 0px">R$</td>
                        <td align="right" style="text-align:right; border-top: 0px"><?= number_format($totalReceita, 2, ',', '.'); ?></td>
                        <td align="right" style="text-align:right; border-top: 0px" id="totalReceber">(0,00)</td>
                    </tr>
                </table>
            </td>
            <td colspan="2"></td>
        </tr>
    </table>
    </div>
<?php
}
?>


<legend>Contas à pagar</legend>
<div class="control-group">
    <label class="control-label"
           for="form_ordenacao_data_pagar"><?php echo $idioma["form_ordenacao_data_pagar"]; ?></label>

    <div class="controls">
        <select name="ordenacao_data_pagar" class="span3">
            <option value=""></option>
            <option
                value="c.idconta" <?php if ($_POST['ordenacao_data_pagar'] == 'c.idconta') echo 'selected="selected"'; ?> >
                Código
            </option>
            <option
                value="c.data_vencimento" <?php if ($_POST['ordenacao_data_pagar'] == 'c.data_vencimento') echo 'selected="selected"'; ?> >
                Vencimento
            </option>
            <option
                value="c.idfornecedor" <?php if ($_POST['ordenacao_data_pagar'] == 'c.idfornecedor') echo 'selected="selected"'; ?> >
                Fornecedor
            </option>
            <option
                value="c.nome" <?php if ($_POST['ordenacao_data_pagar'] == 'c.nome') echo 'selected="selected"'; ?> >
                Descrição
            </option>
            <option
                value="c.idproduto" <?php if ($_POST['ordenacao_data_pagar'] == 'c.idproduto') echo 'selected="selected"'; ?> >
                Produto
            </option>
            <option
                value="c.valor" <?php if ($_POST['ordenacao_data_pagar'] == 'c.valor') echo 'selected="selected"'; ?> >
                Valor
            </option>
        </select>
    </div>
    <br/>

    <label class="control-label"
           for="form_tipo_data_filtro_pagar"><?php echo $idioma["form_tipo_periodo_pagar"]; ?></label>

    <div class="controls">
        <select name="tipo_data_pagar" id="form_tipo_data_filtro_pagar" class="span3"
                onchange="verificaData(this, 'div_de_pagar', 'div_ate_pagar', 'periodo_inicio_pagar', 'periodo_final_pagar')">
            <option value="PER">Periodo definido pelo usuário</option>
            <option value="HOJ" <?php if ($_POST['tipo_data_pagar'] == 'HOJ') echo 'selected="selected"'; ?> >Hoje
            </option>
            <option value="SET" <?php if ($_POST['tipo_data_pagar'] == 'SET') echo 'selected="selected"'; ?> >Últimos 7
                dias
            </option>
            <option value="MAT" <?php if ($_POST['tipo_data_pagar'] == 'MAT') echo 'selected="selected"'; ?> >Mês
                atual
            </option>
            <option value="MPR" <?php if ($_POST['tipo_data_pagar'] == 'MPR') echo 'selected="selected"'; ?> >Próximo
                mês
            </option>
            <option value="MAN" <?php if ($_POST['tipo_data_pagar'] == 'MAN') echo 'selected="selected"'; ?> >Mês
                anterior
            </option>
        </select> <?php /*&nbsp;<input type="submit" class="btn" name="btn_buscar" value="<?= $idioma["btn_gerar"]; ?>" />*/ ?>
    </div>
</div>
<?php if ($_POST['tipo_data_pagar'] && $_POST['tipo_data_pagar'] != 'PER') $display_pagar = 'display:none;'; ?>
<div class="control-group" style="float:left; padding-right:25px; <?= $display_pagar ?>" id="div_de_pagar">
    <label class="control-label" for="periodo_inicio_pagar"><?php echo $idioma["form_de_pagar"]; ?></label>

    <div class="controls"><input class="span2" id="periodo_inicio_pagar" name="periodo_inicio_pagar" type="text"
                                 value="<?php echo $_POST['periodo_inicio_pagar']; ?>"/></div>
</div>
<div class="control-group" id="div_ate_pagar" style="<?= $display_pagar ?>">
    <label class="control-label" for="periodo_final_pagar"><?php echo $idioma["form_ate_pagar"]; ?></label>

    <div class="controls"><input class="span2" id="periodo_final_pagar" name="periodo_final_pagar" type="text"
                                 value="<?php echo $_POST['periodo_final_pagar']; ?>"/></div>
</div>
<br/>

<?php
//CONTAS DESPESA
if (count($array_contas['despesa']) > 0) {//$_POST['btn_buscar'] &&
    ?>
    <div style="overflow:auto;">
    <table class="table">
        <tr>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;">Cód.</th>
            <th style="background-color:#f5f5f5;">Vencimento</th>
            <th style="background-color:#f5f5f5;">Fornecedor</th>
            <th style="background-color:#f5f5f5;">Descrição</th>
            <th style="background-color:#f5f5f5;">Produto</th>
            <th style="background-color:#f5f5f5;">Valor</th>
			<th style="background-color:#f5f5f5;">Doc. referência</th>
			<th style="background-color:#f5f5f5;">Autorização</th>
            <th style="background-color:#f5f5f5;">C/C origem</th>
        </tr>
        <tr>
            <th style="background-color:#f5f5f5;"><input type="checkbox" onclick="alterarValorTotal('totalPagar', 'pagar_contas')" id="alterar_todas_pagar" onchange="alterar_elementos('alterar_todas_pagar', 'pagar_contas')"/>
            </th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;"></th>
			<th style="background-color:#f5f5f5;"></th>
			<th style="background-color:#f5f5f5;"></th>
            <th style="background-color:#f5f5f5;">
                <select id="alterar_todas_correntes_pagar" onchange="alterar_elementos('alterar_todas_correntes_pagar', 'pagar_contas_correntes')">
                    <option value=""> - Selecione uma conta -</option>
                    <?php foreach ($contas_correntes as $corrente) { ?>
                        <option value="<?= $corrente['idconta_corrente'] ?>"><?= $corrente['nome'] ?></option>
                    <?php } ?>
                </select>
            </th>
        </tr>
        <?php
		$totalDespesa = 0;
        foreach ($array_contas['despesa'] as $despesa) {
            $totalDespesa += $despesa['valor'];
			?>
            <tr>
                <td><input class="pagar_contas" type="checkbox" onclick="alterarValorTotal('totalPagar', 'pagar_contas')" data-valor="<?= $despesa['valor']; ?>" name="pagar_contas[<?= $despesa['idconta']; ?>]" value="<?= $despesa['idsindicato']; ?>" <?php if($_POST['pagar_contas'][$despesa['idconta']]) echo 'checked="checked"'; ?> /></td>
                <td><?= $despesa['idconta']; ?></td>
                <td><?= formatadata($despesa['data_vencimento'], 'br', 0); ?></td>
                <td><?= $despesa['fornecedor']; ?></td>
                <td><?= $despesa['nome']; ?></td>
                <td><?= $despesa['produto']; ?></td>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td style="color:#999; border-top: 0px">R$</td>
                            <td align="right"
                                style="text-align:right; border-top: 0px"><?= number_format($despesa['valor'], 2, ',', '.'); ?></td>
                        </tr>
                    </table>
                </td>
				<td><?= ($despesa['documento']) ? $despesa['documento'] : '--'; ?></td>
				<td><?= ($despesa['autorizacao_cartao']) ? $despesa['autorizacao_cartao'] : '--'; ?></td>
                <td>
                    <?php
                    //if($despesa['conta_corrente']) {
                    //echo $despesa['conta_corrente'];
                    //} else {
                    ?>
                    <select name="pagar_contas_correntes[<?= $despesa['idconta']; ?>]" class="pagar_contas_correntes">
                        <option value=""> - Selecione uma conta -</option>
                        <?php foreach ($contas_correntes as $corrente) { ?>
                            <option value="<?= $corrente['idconta_corrente'] ?>"
                                    <?php if ($corrente['idconta_corrente'] == $despesa['idconta_corrente']
												||
											  $_POST['pagar_contas_correntes'][$despesa['idconta']]) { ?>selected="selected"<?php } ?>><?= $corrente['nome'] ?></option>
                        <?php } ?>
                    </select>
                    <?php
                    //}
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="6"></td>
            <td colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td style="color:#999; border-top: 0px">R$</td>
                        <td align="right" style="text-align:right; border-top: 0px"><?= number_format($totalDespesa, 2, ',', '.'); ?></td>
                        <td align="right" style="text-align:right; border-top: 0px" id="totalPagar">(0,00)</td>
                    </tr>
                </table>
            </td>
            <td colspan="2"></td>
        </tr>
    </table>
    </div>
<?php
}
?>
<input type="submit" class="btn" name="btn_buscar" value="<?= $idioma["btn_gerar"]; ?>"/>
<?php
if ($_POST['btn_buscar'] || (count($array_contas['receita']) > 0 || count($array_contas['despesa']) > 0)) {
    ?>
    <input type="submit" class="btn btn-primary" name="btn_fechar" value="<?= $idioma["btn_fechar"]; ?>"
           style="border-left:20px;"/>
<?php } ?>

</form>
<br/>
</div>
</div>
</div>
</div>
</div>
<?php /*<div class="span3">
     		<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
    			<div class="well"><?= $idioma["nav_novousuario_explica"]; ?>
                    <br />
                    <br />
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar" class="btn primary"><?= $idioma["nav_novousuario"]; ?></a>
    			</div>
        	<? } ?>
    		<?php  incluirLib("sidebar_".$url[1],$config); ?>
    	</div>*/
?>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/plugins/portamento/portamento-min.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>

<script>
    $(function () {
        $("#periodo_inicio_receber").datepicker({
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
        $("#periodo_final_receber").datepicker({
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
        $("#periodo_inicio_pagar").datepicker({
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
        $("#periodo_final_pagar").datepicker({
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


    function alterar_elementos(id, nome_classe) {
        var elemento = document.getElementById(id);
        var array_dados = new Array();
        array_dados = document.getElementsByClassName(nome_classe);
        var array_dados_tamanho = array_dados.length;

        if (elemento.type == 'checkbox') {
            var checked = false;
            if (elemento.checked)
                checked = true;

            for (i = 0; i < array_dados_tamanho; i++)
                array_dados[i].checked = checked;
        } else if (elemento.type == 'select-one') {
            var valor_escolhido = elemento.options[elemento.selectedIndex].value;

            for (i = 0; i < array_dados_tamanho; i++)
                array_dados[i].value = valor_escolhido;
        }
    }

	function alterarValorTotal(idTotal, nomeClasse) {
        var contas = document.getElementsByClassName(nomeClasse);
		var totalContas = contas.length;
		var valorTotalSelecionado = 0;
		for (i = 0; i < totalContas; i++) {
			if(contas[i].checked == true) {
				valor = parseFloat(contas[i].getAttribute('data-valor'));
				valorTotalSelecionado += valor;
			}
		}
		valorTotalSelecionado = number_format(valorTotalSelecionado, 2, ',', '.');

		document.getElementById(idTotal).innerHTML = '('+valorTotalSelecionado+')';
    }
</script>
</div>
</body>
</html>
