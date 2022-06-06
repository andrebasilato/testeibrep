<section id="topo">
<div class="navbar navbar-fixed-top visible-desktop">
  <div class="navbar-inner">
    <div class="container-fluid"> 
    
    <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase;" id="nomeAlfamaOraculo"><span style="color:#999;">ALFAMA</span> - <?= $config["tituloSistema"]; ?><span style="color:#999;"> - <?= $config["tituloPainel"]; ?></span></a>
     
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
  </div>
</div>
</section>