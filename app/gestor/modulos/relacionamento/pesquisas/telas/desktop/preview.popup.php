<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet"></head>
<body><div class='container' id='login'>
  <div id='logo'><a href="" title="<?= $config["tituloSistema"]; ?>"><img alt="Logo do Oráculo" src="/especifico/img/<?= $config["logo_pesquisa"]; ?>" /> </a></div>
  <div class='row'>
    <div class='span10 offset1'>
<? if($erro['msg']){ ?>
	<div class="alert alert-error fade in">
		<?php echo $idioma[$erro['msg']]; ?>
	</div>
<? } ?>
<? if($_POST["msg"]) { ?>
	<div class="alert alert-success fade in"> 
		<strong><?= $idioma[$_POST["msg"]]; ?></strong>
	</div>
<? } ?>
  <div class='section section-large' id='login-section'>
      <div class='section-header' large='large'>
        <h3><?php echo $linha["nome"]; ?></h3>
      </div>
      <div class='section-body'>
        <? if($linha["layout"]){
            echo $linha["layout"];
        }else{
            echo $idioma["vazio_preview"];
        } ?>
      </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>