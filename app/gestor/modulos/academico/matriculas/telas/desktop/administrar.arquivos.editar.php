<div id="historicomatricula" style="">
  <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>" method="post" onsubmit="jQuery(document).trigger('close.facebox');">
	<? if($idcontaflag){?>
    <input name="contaflag" type="hidden" value="contaflag" />
    <? }?> 
    <input name="acao" type="hidden" value="editar_arquivo" />
    <input name="idarquivo" type="hidden" value="<?php echo $documento['idarquivo']; ?>" />
    Protocolo:<br />
	<input class="span2 data_historico" name="protocolo" value="<?php echo $documento['protocolo']; ?>" /><br /><br />
	<input class="btn btn-primary" type="submit" value="Salvar" id="botao_historico_form" />
  </form>
</div>