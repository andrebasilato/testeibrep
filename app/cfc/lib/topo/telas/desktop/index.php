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
                        <?php if ($_SESSION['modificar_matricula'] == 'S') { ?>
                            <li>
                                <a href="/<?= $url[0]; ?>/financeiro/faturas">
                                    <?= $idioma['faturas']; ?>
                                </a>
                            </li>
                            <li>
                                <a href="/<?= $url[0]; ?>/financeiro/fechamento_caixa">
                                    <?= $idioma['fechamento_caixa']; ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/configuracoes/valores_cursos">
                                <?= $idioma['valores_cursos']; ?>
                            </a>
                        </li>


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
                    <li>
                        <a href="/<?= $url[0]; ?>/academico/matriculas" class="drop">
                            <div class="divImagem"><img src="/assets/icones/branco/24/checklist_24.png" /></div>
                            <div class="divImagemHover"><img src="/assets/icones/preto/24/checklist_24.png" /></div>
                            <?= $idioma["academico"]; ?>
                        </a>
                        <div class="dropdown_2columns">
                            <div class="col_2">
                                <ul class="linkIcone">
                                    <? if($permissoes["matriculas|1"]) { ?>
                                        <li><a href="/<?= $url[0]; ?>/academico/matriculas" style="background-image: url(/assets/icones/preto/32/motivosdevisitas_32.png)"> <strong><?= $idioma["matriculas"]; ?></strong> <span><?= $idioma["matriculas_descricao"]; ?></span></a></li>
                                    <? } ?>
                                    <? if($permissoes["matriculas|2"]) { ?>
                                        <li class="microMenu"> <a href="/<?= $url[0]; ?>/academico/matriculas/novamatricula"> <strong><?= $idioma["nova_matriculas"]; ?></strong></a> </li>
                                    <? } ?>
                                    <? if($permissoes["ofertas|1"]) { ?>
                                        <li><a href="/<?= $url[0]; ?>/academico/ofertas" style="background-image:url(/assets/icones/preto/32/agendamentos_32.png)"> <strong><?= $idioma["ofertas"]; ?></strong> <span><?= $idioma["ofertas_descricao"]; ?></span></a></li>
                                    <? } ?>
                                    <? if($permissoes["turmas|1"]) { ?>
                                        <li><a href="/<?= $url[0]; ?>/academico/turmas" style="background-image:url(/assets/icones/preto/32/agendamentos_32.png)"> <strong><?= $idioma["turmas"]; ?></strong> <span><?= $idioma["turmas_descricao"]; ?></span></a></li>
                                    <? } ?>
                                    <? if($permissoes["avas|1"]) { ?>
                                        <li><a href="/<?= $url[0]; ?>/academico/avas" style="background-image:url(/assets/icones/preto/32/grupo_contratos_32.png)"> <strong><?= $idioma["avas"]; ?></strong> <span><?= $idioma["avas_descricao"]; ?></span></a></li>
                                    <? } ?>
                                    <li><a href="/<?= $url[0]; ?>/academico" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="semSubMenu">
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
                        <a href="/<?= $url[0]; ?>/financeiro/fechamento_caixa">
                            <div class="divImagem">
                                <img src="/assets/icones/branco/24/repasses_24.png" />
                            </div>
                            <div class="divImagemHover">
                                <img src="/assets/icones/preto/24/repasses_24.png" />
                            </div>
                            <?= $idioma['fechamento_caixa']; ?>
                        </a>
                    </li>
                    <!--                    < ?php } ?>-->
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