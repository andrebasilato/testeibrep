<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
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
      		<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_configuracoes"]; ?></a> <span class="divider">/</span></li>
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
  		<div class="span12">
        	<div class="box-conteudo">
        		<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2> 
            	<div class="tabbable tabs-left">
					<?php incluirTela("inc_menu_edicao",$config,$linha); ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_editar">
                            <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
                            <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
                            
								<? if($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in"> 
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                            <? } ?>
                            <form class="well wellDestaque form-inline" method="post" onsubmit="return valida_form(this)">
                            
                                <table>
                                  <tr>
                                    <td><?php echo $idioma["legenda_numero"]; ?></td>
                                    <td><?php echo $idioma["legenda_nome"]; ?></td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td><input type="text" class="span1" name="numero" id="numero" /></td>
                                    <td><input type="text" class="span3" name="titulo" id="titulo" /></td>
                                    <td>
                                      <input type="hidden" id="acao" name="acao" value="inserir_opcao">
                                	  <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                                    </td>
                                  </tr>
                                </table>
                            </form>                            
                            <form method="post" id="remover_opcao" name="remover_opcao">
                                <input type="hidden" id="acao" name="acao" value="remover_opcao">
                                <input type="hidden" id="remover" name="remover" value="">
                            </form>                            
                            <form method="post" id="editar_opcao" name="editar_opcao">
                              <input type="hidden" id="acao" name="acao" value="editar_opcao">
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th width="80"><?= $idioma["listagem_id"]; ?></th>
                                          <th><?= $idioma["listagem_nome"]; ?></th>
                                          <th width="80"><?= $idioma["listagem_ativo_painel"]; ?></th>
                                          <th width="60"><?= $idioma["listagem_opcoes"]; ?></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php if(count($opcoesArrayAss) > 0) { ?>
                                          <?php 
										  $validacao = "";
										  foreach($opcoesArrayAss as $ind => $op) { 
										  $validacao .= '$("#numero'.$op["idopcao"].'").keypress(isNumber); $("#numero'.$op["idopcao"].'").blur(isNumberCopy); ';
										  ?>
                                              <tr>
                                                  <td><input type="text" class="span1" name="numero[<?php echo $op["idopcao"]; ?>]" id="numero<?php echo $op["idopcao"]; ?>" value="<?php echo $op["numero"]; ?>" maxlength="10" /></td>
                                                  <td><?php echo $op["titulo"]; ?></td>
                                                  <td>
                                                    <?php if($op["ativo_painel"] == "S") { ?>
                                                      <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-success" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $op["idpergunta"]; ?>,<?php echo $op["idopcao"]; ?>);" id="ativo_painel<?php echo $op["idopcao"]; ?>" style="cursor:pointer;">Sim</span>
                                                    <?php } else { ?>
                                                      <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-important" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $op["idpergunta"]; ?>,<?php echo $op["idopcao"]; ?>);" id="ativo_painel<?php echo $op["idopcao"]; ?>" style="cursor:pointer;">Não</span>
                                                    <?php } ?>
                                                  </td>
                                                  <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $op["idopcao"]; ?>)"><i class="icon-remove"></i></a></td>
                                              </tr>
                                          <?php } ?>
                                      <?php } else { ?>
                                          <tr>
                                              <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                                          </tr>
                                      <?php } ?>
                                  </tbody>
                              </table> 
                              <div class="form-actions">
                                <input class="btn btn-primary" type="submit" value="Salvar">
                              </div>
                            </form>           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
	<script type="text/javascript">
	  function valida_form(form) {
		  ordem = document.getElementById('numero').value;
		  titulo = document.getElementById('titulo').value;
		  if (!ordem){
			  alert('<?= $idioma["numero_vazio"]; ?>');
			  return false;
		  }else if(!titulo){
			  alert('<?= $idioma["descricao_vazio"]; ?>');
			  return false;
		  }
		  return true;
	  }
		function ativarDesativar(pergunta, opcao){
			$.msg({ 
			  autoUnblock : false,
			  clickUnblock : false,
			  klass : 'white-on-black',
			  content: 'Processando solicitação.',
			  afterBlock : function(){
				var self = this;
				  jQuery.ajax({
					 url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/ativar_desativar",
					 dataType: "json", //Tipo de Retorno
					 type: "POST",
					 data: {idpergunta: pergunta, idopcao: opcao},
					 success: function(json){ //Se ocorrer tudo certo
						if(json.sucesso){
							altualizaBotoes(json.ativo, json.opcao);
							self.unblock();								
						} else {
							alert('<?= $idioma["json_erro"]; ?>');
							self.unblock();	
						}
												 
					 }
				  });
				
			  }
			});
		}
			
		function altualizaBotoes(ativo, opcao) {
			if(ativo == "S"){
				$("#ativo_painel"+opcao).removeClass("label-important");
				$("#ativo_painel"+opcao).addClass("label-success");
				$("#ativo_painel"+opcao).html("Sim");
			} else if(ativo == "N") {
				$("#ativo_painel"+opcao).removeClass("label-success");
				$("#ativo_painel"+opcao).addClass("label-important");
				$("#ativo_painel"+opcao).html("Não");
			}
		}
		  	
		function remover(id) {
			confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
			if(confirma) {
				document.getElementById("remover").value = id;
				document.getElementById("remover_opcao").submit();
			} 
		}
        var regras = new Array();
        jQuery(document).ready(function($) {
            $("#numero").keypress(isNumber);
            $("#numero").blur(isNumberCopy);
			<?php echo $validacao; ?>
        });
    </script>
</div>
</body>
</html>