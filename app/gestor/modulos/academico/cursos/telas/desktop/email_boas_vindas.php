<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
  <style>

#corpo_sms{
  width: 328px;
  height: 100px;
}
</style>
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $linha["nome"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
          <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>	
                <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
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
                  </div>
                <? } ?>   
                <form class="well" method="post" id="form">
                  <?php if($perfil["permissoes"][$url[2]."|10"]) { ?>
                    <a id="form_botao_variaveis" class="btn" name="botao_variaveis" style="outline:none;"><i class="icon-list-alt"></i><?= $idioma["btn_variaveis"]; ?></a>
                    <div id="div_form_botao_variaveis" style="display: none;">
                        <p>&nbsp;</p>
                        <table class="table-striped table-bordered table-condensed" width="100%">
                            <tr>
                                <td><strong><?= $idioma["variavel_nome_aluno"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_nome_aluno"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_id_aluno"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_id_aluno"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_email_aluno"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_email_aluno"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_cpf_cnpj_aluno"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_cpf_cnpj_aluno"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_matricula"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_matricula"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_curso"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_curso"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_oferta"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_oferta"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_escola"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_escola"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_sindicato"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_sindicato"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_numero_contrato"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_numero_contrato"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_valor_contrato"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_valor_contrato"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_forma_pagamento"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_forma_pagamento"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_quantidade_parcelas"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_quantidade_parcelas"]; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?= $idioma["variavel_link_ambiente_aluno"]; ?></strong></td>
                                <td><?= $idioma["texto_variavel_link_ambiente_aluno"]; ?></td>
                            </tr>
                        </table>
                    </div>
                    <br />
                  	<br />
                  	<p><?= $idioma["form_corpo_email"]; ?></p>    
                    <textarea id="corpo_email" name="corpo_email"><?= $linha["email_boas_vindas"]; ?></textarea>
                    <br />
                    <p><?= $idioma["form_corpo_sms"]; ?></p>    
                    <textarea id="corpo_sms" name="corpo_sms"><?= $linha["sms_boas_vindas"]; ?></textarea>
                    <p class="help-block"><?php echo $idioma["form_corpo_sms_ajuda"]; ?></p>
                    <br />
                    <br />
                    <input type="hidden" id="acao" name="acao" value="salvar_email_boasvindas">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>" />
				  <?php } else { ?>
                  	<!--Se não tiver permissão para inserir ou alterar-->
                    <br />
                    <br />
                  <?php } ?>
                </form>
              </div>
            </div>
          </div>                           
        </div>
      </div>    	
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
  
    <script src="/assets/plugins/ckeditor/sample.js"></script>
    <script src="/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.limit-1.2.source.js"></script>
<script type="text/javascript">

jQuery(document).ready(function($) {
    
    $('#corpo_sms').limit('160','#charsLeft');
    if($('#corpo_sms').val().length == 160){
      $('#charsLeft').html(0);
    }
});

var editor = CKEDITOR.replace( 'corpo_email', {
/*
* Ensure that htmlwriter plugin, which is required for this sample, is loaded.
*/
extraPlugins: 'htmlwriter',

height: 290,
width: '100%',
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
		$('#form_botao_variaveis').click(function() {
		  //$('#div_form_botao_variaveis_imagens').hide("slow", function(){
			$('#div_form_botao_variaveis').toggle("slow");	  
		  //});
		});
		/*$('#form_botao_variaveis_imagens').click(function() {
		  $('#div_form_botao_variaveis').hide("slow", function(){
		  	$('#div_form_botao_variaveis_imagens').toggle("slow");
		  });
		});*/

</script>
  </div>
</body>
</html>