<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<?php //incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="<?= $config["urlSistema"]; ?>/assets/aluno/css/forum.css">
<style type="text/css">
  .content {
	min-width:500px;
  }
  .conteudo {
	min-width:390px;
  }
</style>
</head>
<body>
<div class="content">
  <div class="conteudo" style="min-height:400px;">
    <div class="coluna-dados" id="coluna-conteudo">
      <div class="area area-conteudo" >
        <div class="row-fluid">
          <div class="span12">
            <div class="forum-form marge30-up">
              <div class="forum-base-titulo corbgpadrao"><?php if($url[8] != "moderar") { echo $idioma["responder"]; } else { echo $idioma["moderar"]; } ?></div>
              <div>
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data">
                  <input name="acao" id="acao" type="hidden" value="<?php if($url[8] != "moderar") { ?>responder_topico<?php } else { ?>moderar_mensagem<?php } ?>" />
                  <?php if($url[7] && $url[8] == "responder") { ?>
                    <input name="idmensagem_associada" id="idmensagem_associada" type="hidden" value="<?php echo $url[7]; ?>" />
                  <?php } elseif($url[7] && $url[8] == "moderar") { ?>
                    <input name="idmensagem" id="idmensagem" type="hidden" value="<?php echo $url[7]; ?>" />
                  <?php } ?>
                  <label><?php echo $idioma["mensagem"]; ?></label>
                  <textarea id="mensagem" name="<?php if($url[8] != "moderar") { ?>mensagem<?php } else { ?>moderar<?php } ?>"><?php if($mensagem["moderado"] == "S") { echo nl2br($mensagem["moderado_mensagem"]); } else { echo nl2br($mensagem["mensagem"]); } ?></textarea>
                  <?php if($url[8] != "moderar" && $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|5", false)) { ?>
                      <label onClick="document.getElementById('form_arquivo').style.display = '';"><?php echo $idioma["anexar_arquivo"]; ?></label>
                      <input id="form_arquivo" name="arquivo" type="file" style="display:none;" />
                  <?php } ?>
                  <div style="margin-top:20px;" class="divisor">
                    <input class="corbgpadrao btfade" type="submit" value="<?php echo $idioma["salvar"]; ?>" />
                  </div>
                </form>
              </div>
            </div>
          </div> <!-- principal area -->
        </div>
      </div> <!-- area-conteudo --> 	
    </div><!-- coluna dados -->
  </div>
</div>
<script src="/assets/js/validation.js"></script>
<script type="text/javascript">
  var regras = new Array();
  regras.push("required,mensagem,<?php echo $idioma["mensagem_vazio"]; ?>");
  <?php if($url[8] != "moderar") { ?>
	  regras.push("formato_arquivo,form_arquivo,jpg|jpeg|doc|docx|pdf|ppt|pptx,,<?php echo $idioma["extensao_arquivo_nao_permitida"]; ?>");
  <?php } ?> 
</script>
</body>
</html>