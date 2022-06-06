<section id="global">
  <div class="page-header">
      <h1><?php echo $idioma["titulo"]; ?></h1>
  </div>
  <br />
  <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data">
	  <?php echo $idioma['prioridade']; ?>
      <br />
      <select id="prioridade" name="prioridade">
      	<?php foreach ($prioridades[$config["idioma_padrao"]] as $ind => $prioridade) { ?>
            <option value="<?php echo $ind; ?>" <?php if ($ind == $linha["prioridade"]) echo 'selected="selected"'; ?> ><?php echo $prioridade; ?></option>
        <?php } ?>
      </select>
      <br />
      <?php echo $idioma['proxima_acao']; ?>  
      <br />
      <input id="proxima_acao" class="span2" type="text" value="<?php echo formataData($linha["proxima_acao"], "br", 0); ?>" name="proxima_acao" readonly="readonly" style="cursor: pointer;">
      <br />
      <input type="hidden" name="acao" id="acao" value="alterar_informacoes" />
      <input class="btn" type="submit" name="salvar" id="salvar" value="<?php echo $idioma['btn_salvar']; ?>" />  
  </form>
</section>
<script type="text/javascript">
	var regras = new Array();
	regras.push("required,prioridade,<?php echo $idioma["prioridade_vazio"]; ?>");
	regras.push("required,proxima_acao,<?php echo $idioma["proxima_acao_vazio"]; ?>");
	
	$(function() {
		$("#proxima_acao").datepicker({
			currentText: 'Now',
			dateFormat: 'dd/mm/yy',
			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
			dayNames: ['Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
			monthNames: ['Janeiro','Fevereiro','Marco','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			alignment: 'bottomLeft',
			buttonImageOnly: true,
			buttonImage: '/assets/img/calendar.png',
			showStatus: true
		})
	});
</script>