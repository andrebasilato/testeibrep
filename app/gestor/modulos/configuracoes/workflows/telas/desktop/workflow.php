<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <link rel="stylesheet" href="/assets/plugins/bootstrap-select2/select2.css">
    <link rel="stylesheet" href="/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="/assets/plugins/aw-workflow/workflow.css">
    <link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css">
    <style type="text/css">
        .colorpicker.colorpicker-with-alpha {
            width: 150px;
        }
        .mudarTop {
            top: -100% !important;
        }
        .modal.fade.in.mudarTop {
            top: 50% !important;
        }

        .page-header h1, .tituloEdicao, h1, h2, h3, h4, h5, h6 {
            text-transform: uppercase;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            font-weight: 700;
            color: #333;
            text-rendering: optimizelegibility;
        }

        h4 small, h5 {
            font-size: 12px;
        }

        h4, h5, h6 {
            line-height: 18px;
        }

        * {
            box-sizing: border-box;
        }

        .modalworkflow input[type=text], .modalworkflow select {
            width: 100%;
        }

        .input-append .add-on, .input-prepend .add-on, input, select {
            height: 30px;
        }

        .m-t-20 {
            margin-top: 20px!important;
        }

        .text-right {
            text-align: right!important;
        }

        .ocultar {
            display: none;
        }
    </style>
    <script type="text/javascript">
        var bloqueio_workflow = <?= intval($bloqueio_workflow); ?>;
    </script>
</head>
<body>
<?php incluirLib("topo", $config, $usuario);
$buscaGet = null;
if (isset($_GET["q"])) {
    $buscaGet = $_GET["q"];
}
?>
<div class="container-fluid">
    <section id="global">
        <div class="page-header">
            <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                <small class="hidden-phone"><?= $idioma["pagina_subtitulo"]; ?></small>
            </h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a>
                <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a>
                <span class="divider">/</span></li>
            <li class="active"><?= $workflow["titulo"]; ?></li>
            <span class="pull-right visible-desktop" style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
        </ul>
    </section>
    <div class="row-fluid">
        <div class="span12">
            <div class="box-conteudo box-conteudo-nopadding">
                <div class="box-cabecalho">
                    <div class=" pull-right" style="margin-top: 5px;">
                        <?php
                        if (!$bloqueio_workflow) {
                            ?>
                            <form class="form-search" onsubmit="return false;">
                                <select name="selecionaEmpreedimentos" id="selecionaEmpreedimentos">
                                    <option value="/gestor/configuracoes/workflows">Visualizar todos</option>
                                    <?php foreach ($config['workflows'] as $ind => $wf) { ?>
                                        <option value="/gestor/configuracoes/workflows/<?= $ind; ?>" <?php if ($ind == $url[3]) {
                                            echo "selected";
                                        } ?>><?= strtoupper($wf["titulo"]); ?></option>
                                    <?php } ?>
                                </select>
                                <a href="javascript:void(0);" class="btn" onclick="abrirEmpreendimento('selecionaEmpreedimentos','parent',1)">Abrir</a>
                            </form>
                            <?php
                        }
                        ?>
                        <!-- <a href="/gestor/configuracoes/workflows" class="btn btn-mini"><i class="fa fa-mail-reply"></i> Sair</a> -->
                    </div>
                    <h2 class="tituloEdicao"><?= $workflow["titulo"]; ?></h2>
                </div>
                <div class="box-subconteudo">
                    <div class="panel no-border panel-condensed no-margin" id="painelWorkflow">
                        <div class="panel-body ">
                            <div class="workflow" id="workflow">
                                <div class="wf-control">
                                    <label for="" id="statusbar"></label>
                                    <a class="wf-versao" >v2.1</a>
                                    <?php
                                    if (!$bloqueio_workflow) {
                                        ?>
                                        <a class="wf-bt-action btn objSelecionado" href="javascript:void(0)" id="label-objeto" style="display: none">SELECIONE</a>
                                        <a class="wf-bt-action btn" href="javascript:void(0)" onclick="createBox();"><i class="fa fa-pencil"></i>NOVO</a>
                                        <a class="wf-bt-action btn" href="javascript:void(0)" onclick="saveData(this);"><i class="fa fa-floppy-o "></i>SALVAR</a>
                                        <a class="wf-bt-action btn" href="javascript:void(0)" onclick="exibirOrdem();"><i class="fa fa-sort"></i>ORDEM</a>
                                        <?php
                                    }
                                    ?>
                                    <a class="wf-bt-action btn" href="javascript:void(0)" onclick="ampliarWorkflow(); exibirOcultarTopo();"><i class="fa fa-search"></i>ZOOM</a>
                                </div>
                                <canvas class="canvas" id="canvas" width="400" height="600"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php incluirLib("rodape", $config, $usuario); ?>
</div>


<div class="modal fade slide-up disable-scroll modalworkflow mudarTop" id="modalWorkFlowBlocos" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog ">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h5 id="modalTitulo">Editar <span class="semi-bold">Atributos</span></h5>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="span7">
                            <div class="form-group form-group-default">
                                <label>Titulo da Situação</label>
                                <input type="text" class=" form-control " value="" id="wf-situacao"/>
                            </div>
                        </div>

                        <div class="span5">
                            <div class="form-group form-group-default">
                                <label>Sigla</label>
                                <input type="text" class=" form-control " value="DES" id="wf-sigla"/>
                            </div>
                        </div>


                    </div>

                    <div class="row-fluid">
                        <div class="span4">
                            <div class="form-group form-group-default form-group-default-select2 ">
                                <label class="">Ordem</label>
                                <select class="full-width" data-placeholder="Ordem" data-init-plugin="select2" id="wf-ordem">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="form-group form-group-default">
                                <label>Cor do texto</label>
                                <input type="text" class="colorpickjs form-control colorpicker-component" value="#5367ce" id="wf-cortx"/>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="form-group form-group-default">
                                <label>Cor de fundo</label>
                                <input type="text" class="colorpickjs form-control colorpicker-component" value="#5367ce" id="wf-corbg"/>
                            </div>
                        </div>
                    </div>


                    <div class="row-fluid m-t-20">
                        <div class="span12">
                            <div class="grupo-atributos">
                                <div class="grupo-base" id="atrib-base">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (!$bloqueio_workflow) {
                        ?>
                        <div class="row-fluid" style="margin-top: 10px">
                            <div class="span12 m-t-10 sm-m-t-10 text-right">
                                <button type="button" class="btn btn-danger " onclick="workflowRemover()">Remover</button>
                                <button type="button" class="btn btn-success " onclick="workflowAtualizar()">Atualizar
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<div class="modal fade slide-up disable-scroll modalworkflow mudarTop" id="modalWorkFlowRequisito" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog ">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h5 id="modalTituloRequisito">Editar <span class="semi-bold">Ações e pré requisitos</span></h5>
                </div>
                <div class="modal-body">
                    <div class="row-fluid">
                        <?php
                        if (!$bloqueio_workflow) {
                            ?>
                            <div class="span6">
                                <div class="form-group form-group-default form-group-default-select2 ">
                                    <label class="">Açoes</label>
                                    <select class="full-width wf-modal-campocombo" data-placeholder="Ações"  id="wf-acoes">
                                    </select>
                                    <button class="btn btn-success wf-modal-btcombo" onclick="wfaddAcoes()"  ><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <?php
                        }
                        if (!$bloqueio_workflow) {
                            ?>
                            <div class="span6">
                                <div class="form-group form-group-default form-group-default-select2 ">
                                    <label class="">Pré-Requisitos</label>
                                    <select class="full-width wf-modal-campocombo" data-placeholder="requisito" id="wf-requisitos">
                                    </select>
                                    <button class="btn btn-success wf-modal-btcombo" onclick="wfaddRequisito()"  ><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="row-fluid m-t-20">
                        <div class="span12">
                            <div class="grupo-atributos">
                                <div class="grupo-base " id="atrib-relacionamento">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (!$bloqueio_workflow) {
                        ?>
                        <div class="row-fluid" style="margin-top: 10px">
                            <div class="span12 text-right">
                                <button type="button" class="btn btn-danger " onclick="workflowRemoverRelacionamento()">Deletar</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<script src="/assets/plugins/aw-workflow/blocks.js"></script>
<script src="/assets/plugins/aw-workflow/workflow.js"></script>
<script src="/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="/assets/plugins/bootstrap-select2/select2.min.js"></script>
<script>

    // javascript:(function(){var script=document.createElement('script');script.onload=function(){var stats=new Stats();document.body.appendChild(stats.dom);requestAnimationFrame(function loop(){stats.update();requestAnimationFrame(loop)});};script.src='//rawgit.com/mrdoob/stats.js/master/build/stats.min.js';document.head.appendChild(script);})()

    workflowLink = '/<?= $url[0]; ?>/configuracoes/workflows/<?= $url[3]; ?>/json';

  /*  function ampliarWorkflow() {
        $('#painelWorkflow').toggleClass('amplicawf');
        organizar();
    }*/

    if (bloqueio_workflow) {
        $('input').prop('disabled', true);
        $('select').prop('disabled', true);
    }

    function workflowRemover() {
        console.log('removendo os dados do workflow');
        workFlowRemove();
        unsetObject();
        $('#modalWorkFlowBlocos').modal('hide');
    }

    function workflowAtualizar() {
        var total = 0;
        var obj = getObject();

        obj.nome = $('#wf-situacao').val();
        obj.sigla = $('#wf-sigla').val();

        obj.cor_bg = $('#wf-corbg').val().replace('#', '');
        obj.cor_nome = $('#wf-cortx').val().replace('#', '');

        obj.ordem = $('#wf-ordem').val();//.replace('#', '');

        $('#colunaFlag').find('input').each(function () {
            if ($(this).attr('disabled') != 'disabled') {
                if ($(this).is(':checked')) {
                    obj[$(this).val()] = 'S';
                } else {
                    obj[$(this).val()] = 'N';
                }
            }
        })

        obj.acoes = new Array();

        $('.modalAcoes').find('input').each(function () {

            // console.log($(this).val(),$(this).is(':checked'));
            if ($(this).is(':checked')) {
                obj.acoes.push({'idopcao': $(this).val()});
            }

            //console.log($(this));
            /*if($(this).attr('disabled') != 'disabled'){
             console.log('verificar esse', $(this).val(),$(this).attr('checked'));
             console.log($(this));
             }*/
        })


        setObjectDB(obj);
        unsetObject();
        $('#modalWorkFlowBlocos').modal('hide');
    }


    $(document).ready(function () {
        console.log('ready ok, color picker')
        $('#wf-cortx').colorpicker();
        $('#wf-corbg').colorpicker();
    });

    $('#label-objeto').click(function () {
        workFlowModal(bloqueio_workflow);
    });


    ///<?= $config["urlSistema"]; ?>/<?= $url[0]; ?>/configuracoes/workflows/<?= $url[3]; ?>/json

    function reloadSwf() {
        window.location.reload();
    }

    function abrirEmpreendimento(objId, targ, restore) {
        var selObj = null;
        with (document) {
            if (getElementById) selObj = getElementById(objId);
            if (selObj) eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
            if (restore) selObj.selectedIndex = 0;
        }
    }

    function exibirOcultarTopo () {
        $('.navbar').toggleClass('ocultar');
    }
</script>
</body>
</html>