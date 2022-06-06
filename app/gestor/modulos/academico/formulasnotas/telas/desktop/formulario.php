<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
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
          <li class="active"><?php echo $linha["nome"]; ?></li>
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
				<h2 class="tituloOpcao"><?php if($url[3] == "cadastrar") { echo $idioma["titulo_opcao_cadastar"]; } else { echo $idioma["titulo_opcao_editar"]; } ?></h2>
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
					$linhaObj->GerarFormulario("formulario",$_POST,$idioma);
				  }
				  ?>
				  
				  
				<?php 
				$primeira = true;
				$total_linha = 0;
				foreach ($tipos_notas as $tipo) { 
					$total_linha++;					
				
					if ($total_linha == 1) { ?>
					<div class="control-group">
						<?php if ($primeira) { $primeira = false; ?>
							<label class="control-label" for=""><?= $idioma["label_tipos_notas"]; ?></label>
						<?php } ?>
						<div class="controls">
						    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
							    <tr>
					<?php } ?>
							
									<td align="center" valign="middle">
										<div style="width:110px;">
											<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $tipo["nome"]; ?>" style="width:100px;" onclick="adicionarVariavel('[[N][<?= $tipo["idtipo"]; ?>]]')"><?= $tipo["nome"]; ?></a>
										</div>
									</td>
					
					<?php 
					if ($total_linha == 4) { ?>
							    </tr>
						    </table>
						</div>
                    </div>
					<?php 
					$total_linha = 0;
					} 
					
				} 
				
				if ($total_linha != 0) {
					$valor_coluna = $total_linha;
					while ($valor_coluna < 4) {
						$valor_coluna++;
				?>
					<td align="center" valign="middle">
						<div style="width:110px;"></div>
					</td>
				<?php } ?>
						</tr>
					  </table>
					</div>
				</div>
				<?php } ?>
                				  
				  
                  <div class="control-group">
                    <label class="control-label" for=""><?= $idioma["label_funcoes"]; ?></label>
                    <div class="controls">
                      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                        <tr>
                          <td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_maximo"]; ?>" style="width:100px;" onclick="adicionarVariavel('>>(x;y)')"><?= $idioma["btn_maximo"]; ?></a>
							</div>
						  </td>
                          <td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_minimo"]; ?>" style="width:100px;" onclick="adicionarVariavel('<<(x;y)')"><?= $idioma["btn_minimo"]; ?></a>
							</div>
						  </td>
                          <td align="center" valign="middle">
						    <div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_media"]; ?>" style="width:100px;" onclick="adicionarVariavel('//(x;y)')"><?= $idioma["btn_media"]; ?></a>
							</div>
						  </td>
                          <td align="center" valign="middle">
						    <div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_arredondar"]; ?>" style="width:100px;" onclick="adicionarVariavel('@(x)')"><?= $idioma["btn_arredondar"]; ?></a>
							</div>
						  </td>
                        </tr>
                        <tr>
                          <td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_piso"]; ?>" style="width:100px;" onclick="adicionarVariavel('_(x)')"><?= $idioma["btn_piso"]; ?></a>
							</div>
						  </td>
                          <td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_teto"]; ?>" style="width:100px;" onclick="adicionarVariavel('#(x)')"><?= $idioma["btn_teto"]; ?></a>
							</div>
						  </td>
						  
						  <?php/*<td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_maior"]; ?>" style="width:100px;" onclick="adicionarVariavel('!(x)')"><?= $idioma["btn_maior"]; ?></a>
							</div>
						  </td>*/?>
						  <td align="center" valign="middle">
							<div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_maior"]; ?>" style="width:100px;" onclick="adicionarVariavel('[[MAIOR]]')"><?= $idioma["btn_maior"]; ?></a>
							</div>
						  </td>
                          <td align="center" valign="middle">
						    <div style="width:110px;">
								<a href="javascript:void(0);" class="btn dropdown-toggle" rel="tooltip" data-original-title="<?= $idioma["tooltip_maior_media"]; ?>" style="width:100px;" onclick="adicionarVariavel('&&(x;[[MC]])')"><?= $idioma["btn_maior_media"]; ?></a>
							</div>
						  </td>						  
                        </tr>
                        <tr>
                            <td align="center" valign="middle">
                                <div style="width:110px;">
                                    <a href="javascript:void(0);" class="btn btn-primary" rel="tooltip" data-original-title="<?= $idioma["tooltip_limpar"]; ?>" style="width:100px; color:white;" onclick="adicionarVariavel('LIMPAR')"><?= $idioma["btn_limpar"]; ?></a>
                                </div>
						    </td>
                        </tr>
                      </table>
                    </div>
                  </div>
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
          }
        } ?>			
      });
	  function adicionarVariavel(valor){
		
		if(valor == '[[MAIOR]]') {
			$("#form_formula").val(valor);
			return true;
		} else if(valor == 'LIMPAR') {
			$("#form_formula").val("");
			return true;
		} else {
			document.getElementById('form_formula').value = document.getElementById('form_formula').value.replace('[[MAIOR]]', '');
		}
		
		var inicioSelecao = $("#form_formula")[0].selectionStart;
		var fimSelecao = $("#form_formula")[0].selectionEnd;
		
		var formula = $("#form_formula").val();
		var tamanhoFormula = formula.length;
		
		var antes = formula.substring(0,inicioSelecao);
		var depois = formula.substring(fimSelecao,tamanhoFormula);
		
		$("#form_formula").val(antes+valor+depois);
		
		$("#form_formula").focus();
	  }
    </script>
  </div>
</body>
</html>