<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style>.hidden{margin-top:-75px;}
option[disabled] {
	background-color: #F5F5F5;
	border-color: #DDDDDD;
	cursor: not-allowed;
}
</style>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
<? incluirLib("topo",$config,$usu_vendedor); ?>
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
		
		  <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["sair_edicao"]; ?></a></div>
                    <h2 class="tituloEdicao"><?= $linha["nome"]; ?></h2>
                <div class="tabbable tabs-left">
                <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?= $idioma["legendadadosdados"];  ?></h2>
		
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
                          <?php foreach($salvar["erros"] as $ind => $val) { ?>
                              <br />
                              <?php echo $idioma[$val]; ?>
                          <?php } ?>
                      </strong>
                    </div>
            <? } ?>
            <legend><?= $idioma["legendacadastrar"] ?></legend>
            <form method="post" onsubmit="return validateFields(this, regras)" class="form-horizontal">
                <input name="acao" type="hidden" value="salvar_mensagem" />
                <div class="control-group">
                    <label class="control-label" for="idlocal">Mensagem:</label>
                    <div class="controls">
                        <textarea name="mensagem" id="mensagem" class="span6"></textarea>
                        </br></br>
                        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_cadastrar"]; ?>">&nbsp;
                    </div>
                </div>
            </form>
        </br></br>
        <legend><?= $idioma["legendadadosdados"] ?></legend>
        <?php 
            $totalMensagens = count($mensagensVisita);
            if ($totalMensagens > 0) { 
                $count = 0;
                foreach ($mensagensVisita as $mensagem) { 
                    $count++;
        ?>
<div class="mensagens">
                <div class="mensagens-cabecalho">
                    <img src="/api/get/imagens/usuariosadm_avatar/40/40/<?php echo $mensagem["avatar_servidor"]; ?>" class="img-circle">
                    <span class="mensagens-id"># <?= $mensagem["idmensagem"] ?></span>
                    <span class="mensagens-data"><?= formataData($mensagem["data_cad"],"br",1); ?></span>
                    <span class="mensagens-usuario">
					
                        <?php 
                            if ($mensagem["usuario"]) {
                                echo $mensagem["usuario"]; 
                            } else {
                                echo $mensagem["vendedor"].' (Vendedor)';
                            }
                        ?>                    
                    
                    </span><p />

                    </div>
                <div class="mensagens-conteudo">
                <?php echo nl2br($mensagem["mensagem"]); ?>
                      <?php if (($mensagem["idusuario"] == $usuario["idusuario"]) || (!$mensagem["idusuario"])) { ?>
                        <a class="btn btn-mini" href="javascript:void(0);" onclick="removerMensagem(<?= $mensagem["idmensagem"]; ?>);" >
                            <span class="icon-remove"></span> 
                            <?php echo $idioma["mensagem_excluir"]; ?>
                        </a>
                      <?php } ?>

                </div>
                <div style="clear: both; line-height: 0;">&nbsp;</div>
              </div>  
        <?php }  ?>
            <script>
                function removerMensagem(id) {
                    var msg = "<?=$idioma["mensagem_confirmar_remover"];?>";
                    var confirma = confirm(msg);
                    if(confirma){
                        document.getElementById('idmensagem').value = id;
                        document.getElementById('form_remover_mensagem').submit();
                        return true;  
                    } else {
                        return false; 
                    }
                }
            </script>
            <form method="post" id="form_remover_mensagem" action="" style="padding-top:15px;">
              <input name="acao" type="hidden" value="remover_mensagem" />
              <input name="idmensagem" id="idmensagem" type="hidden" value="" />
            </form>
        <?php } else { ?>
            <table>
                <tr>
                    <td><?= $idioma['sem_mensagem'] ?></td>
                </tr>
            </table>
        <?php } ?>
		
		</div>
    </div>
    </div>
		
        </div>
    </div>
  </div>
<? incluirLib("rodape",$config,$usu_vendedor); ?>
<script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
<script src="/assets/js/ajax.js"></script>

<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.js"></script>
<script type="text/javascript">
	function desabilitarMatriculado() {
		situacoes = document.getElementById('form_situacao');
		
		for(i = 0; i < situacoes.length; i++) {
			if(situacoes[i].value == 'MAT') {
				situacoes[i].disabled = true;
			}
		}
	}
</script>

</div>
</body>
</html>
