<div class="navbar navbar-fixed-top visible-desktop">
  <div class="navbar-inner">
    <div class="container-fluid"> 
    
    <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;" id="nomeAlfamaConstrutor"><span style="color:#999;">ALFAMA</span> - <?= $config["tituloSistema"]; ?><span style="color:#999;"> - <?= $config["tituloPainel"]; ?></span></a>
     
     
      <div class="btn-group pull-right" style="padding-top:3px;"> 
        <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#"> <i class="icon-user"></i> <?= $informacoes["nome"]; ?> <span class="caret"></span> </a>
        <ul class="dropdown-menu">
          <li><a href="/<?= $url[0]; ?>/configuracoes/meusdados"><?= $idioma["meusdados"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/configuracoes/meusdados"><?= $idioma["alterarsenha"]; ?></a></li>
          <li class="divider"></li>
          <li><a href="?opLogin=sair"><?= $idioma["sair"]; ?></a></li>
        </ul>
      </div>

      <div class="nav-collapse collapse">
        <ul class="nav" id="menu-topbar" style="display:none;">
          <li class="active"><a href="/<?= $url[0]; ?>"><?= $idioma["inicio"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/cadastros/empreendimentos"><?= $idioma["empreendimentos"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/cadastros/corretores"><?= $idioma["corretores"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/comercial/reservas"><?= $idioma["reservas"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/comercial/reservas/cadastrar"><?= $idioma["novareserva"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/relatorios"><?= $idioma["relatorios"]; ?></a></li>
          <li><a href="/<?= $url[0]; ?>/relacionamento/mural"><?= $idioma["mural"]; ?></a></li>
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
      	
        <?php/*<li><a href="/<?= $url[0]; ?>"><div class="divImagem"><img src="/assets/icones/branco/24/home-branco_24.png" /></div><?= $idioma["inicio"]; ?></a></li>*/?>
		<li><a href="/<?= $url[0]; ?>/financeiro/repasses"><div class="divImagem"><img src="/assets/icones/branco/24/repasses_24.png" /></div><?= $idioma["repasses"]; ?></a></li>
        
      </ul>
    </div>
  </div>
</div>