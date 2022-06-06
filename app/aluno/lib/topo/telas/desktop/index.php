<?php
	//echo $_SERVER['DOCUMENT_ROOT'];
	include_once($_SERVER['DOCUMENT_ROOT']."/especifico/inc/analyticstracking.php");
?>
<!-- Topo -->
<?php if(array_key_exists('pessoa', $informacoes) ||
    array_key_exists('gestor', $informacoes) ||
    array_key_exists('pessoa', $informacoes) ||
    array_key_exists('professor', $informacoes)) { ?>
<style>
    .msg_acessocomo {
        background-color: #F00;
        color: #FFF;
        font-size: 12px;
        padding: 8px 5px 5px 5px;
        text-align: center;
        margin-bottom: 5px;
    }
</style>
<div class="msg_acessocomo">
<?php
if ($informacoes['pessoa']["gestor"]) {
  printf($idioma["acessocomo"],$informacoes['pessoa']["gestor"]["nome"],$informacoes['pessoa']["nome"]);
} elseif ($informacoes["gestor"]) {
  printf($idioma["acessocomo"],$informacoes["gestor"]["nome"],$informacoes["nome"]);
} elseif ($informacoes['pessoa']["professor"]){
  printf($idioma["acessocomo_professor"],$informacoes['pessoa']["professor"]["nome"],$informacoes['pessoa']["nome"]);
} elseif ($informacoes["professor"]){
  printf($idioma["acessocomo_professor"],$informacoes["professor"]["nome"],$informacoes["nome"]);
}
?>
</div>
<? } ?>
<a href="/<?= $url[0]; ?>" class="logo-responsive"><img  src="<?= (!empty($informacoes['escola']['avatar_servidor'])) ? "/api/get/imagens/escolas_avatar/x/40/".$informacoes['escola']["avatar_servidor"] : "/assets/aluno_novo/img/ibreptra_marca.png"; ?>" alt="Marca" /></a>
<div class="menu-responsive navbar navbar-inverse navbar-fixed-top visible-phone visible-tablet">
    <div class="navbar-inner">
        <div>
            <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar collapsed" type="button"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <nav class="nav-collapse collapse" style="height: 0px;">
                <ul class="nav">
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/meuscursos"><?= $idioma['meus_cursos']; ?></a></li>
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/perfil"><?= $idioma['meu_perfil']; ?></a></li>
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/financeiro"><?= $idioma['financeiro']; ?></a></li>
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/academico/calendario"><?= $idioma['calendario']; ?></a></li>
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/documentos"><?= $idioma['documentos']; ?></a></li>
                    <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/contratos"><?= $idioma['contratos']; ?></a></li>
                    <!-- <li class="item-menu-reponsive"><a href="/<?= $url[0]; ?>/secretaria/atendimento"><?= $idioma['atendimento']; ?></a></li> -->
                    <li class="item-menu-reponsive"><a href="?opLogin=sair"><?= $idioma['sair']; ?></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<div class="content">
    <div class="top">
        <a href="/<?= $url[0]; ?>" class="logo"><img  src="<?= (!empty($informacoes['escola']['avatar_servidor'])) ? "/api/get/imagens/escolas_avatar/x/40/".$informacoes['escola']["avatar_servidor"] : "/assets/aluno_novo/img/ibreptra_marca.png"; ?>" alt="Marca" style="margin-top: -15px;"/></a>
        <div class="content">
            <ul class="menu-main no-mobile">
                <li><a href="/<?= $url[0]; ?>/secretaria/meuscursos" <?php if($url['2'] == 'meuscursos') { echo 'style="background: #fff !important;transition: all .4s !important;"'; } ?>><?= $idioma['meus_cursos']; ?></a></li>
                <li><a href="/<?= $url[0]; ?>/secretaria/perfil" <?php if($url['2'] == 'perfil') { echo 'style="background: #fff !important;transition: all .4s !important;"'; } ?>><?= $idioma['meu_perfil']; ?></a></li>
                <li><a href="/<?= $url[0]; ?>/secretaria/financeiro" <?php if($url['2'] == 'financeiro') { echo 'style="background: #fff !important;transition: all .4s !important;"'; } ?>><?= $idioma['financeiro']; ?></a></li>
                <li><a href="/<?= $url[0]; ?>/academico/calendario" <?php if($url['2'] == 'calendario') { echo 'style="background: #fff !important;transition: all .4s !important;"'; } ?>><?= $idioma['calendario']; ?></a></li>
                <li  class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" <?php if($url['2'] == 'documentospedagogicos' || $url['2'] == 'documentos' || $url['2'] == 'contratos') { echo 'style="background: #fff !important;transition: all .4s !important;"'; } ?>><?= $idioma['secretaria']; ?> <b class="caret"></b>&nbsp</a>
                    <ul class="dropdown-menu">
                        <li style="width: 100%;"><a href="/<?php echo $url[0]; ?>/secretaria/documentospedagogicos"><?php echo $idioma['documentos_pedagogicos']; ?></a></li>
                        <li style="width: 100%;"><a href="/<?php echo $url[0]; ?>/secretaria/documentos"><?php echo $idioma['documentos']; ?></a></li>
                        <li style="width: 100%;"><a href="/<?= $url[0]; ?>/secretaria/contratos"><?= $idioma['contratos']; ?></a></li>
                    </ul>
                </li>
                <li><a href="?opLogin=sair"><?= $idioma['sair']; ?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="top-user">
    <div class="content">
        <p class="user-log no-mobile"><?= sprintf($idioma['ultimo_cesso'], diferencaDias($informacoes['ultimo_acesso'])); ?></p>
        <div class="user-avatar">
            <p class="name-avatar"><?= $informacoes['nome']; ?></p>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="content">
    <div class="picture-avatar">
        <span><img src="/api/get/imagens/pessoas_avatar/56/56/<?= $informacoes["avatar_servidor"]; ?>" alt="Avatar" /></span>
    </div>
</div>
<!-- /Topo -->