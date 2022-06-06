<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["pagina_titulo"]; ?>&nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_ficha"]; ?> #<?php echo $url[4]; ?> <span class="divider">/</span></li>
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
        <h2 class="tituloEdicao"><?= $idioma["ficha"]; ?> #<?= $url[4]; ?>
          <br />
          <small style="text-transform:uppercase;"><?= $idioma["data_abertura"]; ?> <?=formataData($dadospessoais["data_cad"], "br", 1);?></small> 
        </h2>
        <div class="row-fluid">
          <div class="span2">
            <div class="well" style="padding: 8px 0pt; width:180px;" id="menuEsquerda">
              <ul class="nav nav-list">
                <li class="nav-header active"><a><?=$idioma["menu_navegacao"];?></a></li>
                <li><a href="#fichapessoal"><?=$idioma["menu_ficha_pessoa"];?></a></li>
                <li><a href="#relacionamentocoomercial"><?=$idioma["menu_mensagens"];?></a></li>
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
          <section id="fichapessoal" style="#000">
              <legend><?= $idioma["label_fichapessoal"]; ?></legend>
              <table border="0" cellspacing="0" cellpadding="5" class="table table-bordered table-condensed">
                <tr>
                  <td style="border-top:0px" width="150"><?=$idioma["dados_aluno_nome"];?></td>
                  <td style="border-top:0px"><strong><?= $dadospessoais["nome"]; ?></strong></td>
                  <td style="border-top:0px" width="150"><?=$idioma["dados_aluno_documento"];?></td>
                  <td style="border-top:0px">
                    <strong>
            <?php 
            if ($dadospessoais["documento_tipo"] == 'cpf') {
            echo str_pad($dadospessoais["documento"], 11, "0", STR_PAD_LEFT);
            } else {
            echo str_pad($dadospessoais["documento"], 14, "0", STR_PAD_LEFT);
            } 
            ?>
                    </strong>
                  </td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_data_nascimento"];?></td>
                  <td><strong><?= formataData($dadospessoais["data_nasc"],'br',0); ?></strong></td>
                  <td><?=$idioma["dados_aluno_rg"];?></td>
                  <td><strong><?= $dadospessoais["rg"]; ?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_estado_civil"];?></td>
                  <td><strong><?= $estadocivil[$config["idioma_padrao"]][$dadospessoais["estado_civil"]]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_profissao"];?></td>
                  <td><strong><?= $dadospessoais["profissao"]; ?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_endereco"];?></td>
                  <td><strong><?= $dadospessoais["endereco"]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_telefone"];?></td>
                  <td ><strong><?= $dadospessoais["telefone"]; ?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_email"];?></td>
                  <td><strong><?= $dadospessoais["email"]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_celular"];?></td>
                  <td ><strong><?= $dadospessoais["celular"]; ?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_numero"];?></td>
                  <td><strong><?= $dadospessoais["numero"]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_complemento"];?></td>
                  <td><strong><?= $dadospessoais["complemento"]; ?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_bairro"];?></td>
                  <td><strong><?= $dadospessoais["bairro"]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_cidade"];?></td>
                  <td><strong><?= $dadospessoais["cidade"];?></strong></td>
                </tr>
                <tr>
                  <td><?=$idioma["dados_aluno_cep"];?></td>
                  <td><strong><?= $dadospessoais["cep"]; ?></strong></td>
                  <td><?=$idioma["dados_aluno_estado"];?></td>
                  <td><strong><?= $dadospessoais["estado"];?></strong></td>
                </tr>
              </table>
          </section>
          <section id="relacionamentocoomercial" style="#000">
            <legend><?= $idioma["label_relacionamento"]; ?></legend>
              <div class="accordion" id="accordion_mensagens"> 
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_mensagens" href="#cadastrar_mensagem"><?= $idioma["mensagem_cadastrar"]; ?></a>
                  </div>
                  <div id="cadastrar_mensagem" class="accordion-body collapse">
                    <div class="accordion-inner">
                      <form name="form_mensagens" method="post" onsubmit="return validaMensagem();" id="form_mensagens" >
                      		<input type="hidden" name="idpessoa" id="idpessoa" value="<?php echo $url[4];?>" >
                        <div style="border:#CCC solid 1px; padding-bottom:10px; width:99%" class="row-fluid"> 
                          <div style="width:90%; padding-left:15px;">
                            <br />
                            <small><strong><?php echo $idioma["mensagem_texto"]; ?></strong></small>
                            <br />
                            <textarea name="mensagem" id="mensagem" rows="5" style="width:65%;"></textarea>
                            <small><strong><?php echo $idioma["relacionamento_proxima_acao"]; ?></strong></small>
                            <input name="proxima_acao"  id="proxima_acao" class="span2" type="text" />                           
                            <br /> 
                            <br /> 
                            <?php /*?><small><strong><?= $idioma["vendedor"]; ?></strong></small>
                            <select name="idvendedor" id="idvendedor" class="span2" >
                                <option value=""></option>
                                <? foreach($vendedores as $vendedor) { ?>
                                    <option value="<?= $vendedor['idvendedor']; ?>"><?= $vendedor['nome'] ?></option>
                                <? } ?>
                            </select><?php */?>
                            <input type="hidden" name="acao" value="salvar_mensagem"> 
                            <br />
                            <div style="float:right;"><input type="submit" class="btn btn-primary" name="enviar" value="<?php echo $idioma["btn_cadastrar"]; ?> " /></div>                
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
                                        //if ($mensagem["usuario"]) {
                                            echo $mensagem["usuario"]; 
                                        //} else {
                                            //echo $mensagem["vendedor"].' (Vendedor)';
                                        //}
                                    ?>
                                </small>
                                <small>
                                    <strong style="padding-left:50px"><?= $idioma["mensagem_proxima_acao"]; ?> </strong></small>
                                    <?= formataData($mensagem["proxima_acao"],"br",0); ?>
                                <?php /*if ($mensagem['vendedor']) { ?>
                                    <small>
                                        <strong style="padding-left:50px"><?= $idioma["vendedor"]; ?> </strong>
                                    </small><?= $mensagem["vendedor"] ?>
                                <?php }*/ ?>
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
      </div>
      </section>
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
  }else if (document.getElementById('proxima_acao').value == "") {
    alert('<?php echo $idioma["proxima_acao_vazio"]; ?>');
    return false; 
  }
  }
  var regras = new Array();
  regras.push("required,proxima_acao,<?=$idioma["proxima_acao_vazia"];?>");
  $( "#proxima_acao" ).datepicker($.datepicker.regional["pt-BR"]);
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