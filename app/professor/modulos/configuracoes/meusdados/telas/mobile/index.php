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
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["configuracoes"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?php echo $linha["nome"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span9">
      <div class="box-conteudo">
        <h2 class="tituloEdicao"><?= $linha["nome"]; ?> <small>(<?= $linha["email"]; ?>)</small></h2>
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
                      <?php /*?><button type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><?= $idioma["btn_cancelar"]; ?></button><?php */?>
                      <a href="javascript:void(0);" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><?= $idioma["btn_cancelar"]; ?></a>
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
      <?php incluirLib("sidebar_configuracoes",$config); ?>    
  </div>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
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