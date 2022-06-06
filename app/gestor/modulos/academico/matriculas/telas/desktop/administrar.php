<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/plugins/sweetalert2/dist/sweetalert2.min.css">
    <style type="text/css">
        .swal2-container {
            z-index: 350000 !important;
        }
        .status {
            cursor:pointer;
            color:#FFF;
            font-size:9px;
            font-weight:bold;
            padding:5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right:5px;
            line-height: 30px;
        }
        .ativo {
            font-size:15px;
        }
        .inativo {
            background-color:#838383;
        }
        #menuEsquerda {
        }

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

        .inputData {
            width: 75px;
            text-align: center;
        }
    </style>
    <script type="text/javascript">
        function attContrato(id) {
            document.getElementById('iframe_cancelarcontrato').src = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/index/cancelarcontrato?r=' + id;
        }
    </script>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?>&nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/matriculas"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
            <li><?= $idioma["nav_matricula"]; ?> #<?= $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
            <li class="active"><?= $idioma["nav_administrar"]; ?></a></li>
            <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo" style="padding:20px">
                <div class=" pull-right">
                    <?php
                    if (count($matriculaObj->retornarToTalTentativasProva()) && ! empty($matricula['situacao']['visualizacoes'][49]) && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|32", false)) {
                        ?>
                        <a class="btn btn-small" href="#zerarTentativas" rel="facebox" ><?php echo $idioma["zerar_tentativas_prova"]; ?> (<span class='qtd_tentativas'><?php echo count($matriculaObj->retornarToTalTentativasProva()) ;?></span>)</a>
                        <div id="zerarTentativas" style="display:none;">
                            <iframe id="iframe_zerarTentativas" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/index/zerarTentativas" width="500" height="300" frameborder="0"></iframe>
                        </div>

                        <?php
                    }
                    ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
                </div>
                <table border="0" cellspacing="0" cellpadding="15">
                    <tr>
                        <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?= $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
                        <td style="padding: 0px 0px 0px 8px;" valign="top">
                            <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
                                <br />
                                <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
                            </h2>
                        </td>
                    </tr>
                </table>
                <?php
                $matricula['idsituacaoCancelada'] = $situacaoCancelada['idsituacao'];
                incluirTela("administrar.menu",$config,$matricula); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if(!empty($acessoCursoNaoSimultaneo) && ($acessoCursoNaoSimultaneo['idmatricula'] != $matricula['idmatricula']) && $matricula['acesso_simultaneo'] === 'N' && $matricula['idsituacao'] == $situacaoAtiva['idsituacao']) {?>
                        <div class="alert alert-warning">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                            <strong>Atenção</strong><br>
                            <span>Está matrícula está com a situação em curso porém o aluno só poderá iniciar o curso quando concluir o curso <strong><?= $acessoCursoNaoSimultaneo['nome'] ?></strong> da matrícula <strong><?= $acessoCursoNaoSimultaneo['idmatricula']?></strong></span>
                        </div>
                        <?  } ?>
                        <?php
                        if ($mensagem["erro"]) {
                            ?>
                            <div class="alert alert-error">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <?= $idioma[$mensagem["erro"]]; ?>
                            </div>
                            <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
                            <?php
                        }
                        if (count($associarPessoa["erros"]) > 0) {
                            ?>
                            <div class="alert alert-error fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma["form_erros"]; ?></strong>
                                <?php
                                foreach ($associarPessoa["erros"] as $ind => $val) {
                                    ?>
                                    <br />
                                    <?php
                                    echo $idioma[$val];
                                }
                                ?>
                            </div>
                            <?php
                        }

                        if (count($salvar["erros"]) > 0) { ?>
                            <div class="alert alert-error fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma["form_erros"]; ?></strong>
                                <?php
                                foreach ($salvar["erros"] as $ind => $val) {
                                    echo "<br />".$idioma[$val] . " ($val)";
                                }
                                ?>
                            </div>
                            <?php
                        }

                        if($_POST["msg"]) {
                            ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                            <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
                            <?php
                        }
                        if($diploma['total']) {
                            ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?= sprintf($idioma['msg_matricula_diploma'], Request::url('0-1', '/') .'academico/folhasregistrosdiplomas/'.$diploma['idfolha'].'/diplomas', $diploma['idfolha']); ?></strong>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if($matricula["aprovado_comercial"] == "N") {
                            ?>
                            <div class="alert alert-error" style="height:90px;">
                                <?= $idioma["texto_comercial_nao_aprovado"]; ?>
                                <br />
                                <br />
                                <?php
                                if($matricula["situacao"]["visualizacoes"][17] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17",false)) {
                                    ?>
                                    <form method="post" action="#form_aprovar_comercial" id="form_aprovar_comercial" onsubmit="return ConfirmAprovacaoReprovacao('aprovar');" style="float:left; margin-right:10px;">
                                        <input name="acao" type="hidden" value="aprovar_comercial" />
                                        <input class="btn btn-large btn-success" type="submit" value="<?=$idioma['btn_aprovar'];?>" />
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <span class="btn btn-large btn-success" disabled="disabled" data-placement="right" data-original-title="<?= $idioma['sem_permissao']; ?>" rel="tooltip" style="float:left; margin-right:10px;" /><?=$idioma['btn_aprovar'];?></span>
                                    <?php
                                }
                                ?>
                                <br />
                            </div>
                        <?php
                        if($matricula["situacao"]["visualizacoes"][17] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17",false)) {
                        ?>
                            <script type="text/javascript">
                                function ConfirmAprovacaoReprovacao(tipo){
                                    if(tipo == "aprovar") {
                                        var confirma = confirm('<?=$idioma['confirma_aprovar_comercial'];?>');
                                    } else {
                                        var confirma = confirm('<?=$idioma['confirma_reprovar_comercial'];?>');
                                    }
                                    if(confirma)
                                        return true
                                    else
                                        return false;
                                }
                            </script>
                            <?php
                        }
                        }
                        ?>
                        <section id="situacaomatricula" style="#000">
                            <legend><?= $idioma["situacao_matricula"]; ?></legend>
                            <div id="divSituacoes" style="padding-top:15px; padding-bottom:15px;">
                                <?php
                                foreach ($situacaoWorkflow as $ind => $val) {
                                    if ($ind == $matricula["idsituacao"]) {
                                        $class = 'class="status ativo" style="background-color: #'.$val["cor_bg"].';color: #'.$val["cor_nome"].';cursor:default"';
                                    } else {
                                        $class = 'class="status inativo"';
                                        if (in_array($ind, $situacaoWorkflowRelacionamento)
                                            && $matricula['situacao']['visualizacoes'][1]
                                            && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5",false)
                                            && ($matricula['idsituacao'] != $situacaoAprovadoComercial['idsituacao'] ||
                                                $ind == $situacaoCancelada['idsituacao'])) {
                                            $onclick = 'onclick="modificarSituacao(\''.$ind.'\',\''.$val["nome"].'\');"';
                                            if ($ind == $situacaoCancelada['idsituacao']) {
                                                unset($onclick);
                                                $cancelar_link = '<a href="#cancelarmatricula" rel="facebox" style="text-decoration:none;" >';
                                            } elseif($ind == $situacaoInativa["idsituacao"]) {
                                                unset($onclick);
                                                $inativar_link = '<a href="#inativarmatricula" rel="facebox" style="text-decoration:none;" >';
                                            }
                                        } else {
                                            $onclick = 'data-original-title="'.$idioma["indisponivel"].'" style="background-color:#CCC" rel="tooltip"';
                                        }
                                    }

                                    if ($cancelar_link) {
                                        echo $cancelar_link;
                                    } elseif($inativar_link) {
                                        echo $inativar_link;
                                    }
                                    ?>
                                    <span id="<?= $ind; ?>" <?= $class; ?> <?= $onclick; ?>><?= $val['nome']; ?></span>
                                    <?php
                                    if ($cancelar_link) {
                                        echo '</a>'; unset($cancelar_link);
                                    } elseif ($inativar_link) {
                                        echo '</a>'; unset($inativar_link);
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            if (in_array($situacaoCancelada["idsituacao"], $situacaoWorkflowRelacionamento)) {
                                ?>
                                <div id="cancelarmatricula" style="display:none">
                                    <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/index/cancelarmatricula" width="400" height="350" frameborder="0"></iframe>
                                </div>
                                <?php
                            }

                            if (in_array($situacaoInativa["idsituacao"], $situacaoWorkflowRelacionamento)) {
                                ?>
                                <div id="inativarmatricula" style="display:none">
                                    <iframe src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/index/inativarmatricula" width="400" height="350" frameborder="0"></iframe>
                                </div>
                                <?php
                            }

                            if (
                                $matricula['situacao']['visualizacoes'][1]
                                && $matriculaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|5',false)
                                && $matricula['idsituacao'] != $situacaoAprovadoComercial['idsituacao']
                            ) {
                                ?>
                                <script type="text/javascript">
                                    function modificarSituacao(para,nome) {
                                        var de = "<?= $matricula["idsituacao"]; ?>";
                                        var msg = "<?=$idioma["confirma_altera_situacao_matricula"];?>";
                                        msg = msg.replace("[[idmatricula]]", "<?=$url[3];?>");
                                        msg = msg.replace("[[nome]]", nome);
                                        var confirma = confirm(msg);
                                        if (confirma) {
                                            document.getElementById('data_conclusao_').value = document.getElementById('form_data_conclusao').value;// Cópia o valor digitado no outro form
                                            document.getElementById('situacao_para').value = para;
                                            document.getElementById('form_situacao', msg ).submit();
                                        } else {
                                            return false;
                                        }
                                    }
                                </script>
                                <form method="post" action="#situacao" id="form_situacao">
                                    <input id="data_conclusao_" name="data_conclusao" type="hidden" value=""/>
                                    <input name="acao" type="hidden" value="alterar_situacao" />
                                    <input name="situacao_para" id="situacao_para" type="hidden" value="" />
                                </form>
                                <?php
                            }
                            if(($matricula["forma_pagamento"] == 2 || $matricula["forma_pagamento"] == 3) && $matricula["idpedido"] && $matricula['pedido']['pagamento']["status_transacao"] == 'CAP') {
                                ?>
                                <div class="alert alert-success" style="margin-top:10px;"><?php printf($idioma["texto_cartao_autorizado"], $matricula['pedido']['pagamento']["tid"]); ?></div>
                                <?php
                            }
                            if ($matricula["idmotivo_cancelamento"] && $matricula['idsituacao'] == $situacaoCancelada["idsituacao"]) {
                                ?>
                                <div class="alert alert-danger">
                                    <h4><?=$idioma["motivo_cancelamento"];?></h4>
                                    <br />
                                    <strong><?=$matricula["motivo_cancelamento"]["nome"];?></strong>
                                </div>
                                <?php
                            } elseif($matricula["idmotivo_inativo"]) {
                                ?>
                                <div class="alert alert-danger">
                                    <h4><?=$idioma["motivo_inativo"];?></h4>
                                    <br />
                                    <strong><?=$matricula["motivo_inativo"]["nome"];?></strong>
                                </div>
                                <?php
                            }
                            ?>
                        </section>
                        <?php
                        if (
                            $matricula['situacao']['visualizacoes'][1]
                            && $matricula['idsituacao'] == $situacaoInicial['idsituacao']
                        ) {
                            ?>
                            <div class="alert alert-info" style="width:642px;">
                                <?= $idioma['texto_aprovar_matricula']; ?>
                                <br /><br />
                                <form method="post" onsubmit="return confirm('<?= $idioma['confirma_aprovar_matricula']; ?>')">
                                    <input name="acao" type="hidden" value="aprovar_matricula" />
                                    <input <?= ($matricula['escola']['idestado'] == 16 && $matricula['detran_situacao'] != 'LI') ? 'disabled' : '' ?> id="btn_submit" class="btn btn-primary btn-large" type="submit" value="<?= $idioma['btn_aprovar_matricula']; ?>" />
                                </form>
                                <br />
                            </div>
                            <?php
                        }
                        ?>
                        <section id="dadosmatricula">
                            <legend><?=$idioma["dados_matricula"];?></legend>
                            <table border="0" cellspacing="0" cellpadding="5" class="table table-bordered table-condensed">
                                <tr>
                                    <td style="width: 150px"><?= $idioma["dados_sindicato"]; ?></td>
                                    <td><strong><?= $matricula["sindicato"]["nome_abreviado"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_oferta"];?></td>
                                    <td><strong><?= $matricula["oferta"]["nome"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_curso"];?></td>
                                    <td><strong><?= $matricula["curso"]["nome"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_curriculo"];?></td>
                                    <td><strong><?= $matricula["curriculo"]["nome"]; ?></strong> (<?= $matricula["curriculo"]["idcurriculo"]; ?>)</td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_escola"];?></td>
                                    <td><strong><?= $matricula["escola"]["nome_fantasia"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Turma:</td>
                                    <td>
                                        <div style="float:left;"><strong><?= $matricula["turma"]["nome"]; ?></strong> (<?= $matricula["turma"]["idturma"]; ?>)</div>
                                        <?php
                                        if($matricula["situacao"]["visualizacoes"][30] && $matriculaObj->verificaPermissao($perfil["permissoes"], "matriculas|29",false)) {
                                            ?>
                                            <div style="text-align:right; float:right;">
                                                <script type="text/javascript">
                                                    function MM_openBrWindow(theURL,winName,features) {
                                                        window.open(theURL,winName,features);
                                                    }
                                                </script>
                                                <a class="btn btn-mini" onclick="MM_openBrWindow('<?= 'http://'.$_SERVER['SERVER_NAME'].'/'.$url["0"]."/".$url["1"]."/".$url["2"]."/".$url[3]."/".$url[4].'/transferir_turma'; ?>','situacao_LI','status=yes,scrollbars=yes,resizable=yes,width=1000,height=600')" ><?= $idioma["modificar_turma"]; ?></a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_vendedor"];?></td>
                                    <td><strong><?= $matricula["vendedor"]["nome"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_matricula_data_registro"];?></td>
                                    <td><strong><?= formataData($matricula["data_registro"], "br", 0); ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_forma_pagamento"];?></td>
                                    <td><strong><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$matricula["forma_pagamento"]]; ?></strong></td>
                                </tr>
                                <?php
                                if ($matricula["forma_pagamento"] == 2 || $matricula["forma_pagamento"] == 3) {
                                    ?>
                                    <tr>
                                        <td><?=$idioma["dados_bandeira"];?></td>
                                        <td><strong><?= $matricula["bandeira"]['nome']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?=$idioma["dados_autorizacao_cartao"];?></td>
                                        <td><strong><?= $matricula["autorizacao_cartao"]; ?></strong></td>
                                    </tr>
                                    <?php
                                    if ($matricula["idpedido"]) {
                                        ?>
                                        <tr>
                                            <td><?=$idioma["dados_cielo_status_transacao"];?></td>
                                            <td>
                                                <strong>
                                                    <?php
                                                    if($matricula['pedido']['pagamento']["status_transacao"]) {
                                                        ?>
                                                        <?= $idioma['cielo_status_transacao'][$matricula['pedido']['pagamento']["status_transacao"]]; ?>
                                                        <?php
                                                    } else {
                                                        echo $idioma['cielo_status_transacao_nao_criada'];
                                                    }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$idioma["dados_cielo_tid"];?></td>
                                            <td>
                                                <strong>
                                                    <?php
                                                    if($matricula['pedido']['pagamento']["tid"]) {
                                                        ?>
                                                        <?= $matricula['pedido']['pagamento']["tid"]; ?>
                                                        <?php
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$idioma["dados_cielo_nsu"];?></td>
                                            <td>
                                                <strong>
                                                    <?php
                                                    if($matricula['pedido']['pagamento']["nsu"]) {
                                                        ?>
                                                        <?= $matricula['pedido']['pagamento']["nsu"]; ?>
                                                        <?php
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$idioma["dados_cielo_pan"];?></td>
                                            <td>
                                                <strong>
                                                    <?php
                                                    if($matricula['pedido']['pagamento']["pan"]) {
                                                        ?>
                                                        <?= $matricula['pedido']['pagamento']["pan"]; ?>
                                                        <?php
                                                    } else {
                                                        echo '--';
                                                    }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                if ($matricula["combo"] == 'S') {
                                    ?>
                                    <tr>
                                        <td><?=$idioma["dados_combo"];?></td>
                                        <td>
                                            <a class="btn btn-mini" href="/<?= $url[0]; ?>/academico/matriculas/<?= $matricula["combo_matricula"]; ?>/administrar" target="_blank">Matrícula - <strong><?= $matricula["combo_matricula"]; ?></strong></a>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                if (!empty($matricula['escola']['detran_codigo']) && $matricula['detran_situacao_integracao'] && $curso_integrado) { ?>
                                    <tr>
                                        <td><?=$idioma['dados_detran_codigo'];?></td>
                                        <td><strong><?= $matricula['escola']['detran_codigo']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?= $idioma['dados_detran_situacao']; ?></td>
                                        <td>
                                            <div style="float:left;"><strong><?= $situacaoDetran[$config['idioma_padrao']][$matricula['detran_situacao']]; ?></strong></div>
                                            <?php
                                            if (($matriculaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|31', false))
                                                && in_array($matricula['detran_situacao'], ['NL', 'LI'])
                                            ){?>
                                                <div style="text-align:right; float:right;">
                                                    <form method="post" action="#vencimento">
                                                        <input name="acao" type="hidden" value="alterar_situacao_detran_aguardando_liberacao" />
                                                        <input type="submit" id="" class="btn btn-mini" value="<?= $idioma['alterar_situacao_detran']; ?>" onclick="if (! confirm('<?= $idioma['alterar_situacao_detran_confirma']; ?>')) { return false;} " />
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= $idioma['dados_detran_creditos']; ?></td>
                                        <td><strong><?= $sim_nao[$config['idioma_padrao']][$matricula['detran_creditos']]; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><?= $idioma['dados_detran_certificado']; ?></td>
                                        <td><strong><?= $sim_nao[$config['idioma_padrao']][$matricula['detran_certificado']]; ?></strong></td>
                                    </tr>
                                    <?php if($matricula['detran_logs']['retorno'] !== false) {?>
                                        <tr>
                                            <td><?= $idioma['dados_detran_envio']; ?></td>
                                            <td><strong><?= stripslashes(htmlentities($matricula['detran_logs']['string_envio'])); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><?= $idioma['dados_detran_retorno']; ?></td>
                                            <td><strong><?= $matricula['detran_logs']['retorno']; ?></strong></td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                }
                                ?>
                            </table>
                            <input type="button" value="Descrição das datas" id="legendas_datas_botao" class="btn btn-small" />
                            <br />
                            <br />
                            <table border="0" cellspacing="0" cellpadding="5" class="table table-bordered table-condensed tabelaSemTamanho" id="tabela_legendas" style="display:none;">
                                <tr>
                                    <td style="padding-right:20px;" width="140"><strong><?= $idioma['legendas_datas_matricula']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_matricula_legenda']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:20px;"><strong><?= $idioma['legendas_datas_expedicao']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_expedicao_legenda']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:20px;"><strong><?= $idioma['legendas_datas_conclusao']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_conclusao_legenda']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:20px;"><strong><?= $idioma['legendas_datas_limite_ava']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_limite_ava_legenda']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:20px;"><strong><?= $idioma['legendas_datas_previsao_conclusao']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_previsao_conclusao_legenda']; ?></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:20px;" width="160"><strong><?= $idioma['legendas_datas_comissao']; ?></strong></td>
                                    <td style="padding-right:20px;"><?= $idioma['legendas_datas_comissao_legenda']; ?></td>
                                </tr>
                            </table>
                            <form method="post" action="" onsubmit="return validateFields(this, regras_dados_matricula)" enctype="multipart/form-data" class="form-horizontal">
                                <?php
                                if($matricula["situacao"]["visualizacoes"][2] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) {
                                    ?>
                                    <input name="acao" type="hidden" value="alterar_dados_matricula" />
                                    <?php
                                }
                                ?>
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;"><strong><?= $idioma['financeiro_numero_contrato']; ?></strong></td>
                                        <td style="padding-right:20px;"><strong><?= $idioma['dados_matricula_data_matricula']; ?></strong></td>
                                        <td><strong><?= $idioma['dados_matricula_data_expedicao']; ?></strong></td>
                                        <td style="padding-left:20px;"><strong><?= $idioma['dados_matricula_data_prolongada']; ?></strong></td>
                                        <td style="padding-left:20px;"><strong><?= $idioma['dados_matricula_porcentagem']; ?></strong></td>
                                        <td style="padding-left:20px;"><strong><?= $idioma['dados_matricula_data_conclusao']; ?></strong></td>
                                        <td style="padding-left:20px;"><strong><?= $idioma['dados_matricula_data_primeiro_acesso']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_numero_contrato" class="span2" type="text" value="<?= $matricula["numero_contrato"]; ?>" name="numero_contrato">
                                        </td>
                                        <td style="padding-right:20px;"><input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_data_matricula" class="inputData" type="text" value="<?= formataData($matricula["data_matricula"], "br", 0); ?>" name="data_matricula" required></td>
                                        <td><input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_data_expedicao" class="inputData" type="text" value="<?php if($matricula["data_expedicao"] && $matricula["data_expedicao"] != "0000-00-00") echo formataData($matricula["data_expedicao"], "br", 0); ?>" name="data_expedicao"></td>
                                        <td style="padding-left:20px;"><input id="form_data_prolongamento" class="inputData" type="text" value="<?php if($matricula["data_prolongada"] && $matricula["data_prolongada"] != "0000-00-00") echo formataData($matricula["data_prolongada"], "br", 0); ?>" name="data_prolongada" <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?>></td>
                                        <td style="padding-left:20px;"><input <?php if(!$matricula["situacao"]["visualizacoes"][32] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|30",false)) { ?> disabled="disabled" <?php } ?> id="form_porcentagem" maxlength="6" type="text" value="<?= number_format($matricula["porcentagem_manual"], 2, ",", "."); ?>" class="inputData" name="porcentagem_manual" style="text-align:left;"></td>
                                        <td style="padding-left:20px;"><input id="form_data_primeiro_acesso" class="inputData" type="text" readonly value="<?= formataData($matricula["data_primeiro_acesso"], "br", 0); ?>" <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> /></td>
                                    </tr>
                                </table>
                                <br />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;"><strong><?= $idioma['dados_matricula_vendedor']; ?></strong></td>
                                        <td style="padding-right:20px;"><strong><?= $idioma['dados_matricula_data_registro']; ?></strong></td>
                                        <td style="padding-left:20px;"><strong><?= $idioma['dados_forma_pagamento']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <select <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> name="idvendedor" id="form_idvendedor" class="span4">
                                                <option value=""><?= $idioma["selecione_empresa"]; ?></option>
                                                <?php
                                                foreach($vendedores as $vendedor) {
                                                    ?>
                                                    <option value="<?= $vendedor["idvendedor"]; ?>" <?php if($matricula["idvendedor"] == $vendedor["idvendedor"]) { ?> selected="selected"<?php } ?>><?= $vendedor["nome"]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td style="padding-right:20px;">
                                            <input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_data_registro" class="inputData" type="text" value="<?= formataData($matricula["data_registro"], "br", 0); ?>" name="data_registro">
                                        </td>
                                        <td style="padding-left:20px;">
                                            <select <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> name="forma_pagamento" id="forma_pagamento" class="span4 " onchange="liberaCamposFormasPagamento(this.options[this.selectedIndex].value);">
                                                <option value=""><?= $idioma["selecione_empresa"]; ?></option>
                                                <?php
                                                foreach($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']] as $ind => $forma) {
                                                    ?>
                                                    <option value="<?= $ind; ?>" <?php if($matricula["forma_pagamento"] == $ind) { ?> selected="selected"<?php } ?>><?= $forma; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <script>
                                    function liberaCamposFormasPagamento(valorCampo){
                                        if (valorCampo == 2 || valorCampo == 3) {
                                            $('#financeiro_informacoes_cartao').show("fast");
                                        } else {
                                            $('#idbandeira').attr("value","");
                                            $('#autorizacao_cartao').attr("value","");
                                            $('#financeiro_informacoes_cartao').hide("fast");
                                        }
                                    }
                                </script>
                                <table id="financeiro_informacoes_cartao" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" style="<?php if ($matricula["forma_pagamento"] != 2 && $matricula["forma_pagamento"] != 3) { ?>display:none; <?php } ?>">
                                    <tr>
                                        <td bgcolor="#F4F4F4" colspan="2" style="text-transform:uppercase;"><strong><?=$idioma["financeiro_informacoes_cartao"];?></strong></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_bandeira_cartao"];?></strong></td>
                                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_autorizacao_cartao"];?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="idbandeira" id="idbandeira" style="width:auto;" class="">
                                                <option value=""><?= $idioma["selecione_bandeira_cartao"]; ?></option>
                                                <?php
                                                foreach($bandeirasCartoes as $bandeiraCartao) {
                                                    ?>
                                                    <option value="<?= $bandeiraCartao["idbandeira"]; ?>" <?php if($matricula["idbandeira"] == $bandeiraCartao['idbandeira']) { ?> selected="selected"<?php } ?> ><?= $bandeiraCartao["nome"]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input name="autorizacao_cartao" type="text" id="autorizacao_cartao" value="<?= $matricula['autorizacao_cartao']; ?>" maxlength="40" class="span2 " /></td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;"><strong><?= $idioma['financeiro_valor']; ?></strong></td>
                                        <td><strong><?= $idioma['financeiro_qtd_parcelas']; ?></strong></td>
                                        <td><span style="width: 70px;"><strong><?= $idioma['faturada']; ?></strong></span</td>
                                        <td style="width: 80px;"><label for="form_limite_datavalid"><span style="width: 70px;"><strong><?= $idioma['datavalid_limit']; ?></strong></span></label></td>
                                        <td style="width: 80px; text-align: center;"><label for="form_biometria_liberada"><span style="width: 70px;"><strong><?= $idioma['form_biometria_liberada']; ?></strong></span></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;"><input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_valor_contrato" class="span2 " type="text" value="<?= number_format($matricula["valor_contrato"],2,",","."); ?>" name="valor_contrato"></td>
                                        <td style="padding-right:20px;"><input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_qtd_parcelas" class="span2 " type="text" value="<?= $matricula["quantidade_parcelas"]; ?>" name="qtd_parcelas"></td>
                                        <td style="padding-right:20px;">
                                            <select style="width: 80px;" <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7",false)) { ?> disabled="disabled" <?php } ?>class="form-control" name="faturada">
                                                <option value="CUPOM"<?php if($matricula["faturada"] == "CUPOM")echo ' selected="selected"'; ?>>Cupom</option>
                                                <option value="S"<?php if($matricula["faturada"] == "S")echo ' selected="selected"'; ?>>Sim</option>
                                                <option value="N"<?php if($matricula["faturada"] == "N")echo ' selected="selected"'; ?>>Não</option>
                                            </select>
                                        </td>
                                        <td style="padding-right:20px;"><input style="width: 80px;" id="form_limite_datavalid" class="span2" type="text" value="<?= $matricula["limite_datavalid"]; ?>" name="limite_datavalid" <?php
                                            if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"],$url[2]."|34",false))
                                                echo 'disabled="disabled"';?> required />
                                        </td>
                                        <td style="padding-right:20px;">
                                            <select id="form_biometria_liberada" style="width: 80px;" class="form-control span2" name="biometria_liberada" <?php
                                            if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"],$url[2]."|34",false))
                                                echo 'disabled="disabled"';?>>
                                                <option value="S"<?php if($matricula["biometria_liberada"] == "S")echo ' selected="selected"'; ?>>Sim</option>
                                                <option value="N"<?php if($matricula["biometria_liberada"] == "N")echo ' selected="selected"'; ?>>Não</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;"><strong><?= $idioma['dados_matricula_renach']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <input <?php if(!$matricula["situacao"]["visualizacoes"][2] || !$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6",false)) { ?> disabled="disabled" <?php } ?> id="form_renach" class="span3 " type="text" value="<?= $matricula['renach'] ?>" name="renach" maxlength="30">
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;"><strong><?= $idioma['data_inicio_certificado']; ?></strong></td>
                                        <td><strong><?= $idioma['data_final_certificado']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <input id="form_data_inicio_certificado" class="inputData" type="text" value="<?= formataData($matricula["data_inicio_certificado"], "br", 0); ?>" name="data_inicio_certificado">
                                        </td>
                                        <td style="padding-right:20px;">
                                            <input id="form_data_final_certificado" class="inputData" type="text" value="<?= formataData($matricula["data_final_certificado"], "br", 0); ?>" name="data_final_certificado">
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <?php if($matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|35", false) && $matricula["situacao"]["visualizacoes"][81]) {?>
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <strong><?= $idioma['dados_matricula_data_inicio_curso']; ?></strong></td>
                                        <td><strong><?= $idioma['dados_matricula_data_conclusao']; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:63px;">
                                            <input id="form_data_inicio_curso" class="inputData" type="text"
                                                   value="<?= formataData($matricula["data_inicio_curso"], "br", 0); ?>"
                                                   name="data_inicio_curso">
                                        </td>
                                        <td style="padding-right:20px;">
                                            <input id="form_data_conclusao" class="inputData" type="text"
                                                   value="<?= formataData($matricula["data_conclusao"], "br", 0); ?>"
                                                   name="data_conclusao">
                                        </td>
                                    </tr>
                                </table>
                                <?php }?>
                                <br />
                                <br />
                                <input id="btn_submit" class="btn btn-primary" type="submit" value="<?=$idioma["btn_salvar"];?>">
                            </form>
                        </section>
                        <section id="dadosaluno">
                            <legend><?=$idioma["label_dados_aluno"];?></legend>
                            <table border="0" cellspacing="0" cellpadding="5" class="table table-bordered table-condensed">
                                <tr>
                                    <td style="border-top:0px" width="150"><?=$idioma["dados_aluno_nome"];?></td>
                                    <td style="border-top:0px" width="400"><strong><?= $matricula["pessoa"]["nome"]; ?></strong></td>
                                    <td style="border-top:0px" width="150"><?=$idioma["dados_aluno_documento"];?></td>
                                    <td style="border-top:0px" width="400">
                                        <strong>
                                            <?php
                                            if ($matricula["pessoa"]["documento_tipo"] == 'cpf') {
                                                echo str_pad($matricula["pessoa"]["documento"], 11, "0", STR_PAD_LEFT);
                                            } else {
                                                echo str_pad($matricula["pessoa"]["documento"], 14, "0", STR_PAD_LEFT);
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_data_nascimento"];?></td>
                                    <td><strong><?= formataData($matricula["pessoa"]["data_nasc"],'br',0); ?></strong></td>
                                    <td><?=$idioma["dados_aluno_rg"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["rg"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_cnh"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["cnh"]; ?></strong></td>
                                    <td><?=$idioma["dados_aluno_categoria_cnh"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["categoria"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_endereco"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["endereco"]; ?></strong></td>
                                    <td><?=$idioma["dados_aluno_telefone"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["telefone"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_email"];?></td>
                                    <td>
                                        <strong><?= $matricula["pessoa"]["email"]; ?></strong>
                                        <?php
                                        if ($relacionamentoComercial['idrelacionamento']) {
                                            ?>
                                            <a class="btn btn-mini" href="/<?= $url[0]; ?>/comercial/relacionamentocomercial/<?= $relacionamentoComercial['idrelacionamento'] ?>/administrar" target="_blank"><?= $idioma["dados_aluno_relacionamento_comercial"] ?></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td><?=$idioma["dados_aluno_celular"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["celular"]; ?></strong>
                                        <? if (trim($matricula["pessoa"]["celular"]) !== '')  {
                                            $telefoneWhats = '55' . str_replace([' ', '-', '(', ')'], '', $matricula["pessoa"]["celular"]);

                                            $primeiroNum = substr(str_replace([' ', '-', '(', ')'], '', $matricula["pessoa"]["celular"]), 2, 1);

                                            if(strlen(str_replace([' ', '-', '(', ')'], '', $matricula["pessoa"]["celular"])) == 11 && $primeiroNum > 6 && $primeiroNum < 10) {
                                                ?>
                                                <div class="pull-right">
                                                    <a class="btn btn-mini link-whatsapp" href="https://api.whatsapp.com/send?phone=<?= $telefoneWhats; ?>" target="_blank">
                                                        Abrir Whatsapp
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_numero"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["numero"]; ?></strong></td>
                                    <td><?=$idioma["dados_aluno_complemento"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["complemento"]; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_bairro"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["bairro"]; ?></strong></td>
                                    <td><?=$idioma["dados_aluno_cidade"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["cidade"];?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_cep"];?></td>
                                    <td><strong><?= $matricula["pessoa"]["cep"]; ?></strong></td>
                                    <td><?=$idioma["dados_aluno_estado"];?></td>
                                    <td colspan="3"><strong><?= $matricula["pessoa"]["estado"];?></strong></td>
                                </tr>
                                <tr>
                                    <td><?=$idioma["dados_aluno_ultimo_acesso"];?></td>
                                    <td colspan="5"><strong><?= ($matricula["pessoa"]["ultimo_acesso"]) ? formataData($matricula["pessoa"]["ultimo_acesso"], 'pt', 1) : ''; ?></strong></td>
                                </tr>
                            </table>
                            <?php
                            if($matriculaObj->verificaPermissao($perfil["permissoes"], "pessoas|2",false)) {
                                ?>
                                <a class="btn btn-mini" href="#editardadosaluno" rel="facebox" ><?= $idioma["editar_dados_aluno"]; ?></a>
                                <?php
                            } else {
                                ?>
                                <span class="btn btn-mini" disabled="disabled" data-placement="right" data-original-title="<?= $idioma['sem_permissao']; ?>" rel="tooltip" /><?= $idioma["editar_dados_aluno"]; ?></span>
                                <?php
                            }
                            if($matriculaObj->verificaPermissao($perfil["permissoes"], "pessoas|2",false)) {
                                ?>
                                <div id="editardadosaluno" style="display:none;">
                                    <iframe id="iframe_editardadosaluno" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar/index/editardadosaluno" width="800" height="500" frameborder="0"></iframe>
                                </div>
                                <?php
                            }
                            ?>
                        </section>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>

    <script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/assets/plugins/jquery.scrollTo/jquery.scrollTo.min.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.form.js"></script>
    <script src="/assets/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        $('#legendas_datas_botao').click(function() {
            $('#tabela_legendas').toggle("slow");
        });
        function contador(n) {
            if(n == 0){
                $( '#facebox' ).css( "display","none" );
                $( '#facebox_overlay' ).css( "display","none" );
                $( '#facebox_overlay' ).css( "display","none" );
                var url = "<?php echo $url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4] ?>";
                $(location).attr('href',url);
            }
            $('.qtd_tentativas').text(n);
        };
    </script>
    <script type="text/javascript">
        var $painelScrollTo = $('.box-conteudo');

        $('.scrollTo').click(function(){
            var valor_link = $(this).attr("href");
        });

        $("#form_data_matricula").mask("99/99/9999");
        $("#form_data_matricula").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_negativacao_matricula").mask("99/99/9999");
        $("#form_negativacao_matricula").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_prolongamento").mask("99/99/9999");
        $("#form_data_prolongamento").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_expedicao").mask("99/99/9999");
        $("#form_data_expedicao").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_solicitacao_carteirinha").mask("99/99/9999");
        $("#form_data_solicitacao_carteirinha").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_comissao").mask("99/99/9999");
        $("#form_data_comissao").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_qtd_parcelas").keypress(isNumber);
        $("#form_qtd_parcelas").blur(isNumberCopy);
        $("#form_valor_contrato").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});

        $("#form_data_inicio_certificado").mask("99/99/9999");
        $("#form_data_inicio_certificado").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_final_certificado").mask("99/99/9999");
        $("#form_data_final_certificado").datepicker($.datepicker.regional["pt-BR"]);

        $("#form_data_registro").mask("99/99/9999");
        $("#form_data_registro").datepicker($.datepicker.regional["pt-BR"]);

        $("#form_data_inicio_curso").mask("99/99/9999");
        $("#form_data_inicio_curso").datepicker($.datepicker.regional["pt-BR"]);
        $("#form_data_conclusao").mask("99/99/9999");
        $("#form_data_conclusao").datepicker($.datepicker.regional["pt-BR"]);


        var regras_dados_matricula = new Array();
        <?php
        if ($matricula['escola']['idestado'] == 21) { ?>
        regras_dados_matricula.push('length>10,form_renach,Campo deve ter exatamente 11 carácteres');
        regras_dados_matricula.push('length<12,form_renach,Campo deve ter exatamente 11 carácteres');
        <?php
        } ?>

        $("#vencimento").mask("99/99/9999");
        $("#vencimento").datepicker($.datepicker.regional["pt-BR"]);
        $("#quantidade_parcelas").keypress(isNumber);
        $("#quantidade_parcelas").blur(isNumberCopy);
        $("#valor").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
        $("#numero_cheque").keypress(isNumber);
        $("#numero_cheque").blur(isNumberCopy);
        $("#form_porcentagem").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});

        jQuery(document).ready(function($) {
            $("#vencimento").change(function() {
                if($("#vencimento").val() != '') {
                    valordata = $("#vencimento").val();
                    date= valordata;
                    ardt= new Array;
                    ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
                    ardt=date.split("/");
                    erro=false;
                    if ( date.search(ExpReg)==-1){
                        erro = true;
                    } else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
                        erro = true;
                    else if ( ardt[1]==2) {
                        if ((ardt[0]>28)&&((ardt[2]%4)!=0))
                            erro = true;
                        if ((ardt[0]>29)&&((ardt[2]%4)==0))
                            erro = true;
                    }
                    if (erro) {
                        alert("\""+valordata+"\" <?= $idioma["financeiro_primeiro_vencimento_invalido"]; ?>");
                        $('#vencimento').focus();
                        $("#vencimento").val('');
                        return false;
                    }
                    return true;
                }
            });

            $("#vencimento").datepicker({
                currentText: 'Now'/*,
                minDate:'Now'*/
            });
        });

        window.onload = function(){
            $('#' + document.documentURI.split("#")[1].toString() + ' > legend').trigger('click');
        };

        window.onload = function(){
            $('a[rel*=facebox]').on('click', function(){
                $(".notas_css").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
            });
        };

        function zerarValores(mensagemConfirmacao, ondeZerar) {
            var confirma = confirm("Certeza que deseja " + mensagemConfirmacao + "?");
            if (confirma) {
                $("#acaoZerar").val("zerar_" + ondeZerar);
                $("#formZerar").submit();
            }
        }

        function confirmaAcessoComo(usuario) {
            var confirma = confirm('Você está prestes a acessar o painel do aluno\ncom o cadastro de "'+usuario+'".\nDeseja continuar?\n');
            if(confirma){
                return true;
            } else {
                return false;
            }
        }

        function cancelamentoDetran(iframeForm, alerta, url)
        {
            var controller = new AbortController()
            var segundos = 7;
            var timeoutId = setTimeout(() => controller.abort(), 1000 * segundos);
            Swal.fire({
                title: alerta,
                icon: "info",
                didOpen: () => {
                    Swal.showLoading()
                    return fetch(url,  { signal: controller.signal })
                    .then(resposta => {
                        if (!resposta.ok) {
                            throw new Error(resposta.statusText)
                        }
                        return resposta.json()
                    })
                    .catch(error => {
                        console.error(error);
                        var errorMsg = (error.name === 'AbortError') ? 'Tempo de conexão excedido!' : error;
                        Swal.fire(
                            'Algo deu errado...',
                            `${errorMsg}`,
                            'error'
                        ).then(()=>{
                            iframeForm.submit();
                        })
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((retorno) => {
                Swal.fire(
                    (retorno.value?.erro == false) ? 'Tudo certo!' : 'Algo deu errado...',
                    retorno.value?.mensagem,
                    (retorno.value?.erro == false) ? 'success' : 'error'
                ).then(()=>{
                    iframeForm.submit();
                })
            })
        }

    </script>
    <form method="post" id="formZerar" style="display: none;">
        <input type="hidden" name="acao" id="acaoZerar" value="">
    </form>
</div>
<?php incluirTela("cabecalho_info", $config, $matricula); ?>
</body>
</html>
