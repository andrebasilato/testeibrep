<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
  <?php incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    
      <div id="assinarcontrato" style="display:none;">
          <section id="global">
              <div class="page-header">
                  <h1><?php echo $idioma["assina_contrato"]; ?></h1>
              </div>
              <ul class="breadcrumb">
                  <li><?php echo $idioma["label_assina_contrato"]; ?></li>
                  <li class="active"><strong id="contrato_assinar_nome"></strong></li>
              </ul>
              <form action="" method="post" id="form_contrato_assinar">
                  <input name="acao" type="hidden" value="assinar_contrato" />
                  <input name="idescola_contrato" id="idescola_contrato_assinar" type="hidden" value="" />
                  <input name="situacao" id="situacao_assinar" type="hidden" value="" />
                  <?= $idioma["assina_contrato_explicativo"]; ?>
                  <br />
                  <div class="row-fluid">
                      <div class="span5 botao btn" id="contrato_assinar_aprovar" onclick="contrato_assinar_selecionarSituacao(2);"><?= $idioma["contrato_assinar"]; ?></div>
                      <div class="span5 botao btn" id="contrato_assinar_desaprovar" onclick="contrato_assinar_selecionarSituacao(1);"><?= $idioma["contrato_nao_assinar"]; ?></div>
                  </div>
                  <br />
              </form>
          </section>
          <script type="text/javascript">
          function contrato_assinar_selecionarSituacao(situacao) {
          if(situacao == 1){
          var confirma = confirm('<?= $idioma["confirma_nao_assinar_contrato"]; ?>');
          } else if(situacao == 2) {
          var confirma = confirm('<?= $idioma["confirma_assinar_contrato"]; ?>');
          }
          if(confirma) {
            document.getElementById('situacao_assinar').value = situacao;
            document.getElementById('form_contrato_assinar').submit();
          } else {
                      return false;
                  }
              }

              function assinarContrato(id, nome, situacaoatual) {
                  document.getElementById('idescola_contrato_assinar').value = id;
                  document.getElementById('contrato_assinar_nome').innerHTML = nome;
                  if (situacaoatual == 1) {
          // Nao aprovado
                      $("#contrato_assinar_aprovar").removeClass("btn-success");
                      $("#contrato_assinar_desaprovar").addClass("btn-danger");
                  } else if (situacaoatual == 2) {
          // Aprovado
                      $("#contrato_assinar_desaprovar").removeClass("btn-danger");
                      $("#contrato_assinar_aprovar").addClass("btn-success");
                  } else {
                      $("#contrato_assinar_aprovar").removeClass("btn-success");
                      $("#contrato_assinar_desaprovar").removeClass("btn-danger");
                  }
                  return true;
              }
          </script>
      </div>
      
      <div id="validarcontrato" style="display:none;">
  <section id="global">
    <div class="page-header">
      <h1><?php echo $idioma["valida_contrato"]; ?></h1>
    </div>
    <ul class="breadcrumb">
      <li><?php echo $idioma["label_valida_contrato"];?></li>
      <li class="active"><strong id="contrato_validar_nome"></strong></li>
    </ul>
    <form action="" method="post" id="form_contrato_validar">
      <input name="acao" type="hidden" value="validar_contrato" />
      <input name="idescola_contrato" id="idescola_contrato_validar" type="hidden" value="" />
      <input name="situacao" id="situacao_validar" type="hidden" value="" />
      <? /* =$idioma['explicativo_contrato']; */?>
      <br />
      <div class="row-fluid">
        <div class="span5 botao btn" id="contrato_validar_aprovar" onclick="contrato_validar_selecionarSituacao(2);"><?=$idioma["contrato_validar"];?></div>
        <div class="span5 botao btn" id="contrato_validar_desaprovar" onclick="contrato_validar_selecionarSituacao(1);"><?=$idioma["contrato_nao_validar"];?></div>
      </div>
      <br />
    </form>
  </section>
  <script type="text/javascript">
    function contrato_validar_selecionarSituacao(situacao) {
    if(situacao == 1){
      var confirma = confirm('<?=$idioma["confirma_nao_validar_contrato"];?>');
    } else if(situacao == 2) {
      var confirma = confirm('<?=$idioma["confirma_validar_contrato"];?>');
    }
    if(confirma) {
      document.getElementById('situacao_validar').value = situacao;
      document.getElementById('form_contrato_validar').submit();
    } else {
      return false;
    }
    }

    function validarContrato(id,nome,situacaoatual) {
    document.getElementById('idescola_contrato_validar').value = id;
    document.getElementById('contrato_validar_nome').innerHTML = nome;
    if(situacaoatual == 1){
      // Nao aprovado
      $("#contrato_validar_aprovar").removeClass("btn-success");
      $("#contrato_validar_desaprovar").addClass("btn-danger");
    } else if(situacaoatual == 2) {
      // Aprovado
      $("#contrato_validar_desaprovar").removeClass("btn-danger");
      $("#contrato_validar_aprovar").addClass("btn-success");
    } else {
      $("#contrato_validar_aprovar").removeClass("btn-success");
      $("#contrato_validar_desaprovar").removeClass("btn-danger");
    }
    return true;
    }
  </script>
</div>
      
 <div id="cancelarcontrato" style="display:none;">
  <iframe id="iframe_cancelarcontrato" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?= $url[5]; ?>/cancelarcontrato" width="400" height="290" frameborder="0"></iframe>
</div>     
      
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
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
              <h2 class="tituloEdicao"><?= $linha["nome_fantasia"]; ?>  <?php if ($linha["email"]) {?><small>(<?= $linha["email"]; ?>)</small> <?php } ?></h2>
            	
			<?php incluirTela("inc_menu_edicao",$config,$usuario); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
                
                  <?php if($mensagem["erro"]) { ?>
              <div class="alert alert-error">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                <?= $idioma[$mensagem["erro"]]; ?>
              </div>
        <script>alert('<?= str_ireplace(array("<br />", "<br/>", "<br>"), "\\n", $idioma[$mensagem["erro"]]); ?>');</script>
            <?php } ?>
            <?php if($_POST["msg"]) { ?>
              <div class="alert alert-success fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$_POST["msg"]]; ?></strong>
              </div>
        <script>alert('<?= $idioma[$_POST["msg"]]; ?>');</script>
            <?php } ?>


            <section id="contratosescola">

              <legend data-abrefecha="contratos_escola_ancora_div">
                    <?=$idioma["label_contratos_escola"];?>
              </legend>
              <div id="contratos_escola_ancora_div">

                  
        <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", false)) { ?>
                  <script type="text/javascript">
                    function atualizaContratoGet() {
                      var contrato = document.getElementById("idcontrato").options[document.getElementById("idcontrato").selectedIndex].value;
                      array_get = contrato.split("|");
                      link_var = "/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/gerarcontrato/"+array_get[0]+"/"+array_get[1];
                      document.getElementById('iframe_contrato_pre').src = link_var;
                    }
                  </script>
                  <div id="gerarcontrato" style="display:none">
                    <iframe id="iframe_contrato_pre" src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/gerarcontrato" width="700" height="500" frameborder="0"></iframe>
                  </div>
                <?php //} ?>
                <div style="float:left">
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_contrato"];?></strong></td>
                      <td bgcolor="#F4F4F4">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
                        <select name="idcontrato" id="idcontrato" class="span3" <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", false)) { ?>onchange="atualizaContratoGet()"<?php } else { ?>disabled="disabled"<?php } ?>>
                          <option value=""></option>
                          <?php foreach($contratos as $contrato) { ?>
                            <option value="<?= $contrato["tipo"]; ?>|<?= $contrato["idcontrato"]; ?>"><?= $contrato["nome"]; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td>
                      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", false)) { ?>
                        <a class="btn btn-mini" id="btn_contrato_pre" href="#gerarcontrato" rel="facebox" ><?php echo $idioma["btn_adicionar"]; ?></a>
                      <?php } else { ?>
                        <span class="btn btn-mini" disabled="disabled" data-placement="right" data-original-title="<?= $idioma['sem_permissao']; ?>" rel="tooltip" /><?= $idioma["btn_adicionar"]; ?></span>
                      <?php } ?>
                      </td>
                    </tr>
                  </table>
                </div>
                <form method="post" action="" style="float:left; margin-left:10px;" enctype="multipart/form-data">
                  <?php //if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", false)) { ?>
                    <input name="acao" type="hidden" value="enviar_contrato" />
                  <?php //} ?>
                  <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                      <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_eviar"];?></strong></td>
                      <td bgcolor="#F4F4F4"><?=$idioma["contratos_escola_tipo"];?></td>
                      <td bgcolor="#F4F4F4">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input name="contrato" type="file" id="contrato" /></td>
                      <td>
                        <select name="idtipo" id="idtipo" style="width:auto">
                          <?php foreach($tiposContratos as $tipo) { ?>
                            <option value="<?= $tipo["idtipo"]; ?>"><?= $tipo["nome"]; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                      <td><input class="btn btn-mini" type="submit" value="<?php echo $idioma["btn_enviar"]; ?>" /></td>
                    </tr>
                    <tr>
                      <td colspan="3" style="color:#999999"><?=$idioma["contratos_escola_extensao_arquivo"];?></td>
                    </tr>
                  </table>
                </form>
        <?php } ?>
        <div style="clear: both;">
              <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho" <?php if($margin) { ?>style="margin-top:<?php echo $margin; ?>px;<?php } ?>">
                <tr>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_numero"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_tipo"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_nome"];?></strong></td>
                  
                  
                  
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_cancelado"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_aceito"];?></strong></td>
                  <td bgcolor="#F4F4F4"><strong><?=$idioma["contratos_escola_ip"];?></strong></td>
                  <td bgcolor="#F4F4F4">&nbsp;</td>
                </tr>
                <?php
                if(count($linha["contratos"]) > 0) {
                  foreach($linha["contratos"] as $contrato){
                  ?>
                    <tr>
                      <td><?= $contrato["idescola_contrato"]; ?></td>
                      <td><?= $contrato["tipo"]; ?></td>
                      <td>
                        <?php
            if($contrato["contrato"]) {
              $nomeContrato = $contrato["contrato"];
            } else {
              $nomeContrato = $contrato["arquivo"];
            }
              echo $nomeContrato;
                        ?>
            <br />
                        <span style="color:#999999">
                          <?php if($contrato["contrato"]) { echo $idioma["contratos_escola_gerado_dia"]; } else { echo $idioma["contratos_escola_enviado_dia"]; } ?>
                          <?= formataData($contrato["data_cad"],'br',1); ?>
                        </span>
                      </td>
<!--                      <td>
                        <?php
                        $nomeContrato = addcslashes($nomeContrato,"'");
                        if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) {
                          if($contrato["cancelado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_escola_alerta_cancelado_assinar"].'\')"';
                          } elseif($contrato["validado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_escola_alerta_validado"].'\')"';
                          } else {
                            if($contrato["assinado"]) {
                              $situacao = 2;
                            } else if($contrato["nao_assinado"]) {
                              $situacao = 1;
                            } else {
                              $situacao = 0;
                            }
                            $href = 'href="#assinarcontrato" rel="facebox" onclick="assinarContrato('.$contrato["idescola_contrato"].',\''.$nomeContrato.'\','.$situacao.')"';
                          }
                          ?>
                          <a <?= $href; ?>>
                        <?php } ?>
                        <?php if($contrato["assinado"]) { ?>
                          <span class="label" <?php if(!$contrato["cancelado"]) { ?>style="background-color:#060"<?php } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["assinado"],'br',1); ?></span>
                        <?php } elseif($contrato["nao_assinado"]) { ?>
                          <span class="label" <?php if(!$contrato["cancelado"]) { ?>style="background-color:#FF0000"<?php } ?> title="<?= $contrato["usuario_assina"]; ?>" rel="tooltip"><?= formataData($contrato["nao_assinado"],'br',1); ?></span>
                        <?php }else { ?>
                          <span class="label"><?= $idioma["contratos_escola_nao_assinado"]; ?></span>
                        <?php } ?>
                        <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) { ?>
                          </a>
                        <?php } ?>
                      </td>-->
                      
                      
<!--                      <td>
                        <?php
                        if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) {
                          if($contrato["cancelado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_escola_alerta_cancelado_validadar"].'\')"';
                          } elseif(!$contrato["assinado"]) {
                            $href = 'href="javascript:alert(\''.$idioma["contratos_escola_alerta_assinado"].'\')"';
                          } else {
                            if($contrato["validado"]) {
                              $situacaoAtual = 2;
                            } else if($contrato["nao_validado"]){
                              $situacaoAtual = 1;
                            } else {
                              $situacaoAtual = 0;
                            }
                            $href = 'href="#validarcontrato" rel="facebox" onclick="validarContrato('.$contrato["idescola_contrato"].',\''.$nomeContrato.'\','.$situacaoAtual.')"';
                          }
                          ?>
                          <a <?= $href; ?>>
                        <?php } ?>
                        <?php if($contrato["validado"]) { ?>
                          <span class="label" <?php if(!$contrato["cancelado"]) { ?>style="background-color:#060"<?php } ?> title="<?= $contrato["usuario_valida"]; ?>" rel="tooltip"><?= formataData($contrato["validado"],'br',1); ?></span>
                        <?php } else if($contrato["nao_validado"]) { ?>
                          <span class="label" <?php if(!$contrato["cancelado"]) { ?>style="background-color:#FF0000"<?php } ?> title="<?= $contrato["usuario_valida"]; ?>" rel="tooltip"><?= formataData($contrato["nao_validado"],'br',1); ?></span>
                        <?php } else { ?>
                          <span class="label"><?= $idioma["contratos_escola_nao_validado"]; ?></span>
                        <?php } ?>
                        <?php if( $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) { ?>
                          </a>
                        <?php } ?>
                      </td>-->
                      <td>
                        <?php /*echo $contrato["cancelado"];*/ if($contrato["cancelado"]) { ?>
                          <a href="javascript:alert('<?=$idioma["contratos_escola_cancelado_definitivo"];?><?php if ($contrato["justificativa"]) {?>\n\nJustificativa:\n<?= $contrato["justificativa"]; ?><?php } ?>')"> <span class="label" style="background-color:#C00" title="<?= $contrato["usuario_cancela"]; ?>" rel="tooltip"><?= formataData($contrato["cancelado"],'br',1); ?></span> </a>
                        <?php } else {?>
                          <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8",false)) { ?>
                            <a href="#cancelarcontrato" rel="facebox" onclick="attContrato('<?= $contrato["idescola_contrato"]; ?>');"><span class="label"><?=$idioma["contratos_escola_cancelar"];?></span></a>
                          <?php } else { ?>
                            <span class="label" title="<?= $idioma["sem_permissao"]; ?>" rel="tooltip"><?=$idioma["contratos_escola_cancelar"];?></span>
                          <?php } ?>
                        <?php } ?>
                        &nbsp;

                      </td>
                      <td>
                        <?php if($contrato["aceito_cfc"] == 'S') {
                          echo "Aceito";
                          ?>
                          <br />
                          <span style="color:#999999">
                            <?= 'Data: '.formataData($contrato["aceito_cfc_data"],'br',1); ?>
                          </span>
                        <?php } else {
                          echo "Aguardando"; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if($contrato["ip"]) {
                          echo $contrato["ip"];
                          ?>
                        <?php } else {
                          echo "Aguardando"; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if($contrato["contrato"]) { ?>
                          <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/contratopdf/<?= $contrato["idescola_contrato"]; ?>" target="_blanck"><?=$idioma["contratos_escola_abrir_pdf"];?></a>
                          &nbsp;
                          <a class="btn btn-mini" href="javascript:abrePopup('/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/contrato/<?= $contrato["idescola_contrato"]; ?>','contrato<?= $contrato["idescolacontrato"]; ?>','scrollbars=yes,resizable=yes,width=800,height=600')" ><?=$idioma["contratos_escola_abrir"];?></a>
                        <?php } else { ?>
                          <a class="btn btn-mini" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/contratodownload/<?= $contrato["idescola_contrato"]; ?>" ><?=$idioma["contratos_escola_download"];?></a>
                        <?php } ?>
                       
                      </td>
                    </tr>
          <?php } ?>
                  
                <?php
                } else { ?>
                  <tr>
                    <td colspan="7"><?=$idioma["nenhum_contrato"];?></td>
                  </tr>
                <?php } ?>
              </table>
              </div>
            </section>
              
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
      
    <?php incluirLib("rodape",$config,$usuario); ?>
    <script>
        function attContrato(id) {
          document.getElementById('iframe_cancelarcontrato').src = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/cancelarcontrato?r=' + id;
        }

    </script>
</div>
</body>
</html>