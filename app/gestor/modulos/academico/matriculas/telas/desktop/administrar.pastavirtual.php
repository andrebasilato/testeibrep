<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
<style type="text/css">

.tituloEdicao {
  font-size:45px;
}
legend {
  line-height:25px;
  margin-bottom: 5px;
  margin-top: 20px;
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
legend {
  background-color: #F4F4f4;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  padding: 5px 5px 5px 15px;
  width: 98%;
}


legend span {
  font-size: 9px;
  float: right;
  margin-right: 15px;
  color: #999;
}



</style>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:20px">

        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
        </div>

 

<table border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
    <td style="padding: 0px 0px 0px 8px;" valign="top">        <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
          <br />
          <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
        </h2></td>
  </tr>
</table>


<?php incluirTela("administrar.menu",$config,$matricula); ?>


        <div class="row-fluid">


          <div class="span12">

            <?php if($mensagem["erro"]) { ?>
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

            
            <section id="pastavirtual">
				<legend data-abrefecha="pastavirtual_ancora_div">
					Pasta virtual
			   </legend>
			   <div id="pastavirtual_ancora_div">
				<?php if(/*$matricula["situacao"]["visualizacoes"][7] && */$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|24", false)) { ?>
				<form method="post" action="" style="padding-top:15px;" enctype="multipart/form-data">
					<input name="acao" type="hidden" value="adicionar_arquivo" />
					<table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
						<thead>
							<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_arquivo_nome"];?></strong></td>
							<td bgcolor="#F4F4F4"><?=$idioma["documentos_matricula_arquivo"];?></td>
							<td bgcolor="#F4F4F4"><?=$idioma["documentos_matricula_conta"];?></td>
                            <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_protocolo"];?></strong></td>
							<td bgcolor="#F4F4F4">&nbsp;</td>
						</thead>
						<tbody>
							<tr>
								<td><input name="nome_arquivo" type="text" id="nome_arquivo"/></td>
								<td><input name="documento" type="file" id="arquivo"/></td>
								<td>
									<select name="idconta">
										<option value="">- Selecione uma conta -</option>
										<?php foreach ($contas as $evento) { ?>
											<optgroup label="<?php echo $evento[0]['evento']; ?>">
											<?php foreach ($evento as $conta_dados) { ?>
												<option value="<?php echo $conta_dados['idconta']; ?>">
													<?php echo $conta_dados['idconta'] . ' - ' . number_format($conta_dados['valor'], 2, ',', '.') . ' - ' . formataData($conta_dados['data_vencimento'], 'pt', 0); ?>
												</option>
											<?php } ?>
											</optgroup>
										<?php } ?>
									</select>
								</td>
								<td><input type="text" name="protocolo" id="protocolo"  value="" /></td>
								<td><input class="btn btn-mini" type="submit" id="enviar-arquivo" value="<?php echo $idioma["btn_adicionar"]; ?>" /></td>
							</tr>
						</tbody>
					</table>
				</form>
				<? } ?>

				<table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
					<tr>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_arquivo_nome"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_arquivo"];?></strong></td>
						 <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_protocolo"]; ?></strong>
                        </td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_opcoes"];?></strong></td>

					</tr>
					
					<?php if (count($arquivos) > 0) {
						foreach($arquivos as $documento) {  ?>
					<tr>
						<td><span id="mensagem_retorno"><?php echo $documento["nome_arquivo"]; ?></span></td>
						<td><span id="mensagem_retorno"><?php echo $documento["arquivo_nome"]; ?></span></td>
                        <td>
                                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/editarprotocolo/<?= $documento["iddocumento"]; ?>" rel="facebox tooltip" data-original-title="<?= $idioma["documentos_matricula_protocolo_editar"]; ?>" data-placement="left">
                                      <?php if($documento["protocolo"]) {
                                        echo $documento["protocolo"];
                                      } else {
                                        echo '--';
                                      } ?>
                                    </a>
                           </td>
                        <td>
                        
						<?php if ($documento["arquivo_nome"]) { ?>
							<?php if (strpos($documento['arquivo_tipo'],'image') !== false) { ?>
								<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/visualizararquivo/<?= $documento["iddocumento"]; ?>" class="fancybox btn btn-mini" rel="gallery" title="<?= $documento["tipo"].' ('.$documento["arquivo_nome"].')'; ?>"><i class="icon-picture"></i><?=$idioma["documentos_matricula_visualizar"];?></a>
							  <?php } ?>
                              
							<?php  if (strpos($documento['arquivo_tipo'],'pdf') !== false) { ?>
								<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/visualizararquivopdf/<?= $documento["iddocumento"]; ?>.pdf" class="btn btn-mini" target="_blank"><i class="icon-picture"></i><?=$idioma["documentos_matricula_visualizar"];?></a>
							  <?php }  ?>                              
                              
							  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/downloadarquivo/<?= $documento["iddocumento"]; ?>" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_matricula_download"]; ?>" data-placement="left"><i class="icon-download-alt"></i></a>
							<?php } else { ?>
							  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:enviarArquivo(<?= $documento["idarquivo"]; ?>);" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_matricula_enviar"]; ?>" data-placement="left"><i class="icon-upload"></i><?=$idioma["documentos_matricula_enviar"];?></a>
							<?php } ?>
							  <?php if(/*$matricula["situacao"]["visualizacoes"][9] && */$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26", false)) { ?>
								<a class="pull-right" href="javascript:void(0);" onclick="removerArquivo(<?= $documento["idarquivo"]; ?>,'<?php if($documento["arquivo_nome"]){ echo "(".$documento["arquivo_nome"].")"; } ?>','<?= $documento["idconta"]; ?>');"><img src="/assets/img/remover_16x16.gif" width="16" height="16" border="0" /></a>
							   <?php } ?>
						</td>


					</tr>
					<?php }
					} else { ?>
					<tr>
						<td colspan="7"><?=$idioma["nenhum_documento"];?></td>
					</tr>
					<?php } ?>
				</table>
			<?php if(/*$matricula["situacao"]["visualizacoes"][9] && */$matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26", false)) { ?>
			<script type="text/javascript">
			function removerArquivo(id,nome, idconta) {

				var msg = "<?=$idioma["confirm_remover_documento"];?>";
				msg = msg.replace("[[nome]]", nome);
				var confirma = confirm(msg);

				if (confirma) {
					document.getElementById('idarquivo').value = id;
					document.getElementById('idconta_remover').value = idconta;
					document.getElementById('form_remover_arquivo').submit();

					return true;
				} else {
					return false;
				}
			}
			</script>
			<form method="post" id="form_remover_arquivo" action="" style="padding-top:15px;">
				<input name="acao" type="hidden" value="remover_arquivo" />
				<input name="idarquivo" id="idarquivo" type="hidden" value="" />
				<input name="idconta_remover" id="idconta_remover" type="hidden" value="" />
			</form>
			<? } ?>
			
				<h4>Arquivos de contas</h4><br />
				<table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
					<tr>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_arquivo_nome"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_arquivo"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_conta"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_protocolo"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_opcoes"];?></strong></td>

					</tr>
					
					<?php if (count($arquivos_contas) > 0) {
						foreach($arquivos_contas as $documento) { ?>
					<tr>
						<td><span id="mensagem_retorno"><?php echo $documento["nome_arquivo"]; ?></span></td>
						<td><span id="mensagem_retorno"><?php echo $documento["arquivo_nome"]; ?></span></td>
						<td><span><?php echo $documento["idconta"]; ?></span></td>
						<td>
                         <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/editarprotocoloconta/<?= $documento["iddocumento"]; ?>" rel="facebox tooltip" data-original-title="<?= $idioma["documentos_matricula_protocolo_editar"]; ?>" data-placement="left">
                                      <?php if($documento["protocolo"]) {
                                        echo $documento["protocolo"];
                                      } else {
                                        echo '--';
                                      } ?>
                                    </a>
                        </td>
						<td>
						<?php if ($documento["arquivo_nome"]) { ?>
							<?php if (strpos($documento['arquivo_tipo'],'image') !== false) { ?>
								<a href="/<?= $url[0]; ?>/financeiro/contas/idconta/<?php echo $documento["idconta"]; ?>/pastavirtual/visualizararquivo/<?= $documento["iddocumento"]; ?>" class="fancybox btn btn-mini" rel="gallery" title="<?= $documento["tipo"].' ('.$documento["arquivo_nome"].')'; ?>"><i class="icon-picture"></i><?=$idioma["documentos_matricula_visualizar"];?></a>
							  <?php } ?>
							  <a href="/<?= $url[0]; ?>/financeiro/contas/idconta/<?php echo $documento["idconta"]; ?>/pastavirtual/downloadarquivo/<?= $documento["iddocumento"]; ?>" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_matricula_download"]; ?>" data-placement="left"><i class="icon-download-alt"></i></a>
							<?php } else { ?>
							  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:enviarArquivo(<?= $documento["idarquivo"]; ?>, <?= $documento["idconta"]; ?>);" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_matricula_enviar"]; ?>" data-placement="left"><i class="icon-upload"></i><?=$idioma["documentos_matricula_enviar"];?></a>
							<?php } ?>
							  <?php if($matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26", false)) { ?>
                                <a class="pull-right" href="javascript:void(0);" onclick="removerArquivo(<?= $documento["idarquivo"]; ?>,'<?php if($documento["arquivo_nome"]){ echo "(".$documento["arquivo_nome"].")"; } ?>',<?= $documento["idconta"]; ?>);"><img src="/assets/img/remover_16x16.gif" width="16" height="16" border="0" /></a>
							   <?php } ?>
						</td>


					</tr>
					<?php }
					} else { ?>
					<tr>
						<td colspan="7"><?=$idioma["nenhum_documento"];?></td>
					</tr>
					<?php } ?>
				</table>
                <script type="text/javascript">
                    function enviarArquivo(id, idconta) {
                      document.getElementById('idarquivo_enviar').value = id;
                      if(idconta){
                         document.getElementById('idconta_enviar').value = idconta; 
                      }
                      document.getElementById('arquivo_enviar').click();
                    }
                </script>
                <form action="" method="post" id="formEnviarArquivo" name="formEnviarArquivo" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="enviar_arquivo" />
                    <input type="hidden" name="idarquivo_enviar" id="idarquivo_enviar" value="" />
                    <input type="hidden" name="idconta_enviar" id="idconta_enviar" value="" />
                    <input type="file" id="arquivo_enviar" name="arquivo" style="display:none;" />
                </form>
			
				</div>
			</section>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <?php incluirLib("rodape",$config,$usuario); ?>
  <script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
</div>
<script>
$("#enviar-arquivo").on('click', function() {
	if (! document.getElementById('nome_arquivo').value) {
        alert('Informe o nome do arquivo antes de submeter o formulário.');
        return false;
    }
    if (! document.getElementById('protocolo').value) {
        alert('Informe o protocolo antes de submeter o formulário.');
        return false;
    }
  });
  
$(document).ready(function(){
	$('#arquivo_enviar').change(function(){
		$('#mensagem_retorno').html('Enviando...');
		$('#formEnviarArquivo').submit();
	});
});  
  
jQuery(document).ready(function($) {
    $('.fancybox').fancybox({
        type       : 'image',
		maxHeight  : 500,
        //prevEffect : 'none',
        //nextEffect : 'none',
        //closeBtn   : false,
        //helpers : {
        //  title : { type : 'inside' },
        //  buttons : {}
        //}
    });
});
</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>