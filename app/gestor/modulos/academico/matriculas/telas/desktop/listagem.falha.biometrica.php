<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
            <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo">
                <div id="listagem_informacoes">
                    <? printf($idioma["informacoes"],$reconhecimentoObj->Get("total")); ?>
                    <br />
                    <? printf($idioma["paginas"],$reconhecimentoObj->Get("pagina"),$reconhecimentoObj->Get("paginas")); ?>
                </div>
                <?php $matriculaObj->GerarTabela($dadosArray,$_GET["q"],$idioma, 'listagem_falha_biometrica'); ?>
                <div id="listagem_form_busca">
                    <div class="input">
                        <div class="inline-inputs">
                            <?= $idioma["registros"]; ?>
                            <form action="" method="get" id="formQtd">
                                <? if($_GET["buscarpor"] && $_GET["buscarem"]) { ?>
                                    <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET["buscarpor"]; ?>">
                                    <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET["buscarem"]; ?>">
                                <? }
                                if(is_array($_GET["q"])){
                                    foreach($_GET["q"] as $ind => $valor){?>
                                        <input id="q[<?=$ind?>]" type="hidden" value="<?=$valor;?>" name="q[<?=$ind?>]" />
                                    <? }
                                } ?>
                                <input id="cmp" type="hidden" value="<?= $matriculaObj->Get("ordem_campo"); ?>" name="Cmp" />
                                <? if($_GET["ord"]){?>
                                    <input id="ord" type="hidden" value="<?=$_GET["ord"];?>" name="ord" />
                                <? } ?>
                                <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?= $matriculaObj->Get("limite"); ?>" />
                                <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?= $idioma["exibir"]; ?></a>
                            </form>
                        </div>
                    </div>
                </div>
                <? if($matriculaObj->Get("paginas") > 1) {
                    if(!$_GET["ord"]) $_GET["ord"] = "desc";
                    $matriculaObj->Set("ordem",$_GET["ord"]);
                    if(!$_GET["qtd"]) $_GET["qtd"] = 30;
                    $matriculaObj->Set("limite",intval($_GET["qtd"]));
                    if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
                    $matriculaObj->Set("ordem_campo",$_GET["cmp"]);
                    ?>
                    <div class="pagination"><ul><?= $reconhecimentoObj->GerarPaginacao($idioma); ?></ul></div>
                <? } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script language="javascript" type="text/javascript">
        jQuery(document).ready(function($) {
            $(".select231").select2();
            $("#qtd").keypress(isNumber);
            $("#qtd").blur(isNumberCopy);
            $("#q[3|m.data_cad]").mask("99/99/9999>");
        });
    </script>
</div>
</body>
</html>
