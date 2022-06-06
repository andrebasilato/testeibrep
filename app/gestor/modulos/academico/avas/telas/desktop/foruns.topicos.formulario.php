<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>

<?php incluirLib("head_forum",$config,$usuario); ?>
<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
<script type="text/javascript">
$(document).ready(function(){
	$('#coluna-conteudo').css('left', 0).css('width', '100%');
});
function valida(form,regras) {
	if (!validateFields(form, regras)) { 
		return false;
	} else { 
		fechaLoading();
		window.close();
		return true;
	} 
}
</script>
<style type="text/css">
  .content {
	min-width:590px;
	padding:0px !important;
  }
  .conteudo {
	min-width:390px;
  }
</style>
</head>
<body>
<div class="content" style="min-width:500px;">
  <div class="conteudo" style="min-height:450px;min-width:390px;">
    <div class="coluna-dados" id="coluna-conteudo">
      <div class="area area-conteudo" >
        <div class="row-fluid">
          <div class="span12">
            <div class="forum-form">
              <div class="forum-base-titulo corbgpadrao"><?php if($url[8] != "moderar") { echo $idioma["criar_topico"]; } else { echo $idioma["moderar_topico"]; } ?></div>
              <div>
                <?php
				if($url[8] == "moderar") {
					$action = '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/'.$url[7].'/mensagens';
				} else {
					$action = '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6];
				}
				?>
                <form method="post" action="<?php echo $action; ?>" onsubmit="return valida(this, regras);" enctype="multipart/form-data" target="_parent">
                  <input name="acao" id="acao" type="hidden" value="<?php if($url[8] != "moderar") { ?>salvar_topico<?php } else { ?>moderar_topico<?php } ?>" />
                  <input name="idforum" id="idforum" type="hidden" value="<?php echo $url[5]; ?>" />
                  <?php if($url[8] != "moderar") { ?>
                    <label><?php echo $idioma["nome"]; ?></label>
                    <br clear="all"/>
                    <input id="form_nome" name="nome" type="text" />
				  <?php } ?> 
                  <br clear="all"/>
                  <label><?php echo $idioma["mensagem"]; ?></label>
                  <br clear="all"/>
                  <textarea id="form_mensagem" name="<?php if($url[8] != "moderar") { ?>mensagem<?php } else { ?>moderar<?php } ?>"><?php if($topico["moderado"] == "S") { echo nl2br($topico["moderado_mensagem"]); } else { echo nl2br($topico["mensagem"]); } ?></textarea>
                  <?php if($url[8] != "moderar") { ?>
                      <label onClick="document.getElementById('form_arquivo').style.display = '';"><?php echo $idioma["anexar_arquivo"]; ?></label>
                      <input id="form_arquivo" name="arquivo" type="file" style="display:none;" />
                  <?php } ?>
                   <br clear="all"/>
                  <div style="margin-top:20px;" class="divisor">
                    <input class="corbgpadrao btfade" type="submit" value="<?php echo $idioma["salvar"]; ?>" />
                  </div>
                </form>
              </div>
            </div>
          </div> <!-- principal area -->
        </div>
      </div> <!-- area-conteudo --> 	
    </div><!-- coluna dados -->
  </div>
</div>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/js/validation.js"></script>
<script type="text/javascript">
  var regras = new Array();
  <?php 
  
  if($url[8] != "moderar") { ?>
	regras.push("required,form_nome,<?php echo $idioma["nome_vazio"]; ?>");
	regras.push("formato_arquivo,form_arquivo,jpg|jpeg|doc|docx|pdf|ppt|pptx,,<?php echo $idioma["extensao_arquivo_nao_permitida"]; ?>");
  <?php } ?> 
  regras.push("required,form_mensagem,<?php echo $idioma["mensagem_vazio"]; ?>");
  
	jQuery(document).ready(function($) {
		var editor = CKEDITOR.replace( '<?php if($url[8] != "moderar") { ?>mensagem<?php } else { ?>moderar<?php } ?>', {
			height: 290,
			width: '100%',
			toolbar: [
				["Bold","Italic","Underline","StrikeThrough","-","Outdent","Indent","NumberedList","BulletedList"],
				["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
				["Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],["Image"]
			],
			contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',
			docType: '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',
			coreStyles_bold: { element: 'b' },
			coreStyles_italic: { element: 'i' },
			coreStyles_underline: { element: 'u' },
			font_style: {
				element: 'font',
				attributes: { 'face': '#(family)' }
			},
			fontSize_sizes: '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28;36px/36;48px/48;72px/72',
			fontSize_style: {
				element: 'font',
				attributes: { 'size': '#(size)' },
				styles: { 'font-size': '#(size)px' }
			},
			on: { 'instanceReady': configureFlashOutput }
		});
		function configureFlashOutput( ev ) {
			var editor = ev.editor,
			dataProcessor = editor.dataProcessor,
			htmlFilter = dataProcessor && dataProcessor.htmlFilter;
			
			dataProcessor.writer.selfClosingEnd = '>';
			
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
	});
	
</script>
</body>
</html>