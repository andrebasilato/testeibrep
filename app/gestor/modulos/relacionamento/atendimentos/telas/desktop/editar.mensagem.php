<section id="global">
  <div class="page-header">
      <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  <br />
  <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" action="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza"."/".$url[5]; ?>">
	  <strong><?php echo $idioma['resposta']; $time = time(); ?></strong>  
      <br />
      <textarea name="resposta" id="resposta<?php echo $time; ?>" class="xxlarge" rows="5" style="width:99%;"><?php echo $linha["resposta"]; ?></textarea>
      <?php if(count($linha["arquivos"])) { ?>
          <br />
          <strong><?= $idioma["arquivo"]; ?></strong>
          <br />
          <?php foreach ($linha["arquivos"] as $arquivos) { ?>
              <div id="arquivos<?= $arquivos["idarquivo"]; ?>">
                  <span class="icon-file"></span>
                  <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download/".$url[5]."/".$arquivos["idarquivo"]; ?>"><?= $arquivos["nome"]; ?> (<?= tamanhoArquivo($arquivos["tamanho"]); ?>)</a>
                  <span class="icon-remove" onclick="confirmaDeletaArquivo('<?= $arquivos["idarquivo"]; ?>')" style="cursor:pointer" data-original-title="<?php echo $idioma["excluir_arquivo"]; ?>" data-placement="right" rel="tooltip"></span>
                  <br />
              </div>
          <?php } ?>
      <?php } ?>
      <br />
      <input name="publica" type="checkbox" value="S" <?php if($linha["publica"] == "S") { ?>checked="checked"<?php } ?> />&nbsp;<?php echo $idioma['publica']; ?>
      <br />
      <br />
      <div id="divArquivos">
	  	  <strong><?php echo $idioma["anexar_arquivo"]; ?></strong>
          <?php /*<small onclick="novoArquivo();" style="cursor:pointer;"><?php echo $idioma["outro_arquivo"]; ?></small>*/ ?>
		  <input type="button" class="btn btn-primary btn-mini" onclick="novoArquivo();" name="enviar" value=" + " />
          <br />
          <input type="file" name="arquivo[1]" id="arquivo[1]" />
          <br />
      </div> 
      <input type="hidden" name="acao" id="acao" value="editar_mensagem" />
      <input class="btn" type="submit" name="salvar" id="salvar" value="<?php echo $idioma['btn_salvar']; ?>" />  
  </form>
</section>
<script type="text/javascript">
	var regras = new Array();
	regras.push("formato_arquivo,arquivo[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
	regras.push("required,resposta,<?php echo $idioma['mensagem_vazio']; ?>");
</script>
<script type="text/javascript">
function novoArquivo(){
	var IE = document.all?true:false
	var div_arquivos = document.getElementById( "divArquivos" );		
	if( !IE ){
		var length = div_arquivos.childNodes.length -1;
	}else{
		var length = div_arquivos.childNodes.length +1;			
	}		

	var input = document.createElement( 'INPUT' );
	input.setAttribute( "type" , "file" );
	id = "arquivo[" + length + "]";
	input.setAttribute( "name" , id);	
	input.setAttribute( "id" , id);
	div_arquivos.appendChild( input );
	var br = document.createElement('br');
	div_arquivos.appendChild(br);
	
	regras.push("formato_arquivo,"+id+",jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
}
</script>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script>
function deletaArquivo(arquivo){ 
	$.msg({ 
	  autoUnblock : false,
	  clickUnblock : false,
	  klass : 'white-on-black',
	  content: 'Processando solicitação.',
	  afterBlock : function(){
		var self = this; 
		  jQuery.ajax({ 
			 url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/excluir_arquivo",
			 dataType: "json",
			 type: "POST",
			 data: {idarquvio: arquivo},
			 success: function(json){				
				if(json.sucesso){;
					$("#arquivo"+arquivo).html("");
					$("#arquivos"+arquivo).html("<?php echo $idioma['excluido_sucesso']; ?>");
					self.unblock();								
				} else {
					$("#arquivo"+arquivo).html("<?php echo $idioma['excluido_falha']; ?>");
					$("#arquivos"+arquivo).html("<?php echo $idioma['excluido_falha']; ?>");
					alert('<?php echo $idioma['erro_json']; ?>');
					self.unblock();	
				}										 
			 }
		  });		
	  }
	});
}
function confirmaDeletaArquivo(arquivo) {
	confirma = confirm("<?php echo $idioma["confirma_excluir_arquivo"]; ?>");
	if(confirma) {
		deletaArquivo(arquivo);
	} else {
		return false;
	}
}