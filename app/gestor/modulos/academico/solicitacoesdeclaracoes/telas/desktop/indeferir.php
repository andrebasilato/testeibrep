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
    		<li class="active"><?php echo $linha["nome"]; ?></li>
    	<span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
			<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
			<div class="tabbable tabs-left">
			<?php if($url[3] != "cadastrar") { incluirTela("inc_menu_edicao",$config,$linha); } ?>
        
                          <? if(count($remover["erros"]) > 0){ ?>
                              <div class="alert alert-error fade in">
                                  <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                    <strong><?= $idioma["form_erros"]; ?></strong>
                                        <? foreach($remover["erros"] as $ind => $val) { ?>
                                            <br />
                                            <?php echo $idioma[$val]; ?>
                                        <? } ?>
                                    </strong>
                                  </div>
                          <? } ?>    
                          <form method="post" action="" class="form-horizontal">
                          <input name="acao" type="hidden" value="indeferir" />
                          <input name="idindeferir" type="hidden" value="<?= $linha['idsolicitacao_declaracao']?>" /> 
                            <div class="control-group">
                              <p> 
								<br />
								<? printf($idioma["usuario_selecionado"],$linha["nome"]); ?>
                                <br /><br />
                                <?= $idioma["informacoes"]; ?> <br />
                              </p>                            
                                <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
                                <div class="controls" >
                                  <label class="checkbox" style="margin-left:110px;">
                                    <input name="remover" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="remover">
                                    <?= $idioma["confirmacao_formulario"]; ?>
                                  </label>
                                  <p class="help-block" style="margin-left:110px;"><?= $idioma["nota"]; ?></p>
                                </div>
                              </div>
                              <div class="form-actions">
                                  <input type="submit" class="btn btn-primary" value="<?= $idioma["cancelar_solicitacao"]; ?>">&nbsp;
                                  <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["cancelar_operacao"]; ?>" />
                              </div>
                    </form>
			</div>
        
        </div>
    </div> 
    <?php/*<div class="span3">
    	<?php  incluirLib("sidebar_".$url[1],$config); ?>    
    </div>*/?>
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>