<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
    <style type="text/css">
        .status {
            cursor: pointer;
            color: #FFF;
            font-size: 9px;
            font-weight: bold;
            padding: 5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right: 5px;
            line-height: 30px;
        }

        .ativo {
            font-size: 15px;
        }

        .inativo {
            background-color: #838383;
        }

        #portamento_container {
            position: relative;
        }

        #portamento_container #menuEsquerda {
            position: absolute;
        }

        #portamento_container #menuEsquerda.fixed {
            position: fixed;
            margin-top: 90px;
        }

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
    </style>
</head>
<body>
<?php incluirLib("topo", $config, $usuario); ?>
<div class="container-fluid">
<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;
            <small><?php echo $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span>
        </li>
        <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span
                class="divider">/</span></li>
        <li>
            <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a>
            <span class="divider">/</span></li>
        <li><?php echo $idioma["nav_ficha"]; ?> #<?php echo $url[4]; ?> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
<div class="span12">
<div class="box-conteudo">
<div class=" pull-right">
    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i
            class="icon-share-alt"></i><?= $idioma["btn_sair"]; ?></a>
</div>
<h2 class="tituloEdicao"><?= $idioma["ficha"]; ?> #<?= $url[4]; ?>
    <br/>
    <small
        style="text-transform:uppercase;"><?= $idioma["data_abertura"]; ?> <?= formataData($matricula["data_cad"], "br", 1); ?></small>
</h2>
<div class="row-fluid">
<div class="span2">
    <div class="well" style="padding: 8px 0pt; width:180px;" id="menuEsquerda">
        <ul class="nav nav-list">
            <li class="nav-header active"><a><?= $idioma["menu_navegacao"]; ?></a></li>
            <li><a href="#contasmatricula"><?= $idioma["menu_contas"]; ?></a></li>
            <li><a href="#logcobrancasmatricula"><?= $idioma["menu_cobrancas"]; ?></a></li>
            <li><a href="#contatos"><?= $idioma["menu_contatos"]; ?></a></li>
        </ul>
    </div>
</div>
<div class="span10">
<? if ($cobranca["erro"]) { ?>
    <div class="alert alert-error">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <?= $idioma[$cobranca["erro"]]; ?>
    </div>
    <script>alert('<?= $idioma[$cobranca["erro"]]; ?>');</script>
<? } ?>
<? if ($_POST["msg"]) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
    </div>
    <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
<? } ?>
<section id="contasmatricula" style="#000">
    <legend><?= $idioma['legenda_contas']; ?></legend>
    <br/>
    <? if (count($contas_matricula) > 0) { ?>
    <? foreach ($contas_matricula as $contas) { ?>
        <h4><?= $contas[0]["evento"]; ?></h4>
        <br/>
        <table width="720" cellpadding="5" cellspacing="0"
               class="table table-bordered table-condensed tabelaSemTamanho">
            <tr>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_id"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_forma_pagamento"]; ?></strong></td>
                <?php /*?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_parcela"];?></strong></td><?php */ ?>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_valor"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_vencimento"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_situacao"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_bandeira_cartao"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_autorizacao_cartao"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_banco_cheque"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_agencia_cheque"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_cc_cheque"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_numero_cheque"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_emitente_cheque"]; ?></strong></td>
                <td bgcolor="#F4F4F4"><strong><?= $idioma["financeiro_desconto"]; ?></strong></td>
            </tr>
            <?
            $total = 0;
            $total_compartilhado = 0;
            $totalDesconto = 0;
            foreach ($contas as $conta) {
                $total += $conta["valor"];
                $totalDesconto += $conta["desconto"];
                ?>
                <tr>
                    <td><?php echo $conta["idconta"]; ?></td>
                    <td><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?></td>
                    <?php /*?><td><?php echo $conta["parcela"]; ?></td><?php */ ?>
                    <td>
                        <span style="color:#999">R$</span>

                        <?php
                        if ($conta['valor_matricula']) {
                            $valor_parcela = ($conta["valor_matricula"] / $conta['total_contas_compartilhadas']);
                            $total_compartilhado += $valor_parcela;
                            echo '<strong>' . number_format($valor_parcela, 2, ",", ".") . '</strong> <span style="color:#999"> / ' . number_format($conta["valor"], 2, ",", ".") . '</span>';
                        } else {
                            echo '<strong>' . number_format($conta["valor"], 2, ",", ".") . '</strong>';
                        }
                        ?>

                    </td>
                    <td><?php echo formataData($conta["data_vencimento"], 'br', 0); ?></td>
                    <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label"
                              style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>"
                              data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                    <td><?php if ($conta["bandeira_cartao"]) echo $conta["bandeira_cartao"]; else echo "--"; ?></td>
                    <td><?php if ($conta["autorizacao_cartao"]) echo $conta["autorizacao_cartao"]; else echo "--"; ?></td>
                    <td><?php if ($conta["banco"]) echo $conta["banco"]; else echo "--"; ?></td>
                    <td><?php if ($conta["agencia_cheque"]) echo $conta["agencia_cheque"]; else echo "--"; ?></td>
                    <td><?php if ($conta["cc_cheque"]) echo $conta["cc_cheque"]; else echo "--"; ?></td>
                    <td><?php if ($conta["numero_cheque"]) echo $conta["numero_cheque"]; else echo "--"; ?></td>
                    <td><?php if ($conta["emitente_cheque"]) echo $conta["emitente_cheque"]; else echo "--"; ?></td>
                    <td><span style="color:#999">R$</span>
                        <strong><?php echo number_format($conta["desconto"], 2, ",", "."); ?></strong></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>
                    <span style="color:#999">R$</span>

                    <?php
                    if ($total_compartilhado) {
                        echo '<strong>' . number_format($total_compartilhado, 2, ",", ".") . '</strong> <span style="color:#999"> / ' . number_format($total, 2, ",", ".") . '</span>';
                    } else {
                        echo '<strong>' . number_format($total, 2, ",", ".") . '</strong>';
                    }
                    ?>

                </td>
                <td colspan="9">&nbsp;</td>
                <td><span style="color:#999">R$</span>
                    <strong><?php echo number_format($totalDesconto, 2, ",", "."); ?></strong></td>
            </tr>
        </table>
        <br/>
    <?php } ?>
    </form>
</section>
<section id="logcobrancasmatricula" style="#000">
    <!--Session do Administrar de Matrícula com as mensagens-->
    <legend><?= $idioma["label_cobrancas"]; ?></legend>
    <div class="accordion" id="accordion_cobrancas">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_cobrancas"
                   href="#cadastrar_mensagem"><?= $idioma["cobranca_cadastrar"]; ?></a>
            </div>
            <div id="cadastrar_mensagem" class="accordion-body collapse">
                <div class="accordion-inner">
                    <form name="form_cobrancas" method="post" onsubmit="return validaCobranca();" id="form_cobrancas">
                        <input type="hidden" name="idmatricula" id="idmatricula" value="<?php echo $url[4]; ?>">

                        <div style="border:#CCC solid 1px; padding-bottom:10px; width:99%" class="row-fluid">
                            <div style="width:90%; padding-left:15px;">
                                <br/>
                                <small><strong><?php echo $idioma["cobranca_mensagem"]; ?></strong></small>
                                <br/>
                                <textarea name="mensagem" id="mensagem" rows="5" style="width:65%;"></textarea>
                                <small><strong><?php echo $idioma["cobranca_proxima_acao"]; ?></strong></small>
                                <input name="proxima_acao" id="proxima_acao" class="span2" type="text"/>
                                <br/>
                                <input type="hidden" name="acao" value="salvar_cobranca">
                                <br/>

                                <div style="float:right;"><input type="submit" class="btn btn-primary" name="enviar"
                                                                 value="<?php echo $idioma["btn_cadastrar"]; ?> "/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_cobrancas"
                   href="#cobrancas_cadastradas"><?= $idioma["cobrancas_cadastradas"]; ?></a>
            </div>
            <div id="cobrancas_cadastradas" class="accordion-body collapse in">
                <div class="accordion-inner" style="max-height:400px; overflow:auto;">
                    <?php
                    $totalCobrancas = count($cobrancasMatricula);
                    if ($totalCobrancas > 0) {
                        $count = 0;
                        foreach ($cobrancasMatricula as $cobranca) {
                            $count++;
                            ?>
                            <table cellpadding="5" cellspacing="0"
                                   class="table table-bordered table-condensed tabelaSemHover">
                                <tr>
                                    <td>
                                        <small><strong># <?= $cobranca["idcobranca"] ?></strong></small>
                                        <br/>
                                        <small>
                                            <strong><?= $idioma["data"]; ?> </strong><?= formataData($cobranca["data_cad"], "br", 1); ?>
                                            <strong><?= $idioma["por"]; ?></strong> <?php echo $cobranca["usuario"]; ?>
                                        </small>
                                        <small><strong
                                                style="padding-left:50px"><?= $idioma["cobranca_proxima_acao"]; ?> </strong>
                                        </small><?= formataData($cobranca["proxima_acao"], "br", 0); ?>
                                        <span class="pull-right" style="color:#999;">
								  <?php
                                  if ($cobranca["idusuario"] == $usuario["idusuario"]) {
                                      ?>
                                      <a class="btn btn-mini" href="javascript:void(0);"
                                         onclick="removerCobranca(<?= $cobranca["idcobranca"]; ?>);"><span
                                              class="icon-remove"></span> <?php echo $idioma["cobranca_excluir"]; ?></a>
                                  <?php } ?>
                                </span>
                                        <br/>
                                        <br/>
                                        <?php echo nl2br($cobranca["mensagem"]); ?>
                                        <br/>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                        <script>
                            function removerCobranca(id) {
                                var msg = "<?=$idioma["cobranca_confirmar_remover"];?>";
                                var confirma = confirm(msg);

                                if (confirma) {
                                    document.getElementById('idcobranca').value = id;
                                    document.getElementById('form_remover_cobranca').submit();
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        </script>
                        <form method="post" id="form_remover_cobranca" action="" style="padding-top:15px;">
                            <input name="acao" type="hidden" value="remover_cobranca"/>
                            <input name="idcobranca" id="idcobranca" type="hidden" value=""/>
                        </form>
                    <?php } else { ?>
                        <ul class="nav nav-tabs nav-stacked">
                            <li>
                                <span> <?php echo $idioma["sem_cobranca"]; ?> </span>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!--Session do Administrar de Matrícula com as mensagens(END)-->
</section>

<section id="contatos" style="#000">
    <!--Session do Administrar de Matrícula com as mensagens-->
    <legend><?= $idioma["label_contatos"]; ?></legend>
    <div class="accordion-inner" style="max-height:400px; overflow:auto;">    
        
        <h4><?php echo $idioma['contato_cadastro']; ?></h4><br />
        <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover tabelaSemTamanho">
            <tr>
                <th><strong> <?= $idioma["contatos_email"] ?></strong></th>
                <th><strong> <?= $idioma["contatos_telefone"] ?></strong></th>
                <th><strong> <?= $idioma["contatos_celular"] ?></strong></th>
            </tr>                   
            <tr>
                <td><?= $dadosPessoa["email"] ?></td>
                <td><?= $dadosPessoa["telefone"] ?></td>
                <td><?= $dadosPessoa["celular"] ?></td>
            </tr>                
        </table>
        
        <h4><?php echo $idioma['contato_associados']; ?></h4><br />
        <?php
        if (count($contatosArray)) {
        ?>
            <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover tabelaSemTamanho">
                <tr>
                    <th><strong> <?= $idioma["contatos_idcontato"] ?></strong></th>
                    <th><strong> <?= $idioma["contatos_tipo"] ?></strong></th>
                    <th><strong> <?= $idioma["contatos_valor"] ?></strong></th>
                </tr>
                <?php
                foreach ($contatosArray as $contato) {
                ?>                    
                        <tr>
                            <td><?= $contato["idcontato"] ?></td>
                            <td><?= $contato["tipo"] ?></td>
                            <td><?= $contato["valor"] ?></td>
                        </tr>
                    
                <?php } ?>
            </table>
        <?php } else { ?>
            <ul class="nav nav-tabs nav-stacked">
                <li>
                    <span> <?php echo $idioma["sem_contato"]; ?> </span>
                </li>
            </ul>
        <?php } ?>
    </div>
</section>

    <?php } else { ?>
        <ul class="nav nav-tabs nav-stacked">
            <li>
                <span> <?php echo $idioma["sem_financeiro"]; ?> </span>
            </li>
        </ul>
    <?php } ?>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
<? incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/plugins/portamento/portamento-min.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script>
    function validaCobranca() {
        if (document.getElementById('mensagem').value == "") {
            alert('<?php echo $idioma["mensagem_vazio"]; ?>');
            return false;
        } else if (document.getElementById('proxima_acao').value == "") {
            alert('<?php echo $idioma["proxima_acao_vazio"]; ?>');
            return false;
        }
    }
    var regras = new Array();
    regras.push("required,proxima_acao,<?=$idioma["proxima_acao_vazia"];?>");
    $("#proxima_acao").datepicker($.datepicker.regional["pt-BR"]);
    jQuery(document).ready(function ($) {
        $("#proxima_acao").datepicker({
            currentText: 'Now',
            minDate: 'Now'
        })
    });
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64"/></div>
</div>
</body>
</html>