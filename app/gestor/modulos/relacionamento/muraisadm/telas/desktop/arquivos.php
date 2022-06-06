<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<script language="javascript">
    var regras = new Array();
	//regras.push("formato_arquivo,arquivos[1],zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf|odt|ods|odf,'',<?php echo $idioma['arquivo_invalido']; ?>");

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
	br = document.createElement('br');
	div_arquivos.appendChild(br);
	
	regras.push("formato_arquivo,"+id+",zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf|odt|ods|odf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}

function deletarArquivo(val){
	if(confirm("<?= $idioma["confirmar_remocao"]?>")){
		document.getElementById("idmural_arquivo").value = val
		document.getElementById("acao").value = "remover_arquivos";
		document.getElementById("form_arquivo").submit();
	}
}

function validarArquivo(){
	
	sem_arquivos
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
    	<li class="active"><?php echo $linha["titulo"]; ?></li>
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
    
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        			<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
                    <h2 class="tituloEdicao"><?= $linha["titulo"]; ?> </h2> 
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
                             
                          <form id="form_arquivo" method="post" action="" class="well form-horizontal form-inline" enctype="multipart/form-data" onsubmit="return validateFields(this, regras)">
                             <?php if($perfil["permissoes"][$url[2]."|9"]) { ?>
                             <input name="acao" id="acao" type="hidden" value="salvar_arquivos" />
                             <input name="idmural_arquivo" id="idmural_arquivo" type="hidden" value="" />                          
                              <?php /*?><div id="blog-posts">
                                <? if(count($manualArquivosArray) > 0){?>
                                <?php foreach($manualArquivosArray as $manual) { ?>
                                  <div style="float:left;width:230px">
                                  <?php echo $manual['nome']; ?><br />
                                  <small class="muted"><?php echo formataData($manual['data_cad'],'pt',1); ?></small></p> 
                                  </div>
                                  <div style="float:right; width:40px;">
                                  <button type="button" id="remover" class="btn btn-mini" data-original-title="<?= $idioma["btn_visualizar"]; ?>" data-placement="left" rel="tooltip" onclick="deletarArquivo('<?php echo $manual['idmural_arquivo']; ?>')"><i class="icon-remove"></i></button>
                                  </div>
                          
                                  <br /><br /><br /><br />
                                <?php } ?>
                                <? }else{ ?>
                                  <?= $idioma["sem_arquivo"]?>
                                <? } ?>            
                              </div><?php */?>
                            <div class="control-group">
  						  <p><?= $idioma["form_associar"]; ?></p>
                          <p><?= $idioma['anexar_arquivo']; ?></p>
                          <div style="float:left;" id="div_arquivos">
                          <input type="file" name="arquivos[1]" id="arquivos[1]" class="files" /><br /><br />
                          </div>
                      </div>
                       
                      <small onclick="novo_arquivo();" style="cursor:pointer;"><?php echo $idioma['outro_arquivo']; ?></small><br /><br /> 
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
                    
                    <form method="post" id="remover_arquivo" name="remover_arquivo">
                        <input type="hidden" id="acao" name="acao" value="remover_arquivo">
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
                            <?php if(count($arquivosArray) > 0) { ?>
                                <?php foreach($arquivosArray as $ind => $arquivo) { ?>
                                    <tr>
                                        <td><?php echo $arquivo["idmural_arquivo"]; ?></td>
                                        <td><?= $arquivo["nome"]; ?></td>
                                        <td><?php echo tamanhoArquivo($arquivo["tamanho"]); ?></td>
                                        <td style="text-align:right">                                       
                                           <?php if($perfil["permissoes"][$url[2]."|11"]) { ?>
                                          <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/downloadArquivo/".$arquivo["idmural_arquivo"]; ?>" class="btn btn-mini" data-original-title="<?= $idioma["btn_download"]; ?>" data-placement="left" rel="tooltip"><i class="icon-download-alt"></i> <?php echo $idioma["baixar"]; ?></a>
                                          <? }else{ ?>
                                          <a href="javascript:void(0);" class="btn btn-mini disabled" data-original-title="<?= $idioma["btn_remover_permissao_download"]; ?>" data-placement="left" rel="tooltip"><i class="icon-download-alt"></i> <?php echo $idioma["baixar"]; ?></a>
                                          <? } ?>                                          
                                          <?php if($perfil["permissoes"][$url[2]."|10"]) { ?>
                                          <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="deletarArquivo(<?php echo $arquivo["idmural_arquivo"]; ?>)"><i class="icon-remove"></i></a>
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
</div>
</body>
</html>                          