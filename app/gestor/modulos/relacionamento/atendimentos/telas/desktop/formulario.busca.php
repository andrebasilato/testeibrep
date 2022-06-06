<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/css/etapas.css" media="all" type="text/css"/>
</head>
<body>
<? incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
      <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["nav_formulario"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
      <div class="box-conteudo">
		<? if(count($linha["erros"]) > 0){ ?>
          <div class="control-group">
            <div class="row alert alert-error fade in" style="width:470px; margin:0px;">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma["form_erros"]; ?></strong>
              <? foreach($linha["erros"] as $ind => $val) { ?><br /><?php echo $idioma[$val]; ?><? } ?>
            </div>
          </div> 
        <? } ?>         
        <div class="control-group">
          <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
            <input name="acao" type="hidden" value="buscar_pessoa" />
            <fieldset>
              <legend><?=$idioma["titulo_formulario"]?></legend>
              <div class="control-group">
                <label class="control-label" for="form_cpf">CPF:</label>
                <div class="controls">
                  <input id="form_cpf" class="span2" type="text" maxlength="14" <? if($_GET["cpf"] && !count($linha["erros"])) { ?> readonly="readonly"<? } ?> value="<?= $_GET["cpf"]; ?>" name="cpf">
                  <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_buscar"]; ?>">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="form_nome">CNPJ:</label>
                <div class="controls">
                  <input id="form_cnpj" class="span2" type="text" maxlength="18" value="" name="cnpj">
                  <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_buscar"]; ?>">
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
  	</div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
  <script type="text/javascript">
	var regras = new Array();
	//regras.push("valida_cpf,form_cpf,<?php echo $idioma["cpf_invalido"]; ?>");
	jQuery(document).ready(function($) {
	  $("#form_cpf").mask("999.999.999-99");
	  $("#form_cnpj").mask("99.999.999/9999-99");
	});
  </script>
</div>
</body>
</html>