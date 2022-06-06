<!DOCTYPE html>
<html>
<head>
  <?php incluirLib("head",$config,$usuario); ?>
 <link rel="stylesheet" type="text/css" href="/assets/js/select/bootstrap-select.min.css">
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
                <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
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
                <form method="post" onsubmit="return validateFields(this, regras)" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar_video" />
                  <? if($url[6] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco_videos"]["primaria"].'" id="'.$config["banco_videos"]["primaria"].'" value="'.$linha[$config["banco_videos"]["primaria"]].'" />';
                    foreach($config["banco_videos"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <?
                    }
                    $linhaObj->GerarFormulario("formulario_videos",$linha,$idioma);
                  } else {
                    $linhaObj->GerarFormulario("formulario_videos",$_POST,$idioma);
                  }
                  ?>
                  <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="history.back()" value="<?= $idioma["btn_cancelar"]; ?>" />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/js/ajax.js"></script>
    <script type="text/javascript">
      var regras = new Array();
      <?php
      foreach($config["formulario_videos"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
			  if($campo["tipo"] == "file"){ ?>
				regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			  <? } else { ?>
				regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
			  <?
			  }
            }
          }
        }
      }
      ?>
	  function deletaArquivo(div, obj) {
  		if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
  		  solicita(div, obj);
  		}
	  }
    </script>
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
  <script src="/assets/js/select/bootstrap-select.min.js"></script>
  <!-- // <script src="/assets/js/select/bootstrap-select.js"></script> -->
    <!-- 2.3.2
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.js"></script>
    -->
    <script type="text/javascript">
        $(document).on('ready', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            $('.selectpicker').change(function() {
              if ('' != $(this).val()) {

                $.post(
                  '/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/api/lista-de-videos',
                  {"pasta": $(this).val()},
                  function(result) {
                    var list = JSON.parse(result);

                    if (list.length > 0) {
                      for(i = 0; i < list.length; i++) {
                        $('.selectVideo').append('<option value="' + list[i].idvideo + '">' + list[i].titulo +' </option>');
                      }
                      $('.selectVideo').removeAttr('disabled');
                    }
                    console.log(list.length);
                });
                // Requisiçao ajax akiew :

              }
            });
        });
    </script>

  </div>
  <script type="text/javascript">
    $(window).load(function(){
        console.log($('body').height());
        $('body').css('height', $('body').height() + 'px');
        if($('body').height() > 100)
          parent.document.getElementById('videoIframe').height = $('body').height() + 'px';
    })
  </script>
</body>
</html>