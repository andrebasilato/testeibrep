    <h2 class="tituloEdicao"><?= $page["nome"]; ?></h2>
    <div class="tabbable tabs-left">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_editar">
<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
          <? if(count($salvar["erros"]) > 0){ ?>
            <div class="alert alert-error fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma["form_erros"]; ?></strong>
              <? foreach($salvar["erros"] as $ind => $val) { ?>
                <br />
                <?php echo $idioma[$val]; ?>
              <? } ?>
            </div>
          <? } ?>
          <form method="post" action="" class="form-horizontal">
            <input name="acao" type="hidden" value="remover" />
            <div class="control-group">
            <p>
              <? printf($idioma["usuario_selecionado"],$page["nome"], $page['idhistorico_escolar_paginas']); ?>
              <br />
              <br />
              <?= $idioma["informacoes"]; ?> <br />
            </p>
            <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
              <div class="controls">
                <label class="checkbox">
                  <input name="remover" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="remover">
                  <?= $idioma["confirmacao_formulario"]; ?>
                </label>
                <p class="help-block"><?= $idioma["nota"]; ?></p>
              </div>
            </div>
            <div class="form-actions">
              <a type="submit" id="enviar" href="<?php echo Request::url('0-3', '/') . $page['idhistorico_escolar'] .'/removerpagina/id/'.Request::url('4'); ?>/true" class="btn btn-primary" style="color: #FFFFFF"><?= $idioma["remover"]; ?>	</a>&nbsp;
              <button type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $page["idhistorico_escolar"]; ?>    /paginas');"><?= $idioma["cancelar"]; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
	document.getElementById('enviar').onclick = function() {
		if (! document.getElementById('remover').checked) {
			window.alert('Para remover é necessário marcar a mensagem de confirmação.');
			return false;
		}
		document.parent.location.href = '<?php echo Request::url('0-3', '/') . $page['idhistorico_escolar'] .'/removerpagina/id/' . Request::url('4'); ?>';
		return true;
	};
	</script>
  </div>
</div>
