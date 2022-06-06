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
    		<li><?php echo $linha["nome"]; ?> <span class="divider">/</span></li>
            <li class="active"><?php echo $idioma["gerar"]; ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid">
  		<div class="span12">
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
    						<form class="well" method="post" <?php/*target="_blank"*/?> >
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
								
								<div class="control-group">
									<label class="control-label" for="form_tipo_data_filtro"><?php echo $idioma["form_tipo_periodo"]; ?></label>
									<div class="controls">
										<select name="tipo_data" id="form_tipo_data_filtro" class="span3" onchange="verificaData(this)" >
											<option value="PER" >Periodo definido pelo usuário</option>
											<option value="HOJ" >Hoje</option>
											<option value="SET" >Últimos 7 dias</option>
											<option value="MAT" >Mês atual</option>
											<option value="MPR" >Próximo mês</option>
											<option value="MAN" >Mês anterior</option>											
										</select>
									</div>
								</div>
								
								<div class="control-group" style="float:left; padding-right:25px;" id="div_de">
									<label class="control-label" for="periodo_inicio"><?php echo $idioma["form_de"]; ?></label>
									<div class="controls"><input class="span2" id="periodo_inicio" name="periodo_inicio" type="text" value=""  /></div>
								</div>
								<div class="control-group" id="div_ate">
									<label class="control-label" for="periodo_final"><?php echo $idioma["form_ate"]; ?></label>
									<div class="controls"><input class="span2" id="periodo_final" name="periodo_final" type="text" value=""  /></div>
								</div>
                                <br />
								
								<div class="control-group" style="float:left; padding-right:25px;" id="div_de">
									<label class="control-label" for="linha_a_partir"><?php echo $idioma["form_linha_a_partir"]; ?></label>
									<div class="controls"><input class="span2 numerico" id="linha_a_partir" name="linha_a_partir" type="text" value=""  /></div>
								</div>
								<div class="control-group" id="div_ate">
									<label class="control-label" for="coluna_a_partir"><?php echo $idioma["form_coluna_a_partir"]; ?></label>
									<div class="controls"><input class="span2 numerico" id="coluna_a_partir" name="coluna_a_partir" type="text" value=""  /></div>
								</div>
                                <br />

								<div class="control-group" style="padding-right:25px;">
									<label class="control-label" for="pessoas"><?php echo $idioma["form_pessoas"]; ?></label>
									<div class="controls"><select id="pessoas" name="pessoas"></select></div>
								</div>
								<br />
								
								<div class="control-group" style="padding-right:25px;">
									<label class="control-label" for="matriculas"><?php echo $idioma["form_matriculas"]; ?></label>
									<div class="controls"><select id="matriculas" name="matriculas"></select></div>
								</div>
								<br />
								
    							<input type="hidden" id="acao" name="acao" value="gerar_etiquetas">
                                <input type="submit" class="btn" value="<?= $idioma["btn_gerar"]; ?>" />

    						</form>
                            <br />
						</div>
              		</div>
            	</div>                           
        	</div>
    	</div>
  	</div>
  	<? incluirLib("rodape",$config,$usuario); ?>
	<script src="/assets/plugins/portamento/portamento-min.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>	
	<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
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
			
		});
		
		function verificaData (obj) {
			if(obj.value == 'PER') {
				document.getElementById('div_de').style.display = 'block';
				document.getElementById('div_ate').style.display = 'block';
			} else {
				document.getElementById('div_de').style.display = 'none';
				document.getElementById('div_ate').style.display = 'none';
				document.getElementById('periodo_inicio').value = '';
				document.getElementById('periodo_final').value = '';
			}
		}
	  
	  $(document).ready(function(){                
		$("#pessoas").fcbkcomplete({
		  json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/pessoas",
		  addontab: true,
		  height: 10,
		  maxshownitems: 10,
		  input_min_size: 1,
		  cache: true,
		  maxitems: 30,
		  filter_selected: true,
		  firstselected: true,
		  complete_text: "<?= $idioma["mensagem_select_pessoa"]; ?>",
		  addoncomma: true
		});
		$("#matriculas").fcbkcomplete({
		  json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/matriculas",
		  addontab: true,
		  height: 10,
		  maxshownitems: 10,
		  input_min_size: 1,
		  cache: true,
		  maxitems: 30,
		  filter_selected: true,
		  firstselected: true,
		  complete_text: "<?= $idioma["mensagem_select_matricula"]; ?>",
		  addoncomma: true
		});
	  });
	</script>
	
	<script type="text/javascript">	
		$(".numerico").keypress(isNumber);
		$(".numerico").blur(isNumberCopy);
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
				$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/curso"; ?>',{idoferta: $(this).val(), ajax: 'true'}, function(json){
					var a = json.curso.length;
					var options = '<option value="">– <?php echo $idioma["escolha_curso"]; ?> –</option>';
					for (var i = 0; i < a; i++) {
						options += '<option value="' + json.curso[i].idcurso + '" >' + json.curso[i].nome + '</option>';
					}
					$('#idcurso').html(options);
				});
				//$('#idcurso').html('<option value="">– <?php echo $idioma["escolha_oferta"]; ?> –</option>');
			}
		});
	</script>
</div>
</body>
</html>