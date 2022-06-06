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
	min-width: 400px;
  }
  .container-fluid {
	min-width: 400px;
  }
</style>
</head>
<body>
<section id="global">
  <div class="page-header"><h1><?=$idioma["cancela_contrato"];?></h1></div>
  <form id="form_contrato_cancelar" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>" method="post" target="_parent" >
    <input name="acao" type="hidden" value="cancelar_contrato" />
    <input name="idmatricula_contrato" id="idmatricula_contrato_cancelar" type="hidden" value="<?= $_GET['r']; ?>" />
    <input name="situacao" id="situacao_cancelar" type="hidden" value="" />
    <?=$idioma["label_cancela_justificativa"];?>
    <br />
    <textarea class="span5" name="justificativa" id="justificativa"></textarea>
    <br />
    <br />
    <div class="row-fluid">
    <div class="span5 botao btn" id="contrato_cancelar_aprovar" style="height:80px;line-height: 72px;font-size: 18px;" onclick="contrato_cancelar_selecionarSituacao(2);"><?=$idioma["contrato_cancela"];?></div>
    </div>  
    <br /> 
  </form>     
</section>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-modal.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-scrollspy.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-popover.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-carousel.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-typeahead.js"></script>
<script src="/assets/js/mousetrap.min.js"></script>
<script src="/assets/js/construtor.js"></script>
<script>
  var regras = new Array();
  regras.push("required,justificativa,Campo justificativa é obrigatório");
		
  function contrato_cancelar_selecionarSituacao(situacao) {
	if(situacao == 1){
	  var confirma = confirm('<?=$idioma["confirma_nao_cancelar_contrato"];?>');
	} else if(situacao == 2) {
	  var confirma = confirm('<?=$idioma["confirma_cancelar_contrato"];?>');
	}
	if(confirma && validateFields(document.getElementById('form_contrato_cancelar'), regras)) {
	  document.getElementById('situacao_cancelar').value = situacao;
	  document.getElementById('form_contrato_cancelar').submit();
	} else {
	  return false;	
	}	  
  }
	
  function cancelarContrato(id,nome,situacaoatual) {
	document.getElementById('idmatricula_contrato_cancelar').value = id;
	document.getElementById('contrato_cancelar_nome').innerHTML = nome;
	  
	if(situacaoatual == 1){
	  // Nao aprovado
	  $("#contrato_cancelar_aprovar").removeClass("btn-danger");
	} else if(situacaoatual == 2) {
	  $("#contrato_cancelar_aprovar").addClass("btn-danger");
	}
	  
	return true;
  }
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
</body>
</html>