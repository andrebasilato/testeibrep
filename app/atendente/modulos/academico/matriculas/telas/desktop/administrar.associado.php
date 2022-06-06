<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
}
</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body style="min-width:500px;">
  <div class="span9">
    <section id="formulario_cpf">
      <? if(count($linha["erros"]) > 0){ ?>
        <div class="control-group">
          <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <? foreach($linha["erros"] as $ind => $val) { ?>
              <br />
              <?php echo $idioma[$val]; ?>
            <? } ?>
          </div>
        </div>
      <? } ?>
      <form method="get">
        <legend><?=$idioma["titulo"];?></legend>
        <div id="form_campo_cpf">
          <div class="control-group">
            <? if(count($busca["erros"]) > 0){ ?>
              <div class="control-group">
                <div class="row alert alert-error fade in" style="margin:0px;">
                  <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                  <strong><?= $idioma["busca_erros"]; ?></strong>
                  <? foreach($busca["erros"] as $val) { ?><br /><?= $idioma[$val]; ?><? } ?>
                </div>
              </div>
            <? } ?>

            <div class="control-group">
                <label class="control-label" for="documento_tipo"><strong><?= $idioma["associado_documento"]; ?></strong></label>
                <div class="controls">
                <select name="documento_tipo" id="documento_tipo" class="span2">
                    <option value=""></option>
                    <option value="cpf" <? if($_GET["cpf"]) echo 'selected'; ?>><?= $idioma["legenda_cpf"]; ?></option>
                    <option value="cnpj" <? if($_GET["cnpj"]) echo 'selected'; ?>><?= $idioma["legenda_cnpj"]; ?></option>
                </select>
                </div>
            </div>

            <div id="div_form_documento" class="control-group" <? if(!$_GET["cpf"]) echo 'style="display: none;"'; ?> >
              <label class="control-label" for="form_cpf"><strong><?= $idioma["associado_cpf"]; ?></strong></strong></label>
              <div class="controls">
                <input id="documento" class="span3 inputGrande" type="text" maxlength="14" name="cpf" <? if($_GET["cpf"] && count($busca["erros"]) == 0) { ?>readonly<? } ?> value="<?= $_GET["cpf"]; ?>">
                <? if($_GET["cpf"] && count($busca["erros"]) == 0) { ?><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>"><?= $idioma["associado_outro_cpf"]; ?></a><? } ?>
                </div>
            </div>
          </div>

          <div id="div_form_documento_cnpj" class="control-group" <? if(!$_GET["cnpj"]) echo 'style="display: none;"'; ?>>
            <div class="control-group">
                <label class="control-label" for="form_documento_cnpj"><strong><?= $idioma["associado_cnpj"]; ?></strong></label>
                <div class="controls">
                 <input id="documento" class="span3 inputGrande" type="text" maxlength="18" name="cnpj" <? if($_GET["cnpj"] && count($busca["erros"]) == 0) { ?>readonly<? } ?> value="<?= $_GET["cnpj"]; ?>">
                <? if($_GET["cnpj"] && count($busca["erros"]) == 0) { ?><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>"><?= $idioma["associado_outro_cnpj"]; ?></a><? } ?>
                </div>
            </div>
          </div>

          <?php if( (!$_GET["cpf"] && !$_GET["cnpj"] ) || count($busca["erros"]) > 0) { ?>
            <div class="control-group">
              <div class="controls">
                <input type="submit" class="btn" value="<?=$idioma["btn_buscar"];?>" />
              </div>
            </div>
          <?php } ?>
        </div>

      </form>
    </section>
    <? ?>

    <? if ( ($_GET["cpf"] || $_GET["cnpj"] )  && count($busca["erros"]) == 0) {?>
      <br />
      <br />
      <section id="formulario_cliente">
        <? if(count($linha["erros"]) > 0){ ?>
          <div class="control-group">
            <div class="row alert alert-error fade in" style="margin:0px;">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma["form_erros"]; ?></strong>
              <? foreach($linha["erros"] as $ind => $val) { ?>
                <br />
                <?php echo $idioma[$val]; ?>
              <? } ?>
            </div>
          </div>
        <? } ?>
        <? if(count($salvar["erros"]) > 0){ ?>
          <div class="control-group">
            <div class="row alert alert-error fade in" style="margin:0px;">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma["form_erros"]; ?></strong>
              <? foreach($salvar["erros"] as $ind => $val) { ?>
                <br />
                <?php echo $idioma[$val]; ?>
              <? } ?>
            </div>
          </div>
        <? } ?>


        <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/administrar" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal" target="_parent">
          <legend><?= $idioma["tipo_associacao"]; ?></legend>

            <?php
            /*
                ?>
                <div class="control-group">
                    <label class="control-label" for="idtipo"><strong><?=$idioma["form_tipo_associacao"];?></strong></label>
                    <div class="controls">
                        <select name="idtipo" id="idtipo" style="width:auto;">
                            <option value=""><?= $idioma["selecione_tipo_associacao"]; ?></option>
                            <?php foreach($tiposAssociacoes as $tipoAssociacao) { ?>
                                <option value="<?= $tipoAssociacao["idtipo"]; ?>"><?= $tipoAssociacao["nome"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php
            */
            ?>
          <?php
          if($associado["idpessoa"]) {
            $pessoaObj->GerarFormulario("formulario_pessoas",$associado,$idioma);
            echo '<input type="hidden" name="idpessoa" id="idpessoa" value="'.$associado["idpessoa"].'" />';
            foreach($config["banco_pessoas"]["campos_unicos"] as $campoid => $campo) {
            ?>
              <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $associado[$campo["campo_banco"]]; ?>" />
            <?
            }

          } else {
            $pessoaObj->GerarFormulario("formulario_pessoas",$_POST,$idioma);
             echo '<input type="hidden" name="documento" id="documento" value="'. (($_GET["cpf"]) ? $_GET["cpf"] : $_GET["cnpj"]) .'" />';
             echo '<input type="hidden" name="documento_tipo" id="documento_tipo" value="'. (($_GET["cpf"]) ? "cpf" : "cnpj") .'" />';
          }
          ?>
          <div class="form-actions">
            <input name="acao" type="hidden" value="adicionar_associado" />
            <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">
          </div>
        </form>
      </section>
    <? } ?>
  </div>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/js/construtor.js"></script>
<script type="text/javascript">
window.onload = function() {
  //$("#form_cpf").mask("999.999.999-99");
  $("#form_telefone").mask('(99) 9999-9999');
};



  var regras = new Array();
  regras.push("required,idtipo,<?php echo $idioma["tipo_associacao_vazio"]; ?>");
  <?php
  $config["formulario_pessoas"] = $pessoaObj->config['formulario_pessoas'];
  foreach($config["formulario_pessoas"] as $fieldsetid => $fieldset) {
    foreach($fieldset["campos"] as $campoid => $campo) {
      if(is_array($campo["validacao"])){
        foreach($campo["validacao"] as $tipo => $mensagem) {
          if($campo["id"] != "form_idpais"){
            if($campo["tipo"] == "file"){ ?>
              regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
            <? } else { ?>
              regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
            <?
            }
          } else {
          ?>
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
    foreach($config["formulario_pessoas"] as $fieldsetid => $fieldset) {
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
        if($campo["numerico"]){ ?>
          $("#<?= $campo["id"]; ?>").keypress(isNumber);
          $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
        <?
        }
        if($campo["decimal"]){ ?>
          $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});
        <?
        }
        if($campo["json"]){ ?>
          $('#<?=$campo["json_idpai"];?>').change(function(){
            if($(this).val()){
              $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
                var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
                for (var i = 0; i < json.length; i++) {
                  var selected = '';
                  if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                    var selected = 'selected';
                  options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                }
                $('#<?=$campo["id"];?>').html(options);
              });
            } else {
              $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
            }
          });
          <? if($_POST[$campo["valor"]]){ ?>
            $.getJSON('<?=$campo["json_url"];?><?=$_POST[$campo["json_idpai"]];?>', function(json){
              var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
              for (var i = 0; i < json.length; i++) {
                var selected = '';
                if(json[i].<?=$campo["valor"];?> == <?=intval($_POST[$campo["valor"]]);?>)
                  var selected = 'selected';
                options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
              }
              $('#<?=$campo["id"];?>').html(options);
            });
          <? } elseif($associado[$campo["valor"]]) { ?>
            $.getJSON('<?=$campo["json_url"];?><?=$associado[$campo["json_idpai"]];?>', function(json){
              var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
              for (var i = 0; i < json.length; i++) {
                var selected = '';
                if(json[i].<?=$campo["valor"];?> == <?=intval($associado[$campo["valor"]]);?>)
                  var selected = 'selected';
                options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
              }
              $('#<?=$campo["id"];?>').html(options);
            });
          <? } ?>
        <?
        }
      }
    }
    ?>
  });
  </script>
  <script type="text/javascript">
    function buscarCEP(cep_informado){
      //exibeLoading();
      $.msg({
        autoUnblock : false,
        clickUnblock : false,
        klass : 'white-on-black',
        content: 'Processando solicitação.',
        afterBlock : function(){
        var self = this;
        jQuery.ajax({
          url: "/api/get/cep",
          dataType: "json", //Tipo de Retorno
          type: "POST",
          data: {cep: cep_informado},
          success: function(json){ //Se ocorrer tudo certo
            if(json.sucesso){
              $("select[name='idlogradouro']").val(json.idlogradouro);
              $("input[name='endereco']").val(json.endereco)
              $("input[name='bairro']").val(json.bairro)
              $("select[name='idestado']").val(json.idestado);
              <?php
              foreach($config["formulario_pessoas"] as $fieldsetid => $fieldset) {
                foreach($fieldset["campos"] as $campoid => $campo) {
                  if($campo["json"] && $campo["nome"] == "idcidade"){ ?>
                    $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>',{<?=$campo["json_idpai"];?> : json.idestado, ajax: 'true'}, function(jsonCidade){
                      var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
                      for (var i = 0; i < jsonCidade.length; i++) {
                        var selected = '';
                        if(jsonCidade[i].<?=$campo["valor"];?> == json.idcidade)
                          var selected = 'selected';
                        options += '<option value="' + jsonCidade[i].<?=$campo["valor"];?> + '" '+ selected +'>' + jsonCidade[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                      }
                      $('#<?=$campo["id"];?>').html(options);
                    });
                  <?php
                  }
                }
              }
              ?>
              self.unblock();
            } else {
              alert('<?= $idioma["json_erro"]; ?>');
              self.unblock();
            }
          }
        });
      }
    });
  }

  $(document).ready(function(){
    $("input[name='cep']").blur(function() {
      buscarCEP($("input[name='cep']").val());
    });
  });
  </script>
  <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#form_idpais").fcbkcomplete({
        width: 350,
        width_options: 400,
        width_dialog: 400,
        json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/nacionalidade",
        addontab: true,
        height: 10,
        maxshownitems: 10,
        cache: true,
        maxitems: 1,
        filter_selected: true,
        firstselected: true,
        complete_text: "<?= $idioma["mensagem_select"]; ?>",
        addoncomma: true
      });
      if(<?=intval($associado["idpais"])?> > 0)
        $("#form_idpais").trigger("addItem",[{"title": "<?=$associado["pais"]?>", "value": "<?=$associado["idpais"]?>"}]);

      $('.maininput').attr("id","form_idpais3");
    });
  </script>

  <script>
    document.getElementById("documento_tipo").onchange = function(){
        document.getElementById("div_form_documento").style.display =  (this.value == "cpf") ?  "block" : "none";
        document.getElementById("div_form_documento_cnpj").style.display = (this.value == "cnpj") ?  "block" : "none";
    };
  </script>
</body>
</html>