<?php 
	//echo $_SERVER['DOCUMENT_ROOT'];
	include_once($_SERVER['DOCUMENT_ROOT']."/especifico/inc/analyticstracking.php");
?>
<?
if($informacoes["gestor"]) {
?>
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
<? printf($idioma["acessocomo"],$informacoes["gestor"]["nome"],$informacoes["nome"]); ?>
</div>
<? } ?>

<section id="topo">
<div class="navbar navbar-fixed-top visible-desktop">
  <div class="navbar-inner">
    <div class="container-fluid">

    <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;" id="nomeAlfamaOraculo"><span style="color:#999;">ALFAMA</span> - <?= $config["tituloSistema"]; ?><span style="color:#999;"> - <?= $config["tituloPainel"]; ?></span></a>


      <div class="btn-group pull-right" style="padding-top:3px;">
        <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#"> <i class="icon-user"></i> <?= $informacoes["nome"]; ?> <span class="caret"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="/<?= $url[0]; ?>/configuracoes/meusdados"><?= $idioma["meusdados"]; ?></a></li>
          <li class="divider"></li>
          <li><a href="?opLogin=sair"><?= $idioma["sair"]; ?></a></li>
        </ul>
      </div>

      <div class="nav-collapse collapse">
        <ul class="nav" id="menu-topbar" style="display:none;">
          <li class="active"><a href="/<?= $url[0]; ?>"><?= $idioma["inicio"]; ?></a></li>
        </ul>
      </div>

    </div>
  </div>
</div>
<div class="container-fluid">
  <div id="menuLogo" class="visible-desktop">
    <div id="logo">
      <h1><a href="/<?= $url[0]; ?>" title="<?= $config["tituloSistema"]; ?>"><?= $config["tituloSistema"]; ?></a></h1>
    </div>

    <div id="divMenu">
      <ul id="menu">

		<li class="semSubMenu"><a href="/<?= $url[0]; ?>">
          <div class="divImagem"><img src="/assets/icones/branco/24/home-branco_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/home_24.png" /></div>
          <?= $idioma["inicio"]; ?>
          </a>
        </li>

    <li class="semSubMenu"> <a href="/<?= $url[0]; ?>/relacionamento/mural">
          <div class="divImagem"><img src="/assets/icones/branco/24/murais_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/murais_24.png" /></div>
          <?= $idioma["mural"]; ?>
          </a> </li>

		<li class="semSubMenu"> <a href="/<?= $url[0]; ?>/relacionamento/chat">
          <div class="divImagem"><img src="/assets/icones/branco/24/murais_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/murais_24.png" /></div>
          chat
          </a> </li>

		<li class="semSubMenu"> <a href="/<?= $url[0]; ?>/academico/avaliacoes">
          <div class="divImagem"><img src="/assets/icones/branco/24/grupo_contratos_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/grupo_contratos_24.png" /></div>
          <?= $idioma["avaliacoes"]; ?>
          </a> </li>

        <li class="semSubMenu"> <a href="/<?= $url[0]; ?>/academico/foruns">
          <div class="divImagem"><img src="/assets/icones/branco/24/grupo_contratos_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/grupo_contratos_24.png" /></div>
          <?= $idioma["foruns"]; ?>
          </a> </li>

		<li class="semSubMenu"> <a href="/<?= $url[0]; ?>/academico/tiraduvidas">
          <div class="divImagem"><img src="/assets/icones/branco/24/grupo_contratos_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/grupo_contratos_24.png" /></div>
          <?= $idioma["tiraduvidas"]; ?>
          </a> </li>

        <li class="semSubMenu">
            <a href="/<?= $url[0]; ?>/academico/avas">
                <div class="divImagem"><img src="/assets/icones/branco/24/grupo_contratos_24.png" /></div>
                <div class="divImagemHover"><img src="/assets/icones/preto/24/grupo_contratos_24.png" /></div>
                <?= $idioma["avas"]; ?>
            </a>
        </li>

        <li class="semSubMenu">
            <a href="/<?= $url[0]; ?>/academico/pessoas">
                <div class="divImagem"><img src="/assets/icones/branco/24/pessoas_24.png" /></div>
                <div class="divImagemHover"><img src="/assets/icones/preto/24/pessoas_24.png" /></div>
                <?= $idioma["pessoas"]; ?>
            </a>
        </li>

      <li class="semSubMenu">
          <a href="/<?= $url[0]; ?>/academico/aulaonline">
              <div class="divImagem"><img src="/assets/icones/branco/24/pessoas_24.png" /></div>
              <div class="divImagemHover"><img src="/assets/icones/preto/24/pessoas_24.png" /></div>
              <?= $idioma["aulaonline"]; ?>
          </a>
      </li>
        
        <li class="menu_right"> <a href="?opLogin=sair">
          <div class="divImagem"><img src="/assets/icones/branco/24/empresas_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/empresas_24.png" /></div>
          <?= $idioma["logout"]; ?>
          </a> </li>

      </ul>
    </div>
  </div>
</div>
</section>