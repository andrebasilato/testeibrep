<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/css/etapas.css" media="all" type="text/css"/>
<style>.hidden{margin-top:-75px;}</style>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">

<script>
function valida_form(form, ru) {
	CKEDITOR.instances.form_descricao.updateElement();
	if (validateFields(form, ru))
		return true;
	return false;
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
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $idioma["nav_formulario"]; ?></li>
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
        <? if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in"><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a><strong><?= $idioma[$_POST["msg"]]; ?></strong></div>
        <? } ?>
        <? if(count($salvar["erros"]) > 0){ ?>
            <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma["form_erros"]; ?></strong>
                <? foreach($salvar["erros"] as $ind => $val) { ?>
                    <br />
                    <?php echo $idioma[$val]; ?>
                <? } ?>
            </div>
        <? } ?>
        <div class="control-group">
          <form method="post" onsubmit="return valida_form(this, regras)" enctype="multipart/form-data" class="form-horizontal">
            <input name="acao" type="hidden" value="salvar" />
            <?php if($linha["nome"]) { ?>
            	<input name="idpessoa" type="hidden" value="<?php echo $linha["idpessoa"]; ?>" />
				<input name="idusuario" type="hidden" value="<?php echo $usuario["idusuario"]; ?>" />
            <?php } ?>
            <div class="control-group">
              <legend><?=$idioma["titulo_formulario"]?></legend>
              <div class="well span3">
                <? if($_POST["cpf"]){?>
                  <div class="control">
                    <input name="cpf" type="hidden" value="<?php echo $_POST["cpf"]; ?>" />
                    <label class="" for="form_documento"><strong><?=$idioma["cpf"];?></strong></label>
                    <input id="form_documento" class="span2" type="text" maxlength="14" value="<?=$_POST["cpf"];?>" name="documento" readonly="readonly">
                  </div>
                  <br />
                  <div class="control"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?=$idioma["outro_cpf"];?></a></div>
                <? }else if($_POST["cnpj"]){ ?>
                  <div class="control">
                    <input name="cnpj" type="hidden" value="<?php echo $_POST["cnpj"]; ?>" />
                    <label class="" for="form_documento"><strong><?=$idioma["cnpj"];?></strong></label>
                    <input id="form_documento" class="span2" type="text" maxlength="14" value="<?=$_POST["cnpj"]?>" name="documento" readonly="readonly">
                  </div>
                  <br />
                  <div class="control"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>"><?=$idioma["outro_cnpj"];?></a></div>
                <? } ?>
              </div>
            </div>
			<fieldset>
            	<legend><?php echo $idioma["titulo_formulario_pessoa"]?></legend>
                <div class="control-group">
                	<label for="form_nome" class="control-label"><strong><?php echo $idioma["form_nome"]; ?></strong></label>
                    <div class="controls">
                    	<input type="text" maxlength="100" value="<?php if($linha["nome"]) { echo $linha["nome"]; } else { echo $_POST["nome"]; } ?>" name="nome" id="form_nome" class="span5" <?php if($linha["nome"]) { ?>readonly="readonly"<?php } ?>>
                    </div>
                </div>
                <div class="control-group">
                	<label for="form_email" class="control-label"><strong><?php echo $idioma["form_email"]; ?></strong></label>
                    <div class="controls">
                    	<div class="input-prepend">
                        	<span class="add-on">@</span>
                            <input type="text" maxlength="100" value="<?php if($linha["email"]) { echo $linha["email"]; } else { echo $_POST["email"]; } ?>" name="email" id="form_email" class="span5" <?php if($linha["email"]) { ?>readonly="readonly"<?php } ?>>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label for="form_telefone" class="control-label"><strong><?php echo $idioma["form_telefone"]; ?></strong></label>
                    <div class="controls">
                        <input type="text" value="<?php if($linha["telefone"]) { echo $linha["telefone"]; } else { echo $_POST["telefone"]; } ?>" name="telefone" id="form_telefone" class="span2" <?php if($linha["telefone"]) { ?>readonly="readonly"<?php } ?>>
                    </div>
                </div>
                <div class="control-group">
                    <label for="form_celular" class="control-label"><?php echo $idioma["form_celular"]; ?></label>
                    <div class="controls">
                        <input type="text" value="<?php if($linha["celular"]) { echo $linha["celular"]; } else { echo $_POST["celular"]; } ?>" name="celular" id="form_celular" class="span2" <?php if($linha["celular"]) { ?>readonly="readonly"<?php } ?>>
                    </div>
                </div>
            </fieldset>
			<fieldset>
            	<legend><?=$idioma["titulo_formulario_atendimento"]?></legend>
                <div class="control-group">
                    <label for="idassunto" class="control-label"><strong><?php echo $idioma["form_idassunto"]; ?></strong></label>
                    <div class="controls">
                        <select class="span5" id="idassunto" name="idassunto">
                            <option value=""></option>
                            <?php foreach($assuntos as $ind => $assunto) { ?>
                                <option value="<?php echo $assunto["idassunto"]; ?>" <?php if($_POST["idassunto"] == $assunto["idassunto"]) { ?>selected="selected"<?php } ?>><?php echo $assunto["nome"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group" id="subassunto" style="display:none;">
                	<label for="idsubassunto" id="label_idsubassunto" class="control-label"><strong><?php echo $idioma["form_idsubassunto"]; ?></strong></label>
                    <div class="controls">
                    	<select class="span4" id="idsubassunto" name="idsubassunto">
                        	<option value=""></option>
                        </select>
                    </div>
                </div>
				<div class="control-group">
                    <label for="idcurso" class="control-label"><?php echo $idioma["form_idcurso"]; ?></label>
                    <div class="controls">
                        <select class="span5" id="idcurso" name="idcurso">
                            <option value=""></option>
                            <?php foreach($cursos as $ind => $curso) { ?>
                                <option value="<?php echo $curso["idcurso"]; ?>" <?php if($_POST["idcurso"] == $curso["idcurso"]) { ?>selected="selected"<?php } ?>><?php echo $curso["nome"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="control-group">
                    <label for="idmatricula" class="control-label"><?php echo $idioma["form_idmatricula"]; ?></label>
                    <div class="controls">
                        <select class="span5" id="idmatricula" name="idmatricula">
                            <option value=""></option>
                            <?php foreach($matriculas as $ind => $matricula) { ?>
                                <option value="<?php echo $matricula["idmatricula"]; ?>" <?php if($_POST["idmatricula"] == $matricula["idmatricula"]) { ?>selected="selected"<?php } ?>><?php echo $matricula["nome"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                	<label for="form_titulo" class="control-label"><strong><?php echo $idioma["form_titulo"]; ?></strong></label>
                    <div class="controls">
                    	<input type="text" maxlength="100" value="<?php echo $_POST["titulo"]; ?>" name="titulo" id="form_titulo" class="span5">
                    </div>
                </div>
				<div class="control-group">
                	<label for="form_titulo" class="control-label"><strong><?php echo 'Arquivos'; ?></strong> </label>
                    <div class="controls">
                    	<div id="divArquivos">	
						  <input type="file" name="arquivo[1]" id="arquivo[1]" /> <input type="button" class="btn btn-primary btn-mini" onclick="novoArquivo();" name="enviar" value=" + " />
						  <br />
						</div>
                    </div>
                </div>
				<div class="control-group">
                	<label for="form_titulo" class="control-label"><?php echo $idioma["form_cliente_bloquear"]; ?></label>
                    <div class="controls">
                    	<input type="checkbox" <?php if($_POST["cliente_bloquear"]) echo 'checked="checked"'; ?> name="cliente_bloquear" id="form_cliente_bloquear" /> <?php echo $idioma["form_cliente_bloquear"] ?>
                    </div>
                </div>
                <div class="control-group">
                	<label for="form_descricao" class="control-label"><strong><?php echo $idioma["form_descricao"]; ?></strong></label>
                    <div class="controls">
                    	<textarea name="descricao" id="form_descricao" class="xxlarge"><?php echo $_POST["descricao"]; ?></textarea>
					</div>
                </div>
            </fieldset>          
            <div class="form-actions"><input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/ckeditor/sample.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
  <script type="text/javascript">

	  var regrasObr = new Array();
	  regrasObr.push("required,form_documento,<?php echo $idioma["documento_vazio"]; ?>");
	  regrasObr.push("required,form_nome,<?php echo $idioma["nome_vazio"]; ?>");
	  regrasObr.push("required,form_email,<?php echo $idioma["email_vazio"]; ?>");
	  regrasObr.push("valid_email,form_email,<?php echo $idioma["email_invalido"]; ?>");
	  regrasObr.push("required,form_telefone,<?php echo $idioma["telefone_vazio"]; ?>");
	  regrasObr.push("required,idassunto,<?php echo $idioma["assunto_vazio"]; ?>");
	  regrasObr.push("required,form_titulo,<?php echo $idioma["titulo_vazio"]; ?>");
	  regrasObr.push("required,form_descricao,<?php echo $idioma["descricao_vazio"]; ?>");
	  
	  var regras = regrasObr;
	  
	  jQuery(document).ready(function($) {
		  
	var editor = CKEDITOR.replace( 'descricao', {
/*
* Ensure that htmlwriter plugin, which is required for this sample, is loaded.
*/
extraPlugins: 'htmlwriter',

height: 290,
width: '85%',
toolbar: [
["Bold","Italic","Underline","StrikeThrough","-",
"Outdent","Indent","NumberedList","BulletedList"],
["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
["Image","Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],
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
		  
		  
			  
		$('#idassunto').change(function(){
		  if($(this).val()){
			$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/subassunto"; ?>',{idassunto: $(this).val(), ajax: 'true'}, function(json){
			  var options = '<option value="">– <?php echo $idioma["form_selecione_subassunto"]; ?> –</option>';
			  for (var i = 0; i < json.subassunto.length; i++) {
				options += '<option value="' + json.subassunto[i].idsubassunto + '" >' + json.subassunto[i].nome + '</option>';
			  }	
			  $('#idsubassunto').html(options);
			  if(json.subassunto_obrigatorio == "S") {
				regras = new Array();
				regras.push("required,idassunto,<?php echo $idioma["assunto_vazio"]; ?>");
				regras.push("required,idsubassunto,<?php echo $idioma["subassunto_vazio"]; ?>");
				$('#label_idsubassunto').html('<strong><?php echo $idioma["form_idsubassunto"]; ?></strong>');
				$('#subassunto').show();
				
				regras.push("required,form_titulo,<?php echo $idioma["titulo_vazio"]; ?>");
				regras.push("required,form_descricao,<?php echo $idioma["descricao_vazio"]; ?>");
			  } else {
				regras = new Array();
				regras.push("required,idassunto,<?php echo $idioma["assunto_vazio"]; ?>");
				$('#label_idsubassunto').html('<?php echo $idioma["form_idsubassunto"]; ?>');
				if(json.subassunto.length <= 0) {
				  $('#subassunto').hide();
				} else {
				  $('#subassunto').show();
				}
				regras.push("required,form_titulo,<?php echo $idioma["titulo_vazio"]; ?>");
				regras.push("required,form_descricao,<?php echo $idioma["descricao_vazio"]; ?>");
				
			  }
			});
		  } else {
			$('#idsubassunto').html('<option value="">– <?php echo $idioma["form_selecione_assunto"]; ?> –</option>');
			$('#subassunto').hide();
		  }
		});
	  });
  </script>
  
<?php /*?><script type="text/javascript">
	var regras = new Array();
	regras.push("formato_arquivo,arquivo[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
	regras.push("required,resposta,<?php echo $idioma['mensagem_vazio']; ?>");
</script><?php */?>
<script type="text/javascript">
function novoArquivo(){
	var IE = document.all?true:false
	var div_arquivos = document.getElementById( "divArquivos" );		
	if( !IE ){
		var length = div_arquivos.childNodes.length -1;
	}else{
		var length = div_arquivos.childNodes.length +1;			
	}		

	var input = document.createElement( 'INPUT' );
	input.setAttribute( "type" , "file" );
	id = "arquivo[" + length + "]";
	input.setAttribute( "name" , id);	
	input.setAttribute( "id" , id);
	div_arquivos.appendChild( input );
	var br = document.createElement('br');
	div_arquivos.appendChild(br);
	
	regras.push("formato_arquivo,"+id+",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}
</script>
</div>
</body>
</html>