<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/bootstrap_v2/css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="/assets/css/oraculo.mobile.css" rel="stylesheet" />
  <script src="/assets/js/jquery-3.5.1.min.js"></script>
  <script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<style>
	#logo {
		padding: 20px;
	}
@media (min-width: 480px) {
	#logo {
		padding: 50px !important;
	}
}

</style>
</head>

<body style="padding-top:10px">
<div class='container' id='login'>
  <div id='logo'> <a href="<?= $config["websiteEmpresa"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="<?= $config["tituloSistema"]; ?>" src="/especifico/img/logo_empresa.png" /> </a></div>
  <div class='row'>
    <div class='span6 offset3'>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3><?=$idioma["identifique"];?></h3>
          <ul>
            <li><a href="<?= $config["urlSite"]; ?>" target="_blank"><img src="/assets/img/logo_pequena.png" width="135" height="50" /></a></li>
          </ul>
        </div>
        <div class='section-body'>
		<? if($_POST["msg"] == "cadastrar_sucesso" || $_POST["msg"] == "mensagem_sucesso") { ?>
          <div class="alert alert-success fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma[$_POST["msg"]]; ?></strong>
          </div>
        <? }elseif($_POST["msg"]){ ?>
          <div class="alert alert-error">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma[$_POST["msg"]]; ?></strong>
          </div>
        <? } ?>
          <form method="post">
            <label for="txt_usuario"><?= $idioma["email"];?></label>
            <input class="span5" name="txt_usuario" type="text" id="txt_usuario" value="<?= $_POST["txt_usuario"]; ?>" size="30">
            <label for="txt_senha"><?=$idioma["senha"];?></label>
            <input class="span5" name="txt_senha" type="password" id="txt_senha" size="30">
            <div class='form-actions'>
              <div class='row'>
                <div class='span2'>
              	   <input name="opLogin" type="hidden" id="opLogin" value="login" />
                   <input class="btn btn-primary" name="commit" type="submit" value="Acessar painel" />
                </div>
              </div>
            </div>
            <p> <a href="/gestor/esqueci" style="color:#999999"><?= $idioma["esqueci_senha"]; ?></a> </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--
<p class='ac'>
	<small> <i class='icon-share'></i> <a href="mailto:gabriel@alfamaweb.com.br"><?= $idioma["contato"]; ?></a></small>
</p>
-->
</body>
</html>
