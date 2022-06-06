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
    	fechaLoading();
    	window.close();
    	return true;
    }
</script>
</head>
<body>
<div class="container-fluid" >
  <div class="page-header">
    <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  <br />
  <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/" method="post" onSubmit="return valida(this, regras);" target="_parent">	  
    <?php echo $idioma['data_prevista_conclusao']; ?> 
    <br />
    <input name="idsolicitacao" type="hidden" value="<?= $url[3]; ?>" /> 
    <input name="data_prevista_conclusao" type="text" id="data_prevista_conclusao" class="span2" style="height:30px;" />
    <br /><br />
    <?php echo $idioma['campo_adicional_local']; ?> 
    <br />
    <input name="campo_adicional_local" type="text" id="campo_adicional_local" class="span4" style="height:30px;" />
    <br /><br />
    <?php echo $idioma['campo_adicional']; ?>  
    <br />
    <textarea id="campo_adicional" name="campo_adicional" style="width:500px; height:100px;"></textarea>
    <br /><br />
    <?php echo $idioma['campo_adicional']; ?> 2  
    <br />
    <input name="campo_adicional2" type="text" id="campo_adicional2" class="span4" style="height:30px;" />
    <br /><br />
    <?php echo $idioma['campo_adicional']; ?> 3 
    <br />
    <input name="campo_adicional3" type="text" id="campo_adicional3" class="span4" style="height:30px;" />
    <br /><br />
    <?php echo $idioma['campo_adicional']; ?> 4 
    <br />
    <input name="campo_adicional4" type="text" id="campo_adicional4" class="span4" style="height:30px;" />
    <br /><br />
    <input name="acao" type="hidden" value="gerar_declaracao" /> 
    <input class="btn" type="submit" name="salvar" id="salvar" value="<?php echo $idioma["btn_salvar"]; ?>" />  
  </form>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script src="/assets/plugins/ckeditor/sample.js"></script>

<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script type="text/javascript">
    $("#data_prevista_conclusao").mask("99/99/9999");
    $("#data_prevista_conclusao").datepicker($.datepicker.regional["pt-BR"]);
        jQuery(document).ready(function($) {    
            var editor = CKEDITOR.replace('campo_adicional', {
                    /*
                    * Ensure that htmlwriter plugin, which is required for this sample, is loaded.
                    */
                    enterMode : CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    extraPlugins: 'htmlwriter',
                    height: 100,
                    width: 500,
                    toolbar: [
                        ["Bold","Italic","Underline","StrikeThrough","-",
                        "Outdent","Indent","NumberedList","BulletedList"],
                        ["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
                        ["Image","Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],
                    ],

                    /*
                    * Style sheet for the contents
                    */
                    
                    contentsCss: 'body {color:#000; background-color#FFF; font-family: Arial; font-size:80%;} p, ol, ul {margin: 0px;}',

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
        });
    </script>		
</body>
</html>
