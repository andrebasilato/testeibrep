<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <? if($url[3] == "cadastrar") { ?>
          <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
          <li class="active"><?php echo $linha["razao_social"]; ?></li>
        <? } ?>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <?php if($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome_fantasia"]; ?> <small>(<?= $linha['email'] ?>)</small></h2><?php } ?>
          <div class="tabbable tabs-left">
            <?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php if($url[3] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?>
                </h2>
                <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">??</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>
                <? if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">??</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                <? } ?>
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar" />
                  <? if($url[4] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco"]["primaria"].'" id="'.$config["banco"]["primaria"].'" value="'.$linha[$config["banco"]["primaria"]].'" />';
                    foreach($config["banco"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <?
                    }
                    $linhaObj->GerarFormulario("formulario",$linha,$idioma);
                  } else {
                    unset($config["formulario"][0]["campos"][2]["evento"]);
                    $config["formulario"][0]["campos"][3]["evento"] = "maxlength='14'";
                    $config["formulario"][0]["campos"][4]["evento"] = "maxlength='18'";
                    $linhaObj->Set("config",$config);
                    $linhaObj->GerarFormulario("formulario",$_POST,$idioma);
                  }
                  ?>
                  <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
    </div>
    <script src="/assets/js/ajax.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
    <link rel="stylesheet" href="/assets/plugins/password_force/style.css" type="text/css" media="screen" charset="utf-8" />

    <script type="text/javascript">
      var regras = new Array();
      <?php
      foreach($config["formulario"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
              if($campo["tipo"] == "file"){
              ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? } else { ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? }
            }
          }
        }
      }
      ?>
      jQuery(function($) {
        <?php
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
                                alert("\"" + valordata + "\" n??o ?? uma data v??lida!!!");
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
            if($campo["datepicker"]) { ?>
              $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
            <?
            }
            if($campo["numerico"]) {
            ?>
              $("#<?= $campo["id"]; ?>").keypress(isNumber);
              $("#<?= $campo["id"]; ?>").blur(isNumberCopy);
            <?
            }
            if($campo["decimal"]) {
            ?>
              $("#<?= $campo["id"]; ?>").maskMoney({symbol:"R$",decimal:",",thousands:"."});
            <?
            }
            if($campo["json"]){ ?>
              $('#<?= $campo["json_idpai"]; ?>').change(function(){
                if($(this).val()){
                  $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
                    var options = '<option value="">??? <?=$idioma[$campo["json_input_vazio"]]; ?> ???</option>';
                    for (var i = 0; i < json.length; i++) {
                      var selected = '';
                      if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                        var selected = 'selected';
                      options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                    }
                    $('#<?=$campo["id"];?>').html(options);
                  });
                } else {
                  $('#<?=$campo["id"];?>').html('<option value="">??? <?=$idioma[$campo["json_input_pai_vazio"]]; ?> ???</option>');
                }
              });
              $('#<?= $campo["json_idpai"]; ?>').change();
            <?php
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
        } ?>
      });
    </script>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
    <script type="text/javascript">
        function buscarCEP(cep_informado, onde){
            $.msg({
                autoUnblock : true,
                clickUnblock : false,
                klass : 'white-on-black',
                content: 'Processando solicita????o.',
                afterBlock : function(){
                    var self = this;
                    jQuery.ajax({
                        url: "/api/get/cep",
                        dataType: "json",
                        type: "POST",
                        data: {cep: cep_informado},
                        success: function(json){
                            if (json.sucesso) {
                                if (onde == 'escola') {
                                    $("select[name='idlogradouro']").val(json.idlogradouro);
                                    $("input[name='endereco']").val(json.endereco)
                                    $("input[name='bairro']").val(json.bairro)
                                    $("select[name='idestado']").val(json.idestado);
                                } else if (onde == 'gerente') {
                                    $("select[name='gerente_idlogradouro']").val(json.idlogradouro);
                                    $("input[name='gerente_endereco']").val(json.endereco)
                                    $("input[name='gerente_bairro']").val(json.bairro)
                                    $("select[name='gerente_idestado']").val(json.idestado);
                                } else if (onde == 'responsavel_legal') {
                                    $("select[name='responsavel_legal_idlogradouro']").val(json.idlogradouro);
                                    $("input[name='responsavel_legal_endereco']").val(json.endereco)
                                    $("input[name='responsavel_legal_bairro']").val(json.bairro)
                                    $("select[name='responsavel_legal_idestado']").val(json.idestado);
                                }

                                if (onde == 'escola') {
                                    <?php
                                    foreach ($config["formulario"] as $fieldsetid => $fieldset) {
                                        foreach ($fieldset["campos"] as $campoid => $campo) {
                                            if ($campo['json'] && ($campo['nome'] == 'idcidade')) {
                                                ?>
                                                $.getJSON(
                                                    '<?= $campo['json_url']; ?>',
                                                    {
                                                        <?= $campo['json_idpai']; ?> : json.idestado,
                                                        ajax: 'true'
                                                    },
                                                    function(jsonCidade)
                                                    {
                                                        var options = '<option value="">- <?= $idioma[$campo['json_input_vazio']]; ?> -</option>';
                                                        for (var i = 0; i < jsonCidade.length; i++) {
                                                            var selected = '';
                                                            if(jsonCidade[i].<?= $campo['valor']; ?> == json.idcidade)
                                                            var selected = 'selected';
                                                            options += '<option value="' + jsonCidade[i].<?= $campo["valor"]; ?> + '" '+ selected +'>' + jsonCidade[i].<?= $campo["json_campo_exibir"]; ?> + '</option>';
                                                        }
                                                        $('#<?=$campo["id"];?>').html(options);
                                                    });
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                } else if (onde == 'gerente') {
                                    <?php
                                    foreach ($config["formulario"] as $fieldsetid => $fieldset) {
                                        foreach ($fieldset["campos"] as $campoid => $campo) {
                                            if ($campo['json'] && $campo['nome'] == 'gerente_idcidade') {
                                                ?>
                                                $.getJSON(
                                                    '<?= $campo['json_url']; ?>',
                                                    {
                                                        <?= $campo['json_idpai']; ?> : json.idestado,
                                                        ajax: 'true'
                                                    },
                                                    function(jsonCidade)
                                                    {
                                                        var options = '<option value="">- <?= $idioma[$campo['json_input_vazio']]; ?> -</option>';
                                                        for (var i = 0; i < jsonCidade.length; i++) {
                                                            var selected = '';
                                                            if(jsonCidade[i].<?= $campo['valor']; ?> == json.idcidade)
                                                            var selected = 'selected';
                                                            options += '<option value="' + jsonCidade[i].<?= $campo["valor"]; ?> + '" '+ selected +'>' + jsonCidade[i].<?= $campo["json_campo_exibir"]; ?> + '</option>';
                                                        }
                                                        $('#<?=$campo["id"];?>').html(options);
                                                    });
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                } else if (onde == 'responsavel_legal') {
                                    <?php
                                    foreach ($config["formulario"] as $fieldsetid => $fieldset) {
                                        foreach ($fieldset["campos"] as $campoid => $campo) {
                                            if ($campo['json'] && $campo['nome'] == 'responsavel_legal_idcidade') {
                                                ?>
                                                $.getJSON(
                                                    '<?= $campo['json_url']; ?>',
                                                    {
                                                        <?= $campo['json_idpai']; ?> : json.idestado,
                                                        ajax: 'true'
                                                    },
                                                    function(jsonCidade)
                                                    {
                                                        var options = '<option value="">- <?= $idioma[$campo['json_input_vazio']]; ?> -</option>';
                                                        for (var i = 0; i < jsonCidade.length; i++) {
                                                            var selected = '';
                                                            if(jsonCidade[i].<?= $campo['valor']; ?> == json.idcidade)
                                                            var selected = 'selected';
                                                            options += '<option value="' + jsonCidade[i].<?= $campo["valor"]; ?> + '" '+ selected +'>' + jsonCidade[i].<?= $campo["json_campo_exibir"]; ?> + '</option>';
                                                        }
                                                        $('#<?=$campo["id"];?>').html(options);
                                                    });
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                }

                                self.unblock();
                            } else {
                                alert('<?= $idioma['cep_nao_encontrado']; ?>');
                                self.unblock();
                            }
                        }
                    });
                }
            });
        }

        $(document).ready(function(){
            $("input[name='cep']").blur(function() {
                buscarCEP($("input[name='cep']").val(), 'escola');
            });

            $("input[name='gerente_cep']").blur(function() {
                buscarCEP($("input[name='gerente_cep']").val(), 'gerente');
            });

            $("input[name='responsavel_legal_cep']").blur(function() {
                buscarCEP($("input[name='responsavel_legal_cep']").val(), 'responsavel_legal');
            });
            
            $("#form_max_parcelas").blur(function() {
                if($(this).val() == 0){
                    $(this).val('');
                }
            });

            if ($("#form_max_parcelas").val() == 0){
                $("#form_max_parcelas").val('');
            }

            $("#form_max_boletos").blur(function() {
                if($(this).val() == 0){
                    $(this).val('');
                }
            });

            if ($("#form_max_boletos").val() == 0){
                $("#form_max_boletos").val('');
            }
        });
  
        function deletaArquivo(div, obj) {
            if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
                solicita(div, obj);       
            }
        }

        function aplicarRegrasFastConnect() {
            valorCampo = $('#form_fastconnect').val();

            //IN??CIO REGRAS QUE IR??O INSERIR/REMOVER
            var regrasInserirRemover = new Array();
            regrasInserirRemover.push('required,form_fastconnect_client_code,<?= $idioma['fastconnect_client_code_vazio'] ?>');
            regrasInserirRemover.push('required,form_fastconnect_client_key,<?= $idioma['fastconnect_client_key_vazio'] ?>');
            //FIM REGRAS QUE IR??O INSERIR/REMOVER

            //IN??CIO CAMPOS QUE IR??O MOSTRAR/OCULTAR
            var camposMotrarOcultar = new Array();
            camposMotrarOcultar.push('form_fastconnect_client_code');
            camposMotrarOcultar.push('form_fastconnect_client_key');
            //FIM CAMPOS QUE IR??O MOSTRAR/OCULTAR

            if (valorCampo == 'S') {
                for (var i = 0; i < camposMotrarOcultar.length; i++) {
                    //Exibir os campos
                    $('#div_' + camposMotrarOcultar[i]).show("fast");
                }
              
                posicaoInserir = regras.indexOf('required,form_fastconnect,<?= $idioma['fastconnect_vazio'] ?>');

                for (var i = regrasInserirRemover.length-1; i >= 0; i--) {
                    //Se n??o tiver no array ir?? inserir
                    if (regras.indexOf(regrasInserirRemover[i]) < 0) {
                        regras.splice(posicaoInserir+1, 0, regrasInserirRemover[i]);
                    }
                }
            } else {
                for (var i = 0; i < camposMotrarOcultar.length; i++) {
                    //Ocultar os campos
                    $('#div_' + camposMotrarOcultar[i]).hide("fast");
                    $('#' + camposMotrarOcultar[i]).val("");
                }
              
                for (var i = 0; i < regrasInserirRemover.length; i++) {
                    //Ir?? remover do array de regras
                    regraCampo = regras.indexOf(regrasInserirRemover[i]);
                    if (regraCampo >= 0) {
                        regras.splice(regraCampo, 1);
                    }
                }
            }
        }
          
        $('#form_fastconnect').change(function() {
            aplicarRegrasFastConnect();
        });
        aplicarRegrasFastConnect();

        function aplicarRegrasEstado() {
            valorCampo = $('#idestado').val();

            var estadosDetran = new Array("<?= implode('","', $estadosDetran); ?>");

            //IN??CIO CAMPOS QUE IR??O MOSTRAR/OCULTAR
            var camposMotrarOcultar = new Array();
            camposMotrarOcultar.push('form_detran_codigo');
            //FIM CAMPOS QUE IR??O MOSTRAR/OCULTAR

            if (estadosDetran.indexOf(valorCampo) >= 0) {
                for (var i = 0; i < camposMotrarOcultar.length; i++) {
                    //Exibir os campos
                    $('#div_' + camposMotrarOcultar[i]).show("fast");
                }

                $('#fieldset_dados_detran').show("fast");
            } else {
                for (var i = 0; i < camposMotrarOcultar.length; i++) {
                    //Ocultar os campos
                    $('#div_' + camposMotrarOcultar[i]).hide("fast");
                    $('#' + camposMotrarOcultar[i]).val("");
                }

                $('#fieldset_dados_detran').hide("fast");
            }
        }
          
        $('#idestado').change(function() {
            aplicarRegrasEstado();
        });
        aplicarRegrasEstado();
    </script>
</body>
</html>
