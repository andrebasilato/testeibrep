<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head", $config, $usuario); ?>
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
        <li class="active"><?php echo $idioma["remover"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
          <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
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
                 <?php  if(isset($_POST["msg"])){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                      <br /><br />
                      <?php echo $idioma[$_POST['msg']]; ?>

                  </div>
                <? } ?>
                <form method="post" action="" class="form-horizontal">
                  <input name="acao" type="hidden" value="remover" />
                  <div class="control-group">
                  <p>
                    <? printf($idioma["usuario_selecionado"],$linha["nome"], $linha[$config["banco"]["primaria"]]); ?>
                    <br />
                    <br />
                    <?= $idioma["informacoes"]; ?> <br />
                  </p>
                  <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="remover" id="enviar" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="remover">
                        <?= $idioma["confirmacao_formulario"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota"]; ?></p>
                    </div>
                  </div>
                  <?php // Informa se tem ofertas vinculadas
                    if($ofertas){

                    echo '<div class="alert alert-error fade in">' . $idioma["aviso_ofertas"];

                    foreach ($ofertas as $oferta){?>
                        • <?= $oferta['nome'] ?> (Oferta-Curso ID: <?= $oferta['idoferta_curso']?>) <a href="/<?= $url[0]; ?>/academico/ofertas/<?=$oferta['idoferta']?>/cursos/<?= $oferta['idoferta_curso']?>/academico"> - <u>Acessar</u> </a></br>
                    <?php }
                    echo '</div>';
                    } ?>
                  <div class="form-actions">
                    <input type="submit" id="remover" class="btn btn-primary" value="<?= $idioma["remover"]; ?>">&nbsp;
                    <button type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>');"><?= $idioma["cancelar"]; ?></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      <script>
          document.getElementById('remover').onclick = function() {
              if (! document.getElementById('enviar').checked) {
                  window.alert('Para remover é necessário marcar a mensagem de confirmação.');
                  return false;
              }
              document.parent.location.href = '<?php echo Request::url('0-3', '/') . $page['idcertificado'] .'/removerpagina/id/' . Request::url('4'); ?>';
              return true;
          };
      </script>
	<? incluirLib("rodape",$config,$usuario); ?>
  </div>
</body>
</html>