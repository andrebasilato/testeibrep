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
    	<? if($url[4] == "editar") { ?>
    		<li class="active"><?php echo $linha["nome"]; ?></li>
    	<? } else { ?>
    		<li class="active"><?= $idioma["nav_formulario"]; ?></li>
    	<? } ?>
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
      		<? if($_POST["msg"]) { ?>
      			<div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        			<strong><?= $idioma[$_POST["msg"]]; ?></strong>
      			</div>
      		<? } ?>
      		<? if(count($salvar["erros"]) > 0){ ?>
      			<div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                      <strong><?= $idioma["form_erros"]; ?></strong>
                          <? foreach($salvar["erros"] as $ind => $val) { ?>
                              <br />
                              <?php echo $idioma[$val]; ?>
                          <? } ?>
                      </strong>
      				</div>
      		<? } ?>
      		<form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
        		<input name="acao" type="hidden" value="salvar" />
                <? $acao_url = explode("?",$_SERVER['HTTP_REFERER']); ?>
                    <input name="acao_url" type="hidden" value="<?=base64_encode($acao_url[1])?>" />
        		<? if($url[4] == "editar") {
					echo '<input type="hidden" name="'.$config["banco"]["primaria"].'" id="'.$config["banco"]["primaria"].'" value="'.$linha[$config["banco"]["primaria"]].'" />';
					foreach($config["banco"]["campos_unicos"] as $campoid => $campo) {
				  	?>
        				<input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
        			<? 
					}
					  
					$linhaObj->GerarFormulario("formulario",$linha,$idioma);
				
				} else {
					$linhaObj->GerarFormulario("formulario",$_POST,$idioma);
				}
				?>
                          
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                </div>
			</form>
        </div>
    </div> 
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
<script type="text/javascript">
	var regras = new Array();
	<?php
	foreach($config["formulario"] as $fieldsetid => $fieldset) {
		foreach($fieldset["campos"] as $campoid => $campo) {
			if(is_array($campo["validacao"])){
					foreach($campo["validacao"] as $tipo => $mensagem) {
					  if($campo["tipo"] == "file"){
	?>
						regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
	<?				  }else{ ?>
						regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
	<?
					  }
					}
				}
		}
	}
	?>
	jQuery(document).ready(function($) {
		
		
	<?
		foreach($config["formulario"] as $fieldsetid => $fieldset) {
			foreach($fieldset["campos"] as $campoid => $campo) {
				if($campo["mascara"]){ ?>
				<?php if($campo["mascara"] == "99/99/9999") { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
					$('#<?= $campo["id"]; ?>').change(function() {
						if($('#<?= $campo["id"]; ?>').val() != '') {
							valordata = $("#<?= $campo["id"]; ?>").val();
							date= valordata;
							ardt= new Array;
							ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
							ardt=date.split("/");
							erro=false;
							if ( date.search(ExpReg)==-1){
								erro = true;
							}
							else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
								erro = true;
							else if ( ardt[1]==2) {
								if ((ardt[0]>28)&&((ardt[2]%4)!=0))
									erro = true;
								if ((ardt[0]>29)&&((ardt[2]%4)==0))
									erro = true;
							}
							if (erro) {
								alert("\"" + valordata + "\" não é uma data válida!!!");
								$('#<?= $campo["id"]; ?>').focus();
								$("#<?= $campo["id"]; ?>").val('');
								return false;
							}
							return true;
						}
					});
				<?php } elseif($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
					$('#<?= $campo["id"]; ?>').focusout(function(){
						var phone, element;
						element = $(this);
						element.unmask();
						phone = element.val().replace(/\D/g, '');
						if(phone.length > 10) {
							element.mask("(99) 99999-999?9");
						} else {
							element.mask("(99) 9999-9999?9");
						}
					}).trigger('focusout');
				<?php } else { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
				<?php } ?>
            <? 
            }
				if($campo["datepicker"]){
	?>
		$( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
	<?
				}
				if($campo["numerico"]){
	?>
		$("#<?= $campo["id"]; ?>").keypress(isNumber);
		$("#<?= $campo["id"]; ?>").blur(isNumberCopy);
	<?
				}
				if($campo["decimal"]){
	?>
		$("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
	<?

				}
			}
		}
	?>			
	});
</script>
</div>
</body>
</html>