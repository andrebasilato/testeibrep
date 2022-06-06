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
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid"> 
    <a class="brand" href="/<?= $url[0]; ?>" style="text-transform:uppercase; padding-left:20px;" id="nomeAlfamaConstrutor"><?= $config["tituloSistema"]; ?><span style="color:#999;"> - <?= $config["tituloPainel"]; ?> - V<?= $config["oraculo_versao"]; ?></span></a>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div id="menuLogo">
    <div id="logo">
      <h1><a href="/<?= $url[0]; ?>" title="Or&aacute;culo">Oráculo</a></h1>
    </div>
<div id="divMenu">
      <ul id="menu">        
        <li class="menu_right" style="	width:80px;"><a href="javascript:void(0);" class="drop">
          <div class="divImagem"><img src="/assets/icones/branco/24/comercial-branco_24.png" /></div>
          <div class="divImagemHover"><img src="/assets/icones/preto/24/comercial_24.png" /></div>
          <?= $idioma["opcoes"]; ?>
          </a>
          <div class="dropdown_2columns align_right">
            <div class="col_2">
              <ul class="linkIcone">
              
                <? /* <li><a href="/<?= $url[0]; ?>/academico/matriculas/novamatricula" style="background-image: url(/assets/icones/preto/32/motivosdevisitas_32.png)"> <strong><?= $idioma["nova_matriculas"]; ?></strong> <span><?= $idioma["nova_matriculas_descricao"]; ?></span></a></li> */ ?>
                <li><a href="/<?= $url[0]; ?>/academico/matriculas" style="background-image: url(/assets/icones/preto/32/motivosdevisitas_32.png)"> <strong><?= $idioma["matriculas"]; ?></strong> <span><?= $idioma["matriculas_descricao"]; ?></span></a></li>              
                <li class="microMenu"> <a href="/<?= $url[0]; ?>/academico/matriculas/novamatricula"> <strong><?= $idioma["nova_matriculas"]; ?></strong></a> </li>
                <li><a href="/<?= $url[0]; ?>/comercial/visitas" style="background-image:url(/assets/icones/preto/32/muraladministrador_32.png)"> <strong><?= $idioma["visitas"]; ?></strong> <span><?= $idioma["visitas_descricao"]; ?></span></a></li>
                <li class="microMenu"> <a href="/<?= $url[0]; ?>/comercial/visitas/cadastrar"> <strong><?= $idioma["nova_visita"]; ?></strong></a> </li>

                <li> <a href="?opLogin=sair" style="background-image:url(/assets/icones/preto/24/empresas_24.png)"> <strong><?= $idioma["sair"]; ?></strong> <span><?= $idioma["sair_descricao"]; ?></span> </a> </li>
              </ul>
            </div>
          </div>
        </li>
      </ul>
    </div>    
  </div>
</div>