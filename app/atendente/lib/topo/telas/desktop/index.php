<?php
include_once($_SERVER['DOCUMENT_ROOT']."/especifico/inc/analyticstracking.php");

if (defined('URL_LOGO_PEGUENA')) { ?>
    <style>
    #logo h1 a {
    	background:transparent url(<?php echo URL_LOGO_PEGUENA; ?>) 0 0 no-repeat;
        background-size: cover;
    }
    </style>
    <?php } ?>

<section id="topo">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;" id="nomeAlfamaOraculo">
                    <?= $config['tituloSistema']; ?>
                    <span style="color:#999;"> - <?= $config['tituloPainel']; ?> - V<?= $config['oraculo_versao']; ?></span>
                </a>
                <div class="btn-group pull-right" style="padding-top:0px;">
                    <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
                        <i class="icon-user"></i> <?= $informacoes['nome']; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/<?= $url[0]; ?>/configuracoes/meusdados">
                                <i class='icon-asterisk'></i> <?= $idioma['meusdados']; ?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="?opLogin=sair">
                                <i class='icon-share-alt'></i> <?= $idioma['sair']; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-collapse collapse">
                    <ul class="nav" id="menu-topbar" style="display:none;">
                        <li class="active">
                            <a href="/<?= $url[0]; ?>">
                                <?= $idioma['inicio']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoInicial['idsituacao']; ?>">
                                <?= $idioma['matriculas_pendentes']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaAtiva['idsituacao']; ?>">
                                <?= $idioma['matriculas_ativas']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoFim['idsituacao']; ?>">
                                <?= $idioma['matriculas_finalizadas']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoCancelada['idsituacao']; ?>">
                                <?= $idioma['matriculas_canceladas']; ?>
                            </a>
                        </li>
<!--                        <li>
                            <a href="/<?= $url[0]; ?>/financeiro/faturas">
                                <?= $idioma['faturas']; ?>
                            </a>
                        </li>
                        <li>
                            <a href="/<?= $url[0]; ?>/configuracoes/valores_cursos">
                                <?= $idioma['valores_cursos']; ?>
                            </a>
                        </li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div id="menuLogo" class="hidden-phone">
            <div id="logo">
                <h1><a href="/<?= $url[0]; ?>" title="Or&aacute;culo">Oráculo</a></h1>
            </div>
            <div id="divMenu">
                <ul id="menu">
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>">
                            <div class="divImagem"><img src="/assets/icones/branco/24/home-branco_24.png" /></div>
                            <div class="divImagemHover"><img src="/assets/icones/preto/24/home_24.png" /></div>
                            <?= $idioma["inicio"]; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoInicial['idsituacao']; ?>">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/motivos_visitas_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/motivosdevisitas_24.png" />
                            </div>
                            <?= $idioma['matriculas_pendentes']; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaAtiva['idsituacao']; ?>">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/motivos_visitas_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/motivosdevisitas_24.png" />
                            </div>
                            <?= $idioma['matriculas_ativas']; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoFim['idsituacao']; ?>">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/motivos_visitas_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/motivosdevisitas_24.png" />
                            </div>
                            <?= $idioma['matriculas_finalizadas']; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/academico/matriculas?q[1|m.idsituacao]=<?= $situacaoCancelada['idsituacao']; ?>">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/motivos_visitas_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/motivosdevisitas_24.png" />
                            </div>
                            <?= $idioma['matriculas_canceladas']; ?>
                        </a>
                    </li>
<!--                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/financeiro/faturas">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/repasses_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/repasses_24.png" />
                            </div>
                            <?= $idioma['faturas']; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/configuracoes/valores_cursos">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/repasses_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/repasses_24.png" />
                            </div>
                            <?= $idioma['valores_cursos']; ?>
                        </a>
                    </li>-->
                    <li>
                        <a href="/<?= $url[0]; ?>/relacionamento/mural">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/murais_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/murais_24.png" />
                            </div>
                            <?= $idioma["mural"]; ?>
                        </a>
                    </li>
                    <li>
                        <a href="/<?= $url[0]; ?>/relatorios">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/relatorio-branco_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/relatorio_24.png" />
                            </div>
                            <?= $idioma["relatorios"]; ?>
                        </a>
                    </li>

                    <li class="menu_right semSubMenu">
                        <a href="?opLogin=sair">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/arrow_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/arrow_24.png" />
                            </div>
                            <?= $idioma['sair']; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>