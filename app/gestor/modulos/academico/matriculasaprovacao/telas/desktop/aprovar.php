  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["modificacoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li class="active"><?php printf($idioma["validacao"], formataData($liberacao["data_cad"],"br",1)); ?></li>
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
    
    <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>" onsubmit="return validateFields(this, regras)" id="form_validacao" enctype="multipart/form-data" class="form-vertical">
    	<input name="acao" type="hidden" value="aprovar" />
        <input name="validacao" type="hidden" value="<?=$linha["idvalidacao"]?>" />
        <div class="span7" style="margin:0">

          <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>         
			<? //if($linha["situacao"] == "P"){ ?>
              <div class="form-actions">
                  <input type="submit" class="btn btn-primary" id="btn_valida" name="btn_valida" value="<?= $idioma["btn_validar"]; ?>">&nbsp;
                  <input type="submit" class="btn btn-danger" id="btn_recusa" name="btn_recusa" value="<?= $idioma["btn_negar"]; ?>">&nbsp;
              </div>
            <? //} ?>
          <? } ?>
       </div>
    </form>  
  </section>
<? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
  <? if($linha["situacao"] == "P"){?>
  <script type="text/javascript">
	$("#btn_recusa").click(function(){
		if(confirm('<?=$idioma["confirm_recusar"];?>')){
			$('#form_validacao').submit();
		}else{
			return false;
		}
	});
	
	$("#btn_valida").click(function(){
		if(!confirm('<?=$idioma["confirm_validar"];?>')){
			return false;
		}
	});
  </script>
  <? } ?>
<? } ?>