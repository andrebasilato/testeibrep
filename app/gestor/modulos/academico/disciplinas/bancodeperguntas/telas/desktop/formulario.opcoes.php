<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <?php //print_r2($linha,true); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
      <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
      <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?php echo tamanhoTexto(100,$linha["nome"]); ?></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo">
        <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
          <h2 class="tituloEdicao"><?php echo tamanhoTexto(100,$linha["nome"]); ?></h2>
          <div class="tabbable tabs-left">
      <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
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
                <?php if (!$possueProva) {?>
                <form class="well wellDestaque form-inline" method="post">                            
                  <table>
                    <tr>
                      <td><?php echo $idioma["form_ordem"]; ?></td>
                      <td><?php echo $idioma["form_nome"]; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><input type="text" class="span1" name="ordem" id="ordem" /></td>
                      <td><input type="text" class="span3" name="nome" id="nome" /></td>
                      <td>
                        <input type="hidden" id="acao" name="acao" value="cadastrar_opcao">
                        <input type="submit" class="btn" value="<?= $idioma["btn_adicionar"]; ?>" />
                      </td>
                    </tr>
                  </table>
                </form>
                <?php }else { ?>
                    <div class="alert alert-error fade in">
                        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                          <?php echo $idioma['impossivel_editar_opcoes']; ?>
                    </div>
                <?php } ?>                            
                <form method="post" id="remover_opcao" name="remover_opcao">
                  <input type="hidden" id="acao" name="acao" value="remover_opcao">
                  <input type="hidden" id="remover" name="remover" value="">
                </form>                            
                <form method="post" id="editar_opcao" name="editar_opcao">
                  <input type="hidden" id="acao" name="acao" value="editar_opcoes">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th width="80"><?= $idioma["tabela_ordem"]; ?></th>
                        <th><?= $idioma["tabela_nome"]; ?></th>
                        <th width="80"><?= $idioma["tabela_ativo_painel"]; ?></th>
                        <th width="80"><?= $idioma["tabela_resposta"]; ?></th>
                        <th width="60"><?= $idioma["tabela_opcoes"]; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($opcoes) > 0) {
                        $validacao = "";
                        foreach($opcoes as $opcao) {
              $tipo = $linha['multipla_escolha']; 
                          $validacao .= '$("#numero'.$opcao["idopcao"].'").keypress(isNumber); $("#numero'.$opcao["idopcao"].'").blur(isNumberCopy); ';
                          ?>
                          <tr>
                            <td><input type="text" class="span1" name="ordens[<?php echo $opcao["idopcao"]; ?>]" id="ordens<?php echo $opcao["idopcao"]; ?>" value="<?php echo $opcao["ordem"]; ?>" maxlength="10" /></td>
                            <td><?php echo $opcao["nome"]; ?></td>
                            <?php if (!$possueProva) {?>
                                <td>
                                  <?php if($opcao["ativo_painel"] == "S") { ?>
                                    <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-success" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $opcao["idpergunta"]; ?>,<?php echo $opcao["idopcao"]; ?>);" id="ativo_painel<?php echo $opcao["idopcao"]; ?>" style="cursor:pointer;">Sim</span>
                                  <?php } else { ?>
                                    <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-important" data-placement="left" rel="tooltip" onclick="ativarDesativar(<?php echo $opcao["idpergunta"]; ?>,<?php echo $opcao["idopcao"]; ?>);" id="ativo_painel<?php echo $opcao["idopcao"]; ?>" style="cursor:pointer;">Não</span>
                                  <?php } ?>
                                </td>
                                 <td>
                                  <?php if($opcao["correta"] == "S") { ?>
                                    <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-success" data-placement="left" rel="tooltip" onclick="corretaIncorreta(<?php echo $opcao["idpergunta"]; ?>,<?php echo $opcao["idopcao"]; ?>);" id="correta<?php echo $opcao["idopcao"]; ?>" style="cursor:pointer;">Sim</span>
                                  <?php } else { ?>
                                    <span data-original-title="<?php echo $idioma["clique_ativar_inativar"]; ?>" class="label label-important" data-placement="left" rel="tooltip" onclick="corretaIncorreta(<?php echo $opcao["idpergunta"]; ?>,<?php echo $opcao["idopcao"]; ?>);" id="correta<?php echo $opcao["idopcao"]; ?>" style="cursor:pointer;">Não</span>
                                  <?php } ?>
                                </td>
                                <td><a href="javascript:void(0);" class="btn btn-mini" data-original-title="<?= $idioma["btn_remover"]; ?>" data-placement="left" rel="tooltip" onclick="remover(<?php echo $opcao["idopcao"]; ?>)"><i class="icon-remove"></i></a></td>
                            <?php } else { ?>
                                <td>
                                <?php if($opcao["ativo_painel"] == "S") { ?>
                                  <span data-original-title="<?php echo $idioma["impossivel_ativar"]; ?>" class="label label-success" data-placement="left" rel="tooltip">Sim</span>
                                <?php } else { ?>
                                  <span data-original-title="<?php echo $idioma["impossivel_ativar"]; ?>" class="label label-important" data-placement="left" rel="tooltip">Não</span>
                                <?php } ?>
                              </td>
                               <td>
                                <?php if($opcao["correta"] == "S") { ?>
                                  <span data-original-title="<?php echo $idioma["impossivel_mudar_certa"]; ?>" class="label label-success" data-placement="left" rel="tooltip">Sim</span>
                                <?php } else { ?>
                                  <span data-original-title="<?php echo $idioma["impossivel_mudar_certa"]; ?>" class="label label-important" data-placement="left" rel="tooltip">Não</span>
                                <?php } ?>
                              </td>
                              <td title="<?= $idioma["impossivel_excluir"] ?>">---</td>
                            <?php } ?>
                          </tr>
                        <?php } ?>
                      <?php } else { ?>
                        <tr>
                          <td colspan="4"><?= $idioma["sem_informacao"]; ?></td>
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
  <? incluirLib("rodape",$config,$usuario); ?>
  <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
  <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
  <script type="text/javascript">
    function ativarDesativar(pergunta, opcao){
    $.msg({ 
      autoUnblock : false,
      clickUnblock : false,
      klass : 'white-on-black',
      content: 'Processando solicitação.',
      afterBlock : function(){
      var self = this;
      jQuery.ajax({
        url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/ativar_desativar",
        dataType: "json", //Tipo de Retorno
        type: "POST",
        data: {idpergunta: pergunta, idopcao: opcao, acao: "json_perguntas"},
        success: function(json){ //Se ocorrer tudo certo
        if(json.sucesso){
          altualizaBotoes(json.ativo, json.opcao);
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
    
    function altualizaBotoes(ativo, opcao) {
    if(ativo == "S"){
      $("#ativo_painel"+opcao).removeClass("label-important");
      $("#ativo_painel"+opcao).addClass("label-success");
      $("#ativo_painel"+opcao).html("Sim");
    } else if(ativo == "N") {
      $("#ativo_painel"+opcao).removeClass("label-success");
      $("#ativo_painel"+opcao).addClass("label-important");
      $("#ativo_painel"+opcao).html("Não");
    }
    }
    
    //Adicionando função marcar correta----------------
    function corretaIncorreta(pergunta, opcao){
    $.msg({
      autoUnblock : false,
      clickUnblock : false,
      klass : 'white-on-black',
      content: 'Processando solicitação.',
      afterBlock : function(){
      var self = this;
      jQuery.ajax({
        url: "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/json/correta_incorreta",
        dataType: "json", //Tipo de Retorno
        type: "POST",
        data: {idpergunta: pergunta, idopcao: opcao, acao: "json_perguntas"},
        success: function(json){ //Se ocorrer tudo certo
        if(json.sucesso){
          altualizaBotoesCorreta(json.correta, json.opcao);
          self.unblock();
          location.href = location.href;
        } else {
          alert('<?= $idioma["json_erro"]; ?>');
          self.unblock(); 
        }                      
        }
      });
      }
    });
    }// Adicionando função marcar correta(END)-----------
    
    function altualizaBotoesCorreta(correta, opcao) {
    if(correta == "S"){
      $("#correta"+opcao).removeClass("label-important");
      $("#correta"+opcao).addClass("label-success");
      $("#correta"+opcao).html("Sim");
    } else if(correta == "N") {
      $("#correta"+opcao).removeClass("label-success");
      $("#correta"+opcao).addClass("label-important");
      $("#correta"+opcao).html("Não");
    }
    }
    
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