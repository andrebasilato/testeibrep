  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["objeto_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["idsolicitacao_declaracao"]; ?></strong></li>
  	</ul>

    <? if ($linha["situacao"] == "I") { ?>
        <div class="control-group" id="campos_cancelar">
            <label class="checkbox">
                <strong><?= $idioma['motivo_cancelamento']?></strong>
                <textarea id="motivo_cancelamento" name="motivo_cancelamento" disabled="disabled" class="span6"><?= $linha["motivo_cancelamento"]; ?></textarea>
            </label>
        </div>
        </br>
    <? } ?> 
  </section>