<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
    <script type="text/javascript">
		window.opener.location.reload();
    </script>
</head>
<body>

<!-- Conteudo -->
<div class="divResizeOne">
    <div class="Resize-top-box box-azul Reset-ResizeOne">
        <h1><?php echo $idioma['participantes']; ?></h1>
        <i class="icon-group"></i>            
    </div>
	<?php
    $primeiraLinha = false;
    foreach ($integrantes as $integrante) { 
        if($integrante['ativo_usuario'] == 'N') $integrante['nome_usuario'] = '<strike>'.$integrante['nome_usuario'].'</strike>';
		?>
        <div class="row-fluid <?php if(!$primeiraLinha) { ?>Resize-align<?php } ?>">
            <div class="span12 extra-align box-white">
                <div class="contact-avatar l-align">
                    <img src="/api/get/imagens/<?php echo $integrante['pasta_servidor']; ?>/56/56/<?php echo $integrante['avatar_servidor']; ?>" alt="Avatar">
                </div>
                <div class="text-side-two details-resume">
                    <h1><?php echo $integrante['nome_usuario']; ?></h1>                                  
                    <p><i><?php echo $integrante['tipo_usuario']; ?></i></p>                                                                   
                </div>  
            </div>
        </div>
        <?php 
        $primeiraLinha = true;
    }
	?>
</div>
<div id="scrool" class="divResizeTwo box-cgray">
    <div class="Reset-ResizeTwo top-box box-gray">
        <h1><?php echo $idioma['mensagens']; ?></h1>
        <?php if(verificaPermissaoAcesso(false)) { ?>
	        <div class="r-align">
	            <a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6].'/adicionar'; ?>" class="abrirModal no-mobile"><div class="btn btn-verde btn-add"><?php echo $idioma['adicionar_pessoa']; ?></div></a>
	            <?php /* ?><a href="javascript:confirmaSair();"><i class="closed-x" style="margin-top: 10px; position: relative;"> <strong><?php echo $idioma['sair']; ?></strong></i></a>
	            <form id="formSair" name="formSair" method="post" >
	                <input name="acao" type="hidden" value="sair_conversa" />
	            </form><?php */ ?>
	        </div>
        <?php } ?>
    </div>
    <div class="conversation-box opened-chat" id="chat-container">                       
		<?php 
        foreach ($mensagens as $mensagem) { 
            $class1 = 'send-box';
            $class2 = 'l-align';
            $class3 = 'l-align';
            if($mensagem['tipo_usuario'] == 'ALUNO' && $mensagem['id_usuario'] == $usuario['idpessoa']) {
                $class1 = 'receive-box';
                $class2 = 'r-align';
                $class3 = 'r-text r-align';
            }
            ?>
            <div class="row-fluid m-box" data-idmessage="<?php echo $mensagem['idmensagem_instantanea_conversa']; ?>">
                <div class="span12 <?php echo $class1; ?>">
                    <div class="<?php echo $class2; ?>">
                        <div class="contact-avatar">
                            <img src="/api/get/imagens/<?php echo $mensagem['pasta_servidor']; ?>/56/56/<?php echo $mensagem['avatar_servidor']; ?>" alt="Avatar">
                            <h3><?php echo $mensagem['nome_usuario']; ?></h3>
                        </div>
                    </div>
                    <div class="box-chat <?php echo $class3; ?>">
                        <p><?php echo $mensagem['mensagem']; ?></p>
                        <?php if($mensagem['arquivo_nome']) { ?>
                            <h4><?php echo $idioma['arquivo']; ?> <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/download/<?php echo $mensagem['idmensagem_instantanea_conversa']; ?>"><?php echo $mensagem['arquivo_nome']; ?></a></h4>
                        <?php } ?>
                        <h4><?php echo $mensagem['data_cad']; ?></h4>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div id="container" class="talk-box">
    <form id="form_mensagem" name="form_mensagem" method="post" class="no-margin" enctype="multipart/form-data">
        <textarea name="mensagem" id="mensagem" placeholder="<?php echo $idioma['digite_mensagem']; ?>"></textarea>
        <a style="clear:both;float:left;"><input id="pickfiles" type="button" value="Selecionar arquivo"><span style="font-size:14px !important;">   Arquivos maiores que 2Mb não serão adicionados.</span></a>                       
        <div id="filelist" style="clear:both;float:left;"></div>
        <input type="button" id="enviarMensagem" name="enviarMensagem" class="btn btn-azul btn-large btn-chat no-margin center-align" value="<?php echo $idioma['enviar']; ?>" />
    </form>
</div>
<!-- /Conteudo -->
<?php //incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/aluno_novo/js/jquery-1.10.2.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-1.9.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/aluno_novo/js/jquery.cycle2.min.js"></script>
<script src="/assets/aluno_novo/bootstrap/js/bootstrap.min.js"></script>

<script src="/assets/js/validation.js"></script>
<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>

<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.gears.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.flash.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.html4.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/plupload.html5.js"></script>
<script type="text/javascript" src="/assets/plugins/uploadify/js/i18n/pt-br.js"></script>

<script type="text/javascript">
	<?php /* ?>function confirmaSair() {
		if(confirm('<?php echo $idioma['confirmar_sair_conversa']; ?>')) {
			document.getElementById('formSair').submit();
		}
	}<?php */ ?>
	
	jQuery(document).ready(function($) {
		var idmensagem_instantanea = '<?php echo $url[6]; ?>';
		var temArquivo = 'N';

		var chatContainer = document.getElementById('scrool');
		chatContainer.scrollTop = chatContainer.scrollHeight;
		
		var w = 0;
		var wss = 0;
		var uploader = new plupload.Uploader({
			runtimes : 'html5,gears,flash,silverlight,browserplus',
			browse_button : 'pickfiles',
			container: 'container',
			max_file_size : '2mb',
			max_file_count: 1, 
			multi_selection: false,
			url : '<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6]."/enviar_arquivo_instantanea" ?>',
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
			if (!document.getElementById("mensagem").value) {
				alert('<?php echo $idioma['mensagem_vazio']; ?>');
			} else {
				$.post('<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6]; ?>', {
					acao: 'enviar_mensagem_instantanea',
					mensagem: $('#mensagem').val(),
					temArquivo: temArquivo,
					idmensagem_instantanea: idmensagem_instantanea
				}, function (data) {
					var newMessages = JSON.parse(data);
					if (newMessages["erro"]){
						alert(newMessages["erros"]);
					} else if (newMessages["erro_json"]){
						alert('<?php echo $idioma['erro_json']; ?>');
						document.getElementById("mensagem").value = "";
					} else {
						document.getElementById("mensagem").value = "";
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
			
		setInterval(function(){
			$.post('<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/'.$url[6]; ?>', {
				acao: 'atualizaConversas',
				idmensagem_instantanea: idmensagem_instantanea,
				ultimaIdMensagem: $('#chat-container > div:last-child').attr('data-idmessage')
			}, function (data) {
				var newMessages = JSON.parse(data);
				console.log('Atualizando Chat...');

				for (var message in newMessages) {
					var htmlArquivo;
					if (newMessages[message].arquivo_servidor != null && newMessages[message].arquivo_servidor != '') {
						htmlArquivo = '<h4><?php echo $idioma['arquivo']; ?> <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $url[6]; ?>/download/'+newMessages[message].idmensagem_instantanea_conversa+'">'+newMessages[message].arquivo_nome+'</a></h4>';
					} else {
						htmlArquivo = '';
					}

					var class1 = 'send-box';
					var class2 = 'l-align';
					var class3 = 'l-align';
					if(newMessages[message].tipo_usuario == 'ALUNO' && newMessages[message].id_usuario == <?php echo $usuario['idpessoa']; ?>) {
						class1 = 'receive-box';
						class2 = 'r-align';
						class3 = 'r-text r-align';
					}
                    //alert(newMessages[message].mensagem);
                    //alert(newMessages[message].mensagem.replace(/\n/g, "<br>" ));
                    //alert(newMessages[message].mensagem.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, "<br>" ));
					
					$("#chat-container").append('<div class="row-fluid m-box" data-idmessage="'+ newMessages[message].idmensagem_instantanea_conversa +'"><div class="span12 '+ class1 +'"><div class="'+ class2 +'"><div class="contact-avatar"><img src="/api/get/imagens/'+ newMessages[message].pasta_servidor +'/56/56/' + newMessages[message].avatar_servidor + '" alt="Avatar"><h3>' + newMessages[message].nome_usuario + '</h3></div></div><div class="box-chat '+ class3 +'"><p>' + newMessages[message].mensagem.replace(/\n/g, "<br>" ) + '</p>'+ htmlArquivo +'<h4>' + newMessages[message].data_cad + '</h4></div></div></div>');
				}
				var chatContainer = document.getElementById('scrool');
				chatContainer.scrollTop = chatContainer.scrollHeight;
			});
		}, 5000);
		
		$('.abrirModal').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			if (url.indexOf('#') == 0) {
				$(url).modal('open').on('shown', function () { }).on("hidden", function () { $(this).remove(); });
			} else {
				$.get(url, function(data) {
					$('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+data+'</div>').modal().on('shown', function () { }).on("hidden", function () { $(this).remove(); });
				}).success(function() { 
					$('input:text:visible:first').focus();
				});
			}
		});
	});
</script>
</body>
</html>