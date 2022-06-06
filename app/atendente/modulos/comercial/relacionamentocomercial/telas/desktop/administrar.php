<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<style type="text/css">
.status {
  cursor:pointer;
  color:#FFF;
  font-size:9px;
  font-weight:bold;
  padding:5px;
  text-transform: uppercase;
  white-space: nowrap;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  margin-right:5px;
  line-height: 30px;
}
.ativo {
  font-size:15px;
}
.inativo {
  background-color:#838383;
}
#menuEsquerda {
}
#portamento_container {
  position:relative;
}
#portamento_container #menuEsquerda {
  position:absolute;
}
#portamento_container #menuEsquerda.fixed {
  position:fixed;
  margin-top: 90px;
}
.tituloEdicao {
  font-size:45px;
}
legend {
  line-height:25px;
  margin-bottom: 5px;
  margin-top: 20px;
}
.botao {
  height:100px;
  margin-top: 15px;
  padding-bottom:0px;
  float:left;
  padding-top:40px;
  height:58px;
  text-transform:uppercase;
}
legend {
	background-color: #F4F4f4;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	padding: 5px 5px 5px 15px;
	width: 98%;	
}
</style>
</head>
<body>
<?php incluirLib("topo",$config,$usu_vendedor); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_ficha"]; ?> #<?php echo $url[3]; ?> <span class="divider">/</span></li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i><?= $idioma["btn_sair"]; ?></a> 
        </div>
        <h2 class="tituloEdicao"><?= $idioma["ficha"]; ?> #<?= $url[3]; ?>
          <br />
          <small style="text-transform:uppercase;"><?= $idioma["data_abertura"]; ?> <?=formataData($dadospessoais["data_cad"], "br", 1);?></small> 
        </h2>
        <div class="row-fluid">
          <div class="span2">
            <div class="well" style="padding: 8px 0pt; width:180px;" id="menuEsquerda">
              <ul class="nav nav-list">
                <li class="nav-header active"><a><?=$idioma["menu_navegacao"];?></a></li>
                <li><a href="#dadosrelacionamento"><?=$idioma["menu_dados_relacionamento"];?></a></li>
                <li><a href="#mensagens"><?=$idioma["menu_mensagens"];?></a></li>
              </ul>
            </div>
          </div>
          <div class="span10">
              <? if($mensagem["erro"]) { ?>
                    <div class="alert alert-error"> 
                      <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                      <?= $idioma[$mensagem["erro"]]; ?>
                    </div>
                    <script>alert('<?= $idioma[$mensagem["erro"]]; ?>');</script>
              <? }?>
              <? if($_POST["msg"]) { ?>
                <div class="alert alert-success fade in"> 
                  <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> 
                  <strong><?= $idioma[$_POST["msg"]]; ?></strong> 
                </div>
                <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
              <? } ?>
          <section id="dadosrelacionamento" style="#000">
              <legend><?= $idioma["label_dadosrelacionamento"]; ?></legend>
              <form method="post" action="" onsubmit="return validateFields(this, regras_dados_relacionamento)" enctype="multipart/form-data" class="form-horizontal">
                  <input name="acao" type="hidden" value="alterar_dados_relacionamento" />
                <table>
                    <tr>
                        <td style="padding-right:20px;padding-bottom:20px;">
                            <strong><?= $idioma['email_pessoa']; ?></strong></br>
                            <input id="form_email_pessoa" class="span4 inputGrande" type="text" value="<?php echo $linha["email_pessoa"]; ?>" name="email_pessoa">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-right:20px; padding-bottom:20px;">
                            <strong><?= $idioma['nome_pessoa']; ?></strong></br>
                            <input id="form_nome_pessoa" class="span4 inputGrande" type="text" value="<?php echo $linha["nome_pessoa"]; ?>" name="nome_pessoa">
                        </td>
                    </tr> 

                    <tr>
                        <td style="padding-right:20px;">
                            <strong><?= $idioma['ativo_painel']; ?></strong></br>
                            <select name="ativo_painel" id="form_ativo_painel" class="span2 inputGrande">
                                <option value="S"<? if($linha["ativo_painel"] == "S") { ?> selected="selected"<? } ?>><?= $sim_nao[$config["idioma_padrao"]]["S"]; ?></option>
                                <option value="N"<? if($linha["ativo_painel"] == "N") { ?> selected="selected"<? } ?>><?= $sim_nao[$config["idioma_padrao"]]["N"]; ?></option>
                            </select>
                        </td>
                    </tr>  
                </table>
                <br />
                <div id="div_valor_contrato_qtd_parcelas" style="display:none">
                  <table>
                    <tr>
                        <td style="padding-right:20px;">
                            <strong><?= $idioma['financeiro_valor']; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-right:20px;">
                            <input id="form_valor_contrato" class="span2 inputGrande" type="text" value="<?php echo number_format($matricula["valor_contrato"],2,",","."); ?>" name="valor_contrato">
                        </td>
                    </tr>
                  </table>
                </div>
                <br />
                <input id="btn_submit" class="btn btn-primary" type="submit" value="<?=$idioma["btn_salvar"];?>">
              </form>
          </section>
          <section id="mensagens" style="#000">
            <legend><?= $idioma["label_relacionamento"]; ?></legend>
              <div class="accordion" id="accordion_mensagens"> 
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_mensagens" href="#cadastrar_mensagem"><?= $idioma["mensagem_cadastrar"]; ?></a>
                  </div>
                  <div id="cadastrar_mensagem" class="accordion-body collapse">
                    <div class="accordion-inner">
                      <form name="form_mensagens" method="post" onsubmit="return validaMensagem();" id="form_mensagens" >
                      		<input type="hidden" name="idrelacionamento" id="idrelacionamento" value="<?php echo $url[3];?>" >
                        <div style="border:#CCC solid 1px; padding-bottom:10px; width:100%" class="row-fluid"> 
                          <div style="width:95%; padding-left:15px;">
                            <br />
                            <small><strong><?php echo $idioma["mensagem_texto"]; ?></strong></small>
                            <br />
                            <textarea name="mensagem" id="mensagem" rows="5" style="width:65%;"></textarea>
							<strong style="margin-left:10px;"><?= $idioma['mensagem_proxima_acao'] ?></strong><input type="text" name="proxima_acao" id="proxima_acao" style="margin-left:5px;">
                            <br /> 
                            <br /> 
							<input type="checkbox" id="enviar_email" name="enviar_email" style="float: left; margin-right: 5px"><label for="enviar_email">Enviar por email</label>
                            <input type="hidden" name="acao" value="salvar_mensagem"> 
                            <br />
                            <input type="submit" class="btn btn-primary" name="enviar" value="<?php echo $idioma["btn_cadastrar"]; ?> " />               
                          </div>          
                        </div>           
                      </form>
                    </div>
                  </div>
                </div>
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_mensagens" href="#mensagens_cadastradas"><?= $idioma["mensagens_cadastradas"]; ?></a>
                  </div>
                  <div id="mensagens_cadastradas" class="accordion-body collapse in">
                    <div class="accordion-inner" style="max-height:400px; overflow:auto;">
                      <?php 
              					  $totalMensagens = count($mensagensPessoa);
              					  if ($totalMensagens > 0) { 
              						$count = 0;
              						foreach ($mensagensPessoa as $mensagem) { 
              						  $count++;
						          ?>
                          <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover">
                            <tr>
                              <td>
                                <small><strong># <?= $mensagem["idmensagem"] ?></strong></small>
                                <br />
                                <small>
                                    <strong><?= $idioma["data"]; ?> </strong><?= formataData($mensagem["data_cad"],"br",1); ?> 
                                    <strong><?= $idioma["por"]; ?></strong> 
                                    <?php 
                                        if ($mensagem["usuario"]) {
                                            echo $mensagem["usuario"]; 
                                        } else {
                                            echo $mensagem["vendedor"].' (Vendedor)';
                                        }
                                    ?>
                                </small>
                                <?php if ($mensagem['vendedor']) { ?>
                                    <small>
                                        <strong style="padding-left:50px"><?= $idioma["vendedor"]; ?> </strong>
                                    </small><?= $mensagem["vendedor"] ?>
                                <?php } ?>
                                <span class="pull-right" style="color:#999;">
								  <?php if (($mensagem["idusuario"] == $usuario["idusuario"]) || (!$mensagem["idusuario"])) { ?>
                                    <a class="btn btn-mini" href="javascript:void(0);" onclick="removerMensagem(<?= $mensagem["idmensagem"]; ?>);" >
                                        <span class="icon-remove"></span> 
                                        <?php echo $idioma["mensagem_excluir"]; ?>
                                    </a>
                                  <?php } ?>
                                </span>
                                <br />
                                <br />
                                <?php echo nl2br($mensagem["mensagem"]); ?>	
                                <br />
								<?php if ($mensagem["proxima_acao"]) { ?>
                    <br/>
                    <strong><?= $idioma['mensagem_proxima_acao']?></strong>
                    <?= formataData($mensagem["proxima_acao"],"br",0)?>
                <?php } ?>
                              </td>
                            </tr>  
                          </table>  
						<?php } ?>
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
                    </div>
              </div>  
        <?php } else { ?>
        	<ul class="nav nav-tabs nav-stacked">      
              <li>
                <span> <?php echo $idioma["sem_relacionamento"]; ?> </span>        
              </li>              
            </ul>
        <?php } ?>
          </div>
        </div>
        <div class="clearfix"></div>
      </section>
        <section id="historicorelacionamento">
            <div>
                <?php echo $linhaObj->retornarHistoricoTabela($historicos, $idioma); ?>
            </div>
        </section>
    </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
  <script src="/assets/plugins/portamento/portamento-min.js"></script> 
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script> 
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script> 
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script> 
  <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script> 
  <script>
    function validaMensagem() {
        if (document.getElementById('mensagem').value == "") {
            alert('<?php echo $idioma["mensagem_vazio"]; ?>');
            return false; 
        }
		if (document.getElementById('proxima_acao').value == "") {
            alert('<?php echo $idioma["proxima_acao_vazio"]; ?>');
            return false;
        }
        return true;
    }
    var regras_dados_relacionamento = new Array();
    regras_dados_relacionamento.push("required,form_ativo_painel,<?=$idioma["ativo_painel_vazio"];?>");
    regras_dados_relacionamento.push("required,form_nome_pessoa,<?=$idioma["nome_pessoa_vazio"];?>");
    regras_dados_relacionamento.push("required,form_email_pessoa,<?=$idioma["email_pessoa_vazio"];?>");
    regras_dados_relacionamento.push("valid_email,form_email_pessoa,<?=$idioma["email_pessoa_invalido"];?>");
    jQuery(document).ready(function($) {
        $("#proxima_acao").datepicker({          
        currentText: 'Now',     
        minDate:'Now'
        })
    });
</script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
</div>
</body>
</html>