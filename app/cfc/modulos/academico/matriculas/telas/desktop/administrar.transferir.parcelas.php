<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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
		<?php
		if ($_POST["msg"]) {
			?>
			<div class="alert alert-success fade in"> 
				<a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
				<strong>
					<?= $idioma[$_POST["msg"]]; ?>
				</strong> 
			</div>
			<script>
				alert('<?= $idioma[$_POST["msg"]]; ?>');
			</script>
			<?php
		}
		?>
		<form method="post" action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/transferir_parcelas_selecionadas/<?= $url[7]; ?>" class="form-horizontal" onsubmit="return verificaSelecao();" >
                  
                <? if(count($matricula_contas['contas']) > 0) { ?>
				
				<? foreach($matricula_contas as $contas) { ?>
                  <h4><?= $contas[0]["evento"]; ?></h4>
                  <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
					  <td bgcolor="#F4F4F4">&nbsp;</td>
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
							<input type="checkbox" class="parcelas_selecionadas" name="parcelas_selecionadas[<?php echo $conta["idconta"]; ?>]" />
                        </td>
                        <td><?php echo $conta["idconta"]; ?></td>
                        <td><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?></td>
                        <td>
							<span style="color:#999">R$</span> 
							
								<?php
									if($conta['valor_matricula']) {
										$valor_parcela = ($conta["valor_matricula"]/$conta['total_contas_compartilhadas']);
										echo '<strong>'.number_format($valor_parcela, 2, ",", ".").'</strong> <span style="color:#999"> / '. number_format($conta["valor"], 2, ",", ".").'</span>';
									} else {
										echo '<strong>'.number_format($conta["valor"], 2, ",", ".").'</strong>';
									} 
								?>
							
						</td>
                        <td><?php echo formataData($conta["data_vencimento"],'br',0); ?></td>
                        <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                      </tr>
                      <?php } ?> 
					</table>
                <?php } ?>
				
				<input class="btn btn-primary" type="submit" value="<?=$idioma["btn_selecionar"];?>" />
				
				<a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/transferir" ><?= $idioma["voltar"]; ?></a>
				
			  <?php } else { ?>
				  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["nenhum_financeiro"]; ?></strong>
                  </div>
				  <a class="btn" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/transferir" ><?= $idioma["voltar"]; ?></a>
			  <?php } ?>
                
                  
      </form> 
      </div>
  </div>
</div>

<script>
function verificaSelecao() {
	var inputs = document.getElementsByClassName('parcelas_selecionadas');
	var tamanho = inputs.length;
	var selecionado = false;
	for(var i=0; i<tamanho; i++) {
		if(inputs[i].checked == true){
			selecionado = true;
			break;
		}
	}
	if(selecionado != true) {
		alert("Selecione pelo menos uma conta.")
		return false;
	}
}
</script>

</body>
</html>