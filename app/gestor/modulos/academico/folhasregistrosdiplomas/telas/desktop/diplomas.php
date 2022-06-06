<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
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
          <li class="active"><?php echo $idioma["nav_diplomas"]; ?></li>
        <? } ?>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <?php if($url[3] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
          <div class="tabbable tabs-left">
      <?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
        <h2 class="tituloOpcao"><?php echo $idioma["titulo_diplomas"];  ?></h2>

<div id="listagem_informacoes">Caso queira acrescentar matrículas, utilize o campo abaixo.<br />
  Você poderá digitar o nome de algum aluno ou número da matrícula para adiciona-ló a folha de registro atual.
</div>
<form class="form-inline wellCinza" method="post">
    <input type="hidden" id="acao" name="acao" value="associar_diploma">
    Matrículas:
    <select style="display: none !important" name="matriculas" id="matriculas" maxlength="200" class="span6"></select>
    <input type="submit" class="btn" value="Adicionar" />
</form>

<?php if ($linhaObj->getErrors()) { ?>
  <div class="alert alert-danger fade in">
    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
    <strong><?= sprintf($idioma['count_erros'], count($_SESSION['errors'])); ?></strong>
    <ul>
    <?php
    foreach ($_SESSION['errors'] as $error) {
        if ($error == 'limite_matriculas_excedido') {
            ?>
            <li><?= $idioma[$error]; ?></li>
            <?php
        } else {
            ?>
            <li><?= sprintf($idioma['matricula_error'], $error['idmatricula'], $error['aluno']); ?></li>
            <?php
        }
    }
    ?>
    </ul>
  </div>
  <?php unset($_SESSION['errors']) ?>
<? } ?>



        <? if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <? } ?>

                <?php $linhaObj->GerarTabela($diplomasArray,$_GET["q"],$idioma,"listagem_diplomas"); ?>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  <? incluirLib("rodape",$config,$usuario); ?>
       <script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#matriculas").fcbkcomplete({
        json_url: "<?php echo Request::url('0-4', '/'); ?>json/",
        addontab: true,
        height: 10,
        maxshownitems: 15,
        cache: false,
        maxitems: 10,
        input_min_size: 1,
        filter_selected: true,
        firstselected: true,
        complete_text: "Digite parte do nome da pessoa ou o número de matrícula...",
        addoncomma: true,
      });
      $("#matriculas").css('display', 'none');
    })




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
      jQuery(function($){
        <? foreach($config["formulario"] as $fieldsetid => $fieldset) {
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
              $('#<?=$campo["json_idpai"];?>').change(function(){
                if($(this).val()){
                  $.getJSON('<?=$campo["json_url"];?>',{<?=$campo["json_idpai"];?>: $(this).val(), ajax: 'true'}, function(json){
                    var options = '<option value="">– <?=$idioma[$campo["json_input_vazio"]]; ?> –</option>';
                    for (var i = 0; i < json.length; i++) {
                      var selected = '';
                      if(json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> == <?=intval($linha[$campo["valor"]]);?>)
                        var selected = 'selected';
                      options += '<option value="' + json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                    }
                    $('#<?=$campo["id"];?>').html(options);
                  });
                } else {
                  $('#<?=$campo["id"];?>').html('<option value="">– <?=$idioma[$campo["json_input_pai_vazio"]]; ?> –</option>');
                }
              });
              $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', function(json){
                var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';
                for (var i = 0; i < json.length; i++) {
                  var selected = '';
                  if(json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> == <?=intval($linha[$campo["valor"]]);?>)
                    var selected = 'selected';
                  options += '<option value="' + json[i].<?=($campo["sql_valor"] ? $campo["sql_valor"] : $campo["valor"]);?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                }
                $('#<?=$campo["id"];?>').html(options);
              });
            <?
            }
          }
        } ?>
      });
    </script>
  </div>
</body>
</html>