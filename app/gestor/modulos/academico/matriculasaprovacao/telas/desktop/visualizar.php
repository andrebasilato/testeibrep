  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["modificacoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li class="active"><?php printf($idioma["validacao"], $linha["solicitante"], formataData($linha["data_cad"],"br",1)); ?></li>
  	</ul>
    <? if($linha["situacao"] == "V"){?>
    	<ul class="breadcrumb">
            <li class="active"><?php printf($idioma["validacao_apovada"], $linha["usuario_validou"], formataData($linha["validacao"],"br",1)); ?></li>
        </ul>
    <? } elseif($linha["situacao"] == "R") { ?>
    	<ul class="breadcrumb">
            <li class="active"><?php printf($idioma["validacao_recusada"], $linha["usuario_validou"], formataData($linha["validacao"],"br",1)); ?></li>
        </ul>
    <? } ?>
    <ul class="breadcrumb">
    	<li><? echo $idioma["selecionado_cliente"]; ?></li>
    	<li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  	</ul>
    
    <form method="post" onsubmit="return validateFields(this, regras)" id="form_validacao" enctype="multipart/form-data" class="form-vertical">
    	<input name="acao" type="hidden" value="validar" />
        <input name="validacao" type="hidden" value="<?=$linha["idvalidacao"]?>" />
        <div class="span7" style="margin:0">
          <div style="overflow: auto; max-height: 400px;">
          	<? $linhaObj->GerarTabela($campos,NULL,$idioma,"listagem_campos"); ?>
          </div>
          <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
          <div class="control-group">
            <label class="control-label" for="form_contrato"><strong><?=$idioma["observacoes"];?>:</strong></label>
            <div class="controls">
              <? if($linha["situacao"] == "P"){?>
                <textarea class="xxlarge" id="form_descricao" name="descricao" style="width:100%;"></textarea>
              <? } else { echo $linha["descricao"]; } ?>
            </div>
          </div>
         
			<? if($linha["situacao"] == "P"){ ?>
              <div class="form-actions">
                  <input type="submit" class="btn btn-primary" id="btn_valida" name="btn_valida" value="<?= $idioma["btn_validar"]; ?>">&nbsp;
                  <input type="button" class="btn btn-danger" id="btn_recusa" name="btn_recusa" value="<?= $idioma["btn_negar"]; ?>">&nbsp;
              </div>
            <? } ?>
          <? } ?>
       </div>
    </form>  
  </section>
<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
  <? if($linha["situacao"] == "P"){?>
  <!--<link rel="stylesheet" href="/assets/plugins/redactor770/redactor/css/redactor.css" />
  <script src="/assets/plugins/redactor770/redactor/langs/pt_br.js"></script> 
  <script src="/assets/plugins/redactor770/redactor/redactor.js"></script>-->
  <script type="text/javascript">
	var regras = new Array();

	$("#btn_recusa").click(function(){
		if(confirm('<?=$idioma["confirm_recusar"];?>')){
			regras.push("required,form_descricao,<?=$idioma["motivo_vazio"];?>");
			$('#form_validacao').submit();
		}else{
			return false;
		}
	});
	
	$("#btn_valida").click(function(){
		regras = new Array();
		if(!confirm('<?=$idioma["confirm_validar"];?>')){
			return false;
		}
	});
	
	//jQuery(document).ready(function($) {
	//	$("#form_descricao").height(150);
	//	$("#form_descricao").redactor({lang: 'pt_br', buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 'fontcolor', 'backcolor', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'horizontalrule']});				
	//});
  </script>
  <? } ?>
<? } ?>