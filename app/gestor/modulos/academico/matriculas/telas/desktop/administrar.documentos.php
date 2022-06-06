<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/assets/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />-->
<style type="text/css">

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


legend span {
  font-size: 9px;
  float: right;
  margin-right: 15px;
  color: #999;
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
      <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/matriculas"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
      <li><?php echo $idioma["nav_matricula"]; ?> #<?php echo $matricula["idmatricula"]; ?> <span class="divider">/</span></li>
      <li class="active"><?php echo $idioma["nav_administrar"]; ?></a></li>
      <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
    </ul>
  </section>
  <div class="row-fluid">
    <div class="span12">
      <div class="box-conteudo" style="padding:20px">

        <div class=" pull-right">
          <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"> <i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
        </div>



<table border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td style="padding:0px;" valign="top"><img src="/api/get/imagens/pessoas_avatar/60/60/<?php echo $matricula["pessoa"]["avatar_servidor"]; ?>" class="img-circle"></td>
    <td style="padding: 0px 0px 0px 8px;" valign="top">        <h2 class="tituloEdicao"><?= $idioma["matricula"]; ?> #<?= $matricula["idmatricula"]; ?>
          <br />
          <small style="text-transform:uppercase;">Aluno: <?= $matricula["pessoa"]["nome"]; ?></small>
        </h2></td>
  </tr>
</table>


<? incluirTela("administrar.menu",$config,$matricula); ?>


        <div class="row-fluid">


          <div class="span12">

            <?php if($mensagem["erro"]) { ?>
              <div class="alert alert-error">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?= $idioma[$mensagem["erro"]]; ?>
              </div>
        <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
            <? } ?>
            <? if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
              </div>
        <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <? } ?>


            <section id="documentosmatricula">
              <legend><?=$idioma["label_documentos_matricula"];?></legend>
              <? if($matricula["situacao"]["visualizacoes"][7] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", false)) { ?>
                <form method="post" action="" style="padding-top:15px;" enctype="multipart/form-data">
                  <input name="acao" type="hidden" value="adicionar_documento" />
                    <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                      <tr>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_tipo"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_pessoa"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_protocolo"];?></strong></td>
                        <td bgcolor="#F4F4F4" width="200"><strong><?=$idioma["documentos_matricula_arquivo"];?></strong></td>
                        <td bgcolor="#F4F4F4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>
                          <select name="idtipo" id="idtipo" style="width:300px;">
                            <? foreach($tiposDocumentos as $tipo) {
                              $documentoObrigatorio = false;
                              if(($tipo["todas_sindicatos_obrigatorio"] == "S" || $tipo["sindicato_obrigatorio"] == "S" ) && ($tipo["todos_cursos_obrigatorio"] == "S" || $tipo["curso_obrigatorio"] == "S")) {
                                $documentoObrigatorio = true;
                              }
                              ?>
                              <option value="<?= $tipo["idtipo"]; ?>"><?= $tipo["nome"]; ?><? if($documentoObrigatorio) { echo "    *".$idioma["obrigatorio"]."*"; } ?></option>
                            <? } ?>
                          </select>
                        </td>
                        <?php
                        /*
                            ?>
                            <td>
                                <select name="idtipo_associacao" id="idtipo_associacao" style="width:auto">
                                    <option value=""><?= $idioma["aluno"]; ?></option>
                                    <?php foreach($tiposAssociacoes as $tipo) { ?>
                                        <option value="<?= $tipo["idtipo"]; ?>"><?= $tipo["nome"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php
                        */
                        ?>
                        <td><input name="protocolo" type="text" id="protocolo" class="span2"/></td>
                        <td><input name="documento" type="file" id="documento"/></td>
                        <td><input class="btn btn-mini" type="submit" value="<?php echo $idioma["btn_adicionar"]; ?>" /></td>
                      </tr>
                    </table>
                  </form>
                <? } ?>
                <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_tipo"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_pessoa"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_protocolo"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_arquivo"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_opcoes"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">
                            <strong><?= $idioma["documentos_matricula_situacao"]; ?></strong>
                        </td>
                        <td bgcolor="#F4F4F4">&nbsp;</td>
                        <?php /*?><td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_informacoes"];?></strong></td><?php */?>
                        <?php
                        if ($matricula["situacao"]["visualizacoes"][9] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12", false)) {
                            ?>
                            <td width="15" bgcolor="#F4F4F4">&nbsp;</td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    if (count($matricula["documentos"]) > 0) {
                        foreach ($matricula["documentos"] as $documento) {
                            ?>
                            <tr>
                                <td>
                                    <?= $documento["tipo"]; ?>
                                </td>
                                <td>
                                    <?= ($documento["associacao"]) ? $documento["associacao"] : $idioma["aluno"]; ?>
                                </td>
                                <td>
                                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/editarprotocolo/<?= $documento["iddocumento"]; ?>" rel="facebox tooltip" data-original-title="<?= $idioma["documentos_matricula_protocolo_editar"]; ?>" data-placement="left">
                                      <?php if($documento["protocolo"]) {
                                        echo $documento["protocolo"];
                                      } else {
                                        echo '--';
                                      } ?>
                                    </a>
                                </td>
                                <td>
                                    <span id="mensagem_retorno">
                                        <?= $documento["arquivo_nome"]; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if ($documento["arquivo_nome"]) {
                                        ?>
                                        <?php
                                        if (strpos($documento['arquivo_tipo'],'image') !== false) {
                                            ?>
                                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/visualizardocumento/<?= $documento["iddocumento"]; ?>" class="fancybox btn btn-mini" rel="gallery" title="<?= $documento["tipo"].' ('.$documento["arquivo_nome"].')'; ?>">
                                                <i class="icon-picture"></i><?=$idioma["documentos_matricula_visualizar"];?>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/downloaddocumento/<?= $documento["iddocumento"]; ?>" class="btn btn-mini" rel="tooltip" data-original-title="<?= $idioma["documentos_matricula_download"]; ?>" data-placement="left">
                                            <i class="icon-download-alt"></i>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="javascript:enviarArquivo(<?= $documento["iddocumento"]; ?>);" class="btn btn-mini" rel="tooltip" data-original-title="<?= $idioma["documentos_matricula_enviar"]; ?>" data-placement="left">
                                            <i class="icon-upload"></i><?= $idioma["documentos_matricula_enviar"]; ?>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($matricula["situacao"]["visualizacoes"][8] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11",false)) {
                                        ?>
                                        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/validardocumento/<?= $documento["iddocumento"]; ?>" rel="facebox" >
                                            <?php
                                        }
                                        ?>
                                        <span class="label" style="background-color:<?= $situacao_documento_cores[$documento["situacao"]]; ?>" <? /*if($matricula["situacao"]["visualizacoes"][5]) { if($matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) {*/ echo 'title="'.$idioma["clique_alterar"].'" rel="tooltip"'; /*} else { ?>title="<?= $idioma["sem_permissao"]; ?>" rel="tooltip"<? } }*/ ?>>
                                            <?= $situacao_documento[$config["idioma_padrao"]][$documento["situacao"]]; ?>
                                        </span>
                                        <?php
                                        if ($matricula["situacao"]["visualizacoes"][8] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11",false)) {
                                            ?>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($documento["situacao"] == 'reprovado') {
                                        ?>
                                        &nbsp;
                                        <a href="#reprovacao_motivo_<?= $documento["iddocumento"]; ?>" rel="facebox" class="btn btn-mini" >
                                            <?= $idioma["documentos_matricula_motivo_reprovacao"]; ?>
                                        </a>
                                        <div id="reprovacao_motivo_<?= $documento["iddocumento"]; ?>" style="display:none;">
                                            <div class="page-header">
                                                <h1><?= $idioma["documentos_matricula_motivo_reprovacao"]; ?></h1>
                                                <br />
                                                <br />
                                                <strong><?= $idioma["documentos_matricula_motivo_descricao"]; ?></strong>
                                            </div>
                                            <textarea disabled="disabled" style="width:340px; min-height:150px;"><?= $documento["descricao_motivo_reprovacao"]; ?></textarea>
                                        </div>
                                        &nbsp;
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                                if ($matricula["situacao"]["visualizacoes"][9] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12", false)) {
                                    ?>
                                    <td>
                                        <a href="javascript:void(0);" onclick="removerDocumento(<?= $documento["iddocumento"]; ?>,'<?= $documento["tipo"]; ?> (<?= $documento["arquivo_nome"]; ?>)');">
                                            <img src="/assets/img/remover_16x16.gif" width="16" height="16" border="0" />
                                        </a>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                          <td colspan="7"><?=$idioma["nenhum_documento"];?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </table>
                <?php if($matricula["situacao"]["visualizacoes"][7] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", false)) { ?>
                    <div id="enviarDocumentosLote" style="display:none;">
                        <form class="form-inline" method="post" onsubmit="return confirmaEnvioArquivosLote();">
                            <input name="acao" type="hidden" value="adicionar_documentos_lote" />
                            <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                                <tr>
                                    <td bgcolor="#F4F4F4"><strong><?= $idioma["label_documentos_matricula"]; ?></strong></td>
                                </tr>
                               <? foreach($tiposDocumentos as $tipo) {
                                    $documentoObrigatorio = false;
                                    if($tipo["todos_cursos_obrigatorio"] == "S" || $tipo["todas_sindicatos_obrigatorio"] == "S" || $tipo["sindicato_obrigatorio"] == "S" || $tipo["curso_obrigatorio"] == "S") {
                                        $documentoObrigatorio = true;
                                    } ?>
                                    <tr>
                                        <td><input name="documentos[<?= $tipo["idtipo"]; ?>]" type="checkbox" value="<?= $tipo["idtipo"]; ?>" />&nbsp;&nbsp;<?= $tipo["nome"]; ?><? if($documentoObrigatorio) { echo " (".$idioma["obrigatorio"].") "; } ?></td>
                                    </tr>
                                <? } ?>
                                <tr>
                                    <td>Protocolo: <input type="text" name="protocolo" id="protocolo" value="" /></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center"><input type="submit" name="button" id="button" value="<?= $idioma["documentos_matricula_enviar"]; ?>" class="btn btn-mini " /></td>
                                </tr>
                            </table>
                        </form>
                        <script>
                            function confirmaEnvioArquivosLote(){
                                var confirma = confirm('Deseja realmente enviar os arquivos selecionados?');
                                if(confirma) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        </script>
                    </div>
                    <a class="btn btn-mini" href="#enviarDocumentosLote" rel="facebox"><?=$idioma["enviar_documentos_lote"];?></a>
                <?php } ?>
                <script type="text/javascript">
                    function enviarArquivo(id) {
                      document.getElementById('iddocumento_enviar').value = id;
                      document.getElementById('documento_enviar').click();
                    }
                </script>
                <form action="" method="post" id="formEnviarArquivo" name="formEnviarArquivo" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="enviar_documento" />
                    <input type="hidden" name="iddocumento" id="iddocumento_enviar" value="" />
                    <input type="file" id="documento_enviar" name="documento" style="display:none;" />
                </form>
                <? if($matricula["situacao"]["visualizacoes"][9] && $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12", false)) { ?>
                  <script type="text/javascript">
                    function removerDocumento(id,nome) {
                      var msg = "<?=$idioma["confirm_remover_documento"];?>";
                      msg = msg.replace("[[nome]]", nome);
                      var confirma = confirm(msg);
                      if(confirma){
                        document.getElementById('iddocumento').value = id;
                        document.getElementById('form_remover_documento').submit();
                        return true;
                      } else {
                        return false;
                      }
                    }
                  </script>
                  <form method="post" id="form_remover_documento" action="" style="padding-top:15px;">
                    <input name="acao" type="hidden" value="remover_documento" />
                    <input name="iddocumento" id="iddocumento" type="hidden" value="" />
                  </form>
                <? } ?>
              </section>




          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
  <script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
  <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
</div>

<script>
$(document).ready(function(){
    $('#documento_enviar').change(function(){
        $('#mensagem_retorno').html('Enviando...');
        $('#formEnviarArquivo').submit();
    });
});

jQuery(document).ready(function($) {
    $('.fancybox').fancybox({
        type       : 'image',
        //prevEffect : 'none',
        //nextEffect : 'none',
        //closeBtn   : false,
        //helpers : {
        //  title : { type : 'inside' },
        //  buttons : {}
        //}
    });
});
</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>