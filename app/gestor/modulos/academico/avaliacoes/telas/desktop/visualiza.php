<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style>
/*.tituloEdicao {
	font-size:45px;
}*/

legend {
	line-height:25px;
	margin-bottom: 5px;
	margin-top: 20px;
}

.tabela {
	border:#CCC solid 1px;
	width:100%;
}
.linha {
	border-bottom:#CCC solid 1px;
}
.coluna {
	border-right:#CCC solid 1px;
}
.botao {
	height:100px;
	margin-top: 15px;
	padding-bottom:0px;
	float:left;
	padding-top:40px;
	height:58px;
	text-transform:uppercase;
}
td[valign='baseline']{
	display: block;
	float: left;
	width: 100%;
}
.nav > .li {
	border:#CCC solid 1px;
	padding:10px;
}

.nav > .li:hover {
  text-decoration: none;
  background-color: #eeeeee;
}

.botao_big {
	height:100px;
	margin-top: 15px;
	padding-bottom:0px;
}
.li {
	border:#CCC solid 1px;
	padding:10px;
}
.li:hover {
  text-decoration: none;
  background-color: #eeeeee;
}

.divCentralizada {
  position: relative;
  width: 700px;
  height: 150px;
  left: 15%;
  top:50%;
}

/* CSS BOOTSTRAP LABEL MESMA LINHA */
label input, label textarea, label select {
  display: inline;
}

.tituloPergunta {
	font-size:15px;
}
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>

<div class="container-fluid">
  <section id="global">
    <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?>&nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li class="active"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/avaliacoes"><?= $idioma["pagina_titulo"]; ?></a></li>
      <span class="pull-right" style="padding-top:3px; color:#999">
      <?= $idioma["hora_servidor"]; ?>
      <?= date("d/m/Y H\hi"); ?>
      </span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
		<?php $linha1 = each($prova); ?>
	    <img class="fotototal" src="/api/get/imagens/avas_avaliacoes_imagem_exibicao/x/100/<?php echo $linha1['value']['imagem_exibicao_servidor']; ?>">

        <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
        <h2 class="tituloEdicao" style="line-height:25px; padding-left:0px;">
            <small style="text-transform:uppercase;"><?=$idioma["titulo_prova"];?></small> # <?=$linha["idprova"];?><br />
		    <small style="text-transform:uppercase;"><?=$idioma["titulo_matricula"];?></small> # <?=$linha["idmatricula"];?><br />
		    <small style="text-transform:uppercase;"><?=$idioma["titulo_aluno"];?>:</small> <?=$linha["aluno"];?><br />
		    <small style="text-transform:uppercase;"><?=$idioma["titulo_curso"];?>:</small> <?=$linha["curso"];?><br />
        </h2>

		  <?php if((float)$linha['nota']) { ?>
			<h1>
				<span class="label label-default pull-right" style="width:50px;font-size:20px; color:#FFF">
					<?php echo number_format($linha['nota'],2,',','.'); ?>
				</span>
			</h1>
		  <?php } ?>


        <div class="row-fluid">
          <div class="span10">

            <? if($mensagem["erro"]) { ?>
            <div class="alert alert-error">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <?= $idioma[$mensagem["erro"]]; ?>
            </div>
            <script>alert('<?= $idioma[$mensagem["erro"]]; ?>');</script>
            <? } ?>

			<? if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong>
              <?= $idioma[$_POST["msg"]]; ?>
              </strong> </div>
              <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <? } ?>

			<script>
			function verificaNotaQuestao(input) {
				var str = input.value;
				var res = str.replace(',','.');
				if(res > 10) {
					input.value = '0,00'
					alert('Nota maior que 10.');
				}
			}

			function verificaNotaTotal() {
				var array_notas = document.getElementsByClassName('nota');
				var tamanho = array_notas.length;
				var total = 0;
				for(var i=0; i<tamanho; i++) {
					str = array_notas[i].value;
					num = parseInt(str.replace(',','.'));
					if(!isNaN(num))
						total += parseInt(str.replace(',','.'));
				}
				if(total > 10) {
					alert('Soma das notas maior que 10.');
					return false;
				}
			}
			</script>

			<section>
				<?php if($url[4] == 'corrigir') { ?>
					<form method="post" onsubmit="return verificaNotaTotal()" enctype="multipart/form-data">
						<input type="hidden" name="acao" value="corrigir_avaliacao" />
				<?php } ?>

				<?php foreach($prova as $pergunta) {
						$total_opcoes = count($pergunta['opcoes']);
				?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr style="height:45px;">
							<td colspan="<?php echo $total_opcoes; ?>">
								<div style="margin-left:<?php //echo $pergunta['espacamento_esquerda']; ?>px;" class="tituloPergunta">
									<?php if($url[4] == 'corrigir') { ?>
											<input name="notas[<?php echo $pergunta['id_prova_pergunta']; ?>]" type="text" class="nota" maxlength="5" onkeyup="verificaNotaQuestao(this)" style="width:35px;" value="<?php echo str_replace('.',',',$pergunta['nota_questao']); ?>" />
									<?php } ?>
									<?php echo ++$num_pergunta.') '.$pergunta['nome']; ?>

									<?php if ($pergunta['imagem_servidor']) {?>
										<br /><br />
										<div class="box-pergunta_imagem">
					                       <a style="hover" title="Clique para baixar a imagem em anexo à pergunta." href="/<?= $url[0] ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?= $url[3] ?>/download_imagem_pergunta/<?= $pergunta['idpergunta'] ?>">
					                       		<img class="fotototal" src="/api/get/imagens/disciplinas_perguntas_imagens/x/300/<?php echo $pergunta["imagem_servidor"]; ?>">
					                       </a>
					                    </div>
									<?php } ?>

								</div>
							</td>
						</tr>

						<?php
						if($pergunta['tipo'] == 'O') {
							$colunas = 0;
							$horizontal = 0;
							foreach($pergunta['opcoes'] as $op => $opcoes) {
								$checked = '';
								if($opcoes['id_prova_pergunta_opcao'])
									$checked = 'checked="checked"';

								++$horizontal;
								if(!$pergunta['quantidade_colunas']) {

									if($pergunta['sentido'] == 'H') {
										if(!$horizontal) {
											?><tr><?php
										}

										if($pergunta['multipla_escolha'] == 'S') {
										?>
											<td valign="baseline">
												<label>
													<input type="checkbox" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes["numero"]; ?>" />
													&nbsp;
													<?php echo $opcoes['nome']; ?>
												</label>
											</td>
										<?php
										} else {
										?>
											<td>
												<label>
													<input type="radio" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['idopcao']; ?>"  />
													&nbsp;
													<?php echo $opcoes['nome']; ?>
												</label>
											</td>
										<?php
										}
										if($horizontal == $total_opcoes){
											?></tr><?php
										}
									} else {
										if($pergunta['multipla_escolha'] == 'S') {
										?>
											<tr>
												<td valign="baseline">
													<label>
														<input type="checkbox" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['numero']; ?>" />
														&nbsp;
														<?php echo $opcoes['nome']; ?>
													</label>
												</td>
											</tr>
										<?php
										} else {
										?>
											<tr>
												<td valign="baseline">
													<label>
														<input type="radio" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['idopcao']; ?>" />
														&nbsp;
														<?php echo $opcoes['nome']; ?>
													</label>
												</td>
											</tr>
										<?php
										}
									}

								} else {

									if($pergunta['sentido'] == 'H') {
										if(!$horizontal) {
											?><tr><?php
										}
										if($pergunta['multipla_escolha'] == 'S') {
										?>
											<td valign="baseline">
												<label>
													<input type="checkbox" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['numero']; ?>" />
													&nbsp;
													<?php echo $opcoes['nome']; ?>
												</label>
											</td>
										<?php
										} else {
										?>
											<td valign="baseline">
												<label>
													<input type="radio" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['idopcao']; ?>" />
													&nbsp;
													<?php echo $opcoes['nome']; ?>
												</label>
											</td>
										<?php
										}
										if($horizontal == $total_opcoes) {
											?></tr><?php
										}
									} else {
										if($pergunta['multipla_escolha'] == 'S'){
										?>
											<tr>
												<td valign="baseline">
													<label>
														<input type="checkbox" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['numero']; ?>" />
														&nbsp;
														<?php echo $opcoes['nome']; ?>
													</label>
												</td>
											</tr>
										<?php
										}else{
										?>
											<tr>
												<td valign="baseline">
													<label>
														<input type="radio" disabled="disabled" <?php echo $checked; ?> value="<?php echo $opcoes['idopcao']; ?>" />
														&nbsp;
														<?php echo $opcoes['nome']; ?>
													</label>
												</td>
											</tr>
										<?php
										}
									}

								}
							}
						} else {
						?>
							<tr>
								<td>
									<textarea disabled="disabled" rows="4" style="width:100%;"><?php echo $pergunta['resposta']; ?></textarea>
								</td>
							</tr>
						<?php
						}
						?>
						<?php if($url[4] == 'corrigir' || ($url[4] == 'visualizar' && $pergunta['observacao'])) { ?>
							<?php if($pergunta['aluno_arquivo_servidor']) { ?>
								<tr>
									<td>
									  <br />
									  <strong>Anexo do aluno:</strong>
									  <span class="icon-file"></span>
									  <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download_arquivo_aluno/".$pergunta["id_prova_pergunta"]; ?>"><?= $pergunta["aluno_arquivo"]; ?> (<?= tamanhoArquivo($pergunta["aluno_arquivo_tamanho"]); ?>)</a>
									  <br />
									</td>
								</tr>
							<?php } ?>

							<tr><td>&nbsp;</td></tr>
							<tr>
								<td>
									Observação<br />
									<textarea <?php if($url[4] == 'visualizar') echo 'disabled="disabled"'; ?> name="observacoes[<?php echo $pergunta['id_prova_pergunta']; ?>]" style="width:30%;" rows="4"><?php echo $pergunta['observacao']; ?></textarea>
								</td>
							</tr>
							<?php if($pergunta['professor_arquivo_servidor']) { ?>
								<tr>
									<td>
									  <br />
									  <strong>Anexo do professor:</strong>
									  <span class="icon-file"></span>
									  <a href="<?php echo "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/download_arquivo_professor/".$pergunta["id_prova_pergunta"]; ?>"><?= $pergunta["professor_arquivo"]; ?> (<?= tamanhoArquivo($pergunta["professor_arquivo_tamanho"]); ?>)</a>
									  <br />
									</td>
								</tr>
							<?php } ?>
							<?php if($url[4] == 'corrigir') { ?>
								<tr>
									<td>
										<br />
										<?= $idioma['anexar_arquivo'] ?>
										<input type="file" name="anexos[<?= $pergunta['id_prova_pergunta'] ?>]" id="anexos<?= $pergunta['id_prova_pergunta'] ?>">
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr><td>&nbsp;</td></tr>

					</table>
					<hr />
				<?php } ?>

				<?php if($url[4] == 'corrigir') { ?>
						<input type="submit" class="btn btn-primary" value="Salvar Correção" />
					</form>
				<?php } ?>
			</section>

			<section id="historico_avaliacao">
           	    <legend><?=$idioma["historico_label"];?></legend>
				<div style="height:400px; overflow:auto; border:0px;">
					<?php echo $linhaObj->retornarHistoricoTabela($historico, $idioma); ?>
				</div>
            </section>

          </div>
        </div>
        <div class="clearfix"></div>

      </div>
    </div>

  </div>
  <? incluirLib("rodape",$config,$usu_professor); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script>
	$("#data_registro").mask("99/99/9999");
	$("#data_registro").datepicker($.datepicker.regional["pt-BR"]);
	$(".nota").maskMoney({decimal:",",thousands:".",precision: 2,allowZero: true});
	jQuery(document).ready(function($) {
		//Alteralndo o valor do input quando a operação e o subsídio perderem o foco-----------------
		$('#valor').blur(function() {
			$('#valor').val(number_format(calculaFinanciado(), 2, ',', '.'));
		});
		//END-----
		$("#numero").keypress(isNumber);
		$("#numero").blur(isNumberCopy);
	});
</script>
<script type="text/javascript">
	var regras = new Array();
	regras.push("formato_arquivo,arquivos[1],jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf,'',<?php echo $idioma['arquivo_invalido']; ?>");
</script>
</div>

</body>
</html>