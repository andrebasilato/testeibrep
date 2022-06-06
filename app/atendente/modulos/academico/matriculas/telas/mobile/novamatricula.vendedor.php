<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
  <style type="text/css"> 
    legend {
      font-size: 10px;
    }
  </style>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>        
      <li class="active"><?= $idioma["nav_novamatricula"]; ?></li>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
      <div class="box-conteudo" style="padding:35px;">
        <div class="row-fluid">
          <div class="span12">
              
              <legend><?= $idioma["label_oferta"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["oferta"]; ?></h2> 
              <legend><?= $idioma["label_curso"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["curso"]; ?></h2>
              <legend><?= $idioma["label_escola"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $escola["nome_fantasia"]; ?></h2>
			  <legend><?= $idioma["label_turma"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $turma["nome"]; ?></h2>
              <legend><?= $idioma["label_cliente"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $_SESSION["matricula"]["pessoa"]["nome"]; ?> - <span style="color:#666666">(<?= $_SESSION["matricula"]["pessoa"]["documento"]; ?>)</span></h2> 
                        
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
              <?php if(count($vendedoresVisitas) > 0) { ?>
                <section id="formulario_cliente">  
                  <legend><?=$idioma["vendedores_visita"];?></legend>
                  <table border="0" cellspacing="0" cellpadding="5" class="table tabelaSemTamanho">
                    <thead>
                      <tr> 
                        <th><?= $idioma["tabela_vendedor"]; ?></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
					<? foreach($vendedoresVisitas as $vendedorVisita) { ?>  
                      <tr>
                        <td><strong><?= $vendedorVisita["nome"]; ?></strong></td>
                        <td><input type="submit" class="btn btn-mini" onclick="selecionarVendedor('<?= $vendedorVisita["idvendedor"]; ?>','<?= addslashes($vendedorVisita["nome"]); ?>');" value="<?= $idioma["selecionar_vendedor"]; ?>" /></td>
                      </tr>
                    <? } ?>
                  </table>
                <?php } ?>
              </section>
              <section id="formulario_cpf">  
                <form method="get">
                  <legend><?=$idioma["vendedor_label"];?></legend>
                  <div class="control-group">
                    <label class="control-label" for="form_nome"><strong><?=$idioma["vendedor_nome"];?></strong></label>
                    <div class="controls">
                      <input id="vendedor" class="span5 inputGrande" type="text" maxlength="60" name="vendedor" value="<?= $_GET["vendedor"]; ?>">
                    	<span class="help-block"><?=$idioma["vendedor_ajuda"];?></span>
                    </div>
                  </div> 
                  <div class="control-group">
                    <div class="controls">
                      <input type="submit" class="btn" value="<?=$idioma["btn_buscar"];?>" />
                    </div>
                  </div>        
                </form> 
              </section>  
			  <? if($_GET["vendedor"]) { ?>
                <section id="formulario_cliente">  
                  <table border="0" cellspacing="0" cellpadding="5" class="table tabelaSemTamanho">
                    <thead>
                      <tr> 
                        <th><?= $idioma["tabela_vendedor"]; ?></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
					<?php if(count($vendedores) > 0) { ?>
					  <? foreach($vendedores as $vendedor) { ?>  
                        <tr>
                          <td><strong><?= $vendedor["nome"]; ?></strong></td>
                          <td><input type="submit" class="btn btn-mini" onclick="selecionarVendedor('<?= $vendedor["idvendedor"]; ?>','<?= addslashes($vendedor["nome"]); ?>');" value="<?= $idioma["selecionar_vendedor"]; ?>" /></td>
                        </tr>
                      <? } ?>
                    <?php } else { ?>
                      <tr>
                        <td colspan="2"><?= $idioma["nenhuma_vendedor"]; ?></td>
                      </tr>
                    <?php } ?>
                  </table>
                </section> 
			  <? } ?> 
              <form method="post" id="form_idvendedor" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url[7]; ?>/financeiro">
                <input name="idvendedor" type="hidden" id="input_idvendedor" value="" />
              </form>
              <script>
                function confirmarVendedor(idvendedor) {
                  document.getElementById('input_idvendedor').value = idvendedor;
                  document.getElementById('form_idvendedor').submit();
                  return false;
                }
                function selecionarVendedor(idvendedor,nome) {
                  var msg = "<?= $idioma["confirma_selecao_vendedor"]; ?>";
                  msg = msg.replace("[[nome]]",nome);
                  var confirma = confirm(msg);
                  if(confirma){
                    confirmarVendedor(idvendedor);
                  } else {
                    return false;	
                  }
                }
              </script>
            </section> 
          </div>
        </div>
        <div class="clearfix"></div>                                  
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
  <script type="text/javascript">
	var regras = new Array();
	regras.push("required,idoferta,<?= $idioma["idoferta_vazio"]; ?>");
	regras.push("required,idoferta_curso,<?= $idioma["idoferta_curso_vazio"]; ?>");
	regras.push("required,idoferta_curso_escola,<?= $idioma["idoferta_curso_escola_vazio"]; ?>");
	
	jQuery(document).ready(function($) { 		
	  $('#idoferta').change(function(){
		$('#idoferta_curso').prop('disabled', true);
		$('#idoferta_curso_escola').prop('disabled', true);
		
		$('#idoferta_curso').html('<option value=""><?= $idioma["carregando"]; ?></option>');
		$('#idoferta_curso_escola').html('<option value=""><?= $idioma["carregando"]; ?></option>');
		if($(this).val()){
		  $.getJSON('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/json/oferta_curso',{ idoferta: $(this).val(), ajax: 'true'}, function(json){
			var options = '<option value=""><?= $idioma["selecione_oferta_curso"]; ?></option>';
			for (var i = 0; i < json.length; i++) {
			  options += '<option value="' + json[i].idoferta_curso + '">' + json[i].curso + '</option>';
			}	
			$('#idoferta_curso').html(options);
			$('#idoferta_curso_escola').html('<option value=""><?= $idioma["selecione_oferta_curso"]; ?></option>');
			
			$('#idoferta_curso').removeAttr('disabled');
		  });
		} else {
		  $('#idoferta_curso').html('<option value=""><?= $idioma["selecione_oferta"]; ?></option>');
		  $('#idoferta_curso_escola').html('<option value=""><?= $idioma["selecione_oferta"]; ?></option>');
		}
	  });
	  $('#idoferta_curso').change(function(){
		$('#idoferta_curso_escola').prop('disabled', true);
		
		$('#idoferta_curso_escola').html('<option value=""><?= $idioma["carregando"]; ?></option>');
		if($(this).val()){
		  $.getJSON('/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/json/oferta_curso_escola',{ idoferta_curso: $(this).val(), ajax: 'true'}, function(json){
			var options = '<option value=""><?= $idioma["selecione_oferta_curso_escola"]; ?></option>';
			for (var i = 0; i < json.length; i++) {
			  options += '<option value="' + json[i].idoferta_curso_escola + '">' + json[i].escola + '</option>';
			}	
			$('#idoferta_curso_escola').html(options);
			
			$('#idoferta_curso_escola').removeAttr('disabled');
		  });
		} else {
		  $('#idoferta_curso').html('<option value=""><?= $idioma["selecione_oferta_curso"]; ?></option>');
		  $('#idoferta_curso_escola').html('<option value=""><?= $idioma["selecione_oferta_curso"]; ?></option>');
		}
	  });
	});
  </script>
</div>
</body>
</html>