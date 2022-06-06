<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <!-- Box -->
        <div class="box-side box-bg">
            <div class="top-box box-amarelo">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-file-text-alt"></i>            
            </div>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
				<div class="row-fluid box-item">
					<div class="span12">                
						<div class="abox extra-align">
							<?php if(verificaPermissaoAcesso(false)) { ?>
								<div class="row-fluid">
									<div class="span12">
										<div class="r-align">
											<a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$url[4].'/meusprofessores'; ?>"><div class="btn btn-verde">+ <?php echo $idioma['nova_mensagem']; ?></div></a>
										</div>                        
									</div>
								</div>
								<br />
							<?php } ?>
							<?php /*if(verificaPermissaoAcesso(false)) { ?>
								<div class="row-fluid">
									<div class="span12">
										<div class="r-align">
											<a href="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$url[3].'/'.$url[4].'/'.$url[5].'/cadastrar'; ?>" class="abrirModal"><div class="btn btn-verde">+ <?php echo $idioma['nova_mensagem']; ?></div></a>
										</div>                        
									</div>
								</div>
								<br />
							<?php }*/ ?>
							<?php 							
							$contadorMensagens = 0;
							foreach($mensagens as $mensagem) { 
								$nomesIntegrantes = array();
								foreach($mensagem['integrantes'] as $integrante) {  
									if($integrante['ativo_usuario'] == 'N') $integrante['nome_usuario'] = '<strike>'.$integrante['nome_usuario'].'</strike>';
									$nomesIntegrantes[] = $integrante['nome_usuario'];
								}
								$nomesIntegrantes = implode(', ', $nomesIntegrantes);
								
								$contadorMensagens++;
								if($contadorMensagens == 1) {
								?>
                                    <!-- Linha -->
                                    <div class="row-fluid">
								<?php } ?>                            
								<div class="span4 download-box">
									<div class="title-download">
										<h1><?php echo $idioma['mensagens_de']; ?> <i><?php echo formataData($mensagem['data_cad'],'br',1); ?></i></h1>
									</div>
									<div class="container-download no-border">
										<div class="archive-donwload">
											<p><?php echo $idioma['mensagens_com']; ?> <i><?php echo $nomesIntegrantes; ?></i></p>
										</div>
										<a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $matricula['idmatricula']; ?>/<?php echo $url[4]; ?>/<?php echo $url[5]; ?>/<?php echo $mensagem['idmensagem_instantanea']; ?>" target="_blank"><div class="btn btn-azul btn-small btn-download"><?php echo $idioma['abrir']; ?></div></a>
									</div>  
								</div>
                                <?php 
								if($contadorMensagens == 3) { 
									$contadorMensagens = 0; 
									?>
                                    </div> 
                                    <!-- /Linha -->
								<?php } ?> 
							<?php } ?> 
                            <?php 
							if($contadorMensagens > 0) {
							?>                                
                                </div>                                            
                                <!-- /Linha -->    
							<?php } ?>             
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/validation.js"></script>
<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
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