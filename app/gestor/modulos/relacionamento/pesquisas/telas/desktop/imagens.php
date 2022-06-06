<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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
	/*br = document.createElement('br');
	div_arquivos.appendChild(br);*/
	
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
						  <div id="listagem_informacoes"><?= $idioma["texto_explicativo"]; ?></div>
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
                      <?php if($perfil["permissoes"][$url[2]."|13"]) { ?>
                      <input name="acao" type="hidden" value="salvar_imagens" />                            
                      
                      <div class="control-group">
  						  <p><?= $idioma["form_associar"]; ?></p>
                          <p><?= $idioma['anexar_arquivo']; ?> <input type="button" class="btn btn-primary btn-mini" onclick="novo_arquivo();" name="enviar" value=" + " /></p>
                          <div style="float:left;" id="div_arquivos">
                          <input type="file" name="arquivos[1]" id="arquivos[1]" /><br />
                          </div>
                      </div>
                       
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["salvar"]; ?>">&nbsp;
                      <? } else{ ?>
                      <div class="control-group">
  						  <p><?= $idioma["form_associar"]; ?></p>
                          <p><?= $idioma['anexar_arquivo']; ?></p>
                          <div style="float:left;" id="div_arquivos">
                          <input type="file" name="arquivos[1]" id="arquivos[1]" disabled="disabled" /><br /><br />
                          </div>
                      </div>
                      <a href="javascript:void(0);" rel="tooltip" data-original-title="<?= $idioma["salvar_permissao"]; ?>" data-placement="right" class="btn disabled"><?= $idioma["salvar"]; ?></a>
                      <? } ?>
                      <br /><br />    
                    </form> 
                    
                    <form method="post" id="remover_imagem" name="remover_imagem">
                        <input type="hidden" id="acao" name="acao" value="remover_imagem">
                        <input type="hidden" id="remover" name="remover" value="">
                    </form>                            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $idioma["listagem_id"]; ?></th>
                                <th><?= $idioma["listagem_nome"]; ?></th>
                                <th><?= $idioma["listagem_tamanho"]; ?></th>
                                <th width="250" style="text-align:center;"><?= $idioma["listagem_opcoes"]; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($imagensArray) > 0) { ?>
                                <?php foreach($imagensArray as $ind => $imagem) { ?>
                                    <tr>
                                        <td><?php echo $imagem["idpesquisa_imagem"]; ?></td>
                                        <td><?= $imagem["nome"]; ?></td>
                                        <td><?php echo tamanhoArquivo($imagem["tamanho"]); ?></td>
                                        <td style="text-align:right">
                                          <?php if (strpos($imagem['tipo'],'image') !== false) { ?> <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza_imagem/".$imagem["idpesquisa_imagem"]; ?>" rel="facebox tooltip" class="btn btn-mini" data-original-title="<?= $idioma["btn_visualizar"]; ?>" data-placement="left"><i class="icon-picture"></i> <?php echo $idioma["visualizar"]; ?></a> <?php } ?>                                          
                                          <?php /*<a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download/".$imagem["idpesquisa_imagem"]; ?>">
                                          <button class="btn btn-mini" data-original-title="<?= $idioma["btn_download"]; ?>" data-placement="left" rel="tooltip"><i class="icon-download-alt"></i> <?php echo $idioma["baixar"]; ?></button>
                                          </a> */ ?>
										  <a class="btn btn-mini" data-original-title="<?= $idioma["btn_download"]; ?>" data-placement="left" rel="tooltip" href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download/".$imagem["idpesquisa_imagem"]; ?>">
                                          <i class="icon-download-alt"></i> <?php echo $idioma["baixar"]; ?>
                                          </a> 
                                          <?php if($perfil["permissoes"][$url[2]."|14"]) { ?>
                                          <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $imagem["idpesquisa_imagem"]; ?>)"><i class="icon-remove"></i> </a>                                          
                                          <?php } else { ?>
                                          <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_excluir"]; ?>" data-placement="left" rel="tooltip"><i class="icon-remove"></i></a>
                                          <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4"><?= $idioma["sem_informacao"]; ?></td>
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
	regras.push("formato_arquivo,arquivos[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");	
</script>
<script type="text/javascript">		
	function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
			document.getElementById("remover").value = id;
			document.getElementById("remover_imagem").submit();
		} 
	}
</script>
</div>
</body>
</html>