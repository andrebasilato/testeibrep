<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
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
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                    class="divider">/</span></li>
            <li class="active"><?php echo $linha["nome"]; ?></li>
            <span class="pull-right"
                  style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                           class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
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
                            <form method="post" onsubmit="return validateFields(this, regras)"
                                  enctype="multipart/form-data" class="form-horizontal">
                                <p><?php echo $idioma["explicacao"]; ?></p>
                                <input name="acao" type="hidden" value="salvar_valor_regra">
                                <input name="acao_url" type="hidden" value="">
                                <fieldset>
                                    <legend><?php echo $idioma["legendadadosdados"]; ?></legend>
                                    <div class="span6 well wellDestaque">
                                        <div class="span2">
                                            <label class=""
                                                   for="valor"><strong><?php echo $idioma["form_valor"]; ?></strong></label>
                                            <input class="span2" id="valor" name="valor" type="text" value=""
                                                   maxlength="15">
                                        </div>
                                        <div class="span2">
                                            <label class=""
                                                   for="porcentagem"><strong><?php echo $idioma["form_porcentagem"]; ?></strong></label>
                                            <input class="span2" id="porcentagem" name="porcentagem" type="text"
                                                   value="" maxlength="15">
                                        </div>
                                        <div class="span1">
                                            <label class="" for="btn_submit">&nbsp;</label>
                                            <input type="submit" id="btn_submit" class="btn btn-primary"
                                                   value="<?php echo $idioma["btn_adicionar"]; ?>">
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <form method="post" id="remover_valor_regra" name="remover_valor_regra">
                                <input type="hidden" id="acao" name="acao" value="remover_valor_regra">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>
                            <table class="table table-striped ">
                                <thead>
                                <tr>
                                    <th><?= $idioma["tabela_valor"]; ?></th>
                                    <th><?= $idioma["tabela_porcentagem"]; ?></th>
                                    <th width="250" style="text-align:right;"><?= $idioma["opcoes"]; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($valoresRegra) > 0) { ?>
                                    <?php foreach ($valoresRegra as $valorRegra) { ?>
                                        <tr>
                                            <td><?= number_format($valorRegra["valor"], 2, ",", "."); ?></td>
                                            <td><?= number_format($valorRegra["porcentagem"], 2, ",", "."); ?></td>
                                            <td style="text-align:right">
                                                <a href="javascript:void(0);" class="btn btn-mini"
                                                   data-original-title="<?= $idioma["btn_remover"]; ?>"
                                                   data-placement="left" rel="tooltip"
                                                   onclick="remover(<?php echo $valorRegra["idvalor"]; ?>)"><i
                                                        class="icon-remove"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4"><?= $idioma["sem_informacao"]; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script type="text/javascript">
        var regras = new Array();
        regras.push("required,valor,valor_vazio");
        regras.push("required,porcentagem,porcentagem_vazio");

        jQuery(document).ready(function ($) {
            $("#valor").maskMoney({symbol: "R$", decimal: ",", thousands: ".", allowZero: true});
            $("#porcentagem").maskMoney({symbol: "R$", decimal: ",", thousands: ".", allowZero: true});

        });
    </script>
    <script type="text/javascript">
        function remover(id) {
            confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
            if (confirma) {
                document.getElementById("remover").value = id;
                document.getElementById("remover_valor_regra").submit();
            }
        }
    </script>
</div>
</body>
</html>