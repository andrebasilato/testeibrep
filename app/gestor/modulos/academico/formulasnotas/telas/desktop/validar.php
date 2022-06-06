<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
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
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><li><?php echo $linha["nome"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["nav_validar"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <?php if($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
          <div class="tabbable tabs-left">
			<?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php if($url[3] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
				<? if($validar["sucesso"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["validar_sucesso"]; ?></strong>
                    <br />
                    <br />
					<strong><?= $validar["formula"]; ?> = <?= number_format($validar["valor"], 1, ",", "."); ?></strong>
                  </div>
                <? } elseif($validar["erro"]) { ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["formula_invalida"]; ?></strong>
                    <br />
                    <br />
					<strong><?= $validar["formula"]; ?></strong>
                  </div>
                <? } ?>
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="validar" />
				  <?php 
				  $_POST["formula"] = $linha["formula"];
				  $linhaObj->GerarFormulario("formulario_validar",$_POST,$idioma); 
				  ?>
                  <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_validar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
      var regras = new Array();

      jQuery(function($){		
		<?php 
		$temVariavelN1 = strpos($_POST["formula"],"[[N][1]]");
		$temVariavelN2 = strpos($_POST["formula"],"[[N][2]]");
		$temVariavelV1 = strpos($_POST["formula"],"[[V][1]]");
		$temVariavelV2 = strpos($_POST["formula"],"[[V][2]]");
		$temVariavelPN1 = strpos($_POST["formula"],"[[PN][1]]");
		$temVariavelPN2 = strpos($_POST["formula"],"[[PN][2]]");
		$temVariavelPV1 = strpos($_POST["formula"],"[[PV][1]]");
		$temVariavelPV2 = strpos($_POST["formula"],"[[PV][2]]");		
		if($temVariavelN1 !== false) { ?>
		  $("#div_form_nota_normal_1").show();
		  regras.push("required,form_nota_normal_1,<?php echo $idioma["nota_normal_1_vazio"]; ?>");
		  $("#form_nota_normal_1").maskMoney({decimal:",",thousands:".",precision:1});
		<?php } ?>
		<?php if($temVariavelN2 !== false) { ?>
		  $("#div_form_nota_normal_2").show();
		  regras.push("required,form_nota_normal_2,<?php echo $idioma["nota_normal_2_vazio"]; ?>");
		  $("#form_nota_normal_2").maskMoney({decimal:",",thousands:".",precision:1});
		<?php } ?>
		<?php if($temVariavelV1 !== false) { ?>
		  $("#div_form_nota_virtual_1").show();
		  regras.push("required,form_nota_virtual_1,<?php echo $idioma["nota_virtual_1_vazio"]; ?>");
		  $("#form_nota_virtual_1").maskMoney({decimal:",",thousands:".",precision:1});
		<?php } ?>
		<?php if($temVariavelV2 !== false) { ?>
		  $("#div_form_nota_virtual_2").show();
		  regras.push("required,form_nota_virtual_2,<?php echo $idioma["nota_virtual_2_vazio"]; ?>");
		  $("#form_nota_virtual_2").maskMoney({decimal:",",thousands:".",precision:1});
		<?php } ?>
		<?php if($temVariavelPN1 !== false) { ?>
		  $("#div_form_peso_normal_1").show();
		  regras.push("required,form_peso_normal_1,<?php echo $idioma["peso_normal_1_vazio"]; ?>");
		  $("#form_peso_normal_1").keypress(isNumber);
		  $("#form_peso_normal_1").blur(isNumberCopy);
		<?php } ?>
		<?php if($temVariavelPN2 !== false) { ?>
		  $("#div_form_peso_normal_2").show();
		  regras.push("required,form_peso_normal_2,<?php echo $idioma["peso_normal_2_vazio"]; ?>");
		  $("#form_peso_normal_2").keypress(isNumber);
		  $("#form_peso_normal_2").blur(isNumberCopy);
		<?php } ?>
		<?php if($temVariavelPV1 !== false) { ?>
		  $("#div_form_peso_virtual_1").show();
		  regras.push("required,form_peso_virtual_1,<?php echo $idioma["peso_virtual_1_vazio"]; ?>");
		  $("#form_peso_virtual_1").keypress(isNumber);
		  $("#form_peso_virtual_1").blur(isNumberCopy);
		<?php } ?>
		<?php if($temVariavelPV2 !== false) { ?>
		  $("#div_form_peso_virtual_2").show();
		  regras.push("required,form_peso_virtual_2,<?php echo $idioma["peso_virtual_2_vazio"]; ?>");
		  $("#form_peso_virtual_2").keypress(isNumber);
		  $("#form_peso_virtual_2").blur(isNumberCopy);
		<?php } ?>		
      });
    </script>
  </div>
</body>
</html>