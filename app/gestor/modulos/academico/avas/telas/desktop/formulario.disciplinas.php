<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <link href="/assets/css/menuVertical.css" rel="stylesheet" />
</head>
<body>
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? echo $linha["nome"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/disciplinas"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["nav_formulario"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
</section>
<div class="row-fluid">
  <div class="span12">
    <div class="box-conteudo box-ava">
      <div class="tabbable tabs-left">
        <?php incluirTela("inc_submenu",$config,$linha); ?>
        <div class="ava-conteudo">
          <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
          <div class="tab-pane active" id="tab_editar">
            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_objetos"]; ?></h2>
            <? if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <strong><?= $idioma[$_POST["msg"]]; ?></strong>
              </div>
            <? } ?>
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
            <form class="well wellDestaque form-inline" method="post">
              <table>
                <tr>
                  <td><?php echo $idioma["form_disciplina"]; ?></td>
                  <td></td>
                </tr>
                <tr>
                  <td>
                    <select class="span4" name="iddisciplina" id="iddisciplina">
                      <option value=""></option>
					  <?php foreach($disciplinas as $disciplina) { ?>
                        <option value="<?php echo $disciplina["iddisciplina"]; ?>"><?php echo $disciplina["nome"]; ?></option>
					  <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="hidden" id="acao" name="acao" value="cadastrar_disciplina">
                    <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                  </td>
                </tr>
              </table>
            </form>
            <form method="post" id="remover_disciplina" name="remover_disciplina">
              <input type="hidden" id="acao" name="acao" value="remover_disciplina">
              <input type="hidden" id="remover" name="remover" value="">
            </form>
            <form method="post" id="tempo_offline_disciplina" name="tempo_offline_disciplina">
            <input type="hidden" id="acao" name="acao" value="salvar_horas_offline">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th style="width:85%;"><?= $idioma["tabela_disciplina"]; ?></th>
                  <th style="width:10%;"><?= $idioma["tabela_offline"]; ?></th>
                  <th style="width:5%;"><?= $idioma["tabela_opcoes"]; ?></th>
                </tr>
              </thead>
              <tbody>
              
                <?php 
                if(count($disciplinasAva) > 0) {
                  foreach($disciplinasAva as $disciplinaAva) { ?>
                    <tr>
                      <td><?php echo $disciplinaAva['nome']; ?></td>
                      <td>
                        <input type="text" style="width:75%;" name="tempo_offline[]" id="tempo_offline<?php echo $disciplinaAva['idava_disciplina']; ?>" value="<?php echo $disciplinaAva['tempo_offline']; ?>">
                        <input type="hidden" name="idava_disciplina[]" id="idava_disciplina<?php echo $disciplinaAva['idava_disciplina']; ?>" value="<?php echo $disciplinaAva['idava_disciplina']; ?>">
                      </td>
                      <td> <?php if($disciplinaAva['idavaliacao'] == NULL){?>
                          <a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $disciplinaAva['idava_disciplina']; ?>)"><i class="icon-remove"></i></a></td>
                        <?php } ?>
                    </tr>
                  <?php } ?>
                  <tr>
                      <td colspan="3">
                        <input type="submit" class="btn btn-primary" value="Salvar">&nbsp;
                        <input type="reset" class="btn" onclick="MM_goToURL('parent','/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>');" value="Cancelar">
                      </td>
                    </tr>
                <?php } else { ?>
                  <tr>
                    <td colspan="6"><?= $idioma["sem_informacao"]; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script> 
<script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function($) {
  <?php 
  if(count($disciplinasAva) > 0) {
  foreach($disciplinasAva as $disciplinaAva) { ?>
  $("#tempo_offline<?php echo $disciplinaAva['idava_disciplina']; ?>").mask("99:99:99");
  <?php } } ?>
  });
  function remover(id) {
	confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
	if(confirma) {
	  document.getElementById("remover").value = id;
	  document.getElementById("remover_disciplina").submit();
	}
  }
</script>
</div>
</body>
</html>