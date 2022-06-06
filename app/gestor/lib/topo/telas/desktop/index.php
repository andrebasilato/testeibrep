<?php
//echo $_SERVER['DOCUMENT_ROOT'];
include_once($_SERVER['DOCUMENT_ROOT']."/especifico/inc/analyticstracking.php");
?>
<?php if(defined('URL_LOGO_PEGUENA')) { ?>
    <style>
        #logo h1 a {
            background:transparent url(<?php echo URL_LOGO_PEGUENA; ?>) 0 0 no-repeat;
            background-size: cover;
        }
    </style>
<?php } ?>

<?
$permissoes = unserialize($informacoes["perfil"]["permissoes"]);
?>
<section id="topo">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;" id="nomeAlfamaOraculo"><?= $config["tituloSistema"]; ?><span style="color:#999;"> - <?= $config["tituloPainel"]; ?> - V<?= $config["oraculo_versao"]; ?></span></a>
                <div class="btn-group pull-right" style="padding-top:0px;">
                    <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#"> <i class="icon-user"></i> <?= $informacoes["nome"]; ?> (<?= $informacoes["perfil"]["nome"]; ?>) <span class="caret"></span> </a>
                    <ul class="dropdown-menu">
                        <li><a href="/gestor/configuracoes/meusdados"><i class='icon-asterisk'></i> Meus dados</a></li>
                        <li class="divider"></li>
                        <form id="form_manual" method="post" action="<?= $GLOBALS["config"]["link_manual"]; ?>/gestor" target="_blanck">
                            <li><a style="cursor:pointer;" onclick="javascript:document.getElementById('form_manual').submit();"><?= $idioma["manual"]; ?></a> </li>
                            <input type="hidden" name="idusuario" value="<?php echo $informacoes['idusuario']; ?>" />
                            <input type="hidden" name="login_manual" value="login_manual" />
                        </form>
                        <li class="divider"></li>
                        <li><a href="?opLogin=sair"><i class='icon-share-alt'></i> Sair</a></li>
                    </ul>
                </div>

                <div class="nav-collapse collapse">
                    <ul class="nav" id="menu-topbar" style="display:none;">
                        <li class="active"><a href="/<?= $url[0]; ?>"><?= $idioma["inicio"]; ?></a></li>
                        <? if($_SESSION["modulosPermissoes"]["cadastros"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/cadastros"> <?= $idioma["cadastros"]; ?></a></li>
                        <? } ?>
                        <? if($_SESSION["modulosPermissoes"]["comercial"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/comercial"><?= $idioma["comercial"]; ?></a></li>
                        <? } ?>
                        <? if($_SESSION["modulosPermissoes"]["financeiro"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/financeiro"><?= $idioma["financeiro"]; ?></a></li>
                        <? } ?>
                        <? if($_SESSION["modulosPermissoes"]["relacionamento"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/relacionamento"><?= $idioma["relacionamento"]; ?></a></li>
                        <? } ?>
                        <? if($_SESSION["modulosPermissoes"]["juridico"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/juridico"><?= $idioma["juridico"]; ?></a></li>
                        <? } ?>
                        <? if($_SESSION["modulosPermissoes"]["relatorios"]["acesso"]){ ?>
                            <li><a href="/<?= $url[0]; ?>/relatorios"><?= $idioma["relatorios"]; ?></a></li>
                        <? } ?>
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

                    <? if($_SESSION["modulosPermissoes"]["cadastros"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/cadastros" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/cadastro_branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/cadastro_24.png" /></div>
                                <?= $idioma["cadastros"]; ?>
                            </a>
                            <div class="dropdown_2columns">
                                <div class="col_2">
                                    <ul class="linkIcone">
                                        <? if($permissoes["pessoas|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/cadastros/pessoas" style="background-image:url(/assets/icones/preto/32/pessoas_32.png)"> <strong><?= $idioma["pessoas"]; ?></strong> <span><?= $idioma["pessoas_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <? if($permissoes["professores|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/cadastros/professores" style="background-image:url(/assets/icones/preto/32/validacaocadastral_32.png)"> <strong><?= $idioma["professores"]; ?></strong> <span><?= $idioma["professores_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <? if($permissoes["atendentes|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/cadastros/atendentes" style="background-image:url(/assets/icones/preto/32/corretores_32.png)"> <strong><?= $idioma["vendedores"]; ?></strong> <span><?= $idioma["vendedores_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <? if($permissoes["cfc|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/cadastros/cfc" style="background-image:url(/assets/icones/preto/32/empresas_32.png)"> <strong><?= $idioma["escolas"]; ?></strong> <span><?= $idioma["escolas_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <li><a href="/<?= $url[0]; ?>/cadastros" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>

                    <? if($_SESSION["modulosPermissoes"]["academico"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/academico" class="drop">
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
                    <? } ?>


                    <? if($_SESSION["modulosPermissoes"]["comercial"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/comercial" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/comercial-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/comercial_24.png" /></div>
                                <?= $idioma["comercial"]; ?>
                            </a>
                            <div class="dropdown_2columns">
                                <div class="col_2">
                                    <ul class="linkIcone">

                                        <? if($permissoes["visitas|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/comercial/visitas" style="background-image:url(/assets/icones/preto/32/corretores_32.png)"> <strong><?= $idioma["visitas"]; ?></strong> <span><?= $idioma["visitas_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <? if($permissoes["relacionamentocomercial|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/comercial/relacionamentocomercial" style="background-image:url(/assets/icones/preto/32/muraladministrador_32.png)"> <strong><?= $idioma["relacionamentocomercial"]; ?></strong> <span><?= $idioma["relacionamentocomercial_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <? if($permissoes["mapadealcance|1"]) { ?>
                                            <li><a href="/<?= $url[0]; ?>/comercial/mapadealcance" style="background-image:url(/assets/icones/preto/32/regioesdeempreendimentos_32.png)"> <strong><?= $idioma["mapadealcance"]; ?></strong> <span><?= $idioma["mapadealcance_descricao"]; ?></span></a></li>
                                        <? } ?>
                                        <li><a href="/<?= $url[0]; ?>/comercial" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>

                    <? if($_SESSION["modulosPermissoes"]["financeiro"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/financeiro" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/financeiro-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/financeiro_24.png" /></div>
                                <?= $idioma["financeiro"]; ?>
                            </a>
                            <div class="dropdown_2columns">
                                <div class="col_2">
                                    <ul class="linkIcone">

                                        <? if($permissoes["contas|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/financeiro/contas" style="background-image:url(/assets/icones/preto/32/contas_bancarias_32.png)"> <strong><?= $idioma["contas"]; ?></strong> <span><?= $idioma["contas_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <? if($permissoes["contas|2"]) { ?>
                                            <li class="microMenu"> <a href="/<?= $url[0]; ?>/financeiro/contas/cadastrar"> <strong><?= $idioma["nova_conta"]; ?></strong></a> </li>
                                        <? } ?>
                                        <? if($permissoes["contas|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/financeiro/contas/apagar" style="background-image:url(/assets/icones/preto/32/repasses_32.png)"> <strong><?= $idioma["contas_apagar"]; ?></strong> <span><?= $idioma["contas_apagar_descricao"]; ?></span> </a> </li>
                                            <li> <a href="/<?= $url[0]; ?>/financeiro/contas/areceber" style="background-image:url(/assets/icones/preto/32/comissoes_32.png)"> <strong><?= $idioma["contas_areceber"]; ?></strong> <span><?= $idioma["contas_areceber_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <? if($permissoes["fornecedores|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/financeiro/fornecedores" style="background-image:url(/assets/icones/preto/32/agendamentos_32.png)"> <strong><?= $idioma["fornecedores"]; ?></strong> <span><?= $idioma["fornecedores_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <li> <a href="/<?= $url[0]; ?>/financeiro" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>


                    <? if($_SESSION["modulosPermissoes"]["relacionamento"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/relacionamento" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/relacionamento-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/relacionamento_24.png" /></div>
                                <?= $idioma["relacionamento"]; ?>
                            </a>
                            <div class="dropdown_2columns">
                                <div class="col_2">
                                    <ul class="linkIcone">

                                        <? if($permissoes["atendimentos|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/relacionamento/atendimentos" style="background-image:url(/assets/icones/preto/32/atendimentos_32.png)"> <strong><?= $idioma["atendimentos"]; ?></strong> <span><?= $idioma["atendimentos_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <? if($permissoes["assuntosatendimentos|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/relacionamento/assuntosatendimentos" style="background-image:url(/assets/icones/preto/32/atendimentos_32.png)"> <strong><?= $idioma["assuntosatendimentos"]; ?></strong> <span><?= $idioma["assuntosatendimentos_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <li> <a href="/<?= $url[0]; ?>/relacionamento" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>


                    <? if($_SESSION["modulosPermissoes"]["juridico"]["acesso"]){ ?>
                        <li>
                            <a href="/<?= $url[0]; ?>/juridico" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/juridico-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/juridico_24.png" /></div>
                                <?= $idioma["juridico"]; ?>
                            </a>
                            <div class="dropdown_2columns">
                                <div class="col_2">
                                    <ul class="linkIcone">
                                        <? if($permissoes["contratos|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/juridico/contratos" style="background-image:url(/assets/icones/preto/32/contratos_32.png)"> <strong><?= $idioma["contratos"]; ?></strong> <span><?= $idioma["contratos_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <li> <a href="/<?= $url[0]; ?>/juridico" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>

                    <? if($_SESSION["modulosPermissoes"]["relatorios"]["acesso"]){ ?>
                        <li class="semSubMenu"><a href="/<?= $url[0]; ?>/relatorios">
                                <div class="divImagem"><img src="/assets/icones/branco/24/relatorio-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/relatorio_24.png" /></div>
                                <?= $idioma["relatorios"]; ?>
                            </a></li>
                    <? } ?>


                    <? if($_SESSION["modulosPermissoes"]["configuracoes"]["acesso"]){ ?>
                        <li class="menu_right">
                            <a href="/<?= $url[0]; ?>/configuracoes" class="drop">
                                <div class="divImagem"><img src="/assets/icones/branco/24/configuracao-branco_24.png" /></div>
                                <div class="divImagemHover"><img src="/assets/icones/preto/24/configuracao_24.png" /></div>
                                <?= $idioma["configuracoes"]; ?>
                            </a>
                            <div class="dropdown_2columns align_right">
                                <div class="col_2">
                                    <ul class="linkIcone">

                                        <? if($permissoes["usuariosadm|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/configuracoes/usuariosadm" style="background-image:url(/assets/icones/preto/32/usuarios_32.png)"> <strong><?= $idioma["usuariosadm"]; ?></strong> <span><?= $idioma["usuariosadm_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <? if($permissoes["perfisusuarioadm|1"]) { ?>
                                            <li> <a href="/<?= $url[0]; ?>/configuracoes/perfisusuarioadm" style="background-image:url(/assets/icones/preto/32/perfis_acessos_32.png)"> <strong><?= $idioma["usuariosadmperfis"]; ?></strong> <span><?= $idioma["usuariosadmperfis_descricao"]; ?></span> </a> </li>
                                        <? } ?>
                                        <li> <a href="/<?= $url[0]; ?>/configuracoes" style="background-image:url(/assets/icones/preto/32/menu_completo_32.png)"> <strong><?= $idioma["menu_completo"]; ?></strong> <span><?= $idioma["menu_completo_descricao"]; ?></span> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <? } ?>

                </ul>
            </div>
        </div>
    </div>
</section>
<?// if($config["link_manual_funcionalidade"]) { ?>
<!--    <div id="div_link_manual" class="pull-right">-->
<!--        <form id="form_manual_atalho" method="get" action="--><?//= $config["link_manual"]; ?><!----><?//= $config["link_manual_funcionalidade"]; ?><!--" target="_blanck">-->
<!--            <a style="cursor:pointer;" class="btn btn-mini" onclick="javascript:document.getElementById('form_manual_atalho').submit();">-->
<!--                <i class="icon-list-alt"></i>  --><?//= $idioma["manual_usuario"]; ?>
<!--            </a>-->
<!--            <input type="hidden" name="idusuario" value="--><?php //echo $informacoes['idusuario']; ?><!--" />-->
<!--            <input type="hidden" name="login_manual" value="login_manual" />-->
<!--        </form>-->
<!--    </div>-->
<?// } ?>
