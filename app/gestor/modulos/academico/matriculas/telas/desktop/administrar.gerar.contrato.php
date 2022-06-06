<html>
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style>
body {
  min-width: 500px;
}
.container-fluid {
  min-width: 500px;
}
</style>
<script>
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
</head>
<body>
<div class="container-fluid" >
  <div class="page-header">
    <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  <br />
  <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>" method="post" onSubmit="return valida(this, regras);" target="_parent">	  
	<?php echo $idioma["idcontrato"]; ?>
    <br />
    <select name="idcontrato" id="idcontrato" style="width:auto">
      <option value=""></option>
	  <? foreach($contratos as $contrato) { ?>
        <option value="<?= $contrato["tipo"]; ?>|<?= $contrato["idcontrato"]; ?>" <?php if ($contrato["idcontrato"] == $url[8]) { ?> selected="selected" <?php } ?> ><?= $contrato["nome"]; ?></option>
	  <? } ?>
    </select>
    <br />
    <br />
	<?php echo $idioma["campo_adicional_local"]; ?> 
    <br />
    <input name="campo_adicional_local" type="text" id="campo_adicional_local" class="span4" style="height:30px;" />
    <br />
    <br />
	<?php echo $idioma["campo_adicional_1"]; ?>  
    <br />
    <textarea id="campo_adicional_1" name="campo_adicional_1" style="width:500px; height:100px;"></textarea>
    <br />
    <br />
    <?php echo $idioma["campo_adicional_2"]; ?>  
    <br />
    <input name="campo_adicional_2" type="text" id="campo_adicional_2" class="span4" style="height:30px;" />
    <br />
    <br />
    <?php echo $idioma["campo_adicional_3"]; ?> 
    <br />
    <input name="campo_adicional_3" type="text" id="campo_adicional_3" class="span4" style="height:30px;" />
    <br />
    <br />
    <?php echo $idioma["campo_adicional_4"]; ?> 
    <br />
    <input name="campo_adicional_4" type="text" id="campo_adicional_4" class="span4" style="height:30px;" />
    <br />
    <br />
    <input name="acao" type="hidden" value="gerar_contrato" /> 
    <input class="btn" type="submit" name="salvar" id="salvar" value="<?php echo $idioma["btn_salvar"]; ?>" />  
  </form>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  var regras = new Array();
  regras.push("required,idcontrato,<?php echo $idioma["idcontrato_vazio"]; ?>");
  jQuery(document).ready(function($) {	
  var editor = CKEDITOR.replace('campo_adicional_1', {				
	extraPlugins: 'htmlwriter',
	height: 100,
	width: 500,
	toolbar: [
	  ["Bold","Italic","Underline","StrikeThrough","-","Outdent","Indent","NumberedList","BulletedList"],
	  ["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
	  ["Image","Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],
	],
	contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin: 0px;}',
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
	colorButton_enableMore: true,	
	colorButton_foreStyle: {
	  element: 'font',
	  attributes: { 'color': '#(color)' }
	},
	colorButton_backStyle: {
	  element: 'font',
	  styles: { 'background-color': '#(color)' }
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
	htmlFilter.addRules( {
	  elements: {
		$: function( element ) {
		  var style, match, width, height, align;
		  if ( element.name == 'img' ) {
			style = element.attributes.style;
			if ( style ) {
			  match = ( /(?:^|\s)width\s*:\s*(\d+)px/i ).exec( style );
			  width = match && match[1];
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
		  if ( element.name == 'p' ) {
			style = element.attributes.style;
			if ( style ) {
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
