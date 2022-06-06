<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
</head>
<body>
<? incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["configuracoes"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?php echo $linha["nome"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
		<table border="0" cellspacing="0" cellpadding="8">
			<tr>
				<td>
					<img <img
						src="/api/get/imagens/vendedores_avatar/x/120/<?php echo $linha["avatar_servidor"]; ?>"/>
				</td>
				<td>
					<h2 class="tituloEdicao"><?= $linha["nome"]; ?>
						<small>(<?= $linha["email"]; ?>)</small>
					</h2>
				</td>
			</tr>
		</table>
        <div class="tabbable tabs-left">
          <div class="tab-content">
            <div class="tab-pane active" id="tab_editar">
              <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in"> 
                    <?php /*?><button class="close" data-dismiss="alert">×</button><?php */?>
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
                <? if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <?php /*?><button class="close" data-dismiss="alert">×</button><?php */?>
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                <? } ?>
                <form method="post"  onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar" />
                  <? $acao_url = explode("?",$_SERVER['HTTP_REFERER']); ?>
                  <input name="acao_url" type="hidden" value="<?=base64_encode($acao_url[1])?>" />
                    <input type="hidden" name="<?= $config["banco"]["primaria"]?>" id="<?= $config["banco"]["primaria"]?>" value="<?= $linha[$config["banco"]["primaria"]]; ?>" />
                    <?php foreach($config["banco"]["campos_unicos"] as $campoid => $campo) { ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <?php 
                    }
                    $linhaObj->GerarFormulario("formulario",$linha,$idioma);
                    ?> 
                    <div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;

                      <a href="/<?php echo $url[0]; ?>" class="btn" ><?= $idioma["btn_cancelar"]; ?></a>
                    </div>
                  </fieldset>
                </form>
                <div class="clearfix"></div>     
            </div>
          </div>
        </div>                            
      </div>
  </div> 
  <div class="span3">
      <?php //incluirLib("sidebar_configuracoes",$config); ?>    
  </div>
</div>
<? incluirLib("rodape",$config,$usu_vendedor); ?>
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
					if($campo["tipo"] == "file"){
					?>
						regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
					<? }else{ ?>
						regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
					<?
					}
				}
			}
		}
	}
	?>

	jQuery(document).ready(function($) {	
		$(".verificaSenha").passStrength({userid: "#form_email"});
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
	function deletaArquivo(div, obj) {
		if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
			solicita(div, obj);		
		}
	}
</script>
</div>
</body>
</html>