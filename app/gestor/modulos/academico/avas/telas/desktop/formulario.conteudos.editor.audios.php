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
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar_audio" />
                  <? if($url[6] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco_audios"]["primaria"].'" id="'.$config["banco_audios"]["primaria"].'" value="'.$linha[$config["banco_audios"]["primaria"]].'" />';
                    foreach($config["banco_audios"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <? 
                    }					  
                    $config["formulario_audios"][0]["campos"][2]["validacao"] = array("formato_arquivo" => "arquivo_invalido");
					$config["banco"] = $config["banco_audios"];
					$config["formulario"] = $config["formulario_audios"];
					$linhaObj->Set("config",$config);
					$linhaObj->GerarFormulario("formulario_audios",$linha,$idioma);				
                  } else {
                    $linhaObj->GerarFormulario("formulario_audios",$_POST,$idioma);
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
      foreach($config["formulario_audios"] as $fieldsetid => $fieldset) {
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
      jQuery(document).ready(function($) { 
        <?
        foreach($config["formulario_audios"] as $fieldsetid => $fieldset) {
          foreach($fieldset["campos"] as $campoid => $campo) {
            if($campo["mascara"]){ ?>
				<?php if($campo["mascara"] == "99/99/9999") { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
					$('#<?= $campo["id"]; ?>').change(function() {
						if($('#<?= $campo["id"]; ?>').val() != '') {
							valordata = $("#<?= $campo["id"]; ?>").val();
							date= valordata;
							ardt= new Array;
							ExpReg= new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
							ardt=date.split("/");
							erro=false;
							if ( date.search(ExpReg)==-1){
								erro = true;
							}
							else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
								erro = true;
							else if ( ardt[1]==2) {
								if ((ardt[0]>28)&&((ardt[2]%4)!=0))
									erro = true;
								if ((ardt[0]>29)&&((ardt[2]%4)==0))
									erro = true;
							}
							if (erro) {
								alert("\"" + valordata + "\" não é uma data válida!!!");
								$('#<?= $campo["id"]; ?>').focus();
								$("#<?= $campo["id"]; ?>").val('');
								return false;
							}
							return true;
						}
					});
				<?php } elseif($campo["mascara"] == "(99) 9999-9999" || $campo["mascara"] == "(99) 9999-9999?9") { ?>
					$('#<?= $campo["id"]; ?>').focusout(function(){
						var phone, element;
						element = $(this);
						element.unmask();
						phone = element.val().replace(/\D/g, '');
						if(phone.length > 10) {
							element.mask("(99) 99999-999?9");
						} else {
							element.mask("(99) 9999-9999?9");
						}
					}).trigger('focusout');
				<?php } else { ?>
					$("#<?= $campo["id"]; ?>").mask("<?= $campo["mascara"]; ?>");
				<?php } ?>
            <? 
            }
            if($campo["datepicker"]){ ?>
              $("#<?= $campo["id"]; ?>").datepicker($.datepicker.regional["pt-BR"]);
            <?
            }
            if($campo["numerico"]){ ?>
              $("#<?= $campo["id"]; ?>").keypress(isNumber);
              $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
            <?
            }
            if($campo["decimal"]){ ?>
              $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});	
            <?
            }									
          }
        }
        ?>
      });
	  function deletaArquivo(div, obj) {
		if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
		  solicita(div, obj);		
		}
	  }
    </script>
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