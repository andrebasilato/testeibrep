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
                      <td><?php echo $idioma["form_ordem"]; ?></td>
                      <td><?php echo $idioma["form_nome"]; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><input type="text" class="span1" name="ordem" id="form_ordem" maxlength="3" /></td>
                      <td><input type="text" class="span4" name="nome" id="form_nome" maxlength="100" /></td>
                      <td>
                        <input type="hidden" id="acao" name="acao" value="cadastrar_bloco">
                        <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                      </td>
                    </tr>
                  </table>
                </form>                            
                <form method="post" id="remover_bloco" name="remover_bloco">
                  <input type="hidden" id="acao" name="acao" value="remover_bloco">
                  <input type="hidden" id="remover" name="remover" value="">
                </form>
                <form method="post" id="editar_bloco" name="editar_bloco" onsubmit="return validateFields(this, regras_editar);">
                  <input type="hidden" id="acao" name="acao" value="editar_bloco">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th width="80"><?= $idioma["tabela_ordem"]; ?></th>
                        <th><?= $idioma["tabela_bloco"]; ?></th>
                        <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($blocos) > 0) {
						$validacao = "";
                        foreach($blocos as $bloco) { 
						  $validacao .= '$("#ordem'.$bloco["idbloco"].'").keypress(isNumber); $("#ordem'.$bloco["idbloco"].'").blur(isNumberCopy); ';
                          $validacao .= 'regras_editar.push("required,ordem'.$bloco["idbloco"].','.$idioma["ordem_vazio"].'"); ';
						  ?>
                          <tr>
                            <td><input type="text" maxlength="3" class="span1" name="blocos[<?php echo $bloco["idbloco"]; ?>][ordem]" id="ordem<?php echo $bloco["idbloco"]; ?>" value="<?php echo $bloco["ordem"]; ?>" /></td>
                            <td><?php echo $bloco["nome"]; ?></td>
                            <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $bloco["idbloco"]; ?>)"><i class="icon-remove"></i></a></td>
                          </tr>
                        <?php } ?>
                      <?php } else { ?>
                        <tr>
                          <td colspan="3"><?= $idioma["sem_informacao"]; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table> 
                  <?php if(count($blocos) > 0) { ?>
                    <div class="form-actions">
                      <input class="btn btn-primary" type="submit" value="Salvar">
                    </div>                  
				  <?php } ?>
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
		  document.getElementById("remover_bloco").submit();
		} 
	  }
	  var regras = new Array();
	  regras.push("required,form_ordem,<?= $idioma["ordem_vazio"]; ?>");
	  regras.push("required,form_nome,<?= $idioma["nome_vazio"]; ?>");
	  
	  var regras_editar = new Array();
	  
	  jQuery(document).ready(function($) {
		$("#form_ordem").keypress(isNumber);
		$("#form_ordem").blur(isNumberCopy);
		<?php echo $validacao; ?>
	  });
    </script>
  </div>
</body>
</html>