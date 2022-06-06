<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
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
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?php echo $linha["nome"]; ?></h2>
          <div class="tabbable tabs-left">
			<?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
				<h2 class="tituloOpcao"><?php echo $idioma["titulo_opcao"]; ?></h2>	
                <div class="alert alert-success fade in" style="display:none;" id="exibirNovaSenha">
                  <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                  <strong id="novaSenha"></strong>
                </div> 
                <div class="form-horizontal">
                  <div class="control-group">
                    <p>
                      <? printf($idioma["usuario_selecionado"],$linha["nome"], $linha[$config["banco"]["primaria"]]); ?>
                      <br />
                      <?= $idioma["informacoes"]; ?>
                      <br />
                    </p>                            
                    <label class="control-label" for="optionsCheckboxList"><?= $idioma["confirmacao"]; ?></label>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="confirmacao" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="confirmacao">
                        <?= $idioma["confirmacao_formulario"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota_confirmacao"]; ?></p>
                    </div>
                    <br />
                    <label class="control-label" for="optionsCheckboxList"><?= $idioma["adicionais"]; ?></label>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="enviar_email" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="enviar_email">
                        <?= $idioma["enviar_email"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota_enviar_email"]; ?></p>
                    </div>
                    <div class="controls">
                      <label class="checkbox">
                        <input name="exibir_nova_senha" value="<?= $linha[$config["banco"]["primaria"]]; ?>" type="checkbox" id="exibir_nova_senha">
                        <?= $idioma["exibir_nova_senha"]; ?>
                      </label>
                      <p class="help-block"><?= $idioma["nota_exibir_nova_senha"]; ?></p>
                    </div>
                  </div>
                  <div class="form-actions">
                    <a href="javascript:void(0);" class="btn btn-primary" id="resetar" onclick="resetarSenha();"><?= $idioma["resetar"]; ?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>                             
        </div>
      </div>
    </div>
  	<? incluirLib("rodape",$config,$usuario); ?>
    <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
  	<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
  	<script type="text/javascript">
	  function resetarSenha(){
		var confirmacao = $("#confirmacao").attr("checked");
		if(!confirmacao) {
		  alert("<?= $idioma["resetar_vazio"]; ?>");
		  return false;
		}
		var enviarEmail = $("#enviar_email").attr("checked");
		var exibirNovaSenha = $("#exibir_nova_senha").attr("checked");
			
		$.msg({ 
		  autoUnblock : false,
		  clickUnblock : false,
		  klass : 'white-on-black',
		  content: '<?= $idioma["resetar_aguarde"]; ?>',
		  afterBlock : function(){
			var self = this;
			jQuery.ajax({
			  url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/resetar_senha",
			  dataType: "json", //Tipo de Retorno
			  type: "POST",
			  data: {confirmacao: confirmacao, enviar_email: enviarEmail, exibir_nova_senha: exibirNovaSenha, },
			  success: function(json){ //Se ocorrer tudo certo
				if(json.sucesso){
				  if(json.exibir_nova_senha) {
					var msg_email;
					if (!json.sucesso_email) 
					  msg_email = '';
					else 
					  msg_email = '<?= $idioma["sucesso_email"]; ?>';
					$("#novaSenha").html('<?= $idioma["mensagem_nova_senha"]; ?><br><span style="font-size:14px">'+json.nova_senha+'</span><br>'+msg_email);
					$("#exibirNovaSenha").show();
				  } else {
					var msg_email;
					if (!json.sucesso_email) 
					  msg_email = '';
					else 
					  msg_email = '<?= $idioma["sucesso_email"]; ?>';
					$("#novaSenha").html('<?= $idioma["sucesso"]; ?><br>'+msg_email);
					$("#exibirNovaSenha").show();
				  }
				  $("#confirmacao").attr("checked",false);
				  $("#enviar_email").attr("checked",false);
				  $("#exibir_nova_senha").attr("checked",false);
				  self.unblock();
				} else {
				  alert('<?= $idioma["json_erro"]; ?>');
				  self.unblock();	
				}													 
			  }
			});
		  }
		});
	  }			
    </script>
  </div>
</body>
</html>