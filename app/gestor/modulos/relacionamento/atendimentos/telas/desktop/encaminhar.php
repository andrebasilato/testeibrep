<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/assets/css/construtor.css" rel="stylesheet">
<style type="text/css">
body {
	background-color: #FFF !important;
	background-image:none;
	padding-top:0px !important;
}
body {
	min-width: 500px;
}
.container-fluid {
	min-width: 500px;
}
</style>
</head>
<body>
<section id="global">
  <div class="page-header">
      <h1><?= $idioma["titulo"]; ?></h1>
  </div>
  <br />

  <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/visualiza" onsubmit="return validateFields(this, regras)" target="_parent">
	  <strong><?= $idioma['assunto']; ?></strong><br />
      <select id="idassunto" name="idassunto">
        <? foreach ($assuntos as $assunto) { ?>
            <option value="<? echo $assunto['idassunto']; ?>" <? if ($assunto['idassunto'] == $linha['idassunto']) echo 'selected="selected"'; ?> ><? echo $assunto['nome']; ?></option>
        <? } ?>
      </select>
      <br /><br />
      <div class="control-group" id="subassunto">
        <label for="idsubassunto" id="label_idsubassunto"><?= $idioma['subassunto']; ?></label>
        <select id="idsubassunto" name="idsubassunto">
          <option value=""> - <?= $idioma["form_selecione_subassunto"]; ?> - </option>
          <? foreach ($subassuntos as $subassunto) { ?>
              <option value="<?= $subassunto['idsubassunto']; ?>" <? if ($subassunto['idsubassunto'] == $linha['idsubassunto']) echo 'selected="selected"'; ?> ><?= $subassunto['nome']; ?></option>
          <? } ?>
        </select>
      </div>
      <?php/*<div class="control-group" id="unidade">
         <label for="idunidade" id="label_idunidade" class="control-label"><?= $idioma["unidade"]; ?></label>
         <div class="controls">
            <select class="span5" id="idunidade" name="idunidade">
               <option value=""></option>
                  <?php foreach($unidades as $ind => $unidade) { ?>
                      <option value="<?= $unidade["idunidade"]; ?>" <? if ($unidade['idunidade'] == $linha['idunidade']) echo 'selected="selected"'; ?>><?php echo $unidade["empreendimento"]." - ".$unidade["etapa"]." - ".$unidade["bloco"]." - ".$unidade["unidade"]; ?></option>
                  <?php } ?>
            </select>
            <p class="help-block"><?php echo $idioma["ajuda_unidade"]; ?></p>
         </div>
      </div>  */?>    
      <br />
      <br />
      <input type="hidden" name="acao" id="acao" value="alterar_assunto" />
      <input type="submit" name="salvar" id="salvar" value="<?= $idioma['btn_salvar']; ?>" class="btn" />  
  </form>
    
</section>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/js/construtor.js"></script>

<script type="text/javascript">
	var regras = new Array();
	regras.push("required,idassunto,<?= $idioma["assunto_vazio"]; ?>");
 	$(document).ready(function(){
			var subassunto_obrigatorio = '<?= $linha['subassunto_obrigatorio'];?>';
			if (subassunto_obrigatorio == 'S') {
				$('#label_idsubassunto').html('<strong><?php echo $idioma["subassunto"]; ?></strong>');
				regras.push("required,idsubassunto,<?php echo $idioma["subassunto_vazio"]; ?>");
			}			
	})
	$('#idassunto').change(function(){
		if($(this).val()){
			$.getJSON('<?php echo "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/json/subassunto"; ?>',{idassunto: $(this).val(), ajax: 'true'}, function(json){
				var a = json.subassunto.length;
				var options = '<option value="">– <?php echo $idioma["form_selecione_subassunto"]; ?> –</option>';
				for (var i = 0; i < a; i++) {
					options += '<option value="' + json.subassunto[i].idsubassunto + '" >' + json.subassunto[i].nome + '</option>';
				}
				$('#idsubassunto').html(options);
			  if(json.subassunto_obrigatorio == "S") {
				  regras = new Array();
				  regras.push("required,idassunto,<?php echo $idioma["assunto_vazio"]; ?>");
				  regras.push("required,idsubassunto,<?php echo $idioma["subassunto_vazio"]; ?>");
				  $('#label_idsubassunto').html('<strong><?php echo $idioma["subassunto"]; ?></strong>');
				  $('#subassunto').show();
			  } else {
				  regras = new Array();
				  regras.push("required,idassunto,<?php echo $idioma["assunto_vazio"]; ?>");
				  $('#label_idsubassunto').html('<?php echo $idioma["subassunto"]; ?>');
				  if(json.subassunto.length <= 0) {
					$('#subassunto').hide();
				  } else {
					$('#subassunto').show();
				  }
			  }
			});
		} else {
			$('#idsubassunto').html('<option value="">– <?php echo $idioma["form_selecione_assunto"]; ?> –</option>');
		}
	});
</script>
</body>
</html>