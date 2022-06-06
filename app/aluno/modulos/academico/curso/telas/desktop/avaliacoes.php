<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <style>
        .alerta-prova {
            color:#DFB66F;
            font-size:15px;
            font-weight: bold;
            cursor: help;
            float:right;
        }
        .texto_instrucao {
            background: #E6E6E6;
            width: 80%;
            margin: 0 auto;
            padding: 21px 36px 12px 36px;
        }
    </style>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div id="divScroll" class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->
        <!-- Box -->
        <div class="box-side box-bg">
            <span class="top-box box-azul">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-file-text"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2>
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">
                    <div class="abox extra-align">
                        <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                        <?php } ?>
                        <div class="row-fluid m-box">
                            <div class="span3">
                                <div class="imagem-item"><img src="/api/get/imagens/cursos_imagem_exibicao/249/138/<?php echo $curso["imagem_exibicao_servidor"]; ?>" alt="Curso" /></div>
                            </div>
                            <div class="span9">
                                <div class="row-fluid show-grid">
                                    <div class="span12 description-item">
                                        <div class="span8">
                                            <h1><?php echo $curso['nome']; ?></h1>
                                            <p><?php echo $curso['aluno']; ?> <strong><?php echo $usuario['nome']; ?></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span12 center-align" style="overflow-x: auto;">
                                <?php if( !$cargaCompleta && $ava['contabilizar_datas'] == 'S' ) { ?>
                                    <div class="alert alert-warning fade in" style="width:80%!important;">
                                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                        <strong><?php echo $idioma['carga_incompleta']; ?></strong>
                                    </div>
                                <?php } ?>
                                <!-- <div class="span12">
                                       
                                </div>  -->
                                <table>
                                    <thead class="a-table">
                                    <tr bgcolor=#e6e6e6>
                                        <td><?php echo $idioma['tabela_nome']; ?></td>
                                        <td><?php echo $idioma['tabela_opcoes']; ?></td>
                                        <td><?php echo $idioma['tabela_tipo_correcao']; ?></td>
                                        <td><?php echo $idioma['tabela_de']; ?></td>
                                        <td><?php echo $idioma['tabela_ate']; ?></td>
                                        <td><?php echo $idioma['tabela_qtd_tentativa']; ?></td>
                                        <td><?php echo $idioma['tabela_ult_tentativa']; ?></td>
                                        <td><?php echo $idioma['tabela_nota']; ?></td>
                                    </tr>
                                    </thead>
                                    <tbody class="b-table">
                                    <?php
                                    foreach($avaliacoes as $avaliacao) {
                                        if(!$avaliacao['tentativas'])
                                            $avaliacao['tentativas'] = 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $avaliacao['nome'];
                                                if (
                                                    $avaliacao['tentativas'] > 0
                                                    && ($avaliacao['tentativas'] < $avaliacao['qtde_tentativas'] || empty($avaliacao['qtde_tentativas']))
                                                    && $avaliacao['nota'] < $avaliacao['nota_minima']
                                                ) {
                                                    ?>
                                                    <span class="icon-warning-sign alerta-prova" rel="tooltip" data-placement="right" data-original-title="Parece que você não atingiu a nota necessária, tente novamente!"></span>
                                                    <?php
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php

                                                $dataHoje = new DateTime();
                                                $de = new DateTime($avaliacao['periode_de'] . '00:00:00');
                                                $ate = new DateTime($avaliacao["periode_ate"] . '00:00:00');

                                                if ($avaliacao['intervalo_tentativas'] && $tentativas == 0) {
                                                    $intervaloDisponivel = true;
                                                } elseif ($avaliacao["intervalo_tentativas"] && $tentativas > 0) {
                                                    $dataHoraHoje = new DateTime();
                                                    $tempo = explode(':',  $avaliacao['intervalo_tentativas']);
                                                    $proximaTentativa = (new \DateTime($tentativas['inicio']))->modify('+ ' . $tempo[0] . ' hours' . $tempo[1] . ' minutes' . $tempo[2] . ' seconds');
                                                    $data_formatada = ($proximaTentativa->format('d/m/Y H:i'));
                                                    $intervaloDisponivel = ($dataHoraHoje >= $proximaTentativa) ? true : false;
                                                } else {
                                                    $intervaloDisponivel = true;
                                                }

                                                if(
                                                    (!$avaliacao["qtde_tentativas"] || ($avaliacao["tentativas"] < $avaliacao["qtde_tentativas"])) &&
                                                    ($de <= $dataHoje && $ate >= $dataHoje) &&
                                                    (!$avaliacao["nota_minima"] || ($avaliacao["nota"] < $avaliacao["nota_minima"])) &&
                                                    ($avaliacao['exibir_ava'] == 'S') &&
                                                    ($podeFazer) &&
                                                    verificaPermissaoAcesso(false)
                                                ) { ?>
                                                    <?php if( $ava['contabilizar_datas'] == 'S' ){ ?>
                                                        <?php if( $cargaCompleta ){ ?>
                                                            <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $avaliacao['idavaliacao']; ?>/realizar" class="abrirModalAdicionar">
                                                                <div class="btn btn-verde"><?= $idioma['fazer_prova']; ?></div>
                                                            </a>
                                                        <?php }else{ ?>
                                                            <a href="javascript:alert('<?php echo $idioma["js_carga_incompleta"]; ?>');">
                                                                <div class="btn btn-amarelo btn-mob"><?php echo $idioma["inabilitado"]; ?></div>
                                                            </a>
                                                        <?php } ?>
                                                    <?php }else{ ?>
                                                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $avaliacao['idavaliacao']; ?>/realizar" class="abrirModalAdicionar">
                                                            <div class="btn btn-verde"><?= $idioma['fazer_prova']; ?></div>
                                                        </a>
                                                    <?php } ?>
                                                    <?php
                                                    if ($avaliacao["tentativas"]) {
                                                        ?>
                                                        <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $avaliacao['idavaliacao']; ?>/tentativas"><div class="btn btn-verde btn-mob"><?= $idioma["critica"] ?></div></a>
                                                        <?php
                                                    }
                                                } elseif (!$podeFazer && ($de <= $dataHoje && $ate >= $dataHoje)) { ?>
                                                    <div class="btn btn-amarelo btn-mob"><?php echo $idioma["indisponivel"]; ?></div>
                                                <?php } elseif ($avaliacao["tentativas"] > 0) { ?>
                                                    <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $avaliacao['idavaliacao']; ?>/tentativas"><div class="btn btn-verde btn-mob"><?php echo $idioma['visualizar_prova']; ?></div></a>
                                                <?php } else { ?>
                                                    <div class="btn btn-vermelho btn-mob no-click"><?php echo $idioma["fora_periodo"]; ?></div>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $idioma['tipo_correcao'][$avaliacao['avaliador']]; ?></td>
                                            <td><?php echo formataData($avaliacao['periode_de'],'br',0); ?></td>
                                            <td><?php echo formataData($avaliacao['periode_ate'],'br',0); ?></td>
                                            <td>
                                                <?php echo $avaliacao['tentativas']; if($avaliacao['qtde_tentativas']) { echo '/'.$avaliacao['qtde_tentativas']; } ?>
                                            </td>
                                            <td><?php if($avaliacao['ultima_tentativa']) { echo formataData($avaliacao['ultima_tentativa'],'br',1); } else { echo '--'; } ?></td>
                                            <td><?php if($avaliacao['nota']) { echo $avaliacao['nota']; } else { echo '--'; } ?></td>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <?php
                                    $instrucoes = $ava['instrucoes'];
                                    $instrucoes2 = $ava['instrucoes2'];
                                    $matriculaObj = new Matriculas();
                                    $visualizacoesSituacao = $matriculaObj->retornarVisualizacoesSituacao($matricula['idsituacao']);
                                    if ((($podeGerarDiploma['total'] > 0 || $matricula['alunoAprovadoNotas']) && $matricula['alunoAprovadoNotasDias']) && $qtdRespostaCorreta > 0) {
                                        if ($matricula['alunoAprovadoNotas'] && empty($podeGerarDiploma['idfolha'])) {
                                            $idFolha = $matricula['oferta_curso']['idfolha'];
                                        } else {
                                            $idFolha = $podeGerarDiploma['idfolha'];
                                        }
                                        ?>
                                        <?php if($visualizacoesSituacao[74]){
                                        if ($matricula['escola']['idestado'] == 10 && $curso['codigo'] == 'REC' && $matricula['detran_certificado'] == 'N') { ?>
                                            <a href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $matricula['idmatricula']; ?>/<?= $ava['idava']; ?>/diploma/<?= $idFolha; ?>" target="_blank">
                                                <div class="btn btn-amarelo btn-mob" style="margin-bottom: 3rem;"><?= $idioma['diploma']; ?></div>
                                            </a>
                                        <?php } else { ?>
                                            <h2 class="texto_certificado"><?php echo $idioma["texto_btn_conclusao_certificado"] ?></h2>
                                            <a href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $matricula['idmatricula']; ?>/<?= $ava['idava']; ?>/diploma/<?= $idFolha; ?>" target="_blank">
                                                <div class="btn btn-amarelo btn-mob" style="margin-bottom: 3rem;"><?= $idioma['diploma']; ?></div>
                                            </a>
                                        <?php } }?>
                                        <div class="texto_instrucao">
                                            <p><?php echo $instrucoes; ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if(count($avaliacoes) <= 0) { ?>
                                    <table width="100%" border="1" bordercolor=#d3d7da cellspacing="1" cellpadding="5">
                                        <tbody class="c-table">
                                        <tr>
                                            <td><i><?php $idioma['nenhuma_avaliacao']; ?></i></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/js/validation.js"></script>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript">
    function descerScroll() {
        var objScrDiv = document.getElementById("divScroll");
        objScrDiv.scrollTop = objScrDiv.scrollHeight;
    }
    jQuery.noConflict();
    $(document).ready(function() {
        // Support for AJAX loaded modal window.
        // Focuses on first input textbox after it loads the window.
        $('.abrirModalAdicionar').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var atendimento = url.split('/')[4];
            if (url.indexOf('#') == 0) {
                $(url).modal('open').on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
            } else {
                $.get(url, function(data) {
                    $('<div class="modal hide fade text-side-two extra-align" id="idModal" tabindex="-1" role="dialog" style="overflow:auto; position: absolute;" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-body2">'+data+'</div></div>').modal().on('shown', function () { descerScroll()}).on("hidden", function () { $(this).remove(); });
                }).success(function() {
                    $('input:text:visible:first').focus();
                });
            }
        });
    });
    <?php if( !$cargaCompleta && $ava['contabilizar_datas'] == 'S' ) { ?>
    alert('<?php echo $idioma['carga_incompleta']; ?>');
    <?php } ?>
</script>

<link rel="stylesheet" href="/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
</html>