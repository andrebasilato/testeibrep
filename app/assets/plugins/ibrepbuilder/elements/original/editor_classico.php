<?php
require $_SERVER['DOCUMENT_ROOT'] . '/classes/avas.videos.class.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/core.class.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Editor Clássico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<link href="/assets/plugins/ibrepbuilder/elements/bundles/original_content.css" rel="stylesheet"></head>
<body>
    
    <div id="page" class="page">
    
    	<div class="item content" id="editor_classico">
    		
    		<div class="container">
    			<textarea class="xxlarge" id="form_conteudo" name="conteudo" style="height: 200px; visibility: hidden; display: none;">conteudo</textarea>
    		</div>
    	</div>
    </div>

<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<script src="/assets/plugins/ckeditor/config.js"></script>
<script src="/assets/js/ajax.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var editor = CKEDITOR.replace( 'conteudo', {
		extraPlugins: 'Video',
		height: 290,
		width: '100%',
		toolbar: [
					 ["Bold","Italic","Underline","StrikeThrough","-","Outdent","Indent","NumberedList","BulletedList"],
					  ["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
					  ["Table","-","Link","TextColor","BGColor","Format","Font","FontSize"],["Source"],["Image"],
          	        ["Video"]
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

		/** Cria um plugin para vídeos */
        CKEDITOR.plugins.add( 'Video',
        {
            init: function( editor )
            {
                editor.addCommand( 'videoDialog', new CKEDITOR.dialogCommand( 'videoDialog' ) );

                editor.ui.addButton( 'Video',
                {
                    label: 'Inserir um vídeo',
                    command: 'videoDialog',
                    icon: '/assets/plugins/ckeditor/plugins/icons.png?t=CAPD'
                });

                CKEDITOR.dialog.add( 'videoDialog', function( editor )
                {
                    return {
                        title : 'Escolha um vídeo',
                        minWidth : 400,
                        minHeight : 200,
                        contents :
                            [
                                {
                                    id : 'general',
                                  	label : 'Settings',
                                    elements :
                                        [
                                            {
                                                type : 'html',
                                                html : 'Escolha dentre um dos vídeos cadastrados para esse ava.'
                                            },
                                            {
                                                type : 'select',
                                                id : 'video',
                                                label : 'Selecione um vídeo',
                                                items :
                                                    [
                                                        <?php
                                                        $url = explode("/", $_SERVER['REQUEST_URI']);
                                                        $videos = new Videos(new Core, new Zend_Db_Select(new Zend_Db_Mysql()));
                                                        $videos->set('idava', $url[3]);
                                                        $videoList = '';

                                                        foreach ($videos->listarTodasVideo() as $video) {
                                                           $videoList .=  '["'.$video['titulo'].'", "'.$video['idvideo'].'"], ';
                                                        }

                                                        echo trim($videoList, ', ');
                                                    	?>
                                                    ],
                                                commit : function( data )
                                                {
                                                    data.video = this.getValue();
                                                }
                                            }
                                        ]
                                  	}
                             	],
                          	onOk : function( x )
                            {
                            	var dialog = this, data = {}, spanElement = editor.document.createElement('p');
                              	this.commitContent( data );
                              	spanElement.setHtml('[[video][' + parseInt(data.video) + ']]');
                              	editor.insertElement(spanElement);
                          	}
                      	};
                  	});
              	}
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