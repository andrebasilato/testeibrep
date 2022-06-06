<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<style>
.botao {
	height:80px;
	padding-top: 50px;
	padding-bottom:50px;
	font-size:18px;
}
</style>
</head>

<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_configuracoes"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <? if($url[4] == "editar") { ?>
      <li class="active"><?php echo $linha["nome"]; ?></li>
      <? } else { ?>
      <li class="active"><?= $idioma["nav_formulario"]; ?></li>
      <? } ?>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        	<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            <h2 class="tituloEdicao"><?= $linha["nome"]; ?> </h2>
            
            <div class="tabbable tabs-left">
			  <?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_editar">
					<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
				
					<?php if ($linha["situacao"] < 2) { ?>
						<div class="alert alert-error fade in">
						  <strong><?php echo $idioma['erro_nao_finalizada']; ?></strong>
						</div>
					<?php } ?>
					<br />                        
					<?php echo $idioma['resumo_alteracao']; ?>
					<span style="color:#999999"><?php echo $idioma['resumo_alteracao_obs']; ?></span>
					
					<form method="post">
						<?php if ($linha["situacao"] == 2) { ?>
						<input type="hidden" value="reenviar_pesquisa" name="act" />
						<?php } ?>
						<br />                        
						<div class="row-fluid">
							<input type="submit" class="botao btn" id="desativarLogin" value="<?php echo $idioma['reenviar_pesquisa']; ?>" <?php if ($linha["situacao"] < 2) { ?> disabled="disabled" <?php } ?> />				
						</div>  
					</form>
                          
				</div>
              </div>
            </div>
                                           
        </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>

</div>
</body>
</html>