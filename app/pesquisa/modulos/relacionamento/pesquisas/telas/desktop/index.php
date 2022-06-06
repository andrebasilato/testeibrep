<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<body>
<div class='container' id='login'>
  <div id='logo'><a href="<?= $config["urlSistema"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="Logo do Oráculo" src="/especifico/img/<?= $config["logo_pesquisa"]; ?>" /> </a></div>
  <div class='row'>
    <div class='span10 offset1'>
      
      <?php if(count($salvar["erros"]) > 0){ ?>
        <div class="alert alert-error fade in">
          <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
          <strong><?= $idioma["form_erros"]; ?></strong>
          <?php foreach($salvar["erros"] as $ind => $val) { ?>
            <br />
            <?php echo $idioma[$val]; ?>
          <?php } ?>
        </div>
      <?php } ?>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3><?php echo $linha["nome"]; ?></h3>
        </div>
        <div class='section-body'>
		  <?php if($_POST["msg"]) { ?>
			<div class="alert alert-success fade in">
			<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
			<strong><?php echo $idioma[$_POST["msg"]]; ?></strong></div>
		  <?php } else { 
				  if(!$erro) {
					if($linha["layout"]) {
					  echo $linha["layout"];
					} else {
					  echo $idioma["vazio_preview"];
					}
				  } else {
					  ?>
					<div class="alert alert-error fade in">
					  <?php echo $idioma[$mensagemErro]; ?>
					</div>
					<?php
				  }
				}
		  ?>
          <br />
          <br />
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>