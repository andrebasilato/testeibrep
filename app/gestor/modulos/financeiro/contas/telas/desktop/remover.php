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
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span9">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
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
                            <form method="post" action="" class="form-horizontal">
                                <?php if($linha["fatura"] != "S"){ ?>
                                    <input name="acao" type="hidden" value="remover"/>
                                <?php }elseif($linha["fatura"] == "S"){?>
                                    <input name="acao" type="hidden" value="removerFaturas"/>
                                    <input name="data_fatura" type="hidden" value="<?=$linha["data_vencimento"]?>"/>
                                <?php } ?>
                                <div class="control-group">
                                    <p>
                                        <? printf($idioma["usuario_selecionado"], $linha["nome"], $linha[$config["banco"]["primaria"]]); ?>
                                        <br/>
                                        <br/>
                                        <?= $idioma["informacoes"]; ?> <br/>
                                    </p>
                                    <label class="control-label"
                                           for="optionsCheckboxList"><strong><?= $idioma["motivo"]; ?></strong></label>

                                    <div class="controls">
                                        <select name="idmotivo" id="idmotivo">
                                            <option value=""></option>
                                            <?php foreach ($motivosCancelamento as $motivosCancelamento) { ?>
                                                <option
                                                    value="<?php echo $motivosCancelamento['idmotivo']; ?>"><?php echo $motivosCancelamento['nome']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="control-label"
                                           for="optionsCheckboxList"><strong><?= $idioma["confirmacao"]; ?></strong></label>

                                    <div class="controls">
                                        <label class="checkbox">
                                            <input name="remover" value="<?= $linha[$config["banco"]["primaria"]]; ?>"
                                                   type="checkbox" id="remover">
                                            <?= $idioma["confirmacao_formulario"]; ?>
                                        </label>

                                        <p class="help-block"><?= $idioma["nota"]; ?></p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <?php if ($linha['cancelada'] == 'S') { ?>
                                        <div class="alert alert-error fade in">
                                            <a href="javascript:void(0);" data-dismiss="alert"></a>
                                            <?php echo $idioma['conta_cancelada']; ?>
                                        </div>
                                    <?php } else { ?>
                                        <?php if($linha["fatura"] != "S"){ ?>
                                        <input type="submit" class="btn btn-primary"
                                               value="<?= $idioma["remover"]; ?>">&nbsp;
                                        <button type="reset" class="btn"
                                                onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><?= $idioma["cancelar"]; ?></button>
                                        <?php }elseif($linha["fatura"] == "S"){?>
                                            <div class="alert alert-error fade in">
                                                <a href="javascript:void(0);" data-dismiss="alert"></a>
                                                <?php echo $idioma["aviso_fatura"]; ?>
                                            </div>
                                            <input type="submit" class="btn btn-primary"
                                                   value="<?= $idioma["remover_fatura"]; ?>">&nbsp;
                                            <button type="reset" class="btn"
                                                    onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><?= $idioma["cancelar"]; ?></button>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <div class="well">
                    <?= $idioma["nav_cadastrar_explica"]; ?>
                    <br/>
                    <br/>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar"
                       class="btn primary"><?= $idioma["nav_cadastrar"]; ?></a>
                </div>
            <? } ?>
            <?php incluirLib("sidebar_" . $url[1], $config); ?>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
</div>
</body>
</html>