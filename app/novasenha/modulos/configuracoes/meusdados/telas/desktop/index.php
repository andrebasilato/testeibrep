<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<link href="/assets/min/aplicacao.desktop.min.css" rel="stylesheet">
<body>
<div class='container' id='login'>
  <div id='logo'><a href="<?= $config["urlSistema"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="Logo do Construtor" height="75" src="/especifico/img/logo_empresa.png" /> </a></div>
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
      <?php if($url[3] == 'escola'){ $url[3] = 'cfc'; } ?>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3><?php if($linha["idsolicitacao_senha"]) { echo "Olá ".$linha["nome"]; } else { echo "Ops!"; } ?></h3>
        </div>
        <div class='section-body'>
		  <?php if($_POST["msg"]) { ?>
			<div class="alert alert-success fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
            </div>
		  <?php } else { 
			if($linha["idsolicitacao_senha"]) { 
			  if($linha["ativo"] == "S" && !$linha["data_modificacao"] && $horas <= 6) {?>
                <form method="post"  onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal" id="formSenha">
                  <input name="acao" type="hidden" value="salvar" />
                  <?php $linhaObj->GerarFormulario("formulario",$linha,$idioma); ?> 
                  <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="window.location='http://<?= $_SERVER["SERVER_NAME"]; ?>/<?= $url[3]; ?>';" value="<?= $idioma["btn_cancelar"]; ?>" />
                  </div>
                </form>
			<?php
			  } elseif($linha["data_modificacao"]) { ?>
                <div class="alert alert-error fade in">
                  <?php echo $idioma["solicitacao_usada"]; ?>
                </div>
			  <?
              } elseif($horas > 6) { ?>
                <div class="alert alert-error fade in">
                  <?php echo $idioma["solicitacao_expirada"]; ?>
                </div>
			  <?
              } else { ?>
                <div class="alert alert-error fade in">
                  <?php echo $idioma["solicitacao_inativa"]; ?>
                </div>
			  <?
              }
            } else {
			?>
              <div class="alert alert-error fade in">
                <?php echo $idioma["solicitacao_nao_econtrada"]; ?>
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
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-modal.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-scrollspy.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-popover.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-carousel.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-typeahead.js"></script>
<script src="/assets/js/mousetrap.min.js"></script>
<script src="/assets/js/construtor.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script src="/assets/js/ajax.js"></script>
<script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
<link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript">
  var regras = new Array();
  <?php
  foreach($config["formulario"] as $fieldsetid => $fieldset) {
	foreach($fieldset["campos"] as $campoid => $campo) {
	  if(is_array($campo["validacao"])){
		foreach($campo["validacao"] as $tipo => $mensagem) {
		?>
		  regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
		<?
		}
	  }
	}
  }
  ?>
  jQuery(document).ready(function($) {
	$(".verificaSenha").passStrength({userid: "#formSenha"});
  });
</script>
</body>
</html>