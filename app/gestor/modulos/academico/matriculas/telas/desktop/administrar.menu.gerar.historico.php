<div id="historicomatricula" style="">
  <form target="_blank" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/historico_escolar" method="post" onsubmit="jQuery(document).trigger('close.facebox');">
	Data do histórico :<br />
	<input class="span2 data_historico" name="data_historico" /><br /><br />
	<input class="btn btn-primary" type="submit" value="Gerar" id="botao_historico_form" />
  </form>
  <script>
	  $(".data_historico").mask("99/99/9999");
	  $(".data_historico").datepicker($.datepicker.regional["pt-BR"]);
  </script>
</div>
