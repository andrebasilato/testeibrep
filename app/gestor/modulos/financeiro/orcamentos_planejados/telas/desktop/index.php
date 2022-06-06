<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>
        .form-horizontal .control-group {
            margin-bottom: 2px !important;
        }

        .control-group {
            margin-bottom: 4px !important;
        }

        .table th, .table td {
            padding: 5px !important;
        }

        .label-de {
            padding: 2px;
            margin-left: 10px;
            line-height: 27px;
        }

        .label-ate {
            padding: 2px;
            margin-left: 10px;
            line-height: 27px;
        }

        .campo-de {
            padding: 2px;
        }

        .campo-ate {
            padding: 2px;
        }
    </style>
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
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
            <? if ($_GET["q"]) { ?>
                <li><span class="divider">/</span> <a
                    href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a>
                </li><? } ?>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <? if ($_POST["msg"]) { ?>
                    <div class="alert alert-success fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                    </div>
                <? } ?>
                <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" method="get" id="form_filtro">
                    <?php
                    $anoInicio = date("Y") - 2;
                    $anoFim = date("Y") + 2;
                    ?>
                    <table id="sortTableExample" class="table tabelaSemTamanho">
                        <tbody>
                        <tr>
                            <td colspan="8" style="border-top: 0px;">Sindicato:<br/>
                                <select id="idsindicato" name="idsindicato">
                                    <option value="">Selecione um sindicato</option>
                                    <?php foreach ($sindicatosArray as $ind => $sindicato) { ?>
                                        <option value="<?php echo $sindicato["idsindicato"]; ?>"
                                                <?php if ($_GET["idsindicato"] == $sindicato["idsindicato"]) { ?>selected="selected"<?php } ?>><?php echo $sindicato["nome_abreviado"]; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="60" style="border-top: 0px;">
                                <select id="de_mes" class="inputPreenchimentoCompleto" name="de_mes"
                                        style="width:100px">
                                    <?php foreach ($GLOBALS["meses_idioma"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                                        <option value="<?php echo $ind; ?>"
                                                <?php if ($_GET["de_mes"] == $ind) { ?>selected="selected"<?php } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="font-size:20px; line-height: 23px; border-top: 0px;">/</td>
                            <td style="border-top: 0px;">
                                <select id="de_ano" class="inputPreenchimentoCompleto" name="de_ano">
                                    <?php for ($ano = $anoInicio; $ano <= $anoFim; $ano++) { ?>
                                        <option value="<?php echo $ano; ?>"
                                                <?php if ($_GET["de_ano"] == $ano) { ?>selected="selected"<?php } ?>><?php echo $ano; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td width="20" style="line-height: 28px; border-top: 0px;">até</td>
                            <td width="60" style="border-top: 0px;">
                                <select id="ate_mes" class="inputPreenchimentoCompleto" name="ate_mes"
                                        style="width:100px">
                                    <?php foreach ($GLOBALS["meses_idioma"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                                        <option value="<?php echo $ind; ?>"
                                                <?php if ($_GET["ate_mes"] == $ind) { ?>selected="selected"<?php } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="font-size:20px; line-height: 23px; border-top: 0px;">/</td>
                            <td style="border-top: 0px;">
                                <select id="ate_ano" class="inputPreenchimentoCompleto" name="ate_ano">
                                    <?php for ($ano = $anoInicio; $ano <= $anoFim; $ano++) { ?>
                                        <option value="<?php echo $ano; ?>"
                                                <?php if ($_GET["ate_ano"] == $ano) { ?>selected="selected"<?php } ?>><?php echo $ano; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="border-top: 0px;"><input class="btn small" type="submit" value="Exibir metas">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>


                <? if ($sindicatoSelecionada) { ?>

                    Sindicato selecionada:
                    <h2><?= $sindicatoSelecionada["nome"]; ?></h2>

                    <form class="form-horizontal" enctype="multipart/form-data"
                          onsubmit="return validateFields(this, regras)" method="post">
                        <input type="hidden" id="acao" name="acao" value="salvar"/>
                        <table id="sortTableExample" class="table table-striped table-bordered" style="width:auto;">
                            <thead>
                            <tr>
                                <th style="background-color:#FFFFFF;">&nbsp;</th>
                                <?php
                                foreach ($mesesArray as $ind => $data) {
                                    ?>
                                    <th style="background-color:#FFFFFF; text-align:center;"><?php echo $data; ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="background-color:#FFFFFF;"><strong>Categorias </strong></td>
                                <? foreach ($mesesArray as $ind => $data) { ?>
                                    <td width="120" align="center" style="background-color:#FFFFFF; text-align:center;">
                                        <strong>Valor</strong></td>
                                <? } ?>
                            </tr>
                            <?php
                            foreach ($categoriasArray as $categoria) {
                                if ($categoria["tipo"] == "C") {
                                    ?>
                                    <tr>
                                        <td><?php echo $categoria["categoria"]; ?></td>


                                        <?php
                                        foreach ($mesesArray as $ind => $data) {

                                            $dataDados = explode("/", $data);
                                            $dataIndice = $dataDados[1] . "-" . $dataDados[0];
                                            $orcamento = $orcamentos[$categoria["idcategoria"]][$dataIndice];


                                            ?>
                                            <td>

                                                <? if ($orcamento["idorcamento"]) { ?>
                                                    <input
                                                        name="dados[<?= $sindicatoSelecionada["idsindicato"]; ?>][<?= $categoria["idcategoria"]; ?>][<?php echo $dataIndice; ?>][idorcamento]"
                                                        id="idorcamento_<?= $sindicatoSelecionada["idsindicato"]; ?>_<?php echo $dataIndice; ?>"
                                                        type="hidden" value="<?= $orcamento["idorcamento"]; ?>"/>

                                                <?
                                                } ?>

                                                <div class="input-prepend">
                                                    <span class="add-on">R$</span>
                                                    <input
                                                        name="dados[<?= $sindicatoSelecionada["idsindicato"]; ?>][<?= $categoria["idcategoria"]; ?>][<?php echo $dataIndice; ?>][valor]"
                                                        id="valor_<?= $sindicatoSelecionada["idsindicato"]; ?>_<?php echo $dataIndice; ?>"
                                                        type="text" value="<? if ($orcamento["valor"]) {
                                                        echo number_format($orcamento["valor"], 2, ',', '.');
                                                    } ?>" class="span1 inputValor" style="width:80px"/>
                                                </div>

                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                        <div class="form-actions">
                            <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                        </div>
                    </form>
                <? } ?>


                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script language="javascript" type="text/javascript">
        jQuery(document).ready(function ($) {

            $(".inputValor").maskMoney({symbol: "R$", decimal: ",", thousands: "."});


        });
    </script>
</div>
</body>
</html>