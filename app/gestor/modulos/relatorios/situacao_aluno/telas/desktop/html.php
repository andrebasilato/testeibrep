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
    padding: 8px 8px 7px;
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
        <td height="80">
            <table border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
                </tr>
            </table>
        </td>
        <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
        <td  align="right" valign="top">
            <table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
                <tr>
                    <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
                    <td><a href="javascript:window.print();"><?= $idioma["imprimir"]; ?></a></td>
                    <td>
                        <a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
                        <div id="link_salvar" style="display:none;">
                            <div style="width:300px;">
                                <form method="post" onsubmit="return validateFields(this, regras)">
                                    <input type="hidden" name="acao" value="salvar_relatorio" />
                                    <label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
                                    <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                                    <input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
                                </form>
                            </div>
                        </div>
                    </td>
                    <?php /*<td>
                        <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls?<?php echo $_SERVER["QUERY_STRING"]; ?>">
                            <input class="btn" type="submit" value="<?php echo $idioma['baixar_planilha'] ?>" />
                        </form>
                    </td>*/ ?>
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
<?php }
if(count($matriculas["erros"]) > 0) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma["form_erros"]; ?></strong>
        <?php foreach($matriculas["erros"] as $ind => $val) { ?>
            <br />
            <?php echo $idioma[$val]; ?>
        <?php } ?>
    </div>
<?php
}
unset($matriculas["erros"], $matriculas["erro"]);
?>
<?php $relatorioObj->GerarTabela($matriculas,$_GET["q"],$idioma); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td>Registros encontrados: <?= count($matriculas); ?></td>
    </tr>
</table>
<?php if(count($matriculas) <= 0) { ?>
    <table border="1" width="100%">
        <tr>
            <td>Nenhuma informação encontrada</td>
        </tr>
    </table>
<?php
} else {
    foreach ($matriculas as $matricula) { ?>
        <table border="1" width="100%">
            <tr>
                <td width="60" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Matrícula</strong></td>
                <td width="110" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Código do aluno</strong></td>
                <td width="110" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Percentual do AVA</strong></td>
                <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Nome</strong></td>
                <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Telefone do Aluno</strong></td>
                <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Celular do Aluno</strong></td>
                <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Email do Aluno</strong></td>
                <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Atendente</strong></td>
            </tr>
            <tr>
                <td align="center" style="text-align:center"><?= $matricula['idmatricula']; ?></td>
                <td align="center" style="text-align:center"><?= $matricula['idpessoa']; ?></td>
                <td align="center" style="text-align:center"><?= number_format($matricula['porcentagem'], 2, ',', '.'); ?></td>
                <td align="center" style="text-align:left"><?= $matricula['nome']; ?></td>
                <td align="center" style="text-align:left"><?= $matricula['telefone']; ?></td>
                <td align="center" style="text-align:left"><?= $matricula['celular']; ?></td>
                <td align="center" style="text-align:left"><?= $matricula['email']; ?></td>
                <td align="center" style="text-align:left"><?= $matricula['vendedor']; ?></td>
            </tr>
            <tr>
                <td colspan="8" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">DOCUMENTOS ENTREGUES</strong></td>
            </tr>
            <tr>
                <td colspan="8">
                <table border="1">
                    <tr>
                        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Tipo</strong></td>
                        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Pessoa</strong></td>
                        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Situação</strong></td>
                    </tr>
                    <?php if(count($matricula['documentos']) <= 0) { ?>
                        <tr>
                            <td colspan="3">Nenhuma informação encontrada</td>
                        </tr>
                    <?php
                    } else {
                        foreach ($matricula['documentos'] as $documento) { ?>
                            <tr>
                                <td><?= $documento['tipo']; ?></td>
                                <td>
                                    <?php
                                    if($documento["associacao"]) {
                                        echo 'Responsável';
                                    } else {
                                        echo 'Aluno';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class="label" style="background-color:<?= $situacao_documento_cores[$documento["situacao"]]; ?>" >
                                        <?= $situacao_documento[$config["idioma_padrao"]][$documento["situacao"]]; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
                </td>
            </tr>
            <tr>
                <td colspan="8" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">NOTAS</strong></td>
            </tr>
            <tr>
                <td colspan="8">
                <table border="1">
                    <tr>
                        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Disciplina</strong></td>
                        <?php
                        $colunas = 0;
                        foreach($matricula["disciplinas"] as $disciplina) {
                            $notas = count($disciplina["notas"]);
                            if($notas > $colunas) $colunas = $notas;
                        }
                        for($i = 1; $i <= $colunas; $i++){ ?>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Nota <?= $i; ?></strong></td>
                        <?php } ?>
                        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Situação</strong></td>
                    </tr>
                    <?php foreach ($matricula['disciplinas'] as $disciplina) { ?>
                        <tr>
                            <td><?= $disciplina['nome']; ?></td>
                            <?php
                            for($i = 1; $i <= $colunas; $i++){
                                $nota = $disciplina["notas"][$i-1];
                                ?>
                                <td style="text-align:center;">
                                    <?php
                                    if($nota){
                                        if(!$nota["idprova"] && !$nota["id_solicitacao_prova"]){ ?>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom:0px">
                                                <tr>
                                                    <td><?= number_format($nota["nota"],1,',','.'); ?></td>
                                                </tr>
                                            </table>
                                        <?php } else { ?>
                                            <span style="color:#999">
                                                <?= $nota["nota"]; ?>
                                                <?php if($nota["idprova"]){ ?><sup>1</sup><?php } ?>
                                                <?php if($nota["id_solicitacao_prova"]){ ?><sup>2</sup><?php } ?>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <td><?php echo $disciplina['situacao']['situacao']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
                </td>
            </tr>
            <tr>
                <td colspan="8" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">FINANCEIRO</strong></td>
            </tr>
            <tr>
                <td colspan="8">
                <?php foreach($matricula["contas"] as $contas) { ?>
                    <h4><?= $contas[0]["evento"]; ?></h4>
                    <br />
                    <table border="1" width="100%">
                        <tr>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Forma de pagamento</strong></td>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Valor</strong></td>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Vencimento</strong></td>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Situação</strong></td>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Pagamento</strong></td>
                            <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">&nbsp;</strong></td>
                        </tr>
                        <?php
                        $total = 0;
                        $totalCompartilhado = 0;
                        $totalDesconto = 0;
                        foreach($contas as $conta) {

                            $style = '';
                            if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao']  || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                $style = ' style="text-decoration:line-through;"';
                            } else {
                                $total += $conta["valor_parcela"];
                            } ?>
                            <tr>
                                <td>
                                    <?php
                                    echo $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]];
                                    if($conta['idpagamento_compartilhado']) { ?>
                                        <span style="color:red;">(<?= $idioma['conta_compartilhada'] ?>)</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span style="color:#999">R$</span>
                                    <?php echo '<strong><span'.$style.'>'.number_format($conta["valor_parcela"], 2, ",", ".").'</span></strong>'; ?>
                                </td>
                                    <td>
                                        <?php
                                        $styleVencimento = '';
                                        if ($conta["situacao_cancelada"] == 'N' && $conta["situacao_renegociada"] == 'N' && $conta["situacao_transferida"] == 'N' && $conta["situacao_paga"] == 'N' && $conta["situacao_pagseguro"] == 'N') {
                                            if (date('Y-m-d') > $conta["data_vencimento"])
                                                $styleVencimento = 'color:#FF0000;font-weight:bold;';
                                            else if (date('Y-m-d') == $conta["data_vencimento"]) {
                                                $styleVencimento = 'color:#FFA500;font-weight:bold;';
                                            }
                                        } else if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao'] || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                            $styleVencimento .= 'text-decoration:line-through;';
                                        } ?>
                                        <font style="<?php echo $styleVencimento; ?>">
                                            <?php echo formataData($conta["data_vencimento"],'br',0); ?>
                                        </font>
                                    </td>
                                    <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                                    <td><?php if($conta["data_pagamento"] && $conta["data_pagamento"] != '0000-00-00') echo formataData($conta["data_pagamento"], 'br', 0); else echo "--"; ?></td>
                                    <td>
                                        <?php
                                        if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao']) {
                                            echo $conta["parcelas_renegociadas"];
                                        } elseif($situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                            echo $conta['matricula_transferida'] . ' (' . $conta["idconta_transferida"] . ')';
                                        } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <table class="quebra_pagina"><tr><td></td></tr></table>
    <?php } ?>
<?php } ?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
    <tr>
        <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
        <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
    </tr>
</table>
</body>
</html>