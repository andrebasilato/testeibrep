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
              <legend><?= $idioma["label_vendedor"]; ?></legend>
              <h2 style="margin-bottom:15px; font-size:20px; line-height:25px; text-transform:uppercase;"><?= $vendedor["nome"]; ?></h2>          
          
            <section id="formulario"> 
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
              <form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/<?= $url[6]; ?>/<?= $url[7]; ?>/finalizar" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">            
				<? 
				if(!$_POST["bolsa"]) $_POST["bolsa"] = "N";
				$matriculaObj->GerarFormulario("formulario_financeiro",$_POST,$idioma); 
				?>            
                <div class="form-actions">
                  <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_finalizar"]; ?>">
                </div>
              </form>
            </section> 
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
	var regras = new Array();		
	<?php
	foreach($config["formulario_financeiro"] as $fieldsetid => $fieldset) {
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
	  foreach($config["formulario_financeiro"] as $fieldsetid => $fieldset) {
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
		  if ($campo["botao_hide"]){					
			if ($campo["tipo"] == "select") { ?>
			  var valorHide = $('#<?= $campo["id"]; ?>').attr("value");
			  if (valorHide == "S"){
				$('#<?= $campo["id"]; ?> option[value="S"]').attr("selected","selected");
				$("#div_form_idsolicitante").show();
				for (var i = 0; i < regras.length; i++){
				  if(regras[i] == "required,form_valor_contrato,<?= $idioma["valor_contrato_vazio"] ?>" || "required,form_quantidade_parcelas,<?= $idioma["quantidade_parcelas_vazio"] ?>") {
					regras.splice(i, 1); 
				  }
				}
			  } else {
				$('#<?= $campo["id"]; ?> option[value="N"]').attr("selected","selected");
				$("#div_form_valor_contrato").show();
				$("#div_form_quantidade_parcelas").show();
				for (var i = 0; i < regras.length; i++){ 
				  if(regras[i] == "required,form_idsolicitante,<?= $idioma["idsolicitante_vazio"] ?>") {
					regras.splice(i, 1); 
				  }
				}
			  }
			  $('#<?= $campo["id"]; ?>').change(function() {
				var contemValorContrato = false;
				var contemQuantidadeParcela = false;
				var contemIdsolicitante = false;
				valorHide = $("#<?= $campo["id"]; ?>").attr("value");
				if (valorHide == "S"){
				  $("#div_form_idsolicitante").show("fast");
				  $("#div_form_valor_contrato").hide("fast");
				  $("#div_form_quantidade_parcelas").hide("fast");
				  $("#form_valor_contrato").attr("value","");
				  $("#form_quantidade_parcelas").attr("value","");
				  for (var i = 0; i < regras.length; i++) { 
					if(regras[i] == "required,form_valor_contrato,<?= $idioma["valor_contrato_vazio"] ?>") {
					  regras.splice(i, 1);
					}
					if(regras[i] == "required,form_quantidade_parcelas,<?= $idioma["quantidade_parcelas_vazio"] ?>") {
					  regras.splice(i, 1);
					}
					if (regras[i] == "required,form_idsolicitante,<?= $idioma["idsolicitante_vazio"] ?>") {
					  contemIdsolicitante = true;
					}
				  }
				  if (!contemIdsolicitante) {
					regras.push("required,form_idsolicitante,<?= $idioma["idsolicitante_vazio"] ?>"); 					
				  }								
				} else {
				  $("#div_form_valor_contrato").show("fast");
				  $("#div_form_quantidade_parcelas").show("fast");
				  $("#div_form_idsolicitante").hide("fast");
				  $("#div_form_idsolicitante").attr("value","");
				  for (var i = 0; i < regras.length; i++){ 
					if(regras[i] == "required,form_idsolicitante,<?= $idioma["idsolicitante_vazio"] ?>") {
					  regras.splice(i, 1);
					}
					if (regras[i] == "required,form_valor_contrato,<?= $idioma["valor_contrato_vazio"] ?>") {
					  contemValorContrato = true;
					}
					if ("required,form_quantidade_parcelas,<?= $idioma["quantidade_parcelas_vazio"] ?>") {
					  contemQuantidadeParcela = true;
					}
				  }
				  if (!contemValorContrato) {
					regras.push("required,form_valor_contrato,<?= $idioma["valor_contrato_vazio"] ?>"); 					
				  }
				  if (!contemQuantidadeParcela) {
					regras.push("required,form_quantidade_parcelas,<?= $idioma["quantidade_parcelas_vazio"] ?>");						
				  }
				}						
			  });
			<?
			}
		  }
		}
	  }
	  ?>
	  <?php if($matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|20", false)) { ?>
	    document.getElementById('form_data_registro').disabled = false;
	  <?php } ?>
	});
  </script>
</div>
</body>
</html>