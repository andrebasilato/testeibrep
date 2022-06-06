<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
  <style type="text/css"> 
    legend {
      font-size: 10px;
    }
  </style>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>        
      <li class="active"><?= $idioma["nav_novamatricula"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:35px;">
        <?php //INTERRUPÇÃO #174911 - ITEM (Informações Gerais) NO MENU INDICADOR DE NAVEGAÇÃO ?>
        <ul id="navegacao_passos" style="margin-bottom:20px;">
          <li class="frist fdone"><?= $idioma["nav_oferta_curso_escola"]; ?></li>
          <li class="inprogress"><?= $idioma["nav_aluno"]; ?><span></span></li>
          <li><?= $idioma["nav_vendedor"]; ?><span></span></li>
          <li><?= $idioma["nav_informacoes"]; ?><span></span></li>
          <li class="last"><?= $idioma["nav_concluida"]; ?><span></span></li>
        </ul>
        <div class="row-fluid">
          <div class="span3">
            <section id="empreendimento" class="well">                
              <legend><?= $idioma["label_oferta"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["oferta"]; ?></h2> 
              <legend><?= $idioma["label_curso"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["curso"]; ?></h2>
              <legend><?= $idioma["label_escola"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["nome_fantasia"]; ?></h2>
              <legend><?= $idioma["label_turma"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $turma["nome"]; ?></h2> 
            </section>     
          </div>
          <div class="span9">
            <section id="formulario_cpf"> 
              <? if(count($matricula["erros"])) { ?>
                <div class="control-group">
                  <div class="row alert alert-error fade in" style="margin:0px;">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($matricula["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                </div> 
              <? } ?>
              <form method="get" onsubmit="return formulario_documentos(this, regras_documentos)">
                <legend><?= $idioma["form_aluno_label"]; ?></legend>
                <div class="control-group">
                  <label class="control-label" for="form_nome"><strong><?=$idioma["form_aluno_cpf"];?></strong></label>
                    <div class="controls">
                      <input id="form_cpf" class="span3 inputGrande" type="text" maxlength="14" name="cpf"<? if($_GET["cpf"] && !count($matricula["erros"])) { ?> readonly="readonly"<? } ?> value="<?= $_GET["cpf"]; ?>">
                      <? if($_GET["cpf"] && !count($matricula["erros"]) > 0) { ?>  &nbsp;&nbsp;&nbsp;<a href="/<?= $url["0"]; ?>/<?= $url["1"]; ?>/<?= $url["2"]; ?>/<?= $url["3"]; ?>/<?= $url["4"]; ?>/<?= $url["5"]; ?>/<?= $url["6"]; ?>/<?= $url["7"]; ?>/aluno"><?=$idioma["form_aluno_outro_cpf"];?></a> <? } ?>
                    </div>
                  </div>
                  <? if(!$_GET["cpf"] || count($matricula["erros"]) > 0) { ?>  
                    <div class="control-group">
                      <div class="controls">
                        <input type="submit" class="btn" value="<?=$idioma["btn_buscar"];?>" />
                      </div>
                    </div> 
                  <? } ?>       
                </form> 
              </section> 
              <? if(($_GET["cpf"]) && (!count($matricula["erros"]))) { ?>              
                <section id="formulario_cliente">          
                  <? if(count($salvar["erros"]) > 0){ ?>
                    <div class="control-group">
                      <div class="row alert alert-error fade in" style="margin:0px;">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                        <strong><?= $idioma["form_erros"]; ?></strong>
                        <? foreach($salvar["erros"] as $ind => $val) { ?><br /><?php echo $idioma[$val]; ?><? } ?>
                      </div>
                    </div> 
                  <? } ?>        
                  <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url["7"]; ?>/vendedor" onsubmit="return valida_form(this, regras)" enctype="multipart/form-data" class="form-horizontal">            
                    <? 
                    if($pessoa["idpessoa"]) {
                      echo '<input type="hidden" name="idpessoa" id="idpessoa" value="'.$pessoa["idpessoa"].'" />';
                      echo '<input type="hidden" name="documento" id="documento" value="'.$pessoa["documento"].'" />';
                      foreach($config["banco_pessoas"]["campos_unicos"] as $campoid => $campo) {
                      ?>
                        <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $pessoa[$campo["campo_banco"]]; ?>" />
                      <? 
                      }                   
                      $matriculaObj->GerarFormulario("formulario_pessoas",$pessoa,$idioma);             
                    } else {
                      echo '<input type="hidden" name="documento" id="documento" value="'.$_GET["cpf"].'" />';
                      $matriculaObj->GerarFormulario("formulario_pessoas",$pessoa,$idioma);
                    }
                    ?>            
                    <div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_continuar"]; ?>">
                    </div>
                  </form>
                </section> 
              <? } ?>  
            </div>
          </div>
          <div class="clearfix"></div>                                  
        </div>
      </div>
    </div>
  <? incluirLib("rodape",$config,$usuario); ?>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>  
  <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
  <script type="text/javascript">
    //$("#form_cpf").mask("999.999.999-99");
    
    var regras_documentos = new Array();

    $("#form_cpf").keypress(isNumber);
    $("#form_cpf").blur(isNumberCopy);
    
    function formulario_documentos(form, array_regras) {
      if (form.form_cpf.value) {
        regras_documentos.push("valida_cpf,form_cpf,<?= $idioma["cpf_invalido"] ?>");
      } else {
        alert("<?= $idioma["cpf_vazio"] ?>");
        return false;
      }     
      return validateFields(form, array_regras);
    }

    function valida_form(form, array_regras){
        <?php if($pessoa['idpessoa']) { ?>
            idpessoa = <?= $pessoa['idpessoa'] ?>;
        <? }else {?>
            idpessoa = -1;
        <? } ?>
        email = $('#form_email').val();
        var array_retorno = array_regras;
        if (email != ""){
            array_retorno = [];
            jQuery.ajax({
                type: "POST",
                data:{email:email, idpessoa:idpessoa},
                url: '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/validaemail',
                async: false,
                success: function(data) {
                    if(!data) {
                        array_regras.push("registered_email,form_email,<?php echo $idioma['email_cadastrado']; ?>");
                        array_retorno = array_regras;
                    }
                    else{
                        for (i = 0; i < array_regras.length; i++) {
                            if (array_regras[i] != "registered_email,form_email,<?php echo $idioma['email_cadastrado']; ?>") {
                                array_retorno[i] = array_regras[i];
                            }   
                        }
                    }
                    return false;
            }});
        }
        return validateFields(form, array_retorno);
    }

    var regras = new Array();       
    <?php
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
            }else{
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
          if($campo["datepicker"]){ ?>
            $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
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
                    if(json[i].<?=$campo["valor"];?> == <?=intval($pessoa[$campo["valor"]]);?>)
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
              <? }elseif($pessoa[$campo["valor"]]){ ?>
                $.getJSON('<?=$campo["json_url"];?><?=$pessoa[$campo["json_idpai"]];?>', function(json){
                  var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>'; 
                  for (var i = 0; i < json.length; i++) {
                    var selected = '';
                    if(json[i].<?=$campo["valor"];?> == <?=intval($pessoa[$campo["valor"]]);?>)
                      var selected = 'selected';
                    options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                  }
                  $('#<?=$campo["id"];?>').html(options);
                });
              <? } ?>                       
            <?
            }
            /*if ($campo["botao_hide"]){                    
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
            }*/
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
                  foreach($config["formulario_pessoas"] as $fieldsetid => $fieldset) {
                    foreach($fieldset["campos"] as $campoid => $campo) {
                      if($campo["json"] && $campo["nome"] == "idcidade"){ ?>
                        $.getJSON('<?=$campo["json_url"];?><?=$pessoa[$campo["json_idpai"]];?>',{<?=$campo["json_idpai"];?> : json.idestado, ajax: 'true'}, function(jsonCidade){
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
        if(<?=intval($pessoa["idpais"])?> > 0)
          $("#form_idpais").trigger("addItem",[{"title": "<?=$pessoa["pais"]?>", "value": "<?=$pessoa["idpais"]?>"}]);
        $('.maininput').attr("id","form_idpais3");              
        $("#form_idpais").trigger("addItem",[{"title": "Brasil", "value": "33"}]);  
      });
      function letrasMaiusculas(texto){
      texto.value = texto.value.toUpperCase();          
    }
    </script>
</div>
</body>
</html>
