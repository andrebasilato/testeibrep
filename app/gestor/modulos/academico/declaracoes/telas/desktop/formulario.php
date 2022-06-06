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
		  <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
		  <div class="tabbable tabs-left">
			<?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
      		<? if($_POST["msg"]) { ?>
      			<div class="alert alert-success fade in"> 
                	<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        			<strong><?= $idioma[$_POST["msg"]]; ?></strong>
      			</div>
      		<? } ?>
      		<? if(count($salvar["erros"]) > 0){ ?>
      			<div class="alert alert-error fade in">
                	<button class="close" data-dismiss="alert">×</button>
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
                <?php if ($url[3] != 'cadastrar') { ?>
                    <div class="control-group">
                        <div class="controls" style="padding-left:120px; !important">
                            <a id="form_botao_variaveis_imagens" class="btn" name="botao_variaveis" style="outline:none;"><i class="icon-list-alt"></i><?= $idioma["btn_variaveis_imagens"]; ?></a>
                            <div id="div_form_botao_variaveis_imagens" style="display: none;">
                                <p>&nbsp;</p>
                                <table class="table-striped table-bordered table-condensed" width="100%">
                                    <tr>
                                        <td colspan='4' style='border:0px; border-bottom: 1px solid #DDDDDD; background-color:#F8F8F8' width='100%'><strong><?= $idioma["tabela_variaveis_imagens"]; ?></strong></td>
                                    </tr>
                                <? if($imagensArray){?>
                                <? foreach($imagensArray as $ind => $val){ ?>
                                    <tr>
                                        <td><strong>[[I][<?= $val["iddeclaracao_imagem"]; ?>]]</strong></td>
                                        <td><?= $val["nome"]; ?></td>
                                        <td><a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza_imagem/".$val["iddeclaracao_imagem"]; ?>" rel="facebox tooltip" class="btn btn-mini" data-original-title="<?= $idioma["btn_visualizar"]; ?>" data-placement="left"><i class="icon-picture"></i> <?php echo $idioma["visualizar"]; ?></a></td>
                                    </tr>
                                <? } ?>
                                <? }else{ ?>
                                    <tr>
                                        <td colspan="3"><?= $idioma["tabela_imagens_vazio"] ?></td>
                                    </tr>
                                <? } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                </div>
			</form>
			
		  </div>
        </div>
    </div> 
    <?php /*?><div class="span3">
     	<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
    	<div class="well">
        	<?= $idioma["nav_novousuario_explica"]; ?><br /><br />
			<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar" class="btn primary"><?= $idioma["nav_novousuario"]; ?></a>
    	</div>
        <? } ?>
    	<?php  incluirLib("sidebar_".$url[1],$config); ?>    
    </div><?php */?>
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script src="/assets/js/ajax.js"></script>
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
		$("#<?= $campo["id"]; ?>").maskMoney({decimal:",",thousands:".",precision:1});	
	<?

				}
				if($campo["botao_hide"]){
				?>
					$('#<?= $campo["id"]; ?>').click(function() {
					  $('#div_<?= $campo["id"]; ?>').toggle("slow");
					});
				<?
				
				}
							if($campo["editor"]){	
?>	
	var editor = CKEDITOR.replace( '<?= $campo["nome"]; ?>', {
/*
* Ensure that htmlwriter plugin, which is required for this sample, is loaded.
*/
extraPlugins: 'htmlwriter',

height: 290,
width: '100%',
toolbar: [
["Bold","Italic","Underline","StrikeThrough","-",
"Outdent","Indent","NumberedList","BulletedList"],
["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
["Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],
],

/*
* Style sheet for the contents
*/
contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',

/*
* Quirks doctype
*/
docType: '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',

/*
* Core styles.
*/
coreStyles_bold: { element: 'b' },
coreStyles_italic: { element: 'i' },
coreStyles_underline: { element: 'u' },

/*
* Font face.
*/

// Define the way font elements will be applied to the document. The "font"
// element will be used.
font_style: {
element: 'font',
attributes: { 'face': '#(family)' }
},

/*
* Font sizes.
*/

// The CSS part of the font sizes isn't used by Flash, it is there to get the
// font rendered correctly in CKEditor.
fontSize_sizes: '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28;36px/36;48px/48;72px/72',
fontSize_style: {
element: 'font',
attributes: { 'size': '#(size)' },
styles: { 'font-size': '#(size)px' }
} ,

/*
* Font colors.
*/
/*colorButton_enableMore: true,

colorButton_foreStyle: {
element: 'font',
attributes: { 'color': '#(color)' }
},

colorButton_backStyle: {
element: 'font',
styles: { 'background-color': '#(color)' }
},*/

on: { 'instanceReady': configureFlashOutput }
});

/*
* Adjust the behavior of the dataProcessor to match the
* requirements of Flash
*/
function configureFlashOutput( ev ) {
var editor = ev.editor,
dataProcessor = editor.dataProcessor,
htmlFilter = dataProcessor && dataProcessor.htmlFilter;

// Out self closing tags the HTML4 way, like <br>.
dataProcessor.writer.selfClosingEnd = '>';

// Make output formatting match Flash expectations
var dtd = CKEDITOR.dtd;
for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
dataProcessor.writer.setRules( e, {
indent: false,
breakBeforeOpen: false,
breakAfterOpen: false,
breakBeforeClose: false,
breakAfterClose: false
});
}
dataProcessor.writer.setRules( 'br', {
indent: false,
breakBeforeOpen: false,
breakAfterOpen: false,
breakBeforeClose: false,
breakAfterClose: false
});

// Output properties as attributes, not styles.
htmlFilter.addRules( {
elements: {
$: function( element ) {
var style, match, width, height, align;

// Output dimensions of images as width and height
if ( element.name == 'img' ) {
style = element.attributes.style;

if ( style ) {
// Get the width from the style.
match = ( /(?:^|\s)width\s*:\s*(\d+)px/i ).exec( style );
width = match && match[1];

// Get the height from the style.
match = ( /(?:^|\s)height\s*:\s*(\d+)px/i ).exec( style );
height = match && match[1];

if ( width ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
element.attributes.width = width;
}

if ( height ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
element.attributes.height = height;
}
}
}

// Output alignment of paragraphs using align
if ( element.name == 'p' ) {
style = element.attributes.style;

if ( style ) {
// Get the align from the style.
match = ( /(?:^|\s)text-align\s*:\s*(\w*);?/i ).exec( style );
align = match && match[1];

if ( align ) {
element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
element.attributes.align = align;
}
}
}

if ( element.attributes.style === '' )
delete element.attributes.style;

return element;
}
}
});
}
	<? } 

			}
		}
	?>

    $('#form_botao_variaveis_imagens').click(function() {
        $('#div_form_botao_variaveis_imagens').toggle("slow");
    });

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