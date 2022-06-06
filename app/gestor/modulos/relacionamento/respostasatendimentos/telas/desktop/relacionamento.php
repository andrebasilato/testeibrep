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
        <div class="span12">
            <div class="box-conteudo">
                <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                            class="btn btn-small"><i
                            class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
                <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>

                <div class="tabbable tabs-left">
                    <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                    <div class="tab-content">

                    <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                        <div class="tab-pane active" id="tab_editar">
                            <? if ($_SESSION["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_SESSION["msg"]];
                                        unset($_SESSION["msg"]); ?></strong>
                                </div>
                            <? } ?>

                            <div class="page-header">
                                <label class="control-label"><strong>Lista de Assuntos:</strong></label>
                                <div class="controls">
                                    <label class="checkbox">
                                        Todos <input id="todos" name="todos" value="1" type="checkbox">
                                    </label>
                                </div>
                            </div>

                            <form method="post" action="" class="form-horizontal">
                                <input type="hidden" name="acao" value="associar"/>
                                <?php $linhaObj->gerarFormulario('formulario_relacoes', null, $idioma); ?>
                                <div class="form-actions">
                                    <input type="submit" value="Salvar" class="btn btn-primary"/>
                                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["cancelar"]; ?>" />

                                </div>
                            </form>
                        </div>
                        <?php echo $javascript; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape", $config, $usuario); ?>
    <script type="application/javascript">
        jQuery("#todos").on('click', function(){
            if($(this).attr('checked') == 'checked') {
                jQuery('.form-horizontal input').attr('checked', 'checked');
                return;
            }
            jQuery('.form-horizontal input').removeAttr('checked');
            return;
        });
    </script>
</div>
</body>
</html>