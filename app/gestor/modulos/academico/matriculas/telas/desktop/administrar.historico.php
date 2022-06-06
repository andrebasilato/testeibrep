<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
    <style type="text/css">

        .tituloEdicao {
            font-size:45px;
        }
        legend {
            line-height:25px;
            margin-bottom: 5px;
            margin-top: 20px;
        }
        .botao {
            height:100px;
            margin-top: 15px;
            padding-bottom:0px;
            float:left;
            padding-top:40px;
            height:58px;
            text-transform:uppercase;
        }
        legend {
            background-color: #F4F4f4;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            padding: 5px 5px 5px 15px;
            width: 98%;
        }


        legend span {
            font-size: 9px;
            float: right;
            margin-right: 15px;
            color: #999;
        }



    </style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo" style="padding:20px">

                <div class=" pull-right">
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
                </div>



                <table border="0" cellspacing="0" cellpadding="15">
                    <tr>
                        <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
                        <td style="padding: 0px 0px 0px 8px;" valign="top">        <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
                                <br />
                                <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
                            </h2></td>
                    </tr>
                </table>


                <? incluirTela("administrar.menu",$config,$matricula); ?>


                <div class="row-fluid">


                    <div class="span12">

                        <?php if($mensagem["erro"]) { ?>
                            <div class="alert alert-error">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <?= $idioma[$mensagem["erro"]]; ?>
                            </div>
                            <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
                        <? } ?>
                        <? if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                            <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
                        <? } ?>


                        <section id="historicomatricula">

                            <legend data-abrefecha="historico_reserva_ancora_div">
                                <?=$idioma["label_historico_matricula"];?>
                            </legend>
                            <div id="historico_reserva_ancora_div">
                                <?php echo $matriculaObj->retornarHistoricoTabela($matricula["historicos"], $idioma); ?>
                            </div>

                        </section>




                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
</div>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>
