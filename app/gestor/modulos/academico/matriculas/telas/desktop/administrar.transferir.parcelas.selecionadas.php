<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
  margin:20px;
}
body {
	min-width: 500px;
}
.container-fluid {
	min-width: 500px;
}
</style>
</head>
<body>
<div class="row-fluid" >
  <div class="span12">
  	 <legend><?=$idioma["label_titulo"]; ?></legend>
			
			<? if($mensagem["erro"]) { ?>
              <div class="alert alert-error"> 
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?= $idioma[$mensagem["erro"]]; ?>
              </div>
			  <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
            <? } ?>
            <? if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in"> 
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
				<strong><?= $idioma[$_POST["msg"]]; ?></strong> 
              </div>
			  <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <? } ?>
			
			<?php if($salvar['sucesso']) { ?>
				<div class="alert alert-success fade in"> 
					<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
					<strong><?= 'Parcelas Transferidas com sucesso!'; ?></strong> 
                </div>
				<br /><br />
				<a class="btn btn-large btn-primary" style="color:#FFFFFF" target="_parent" onclick="javascript:window.opener.location.href='/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>';window.close();" >Clique aqui para voltar à matrícula</a>
				<br />				
			<?php exit; } ?>
	 
            <form method="post" action="" style="padding-top:15px;" onsubmit="return validateFields(this, regras_financeiro)">
                <input name="acao" type="hidden" value="transferir_parcelas_salvar" />
				
                <? if(count($matricula_contas) > 0) { ?>
				
				<? foreach($matricula_contas as $contas) {
					unset($valor_total);
				?>
                  <h4><?= $contas[0]["evento"]; ?></h4>
                  <br />                  
				  <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_id"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_vencimento"];?></strong></td>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_situacao"];?></strong></td>
                    </tr>
                    <? 
                    foreach($contas as $conta) {					  
                      ?>
                      <tr>
                        <td>
							<input type="hidden" name="parcelas_transferidas[<?php echo $conta["idconta"]; ?>]" />
							<?php echo $conta["idconta"]; ?>
						</td>
                        <td><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?></td>
                        <td>
							<span style="color:#999">R$</span> 
							
								<?php
									if($conta['valor_matricula']) {
										$valor_total += $valor_parcela;
										$valor_parcela = ($conta["valor_matricula"]/$conta['total_contas_compartilhadas']);
										echo '<strong>'.number_format($valor_parcela, 2, ",", ".").'</strong> <span style="color:#999"> / '. number_format($conta["valor"], 2, ",", ".").'</span>';
									} else {
										$valor_total += $conta["valor"];
										echo '<strong>'.number_format($conta["valor"], 2, ",", ".").'</strong>';
									} 
								?>
							
						</td>
                        <td><?php echo formataData($conta["data_vencimento"],'br',0); ?></td>
                        <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                      </tr>
                      <?php } ?>                    
                  	
					  <tr>
						  <td colspan="2"></td>
						  <td><span style="color:#999">R$</span> <strong><?=number_format($valor_total, 2, ",", ".");?></strong></td>
						  <td colspan="2"></td>
                      </tr>
					
					</table><br />
                <?php } ?>				
				
				<div class="control-group" style="padding-right:25px;">
					<h4><?php echo $idioma["form_matricula"]; ?></h4>
					<div class="controls"><select id="matricula" name="matricula"></select></div>
					<div style="margin-top:10px;">Só serão mostradas matrículas na situação <span class="label" style="background:#<?php echo $situacaoInicial["cor_bg"]; ?>;color:#<?php echo $situacaoInicial["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $situacaoInicial["nome"]; ?></span></div>
				</div>
				<br />
			  
                  <input class="btn" type="submit" value="<?=$idioma["btn_transferir"];?>" />
            </form>
			  
			  
			  <?php } else { ?>
				  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["sem_parcelas_selecionadas"]; ?></strong>
                  </div>
			  <?php } ?>
      </div>
  </div>
</div>
<script src="/assets/js/jquery.1.7.1.min.js"></script>

<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
	$("#matricula").fcbkcomplete({
	  json_url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/json/matriculas",
	  addontab: true,
	  height: 10,
	  maxshownitems: 10,
	  input_min_size: 0,
	  cache: true,
	  maxitems: 1,
	  filter_selected: true,
	  firstselected: true,
	  complete_text: "<?= $idioma["mensagem_select"]; ?>",
	  addoncomma: true
	});
  });
</script>


</body>
</html>