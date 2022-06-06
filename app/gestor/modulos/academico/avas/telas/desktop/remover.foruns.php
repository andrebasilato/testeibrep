<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $forum["idava"]; ?>/editar"><? echo $forum["ava"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $forum["idava"]; ?>/foruns"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $forum["nome"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_submenu",$config,$forum); ?>
            <div class="ava-conteudo"> 
              <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
              <h2 class="tituloEdicao"><?php echo $forum["nome"]; ?></h2>
              <?php include("inc_submenu_foruns.php"); ?>
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_remover"]; ?></h2>
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
                <form method="post" action="" class="form-horizontal">
                  <input name="acao" type="hidden" value="remover_forum" />
                  <div class="control-group">
                    <p> 
                      <br />
                      <? printf($idioma["usuario_selecionado"],$forum["nome"]); ?>
                      <br />
                      <br />
                      <?= $idioma["informacoes"]; ?>
                      <br />
                    </p>                            
                    <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="remover" value="<?= $forum[$config["banco_foruns"]["primaria"]]; ?>" type="checkbox" id="remover">
                        <?= $idioma["confirmacao_formulario"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota"]; ?></p>
                    </div>
                    <div class="form-actions">
                      <input type="submit" class="btn btn-primary" value="<?= $idioma["remover"]; ?>">&nbsp;
                      <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');" value="<?= $idioma["cancelar"]; ?>" />
                    </div>
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