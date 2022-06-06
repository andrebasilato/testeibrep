<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
  <? incluirLib('topo', $config, $usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
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
          <div class=" pull-right">
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small">
              <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?>
            </a>
          </div>
          <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
           <?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
           <div class="tab-content">
            <div class="tab-pane active" id="tab_editar">
              <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

              <div id="form_cadastro" style="display: none">
                <form method="post" enctype="multipart/form-data">
                  <input type="hidden" name="acao" value="cadastrar_pagina">
                  <?php $linhaObj->gerarFormulario('formulario_paginas', null, $idioma) ?>
                  <input type="submit" value="Cadastrar" class="btn btn-primary">
                </form>
              </div>

              <?php if (isset($_POST['error'])) { ?>
              <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?php echo $idioma[$_POST['error']]; ?>
              </div>
              <?php } ?>


              <?php if (isset($_POST['msg'])) { ?>
              <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <b><?php echo $idioma[$_POST['msg']]; ?></b>
            </div>
              <?php } ?>


              <a href="#form_cadastro" class="btn" rel="facebox"><?php echo $idioma['cadastrar']; ?></a>

          <div class="controls" style=" float: right;">
            <a name="botao_variaveis_pessoa" href="<?php echo Request::url('0-3', '/'); ?>variaveis" id="form_botao_variaveis_pessoa" class="btn" rel="facebox" style="outline:none;">
              <i class="icon-list-alt"></i> Variáveis</a>

            <p />

        <br />
    </div>

              <?php


              // Troca informações banco de dados para realizar consultas pelo Core em outra tabela
              $linhaObj->config['banco']['tabela'] = Certificados::PAGES_TABLE;
              $linhaObj->config['formulario'] = $config["paginas_lista"];

              $linhaObj->gerarTabela($linhaObj->listarPaginas(), $_GET['q'], $idioma, 'paginas_lista'); ?>

              <table style="table table-striped" style="width: 100%">
                <?php $paginas = $linhaObj->listarPaginas(); ?>
              </table>

              <?php if( count($salvar["erros"]) > 0) { ?>
              <div class="alert alert-error fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma["form_erros"]; ?></strong>
                <?php foreach($salvar["erros"] as $ind => $val) { ?>
                <br />
                <?php echo $idioma[$val]; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <? incluirLib("rodape", $config, $usuario); ?>

</div>
</body>
</html>
