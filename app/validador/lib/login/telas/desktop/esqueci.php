<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php incluirLib("head",$config,$usuario); ?>
  <script src="<?= $config["urlSistema"]; ?>/assets/js/jquery.1.7.1.min.js"></script>
  <script src="<?= $config["urlSistema"]; ?>/assets/bootstrap_v2/js/bootstrap-alert.js"></script> 
<style>
	body {
		border-top: 8px #8e44ad solid;	
	}
	.labelPainel {
position: absolute;
top: 16px;
left: 8px;
text-transform: uppercase;
color: #8e44ad;
font-weight:bold;
	}
</style>  
</head>
<body>
<div class="labelPainel">Painel do Professor</div>
<div class='container' id='login'>
  <div id='logo'> <a href="<?= $config["websiteEmpresa"]; ?>" title="<?= $config["tituloSistema"]; ?>"><img alt="<?= $config["tituloSistema"]; ?>" height="124" src="/especifico/img/logo_empresa.png" width="383" /> </a></div>
  <div class='row'>
    <div class='span6 offset3'>
      <div class='section section-large' id='login-section'>
        <div class='section-header' large='large'>
          <h3><?=$idioma["equeceu_senha"]?></h3>
          <ul>
            <li><a href="<?= $config["urlSistema"]; ?>" target="_blank"><img src="/assets/img/logo_pequena.png" width="135" height="50" /></a></li>
          </ul>
        </div>
        <div class='section-body'>
        <? if (isset($_GET["erro"])) { ?>
              <div class="alert alert-error fade in" style="width:400px;">
                  <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                  <p><?= $idioma[$_POST["msg"]]; ?></p>
              </div>          
        <? } elseif ($_POST["msg"]) { ?>
              <div class="alert alert-success" style="width:400px;">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <p><?= $idioma[$_POST["msg"]]; ?></p>
              </div>
        <? } ?>        
          <form method="post">
            <label for="txt_usuario"><?= $idioma["email"];?></label>
            <input class="span5" name="email" type="text" id="email" value="<?= $_POST["email"]; ?>" size="30">
            <div class='form-actions'>
              <div class='row'>
                <div class='span2' style='width:85px'>
              	   <input name="opLogin" type="hidden" id="opLogin" value="esqueciMinhaSenha" />               
                   <input class="btn btn-primary btn-large" name="commit" type="submit" value="<?=$idioma["enviar_senha"]?>" />
                </div>
              </div>
            </div>
            <p><a href="/<?=$url[0];?>" style="color:#999999" /><?=$idioma["efetuar_login"];?></a> </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
