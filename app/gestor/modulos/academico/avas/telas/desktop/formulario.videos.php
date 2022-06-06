<!DOCTYPE html>
<html>
<head>
  <?php incluirLib("head",$config,$usuario); ?>
 <link href="/assets/css/menuVertical.css" rel="stylesheet" />
 <link rel="stylesheet" type="text/css" href="/assets/js/select/bootstrap-select.min.css">
 </head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? if($url[5] == "cadastrar") { echo $linha["nome"]; } else { echo $linha["ava"]; } ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/videos"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <? if($url[5] == "cadastrar") { ?>
          <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
          <li class="active"><?php echo $linha["nome"]; ?></li>
        <? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_submenu",$config,$linha); ?>
            <div class="ava-conteudo">
              <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
              <?php if($url[5] != "cadastrar") { ?>
                <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
                <?php include("inc_submenu_videos.php"); ?>
              <?php } ?>
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php if($url[5] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
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
                    <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["btn_cancelar"]; ?>" />
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
</body>
</html>