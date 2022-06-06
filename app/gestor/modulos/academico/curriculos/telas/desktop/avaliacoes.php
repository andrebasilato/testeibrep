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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/dadosgerais"><?php echo $linha["nome"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["nav_formulario"]; ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
  	</ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao_editar"]; ?></h2>
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
                <form class="well wellDestaque form-inline" method="post" onsubmit="return validateFields(this, regras);">                            
                  <table>
                    <tr>
                      <td><?php echo $idioma["form_avaliacao"]; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>
                        <select class="span3" name="avaliacao" id="avaliacao">
                          <option value=""></option>
						  <?php foreach($GLOBALS["tipo_avaliacao"][$GLOBALS["config"]["idioma_padrao"]] as $ind => $val) { ?>                             
                            <option value="<?php echo $ind; ?>"><?php echo $val; ?></option>
                          <?php } ?> 						  
                        </select>
                      </td>
                      <td>
                        <input type="hidden" id="acao" name="acao" value="cadastrar_avaliacao">
                        <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                      </td>
                    </tr>
                  </table>
                </form>                            
                <form method="post" id="remover_avaliacao" name="remover_avaliacao">
                  <input type="hidden" id="acao" name="acao" value="remover_avaliacao">
                  <input type="hidden" id="remover" name="remover" value="">
                </form>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th><?= $idioma["tabela_avaliacao"]; ?></th>
                      <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($avaliacoes) > 0) {
                      foreach($avaliacoes as $avaliacao) { ?>
                        <tr>
                          <td><?php echo $GLOBALS["tipo_avaliacao"][$GLOBALS["config"]["idioma_padrao"]][$avaliacao["avaliacao"]]; ?></td>
                          <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $avaliacao["idavaliacao"]; ?>)"><i class="icon-remove"></i></a></td>
                        </tr>
                      <?php } ?>
                    <?php } else { ?>
                      <tr>
                        <td colspan="2"><?= $idioma["sem_informacao"]; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript">
	  var regras = new Array();
	  regras.push("required,avaliacao,<?= $idioma["avaliacao_vazio"]; ?>");
	  
	  function remover(id) {
		confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
		if(confirma) {
		  document.getElementById("remover").value = id;
		  document.getElementById("remover_avaliacao").submit();
		} 
	  }
    </script>
  </div>
</body>
</html>