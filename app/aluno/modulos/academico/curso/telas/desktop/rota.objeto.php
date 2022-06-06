<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario);
    if (!$idioma[$informacoes['pagina_anterior']]) {
        $idioma[$informacoes['pagina_anterior']] = "Ambiente de Estudo";
    } ?>
    <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
    <link media="screen" href="/assets/css/acessibilidade.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .book-box p {
            margin: 10px;
            margin-left: 30px;
            margin-bottom: 4px;
        }

        .visualizacao-pagina {
            line-height: 3.9rem;
            font-size: 13px;
            margin-left: 2.5rem;
        }

        .icone_contabilizado {
            font-size: 20px;
        }

        .visualizacao-pagina {
            margin-left: 5.5rem;
        }

        @media (max-width: 530px) {
            .visualizacao-pagina {
                line-height: 5.9rem;
                margin-left: -12.5rem;
            }
        }
        #video {
            z-index: 1;
        }
        .embed-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }
        .embed-container iframe, .embed-container object, .embed-container embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->
        <!-- Ambientação -->
        <div class="box-side box-bg half-side-box">
            <div class="top-box box-verde">
                <h1><?php echo $idioma['conteudo'] . " / " . $ava['disciplina']; ?></h1>
                <?php // if($favorito) { ?>
                <!-- <i id="icone_favorito" class="icon-heart" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } else { ?>
                <!-- <i id="icone_favorito" class="icon-heart-empty" style="cursor:pointer;" onClick="favoritar()"></i> -->
                <?php // } ?>
                <span class="visualizacao-pagina">VISUALIZAÇÃO DA PÁGINA</span>
                <?php if ($contabilizado) { ?>
                    <i id="icone_contabilizado" class="icon-ok-sign cone_contabilizado icone_contabilizado"
                       style="float: initial;"></i>
                <?php } else { ?>
                    <i id="icone_contabilizado" class="icon-ok-circle cone_contabilizado icone_contabilizado"
                       style="float: initial;"></i>
                <?php } ?>
                <div class="acessibilidade">
                    <i class="icon-minus" style="cursor:pointer;" onClick="changeFontSize('-');"
                       title="Diminuir tamanho do texto"></i>
                    <i class="icon-plus" style="cursor:pointer;" onClick="changeFontSize('+');"
                       title="Aumentar tamanho do texto"></i>
                    <i class="icon-adjust" style="cursor:pointer;" onClick="toggleContrast();"
                       title="Alternar contraste"></i>
                </div>
            </div>
            <i class="set-icon icon-caret-up"></i>
            <div class="clear"></div>
            <?php
            if (!$retornoAcesso['erro']) {
                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="abox m-box">
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav">
                                    <?php if ($objeto['objeto_anterior']['idobjeto']) { ?>
                                        <? $conteudo = $objeto['objeto_anterior']['tipo'] == 'reconhecimento' ? '?voltar=1' : '#conteudo'; ?>
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . $conteudo; ?>">
                                            <hr>
                                            <div><i class="icon-chevron-left"></i></div>
                                            <p><?php echo $idioma['anterior']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <?php
                                        $anterior = $ava['idava'];
                                        foreach ($idAvas as $ind => $idAva) {
                                            if ($anterior == $idAva && $ind > 0) {
                                                $anterior = $idAvas[$ind - 1];
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($anterior == $ava['idava']) { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div><i class="icon-chevron-left"></i></div>
                                                <p><?php echo $idioma['inicio']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $anterior . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div><i class="icon-chevron-left"></i></div>
                                                <p><?php echo $idioma['anterior']; ?></p>
                                            </a>

                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php if ($objeto['objeto_anterior']['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_anterior_anterior']['idobjeto']) { ?>
                                            <a class="" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 2) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_anterior_anterior']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_anterior']['idobjeto'] && $objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                            <a class="" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_anterior']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item"
                                         style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . $url[6] . '#conteudo'; ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <p><?php echo $objeto['objeto']['nome']; ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_proximo']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 2) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_proximo_proximo']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                        <? if (!$objeto['objeto_proximo_proximo'] && (!$downloadsEbooksFeitos)) { ?>
                                        <a class="" onclick="alert('<? echo $idioma['sem_download_ebooks']; ?>')"
                                           href="#">
                                            <? } else { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <? } ?>
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <?php
                                                if ($objeto['objeto_proximo'] && $objeto['objeto_proximo']['tipo'] == 'reconhecimento') {
                                                    $referencia = 'objeto_proximo_proximo';
                                                } else {
                                                    $referencia = 'objeto_proximo';
                                                }
                                                ?>
                                                <p><?php echo $objeto[$referencia]['objeto']['nome']; ?></p>
                                            </a>
                                            <?php } else { ?>
                                                <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                            <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="nav-breadcrumb-item last-nav">
                                    <?php

                                    if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                    <? if (!$objeto['objeto_proximo_proximo'] && (!$downloadsEbooksFeitos)) { ?>
                                    <a class="" onclick="alert('<? echo $idioma['sem_download_ebooks']; ?>')" href="#">
                                        <? } else { ?>
                                        <a class="btnProximo" href="<?php if ($preRequisito) {
                                            echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                        } else {
                                            echo 'javascript:preRequisito();';
                                        } ?>">
                                            <? } ?>
                                            <hr>
                                            <div><i class="icon-chevron-right"></i></div>
                                            <p><?php echo $idioma['proximo']; ?></p>
                                        </a>
                                        <?php } else { ?>
                                            <?php
                                            // Validar pela porcentagem mínima da Oferta/curso
                                            $matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
                                            $proximo = $ava['idava'];
                                            $trava_liberacao_temporaria_datavalid = false;
                                            foreach ($idAvas as $ind => $idAva) {
                                                if ($proximo == $idAva && $ind < count($idAvas) - 1) {
                                                    if ($matricula['liberacao_temporaria_datavalid'] == 'S' && $ind >= 1) $trava_liberacao_temporaria_datavalid = true;
                                                    $proximo = $idAvas[$ind + 1];
                                                    break;
                                                }
                                            } ?>
                                            <?php if ($trava_liberacao_temporaria_datavalid) { ?>
                                                <a href="<?= 'javascript:alert(\'' . sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))) . '\')' ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p><?php echo $idioma['fim_curso']; ?></p>
                                                </a>
                                            <?php } elseif ($proximo == $ava['idava']) { ?>
                                                <a href="<?= $ava['avaliacao_pendente'] || $ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? 'javascript:alert(\'' . ($ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? $idioma['impossibilitado_prosseguir_porcentagem'] : $idioma['impossibilitado_prosseguir']) . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p><?php echo $idioma['fim_curso']; ?></p>
                                                </a>

                                            <?php } else { ?>
                                                <a href="<?= $ava['avaliacao_pendente'] || $ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? 'javascript:alert(\'' . ($ava['porcentagem_ava']['porcentagem'] < $matricula['oferta_curso']['porcentagem_minima_disciplinas'] ? $idioma['impossibilitado_prosseguir_porcentagem'] : $idioma['impossibilitado_prosseguir']) . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p><?php echo $idioma['proximo']; ?></p>
                                                </a>

                                            <?php } ?>
                                        <?php } ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- Conteúdo -->
                            <div id="conteudo" class="extra-align contents">
                                <?php
                                switch ($objeto['tipo']) {
                                    case 'exercicio':
                                        require 'rota.objeto.exercicio.php';
                                        break;
                                    case 'reconhecimento':
                                        $imagemPrincipal = $reconhecimentoObj
                                            ->retornaImagemPrincipal($matricula['idmatricula']);
                                        require 'rota.objeto.reconhecimento.php';
                                        break;
                                    case 'enquete':
                                        require 'rota.objeto.enquete.php';
                                        break;
                                    case 'video':
                                        $html .= '<div class="imagem-item embed-container">';

                                        if ('html5' == $objeto['objeto']['variavel'] || 'interno' == $objeto['objeto']['variavel']) {
                                            if ($config['videoteca_local']) {
                                                $srcVideo = '/storage/videoteca/' . $caminho->caminho . '/' . $objeto['objeto']['idvideo'] . '/' . $objeto['objeto']['arquivo'] . '_hd.mp4';
                                                $srcImg = '/storage/videoteca/' . $caminho->caminho . '/' . $objeto['objeto']['idvideo'] . '/' . $objeto['objeto']['imagem'] . '.jpg';
                                            } else {
                                                $dominio = $config['videoteca_endereco'][rand(0, (count($config['videoteca_endereco']) - 1))];
                                                $srcVideo = $dominio . '/' . $caminho->caminho . '/' . $objeto['objeto']['video_nome'];
                                                $srcImg = $dominio . '/' . $caminho->caminho . '/' . $objeto['objeto']["video_imagem"];
                                            }

                                            $html .= '<video id="video-' . $objeto['objeto']['idvideo'] . '" width="100%" height="400px"
                                                    controls="controls" preload="none"
                                                    poster="' . $srcImg . '">
                                                    <source src="' . $srcVideo . '" type="video/mp4" ></source>
                                                </video>';
                                        } elseif ('youtube' == $objeto['objeto']['variavel'] || 'vimeo' == $objeto['objeto']['variavel']) {
                                            $html .= '<iframe class="videoIframe" src="' . $objeto['objeto']['arquivo'] . '?quality=540p" webkitallowfullscreen mozallowfullscreen allowfullscreen border="0" width="100%" height="400px" style="border: medium none;"></iframe>';
                                        }

                                        $html .= '</div>' . $objeto['objeto']['conteudo'];

                                        echo $html;
                                        break;
                                    default:
                                        if (strpos($objeto['objeto']['arquivo_tipo'], 'pdf') !== false) {
                                            echo "<p>" . nl2br($objeto['objeto']['descricao']) . "</p>";
                                            ?>
                                            <div class="clearfix"></div>
                                            <div style="width:50px;float:left;">
                                                <img src="/assets/img/icone_pdf.png" alt="">
                                            </div>
                                            <div style="margin-top:10px;">
                                                VOCÊ ESTÁ VISUALIZANDO O PDF
                                                <u><b><?= $objeto['objeto']['arquivo_nome'] ?></b></u><br>
                                            </div>
                                            <iframe
                                                    id="pdfDocument"
                                                    height="800"
                                                    width="100%"
                                                    src="<?= $_SERVER['SERVER_ADDRESS'] ?>/assets/plugins/pdfjs/web/viewer.html?file=<?= $_SERVER['SERVER_ADDRESS'] ?>/storage/avas_downloads_arquivo/<?= $objeto['objeto']['arquivo_servidor'] ?>"
                                                    frameborder="0">
                                            </iframe>
                                            <?php
                                        } else if ($objeto["objeto"]["imagem_exibicao_servidor"]) {
                                            echo '<div class="imagem-item">
	                                        <img src="/api/get/imagens/avas_conteudos_imagem_exibicao/x/x/' . $objeto['objeto']["imagem_exibicao_servidor"] . '" alt="Imagem">
	                                    </div>';
                                        }
                                        echo $objeto['objeto']['conteudo'];
                                        break;
                                }
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="nav-breadcrumb">
                                <div class="nav-breadcrumb-item first-nav">
                                    <?php if ($objeto['objeto_anterior']['idobjeto']) { ?>
                                        <? $conteudo = $objeto['objeto_anterior']['tipo'] == 'reconhecimento' ? '?voltar=1' : '#conteudo'; ?>
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . $conteudo ?>">
                                            <hr>
                                            <div><i class="icon-chevron-left"></i></div>
                                            <p><?php echo $idioma['anterior']; ?></p>
                                        </a>
                                    <?php } else { ?>
                                        <?php if ($anterior == $ava['idava']) { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div><i class="icon-chevron-left"></i></div>
                                                <p><?php echo $idioma['inicio']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $anterior . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div><i class="icon-chevron-left"></i></div>
                                                <p><?php echo $idioma['anterior']; ?></p>
                                            </a>

                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php if ($objeto['objeto_anterior']['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_anterior_anterior']['idobjeto']) { ?>
                                            <a href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 2) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_anterior_anterior']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_anterior']['idobjeto'] && $objeto['objeto_anterior']['tipo'] != 'reconhecimento') { ?>
                                            <a href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_anterior']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] != 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item"
                                         style="background:#F4F4F4;color:#333;border-bottom:1px #666 solid;margin-top: 0px;">
                                        <a href="<?php echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . $url[6] . '#conteudo'; ?>">
                                            <hr>
                                            <div><i class="icon-book"></i></div>
                                            <p><?php echo $objeto['objeto']['nome']; ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_proximo']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($objeto['tipo'] == 'reconhecimento') { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 2) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <p><?php echo $objeto['objeto_proximo_proximo']['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                        <hr>
                                        <div></div>
                                    </span>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="nav-breadcrumb-item no-mobile">
                                        <?php if ($objeto['objeto_proximo']['idobjeto']) { ?>
                                            <a class="btnProximo" href="<?php if ($preRequisito) {
                                                echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                            } else {
                                                echo 'javascript:preRequisito();';
                                            } ?>">
                                                <hr>
                                                <div><i class="icon-book"></i></div>
                                                <?php
                                                if ($objeto['objeto_proximo'] && $objeto['objeto_proximo']['tipo'] == 'reconhecimento') {
                                                    $referencia = 'objeto_proximo_proximo';
                                                } else {
                                                    $referencia = 'objeto_proximo';
                                                }
                                                ?>
                                                <p><?php echo $objeto[$referencia]['objeto']['nome']; ?></p>
                                            </a>
                                        <?php } else { ?>
                                            <span>
                                            <hr>
                                            <div></div>
                                        </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="nav-breadcrumb-item last-nav">
                                    <?php
                                    if ($objeto['objeto_proximo']['idobjeto']) {
                                        ?>
                                        <a class="btnProximo" href="<?php if ($preRequisito) {
                                            echo '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo';
                                        } else {
                                            echo 'javascript:preRequisito();';
                                        } ?>">
                                            <hr>
                                            <div><i class="icon-chevron-right"></i></div>
                                            <p><?= $idioma['proximo']; ?></p>
                                        </a>
                                        <?php
                                    } else { ?>
                                        <?php if ($trava_liberacao_temporaria_datavalid) { ?>
                                                <a href="<?= 'javascript:alert(\'' . sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))) . '\')' ?>">
                                                    <hr>
                                                    <div><i class="icon-chevron-right"></i></div>
                                                    <p><?php echo $idioma['fim_curso']; ?></p>
                                                </a>
                                        <?php } elseif ($proximo == $ava['idava']) { ?>
                                            <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] ?>">
                                                <hr>
                                                <div><i class="icon-chevron-right"></i></div>
                                                <p><?php echo $idioma['fim_curso']; ?></p>
                                            </a>

                                        <?php } else { ?>
                                            <a href="<?= $ava['avaliacao_pendente'] ? 'javascript:alert(\'' . $idioma['impossibilitado_prosseguir'] . '\')' : '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>">
                                                <hr>
                                                <div><i class="icon-chevron-right"></i></div>
                                                <p><?php echo $idioma['proximo']; ?></p>
                                            </a>

                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- /Conteúdo -->
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="content" id="acessoBloqueado">
                    <div class="box-bg">
                        <div style="text-align:center;padding: 1em;">
                            <img src="/assets/img/<?php echo $retornoAcesso['erro'][0] ?>" alt="Marca"/>
                            <div class="description-item">
                                <h1><?php echo $retornoAcesso['erro'][1]; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!-- /Ambientação -->
        <!-- Anotações -->
        <div class="box-side box-bg min-side-box">
            <span class="top-box box-cinza">
                <h1><?php echo $idioma['anotacoes']; ?></h1>
                <i class="icon-quote-left"></i>
            </span>
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="book-box" id="anotacoes">
                        <?php foreach ($anotacoes as $anotacao) { ?>
                            <a href="javascript:cadastrarDeletarAnotacao(<?php echo $anotacao["idanotacao"]; ?>);">x</a>
                            <p><?php echo $anotacao['anotacao']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="box-gray center-align extra-align">
                        <form class="no-margin">
                            <textarea name="anotacao" id="anotacao"
                                      placeholder="<?php echo $idioma['digite_anotacao']; ?>"
                                      class="box-textarea"></textarea>
                            <div class="btn btn-cinza btn-send"
                                 onClick="cadastrarDeletarAnotacao(0);"><?php echo $idioma['enviar']; ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Anotações -->
    </div>
    <!-- /Rota de aprendizagem -->
</div>
<!-- /Conteudo -->

<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript" src="/assets/plugins/jwerty/jwerty.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script type="text/javascript">
    var changeCounter = 0;

    function changeFontSize(e) {
        var el = $(".nav-breadcrumb-item a p, #conteudo, #conteudo *"),
            step = 2,
            maxIncrease = 6;

        if (e == '+' && changeCounter < maxIncrease) {
            el.each(function () {
                var actualSize = $(this).css('font-size');
                $(this).css("fontSize", parseInt(actualSize) + step);
            });

            changeCounter++;
        } else if (e == '-' && changeCounter) {
            el.each(function () {
                var actualSize = $(this).css('font-size');
                $(this).css("fontSize", parseInt(actualSize) - step);
            });

            changeCounter--;
        }
    }

    function toggleContrast() {
        var el = $(".nav-breadcrumb, #conteudo");
        el.toggleClass('contrast');
    }
</script>
<script type="text/javascript">
    <?php
    if (!$cargaCompleta && ($objeto['idobjeto'] == $ultimoConteudoRota) && $ava['contabilizar_datas'] == 'S') {
        $ultimoConteudo = true;
    }

    $tempoEmSegundos = tempoEmSegundos($objeto["tempo"]);
    echo "var tempoMinimo = Date.now() + {$tempoEmSegundos}000;";
    $anterior1 = $url[6] - 1;
    if ($objeto['objeto_anterior']['idobjeto']) {
        echo "jwerty.key('←', function () {window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\";});";
    }

    if ($objeto['objeto_proximo']['idobjeto']) {
        if (!$preRequisito) echo "function preRequisito() {alert('{$idioma['pre_requisito']}');}";
        echo "jwerty.key('→', function () {";

        if (!$preRequisito) echo "preRequisito();";
        else if (!$contabilizado) {
            echo "if (Date.now() > tempoMinimo) {
                    window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\";
                } else {
                    alert('{$idioma['tempo_minimo']}');
                }";
        } else {
            echo "window.location = \"/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$ava['idava']}/rota/{$anterior1}\"";
        }
        echo "});";
    }

    $urlIncompleto = '';
    if (!$cargaCompleta) {
        $urlIncompleto = "/incompleto";
    }
    if(!$contabilizado || !$cargaCompleta || $ultimoConteudo ) {
    $porcentagem_ava = 100;
    if ($curriculo['porcentagem_ava'])
        $porcentagem_ava = $curriculo['porcentagem_ava'];
    echo "setTimeout('contabilizar()', {$tempoEmSegundos}000);";
    ?>
    function contabilizar() {
        $.post('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/contabilizar<?php echo $urlIncompleto; ?>',
            {
                matricula: <?php echo $matricula['idmatricula']; ?>,
                ava: <?php echo $ava['idava']; ?>,
                objeto: <?php echo $objeto['idobjeto']; ?>,
                idmatricula: '<?php echo senhaSegura($matricula['idmatricula'], $config['chaveLogin']); ?>',
                idava: '<?php echo senhaSegura($ava['idava'], $config['chaveLogin']); ?>',
                idobjeto: '<?php echo senhaSegura($objeto['idobjeto'], $config['chaveLogin']); ?>'
            },
            function (json) {
                if (json.sucesso) {
                    $('#icone_contabilizado').attr('class', 'icon-ok-sign');
                    $('#barra_porcentagem').css('width', json.porcentagem + '%');

                    if (json.porcentagem >= <?php echo $porcentagem_ava; ?>)
                        $('#barra_porcentagem').css('background-color', '#228B22');

                    $('#porcentagem').html('Andamento do curso: <strong>' + json.porcentagem_formatada + '%</strong>');
                }
            },
            "json"
        );
    }
    <?php } ?>
    function favoritar() {
        $.msg({
            autoUnblock: false,
            clickUnblock: false,
            klass: 'white-on-black',
            content: '<?php echo $idioma["processando"]; ?>',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/favoritar',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        idmatricula: '<?php echo $matricula['idmatricula']; ?>',
                        idava: '<?php echo $ava['idava']; ?>',
                        idobjeto: '<?php echo $objeto['idobjeto']; ?>'
                    },
                    success: function (json) { //Se ocorrer tudo certo
                        if (json.sucesso) {
                            if (json.favorito == 'S') {
                                $('#icone_favorito').attr('class', 'icon-heart');
                            } else {
                                $('#icone_favorito').attr('class', 'icon-heart-empty');
                            }
                            self.unblock();
                        } else if (json.erro_json = 'sem_permissao') {
                            alert('<?php echo $idioma["sem_permissao"]; ?>');
                            self.unblock();
                        } else {
                            alert('<?php echo $idioma["json_erro"]; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }

    function cadastrarDeletarAnotacao(deletar) {
        var txtanotacao = $('#anotacao').val();
        var acao = 'cadastrar';
        if (deletar > 0)
            acao = 'deletar';

        $.msg({
            autoUnblock: true,
            clickUnblock: false,
            klass: 'white-on-black',
            content: '<?= $idioma['processando']; ?>',
            afterBlock: function () {
                var self = this;
                jQuery.ajax({
                    url: '/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/json/anotacao/' + acao,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        idmatricula: '<?php echo $matricula['idmatricula']; ?>',
                        idava: '<?php echo $ava['idava']; ?>',
                        idobjeto: '<?php echo $objeto['idobjeto']; ?>',
                        anotacao: txtanotacao,
                        idanotacao: deletar
                    },
                    success: function (json) { //Se ocorrer tudo certo
                        if (json.sucesso) {
                            $('#anotacao').val('');
                            var anotacoes = '';
                            for (var i = 0; i < json.anotacoes.length; i++) {
                                anotacoes += '<a href="javascript:cadastrarDeletarAnotacao(' + json.anotacoes[i].idanotacao + ');">x</a><p>' + json.anotacoes[i].anotacao + '</p>';
                            }
                            $('#anotacoes').html(anotacoes);
                            self.unblock();
                        } else if (json.erro_json = 'sem_permissao') {
                            alert('<?= $idioma['sem_permissao']; ?>');
                            self.unblock();
                        } else {
                            alert('<?= $idioma['json_erro']; ?>');
                            self.unblock();
                        }
                    }
                });
            }
        });
    }

    function acaoButton(id) {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/link/'; ?>" + id;
        $.post(url, function (data) {
            console.log('Clicou');
        });
    }

    var redireciona = true;
    <?php if(!empty($objeto['idconteudo'])){ ?>
    function verificaCliques(e, objeto) {
        var url = "<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/link/'; ?>" + <?= $url[6]; ?> + "/verifica/" + objeto;

        e.preventDefault();

        $.ajax({
            url: url,
            type: 'GET',
            data: '',
            contentType: false,
            processData: false,
            async: false
        }).done(function (respond) {
            if (respond.length > 0) {
                alert('Atenção, você precisa clicar nos itens abaixo: \n \n' + respond);
                redireciona = false;
            } else {
                redireciona = true;
            }
        });
    }
    <?php } ?>

    function verificarDias(e) {
        e.preventDefault();
        <?php if(!$preRequisitoDias1['sucesso']){ ?>
        alert('PARABÉNS\n' +
            'Você já completou o conteúdo de estudo programado para a data de hoje.\n' +
            'Assim como no trânsito, a VELOCIDADE de estudo precisa ser controlada… vamos desacelerar e, a partir de ' + '<?= $preRequisitoDias1['data'] ?> você poderá continuar com seus estudos!');
        redireciona = false;
        <?php } ?>
    }

    function verificarSimulados(e) {
        e.preventDefault();
        var idObjeto = <?php echo $objeto['objeto_proximo']['idobjeto'] ?: 'null'; ?>;
        var ultimoConteudo = <?php echo $ultimoConteudoRota; ?>;
        var simulados = <?php echo $simulados; ?>;
        var simuladosRealizados = <?php echo $simuladosRealizados; ?>;
        if (ultimoConteudo === idObjeto && simulados > 0 && simuladosRealizados === 0) {
            alert('<?php echo $idioma['simulado_obrigatorio']; ?>');
            redireciona = false;
        }
    }

    function redirecionaPag() {
        if (redireciona == true) {
            window.location.href = '<?= '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo'; ?>';
        }
    }

    function verificaTempoMinimo(e) {
        <?php if ($contabilizado) {
        echo "return true;";
    } else { ?>
        if (Date.now() > tempoMinimo) {
            return true;
        } else {
            alert('<?php echo $idioma['tempo_minimo']; ?>');
            e.preventDefault();
            redireciona = false;
            return false;
        }
        <?php } ?>
    }
    <?php if ($_GET['alert'] == 'verificarSimulados') {
        echo "alert('{$idioma['simulado_obrigatorio']}');";
        echo "window.location.href = '/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$url[4]}/rota/{$url[6]}#conteudo';";
    } else if ($_GET['alert'] == 'cargaCompleta') {
        $dataHoje = new DateTime($ava['data_inicio_ava']);
        $horario_liberado = $dataHoje->modify("+  {$ava['carga_horaria_min']} hours");
        $horario_liberado = $horario_liberado->format("d/m/Y H:i:s");
        $alert = sprintf($idioma['carga_incompleta'], $horario_liberado);
        echo "alert('{$alert}');";
        echo "window.location.href = '/{$url[0]}/{$url[1]}/{$url[2]}/{$matricula['idmatricula']}/{$url[4]}/rota/{$url[6]}#conteudo';";
    }
    ?>
    $(".btnProximo").on("click contextmenu", function (e) {
        <?php if(!empty($objeto['idconteudo'])){ ?>
        verificaCliques(e, '<?= $objeto['idconteudo'] ?>');
        <?php } ?>
        verificaTempoMinimo(e);
        verificarDias(e);
        verificarSimulados(e)
        redirecionaPag();
    });
    <?php
    if (strpos($objeto['objeto']['arquivo_tipo'], 'pdf') !== false) { ?>
    window.pdfDocument.addEventListener('load', function () {
        window.pdfDocument.contentDocument.getElementById('download').style.display = 'none';
        window.pdfDocument.contentDocument.getElementById('print').style.display = 'none';
    });
    <?php
    } ?>
</script>
<?php
if (!empty($config['marcaDaguaIframeVideo'] && $objeto['tipo'] == 'video')) {
    ?>
    <script>
        var id;
        var HeightVideo;
        var widthVideo;
        var timeOut;
        <?php
        if(!empty($usuario['documento'])){
        ?>
        var phrase = ["<?= $usuario['nome']; ?>", "<?= $usuario['documento']; ?>", "<?= $usuario['email']; ?>"];
        <?php
        } else {
        ?>
        var phrase = ["<?= $usuario['nome']; ?>", "<?= $usuario['email']; ?>"];
        <?php
        }
        ?>

        //Cria Id
        function makeId() {
            var text = "";
            var possible = "abcdefghijklmnopqrstuvwxyz";

            for (var i = 0; i < 5; i++){
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            return text;
        }

        //Sortea frase da array
        function sortPhrase() {
            return phrase[Math.floor(Math.random()*phrase.length)];
        }

        //Sortea posiÃ§Ã£o Top
        function topPosition() {
            var topMin = 0;
            var topMax = $(".videoIframe").height() - ($('#'+id).height() + 40);

            return Math.floor(Math.random()*(topMax-topMin+1)+topMin);
        }

        //Sortea posiÃ§Ã£o Left
        function leftPosition() {
            var leftMin = 0;
            var leftMax = $(".videoIframe").width() - ($('#'+id).width() + 40);

            return Math.floor(Math.random()*(leftMax-leftMin+1)+leftMin);
        }

        //Posiciona a div
        function position(id){
            var top = topPosition();
            var left = leftPosition();

            if (top > $(".videoIframe").offset().top + $(".videoIframe").height() - $('#'+id).outerHeight()) {
                top = $(".videoIframe").offset().top + $(".videoIframe").height() - $('#'+id).outerHeight();
            }

            if (left > $(".videoIframe").offset().left + $(".videoIframe").width() - $('#'+id).outerWidth()) {
                left = $(".videoIframe").offset().left + $(".videoIframe").width() - $('#'+id).outerWidth();
            }

            $('#'+id).css({
                top: top,
                left: left
            });
        }

        //Cria div da frase
        function createElement(){
            id = makeId();

            if ($(window).width() <= 768 ) {
                $("body").prepend("<div id='"+id+"' style='position: absolute; display:inline-table; color: #000000; font-size: 12px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; z-index: 2147483648;' >"+sortPhrase()+"</div>");
            } else {
                $("body").prepend("<div id='"+id+"' style='position: absolute; display:inline-table; color: #000000; font-size: 20px; background-color: rgba(255, 255, 255, 0.8); padding: 20px; z-index: 2147483648;' >"+sortPhrase()+"</div>");
            }

            $('#'+id).insertAfter( ".videoIframe" );

            position(id);

            timeOut = setTimeout(function(){ removeElement('#'+id); }, 3000); //Tempo do elemento em tela
        }

        //Remove div da frase
        function removeElement(a){
            $(a).remove();
            timeOut = setTimeout(createElement, 5000); //Tempo do intervalo sem o elemento em tela
        }

        createElement();

        //Verifica fullscreen e remove a div da frase
        $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange', function() {
            clearTimeout(timeOut);
            removeElement('#'+id);
        });
    </script>
    <?php
}
?>

<?php
if ($config['bloqueioConteudo']){ ?>
    <script language='JavaScript'>

    function mensagem(){
        alert('Conteúdo bloqueado!');
        return false;
    }

    function bloquearCopia(Event){
        var Event = Event ? Event : window.event;
        var tecla = (Event.keyCode) ? Event.keyCode : Event.which;
        if(tecla == 17){
            mensagem();
        }
    }

    document.onkeypress = bloquearCopia;
    document.onkeydown = bloquearCopia;
    document.oncontextmenu = mensagem;

    //Bloqueia seleção do conteúdo
    $("body").css('user-select', 'none');
    $("body").css('-o-user-select', 'none');
    $("body").css('-moz-user-select', 'none');
    $("body").css('-khtml-user-select', 'none');
    $("body").css('-webkit-user-select', 'none');

</script>
<?php
}
?>

</body>
</html>
