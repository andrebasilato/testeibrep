<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css"
          media="screen"/>
    <!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
       <link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
    <style type="text/css">
        .tituloEdicao {
            font-size: 45px;
        }
        legend {
            line-height: 25px;
            margin-bottom: 5px;
            margin-top: 20px;
        }
        .botao {
            height: 100px;
            margin-top: 15px;
            padding-bottom: 0px;
            float: left;
            padding-top: 40px;
            height: 58px;
            text-transform: uppercase;
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
        .div-redonda {
            top: -166px;
            right: -8px;
            float: right;
            position: relative;
            z-index: 99999 !important;
            border-radius: 50%;
            background-position: -15px -15px;
            text-align: center !important;
            height: 30px;
            width: 30px;
            font-family: arial;
            font-size: 12px;
            font-weight: bold;
            line-height: 30px;
        }
        .div-redonda-red {
            background-color: #8B0000;
            color: #FFF;
            font-weight: bold;
        }
        .div-redonda-green {
            background-color: #006400;
            color: #FFF;
            font-weight: bold;
        }
        .flex {
            display: flex;
        }
        .flex-wrap {
            flex-wrap: wrap;
        }
        .row {
            flex-direction: row;
        }
        .row-reverse {
            flex-direction: row-reverse;
        }
        .column {
            flex-direction: column;
        }
        .column-reverse {
            flex-direction: column-reverse;
        }
        /* Flex Container */
        .container {
            width: 100%;
            margin: 0 auto;
            display: flex;
            text-align: left;
        }
        span.datavalid-label {
            font-size: 11px !important;
            font-family: Verdana, Geneva, sans-serif !important;
        }
        /* Flex Item */
        .item-flex {
            /* O flex: 1; é necessário para que cada item se expanda ocupando o tamanho máximo do container. */
            z-index: 10 !important;
            padding: 5px;
            margin: 5px;
            background: #EEE;
            text-align: left;
            font-size: 1.5em;
            width: 190px;
            max-width: 190px;
            max-height: 172px;
            height: 172px;
            border: 1px solid #cccccc;
        }
        div.square {
            width: 10px;
            height: 10px;
        }
    </style>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span
                        class="divider">/</span></li>
            <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span
                        class="divider">/</span></li>
            <li>
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a>
                <span class="divider">/</span>
            </li>
            <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span>
            </li>
            <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
            <span class="pull-right"
                  style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo" style="padding:20px">
                <div class=" pull-right">
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i
                                class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
                </div>
                <table border="0" cellspacing="0" cellpadding="15">
                    <tr>
                        <td style="padding:0px;" valign="top"><img
                                    src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>"
                                    class="img-circle"></td>
                        <td style="padding: 0px 0px 0px 8px;" valign="top">
                            <h2
                                    class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
                                <br/>
                                <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
                            </h2>
                        </td>
                    </tr>
                </table>
                <? incluirTela("administrar.menu", $config, $matricula); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if ($mensagem["erro"]) { ?>
                            <div class="alert alert-error">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <?= $idioma[$mensagem["erro"]]; ?>
                            </div>
                            <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
                        <? } ?>
                        <? if ($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                            <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
                        <? } ?>
                        <section>
                            <legend data-abrefecha="historico_reserva_ancora_div" style="background-color: #EEEEEE;">
                                BIOMETRIA (DATAVALID)
                            </legend>
                            <div id="historico_reserva_ancora_div">
                                <br><span class="label label-info">FOTOS ENVIADAS PARA IMAGEM PADRÃO</span><br>
                                <section class="container flex flex-wrap" style="text-align:left;">
                                    <?php if (is_array($imagensPriDV)) { ?>
                                        <?php foreach ($imagensPriDV as $key => $value) { ?>
                                            <div class="modal fade" id="<?php echo $value['idfoto']; ?>Modal"
                                                 tabindex="-1" role="dialog"
                                                 aria-labelledby="<?php echo $value['idfoto']; ?>ModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="<?php echo $value['idfoto']; ?>ModalLabel">JSON
                                                                FOTO
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                            if (!empty($value['json']) && $value['json'] !== null && $value['json'] != 'null') {
                                                                print_r2(json_encode(json_decode($value['json']), JSON_PRETTY_PRINT));
                                                            } else {
                                                                echo "Essa imagem não tem JSON disponivel";
                                                            } ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fechar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-flex">
                                                <?php if (property_exists(json_decode($value['json'])->biometria, 'disponivel') && (json_decode($value['json'])->biometria->disponivel) === false) { ?>
                                                    <a href="#"
                                                       data-toggle="modal"
                                                       data-target="#<?php echo $value['idfoto']; ?>"><img
                                                                src="../../../../../../assets/img/avatar.jpg"
                                                                alt=""
                                                                style="display: block; margin: 0 auto;"></a>
                                                <? } else { ?>
                                                    <a href="#" data-toggle="modal"
                                                       data-target="#<?php echo $value['idfoto']; ?>Modal"><img
                                                                src="<?= $config['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/x/x/' . $value['foto']; ?>"
                                                                alt=""></a>
                                                <? } ?>
                                                <span class="datavalid-label">
                                                    <?php echo "DATA: " . formataData($value['data_cad'], 'br', 1); ?><br>
                                                    <?php echo "IP ORIG: " . $value['ip']; ?>
                                                </span>
                                                <div class="div-redonda <?php echo ($value['probabilidade_datavalid'] >= $GLOBALS['config']['datavalid']['probabDefault']) ? 'div-redonda-green' : 'div-redonda-red'; ?>"><?php echo($value['probabilidade_datavalid'] * 100); ?>
                                                    %
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </section>
                                <br style="clear: both;" />
                                <?php if (is_array($comparacoesDV) && !empty($comparacoesDV)) { ?>
                                    <span class="label label-info">FOTOS ENVIADAS PARA AVALIAÇÃO</span><br>
                                    <section class="container flex flex-wrap" style="text-align:left;padding-bottom: 10px;">
                                        <?php foreach ($comparacoesDV as $key => $value) { ?>
                                            <div class="modal fade" id="<?php echo $value['idfoto']; ?>ComparacoesModal"
                                                 tabindex="-1" role="dialog"
                                                 aria-labelledby="<?php echo $value['idfoto']; ?>ComparacoesModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="<?php echo $value['idfoto']; ?>ComparacoesModalLabel">
                                                                JSON FOTO
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                            if (!empty($value['json']) && $value['json'] !== null && $value['json'] != 'null') {
                                                                print_r2(json_encode(json_decode($value['json']), JSON_PRETTY_PRINT));
                                                            } else {
                                                                echo "Essa imagem não tem JSON disponivel";
                                                            } ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fechar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-flex">
                                                <?php if (property_exists(json_decode($value['json'])->biometria, 'disponivel') && (json_decode($value['json'])->biometria->disponivel) === false) { ?>
                                                    <a href="#" data-toggle="modal" data-target="#<?php echo $value['idfoto']; ?>"><img src="../../../../../../assets/img/avatar.jpg" alt="" style="display: block; margin: 0 auto;"></a>
                                                <? } else { ?>
                                                    <a href="#" data-toggle="modal" data-target="#<?php echo $value['idfoto']; ?>ComparacoesModal"><img src="<?= $config['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/x/x/' . $value['foto']; ?>" alt=""></a>
                                                <? } ?>
                                                <span class="datavalid-label">
                                                    <?php echo "DATA: " . formataData($value['data_cad'], 'br', 1); ?><br>
                                                    <?php echo "IP ORIG: " . $value['ip']; ?>
                                                </span>
                                                <div class="div-redonda <?php echo ($value['probabilidade_datavalid'] >= $GLOBALS['config']['datavalid']['probabDefault']) ? 'div-redonda-green' : 'div-redonda-red'; ?>"><?php echo($value['probabilidade_datavalid'] * 100); ?>
                                                    %
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </section>
                                <?php } ?>
                                <br style="clear: both;" />
                            </div>
                            <legend data-abrefecha="historico_reserva_ancora_div">
                                FOTO PADRÃO DA MATRÍCULA (AZURE)
                            </legend>
                            <div id="historico_reserva_ancora_div">
                                <img src="<?= $config['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/x/x/' . $imagemPrincipal['foto']; ?>"
                                     style="max-width:380px;" alt="">
                                <?php if (!empty($imagemPrincipal)) { ?>
                                    <a href="javascript:void(0);"
                                       onclick="removerFotoPrincpal(<?= $imagemPrincipal['idfoto']; ?>);">
                                        <img src="/assets/img/remover_16x16.gif" width="16" height="16" border="0">
                                    </a>
                                    <div style="text-align: left; width: 480px;">
                                        <?= formataData($imagemPrincipal['data_cad'], 'br', 1); ?> - <strong> IP de
                                            Origem: </strong>
                                        <?= $imagemPrincipal['ip'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>
                        <section>
                            <legend data-abrefecha="historico_reserva_ancora_div" style="background-color: #EEEEEE;">
                                COMPARATIVOS DA BIOMETRIA (AZURE)
                            </legend>
                            <div id="historico_reserva_ancora_div"><?php foreach ($todasComparacoes as $key => $value) {
                                $imagemPri = $config['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/x/x/' . $value['principal'];
                                $imagemCom = $config['urlSistema'] . '/api/get/imagens/matriculas_comparacoes_fotos/x/x/' . $value['foto_comparada_azure'];
                                if ($value['confidence'] >= $config['reconhecimento']['range_minimo']) {
                                    $color = 'green';
                                    $font = '#FFFFFF';
                                } else {
                                    $color = 'yellow';
                                    $font = '#000000';
                                }
                                ?>
                                <div class="modal fade"
                                     id="<?php echo $value['idreconhecimento']; ?>reconhecimentoModal" tabindex="-1"
                                     role="dialog"
                                     aria-labelledby="<?php echo $value['idreconhecimento']; ?>reconhecimentoModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="<?php echo $value['idreconhecimento']; ?>reconhecimentoModalLabel">
                                                    JSON FOTO
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                if (!empty($value['json']) && $value['json'] !== null && $value['json'] != 'null') {
                                                    print_r2(json_decode($value['json']));
                                                } else {
                                                    echo "Essa imagem não tem JSON disponivel";
                                                } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Fechar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid" style="margin-bottom: 40px;">
                                <div style="float: left; text-align: left; width: 380px; height: 260px;">
                                    <a href="#" data-toggle="modal" data-target="#<?php echo $value['idfoto']; ?>Modal">
                                        <img style="float: left; margin-bottom: 10px;max-width:380px;"
                                             src="<?= $config['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/x/x/' . $value['principal']; ?>"
                                             alt="">
                                    </a>
                                    <?= formataData($value['dt_principal'], 'br', 1); ?> -
                                    <strong>IP de Origem: </strong> <?= $imagemPrincipal['ip'] ?>
                                </div>
                                <div style="float: left; width: 380px; height: 360px;">
                                    <a href="#" data-toggle="modal"
                                       data-target="#<?php echo $value['idreconhecimento']; ?>reconhecimentoModal">
                                        <div style="float: left; width: 90px; height: 90px; background-color: <?= $color; ?>; border-radius: 90px; margin-left: 133px; margin-top: 133px; border: 1px solid;">
                                            <p style="font-size: 25px; text-align: center; margin-top: 36px; color: <?= $font; ?>;"><?= $value['confidence']; ?></p>
                                        </div>
                                </div>
                                <div style="float: left; text-align: left; width: 380px; heigth: 260px;">
                                    <a href="#" data-toggle="modal"
                                       data-target="#<?php echo $value['idfoto']; ?>ComparacoesModal">
                                        <img style="float: left; margin-bottom: 10px;max-width:380px;"
                                             src="<?= $config['urlSistema'] . '/api/get/imagens/matriculas_comparacoes_fotos/x/x/' . $value['foto_comparada_azure']; ?>"
                                             alt="">
                                    </a>
                                    <?= formataData($value['data_cad'], 'br', 1); ?> -
                                    <strong>IP de Origem: </strong> <?= $value['ip'] ?>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </section>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
</div>
<?php incluirTela("cabecalho_info", $config, $matricula); ?>
<script type="text/javascript">
    function removerFotoPrincpal(id) {
        var msg = "Você deseja realmente remover essa imagem principal?";
        var confirma = confirm(msg);
        if (confirma) {
            document.getElementById('idimagem').value = id;
            document.getElementById('form_remover_imagem').submit();
            return true;
        } else {
            return false;
        }
    }
</script>
<form method="post" id="form_remover_imagem" action="" style="padding-top:15px;">
    <input name="acao" type="hidden" value="remover_imagem"/>
    <input name="idimagem" id="idimagem" type="hidden" value=""/>
</form>
</body>
</html>