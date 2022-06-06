<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
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
<?php incluirLib("topo",$config,$usu_vendedor); ?>


<?php if($matricula["situacao"]["visualizacoes"][14]) { ?>
<div id="cancelarcontrato" style="display:none;">
  <iframe id="iframe_cancelarcontrato" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/cancelarcontrato" width="400" height="290" frameborder="0"></iframe>
</div>
<?php } ?>

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


            <section id="contratosmatricula">

              <legend data-abrefecha="contratos_matricula_ancora_div">
                    <?=$idioma["label_contratos_matricula"];?>
              </legend>
              <div id="contratos_matricula_ancora_div">


        <? if($matricula["situacao"]["visualizacoes"][11]) { ?>
        <? //if($matricula["situacao"]["visualizacoes"][11]) { ?>
                  <script type="text/javascript">
                    function atualizaContratoGet() {
                      var contrato = document.getElementById("idcontrato").options[document.getElementById("idcontrato").selectedIndex].value;
                      array_get = contrato.split("|");
                      link_var = "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/gerarcontrato/"+array_get[0]+"/"+array_get[1];
                      document.getElementById('iframe_contrato_pre').src = link_var;
                    }
                  </script>
                  <div id="gerarcontrato" style="display:none">
                    <iframe id="iframe_contrato_pre" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/gerarcontrato" width="700" height="500" frameborder="0"></iframe>
                  </div>
                <? //} ?>
                <div style="float:left">
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_contrato"];?></strong></td>
                      <td bgcolor="#F4F4F4">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idcontrato" id="idcontrato" class="span3" <? if($matricula["situacao"]["visualizacoes"][11]) { ?>onchange="atualizaContratoGet()"<? } else { ?>disabled="disabled"<? } ?>>
                          <option value=""></option>
                          <? foreach($contratos as $contrato) { ?>
                            <option value="<?= $contrato["tipo"]; ?>|<?= $contrato["idcontrato"]; ?>"><?= $contrato["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td>
                      <? if($matricula["situacao"]["visualizacoes"][11]) { ?>
                        <a class="btn btn-mini" id="btn_contrato_pre" href="#gerarcontrato" rel="facebox" ><?php echo $idioma["btn_adicionar"]; ?></a>
                      <? } else { ?>
                        <span class="btn btn-mini" disabled="disabled" data-placement="right" data-original-title="<?= $idioma['sem_permissao']; ?>" rel="tooltip" /><?= $idioma["btn_adicionar"]; ?></span>
                      <? } ?>
                      </td>
                    </tr>
                  </table>
                </div>
                <form method="post" action="" style="float:left; margin-left:10px;" enctype="multipart/form-data">
                  <? //if($matricula["situacao"]["visualizacoes"][11]) { ?>
                    <input name="acao" type="hidden" value="enviar_contrato" />
                  <? //} ?>
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_eviar"];?></strong></td>
                      <td bgcolor="#F4F4F4"><?=$idioma["contratos_matricula_tipo"];?></td>
                      <td bgcolor="#F4F4F4">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input name="contrato" type="file" id="contrato" <? /*if(!$matricula["situacao"]["visualizacoes"][11] ) { ?>disabled="disabled"<? }*/ ?>/></td>
                      <td>
                        <select name="idtipo" id="idtipo" style="width:auto" <? /*if(!$matricula["situacao"]["visualizacoes"][11]) { ?>disabled="disabled"<? }*/ ?>>
                          <? foreach($tiposContratos as $tipo) { ?>
                            <option value="<?= $tipo["idtipo"]; ?>"><?= $tipo["nome"]; ?></option>
                          <? } ?>
                        </select>
                      </td>
                      <td><input class="btn btn-mini" type="submit" value="<?php echo $idioma["btn_enviar"]; ?>" <? /*if(!$matricula["situacao"]["visualizacoes"][11]) { ?>disabled="disabled"<? }*/ ?> /></td>
                    </tr>
                    <tr>
                      <td colspan="3" style="color:#999999"><?=$idioma["contratos_matricula_extensao_arquivo"];?></td>
                    </tr>
                  </table>
                </form>
        <? } ?>
        <div style="clear: both;">
              <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                <tr>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_numero"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_tipo"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_nome"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_assinado"];?></strong></td>
                  <?php /*if($existeDevedorSolidario) { ?><td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_assinado_devedor"];?></strong></td><?php }*/ ?>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_validado"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_cancelado"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_matricula_aceito"];?></strong></td>
                  <td bgcolor="#F4F4F4">&nbsp;</td>
                </tr>
                <?
                if(count($matricula["contratos"]) > 0) {
                  foreach($matricula["contratos"] as $contrato){
                  ?>
                    <tr>
                      <td><?= $contrato["idmatricula_contrato"]; ?></td>
                      <td><?= $contrato["tipo"]; ?></td>
                      <td>
                        <?
            if($contrato["contrato"]) {
              $nomeContrato = $contrato["contrato"];
            } else {
              $nomeContrato = $contrato["arquivo"];
            }
              echo $nomeContrato;
                        ?>
            <br />
                        <span style="color:#999999">
                          <? if($contrato["contrato"]) { echo $idioma["contratos_matricula_gerado_dia"]; } else { echo $idioma["contratos_matricula_enviado_dia"]; } ?>
                          <?= formataData($contrato["data_cad"],'br',1); ?>
                        </span>
                      </td>
                      <td>
                        <?php
                        $nomeContrato = addcslashes($nomeContrato,"'");
                        if($matricula["situacao"]["visualizacoes"][12]) {
                          if($contrato["cancelado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_cancelado_assinar"].'\')"';
                          } elseif($contrato["validado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_validado"].'\')"';
                          } else {
                            if($contrato["assinado"]) {
                              $situacao = 2;
                            } else if($contrato["nao_assinado"]) {
                              $situacao = 1;
                            } else {
                              $situacao = 0;
                            }
                            $href = 'href="javascript:alert(\''.$idioma["sem_permissao"].'\')" rel="tooltip"';
                          }
                          ?>
                          <a <?= $href; ?>>
                        <? } ?>
                        <? if($contrato["assinado"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#060"<? } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["assinado"],'br',1); ?></span>
                        <? } elseif($contrato["nao_assinado"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#FF0000"<? } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["nao_assinado"],'br',1); ?></span>
                        <? }else { ?>
                          <span class="label"><?= $idioma["contratos_matricula_nao_assinado"]; ?></span>
                        <? } ?>
                        <? if($matricula["situacao"]["visualizacoes"][12]) { ?>
                          </a>
                        <? } ?>
                      </td>
                      <?php if($existeDevedorSolidario) { ?>
                      <td>
                        <?php
                        /*if($matricula["situacao"]["visualizacoes"][12]) {
                          if($contrato["cancelado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_cancelado_assinar"].'\')"';
                          } elseif($contrato["validado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_validado"].'\')"';
                          } else {
                            if($contrato["assinado_devedor"]) {
                              $situacao = 2;
                            } else if($contrato["nao_assinado_devedor"]) {
                              $situacao = 1;
                            } else {
                              $situacao = 0;
                            }
                            $href = 'href="#assinarcontrato" rel="facebox" onclick="assinarContrato('.$contrato["idmatricula_contrato"].',\''.$nomeContrato.'\','.$situacao.')"';
                          }
                          ?>
                          <a <?= $href; ?>>
                        <? }*/ ?>
                        <? if($contrato["assinado_devedor"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#060"<? } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["assinado_devedor"],'br',1); ?></span>
                        <? } elseif($contrato["nao_assinado_devedor"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#FF0000"<? } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["nao_assinado_devedor"],'br',1); ?></span>
                        <? }else { ?>
                          <span class="label"><?= $idioma["contratos_matricula_nao_assinado"]; ?></span>
                        <? } ?>
                        <? /*if($matricula["situacao"]["visualizacoes"][12]) { ?>
                          </a>
                        <? }*/ ?>
                      </td>
                      <?php } ?>
                      <td>
                        <?php
                        if($matricula["situacao"]["visualizacoes"][13]) {
                          if($contrato["cancelado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_cancelado_validadar"].'\')"';
                          } elseif(!$contrato["assinado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_matricula_alerta_assinado"].'\')"';
                          } else {
                            if($contrato["validado"]) {
                              $situacaoAtual = 2;
                            } else if($contrato["nao_validado"]){
                              $situacaoAtual = 1;
                            } else {
                              $situacaoAtual = 0;
                            }
                            $href = 'href="javascript:alert(\''.$idioma["sem_permissao"].'\')" rel="tooltip"';
                          }
                          ?>
                          <a <?= $href; ?>>
                        <? } ?>
                        <? if($contrato["validado"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#060"<? } ?> title="<?= $contrato["usuario_valida"]; ?>" rel="tooltip"><?= formataData($contrato["validado"],'br',1); ?></span>
                        <? } else if($contrato["nao_validado"]) { ?>
                          <span class="label" <? if(!$contrato["cancelado"]) { ?>style="background-color:#FF0000"<? } ?> title="<?= $contrato["usuario_valida"]; ?>" rel="tooltip"><?= formataData($contrato["nao_validado"],'br',1); ?></span>
                        <? } else { ?>
                          <span class="label"><?= $idioma["contratos_matricula_nao_validado"]; ?></span>
                        <? } ?>
                        <? if($matricula["situacao"]["visualizacoes"][13]) { ?>
                          </a>
                        <? } ?>
                      </td>
                      <td>
                        <? /*echo $contrato["cancelado"];*/ if($contrato["cancelado"]) { ?>
                          <a href="javascript:alert('<?=$idioma["contratos_matricula_cancelado_definitivo"];?><?php if ($contrato["justificativa"]) {?>\n\nJustificativa:\n<?= $contrato["justificativa"]; ?><? } ?>')"> <span class="label" style="background-color:#C00" title="<?= $contrato["usuario_cancela"]; ?>" rel="tooltip"><?= formataData($contrato["cancelado"],'br',1); ?></span> </a>
                        <? } else {?>
                            <span class="label" title="<?= $idioma["sem_permissao"]; ?>" rel="tooltip"><?=$idioma["contratos_matricula_cancelar"];?></span>
                        <? } ?>
                        &nbsp;

                      </td>
                      <td>
                        <?php if($contrato["aceito_aluno"] == 'S') {
                          echo "Aceito";
                          ?>
                          <br />
                          <span style="color:#999999">
                            <?= 'Data: '.formataData($contrato["aceito_aluno_data"],'br',1); ?>
                          </span>
                        <?php } else {
                          echo "Aguardando"; ?>
                        <?php } ?>
                      <td>
                        <? if($contrato["contrato"]) { ?>
                          <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/contratopdf/<?= $contrato["idmatricula_contrato"]; ?>" target="_blanck"><?=$idioma["contratos_matricula_abrir_pdf"];?></a>
                          &nbsp;
                          <a class="btn btn-mini" href="javascript:abrePopup('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/contrato/<?= $contrato["idmatricula_contrato"]; ?>','contrato<?= $contrato["idmatriculacontrato"]; ?>','scrollbars=yes,resizable=yes,width=800,height=600')" ><?=$idioma["contratos_matricula_abrir"];?></a>
                        <? } else { ?>
                          <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/contratodownload/<?= $contrato["idmatricula_contrato"]; ?>" ><?=$idioma["contratos_matricula_download"];?></a>
                        <? } ?>
                        <div class="btn btn-mini" onclick="reenviar_email();">REENVIAR E-MAIL</div>
                        <form action="" method="post" id="form_reenviar_email">
                          <input name="acao" type="hidden" value="reenviar_email" />
                        </form>
                      </td>
                    </tr>
          <? } ?>
                  <?php/*<tr>
                    <td colspan="7" style="color:#999999"><?=$idioma["contratos_matricula_contrato_cancelado"];?></td>
                  </tr>*/?>
                <?
                } else { ?>
                  <tr>
                    <td colspan="7"><?=$idioma["nenhum_contrato"];?></td>
                  </tr>
                <? } ?>
              </table>
              </div>
            </section>


          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usu_vendedor); ?>
</div>

<script>
function attContrato(id) {
  document.getElementById('iframe_cancelarcontrato').src = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/cancelarcontrato?r=' + id;
}

function reenviar_email() {
    var confirma = confirm('Deseja realmente reenviar o e-mail do contrato?');

    if(confirma) {
        document.getElementById('form_reenviar_email').submit();
    } else {
        return false;
    }
}

</script>

<?php incluirTela("cabecalho_info", $config, $matricula); ?>

</body>
</html>