<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
   min-width: 700px !important;

}
</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" />
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div>
    <section id="formulario_cliente">
      <? if(count($aluno["erros"]) > 0){ ?>
        <div class="control-group">
          <div class="row alert alert-error fade in" style="margin:0px;">
            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
            <strong><?= $idioma["form_erros"]; ?></strong>
            <? foreach($aluno["erros"] as $ind => $val) { ?>
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
        <?
        if($aluno) {
          echo '<input type="hidden" name="idpessoa" id="idpessoa" value="'.$aluno["idpessoa"].'" />';
          foreach($config["banco_pessoas"]["campos_unicos"] as $campoid => $campo) {
          ?>
            <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $aluno[$campo["campo_banco"]]; ?>" />
          <?
          }
          $pessoaObj->GerarFormulario("formulario_pessoas",$aluno,$idioma);
        }
        ?>
        <div class="form-actions">
          <input name="acao" type="hidden" value="editar_dados_associado" />
          <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">
        </div>
      </form>
    </section>
  </div>
    <script src="/assets/js/validation.js"></script>
    <script src="/assets/js/ajax.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/js/jquery.maskMoney.js"></script>
    <script src="/assets/js/jquery.maskedinput_1.3.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
    <link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8" />
    <script type="text/javascript">
      $("#form_cpf").mask("999.999.999-99");
      var regras = new Array();
       <?php
      foreach($config["formulario"] as $fieldsetid => $fieldset) {
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
                regras.push("<?php echo $tipo; ?>,form_idpais3,<?php echo $idioma[$mensagem]; ?>");
                <?
              }
            }
          }
        }
      }
      ?>
      jQuery(document).ready(function($) {
        $(".verificaSenha").passStrength({userid: "#form_email"});
        <?
        //print_r2($config["formulario"]);exit;
        foreach($config["formulario"] as $fieldsetid => $fieldset) {
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
            if($campo["json"]){ ?>
              $('#<?=$campo["json_idpai"];?>').change(function(){
                if($(this).val()){
                  $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
                    var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
                    for (var i = 0; i < json.length; i++) {
                      var selected = '';
                      if(json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> == <?=intval($aluno[$campo["valor"]]);?>)
                        var selected = 'selected';
                      options += '<option value="' + json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                    }
                    $('#<?=$campo["id"];?>').html(options);
                  });
                } else {
                  $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                }
              });
              $.getJSON('<?=$campo["json_url"];?><?=$aluno[$campo["json_idpai"]];?>', function(json){
                var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
                for (var i = 0; i < json.length; i++) {
                  var selected = '';
                  if(json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> == <?=intval($aluno[$campo["valor"]]);?>)
                    var selected = 'selected';
                  options += '<option value="' + json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                }
                $('#<?=$campo["id"];?>').html(options);
              });
            <?
            }
            if ($campo["botao_hide"]){
              if ($campo['tipo'] == 'select') { ?>
                asd = new Array();
                var aux_d = $('#<?= $campo["id"]; ?>').attr('value');
                if (aux_d == 'cnpj'){
                  $('#<?= $campo["id"]; ?> option[value="cnpj"]').attr('selected','selected');
                  $('#div_form_<?= $campo["iddiv2"]; ?>').show();
                  for (var i = 0; i < regras.length; i++){
                    if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_vazio"] ?>')
                      regras.splice(i, 1);
                  }
                  for (var i = 0; i < regras.length; i++){
                    if(regras[i] == 'valida_cpf,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_invalido"] ?>')
                      regras.splice(i, 1);
                  }
                } else {
                  $('#<?= $campo["id"]; ?> option[value="cpf"]').attr('selected','selected');
                  $('#div_form_<?= $campo["iddiv"]; ?>').show();
                  for (var i = 0; i < regras.length; i++){
                    if(regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_vazio"] ?>')
                      regras.splice(i, 1);
                  }
                  for (var i = 0; i < regras.length; i++){
                    if(regras[i] == 'valida_cnpj,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_invalido"] ?>')
                      regras.splice(i, 1);
                  }
                }
                $('#<?= $campo["id"]; ?>').change(function() {
                  var contem = false;
                  var contem_v = false;
                  var remover = 0;
                  var remover_v = 0;
                  aux_d = $('#form_tipo').attr('value');
                  if (aux_d == 'cpf'){
                    $('#div_form_<?= $campo["iddiv"]; ?>').show("fast");
                    $('#div_form_<?= $campo["iddiv2"]; ?>').hide("fast");
                    $('#form_<?= $campo["iddiv2"]; ?>').attr("value","");
                    for (var i = 0; i < regras.length; i++){
                      if(regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_vazio"] ?>')
                        remover = i;
                      else if (regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_vazio"] ?>')
                        contem = true;
                      else if (regras[i] == 'valida_cpf,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_invalido"] ?>')
                        contem_v = true;
                    }
                    if (remover != 0)
                      regras.splice(remover, 1);
                    if (!contem)
                      regras.push("required,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_vazio"] ?>");
                    if (!contem_v)
                      regras.push("valida_cpf,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_invalido"] ?>");
                    for (var i = 0; i < regras.length; i++){
                      if(regras[i] == 'valida_cnpj,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_invalido"] ?>')
                        remover_v = i;
                    }
                    if (remover_v != 0)
                      regras.splice(remover_v, 1);
                  } if(aux_d == 'cnpj'){
                    $('#div_form_<?= $campo["iddiv2"]; ?>').show("fast");
                    $('#div_form_<?= $campo["iddiv"]; ?>').hide("fast");
                    $('#form_<?= $campo["iddiv"]; ?>').attr("value","");
                    for (var i = 0; i < regras.length; i++){
                      if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_vazio"] ?>')
                        remover = i;
                      else if (regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_vazio"] ?>')
                        contem = true;
                      else if (regras[i] == 'valida_cnpj,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_invalido"] ?>')
                        contem_v = true;
                    }
                    if (remover != 0)
                      regras.splice(remover, 1);
                    if (!contem)
                      regras.push("required,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_vazio"] ?>");
                    if (!contem_v)
                      regras.push("valida_cnpj,form_<?= $campo["iddiv2"]; ?>,<?= $idioma["cnpj_invalido"] ?>");
                    for (var i = 0; i < regras.length; i++){
                      if(regras[i] == 'valida_cpf,form_<?= $campo["iddiv"]; ?>,<?= $idioma["cpf_invalido"] ?>')
                        remover_v = i;
                    }
                    if (remover_v != 0)
                      regras.splice(remover_v, 1);
                    }
                  }
                );
              <?
              }
            }
          }
        }
        ?>
      });
    </script>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
    <script type="text/javascript">
      function buscarCEP(cep_informado){
        //exibeLoading();
        $.msg({
          autoUnblock : true,
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
                  foreach($config["formulario"] as $fieldsetid => $fieldset) {
                    foreach($fieldset["campos"] as $campoid => $campo) {
                      if($campo["json"] && $campo["nome"] == "idcidade"){ ?>
                        $.getJSON('<?=$campo["json_url"];?><?=$aluno[$campo["json_idpai"]];?>',{<?=$campo["json_idpai"];?> : json.idestado, ajax: 'true'}, function(jsonCidade){
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
          json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/nacionalidade",
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
        /*if(<?=intval($aluno["idpais"])?> > 0)
          $("#form_idpais").trigger("addItem",[{"title": "<?=$aluno["pais"]?>", "value": "<?=$aluno["idpais"]?>"}]);
        $('.maininput').attr("id","form_idpais3");*/

        if(<?=intval($aluno["idpais"])?> > 0)
            $("#form_idpais").trigger("addItem",[{"title": "<?=$aluno["pais"]?>", "value": "<?=$aluno["idpais"]?>"}]);

        $("#form_idpais").trigger("addItem",[{"title": "Brasil", "value": "33"}]);

        $('.maininput').attr("id","form_idpais3");
      });
      function deletaArquivo(div, obj) {
        if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
          solicita(div, obj);
        }
      }



    </script>
</body>
</html>