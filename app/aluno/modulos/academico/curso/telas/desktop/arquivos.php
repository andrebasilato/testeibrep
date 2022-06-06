<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <!-- Box -->
        <div class="box-side box-bg">
            <span class="top-box box-amarelo">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-copy"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12 abox extra-align">
                    <?php if (count($pastas)) { ?>
                        <a href="javascript:history.back()" class="link_voltar">
                            Voltar para a página anterior...</a> <br><br>
                    <?php } ?>
                    <?php foreach ($pastas as $pasta) { ?>
                        <div class="row-fluid">
                            <div class="span6 download-box">
                                <div class="title-download">
                                    <h1><?php echo $pasta['nome']; ?></h1>
                                </div>
                                <?php foreach ($pasta['arquivos'] as $arquivo) { ?>
                                    <div class="container-download">
                                        <div class="archive-donwload">
                                            <p><?php echo $arquivo['nome']; ?></p>
                                            <p><i><?php echo tamanhoArquivo($arquivo['arquivo_tamanho']); ?></i></p>
                                        </div>
                                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $arquivo['iddownload']; ?>/download">
                                            <div class="btn btn-verde btn-download"><?php echo $idioma['baixar']; ?></div>
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (count($pastas)) { ?>
                        <a href="javascript:history.back()" class="link_voltar">
                        Voltar para a página anterior...</a> <br><br>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>
