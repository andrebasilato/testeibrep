<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
	<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Conteudo -->
<div class="content">
	<p class="texto-index"></p>
</div>
<div class="content">
	<div class="content">
		<div class="box-bg">
			<span class="top-box box-azul">
				<h1><?php echo $idioma['pagina_titulo']; ?></h1>
				<i class="icon-smile"></i>            
			</span>
			<h2 class="ball-icon">&bull;</h2> 
			<div class="clear"></div>
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<div class="span12 abox extra-align">
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
							<div class="row-fluid">
								<div class="span3">
									<div id="mensagem_retorno"><img class="img-standard" src="/api/get/imagens/pessoas_avatar/268/267/<?php echo $linha['avatar_servidor']; ?>" alt="Perfil"></div>
									<a class="btn btn-azul r-align" onclick="javascript:document.getElementById('avatar').click();"><?php echo $idioma['botao_mudar_foto']; ?></a>
									<form action="<?php echo '/'.$url[0].'/'.$url[1].'/'.$url[2].'/upload'; ?>" method="post" id="form_imagem" enctype="multipart/form-data">
										<input type="hidden" name="<?php echo $config['banco']['primaria']; ?>" id="<?php echo $config['banco']['primaria']; ?>" value="<?php echo$linha[$config['banco']['primaria']]; ?>" />
										<input type="file" id="avatar" name="avatar" style="display:none;" />			
									</form>
								</div>
								<div class="span9 f-margin">
									<form method="post" onsubmit="return validateFields(this, regras)">
										<input type="hidden" name="<?php echo $config['banco']['primaria']; ?>" id="<?php echo $config['banco']['primaria']; ?>" value="<?php echo $linha[$config['banco']['primaria']]; ?>" />
										<input name="acao" type="hidden" value="salvar" />
										<div class="row-fluid">
											<div class="span12">
												<label><?php echo $idioma['form_nome']; ?></label>
												<input type="text" disabled="disabled" class="span8" name="nome" id="nome" value="<?php echo $linha['nome']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span12">
												<label><?php echo $idioma['form_email']; ?></label>
												<input type="text" class="span8" name="email" id="email" value="<?php echo $linha['email']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<label><?php echo $idioma['form_telefone']; ?></label>
												<input type="text" class="span12" name="telefone" id="telefone" value="<?php echo $linha['telefone']; ?>">
											</div>
											<div class="span4">
												<label><?php echo $idioma['form_celular']; ?></label>
												<input type="text" class="span12" name="celular" id="celular" value="<?php echo $linha['celular']; ?>">
											</div>
										</div>
										<div class="row-fluid dotted-line">
											<div class="span12">
												<label><?php echo $idioma['form_cep']; ?></label>
												<input type="text" class="span4" name="cep" id="cep" value="<?php echo $linha['cep']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<label><?php echo $idioma['form_logradouro']; ?></label>
												<select name="idlogradouro" id="idlogradouro" class="span12">
													<option value=""><?php echo $idioma['selecione_logradouro']; ?></option>
													<?php foreach($logradouros as $logradouro) { ?>
														<option value="<?php echo $logradouro['idlogradouro']; ?>" <?php if($logradouro['idlogradouro'] == $linha['idlogradouro']) { ?>selected<?php } ?>><?php echo $logradouro['nome']; ?></option>
				                          			<?php } ?>
												</select>
											</div>
											<div class="span8">
												<label><?php echo $idioma['form_endereco']; ?></label>
												<input type="text" class="span12" name="endereco" id="endereco" value="<?php echo $linha['endereco']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<label><?php echo $idioma['form_bairro']; ?></label>
												<input type="text" class="span12" name="bairro" id="bairro" value="<?php echo $linha['bairro']; ?>">
											</div>
											<div class="span4">
												<label><?php echo $idioma['form_numero']; ?></label>
												<input type="text" class="span12" name="numero" id="numero" value="<?php echo $linha['numero']; ?>">
											</div>
											<div class="span4">
												<label><?php echo $idioma['form_complemento']; ?></label>
												<input type="text" class="span12" name="complemento" id="complemento" value="<?php echo $linha['complemento']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<label><?php echo $idioma['form_estado']; ?></label>
												<select name="idestado" id="idestado" class="span12" style="margin-bottom: 10px;">
													<option value=""><?php echo $idioma['selecione_estado']; ?></option>
													<?php foreach($estados as $estado) { ?>
														<option value="<?php echo $estado['idestado']; ?>" <?php if($estado['idestado'] == $linha['idestado']) { ?>selected<?php } ?>><?php echo $estado['nome']; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="span6">
												<label><?php echo $idioma['form_cidade']; ?></label>
												<select name="idcidade" id="idcidade" class="span12" style="margin-bottom: 10px;">
													<option value=""><?php echo $idioma['selecione_cidade']; ?></option>
												</select>
											</div>
										</div>
										<div class="row-fluid dotted-line">
											<div class="span4">
												<label><?php echo $idioma['form_nova_senha']; ?></label>
												<input type="password" class="span12" name="nova_senha" id="nova_senha" value="">
											</div>
											<div class="span4">
												<label><?php echo $idioma['form_numero_confirma_nova_senha']; ?></label>
												<input type="password" class="span12" name="confirma_nova_senha" id="confirma_nova_senha" value="">
											</div>
											<div class="span4">
												<label><?php echo $idioma['form_senha_atual']; ?></label>
												<input type="password" class="span12" name="senha" id="senha" value="">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span12"><?php echo $idioma['descricao_nova_senha']; ?></div>
										</div>
										<div class="row-fluid dotted-line">
											<div class="span12">
												<label><?php echo $idioma['form_facebook']; ?></label>
												<input type="text" class="span8" name="facebook" value="<?php echo $linha['facebook']; ?>">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span12">
												<label class="disponivel_interacao">
													<input type="checkbox" name="disponivel_interacao" id="disponivel_interacao" <?php if($linha['disponivel_interacao'] == 'S') echo 'checked="checked"'; ?>>
													<?php echo $idioma['descricao_disponivel_interacao']; ?>
												</label>
											</div>
										</div>
											<div class="row-fluid dotted-line">
												<div class="span12">
													<input type="submit" value="<?php echo $idioma['btn_atualizar']; ?>" class="btn btn-azul">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php incluirLib("rodape", $config, $usuario); ?>
<script type="text/javascript" src="/assets/js/validation.js"></script>
<script type="text/javascript" src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>	
<script type="text/javascript" src="/assets/js/jquery.form.js"></script> 
<script type="text/javascript">
	var regras = new Array();
	regras.push("required,nome,<?php echo $idioma['nome_vazio']; ?>");
	regras.push("required,email,<?php echo $idioma['email_vazio']; ?>");
	regras.push("valid_email,email,<?php echo $idioma['email_invalido']; ?>");
	regras.push("required,cep,<?php echo $idioma['cep_vazio']; ?>");
	// regras.push("required,idlogradouro,<?php echo $idioma['logradouro_vazio']; ?>");
	regras.push("required,endereco,<?php echo $idioma['endereco_vazio']; ?>");
	regras.push("required,bairro,<?php echo $idioma['bairro_vazio']; ?>");
	regras.push("required,numero,<?php echo $idioma['numero_vazio']; ?>");
	regras.push("required,idestado,<?php echo $idioma['estado_vazio']; ?>");
	regras.push("required,idcidade,<?php echo $idioma['cidade_vazio']; ?>");
	regras.push("length>=8,nova_senha,<?php echo $idioma['nova_senha_minimo']; ?>");
	regras.push("length<=30,nova_senha,<?php echo $idioma['nova_senha_maximo']; ?>");
	regras.push("same_as,nova_senha,confirma_nova_senha,<?php echo $idioma['nova_senha_confirmacao']; ?>");
	jQuery(document).ready(function($) { 
		$('#telefone').focusout(function(){
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

		$('#celular').focusout(function(){
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
		
		$("#cep").mask("99999-999");
		
		$('#idestado').change(function(){
			if($(this).val()){
				$.getJSON('/aluno/secretaria/perfil/ajax_cidades',{idestado: $(this).val(), ajax: 'true'}, function(json){
					var options = '<option value=""><?=$idioma['selecione_cidade']; ?></option>';
					for (var i = 0; i < json.length; i++) {
						options += '<option value="' + json[i].idcidade + '" >' + json[i].nome  + '</option>';
					}	
					$('#idcidade').html(options);
				});
			} else {
				$('#idcidade').html('<option value=""><?=$idioma["selecione_estado"]; ?></option>');
			}
		});						
		
		<?php if(intval($linha['idestado'])) { ?>
			$.getJSON('/aluno/secretaria/perfil/ajax_cidades/<?=intval($linha['idestado']);?>', function(json){
				var options = '<option value=""><?=$idioma["selecione_cidade"]; ?></option>';	
				for (var i = 0; i < json.length; i++) {
					var selected = '';
					if(json[i].idcidade == <?=intval($linha['idcidade']);?>)
						var selected = 'selected';
					options += '<option value="' + json[i].idcidade + '" '+ selected +'>' + json[i].nome + '</option>';
				}
				$('#idcidade').html(options);
			});	
		<?php } ?>
		
		$("input[name='cep']").blur(function() {
			buscarCEP($("input[name='cep']").val());
		});
		
		$('#avatar').change(function(){
            $('#mensagem_retorno').html('<img src="/assets/aluno/img/loader.gif" alt="Enviando..."/> Enviando...'); 
            $('#form_imagem').ajaxForm({ target:'#mensagem_retorno' }).submit(); 
        }); 
	});
	
	function buscarCEP(cep_informado){
		//exibeLoading();
		$.msg({ 
			autoUnblock : true,
			clickUnblock : false,
			klass : 'white-on-black',
			content: 'Processando solicitação.',
			afterBlock : function(){
				var self = this;
				jQuery.ajax({
					url: "/api/get/cep",
					dataType: "json", //Tipo de Retorno
					type: "POST",
					data: {cep: cep_informado},
					success: function(json){ //Se ocorrer tudo certo
						if(json.sucesso){
							$("select[name='idlogradouro']").val(json.idlogradouro);
							$("input[name='endereco']").val(json.endereco)
							$("input[name='bairro']").val(json.bairro)
							$("select[name='idestado']").val(json.idestado);
							$.getJSON('/aluno/secretaria/perfil/ajax_cidades',{idestado: json.idestado, ajax: 'true'}, function(jsonCidade){
								var options = '<option value="">– <?=$idioma['selecione_cidade']; ?> –</option>';
								for (var i = 0; i < jsonCidade.length; i++) {
									var selected = '';
									if(jsonCidade[i].idcidade == json.idcidade)
										var selected = 'selected';
									options += '<option value="' + jsonCidade[i].idcidade + '" '+ selected +'>' + jsonCidade[i].nome  + '</option>';
								}	
								$('#idcidade').html(options);
							});	
							self.unblock();				
						} else {
							alert('<?= $idioma["erro_cep"]; ?>');
							self.unblock();
						}				  
					} 	
				});	
			} 
		});
	}	  
</script>
</body>
</html>