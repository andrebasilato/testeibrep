<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
<script type="text/javascript">
function selecionaTodos(id, checkbox, tipo) {
	var div = document.getElementById(id);
	var inputs = div.getElementsByTagName("input");
	var todos = document.getElementById(checkbox).checked;
	var marcar = false;
	if(todos) marcar = true;
	for (i = 0; i < inputs.length; i++) {
		if(inputs[i].type == 'checkbox' && !inputs[i].disabled && inputs[i].size == tipo) inputs[i].checked = marcar;
	}
}
function confirma_pagina_informacao() {
  var confirma = confirm('Você será redirecionado para outra página.\nDeseja continuar? ');
  if(confirma){
	return true;
  } else {
	return false;
  }
}
</script>
</head>
<body>
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
    	<h1><?php echo $idioma["pagina_titulo"]; ?> &nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
      </div>
      <ul class="breadcrumb">
    	<li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
    	<li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>    	
        <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a> </li>
		<li class="active"><span class="divider">/</span><?= $idioma["fila_envio"]; ?></li>
		<?php if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["nav_resetarbusca"]; ?></a></li><?php } ?>
    	<span class="pull-right" style="padding-top:3px; color:#999"><?php echo $idioma["hora_servidor"]; ?> <?php echo date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
          <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_fila"]; ?></a></div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?> </h2> 
           <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">                    		
              <div class="tab-pane active" id="tab_editar">
                <ul class="nav nav-pills">
                  <li <?php if (!$url[5]) { ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/fila"><?= $idioma["nav_fila"]; ?></a></li>
                  <li <?php if ($url[5]) { ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/add"><?= $idioma["nav_fila_add"]; ?></a></li>
                </ul>
				<div class="alert alert-success fade in">
                  <strong><?php echo $idioma['pesquisa_iniciada_auto']; ?></strong>
                </div>
                <?php if ($url[6]) { ?>
                  <ul class="nav nav-pills">
                    <li <?php $corIcone = "preto"; if ($url[6] == 'usuariosadm') { $corIcone = "branco"; ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/usuariosadm"><img src="/assets/icones/<?php echo $corIcone; ?>/16/usuarios_16.png" /> <?= $idioma["nav_fila_add_usuariosadm"]; ?></a></li>
                    <li <?php $corIcone = "preto"; if ($url[6] == 'professores') { $corIcone = "branco"; ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/professores"><img src="/assets/icones/<?php echo $corIcone; ?>/16/corretores_16.png" /> <?= $idioma["nav_fila_add_professores"]; ?></a></li>
                    <li <?php $corIcone = "preto"; if ($url[6] == 'pessoas') { $corIcone = "branco"; ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/pessoas"><img src="/assets/icones/<?php echo $corIcone; ?>/16/pessoas_16.png" /> <?= $idioma["nav_fila_add_pessoas"]; ?></a></li>
                    <li <?php $corIcone = "preto"; if ($url[6] == 'matriculas') { $corIcone = "branco"; ?> class="active" <?php } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/matriculas"><img src="/assets/icones/<?php echo $corIcone; ?>/16/reservas_16.png" /> <?= $idioma["nav_fila_add_matriculas"]; ?></a></li>					
                  </ul>
				<?php } ?>
                <?php if($_POST["msg"]) { ?>
                  <div class="alert alert-success fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
                  </div>
                <?php } ?>
                <?php if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <?php foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val].$val; ?>
                    <?php } ?>
                  </div>
                <?php 
				} 
				if($url[5] && !$url[6]) { ?>
                  <div class="row-fluid" style="padding-bottom:20px;">
                    <a class="span3 btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/usuariosadm">
                        <img src="/assets/icones/preto/32/usuarios_32.png" />
                        <br />
                        <br />
                        <strong style="font-size:16px;"><?php echo $idioma["btn_usuarios_adm"] ?></strong>
                    </a>
                    <a class="span3 btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/professores">
                        <img src="/assets/icones/preto/32/corretores_32.png" /><br /><br /><strong style="font-size:16px;"><?php echo $idioma["btn_professores"] ?></strong>
                    </a>
					<a class="span3 btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/pessoas">
                        <img src="/assets/icones/preto/32/pessoas_32.png" /><br /><br /><strong style="font-size:16px;"><?php echo $idioma["btn_pessoas"] ?></strong>
                    </a>
                  </div>
                  <div class="row-fluid" style="padding-bottom:20px;">
                    <a class="span3 btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/matriculas">
                        <img src="/assets/icones/preto/32/reservas_32.png" /><br /><br /><strong style="font-size:16px;"><?php echo $idioma["btn_matriculas"] ?></strong>
                    </a>
                  </div>
				<?php }elseif($url[5] && $url[6] && $_POST["acao"] == "listar_fila") { ?>
                  <div id="listagem_informacoes"> 		  
                    <strong style="font-size:18px;font-weight:normal;font-family:Calibri,Vernada">Busca realizada:<?php echo $idioma["titulo_opcao"]; ?></strong>
					<br />
					<?php 
					echo $busca;
					echo "<br />";
					printf($idioma["informacoes"],$linhaObj->Get("total")); 
					switch($url[6]) {
					  case "usuariosadm":
						$busca = "Tipo da busca: <strong>Usuários administrativos</strong><br />".$busca;
					  break;
					  case "professores":
						$busca = "Tipo da busca: <strong>Professores</strong><br />".$busca;
					  break;
					  case "pessoas":
						$busca = "Tipo da busca: <strong>Pessoas</strong><br />".$busca;
					  break;
					  case "matriculas":
						$busca = "Tipo da busca: <strong>Matrículas</strong><br />".$busca;
					  break;
					}
					?>
                  </div>
				  <form method="post" class="form-horizontal">
                    <input name="acao" type="hidden" value="salvar_fila"  />
                    <input name="busca" type="hidden" value="<?php echo mysql_real_escape_string($busca);?>" />
                    <input name="filtro" type="hidden" value="<?php echo mysql_real_escape_string($filaAddArray["filtro"]);?>" />
					
                    <label class="checkbox" style="margin-left:8px;">
                      <input name="todos_fila" id="todos_fila" onclick="selecionaTodos('sel_todos', 'todos_fila', 1)" type="checkbox" /> 
                      <em><?= $idioma["marcar_todos"]; ?></em>
                    </label>
                    <?php //if($url[6] == "pessoas") { ?>
                      <label class="checkbox" style="margin-left:8px;">
                        <input name="todos_envio" id="todos_envio" onclick="selecionaTodos('sel_todos', 'todos_envio', 2)" type="checkbox" />
                        <em><?= $idioma["marcar_todos_envio"]; ?></em>
                      </label>
                    <?php //} ?>
                    <div id="sel_todos">
                        <?php 
                        unset($filaAddArray["filtro"]);
                        $linhaObj->GerarTabela($filaAddArray,NULL,$idioma,$listagem,'tabelaSemTamanho'); ?>
                    </div>
                      
					<div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
                      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/fila" class="btn"><?= $idioma["btn_cancelar"]; ?></a>
                    </div>
                  </form>
				<?php } elseif($url[5] && $url[6]) { ?>
                  <form method="post" class="form-horizontal">
                    <input name="acao" type="hidden" value="listar_fila" />
                    <?php $linhaObj->GerarFormulario($configFormulario, $_POST, $idioma); ?>
                    <div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_buscar"]; ?>">&nbsp;
                      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/fila" class="btn"><?= $idioma["btn_cancelar"]; ?></a>
                    </div>
                  </form>
				<?php } else { ?>
                  <div id="listagem_informacoes"> 		  
					<?php printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
                  </div>
				  <form method="post" id="remover_fila" name="remover_fila">
                    <input type="hidden" id="acao" name="acao" value="remover_fila">
                    <input type="hidden" id="remover" name="remover" value="">
                  </form>
				  <?php
				  $linhaObj->GerarTabela($filaArray,$_GET["q"],$idioma,'listagem_fila','tabelaSemTamanho'); 
				  if(count($filtrosArray) > 0) {
					?>
					<br />
                    <strong style="font-size:18px;font-weight:normal;font-family:Calibri,Vernada">Busca(s) realizada(s):</strong>
                    
                    <div class="accordion" id="accordion_fila">
                     	<div class="accordion-group">
                          <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_fila" href="#visualizar_fila"><?= $idioma["visualizar_resultado"]; ?></a>
                          </div>
                          <div id="visualizar_fila" class="accordion-body collapse">
                            <div class="accordion-inner">
                            	<?php
									foreach($filtrosArray as $filtro) {
									  echo "<hr />";
									  echo "<h6 id=\"busca".$filtro["idfiltro"]."\" style=\"color:#000\"><strong>".$filtro["idfiltro"]." - Busca realizada por ".$filtro["nome"]." em ".formataData($filtro["data_cad"], "br", 1)."</strong></h6>";
									  echo $filtro["busca"];
									}
								?>
                            </div>
                          </div>
                        </div>
                       </div>
                       
					<?php 
				  }
                }
				?>                      
              </div>   
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php incluirLib("rodape",$config,$usuario); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>        
    <script src="/assets/js/jquery.maskedinput_1.3.js"></script>
    <script type="text/javascript">
	  jQuery(document).ready(function($) { 
		<?
		foreach($config[$configFormulario] as $fieldsetid => $fieldset) {
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
			if($campo["datepicker"]){
			?>
			  $( "#<?= $campo["id"]; ?>" ).datepicker($.datepicker.regional["pt-BR"]);
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
                
                $.getJSON('<?=$campo["json_url"];?><?=$linha[$campo["json_idpai"]];?>', function(json){
                    var options = '<option value="">- <?=$idioma[$campo["json_input_vazio"]]; ?> -</option>';   
                    for (var i = 0; i < json.length; i++) {
                        var selected = '';
                        if(json[i].<?=$campo["valor"];?> == <?=intval($linha[$campo["valor"]]);?>)
                            var selected = 'selected';
                        options += '<option value="' + json[i].<?=$campo["valor"];?> + '" '+ selected +'>' + json[i].<?=$campo["json_campo_exibir"];?> + '</option>';
                    }
                    $('#<?=$campo["id"];?>').html(options);
                });
                <?
            }
		  }
		}
		?>
	  });
	  	
	  function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
		  document.getElementById("remover").value = id;
		  document.getElementById("remover_fila").submit();
		} 
	  }
    </script>
  </div>
</body>
</html>