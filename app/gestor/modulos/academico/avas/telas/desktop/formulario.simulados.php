<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/calendario.css" type="text/css" media="screen" />
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? if($url[5] == "cadastrar") {
            echo strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, "...") : $linha["nome"];
        } else { echo strlen($linha["ava"]) > 30 ? mb_strimwidth($linha["ava"], 0, 50, "...") : $linha["ava"]; } ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/simulados"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <? if($url[5] == "cadastrar") { ?>
          <li class="active"><?= $idioma["nav_formulario"]; ?></li>
        <? } else { ?>
          <li class="active"><?php echo strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, "...") : $linha["nome"]; ?></li>
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
              <?php if($url[5] != "cadastrar") { ?><h2 class="tituloEdicao">
				<?php echo wordwrap($linha["nome"], 30, " ", true); ?></h2>
                <?php include("inc_submenu_simulados.php"); ?>
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
                <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="salvar_simulado" />
                  <? if($url[6] == "editar") {
                    echo '<input type="hidden" name="'.$config["banco_simulados"]["primaria"].'" id="'.$config["banco_simulados"]["primaria"].'" value="'.$linha[$config["banco_simulados"]["primaria"]].'" />';
                    foreach($config["banco_simulados"]["campos_unicos"] as $campoid => $campo) {
                    ?>
                      <input name="<?= $campo["campo_form"]; ?>_antigo" id="<?= $campo["campo_form"]; ?>_antigo" type="hidden" value="<?= $linha[$campo["campo_banco"]]; ?>" />
                    <? 
                    }					  
                    $linhaObj->GerarFormulario("formulario_simulados",$linha,$idioma);				
                  } else {
                    $linhaObj->GerarFormulario("formulario_simulados",$_POST,$idioma);
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
	<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script type="text/javascript">
        var arrayDisciplinas = new Array();
        <?php 
            if (count($disciplinasPerguntas) > 0) {
                foreach ($disciplinasPerguntas as $indice => $disciplina) {?>
                    arrayDisciplinas.push(<?= $disciplina['iddisciplina'] ?>);
                <?php } 
            } elseif (count($_POST['iddisciplina_perguntas']) > 0) {
                foreach ($_POST['iddisciplina_perguntas'] as $indice => $iddisciplina) {?>
                    arrayDisciplinas.push(<?= $iddisciplina ?>);
                <?php }
            }
        ?>
            
        var regras = new Array();
      <?php
      foreach($config["formulario_simulados"] as $fieldsetid => $fieldset) {
        foreach($fieldset["campos"] as $campoid => $campo) {
          if(is_array($campo["validacao"])){
            foreach($campo["validacao"] as $tipo => $mensagem) {
              if($campo["tipo"] == "file"){
              ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $campo["extensoes"]; ?>,<?php echo $campo["tamanho"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? } else { ?>
                regras.push("<?php echo $tipo; ?>,<?php echo $campo["id"]; ?>,<?php echo $idioma[$mensagem]; ?>");
              <? } //}
            }
          }
        }
      }
      ?>
	  jQuery(function($){

        <? foreach($config["formulario_simulados"] as $fieldsetid => $fieldset) {
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
      if ($campo["botao_hide"]){
        if ($campo['tipo'] != 'select') { ?>
        if($('#<?= $campo["id"]; ?>').attr('checked')== 'checked')
          $('#div_<?= $campo["iddiv"]; ?>').show();
        $('#<?= $campo["id"]; ?>').click(function() {
          $('#div_<?= $campo["iddiv"]; ?>').toggle("fast");
          if($('#<?= $campo["id"]; ?>').attr('checked')!= 'checked')
          $('#div_<?= $campo["iddiv"]; ?> option[value=""]').attr('selected','selected');
          $('#<?= $campo["iddiv2"]; ?>').attr("value","");
          $('#div_<?= $campo["iddiv2"]; ?>').hide("fast");
        });
        <? } else { 
		if($campo["id"] == 'form_avaliador') {
		?>
        var aux_d = $('#<?= $campo["id"]; ?>').attr('value');
        if (aux_d == 'professor'){
          
          $('#div_form_<?= $campo["iddiv"]; ?>').show();
          $('#div_form_<?= $campo["iddiv2"]; ?>').show();
          $('#div_form_<?= $campo["iddiv3"]; ?>').show();
          $('#div_form_<?= $campo["iddiv4"]; ?>').show();

        } else if (aux_d == 'sistema') {
          if($('#div_form_<?= $campo["iddiv"]; ?>').css('display') == 'none'){
			tam = regras.length;
            var qtd_removidos = 0;
			for (var i = 0; i < tam; i++){

              if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>')
                 var var1 = i;                     
              else if(regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?=$idioma[$campo["iddiv2"]."_vazio"];?>')
                 var var2 = i;
              else if(regras[i] == 'required,form_<?= $campo["iddiv3"]; ?>,<?=$idioma[$campo["iddiv3"]."_vazio"];?>')
                 var var3 = i;
              else if(regras[i] == 'required,form_<?= $campo["iddiv4"]; ?>,<?=$idioma[$campo["iddiv4"]."_vazio"];?>')
                 var var4 = i;           
            }
			
			if(var1 != null || var1) { regras.splice(var1, 1); qtd_removidos++; }
			if(var2 != null || var2) { regras.splice((var2-qtd_removidos), 1); qtd_removidos++; }
			if(var3 != null || var3) { regras.splice((var3-qtd_removidos), 1); qtd_removidos++; }
			if(var4 != null || var4) { regras.splice((var4-qtd_removidos), 1); }
			
         }
        }
        $('#<?= $campo["id"]; ?>').change(function() {		  
			  aux_d = $('#form_avaliador').attr('value');
			  if (aux_d == 'professor'){
					
				  $('#div_form_<?= $campo["iddiv"]; ?>').show("fast");
				  $('#div_form_<?= $campo["iddiv2"]; ?>').show("fast");
				  $('#div_form_<?= $campo["iddiv3"]; ?>').show("fast");
				  $('#div_form_<?= $campo["iddiv4"]; ?>').show("fast");
				  var tem1 = false;
				  var tem2 = false;
				  var tem3 = false;
				  var tem4 = false;
				  tam = regras.length;
				  for (var i = 0; i < tam; i++){
					if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>')
					tem1 = true;
					if(regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?=$idioma[$campo["iddiv2"]."_vazio"];?>')
					tem2 = true;
					if(regras[i] == 'required,form_<?= $campo["iddiv3"]; ?>,<?=$idioma[$campo["iddiv3"]."_vazio"];?>')
					tem3 = true;
					if(regras[i] == 'required,form_<?= $campo["iddiv4"]; ?>,<?=$idioma[$campo["iddiv4"]."_vazio"];?>')
					tem4 = true;
				  }
          if(!tem1){
          regras.push("required,form_periodo_correcao_dias,<?=$idioma['periodo_correcao_dias_vazio'];?>");
          }
				  if(!tem2){
					regras.push("required,form_subjetivas_faceis,<?=$idioma['subjetivas_faceis_vazio'];?>");
				  }
				  if(!tem3){  
					regras.push("required,form_subjetivas_intermediarias,<?=$idioma['subjetivas_intermediarias_vazio'];?>");
				  }
				  if(!tem4){
					regras.push("required,form_subjetivas_dificeis,<?=$idioma['subjetivas_dificeis_vazio'];?>");
				  }
				  
			  } else if(aux_d == 'sistema'){
					$('#form_subjetivas_faceis').attr("value","");
					$('#div_form_subjetivas_faceis').hide("fast");
					$('#form_subjetivas_intermediarias').attr("value","");
					$('#div_form_subjetivas_intermediarias').hide("fast");
					$('#form_subjetivas_dificeis').attr("value","");
					$('#div_form_subjetivas_dificeis').hide("fast");
					$('#form_periodo_correcao_dias').attr("value","");
					$('#div_form_periodo_correcao_dias').hide("fast");
					var qtdeArray = regras.length;
					var qtd_removidos = 0;
					for (var i = 0; i < qtdeArray; i++){ 
					  if(regras[i] == 'required,form_<?= $campo["iddiv"]; ?>,<?=$idioma[$campo["iddiv"]."_vazio"];?>') {						
						var var1 = i;                                        
					  }else if(regras[i] == 'required,form_<?= $campo["iddiv2"]; ?>,<?=$idioma[$campo["iddiv2"]."_vazio"];?>'){
						 var var2 = i;
					  }else if(regras[i] == 'required,form_<?= $campo["iddiv3"]; ?>,<?=$idioma[$campo["iddiv3"]."_vazio"];?>'){
						var var3 = i;
					  }else if(regras[i] == 'required,form_<?= $campo["iddiv4"]; ?>,<?=$idioma[$campo["iddiv4"]."_vazio"];?>'){
						var var4 = i;
					  }
				   }
				   
				   if(var1 != null || var1) { regras.splice(var1, 1); qtd_removidos++; }
				   if(var2 != null || var2) { regras.splice((var2-qtd_removidos), 1); qtd_removidos++; }
				   if(var3 != null || var3) { regras.splice((var3-qtd_removidos), 1); qtd_removidos++; }
				   if(var4 != null || var4) { regras.splice((var4-qtd_removidos), 1); }
				   
			  } else {
				   regras.push("required,<?= $campo["id"]; ?>,<?= $idioma[$campo["nome"]."_vazio"];?>");
			  }
		  
        });
        <?
		}
        }
      }
      }
    } ?>

        $("input[name='iddisciplina_perguntas[]']").each(function () {
            if (isInArray(arrayDisciplinas, $(this).val()) != -1) {
                $(this).attr("checked", true) ;
            }
        });
    });

    function isInArray(array, objBusca) {
        for (i = 0; i < array.length; i++) {
            if (array[i] == objBusca) {
                return i;
            }
        }
        return -1;
    }

	function deletaArquivo(div, obj) {
		if(confirm("<?php echo $idioma["arquivo_excluir_confirma"]; ?>")) {
		  solicita(div, obj);		
		}
	  }
    </script>
  </div>
</body>
</html>