<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<script>
  window.resizeTo(screen.width,screen.height);
  window.moveTo(0,0);
</script>
<head>
	<?php incluirLib("head",$config,$usu_professor); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?/*
	<link href="<?= $config["urlSistema"]; ?>/assets/aluno/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
	*/?>
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/normalize.css">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/main.css">
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/base.css">
	<?/*<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/icones.css">*/?>
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/interna.css">
	<?/*<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/vendor/modernizr-2.6.2.min.js"></script>*/?>
	
	<?/*<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/jquery.easing.1.3.js"></script>
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/plugins.js"></script>
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/respond.min.js"></script>
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/js/prefixfree.min.js"></script>
	<script src="<?= $config["urlSistema"]; ?>/assets/aluno/bootstrap/js/bootstrap.min.js"></script>*/?>
	<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
	<style type="text/css">
		.campo-textarea{
			width:80%;
			float:left;
			display: inline-block;
			vertical-align:middle;
			margin-bottom:10px;
		} 
		.campo-enviar{
			width:18%;
			height:80px;
			float:left;
			display: inline-block;
			vertical-align:bottom;
			text-align:center;
		} 
		.campo-file{
			width:100%;
			float:left;
			display: inline-block;
			vertical-align:middle;
		} 
		.chat-nome{
			font-size:14px;
			color:#68b80a;
		}
		.chat-nome > strong{
			color:#000;
		}
		.chat-data{
			font-size:8px!important;
			color:#CDCDCD;
		}
		.forum-listagem .chat-listagem{
			background-color:#FFF!important;
		}
		#ul_mensagens{
			background-color:#FFF!important;
			overflow-x:hidden;
			border-bottom:1px solid #68b80a!important;
		}

		#ul_mensagens > li{
			padding:6px;
			border-bottom:1px solid #68b80a!important;
		}

		h2.titulo_pagina{
			width:65%!important;
			color:#0F0!important;
		}
		.forum-listagem{
			margin:0px;
		}
		.forum-listagem-alunos{
			border-bottom:1px solid #68b80a!important;
			background-color: #FFF!important;
			vertical-align:middle!important;
		}
		.forum-lista-alunos-avatar{
			padding:4px;
			width:45px;
			height:45px;
			display: inline-block;
			vertical-align:middle;
		}
		.forum-lista-alunos-nome{
			padding:4px;
			width:80%;
			display: inline-block;
			vertical-align:middle;
		}
		body{
			background-color:#FFF;
		}
		.forum-base-pessoas{
			border:1px solid #68b80a;
		}
		.forum-base-div-mensagem{
			border:1px solid #68b80a;
		}
		.pull-right{
			float:right!important;
		}
		.corbgVerde-claro{
			line-height:32px;
			height:32px;
			font-size:14px;
			font-weight:bolder;
			color:#EEEEEE;
			padding:6px;
			background-color: #68b80a;
		}
		#mensagem{
			width:98%;
		}
		.lista-chat {
			background-color: #f2f2f2;
			height: 472px;
			overflow-y: scroll;
		}
		.chat-listagem {
			background-color: #f2f2f2;
			height: 260px;
			overflow-y: scroll;
		}
		.chat-participante {
			background-color: #f2f2f2;
			height: 330px;
			overflow-y: scroll;
		}
		.chat-titulo {
			margin-top: -44px;
			padding-left: 59px;
			top: 0px;
			width: 100% !important;
		}
		.chat-tx-apoio {
			font-size: 10px !important;
			/*margin-top: -7px;*/
			text-transform: uppercase;
		}
		.chat-tx-vermelho {
			color: #F30;
		}
		.chat-tx-verde {
			color: #093 !important;
		}
		.chat-avatar-inverso {
			float: right;
		}
		.chat-listagem-inverso {
			padding-left: 0px;
			padding-right: 59px;
		}
		.chat-input {
			appearance: textfield;
			border: none !important;
			color: #FFF !important;
			float: left !important;
			height: 50px !important;
			max-width: none !important;
			padding-left: 10px !important;
			width: 80% !important;
		}
		.chat-bt {
			float: right;
			height: 50px !important;
		}
		.marge20-up {
		margin-top: 20px;
		}
		.chat-input::-webkit-input-placeholder {
			color: #FFF;
		}
		.chat-input::-moz-placeholder {
			color: #FFF;
		}
		.input-verde {
			border: medium none;
			color: #FFFFFF;
			height: 40px;
			text-align: center;
			font-size: 14px;
		}
		#pickfiles{
			margin-left:25px;
			margin-bottom:10px;
		}
		.chat-texarea {
			border: c2c2c2 !important;
			color: #000 !important;
			float: left !important;
			min-height:80px!important;
			max-width: none !important;
			padding: 6px !important;
			margin:10px;
			width: 70%;
			font-size: 14px !important;
		}
		.chat-texarea:focus {
			border:1px solid #68b80a;
		}
		.mensagem_nova {
			font-weight:bold;
		}
		.conversa_ativa {
			background-color:#D9DAD9;
		}
		.forum-listagem-titulo p {
			font-size:14px !important;
			font-family: 'pt_sansregular',Tahoma;
		}
		.coluna-dados{
			padding:10px;
		}
		.principal-texto{
			min-height:550px!important;
			height:550px!important;
		}
	</style>


	



	<script type="text/javascript">
		<?php if ($_POST["msg"]) { ?>
			alert("<?php echo $idioma[$_POST["msg"]]; ?>");
		<?php } ?>
	</script>
</head>
<body>
	<div class="content">
		<?php //incluirLib("curso_topo",$config,$informacoes); ?>
		<div class="conteudo">
			<?php //incluirLib("curso_menu_lateral",$config,$usuario); ?>
			<div class="coluna-dados" id="coluna-conteudo">
				<div class="area area-conteudo" >
					<div class="principal-area">
						<div class="row-fluid">
								<div class="span8">
								<h2><?= $idioma["titulo"]; ?></h2>
								</div>
								<div class="span2" style="text-align:left;">
								<?php if($mensagemInstantanea["sinalizador_professor"] == "S") { ?>
									<a class="btn btn-small btn-danger" style="color:#FFF;margin-left:72%;margin-bottom:10px;" href="<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/desativarsinalizador" ?>">DESATIVAR SINALIZADOR</a>
								<? } else { ?>
									<a class="btn btn-small btn-success" style="color:#FFF;margin-left:73%;margin-bottom:10px;" href="<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/ativarsinalizador" ?>">ATIVAR SINALIZADOR</a>
								<? } ?>
								<!-- <span style="font-weight: 100;background: #CF1826; color: #fff; padding: 3px 14px; top: 15px; border-radius: 10px 0; font-size: 16px; position: absolute; right: 22px;">
								<?= $idioma["ambiente"]; ?>
								</span> -->
								</div>
						</div>
						<div class="principal-titulo">
							<div class="titulo-pagina">
								
							</div>
						</div> <!-- principal - titulo  -->
						<div class="principal-texto" style="overflow-y:hidden;padding-right: 10px;">
							<div class="row-fluid"> 
								<div class="span4"> <!-- coluna 1 -->
									<div class="forum-base-pessoas">
										<div class="forum-base-titulo corbgVerde-claro">
											<?= $idioma["conversas"]; ?>
											<input class="btn btn-small btn-default pull-right" type="button" value="<?= $idioma["nova_mensagem"]; ?>" onclick="window.open('<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/nova_mensagem" ?>', '<?= md5(rand(1, 999) * rand(1, 999)); ?>', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=530');" />
										</div>
										<ul style="overflow-x: hidden; width: 100%;" class="forum-listagem chat-participante">
											<?php 
											if (is_array($mensagensIntegrantes)) {
												foreach ($mensagensIntegrantes as $ind => $var) {
													$integrantes = array();
													foreach ($var["integrantes"] as $indInteg => $varInteg) {
														
														if ($varInteg["ativo_usuario"] == 'S') {
															$integrantes[] = $varInteg["nome_usuario"]." (".$varInteg["tipo_usuario"].")";
														}else {
															$integrantes[] = $varInteg["nome_usuario"]." (".$varInteg["tipo_usuario"].") (REMOVIDO)";
														}
														
														
														//Irá exibir a imagem da pessoa se a conversa for apenas com uma pessoa
														if(count($var["integrantes"]) == 1){
															$pasta_servidor = $varInteg["pasta_servidor"];
															$imagem = $varInteg["avatar_servidor"];
														}else {
															$pasta_servidor = "";
															$imagem = "";
														}
													}

													$classe = "";
													//Indica se é a conversa que a pessoa está vendo
													if ($var["idmensagem_instantanea"] == $url[5]) {
														$classe = "conversa_ativa";
													}

													//Indica se tem mensagens novas na conversa
													if ($var["qnt_conversas_nao_lidas"] > 0) {
														$classe .= "mensagem_nova";
													}
											?>
													<li class="<?= $classe; ?> forum-listagem-alunos">
														<div class="forum-lista-alunos-avatar">
															<img src="/api/get/imagens/<?= $pasta_servidor; ?>/45/45/<?= $imagem; ?>" width="45" height="45">
														</div>
														<div class="forum-lista-alunos-nome">
															<a class="chat-tx-verde" href="<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$var["idmensagem_instantanea"]; ?>" title="<?= implode(", ", $integrantes); ?>"><?= cortar(implode(", ", $integrantes), 39); ?></a>
														</div>
													</li>
											<?php 
												}
											}
											?>
										</ul>
									</div>
								</div>
								<div class="forum-base-div-mensagem span7"><!-- fim da coluna 2 -->
									<div class="forum-base-titulo corbgVerde-claro">
										<?= $idioma["bate_papo"]; ?>
										<?php 
										if ($url[5]) { 
										?>
											<input class="btn btn-small btn-default pull-right" style="margin-right:0px;" type="button" value="<?= $idioma["adicionar_pessoa"]; ?>" onclick="window.open('<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/adicionar_pessoa" ?>', '<?= md5(rand(1, 999) * rand(1, 999)); ?>', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=380');" style="margin-right:180px;" />
											<?php /* ?><form method="post" enctype="multipart/form-data" onsubmit="return confirm('<?= $idioma["confirmar_sair_conversa"]; ?>');">
												<input name="acao" type="hidden" value="sair_conversa" />
												<input class="corbgVerde-claro btfade bts-forum-criar input-verde" type="submit" value="<?= $idioma["sair_conversa"]; ?>" />
											</form><?php */ ?>
										<?php 
										}
										?>
									</div>
									<ul class="forum-listagem chat-listagem" id="ul_mensagens">                                    
										<?php 
										if (count($conversasMensagem) > 0) { 
											foreach ($conversasMensagem as $ind => $var) {
												if ($var["ativo_usuario"] == 'N') {
													$var["nome_usuario"] = "<strike title=\"".$idioma["usuario_removido"]."\">".$var["nome_usuario"]."</strike>";
												}
										?>
												<li data-idmessage="<?= $var["idmensagem_instantanea_conversa"]; ?>">
													<div class="forum-listagem-avatar">
														<img src="/api/get/imagens/<?= $var["pasta_servidor"]; ?>/45/45/<?= $var["avatar_servidor"]; ?>" width="45" height="45">
													</div>
													<div class="forum-listagem-titulo chat-titulo ">
														<p class="chat-nome"><?= $var["nome_usuario"].' <strong>'.$var["tipo_usuario"].'</strong> '.$idioma["diz"]; ?></p>
														<p class="chat-data"><?php echo $var["data_cad"]; ?></p>
														<p><?= nl2br($var["mensagem"]); ?></p>
														<?php if($var["arquivo_servidor"]) { ?>
															<p style="margin-top:10px;width:90%;">
															Arquivo:
															<a style="color: #0088cc;" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $var["idmensagem_instantanea_conversa"]; ?>/download/<?php echo $var["idmensagem_instantanea_conversa"]; ?>"><?php echo $var["arquivo_nome"]; ?></a>
															</p>
														<?php } ?>
													</div>
												</li>
										<?php 
											}
										}
										?>
									</ul>
									<?php
									if(intval($url[5])) {
									?>
										<div id="container" class="forum-form marge20-up" style="width:100%;">
											<form enctype="multipart/form-data" name ="form_mensagem" id="form_mensagem" method="post" action"">
											
											<div class="campo-textarea">
											<textarea id="mensagem" name="mensagem" class="chat-texarea corbgpadrao" placeholder="<?php echo $idioma["escreva"]; ?>"></textarea>
											</div>
											<div class="campo-enviar">
											<input class="btn btn-small btn-default" type="button" id="enviarMensagem" style="margin-top:40px;" value="<?php echo $idioma["enviar"]; ?>" />
											</div>
											<div class="campo-file">
											<a style="clear: both; padding:0px;float: left;margin-left: -15px;"><input id="pickfiles" type="button" value="Selecionar arquivo"><span style="font-size:14px !important;">   Arquivos maiores que 2Mb não serão adicionados.</span></a>                       
											</div>
											
										</form>
										</div>
										<div style="clear: both;float: left;margin-top:-25px;" id="filelist" ></div>
									<?php
									}
									?>
								</div><!-- fim da coluna 2 --> 
							</div>
						</div> <!-- principal area -->
					</div>
				</div> <!-- area-conteudo -->   
			</div><!-- coluna dados -->
		</div>
	</div>
	<script type="text/javascript" src="/assets/aluno/js/vendor/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/assets/aluno/js/main.js"></script>
	<script src="/assets/js/validation.js"></script>
	<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.gears.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.silverlight.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.flash.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.browserplus.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.html4.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.html5.js"></script>
	<script type="text/javascript" src="/assets/plugins/uploadify/js/i18n/pt-br.js"></script>
	<script type="text/javascript" src="/assets/plugins/ckeditor/sample.js"></script>
  	<script type="text/javascript" src="/assets/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
					
				var editor = CKEDITOR.replace( 'mensagem', {

				extraPlugins: 'htmlwriter',
				
				height: 100,
				width: 'auto',
				toolbar: [
					["Bold","Italic","Underline","StrikeThrough","-",
					"Outdent","Indent","NumberedList","BulletedList"],
					["-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","PasteFromWord"],
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
	</script>
	<script type="text/javascript">
		var ul_mensagens = document.getElementById('ul_mensagens');
		ul_mensagens.scrollTop = ul_mensagens.scrollHeight;
	</script>
	<script type="text/javascript">
		var idmensagem_instantanea = '<?= ($url[5]); ?>';
		var temArquivo = 'N';
		jQuery(document).ready(function($) {
			<?php
			if (intval($url[5])) {
			?>
				  
			var w = 0;
			var wss = 0;
			var uploader = new plupload.Uploader({
			  runtimes : 'html5,gears,flash,silverlight,browserplus',
			  browse_button : 'pickfiles',
			  container: 'container',
			  max_file_size : '2mb',
			  max_file_count: 1, 
			  multi_selection: false,
			  url : '<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/enviar_arquivo_instantanea" ?>',
			  flash_swf_url : '/assets/plugins/uploadify/js/plupload.flash.swf',
			  silverlight_xap_url : '/assets/plugins/uploadify/js/plupload.silverlight.xap',
			  filters : [
			      {title : "Arquivos", extensions : "jpg,gif,png,jpeg,bmp,pdf,doc,docx"},
			  ]
			});

			uploader.bind('Init', function(up, params) {                   
			   document.getElementById('filelist').innerHTML = "<div></div>";
			});
			//max = 1;
			uploader.bind('FilesAdded', function(up, files) {
				$("#filelist").html('');
				temArquivo = 'S';
			  	for (var i in files) {
				     	document.getElementById('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
					 	w = files[i].id;
			  	}	
			  	var fileCount = up.files.length,
			    i = 0,
			    ids = $.map(up.files, function (item) { return item.id; });

			    for (i = 0; i < fileCount; i++) {
			        uploader.removeFile(uploader.getFile(ids[i]));
			    }

			});

			uploader.bind('Error', function(up, err) {
				//uploader.removeFile(uploader.getFile(err.file.id));
				temArquivo = 'N';
			  	document.getElementById('filelist').innerHTML = "\nErro: " + err.message;
			});

			/*uploader.bind('UploadProgress', function(up, file) {
			    $(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";	
			});*/
			uploader.init();
             
            $('#enviarMensagem').click(function() {
				var mensagemEditor = editor.getData();
				if ( mensagemEditor == "" ) {
					alert('<?= $idioma["mensagem_vazio"]; ?>');
				} else {
					$.post('<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/enviar_mensagem_instantanea" ?>',{
						acao: 'enviar_mensagem_instantanea',
						mensagem: mensagemEditor,
						temArquivo: temArquivo,
						idmensagem_instantanea: idmensagem_instantanea
					}, function (data) {
						var newMessages = JSON.parse(data);
			            if (newMessages["erro"]){
			            	alert(newMessages["erros"]);
			            } else if (newMessages["erro_json"]){
			            	alert('<?= $idioma["erro_json"]; ?>');
							editor.setData("");
			            } else {
			            	editor.setData("");
			            	if(w){	
			            		uploader.start();
			            		uploader.splice();
								$("#filelist").html(''); // I make this because i changed the updateList funcion.
								document.getElementById("form_mensagem").reset();
			            	}
			            }
			        });
			    }
			}); 
			
				function atualizaConversas() {
					$.post('<?= "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/atualizarChat" ?>',{
			            acao: 'atualizaConversas',
			            idmensagem_instantanea: idmensagem_instantanea,
			            ultimaIdMensagem: $('#ul_mensagens > li:last-child').attr('data-idmessage')
			        }, function (data) {
			            var newMessages = JSON.parse(data);
			            console.log('Atualizando Chat...');

			            for (var message in newMessages) {
			            	var htmlArquivo;
			            	if (newMessages[message].arquivo_servidor != null && newMessages[message].arquivo_servidor != '') {
			            		htmlArquivo = '<p style="margin-top:10px;">Arquivo: <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/'+newMessages[message].idmensagem_instantanea_conversa+'/download/'+newMessages[message].idmensagem_instantanea_conversa+'">'+newMessages[message].arquivo_nome+'</a></p>';
			            	} else {
			            		htmlArquivo = '';
			            	}

			                $("#ul_mensagens").append('<li  data-idmessage="'+ newMessages[message].idmensagem_instantanea_conversa +'">' +
										                	'<div class="forum-listagem-avatar">' +
										                		'<img src="/api/get/imagens/' + newMessages[message].pasta_servidor + '/45/45/' + newMessages[message].avatar_servidor + '" width="45" height="45">' +
									                		'</div>' +
										                	'<div class="forum-listagem-titulo chat-titulo">' +
											                	'<p>' + newMessages[message].data_cad + '</p>' +
											                	'<h2>' + newMessages[message].nome_usuario + ' diz:</h2>' +
											                	'<div class="chat-tx-apoio chat-tx-vermelho"><strong>' + newMessages[message].tipo_usuario + '</strong></div>' +
											                	'<p>' + newMessages[message].mensagem.replace(/\n/g, "<br>" ) + '</p>'
										                	+ htmlArquivo + '</div>' + 
									                	'</li>');


			                var ul_mensagens = document.getElementById('ul_mensagens');
			                ul_mensagens.scrollTop = ul_mensagens.scrollHeight;
			            }
			        });
				}
				setInterval(function(){atualizaConversas()}, 5000);
			<?php
			}
			?>
			jQuery('.coluna-dados').css('left', "0px");
		});
	</script>
</body>
</html>