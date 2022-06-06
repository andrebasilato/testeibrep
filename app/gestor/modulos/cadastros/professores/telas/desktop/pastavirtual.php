<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
<style type="text/css">

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
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/professores"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_professor"]; ?> <?php echo $linha["nome"]; ?> </li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" >

        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
        </div>
		<h2 class="tituloEdicao"><?= $linha["nome"]; ?> </h2>

		<div class="tabbable tabs-left">
		<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>


        <div class="tab-content">
            <div class="tab-pane active" id="tab_editar">
            
            <section id="pastavirtual">
				<h2 class="tituloOpcao">Pasta Virtual</h2>
				
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
				
			   <div id="pastavirtual_ancora_div">
				<?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", false)) { ?>
				<form method="post" action="" style="padding-top:15px;" enctype="multipart/form-data">
					<input name="acao" type="hidden" value="adicionar_arquivo" />
					<table style="width:500px;" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed ">
						<tr>
							<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_professor_arquivo"];?></strong></td>
							<td bgcolor="#F4F4F4" width="100"><strong>Opções</strong></td>
						</tr>
						<tr>
							<td><input name="documento" type="file" id="arquivo"/></td>
							<td><input class="btn btn-mini" type="submit" id="enviar-arquivo" value="<?php echo $idioma["btn_adicionar"]; ?>" /></td>
						</tr>
					</table>
				</form>
				<? } ?>
				<br />
				<table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
					<tr>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_professor_arquivo"];?></strong></td>
						<td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_professor_opcoes"];?></strong></td>
					</tr>
					
					<?php if (count($arquivos) > 0) {
						foreach($arquivos as $documento) { ?>
					<tr>
						<td><span id="mensagem_retorno"><?php echo $documento["arquivo_nome"]; ?></span></td>
						<td>
						<?php if ($documento["arquivo_nome"]) { ?>
							<?php if (strpos($documento['arquivo_tipo'],'image') !== false) { ?>
								<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/visualizararquivo/<?= $documento["iddocumento"]; ?>" class="fancybox btn btn-mini" rel="gallery" title="<?= $documento["tipo"].' ('.$documento["arquivo_nome"].')'; ?>"><i class="icon-picture"></i><?=$idioma["documentos_professor_visualizar"];?></a>
							  <?php } ?>
							  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/downloadarquivo/<?= $documento["iddocumento"]; ?>" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_professor_download"]; ?>" data-placement="left"><i class="icon-download-alt"></i></a>
							<?php } else { ?>
							  <a href="javascript:enviarArquivo(<?= $documento["iddocumento"]; ?>);" class="btn btn-mini" rel="tooltip" data-original-title="<?php echo $idioma["documentos_professor_enviar"]; ?>" data-placement="left"><i class="icon-upload"></i><?=$idioma["documentos_professor_enviar"];?></a>
							<?php } ?>
							  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", false)) { ?>
								<a href="javascript:void(0);" onclick="removerArquivo(<?= $documento["idarquivo"]; ?>,'<?= $documento["tipo"]; ?> (<?= $documento["arquivo_nome"]; ?>)');"><img src="/assets/img/remover_16x16.gif" width="16" height="16" border="0" /></a>
							   <? } ?>
						</td>


					</tr>
					<? }
					} else { ?>
					<tr>
						<td colspan="7"><?=$idioma["nenhum_documento"];?></td>
					</tr>
					<? } ?>
				</table>

			<?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", false)) { ?>
			<script type="text/javascript">
			function removerArquivo(id,nome) {

				var msg = "<?=$idioma["confirm_remover_documento"];?>";
				msg = msg.replace("[[nome]]", nome);
				var confirma = confirm(msg);

				if (confirma) {
					document.getElementById('idarquivo').value = id;
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
			</form>
			<? } ?>
				</div>
			</section>
            

            

          </div>
        </div>
		
		</div>
		
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
  <script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
</div>

<script>
$("#enviar-arquivo").on('click', function() {
    if (! document.getElementById('arquivo').value) {
        alert('Selecione um arquivo antes de submeter o formulário.');
        return false;
    }
  });
  
jQuery(document).ready(function($) {
    $('.fancybox').fancybox({
        type       : 'image',
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

</body>
</html>