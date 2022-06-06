<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="icon" href="/assets/img/favicon.ico">
<style type="text/css">
body {
  padding-top: 40px;
  background-image:none;
}
h2 {
  font-size:30px;	
  text-transform:uppercase;
  line-height:110%;
  margin:25px;
  color:#666;
}

body,td,th {
  font-size: 12px;
  color: #666666;
}
a:link {
  color: #000000;
}
a:visited {
  color: #000000;
}
a:active {
  color: #000;
}
p {
  margin-left:25px;	
}
.breadcrumb {
  font-size:10px;	
}
a:hover {
  color: #000000;
}
</style>
<script>
function valida(form,regras) {
  if (!validateFields(form, regras))
	return false;
  else {
	fechaLoading();
	return true;
  }
}
</script>
</head>
<body>
<div class="container"> 
  <div style="margin-bottom:25px"><a href="/<?= $url[0]; ?>" class="logo"><?php/*<img src="<?php echo $config['logo_pequena']; ?>" width="135" height="50" />*/?></a></div>
  <div class="row">
  	<ul class="breadcrumb">
      <li><?= $idioma["nav_inicio"]; ?><span class="divider">/</span></li>
      <li><?= $idioma["nav_relatorios"]; ?> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
      <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
      <span class="pull-right" style="padding-top:3px; color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>  
    <h2><?= $idioma["pagina_titulo"]; ?></h2>  
    <p>Selecione as opções abaixo.</p>      
    <p>
      <form method="get" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html" id="formRelatorio" class="form-horizontal" target="_blank" onsubmit="return valida(this, regras)" >    
	  	<? $relatorioObj->GerarFormulario("formulario",$linha,$idioma); ?>
        <div class="form-actions">
          <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_gerar_html"]; ?>" onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/html'">
          &nbsp;
          <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_gerar_xlsx"]; ?>" onclick="document.getElementById('formRelatorio').action = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/xls'">
          &nbsp;    
		  <a href="/gestor/relatorios" class="btn dropdown-toggle"> <?= $idioma["btn_cancelar"]; ?> </a>
        </div>
        </fieldset>
      </form>
      </p>      
  </div>
  </div>
</div>
<?php incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
<link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">
	  function valida(form,regras) {
		if (!validateFields(form, regras))
		  return false;
		else {
		  fechaLoading();
		  return true;
		}
	  }
    </script>
	<script type="text/javascript">
		var regras = new Array();
		<?php
		foreach($config["formulario"] as $fieldsetid => $fieldset) {
			foreach($fieldset["campos"] as $campoid => $campo) {
				if(is_array($campo["validacao"])){
					foreach($campo["validacao"] as $tipo => $mensagem) {
						if($campo["id"] != "form_idpais"){
							if($campo["tipo"] == "file"){ ?>
								regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
							<? } else { ?>
								regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
							<?
							}
						}else{
							?>
							regras.push("<?php echo $tipo; ?>,form_idpais3,<?php echo $idioma[$mensagem]; ?>");
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
					if($campo["datepicker"]){ ?>
						$( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
					<?
					}
					if($campo["numerico"]){ ?>
						$("#<?= $campo["id"]; ?>").keypress(isNumber);
						$("#<?= $campo["id"]; ?>").blur(isNumberCopy);
					<?
					}
					if($campo["decimal"]){ ?>
						$("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
					<?
 					}
					if($campo["json"]){ ?>
						$('#<?=$campo["json_idpai"];?>').change(function(){
							if($(this).val()){
								$.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
									var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
									for (var i = 0; i < json.length; i++) {
										var selected = '';
										if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
											var selected = 'selected';
										options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
									}	
									$('#<?=$campo["id"];?>').html(options);
								});
							} else {
								$('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
							}
						});
						
						$.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', function(json){
							var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';	
							for (var i = 0; i < json.length; i++) {
								var selected = '';
								if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
									var selected = 'selected';
								options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
							}
							$('#<?=$campo["id"];?>').html(options);
						});
						<?
					}
				}
			}
		?>
		});
	</script>

</body>
</html>