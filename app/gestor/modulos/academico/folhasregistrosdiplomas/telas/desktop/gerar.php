<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
	<section id="global">
		<div class="page-header">
    		<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  		</div>
  		<ul class="breadcrumb">
      		<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
            <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
    		<?php/*<li><?php echo $linha["nome"]; ?> <span class="divider">/</span></li>*/?>
            <li class="active"><?php echo $idioma["gerar"]; ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid">
  		<div class="span9">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
            	<div class="tabbable tabs-left">
			 		<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              		<div class="tab-content">
                		<div class="tab-pane active" id="tab_editar">
                      		<h3 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h3>	
    						<div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
    						<?php if($_POST["msg"]) { ?>
                            	<div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                  	<strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                              	</div>
                          	<? } ?>
                          	<?php if(count($salvar["erros"]) > 0){ ?>
                              	<div class="alert alert-error fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                                    	<br />
                                        <?php echo $idioma[$val]; ?>
                                    <? } ?>
                                </div>
                          	<? } ?>   
    						<form action="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $url[3] ?>/gerar" class="well" method="post" onsubmit="return validateFields(this, regras)" >
                                <p><?= $idioma["form_oferta"]; ?></p>
                                <select id="idoferta" name="idoferta">
									<option value="" ><?php echo $idioma['escolha_oferta']; ?></option>
                                	<?php foreach($ofertasArray as $ind => $oferta) { ?>
                                    	<option value="<?php echo $oferta["idoferta"]; ?>"><?php echo $oferta["nome"]; ?></option>
                                    <?php } ?>
                                </select>
                                <br /><br />
								
								<p><?= $idioma["form_curso"]; ?></p>
                                <select id="idcurso" name="idcurso">
								  <option value=""> <?= $idioma["escolha_curso"]; ?> </option>
								  <? foreach ($cursosArray as $curso) { ?>
									  <option value="<?= $curso['idcurso']; ?>"><?= $curso['nome']; ?></option>
								  <? } ?>
								</select>
                                <br /><br />
								
								<p><?= $idioma["form_sindicato"]; ?></p>
                                <select id="idsindicato" name="idsindicato">
								  <option value=""> <?= $idioma["escolha_sindicato"]; ?> </option>
								  <? foreach ($sindicatosArray as $sindicato) { ?>
									  <option value="<?= $sindicato['idsindicato']; ?>"><?= $sindicato['nome_abreviado']; ?></option>
								  <? } ?>
								</select>
                                <br /><br />
								
								<div class="control-group">
									<label class="control-label" for="numero_ordem"><?php echo $idioma["form_numero_ordem"]; ?></label>
									<div class="controls"><input class="span2" id="numero_ordem" name="numero_ordem" type="text" value=""  /></div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="numero_registro"><?php echo $idioma["form_numero_registro"]; ?></label>
									<div class="controls"><input class="span2" id="numero_registro" name="numero_registro" type="text" value=""  /></div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="numero_relacao"><?php echo $idioma["form_numero_relacao"]; ?></label>
									<div class="controls"><input class="span2" id="numero_relacao" name="numero_relacao" type="text" value=""  /></div>
								</div>
								
								<div class="control-group" style="float:left; padding-right:25px; float:none;">
									<label class="control-label" for="data_expedicao"><?php echo $idioma["form_data_expedicao"]; ?></label>
									<div class="controls"><input class="span2" id="data_expedicao" name="data_expedicao" type="text" value=""  /></div>
								</div>
								
                                <input type="submit" class="btn" value="<?= $idioma["btn_gerar"]; ?>" />

    						</form>
                            <br />
						</div>
              		</div>
            	</div>                           
        	</div>
    	</div>
    	<div class="span3">
     		<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
    			<div class="well"><?= $idioma["nav_novousuario_explica"]; ?>
                    <br />
                    <br />
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/cadastrar" class="btn primary"><?= $idioma["nav_novousuario"]; ?></a>
    			</div>
        	<? } ?>
    		<?php  incluirLib("sidebar_".$url[1],$config); ?>    
    	</div>
  	</div>
  	<? incluirLib("rodape",$config,$usuario); ?>
	<script src="/assets/plugins/portamento/portamento-min.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>		
	<script src="/assets/js/ajax.js"></script>
	
    <script type="text/javascript">
      var regras = new Array();
	  regras.push("required,idoferta,<?= $idioma['oferta_obrigatoria']; ?>");
	  regras.push("required,idcurso,<?= $idioma['curso_obrigatorio']; ?>");
	  regras.push("required,idsindicato,<?= $idioma['sindicato_obrigatoria']; ?>");
	  regras.push("required,numero_ordem,<?= $idioma['numero_ordem_obrigatorio']; ?>");
	  regras.push("required,numero_registro,<?= $idioma['numero_registro_obrigatorio']; ?>");
	  regras.push("required,numero_relacao,<?= $idioma['numero_relacao_obrigatorio']; ?>");
	  regras.push("required,data_expedicao,<?= $idioma['data_expedicao_obrigatorio']; ?>");
	</script>
	
	<script>
		$(function() {
			$("#periodo_inicio").datepicker({
				currentText: 'Now',
				dateFormat: 'dd/mm/yy',
				dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
				dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
				monthNames: ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				alignment: 'bottomLeft',
				buttonImageOnly: true,
				buttonImage: '/assets/img/calendar.png',
				showStatus: true
			});
			$("#periodo_final").datepicker({
				currentText: 'Now',
				dateFormat: 'dd/mm/yy',
				dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
				dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
				monthNames: ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				alignment: 'bottomLeft',
				buttonImageOnly: true,
				buttonImage: '/assets/img/calendar.png',
				showStatus: true
			});
			$("#data_expedicao").datepicker({
				currentText: 'Now',
				dateFormat: 'dd/mm/yy',
				dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
				dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
				monthNames: ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				alignment: 'bottomLeft',
				buttonImageOnly: true,
				buttonImage: '/assets/img/calendar.png',
				showStatus: true
			});
			
		});
		
	</script>
	
	<script type="text/javascript">	
		$('#idoferta').change(function(){
			if($(this).val()){
				$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/curso"; ?>',{idoferta: $(this).val(), ajax: 'true'}, function(json){
					var a = json.curso.length;
					var options = '<option value="">– <?php echo $idioma["escolha_curso"]; ?> –</option>';
					for (var i = 0; i < a; i++) {
						options += '<option value="' + json.curso[i].idcurso + '" >' + json.curso[i].nome + '</option>';
					}
					$('#idcurso').html(options);
				});
			} else {
				$('#idcurso').html('<option value="">– <?php echo $idioma["escolha_oferta"]; ?> –</option>');
			}
		});
		$('#idcurso').change(function(){
			if($(this).val()){
				$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/sindicato"; ?>',{idcurso: $(this).val(), idoferta: $('#idoferta').val(), ajax: 'true'}, function(json){
					var a = json.sindicato.length;
					var options = '<option value="">– <?php echo $idioma["escolha_sindicato"]; ?> –</option>';
					for (var i = 0; i < a; i++) {
						options += '<option value="' + json.sindicato[i].idsindicato + '" >' + json.sindicato[i].nome + '</option>';
					}
					$('#idsindicato').html(options);
				});
			} else {
				$('#idsindicato').html('<option value="">– <?php echo $idioma["escolha_curso"]; ?> –</option>');
			}
		});
	</script>
</div>
</body>
</html>