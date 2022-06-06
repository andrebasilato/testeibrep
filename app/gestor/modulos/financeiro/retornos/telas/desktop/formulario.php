<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<script type="text/javascript">
function is_array(input){
	return typeof(input)=='object'&&(input instanceof Array);
}

</script>
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
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>">Financeiro</a> <span class="divider">/</span></li>
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
        	<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2> 
            <div class="tabbable tabs-left">
            <?php //incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
              	<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
        
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
            
            	</div></div></div>
            
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
				if($campo["mascara"]){
	?>
		$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
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
				
				if ($campo["botao_hide"]){
					
					if ($campo['tipo'] != 'select') { ?>
					
						if($('#<?= $campo["id"]; ?>').attr('checked')== 'checked')
							$('#div_<?= $campo["iddiv"]; ?>').show();
						
						$('#<?= $campo["id"]; ?>').click(function() {
							$('#div_<?= $campo["iddiv"]; ?>').toggle("fast");
							
							if($('#<?= $campo["id"]; ?>').attr('checked')!= 'checked')
								$('#div_<?= $campo["iddiv"]; ?> option[value=""]').attr('selected','selected');

						$('#<?= $campo["iddiv2"]; ?>').attr("value","");
						$('#div_<?= $campo["iddiv2"]; ?>').hide("fast");
						
						});
	<?              } else { ?>
		
						var aux_d = $('#<?= $campo["id"]; ?>').attr('value');
						if (aux_d == 'O'){
							$('#div_form_<?= $campo["iddiv"]; ?>').show();
							$('#div_form_<?= $campo["iddiv2"]; ?>').show();
							$('#div_form_<?= $campo["iddiv3"]; ?>').show();
						} else if(aux_d == 'S'){
							if($('#div_form_<?= $campo["iddiv"]; ?>').css('display') == 'none'){
								for (var i = 0; i < regras.length; i++){
									if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>')
									   regras.splice(1, i);
								}
							}
						}
						
						$('#<?= $campo["id"]; ?>').change(function() {
							aux_d = $('#form_tipo').attr('value');
							if (aux_d == 'O'){
								$('#div_form_<?= $campo["iddiv"]; ?>').show("fast");
								$('#div_form_<?= $campo["iddiv2"]; ?>').show("fast");
								$('#div_form_<?= $campo["iddiv3"]; ?>').show("fast");
								var tem = false;
								for (var i = 0; i < regras.length; i++){
									if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>')
									   tem = true;
								}
								if(!tem)
									regras.push("required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>");								
							} else {
								if(aux_d == 'S'){
									$('#form_<?= $campo["iddiv"]; ?> option[value=""]').attr('selected','selected');
									$('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
									$('#form_<?= $campo["iddiv2"]; ?> option[value=""]').attr('selected','selected');
									$('#div_form_<?= $campo["iddiv2"]; ?>').hide("fast");
									$('#form_<?= $campo["iddiv3"]; ?>').attr("value","");
									$('#div_form_<?= $campo["iddiv3"]; ?>').hide("fast");			
									for (var i = 0; i < regras.length; i++){
										if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>')
										   regras.splice(1, i);
									}
																								
								} else {
									regras.push("required,<?= $campo["id"]; ?>,<?= $idioma[$campo["nome"]."_vazio"];?>");
									
								}
							}
						}
						
						);
      <?

					}
					
				}
			}
		}
	?>			
	});
</script>
</div>
</body>
</html>