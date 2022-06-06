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
                    <table id="sortTableExample" class="table table-striped tabelaSemTamanho">
                        <tbody>
                        <tr>
                            <td width="60">
                                <select id="de_mes" class="inputPreenchimentoCompleto" name="de_mes"
                                        style="width:100px">
                                    <?php foreach ($GLOBALS["meses_idioma"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                                        <option value="<?php echo $ind; ?>"
                                                <?php if ($_GET["de_mes"] == $ind) { ?>selected="selected"<?php } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="font-size:20px; line-height: 23px;">/</td>
                            <td>
                                <select id="de_ano" class="inputPreenchimentoCompleto" name="de_ano">
                                    <?php for ($ano = $anoInicio; $ano <= $anoFim; $ano++) { ?>
                                        <option value="<?php echo $ano; ?>"
                                                <?php if ($_GET["de_ano"] == $ano) { ?>selected="selected"<?php } ?>><?php echo $ano; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td width="20" style="line-height: 28px;">até</td>
                            <td width="60">
                                <select id="ate_mes" class="inputPreenchimentoCompleto" name="ate_mes"
                                        style="width:100px">
                                    <?php foreach ($GLOBALS["meses_idioma"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>
                                        <option value="<?php echo $ind; ?>"
                                                <?php if ($_GET["ate_mes"] == $ind) { ?>selected="selected"<?php } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="font-size:20px; line-height: 23px;">/</td>
                            <td>
                                <select id="ate_ano" class="inputPreenchimentoCompleto" name="ate_ano">
                                    <?php for ($ano = $anoInicio; $ano <= $anoFim; $ano++) { ?>
                                        <option value="<?php echo $ano; ?>"
                                                <?php if ($_GET["ate_ano"] == $ano) { ?>selected="selected"<?php } ?>><?php echo $ano; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><input class="btn small" type="submit" value="Exibir competências"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <form class="form-horizontal" enctype="multipart/form-data"
                      onsubmit="return validateFields(this, regras)" method="post">
                    <input type="hidden" id="acao" name="acao" value="salvar"/>
                    <?php
                    $datepicker = "";
                    foreach ($competencias["sindicatos"] as $sindicato) { //print_r2($sindicato);
                        ?>
                        <h2 class="tituloOpcao"><?php echo $sindicato["nome_abreviado"]; ?></h2>
                        <table id="sortTableExample" class="table table-striped" style="width:auto;">
                            <thead>
                            <tr>
                                <th style="background-color:#FFFFFF;">&nbsp;</th>
                                <?php
                                $dataInicio = date("m/Y", mktime(0, 0, 0, $_GET["de_mes"], 1, $_GET["de_ano"]));
                                $dataFim = date("m/Y", mktime(0, 0, 0, $_GET["ate_mes"] + 1, 1, $_GET["ate_ano"]));
                                $deMes = $_GET["de_mes"];
                                for ($data = $dataInicio; $data != $dataFim; $data = date("m/Y", mktime(0, 0, 0, ++$deMes, 1, $_GET["de_ano"]))) {
                                    ?>
                                    <th style="background-color:#FFFFFF; text-align:center;"><?php echo $data; ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="pull-right">
                                        <div class="label-de">
                                            <strong><?php echo $idioma["de"]; ?></strong>
                                        </div>
                                        <div class="label-ate">
                                            <strong><?php echo $idioma["ate"]; ?></strong>
                                        </div>
                                    </div>
                                    <strong><?php echo $idioma["data_competencia"]; ?></strong>
                                </td>
                                <?php
                                $dataInicio = date("Y-m-d", mktime(0, 0, 0, $_GET["de_mes"], 1, $_GET["de_ano"]));
                                $dataFim = date("Y-m-d", mktime(0, 0, 0, $_GET["ate_mes"] + 1, 1, $_GET["ate_ano"]));
                                $deMes = $_GET["de_mes"];
                                for ($data = $dataInicio; $data != $dataFim; $data = date("Y-m-d", mktime(0, 0, 0, ++$deMes, 1, $_GET["de_ano"]))) {
                                    $proximaData = date("Y-m-d", mktime(0, 0, 0, $deMes + 1, 1, $_GET["de_ano"]));

                                    $datepicker .= "$(\"#data_de_" . $sindicato["idsindicato"] . "_" . $data . "\").datepicker({
                        currentText: 'Now',			
                        onSelect: function(theDate){				
                          $(\"#data_ate_" . $sindicato["idsindicato"] . "_" . $data . "\").datepicker(\"option\", \"minDate\", theDate)
                          $(\"#data_ate_" . $sindicato["idsindicato"] . "_" . $data . "\").datepicker({
                            currentText: 'Now', 
                            dateFormat: 'dd/mm/yy'
                          })			
                        }
                      });";
                                    $datepicker .= "$(\"#data_ate_" . $sindicato["idsindicato"] . "_" . $data . "\").datepicker({
                        currentText: 'Now',			
                        onSelect: function(theDate){				
                          $(\"#data_de_" . $sindicato["idsindicato"] . "_" . $proximaData . "\").datepicker(\"option\", \"minDate\", theDate)
                          $(\"#data_de_" . $sindicato["idsindicato"] . "_" . $proximaData . "\").datepicker({
                            currentText: 'Now', 
                            dateFormat: 'dd/mm/yy'
                          })			
                        }
                      });";
                                    ?>
                                    <td style="border-left: 1px solid #ddd;">
                                        <div class="campo-de">
                                            <input
                                                id="data_de_<?php echo $sindicato["idsindicato"] . '_' . $data; ?>"
                                                class="span2 data" type="text"
                                                value="<?php echo formataData($sindicato["competencias"][$data]["de"], "br", 0); ?>"
                                                name="sindicato[<?php echo $sindicato["idsindicato"]; ?>][competencias][<?php echo $data; ?>][de]"
                                                style="width: 90px;">
                                        </div>
                                        <div class="campo-ate">
                                            <input
                                                id="data_ate_<?php echo $sindicato["idsindicato"] . '_' . $data; ?>"
                                                class="span2 data" type="text"
                                                value="<?php echo formataData($sindicato["competencias"][$data]["ate"], "br", 0); ?>"
                                                name="sindicato[<?php echo $sindicato["idsindicato"]; ?>][competencias][<?php echo $data; ?>][ate]"
                                                style="width: 90px;">
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td style="background-color:#FFFFFF;"><strong><?php echo $idioma["curso"]; ?></strong>
                                </td>
                                <td style="background-color:#FFFFFF;" colspan="10">
                                    <strong><?php echo $idioma["regra_competencia"]; ?></strong></td>
                            </tr>
                            <?php
                            foreach ($sindicato["cursos"] as $curso) {
                                ?>
                                <tr>
                                    <td><?php echo $curso["nome"]; ?></td>
                                    <?php
                                    $deMes = $_GET["de_mes"];
                                    for ($data = $dataInicio; $data != $dataFim; $data = date("Y-m-d", mktime(0, 0, 0, ++$deMes, 1, $_GET["de_ano"]))) {
                                        ?>
                                        <td style="border-left: 1px solid #ddd;">
                                            <div class="control-group">
                                                <div class="controls span2" style="margin-left:0px;">
                                                    <select id="regra_<?php echo $data; ?>"
                                                            class="inputPreenchimentoCompleto"
                                                            name="sindicato[<?php echo $sindicato["idsindicato"]; ?>][cursos][<?php echo $curso["idcurso"]; ?>][<?php echo $data; ?>]">
                                                        <option value=""></option>
                                                        <?php foreach ($curso["regras"] as $regra) { ?>
                                                            <option value="<?php echo $regra["idregra"]; ?>"
                                                                    <?php if ($curso["competencias"][$data] == $regra["idregra"]) { ?>selected="selected"<?php } ?>><?php echo $regra["nome"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <hr/>
                    <?php } ?>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    </div>
                </form>
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
            $(".data").mask("99/99/9999");
            <?php echo $datepicker; ?>
        });
    </script>
</div>
</body>
</html>