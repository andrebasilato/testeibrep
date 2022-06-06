<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
  margin:20px;
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
<div class="row-fluid" >
  <div class="span12">
  	 <legend><?=$idioma["label_titulo"]; ?></legend>
      <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/renegociar_parcelas" class="form-horizontal" onsubmit="return verificaEvento();" id="form_tipo" >
                <br /> 

				<h4><?=$idioma["financeiro_idevento"];?></h4>
				<br />
				<select name="idevento" id="idevento" style="width:auto;">
				  <option value=""><?= $idioma["selecione_idevento"]; ?></option>
				  <?php foreach($eventosFinanceiros as $eventoFinanceiro) { ?>
					<option value="<?= $eventoFinanceiro['idevento']; ?>" ><?= $eventoFinanceiro['nome']; ?></option>
				  <? } ?>
				</select>
								
				<input class="btn" type="submit" value="<?=$idioma["btn_selecionar"];?>" />
				                  
      </form> 
      </div>
  </div>
</div>
<script>
function verificaEvento() {
	if(document.getElementById('idevento').value == '') {
		alert("Você precisa escolher o tipo de conta.");
		return false;
	} else {
		document.getElementById('form_tipo').action = (document.getElementById('form_tipo').action + '/' + document.getElementById('idevento').value)
	}
}
</script>
</body>
</html>