<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usu_vendedor); ?>
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
<?php incluirLib("topo",$config,$usu_vendedor); ?>
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


                        <section id="declaracoesmatricula">
                            <legend><?=$idioma["label_declaracoes_matricula"];?></legend>
                            <? if($matricula["situacao"]["visualizacoes"][79]) { ?>

                                <script type="text/javascript">
                                    function atualizaDeclaracaoGet() {
                                        var declaracao = document.getElementById("iddeclaracao").options[document.getElementById("iddeclaracao").selectedIndex].value;
                                        array_get = declaracao.split("|");
                                        link_var = "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/gerardeclaracao/"+array_get[0]+"/"+array_get[1];
                                        document.getElementById('iframe_declaracao_pre').src = link_var;
                                    }
                                    function alterarSituacaoVisibilidadeDeclaracao(tipo, idmatriculadeclaracao){
                                        document.getElementById('idmatriculadeclaracao').value = idmatriculadeclaracao;
                                        document.getElementById('situacao_alteracao').value = tipo;
                                        document.getElementById('form_alterar_visibilidade_declaracao').submit();
                                    }
                                </script>
                            <?php

                            $matriculaObj->id = $matricula["idmatricula"];
                            $acessoAva = $matriculaObj->retornarAcessoAva();

                            $matricula['contas'] = $matriculaObj->RetornarContas();
                            $valido = true;

                            foreach ($matricula['contas'] as $contas) {
                                foreach($contas as $conta){
                                    $data_vencimento_banco = formataData($conta["data_vencimento"],'en',0);
                                    if ($conta["situacao_cancelada"] == 'N' && $conta["situacao_renegociada"] == 'N' && $conta["situacao_transferida"] == 'N' && $conta["situacao_paga"] == 'N') {
                                        if (date('Y-m-d') > $data_vencimento_banco){
                                            $valido = false;
                                        }
                                    }
                                }
                            }

                            if(!$acessoAva['pode_acessar_ava']) {
                                $valido = false;
                            }

                            if($valido){
                            ?>
                                <div id="gerardeclaracao" style="display:none">
                                    <iframe id="iframe_declaracao_pre" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/gerardeclaracao" width="600" height="500" frameborder="0"></iframe>
                                </div>
                                <div style="float:left">
                                    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                                        <tr>
                                            <td bgcolor="#F4F4F4"><strong><?=$idioma["declaracoes_matricula_declaracao"];?></strong></td>
                                            <td bgcolor="#F4F4F4">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="iddeclaracao" id="iddeclaracao" class="span3" <? if($matricula["situacao"]["visualizacoes"][79]) { ?>onchange="atualizaDeclaracaoGet()"<? } else { ?>disabled="disabled"<? } ?>>
                                                    <option value=""></option>
                                                    <? foreach($declaracoes as $declaracao) { ?>
                                                        <option value="<?= $declaracao["tipo"]; ?>|<?= $declaracao["iddeclaracao"]; ?>"><?= $declaracao["nome"]; ?></option>
                                                    <? } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <? if($matricula["situacao"]["visualizacoes"][79]) { ?>
                                                    <a class="btn btn-mini" id="btn_declaracao_pre" href="#gerardeclaracao" rel="facebox" ><?php echo $idioma["btn_gerar"]; ?></a>
                                                <? } else { ?>
                                                    <span class="btn btn-mini" disabled="disabled" data-placement="right" data-original-title="<?= $idioma['sem_permissao']; ?>" rel="tooltip" ><?= $idioma["btn_adicionar"];?></span>
                                                <? } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            <? }
                            ?>
                            <div style="clear:both"></div>
                            <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                                <tr>
                                    <td bgcolor="#F4F4F4"><strong><?=$idioma["declaracoes_matricula_numero"];?></strong></td>
                                    <td bgcolor="#F4F4F4"><strong><?=$idioma["declaracoes_matricula_tipo"];?></strong></td>
                                    <td bgcolor="#F4F4F4"><strong><?=$idioma["declaracoes_matricula_nome"];?></strong></td>
                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["declaracoes_legenda_visibilidade"] ?></strong></td>
                                    <td bgcolor="#F4F4F4">&nbsp;</td>
                                </tr>
                                <?
                                if(count($matricula["declaracoes"]) > 0) {
                                    foreach($matricula["declaracoes"] as $declaracao){
                                        ?>
                                        <tr>
                                            <td><?= $declaracao["idmatriculadeclaracao"]; ?></td>
                                            <td><?= $declaracao["tipo"]; ?></td>
                                            <td>
                                                <?
                                                if($declaracao["declaracao"]) {
                                                    $nomeDeclaracao = $declaracao["declaracao"];
                                                } else {
                                                    $nomeDeclaracao = $declaracao["arquivo"];
                                                }
                                                echo $nomeDeclaracao;
                                                ?>
                                                <br />
                                                <span style="color:#999999">
                          <? if($declaracao["declaracao"]) { echo $idioma["declaracoes_matricula_gerado_dia"]; } else { echo $idioma["declaracoes_matricula_enviado_dia"]; } ?>
                          <?= formataData($declaracao["data_cad"],'br',1); ?>
                        </span>
                                            </td>
                                            <td align="center" valign="middle" style="text-align:center; padding-top: 12px;">
                                                <?php if ($declaracao["aluno_visualiza"] == 'S') { ?>
                                                    <a id="botao_aluno_visualiza_declaracao" class="btn btn-mini btn-success" style="color:#FFF" href="javascript:void(0);" onclick="alterarSituacaoVisibilidadeDeclaracao('N',<?= $declaracao['idmatriculadeclaracao'] ?>);" >
                                                        <?= $idioma["btn_visualizar_sim"]; ?>
                                                    </a>
                                                <?php } else { ?>
                                                    <a id="botao_aluno_visualiza_declaracao" class="btn btn-mini btn-danger" style="color:#FFF" href="javascript:void(0);" onclick="alterarSituacaoVisibilidadeDeclaracao('S',<?= $declaracao['idmatriculadeclaracao'] ?>);" >
                                                        <?= $idioma["btn_visualizar_nao"]; ?>
                                                    </a>
                                                <?php }?>
                                            </td>
                                            <td style="text-align:center; padding-top: 12px;">
                                                <? if($declaracao["declaracao"]) { ?>
                                                    <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/declaracaopdf/<?= $declaracao["idmatriculadeclaracao"]; ?>" target="_blanck"><?=$idioma["declaracoes_matricula_abrir_pdf"];?></a>
                                                    &nbsp;
                                                    <a class="btn btn-mini" href="javascript:abrePopup('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/declaracao/<?= $declaracao["idmatriculadeclaracao"]; ?>','declaracao<?= $declaracao["idmatriculadeclaracao"]; ?>','scrollbars=yes,resizable=yes,width=800,height=600')" ><?=$idioma["declaracoes_matricula_abrir"];?></a>
                                                <? } else { ?>
                                                    <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/declaracaodownload/<?= $declaracao["idmatriculadeclaracao"]; ?>" ><?=$idioma["declaracoes_matricula_download"];?></a>
                                                <? } ?>
                                            </td>
                                        </tr>
                                    <? } ?>
                                <? } else { ?>
                                    <tr>
                                        <td colspan="7"><?=$idioma["nenhuma_declaracao"];?></td>
                                    </tr>
                                <? } ?>
                            </table>
                            <form id="form_alterar_visibilidade_declaracao" name="form_alterar_visibilidade_declaracao" method="post" action="">
                                <input type="hidden" id="situacao_alteracao" name="situacao_alteracao" value="" >
                                <input type="hidden" id="idmatriculadeclaracao" name="idmatriculadeclaracao" value="" >
                                <input name="acao" type="hidden" value="alterar_visibilidade_declaracao" />
                            </form>
                        </section>

                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>

<script type="text/javascript">
    function alterarSituacaoVisibilidadeDeclaracao(tipo, idmatriculadeclaracao){
        document.getElementById('idmatriculadeclaracao').value = idmatriculadeclaracao;
        document.getElementById('situacao_alteracao').value = tipo;
        document.getElementById('form_alterar_visibilidade_declaracao').submit();
    }
</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>