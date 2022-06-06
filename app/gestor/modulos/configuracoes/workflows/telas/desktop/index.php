<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small class="hidden-phone"><?= $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
            <?php if ($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
            <span class="pull-right visible-desktop" style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?>  <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12">
                    <div class="box-conteudo">
                        <div id="listagem_informacoes">
                            <div class="pull-right blocoFiltroFuncionalidade">
                                <input type="text" id="filtroFuncionalidade" class="span3 search-query" placeholder="Escreva o que procura...">
                            </div>
                            <?= $idioma["pagina_subtitulo"]; ?>
                        </div>
                        <div class="row-fluid">
                            <?php
                            foreach ($config['workflows'] as $ind => $workflow) {
                                ?>
                                <div class="span2 blocoFuncionalidade">
                                    <a href="/gestor/configuracoes/workflows/<?= $ind; ?>" class="filtroBloco">
                                        <div class="blocoImagem"><img src="/assets/icones/preto/32/workflows_32.png"></div>
                                        <div class="blocoLink filtroTexto">
                                            <?= strtoupper($workflow["titulo"]); ?>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
    <script type="text/javascript" src="/assets/plugins/jcFilter/jcfilter.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("#filtroFuncionalidade").jcOnPageFilter({animateHideNShow: true,
                focusOnLoad: true,
                highlightColor: 'yellow',
                textColorForHighlights: '#000000',
                caseSensitive: false,
                hideNegatives: true,
                parentLookupClass: 'filtroBloco',
                childBlockClass: 'filtroTexto'});
        });
    </script>
</div>
</body>
</html>