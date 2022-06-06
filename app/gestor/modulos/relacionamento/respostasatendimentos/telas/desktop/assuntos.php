<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style>
.row-fluid > .span4 {
  width: 28%;
}
</style>
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
          <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
            <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
            
            <div class="tabbable tabs-left">
			  <?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_editar">
                    <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                    <? if ($_POST["msg"]) { ?>
                        <div class="alert alert-success fade in">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                            <strong><?= $idioma[$_POST["msg"]];
                                unset($_POST["msg"]); ?></strong>
                        </div>
                    <? } ?>
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
                    <form method="post" onsubmit="return validateFields(this, regras)" enctype="multipart/form-data" class="form-horizontal">
                      <input name="acao" type="hidden" value="salvar_assuntos" />
                      <div class="page-header"> 
                      <label class="control-label"><strong><?php echo $idioma["sub_lista"]; ?></strong></label>
                        <div class="controls">
                          <label class="checkbox">
                            <?=$idioma["todos"];?> <input id="todos" name="todos" value="1" type="checkbox" <? if($linha["todos"]=='S') { echo ' checked="checked"'; } ?>>
                          </label>
                        </div>
                      </div>
                      <p class="span8"><?=$idioma["sub_selecione"];?></p>
                      <div class="container-fluid" id="check">
                      	<div class="row-fluid span8">
                        <? 
							foreach($dadosAssoc as $ind => $campo){
								$assuntos_check[] = $campo["idassunto"];
							}
							foreach($dadosArray as $ind => $campo) {
								$i++;
						?>
                          <div class="span4">
                            <label class="checkbox">
                              <input name="assuntos[<?= $ind; ?>]" value="<?= $campo["idassunto"]; ?>" type="checkbox" <? if(in_array($campo["idassunto"],$assuntos_check)) { echo ' checked="checked"'; } ?>>
                              <?= $campo["nome"]; ?>
                            </label>
                          </div>
						  <?  if($i % 2 == 1) echo "</div><div class='row-fluid span8'>"; ?>
                        <? } ?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <input type="submit" class="btn btn-primary" value="<?= $idioma["salvar"]; ?>">&nbsp;
                          <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["cancelar"]; ?>" />
                      </div>
                    </form>
				</div>
              </div>
            </div>
        </div>
    </div> 
  </div>
<? incluirLib("rodape",$config,$usuario); ?>
    <script type="application/javascript">
        jQuery("#todos").on('click', function(){
            if($(this).attr('checked') == 'checked') {
                jQuery('#check input').attr('checked', 'checked');
                return;
            }
            jQuery('#check input').removeAttr('checked');
            return;
        });
    </script>
</div>
</body>
</html>