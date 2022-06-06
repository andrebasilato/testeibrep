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
            <li class="active"><?php echo $idioma["iteracoes"]; ?></li>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid">
  		<div class="span12">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?>  <small>(<?= $linha["email"]; ?>)</small></h2>
            	<div class="tabbable tabs-left">
			 		<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              		<div class="tab-content">
                		<div class="tab-pane active" id="tab_editar">
                      		<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>	
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
                         
                         
							<form class="form-inline wellCinza" method="post">
								<input type="hidden" id="acao" name="acao" value="adicionar_iteracao">
								<table>
									<tr>
										<td><strong>Número:</strong></td>																    
										<td><strong>Data:</strong></td>
									</tr>
									<tr>
										<td><input type="text" name="numero" id="numero" class="span2" maxlength="2" /></td>
										<td><input type="text" name="data" id="data" maxlength="200" /></td>
									</tr>
									<tr>
										<td colspan="2">Descrição</td>
									</tr>
									<tr>
										<td colspan="2"><textarea name="descricao" style="width:350px; height:75px;" ></textarea></td>
									</tr>
								</table>
							    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
							</form>                            
                            
                          
     						<form method="post" id="remover_iteracao" name="remover_iteracao">
    							<input type="hidden" id="acao" name="acao" value="remover_iteracao">
                                <input type="hidden" id="remover" name="remover" value="">
    						</form>
                            <table class="table table-striped tabelaSemTamanho">
        						<thead>
                                    <tr>
                                      <th><?= $idioma["listagem_numero"]; ?></th>
                                      <th><?= $idioma["listagem_data"]; ?></th>
                                      <th><?= $idioma["listagem_descricao"]; ?></th>
                                      <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                    </tr>
        						</thead>
        						<tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><?php echo formataData($linha["data_cad"],'pt',0); ?></td>
                                                <td>
												<span style="display:block; width:500px; word-wrap:break-word;">
													1ª Visita
												</span>
												</td>
                                                <td>&nbsp;
                                                </td>
                                            </tr>                        
										<?php foreach($associacoesArray as $ind => $associacao) { ?>
                                            <tr>
                                                <td><?php echo $associacao["numero"]; ?></td>
                                                <td><?php echo formataData($associacao["data_visita"],'pt',0); ?></td>
                                                <td>
												<span style="display:block; width:500px; word-wrap:break-word;">
												<?php echo $associacao["descricao"]; ?>
												</span>
												</td>
                                                <td>
                                                  <?php if($perfil["permissoes"][$url[2]."|5"]) { ?>
                                                  <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $associacao["iditeracao"]; ?>)"><i class="icon-remove"></i></a>
                                                  <?php } else { ?>
                                                  <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
                                                  <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
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
	function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
			document.getElementById("remover").value = id;
			document.getElementById("remover_iteracao").submit();
		} 
	}
		
    $('#idtipo').change(function(){
	    var select = document.getElementById('idtipo');
		var mas = select.options[select.selectedIndex].getAttribute('alt');
		if (mas) {
			if(mas == '(99) 9999-9999') {
				$('#valor').focusout(function(){
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
			} else {
				$("#valor").mask(mas);
			}
		} else
			$("#valor").unmask();
	});
	
	$("#numero").keypress(isNumber);
	$("#numero").blur(isNumberCopy);
	$("#data").mask("99/99/9999");
	$("#data").datepicker($.datepicker.regional["pt-BR"]);
	</script>
</div>
</body>
</html>