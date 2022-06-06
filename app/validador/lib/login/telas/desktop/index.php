<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php incluirLib("head",$config,$usuario); ?>
 <script src="/assets/js/jquery.1.7.1.min.js"></script>
 <script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<style>
    body {
        border-top: 8px #63ad44 solid;	
    }
    .labelPainel {
        position: absolute;
        top: 16px;
        left: 8px;
        text-transform: uppercase;
        color: #63ad44;
        font-weight:bold;
    }
</style> 
</head>
<body>
<div class="labelPainel">Validador de documentos</div>
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
			<? if($_POST["msg"]){ ?>        
			  <div class="alert alert-error">
				<a href="javascript:void(0);" class="close" data-dismiss="alert">�</a>
				<p><?= $idioma[$_POST["msg"]]; ?></p>
			  </div>
			  <? } ?>
			  <form method="post">
                  <label for="tipo_documento"><?=$idioma["tipo_documento"];?></label>
                  <select name="tipo_documento">
                      <option></option>
                      <option value="DC"><?php echo $idioma["diplomas_certificados"];?></option>
                      <option value="D"><?php echo $idioma["declaracoes"];?></option>
                      <option value="H"><?php echo $idioma["historicos"];?></option>
                  </select>
				<label for="txt_codigo"><?=$idioma["codigo"];?></label>
				<input class="span5" name="txt_codigo" type="text" id="txt_codigo" size="30">
				<div class='form-actions'>
				  <div class='row'>
					<div class='span2' style='width:85px'>
					   <input name="opValidacao" type="hidden" id="opValidacao" value="validacao" />               
					   <input class="btn btn-primary btn-large" name="commit" type="submit" value="Validar" /> 
					</div>
				  </div>
				</div>
			  </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
