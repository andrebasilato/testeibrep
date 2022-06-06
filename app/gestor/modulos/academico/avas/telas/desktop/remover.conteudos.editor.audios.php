<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
  <style type="text/css">
    body {
      min-width: 0px !important;
      max-width: 952px !important;
      padding-top: 0px !important;
      height: 100%;
    }
    .container-fluid {
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
    .container-fluid, body {
      min-width: 0px !important;
    }
    .row-fluid>.span12 {
      max-width: 952px !important;
      min-width: 0px !important;
    }
    .box-conteudo {
      border-color: transparent;
    }
    body {
      background-color: transparent;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <div class="ava-conteudo"> 
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_remover"]; ?></h2>
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
                  <input name="acao" type="hidden" value="remover_audio" />
                  <div class="control-group">
                    <p> 
                      <br />
                      <? printf($idioma["usuario_selecionado"],$linha["nome"]); ?>
                      <br />
                      <br />
                      <?= $idioma["informacoes"]; ?>
                      <br />
                    </p>                            
                    <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="remover" value="<?= $linha[$config["banco_audios"]["primaria"]]; ?>" type="checkbox" id="remover">
                        <?= $idioma["confirmacao_formulario"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota"]; ?></p>
                    </div>
                    <div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["remover"]; ?>">&nbsp;
                      <input type="reset" class="btn" onclick="history.back()" value="<?= $idioma["cancelar"]; ?>" />
                    </div>
                  </div>					  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
  </div>
  <script type="text/javascript">
    $(window).load(function(){
        console.log($('body').height());
        $('body').css('height', $('body').height() + 'px');
        if($('body').height() > 100)
          parent.document.getElementById('audioIframe').height = $('body').height() + 'px';
    })
  </script>
</body>
</html>