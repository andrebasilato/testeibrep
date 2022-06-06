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
			<? if($url[4] == "editar") { ?>
      			<li class="active"><?php echo $linha["nome"]; ?></li>
      		<? } else { ?>
      			<li class="active"><?= $idioma["nav_formulario"]; ?></li>
      		<? } ?>
      		<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  		</ul>
  	</section>
  	<div class="row-fluid">
  		<div class="span9">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?> <? /* <small>(<?= $linha["email"]; ?>)</small> */ ?></h2>
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
    						<form class="well" method="post">
    							<p><?= $idioma["form_associar"]; ?></p>
                                <p><?= $idioma["form_pessoa"]; ?></p>
								<?php if($perfil["permissoes"][$url[2]."|8"]) { ?>    
                                <select id="pessoas" name="pessoas"></select>
                                <br />
                                <br />
    							<input type="hidden" id="acao" name="acao" value="associar_pessoa">
                                <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_adicionar"]; ?>" />
                                <a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/pessoas_em_bloco" rel="facebox"><?php echo $idioma['adicionar_em_bloco']; ?></a><br />
                                <?php } else { ?>
                                <select id="pessoas" name="pessoas" disabled="disabled"></select>
                                <br />
                                <br />
                                <a href="javascript:void(0);" rel="tooltip" data-original-title="<?= $idioma["btn_permissao_inserir"]; ?>" data-placement="right" class="btn disabled"><?= $idioma["btn_adicionar"]; ?></a>
                                <?php } ?>
    						</form>
                            <br />
     						<form method="post" id="remover_pessoa" name="remover_pessoa">
    							<input type="hidden" id="acao" name="acao" value="remover_pessoa">
                                <input type="hidden" id="remover" name="remover" value="">
    						</form>
                            <table class="table table-striped">
        						<thead>
                                    <tr>
                                      <th><?= $idioma["listagem_id"]; ?></th>
                                      <th><?= $idioma["listagem_pessoa"]; ?></th>
                                      <th><?= $idioma["listagem_email"]; ?></th>
                                      <th><?= $idioma["listagem_celular"]; ?></th>
                                      <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                    </tr>
        						</thead>
        						<tbody>
                                    <?php if(count($associacoesArray) > 0) { ?>
										<?php foreach($associacoesArray as $ind => $associacao) { ?>
                                            <tr>
                                                <td><?php echo $associacao["idpesquisa_pessoa"]; ?></td>
                                                <td><?php echo $associacao["nome"]; ?></td>
                                                <td><?php echo $associacao["email"]; ?></td>
                                                <td><?php echo $associacao["celular"]; ?></td>
                                                <td>
                                                  <?php if($perfil["permissoes"][$url[2]."|9"]) { ?>
                                                  <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $associacao["idpesquisa_pessoa"]; ?>)"><i class="icon-remove"></i></a>
                                                  <?php } else { ?>
                                                  <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
                                                  <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
									<?php } else { ?>
                                        <tr>
                                            <td colspan="5"><?= $idioma["sem_informacao"]; ?></td>
                                        </tr>
									<?php } ?>
                                </tbody>
                            </table>
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
    <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){                
			$("#pessoas").fcbkcomplete({
				json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/associar_pessoa",
				addontab: true,
				height: 10,
				maxshownitems: 10,
				cache: true,
				maxitems: 20,
				filter_selected: true,
				firstselected: true,
				complete_text: "<?= $idioma["mensagem_select"]; ?>",
				addoncomma: true
			});
		});
		
		function remover(id) {
			confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
			if(confirma) {
				document.getElementById("remover").value = id;
				document.getElementById("remover_pessoa").submit();
			} 
		}
	</script>
</div>
</body>
</html>