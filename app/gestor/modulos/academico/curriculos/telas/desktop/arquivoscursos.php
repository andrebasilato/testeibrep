<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<script language="javascript">
function novo_arquivo(){
	var IE = document.all?true:false
	var div_arquivos = document.getElementById( "div_arquivos" );		
	if( !IE ){
		var length = div_arquivos.childNodes.length -1;
	}else{
		var length = div_arquivos.childNodes.length +1;			
	}		

	var input = document.createElement( 'INPUT' );
	input.setAttribute( "type" , "file" );
	id = "arquivos[" + length + "]";
	input.setAttribute( "name" , id);	
	input.setAttribute( "id" , id);
	div_arquivos.appendChild( input );
	var br = document.createElement('br');
	div_arquivos.appendChild(br);
	
	regras.push("formato_arquivo,"+id+",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}
</script>
</head>
<body>
<? incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
    	<li class="active"><?php echo $linha["nome"]; ?></li>
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
    
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        			<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
                    <h2 class="tituloEdicao"><?= $linha["nome"]; ?> </h2> 
        			<div class="tabbable tabs-left">
					<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_editar">
                          <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>        
        
                          <? if(count($salvar["erros"]) > 0){ ?>
                            <div class="alert alert-error fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                  <strong><?= $idioma["form_erros"]; ?></strong>
                                      <? foreach($salvar["erros"] as $ind => $val) { ?>
                                          <br />
                                          <?php echo $idioma[$val]; ?>
                                      <? } ?>
                                  </strong>
                                </div>
                          <? } ?>
                          <? if($_POST["msg"]) { ?>
                                <div class="alert alert-success fade in">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                                </div>
                          <? } ?>
                             
                    <form method="post" action="" class="well form-horizontal form-inline" enctype="multipart/form-data" onsubmit="return validateFields(this, regras)">
                      <input name="acao" type="hidden" value="salvar_arquivoscursos" />                            
                        <div class="control-group">
                          <label class="control-label" for="form_titulo"><strong><?php echo $idioma["form_titulo"]; ?></strong></label>
                          <div class="controls">
                            <input id="form_titulo" class="span5" type="text" maxlength="100" value="<?php echo $_POST["titulo"]; ?>" name="titulo">
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="form_descricao"><?php echo $idioma["form_descricao"]; ?></label>
                          <div class="controls">
                            <textarea id="form_descricao" class="xxlarge" name="descricao" style="height: 100px; width: 60%;"><?php echo $_POST["descricao"]; ?></textarea>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="arquivos[1]"><strong><?php echo $idioma['anexar_arquivo']; ?> </strong></label>
                          <div class="controls" id="div_arquivos">
                            <input type="file" name="arquivos[1]" id="arquivos[1]" /><br />
                          </div>
                        </div>
                        
                      <input type="submit" class="controls btn btn-primary" value="<?= $idioma["salvar"]; ?>">&nbsp;
                      <br />
                      <br />    
                    </form> 
                    
                    <form method="post" id="remover_arquivoscursos" name="remover_arquivoscursos">
                        <input type="hidden" id="acao" name="acao" value="remover_arquivoscursos">
                        <input type="hidden" id="remover" name="remover" value="">
                    </form>                            
                    <table class="table table-striped tabelaSemTamanho">
                        <thead>
                            <tr>
                                <th><?= $idioma["listagem_titulo"]; ?></th>
                                <th><?= $idioma["listagem_descricao"]; ?></th>
                                <th><?= $idioma["listagem_nome"]; ?></th>
                                <th width="70"><?= $idioma["listagem_tamanho"]; ?></th>
                                <th width="35">Ativo</th>
                                <th style="text-align:center;" width="190"><?= $idioma["listagem_opcoes"]; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($materiaisArray) > 0) { ?>
                                <?php foreach($materiaisArray as $ind => $material) { ?>
                                    <tr>
                                        <td><?= $material["titulo"]; ?></td>
                                        <td><?= $material["descricao"]; ?></td>
                                        <td><?= $material["nome"]; ?></td>
                                        <td><?php echo tamanhoArquivo($material["tamanho"]); ?></td>
                                        <td>                                        
											<?php if($material["ativo_painel"] == "S") { ?>
											  <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-success" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $material["idcurriculo"]; ?>,<?php echo $material["idarquivo"]; ?>);" id="ativo_painel<?php echo $material["idarquivo"]; ?>" style="cursor:pointer;">Sim</span>
											<?php } else { ?>
											  <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-important" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $material["idcurriculo"]; ?>,<?php echo $material["idarquivo"]; ?>);" id="ativo_painel<?php echo $material["idarquivo"]; ?>" style="cursor:pointer;">Não</span>
											<?php } ?> 
                                        </td>
                                        <td style="text-align:right">
                                          <?php if (strpos($material['tipo'],'image') !== false) { ?> <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza_imagem_arquivocurso/".$material["idarquivo"]; ?>" class="btn btn-mini" data-original-title="<?= $idioma["btn_visualizar"]; ?>" data-placement="left" rel="facebox tooltip"><i class="icon-picture"></i> <?php echo $idioma["visualizar"]; ?></a> <?php } ?>                                          
                                          
										  <a class="btn btn-mini" href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download_arquivocurso/".$material["idarquivo"]; ?>" data-original-title="<?= $idioma["btn_download"]; ?>" data-placement="left" rel="tooltip">
                                          <i class="icon-download-alt"></i> <?php echo $idioma["baixar"]; ?>
                                          </a> 
                                          <?php if($perfil["permissoes"][$url[2]."|15"]) { ?>
                                          <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $material["idarquivo"]; ?>)"><i class="icon-remove"></i> </a>                                          
                                          <?php } else { ?>
                                          <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
                                          <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6"><?= $idioma["sem_informacao"]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                                       
                    </div></div></div>        
        
        </div>
    </div> 
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
<script type="text/javascript">
	var regras = new Array();
	//regras.push("required,form_titulo,<?php echo $idioma["titulo_vazio"]; ?>");
	//regras.push("formato_arquivo,arquivos[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");	
</script>

	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
	<script type="text/javascript">
	
		function ativarDesativar(curriculo, arquivo){
			$.msg({ 
			  autoUnblock : false,
			  clickUnblock : false,
			  klass : 'white-on-black',
			  content: 'Processando solicitação.',
			  afterBlock : function(){
				var self = this;
				  jQuery.ajax({
					 url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/ativar_desativar_arquivos_cursos",
					 dataType: "json", //Tipo de Retorno
					 type: "POST",
					 data: {curriculo: curriculo, arquivo: arquivo},
					 success: function(json){ //Se ocorrer tudo certo
						if(json.sucesso){
							altualizaBotoes(json.ativo, json.arquivo);
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
			
		function altualizaBotoes(ativo, arquivo) {
			if(ativo == "S"){
				$("#ativo_painel"+arquivo).removeClass("label-important");
				$("#ativo_painel"+arquivo).addClass("label-success");
				$("#ativo_painel"+arquivo).html("Sim");
			} else if(ativo == "N") {
				$("#ativo_painel"+arquivo).removeClass("label-success");
				$("#ativo_painel"+arquivo).addClass("label-important");
				$("#ativo_painel"+arquivo).html("Não");
			}
		}

    </script>


<script type="text/javascript">		
	function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
			document.getElementById("remover").value = id;
			document.getElementById("remover_arquivoscursos").submit();
		} 
	}
</script>
</div>
</body>
</html>