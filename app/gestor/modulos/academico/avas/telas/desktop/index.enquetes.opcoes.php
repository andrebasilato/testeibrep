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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><? echo $linha["ava"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/enquetes"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/enquetes/<?= $linha["idenquete"]; ?>/editar"><?php echo $linha["idenquete"]; ?></a></li> <span class="divider">/</span></li>
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
          <?php if($url[5] != "cadastrar") { ?><h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2><?php } ?>
          <?php include("inc_submenu_enquetes.php"); ?>
          <div class="tab-pane active" id="tab_editar">
            <h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_opcoes"]; ?></h2>
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
                  <td><?php echo $idioma["form_ordem"]; ?></td>
                  <td><?php echo $idioma["form_opcao"]; ?></td>
                  <td></td>
              </tr>
              <tr>
                <td><input type="text" class="span1" name="ordem" id="ordem" maxlength="3" /></td>
                <td><input type="text" class="span6" name="opcao" id="opcao" /></td>
              <td>
				<input type="hidden" id="acao" name="acao" value="cadastrar_opcao">
				<input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
			  </td>
        </tr>
    </table>
</form>
<form method="post" id="remover_opcao" name="remover_opcao">
  <input type="hidden" id="acao" name="acao" value="remover_opcao">
  <input type="hidden" id="remover" name="remover" value="">
</form>
<form method="post" id="editar_opcao" name="editar_opcao">
  <input type="hidden" id="acao" name="acao" value="editar_opcao">
  <table class="table table-striped">
    <thead>
      <tr>
        <th width="80"><?= $idioma["tabela_ordem"]; ?></th>
        <th><?= $idioma["tabela_opcao"]; ?></th>
        <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
    </tr>
</thead>
<tbody>
  <?php if(count($opcoes) > 0) {
    $validacao = "";
    foreach($opcoes as $opcao) {
      $validacao .= '$("#ordem'.$opcao["idopcao"].'").keypress(isNumber); $("#ordem'.$opcao["idopcao"].'").blur(isNumberCopy); ';
      ?>
      <tr>
        <td><input type="text" maxlength="3" class="span1" name="opcoes[<?php echo $opcao["idopcao"]; ?>][ordem]" id="ordem<?php echo $opcao["idopcao"]; ?>" value="<?php echo $opcao["ordem"]; ?>" /></td>
        <td><?php echo $opcao["opcao"]; ?></td>
        <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $opcao["idopcao"]; ?>)"><i class="icon-remove"></i></a></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
  </tr>
  <?php } ?>
</tbody>
</table>
<div class="form-actions">
    <input class="btn btn-primary" type="submit" value="Salvar">
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
<? incluirLib("rodape",$config,$usuario); ?>
<script type="text/javascript">
function remover(id) {
  confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
  if(confirma) {
    document.getElementById("remover").value = id;
    document.getElementById("remover_opcao").submit();
}
}
var regras = new Array();
jQuery(document).ready(function($) {
  $("#ordem").keypress(isNumber);
  $("#ordem").blur(isNumberCopy);
  <?php echo $validacao; ?>
});
</script>
</div>
</body>
</html>