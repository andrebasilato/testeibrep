<?php
//echo $_SERVER['DOCUMENT_ROOT'];
include_once($_SERVER['DOCUMENT_ROOT'] . "/especifico/inc/analyticstracking.php");
?>
<?php if (defined('URL_LOGO_PEGUENA')) { ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #logo h1 a {
            background: transparent url(<?php echo URL_LOGO_PEGUENA; ?>) 0 0 no-repeat;
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
                <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;"
                   id="nomeAlfamaOraculo"><?= $config["tituloSistema"]; ?><span
                            style="color:#999;"> - <?= $config["tituloPainel"]; ?> - V<?= $config["oraculo_versao"]; ?></span></a>
                <div class="btn-group pull-right" style="padding-top:0px;">
                    <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#"> <i
                                class="icon-user"></i> <?= $informacoes["nome"]; ?>
                        (<?= $informacoes["perfil"]["nome"]; ?>) <span class="caret"></span> </a>
                    <ul class="dropdown-menu">
                        <li><a href="/alfama/configuracoes/meusdados"><i class='icon-asterisk'></i> Meus dados</a></li>
                        <li class="divider"></li>
                        <form id="form_manual" method="post" action="<?= $GLOBALS["config"]["link_manual"]; ?>/orio"
                              target="_blanck">
                            <li><a style="cursor:pointer;"
                                   onclick="javascript:document.getElementById('form_manual').submit();"><?= $idioma["manual"]; ?></a>
                            </li>
                            <input type="hidden" name="idusuario" value="<?php echo $informacoes['idusuario']; ?>"/>
                            <input type="hidden" name="login_manual" value="login_manual"/>
                        </form>
                        <li class="divider"></li>
                        <li><a href="?opLogin=sair"><i class='icon-share-alt'></i> Sair</a></li>

                    </ul>
                </div>

                <div class="nav-collapse collapse">
                    <ul class="nav" id="menu-topbar" style="display:none;">
                        <!-- Permissão Menu !-->
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
                            <div class="divImagem"><img src="/assets/icones/branco/24/home-branco_24.png"/></div>
                            <div class="divImagemHover"><img src="/assets/icones/preto/24/home_24.png"/></div>
                            <?= $idioma["inicio"]; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/configuracoes/especifico">
                            <div class="divImagem"><img src="/assets/icones/branco/24/configuracao-branco_24.png"/>
                            </div>
                            <div class="divImagemHover"><img src="/assets/icones/preto/24/configuracao_24.png"/></div>
                            <?= $idioma['especifico']; ?>
                        </a>
                    </li>
                    <li class="semSubMenu">
                        <a href="/<?= $url[0]; ?>/configuracoes/detrans">
                            <div class="divImagem"><img src="/assets/icones/branco/24/configuracao-branco_24.png"/>
                            </div>
                            <div class="divImagemHover"><img src="/assets/icones/preto/24/configuracao_24.png"/></div>
                            <?= $idioma['detrans']; ?>
                        </a>
                    </li>
                    <li class="menu_right">
                        <a href="/<?= $url[0] ?>?opLogin=sair">
                            <div class="divImagem"><i class="fa fa-arrow-circle-o-right" style="font-size:24px;"
                                                      aria-hidden="true"></i></div>
                            <div class="divImagemHover"><i class="fa fa-arrow-circle-o-right" style="font-size:24px;"
                                                           aria-hidden="true"></i></div>
                            <?= $idioma["sair"]; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<? if ($config["link_manual_funcionalidade"]) { ?>
    <div id="div_link_manual" class="pull-right">
        <form id="form_manual_atalho" method="get"
              action="<?= $config["link_manual"]; ?><?= $config["link_manual_funcionalidade"]; ?>" target="_blanck">
            <a style="cursor:pointer;" class="btn btn-mini"
               onclick="javascript:document.getElementById('form_manual_atalho').submit();">
                <i class="icon-list-alt"></i> <?= $idioma["manual_usuario"]; ?>
            </a>
            <input type="hidden" name="idusuario" value="<?php echo $informacoes['idusuario']; ?>"/>
            <input type="hidden" name="login_manual" value="login_manual"/>
        </form>
    </div>
<? } ?>
