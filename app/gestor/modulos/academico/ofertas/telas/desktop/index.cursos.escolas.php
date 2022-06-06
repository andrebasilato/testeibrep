<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><?= ($linha["oferta"]) ? $linha["oferta"] : $linha["nome"]; ?></a> <span class="divider">/</span> </li>
        <li class="active"><?= $idioma["pagina_titulo_interno"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
		  <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?> <? /* <small>(<?= $linha["email"]; ?>)</small> */ ?></h2>
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
					<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                    
                    <div class="well">
						<h4>Preenchimento automático</h4>
                        <table class="table">
								<tr style="background-color:#f5f5f5;">
									<th width="15%">Escola</th>
                                    <th width="15%">Curso</th>
									<th width="10%">Currículo</th>									
									<th width="5%">Dias para o AVA</th>
									<th width="10%">Data inicial do AVA</th>
									<th width="10%">Data final do AVA</th>
                                    <th width="5%">Dias para contrato</th>
                                    <th width="5%">Dias para prova</th>
                                    <th width="5%">Ordem</th>
									<th width="1%">Ignorar</th>
                                    <th width="10%">Opções</th>
								</tr>								
								<tr>
                                	<td>
                                    	<select id="buscaEscola" name="buscaEscola" style="width: 135px;"> 
                                        	<option value="">Selecione a escola</option>
                                            <?php if(is_array($arrayBuscaEscolas)){ ?>
                                            <?php foreach($arrayBuscaEscolas as $ind2 => $busacaEscola){ ?>
                                            	<option value="<?php echo $busacaEscola["idescola"]; ?>"><?php echo $busacaEscola["nome_fantasia"]; ?></option>
											<?php } ?>
											<?php } ?>
                                        </select>
                                    </td>
                                	<td>
                                    	<select id="buscaCurso" name="buscaCurso" style="width: 130px;"> 
                                        	<option value="">Selecione o curso</option>
                                            <?php if(is_array($arrayBuscaCursos)){ ?>
                                            <?php foreach($arrayBuscaCursos as $ind2 => $buscaCurso){ ?>
                                            	<option value="<?php echo $buscaCurso["idcurso"]; ?>"><?php echo $buscaCurso["nome"]; ?></option>
                                            <?php } ?>
											<?php } ?>
                                        </select>
                                    </td>
                                	<td>
                                  		<select name="buscaCurriculo" id="buscaCurriculo" style="width: 140px;" >
                                        	<option value="">Selecione o curriculo</option>
                                            <?php foreach($arrayBuscaCurriculo as $ind3 => $buscaCurriculo){ ?>
                                            <option value="<?php echo $buscaCurriculo["idcurriculo"]; ?>"><?php echo $buscaCurriculo["nome"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                          <input class="span1 numerico" type="text" name="buscaAvancadaDiasAva" id="buscaAvancadaDiasAva" value="" style="width: 25px;"/>
                                    </td>
                                    <td>
                                         <input class="span2 data_limite_ava" type="text" name="buscaAvancadaDataLimiteAvaInicio" id="buscaAvancadaDataLimiteAvaInicio" value="" style="width: 65px;" />
                                    </td>
                                    <td>
                                          <input class="span2 data_limite_ava" type="text"  name="buscaAvancadaDataLimiteAvaFim" id="buscaAvancadaDataLimiteAvaFim" value="" style="width: 65px;" />
                                    </td>
                                    <td>
                                         <input class="span1 numerico" type="text"  name="buscaAvancadaDiasContrato" id="buscaAvancadaDiasContrato" value="" style="width: 30px;" />
                                    </td>
                                    <td>
                                         <input class="span1 numerico" type="text"  name="buscaAvancadaDiasProva" id="buscaAvancadaDiasProva" value="" style="width: 30px;" />
                                    </td>
                                    <td>
                                        <input class="span1 numerico" type="text"  name="buscaAvancadaOrdem" id="buscaAvancadaOrdem" value="" style="width: 30px;" />
                                    </td>
                                    <td>
                                        <input type="checkbox"  name="buscaAvancadaIgnorar" id="buscaAvancadaIgnorar" value="1"/>
                                    </td>
                                    <td><button onclick="executaBtnBusca();" class="btn btn" type="button">Preencher</button></td>
                                 </tr>							
							</table>
					</div>
                    
				  <label for="selecionaEscola"><b><?php echo $idioma["escola_cfcs_busca"]; ?></b></label><br>
				  <select id="selecionaEscola" name="selecionaEscola"> 
					<option value="">Selecione uma escola</option>
					<?php if(is_array($arrayBuscaEscolas)){ ?>
					<?php foreach($arrayBuscaEscolas as $ind2 => $busacaEscola){ ?>
						<option value="?idescola=<?php echo $busacaEscola["idescola"]; ?>" <? if($_GET['idescola'] == $busacaEscola["idescola"]) { echo ' selected'; } ?>><?php echo $busacaEscola["sindicato"]; ?> - <?php echo $busacaEscola["nome_fantasia"]; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
				  
				  <br><br><br>
				
                    
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
                
							
					<br />
					<form method="post" action='?idescola=<?= intval($_GET['idescola']); ?>'>
						<input type="hidden" name="acao" value="salvar_cursos_escolas" />
					
						<?php 
						foreach ($dadosArray as $idescola => $escola) { ?>
							<h4 class="tituloOpcao"><?php echo $escola["sindicato"]; ?> > <?php echo $escola["escola"]; ?> </h4>
							
							<table class="table">
								<tr style="background-color:#f5f5f5;">
									<th width="20%">Curso</th>
									<th width="20%">Currículo</th>
									<?php/*<th width="10%">Limite</th>*/?>
									<th width="5%">Dias para o AVA</th>
									<th width="15%">Data inicial do AVA</th>
									<th width="15%">Data final do AVA</th>
                                    <th width="10%">Dias para contrato</th>
                                    <th width="10%">Dias para prova</th>
                                    <th width="5%">Ordem</th>
									<th width="5%">Ignorar</th>
								</tr>
							
								<?php
								foreach ($escola['cursos'] as $idcurso => $curso) {
								?>
							
									<tr>
										<td>
											<?php echo $curso['curso_escola']['curso']; ?>										
										</td>
										<td>
											<select class="curriculos escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][idcurriculo]" id="teste">
												<option value=""></option>
												<?php foreach($curso['curso_escola']['curriculos'] as $curriculo) { ?>
													<option value="<?php echo $curriculo['idcurriculo']; ?>" <?php if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["idcurriculo"]){ if($curso['curso_escola']['idcurriculo'] == $curriculo['idcurriculo']) { ?> selected="selected" <?php } }else{ if($_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["idcurriculo"]  == $curriculo['idcurriculo']){ ?>  selected="selected" <?php } } ?> >
														<?php echo $curriculo['nome']; ?>
													</option>													
												<?php } ?>
											</select>
										</td>
										<?php /*<td>
											<input class="span1 numerico" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][limite]" value="<?php echo $curso['curso_escola']['limite']; ?>" />
										</td>*/ ?>
										<td>
											<input class="span1 numerico dias_para_ava escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][dias_ava]" value="<?php if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_ava"]){ echo $curso['curso_escola']['dias_para_ava'];}else{echo $_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_ava"];} ?>" />									
										</td>
										<td>
											<input class="span2 data_limite_ava data_inicio_ava escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][data_inicio_ava]" value="<?php if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["data_inicio_ava"]){echo formataData($curso['curso_escola']['data_inicio_ava'],'pt',0); }else{ echo formataData($_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["data_inicio_ava"],'pt',0);} ?>" />
										</td>
										<td>
											<input class="span2 data_limite_ava data_fim_ava escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][data_limite_ava]" value="<?php if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["data_limite_ava"]){ echo formataData($curso['curso_escola']['data_limite_ava'],'pt',0);}else{echo formataData($_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["data_limite_ava"],'pt',0); } ?>" />
										</td>
                                        <td>
											<input class="span1 numerico dias_contrato escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][dias_contrato]" value="<?php  if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_contrato"]){ echo $curso['curso_escola']['dias_para_contrato']; }else{echo $_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_contrato"]; } ?>" />									
										</td>
                                        <td>
                                            <input class="span1 numerico dias_para_prova escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][dias_para_prova]" value="<?php  if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_para_prova"]){ echo $curso['curso_escola']['dias_para_prova']; }else{echo $_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["dias_para_prova"]; } ?>" />                                  
                                        </td>
                                        <td>
                                            <input class="span1 numerico ordem escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="text" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][ordem]" value="<?php  if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["ordem"]){ echo $curso['curso_escola']['ordem']; }else{echo $_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["ordem"]; } ?>" />
                                        </td>
										<td>
											<input class="ignorar escola<?php echo $idescola; ?> curso<?php echo $curso['curso_escola']['idcurso']; ?>" type="checkbox" name="escolas[<?php echo $idescola; ?>][cursos][<?php echo $curso['curso_escola']['idcurso']; ?>][ignorar]" value="1" <?php if(!$_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["ignorar"]){ if($curso['curso_escola']['ignorar'] == 'S') { ?> checked="checked" <?php } }else{ if($_POST["escolas"][$idescola]["cursos"][$curso['curso_escola']['idcurso']]["ignorar"]){ ?> checked="checked" <?php } } ?>/>
										</td>
									</tr>
								
								<?php 
								} ?>
							
							</table>
						
						<?php 
						} ?>
					
						<input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>" />
					</form>				
				
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

    <script type="text/javascript">
		$(".data_limite_ava").datepicker($.datepicker.regional["pt-BR"]);
		$(".data_limite_ava").mask("99/99/9999");
		//$("#<? //= ''.$curso['idcurso']; ?>").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});		
		$(".numerico").keypress(isNumber);
		$(".numerico").blur(isNumberCopy);
		
		function executaBtnBusca(){
		 preencheCurriculo();
		 preencheDiaspAVA();
		 preencheDataInicioAva();
		 preencheDataLimiteAvaFim();
		 preencheDiasContrato();
         preencheDiasProva();
		 preencheIgnorar();
		}
		
		function preencheCurriculo(){		
		 var valor = $("#buscaCurriculo").val();
		 if(valor){
			var idescola = $("#buscaEscola").val();
			var idcurso = $("#buscaCurso").val();
			if(idescola && idcurso){
		 		$('.curriculos.curso'+idcurso+'.escola'+idescola+' option[value="' + valor + '"]').attr({ selected : "selected" });
			}else
			if(idcurso){
				$('.curriculos.curso'+idcurso+' option[value="' + valor + '"]').attr({ selected : "selected" });	
			}else
			if(idescola){
				$('.curriculos.escola'+idescola+' option[value="' + valor + '"]').attr({ selected : "selected" });	
			}else{
				$('.curriculos option[value="' + valor + '"]').attr({ selected : "selected" });	
			}
		 }
		}
		function preencheDiaspAVA (){
			 var valor = $("#buscaAvancadaDiasAva").val();
			if(valor){
				var idescola = $("#buscaEscola").val();
				var idcurso = $("#buscaCurso").val();
				
				if(idescola && idcurso){
					$(".dias_para_ava.curso"+idcurso+".escola"+idescola).val(valor);
				}else
				if(idcurso){
					$(".dias_para_ava.curso"+idcurso).val(valor);
				}else
				if(idescola){
					$(".dias_para_ava.escola"+idescola).val(valor);
				}else{
					$(".dias_para_ava").val(valor);
				}
			}
		}
		function preencheDataInicioAva(){
			var valor = $("#buscaAvancadaDataLimiteAvaInicio").val();
			if(valor){
				var idescola = $("#buscaEscola").val();
				var idcurso = $("#buscaCurso").val();
				
				if(idescola && idcurso){
					$(".data_inicio_ava.curso"+idcurso+".escola"+idescola).val(valor);
				}else
				if(idcurso){
					$(".data_inicio_ava.curso"+idcurso).val(valor);
				}else
				if(idescola){
					$(".data_inicio_ava.escola"+idescola).val(valor);
				}else{
					$(".data_inicio_ava").val(valor);
				}
			}
		}
		function preencheDataLimiteAvaFim(){
			var valor = $("#buscaAvancadaDataLimiteAvaFim").val();
			if(valor){
				var idescola = $("#buscaEscola").val();
				var idcurso = $("#buscaCurso").val();
				
				if(idescola && idcurso){
					$(".data_fim_ava.curso"+idcurso+".escola"+idescola).val(valor);
				}else
				if(idcurso){
					$(".data_fim_ava.curso"+idcurso).val(valor);
				}else
				if(idescola){
					$(".data_fim_ava.escola"+idescola).val(valor);
				}else{
					$(".data_fim_ava").val(valor);
				}
			}
		}
		function preencheDiasContrato(){
			var valor = $("#buscaAvancadaDiasContrato").val();
			if(valor){
				var idescola = $("#buscaEscola").val();
				var idcurso = $("#buscaCurso").val();
				
				if(idescola && idcurso){
					$(".dias_contrato.curso"+idcurso+".escola"+idescola).val(valor);
				}else
				if(idcurso){
					$(".dias_contrato.curso"+idcurso).val(valor);
				}else
				if(idescola){
					$(".dias_contrato.escola"+idescola).val(valor);
				}else{
					$(".dias_contrato").val(valor);
				}
			}
		}
        function preencheDiasProva(){
            var valor = $("#buscaAvancadaDiasProva").val();
            if(valor){
                var idescola = $("#buscaEscola").val();
                var idcurso = $("#buscaCurso").val();
                
                if(idescola && idcurso){
                    $(".dias_para_prova.curso"+idcurso+".escola"+idescola).val(valor);
                }else
                if(idcurso){
                    $(".dias_para_prova.curso"+idcurso).val(valor);
                }else
                if(idescola){
                    $(".dias_para_prova.escola"+idescola).val(valor);
                }else{
                    $(".dias_para_prova").val(valor);
                }
            }
        }
		function preencheIgnorar(){			
			var valor = $("#buscaAvancadaIgnorar").val();
			if(valor){
				var idescola = $("#buscaEscola").val();
				var idcurso = $("#buscaCurso").val();
				
				if(idescola && idcurso){
					if($("#buscaAvancadaIgnorar").is(":checked")){
						$(".ignorar.curso"+idcurso+".escola"+idescola).attr('checked', true);
					}else{
						$(".ignorar.curso"+idcurso+".escola"+idescola).attr('checked', false);
					}					
				}else
				if(idcurso){
					if($("#buscaAvancadaIgnorar").is(":checked")){
						$(".ignorar.curso"+idcurso).attr('checked', true);
					}else{
						$(".ignorar.curso"+idcurso).attr('checked', false);
					}					
				}else
				if(idescola){
					if($("#buscaAvancadaIgnorar").is(":checked")){
						$(".ignorar.escola"+idescola).attr('checked', true);
					}else{
						$(".ignorar.escola"+idescolao).attr('checked', false);
					}					
				}else{
					if($("#buscaAvancadaIgnorar").is(":checked")){
						$(".ignorar").attr('checked', true);
					}else{
						$(".ignorar").attr('checked', false);
					}
				}
			}
		}
		jQuery(document).ready(function($) {
			$('#buscaCurso').change(function(){	
				if($(this).val()){
				  $.getJSON('/<?=$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/ajax_curriculos";?>',{idescola: $(this).val(), ajax: 'true'}, function(json){
					  var options = '<option value="">– Selecione o curriculo –</option>';
					  for (var i = 0; i < json.length; i++) {
						  var selected = '';
						  options += '<option value="' + json[i].idcurriculo + '" '+ selected +'>' + json[i].nome + '</option>';
					  }	
					  $('#buscaCurriculo').html(options);
				  });
			  } else {
				  $('#buscaCurriculo').html('<option value="">– Selecione o curso –</option>');
			  }
			});
		});
		
		jQuery(function($) {
		$('#selecionaEscola').on('change', function() {
			var url = $(this).val();
			if (url) {
				window.location = url;
			}
			return false;
		});
});
		
	</script>
	
  </div>
</body>
</html>