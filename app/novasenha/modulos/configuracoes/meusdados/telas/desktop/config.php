<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<body>
<div class='container' id='login'>
  <div id='logo'><a href="<?= $config["urlSistema"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="Logo do Construtor" height="75" src="/especifico/img/logo_empresa.png" width="400" /> </a></div>
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
              <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
            </div>
		  <?php } else { 
			if(!$erro) {
			  if($linha["layout"]) { ?>
				<form method="post"  onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar" />
				  <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
				  <?php $linhaObj->GerarFormulario("formulario",$linha,$idioma); ?> 
                  <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','http://<?= $_SERVER["SERVER_NAME"]; ?>/<?= $url[3]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                  </div>
                </form>
			  <?php } else {
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