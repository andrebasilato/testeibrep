<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usu_vendedor); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
    body, td, th {
      font-family: Verdana, Geneva, sans-serif;
      font-size: 11px;
      color: #000;
    }
    body {
      background-color: #FFF;
      background-image:none;
      padding-top: 0px;
      margin-left: 5px;
      margin-top: 5px;
      margin-right: 5px;
      margin-bottom: 5px;
    }
    a:link {
      color: #000;
      text-decoration: none;
    }
    a:visited {
      text-decoration: none;
      color: #000;
    }
    a:hover {
      text-decoration: underline;
      color: #000;
    }
    a:active {
      text-decoration: none;
      color: #000;
    }
    body, td, th {
      font-family: Verdana, Geneva, sans-serif;
      font-size: 10px;
      color: #000;
    }
    .impressao {
        display:none;
    }
  </style>
  <link href="/assets/css/progress.css" rel="stylesheet">
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80">
      <table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img alt="<?= $config["tituloSistema"]; ?>" src="/especifico/img/logo_empresa.png" />*/?></td>
        </tr>
      </table>
    </td>
    <td align="center"><h2><strong><?= $idioma["dossie_aluno"]; ?></strong></h2></td>
  </tr>
</table>
<br>
<br>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["dados_aluno"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["codigo_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["idpessoa"]; ?></strong></td>
    <td><?= $idioma["nome_aluno"]; ?></td>
    <td colspan="6"><strong><?= $matricula["pessoa"]["nome"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["data_nasc_aluno"]; ?></td>
    <td><strong><?= formatadata($matricula["pessoa"]["data_nasc"],"br",0); ?></strong></td>
    <td><?= $idioma["naturalidade_aluno"]; ?></td>
    <td colspan="3"><strong><?= $matricula["pessoa"]["naturalidade"]; ?></strong></td>
    <td><?= $idioma["documento_aluno"]; ?></td>
    <td colspan="2"><strong><?= str_pad($matricula["pessoa"]["documento"], 11, "0", STR_PAD_LEFT); ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["rg_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["rg"]; ?></strong></td>
    <td><?= $idioma["rg_orgao_emissor_aluno"]; ?></td>
    <td colspan="3"><strong><?= $matricula["pessoa"]["rg_orgao_emissor"]; ?></strong></td>
    <td><?= $idioma["rg_data_emissao_aluno"]; ?></td>
    <td colspan="2"><strong><?= formatadata($matricula["pessoa"]["rg_data_emissao"],"br",0); ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["filiacao_mae_aluno"]; ?></td>
    <td colspan="8"><strong><?= $matricula["pessoa"]["filiacao_mae"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["filiacao_pai_aluno"]; ?></td>
    <td colspan="8"><strong><?= $matricula["pessoa"]["filiacao_pai"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["endereco_aluno"]; ?></td>
    <td colspan="6"><strong><?= $matricula["pessoa"]["endereco"]; ?></strong></td>
    <td><?= $idioma["numero_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["numero"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["complemento_aluno"]; ?></td>
    <td colspan="6"><strong><?= $matricula["pessoa"]["complemento"]; ?></strong></td>
    <td><?= $idioma["cep_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["cep"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["telefone_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["telefone"]; ?></strong></td>
    <td><?= $idioma["celular_aluno"]; ?></td>
    <td><strong><?= $matricula["pessoa"]["celular"]; ?></strong></td>
    <td><?= $idioma["email_aluno"]; ?></td>
    <td colspan="4"><strong><?= $matricula["pessoa"]["email"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["dados_matricula"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["sindicato"]; ?></td>
    <td colspan="8"><strong><?= $matricula["sindicato"]["nome"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["curso"]; ?></td>
    <td colspan="8"><strong><?= $matricula["curso"]["nome"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["oferta"]; ?></td>
    <td colspan="2"><strong><?= $matricula["oferta"]["nome"]; ?></strong></td>
    <td><?= $idioma["data_cad_matricula"]; ?></td>
    <td><strong><?= formatadata($matricula["data_cad"],"br",0); ?></strong></td>
    <td><?= $idioma["data_matricula"]; ?></td>
    <td><strong><?= formatadata($matricula["data_matricula"],"br",0); ?></strong></td>
    <td><?= $idioma["codigo_matricula"]; ?></td>
    <td><strong><?= $matricula["idmatricula"]; ?></strong></td>
  </tr>
  <tr>
    <td>TURMA:</td>
    <td colspan="8"><strong><?= $matricula["turma"]["nome"]; ?></strong> (<?= $matricula["turma"]["idturma"]; ?>)</td>
  </tr>
  <tr>
    <td><?= $idioma["sindicato_matricula"]; ?></td>
    <td colspan="8"><strong><?= $matricula["sindicato_matricula"]; ?></strong></td>
  </tr>
  <tr>
    <td><?= $idioma["cidade_matricula"]; ?></td>
    <td><strong><?= $matricula["cidade_matricula"]; ?></strong></td>
    <td><?= $idioma["estado_matricula"]; ?></td>
    <td><strong><?= $matricula["estado_matricula"]; ?></strong></td>
    <td><?= $idioma["ano_conclusao"]; ?></td>
    <td colspan="4"><strong><?= $matricula["ano_conclusao"]; ?></strong></td>
  </tr>
</table>
<table class="quebra_pagina"><tr><td></td></tr></table>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan="5" bgcolor="#F4F4F4"><strong><?= $idioma["documentos_entregues"]; ?></strong></td>
    <td colspan="4" bgcolor="#F4F4F4"><strong><?= $idioma["documentos_pendentes"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="5">
      <table width="700" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_tipo"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong> <?=$idioma["documentos_matricula_pessoa"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["documentos_matricula_situacao"];?></strong></td>
        </tr>
    <?
        if (count($matricula["documentos"]) > 0) {
          foreach($matricula["documentos"] as $documento) { ?>
            <tr>
              <td><?= $documento["tipo"]; ?></td>
              <td><? if($documento["associacao"]) { echo $idioma["associacao"]; } else { echo $idioma["aluno"]; } ?></td>
              <td><span class="label" style="background-color:<?= $situacao_documento_cores[$documento["situacao"]]; ?>" ><?= $situacao_documento[$config["idioma_padrao"]][$documento["situacao"]]; ?></span></td>
            </tr>
          <? }
        } else { ?>
          <tr>
            <td colspan="3"><?=$idioma["nenhum_documento_entregue"];?></td>
          </tr>
        <? } ?>
      </table>
    </td>
    <td colspan="4" valign="top">
    <?
    if (count($matricula["documentos_pendentes"]) > 0) { ?>
    <ul>
      <? foreach($matricula["documentos_pendentes"] as $documento) { ?>
            <li><?= $documento["nome"]; ?></li>
          <? } ?>
        </ul>
    <? } else { ?>
    <?= $idioma["todos_documento_entregues"]; ?>
    <? } ?>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["avaliacoes"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4" style="height:30px"><strong><?= $idioma["notas_disciplina"]; ?></strong></td>
          <?
      $colunas = 0;
      foreach($matricula["disciplinas"] as $ind => $disciplina) {
      $notas = count($disciplina["notas"]);
      if($notas > $colunas) $colunas = $notas;
      }
      for($i = 1;$i <= $colunas; $i++){ ?>
            <td bgcolor="#F4F4F4"><strong><?= $idioma["notas_nota"]; ?><?= $i; ?></strong></td>
          <? } ?>
          <td bgcolor="#F4F4F4"><strong><?= $idioma["situacao_nota"]; ?></strong></td>
        </tr>
        <? foreach($matricula["disciplinas"] as $ind => $disciplina) { ?>
          <tr>
            <td align="right" style="text-align:right; height:30px"><strong><?=$disciplina["nome"];?></strong></td>
            <? for($i = 1;$i <= $colunas; $i++){
              $nota = $disciplina["notas"][$i-1];
              ?>
              <td style="text-align:center;">
                <? if($nota){ ?>
                  <? if(!$nota["idprova"] && !$nota["id_solicitacao_prova"]){ ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-bottom:0px">
                      <tr>
                        <td>
                            <?= ($disciplina['nota_conceito'] == 'S') ? notaConceito($nota['nota']) : number_format($nota["nota"],1,',','.'); ?>
                        </td>
                      </tr>
                    </table>
                  <? } else { ?>
                    <span style="color:#999">
                      <?= $nota["nota"]; ?>
                      <? if($nota["idprova"]){ ?><sup>1</sup><? } ?>
                      <? if($nota["id_solicitacao_prova"]){ ?><sup>2</sup><? } ?>
                    </span>
                  <? } ?>
                <? } ?>&nbsp;
              </td>
            <? } ?>
            <td><?php echo $disciplina['situacao']['situacao']; ?></td>
          </tr>
        <? } ?>
        <tr>
          <td colspan="<?= $colunas+2; ?>" align="center" style="text-align:center"><?= $idioma["notas_legenda"]; ?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table class="quebra_pagina"><tr><td></td></tr></table>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td colspan="9" bgcolor="#F4F4F4"><strong>PROVAS SOLICITADAS</strong></td>
    </tr>
    <tr>
        <td colspan="9">
            <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                <tr>
                    <td bgcolor="#F4F4F4"><strong>Data de realização</strong></td>
                    <td bgcolor="#F4F4F4"><strong>De</strong></td>
                    <td bgcolor="#F4F4F4"><strong>Até</strong></td>
                    <td bgcolor="#F4F4F4"><strong>CFC / Unidade</strong></td>
                    <td bgcolor="#F4F4F4"><strong>Disciplinas</strong></td>
                    <td bgcolor="#F4F4F4"><strong>Dt. Solicitação</strong></td>
                    <td bgcolor="#F4F4F4"><strong>Situação</strong></td>
                    <td bgcolor="#F4F4F4"><strong>Motivo de cancelamento</strong></td>
                </tr>
                <?php
                if(count($matricula["solicitacoes"]) > 0) {
                    foreach($matricula["solicitacoes"] as $solicitacoes) { ?>
                        <tr>
                            <td><?= $solicitacoes["data_realizacao"]; ?></td>
                            <td><?= $solicitacoes["de"]; ?></td>
                            <td><?= $solicitacoes["ate"]; ?></td>
                            <td><?= $solicitacoes["escola_local"]; ?></td>
                            <td><?= $solicitacoes["disciplinas"]; ?></td>
                            <td><?= $solicitacoes["data_solicitacao"]; ?></td>
                            <td>
                                <?php
                                if($solicitacoes["situacao"] == "E") {
                                        echo "<span class=\"label\" style=\"background-color:#FF6600\" >Em espera</span>";
                                } elseif ($solicitacoes["situacao"] == "A") {
                                        echo "<span class=\"label\" style=\"background-color:#339900\" >Agendada</span>";
                                } elseif ($solicitacoes["situacao"] == "C") {
                                        echo "<span class=\"label\" style=\"background-color:#ff0000\" >Cancelada</span>";
                                }
                                ?>
                            </td>
                            <td><?php if($solicitacoes["situacao"] == "C") echo $solicitacoes["motivo_cancelamento"]; else echo '--'; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="8">Nenhuma solicitação de prova foi encontrada!</td>
                    </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
</table>
<table class="quebra_pagina"><tr><td></td></tr></table>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["financeiro"]; ?></strong></td>
    </tr>
    <tr>
        <td colspan="9">
            <?php foreach($matricula["contas"] as $contas) { ?>
                <h4><?= $contas[0]["evento"]; ?></h4>
                <br />
                <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                    <tr>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_vencimento"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_situacao"];?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_data_pagamento"];?></strong></td>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_bandeira_cartao"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_autorizacao_cartao"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_banco_cheque"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_agencia_cheque"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_cc_cheque"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_numero_cheque"];?></strong></td><?php */ ?>
                        <?php /* ?><td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_emitente_cheque"];?></strong></td><?php */ ?>
                        <td bgcolor="#F4F4F4">&nbsp;</td>
                    </tr>
                    <?
                    $total = 0;
                    $totalCompartilhado = 0;
                    $totalDesconto = 0;
                    foreach($contas as $conta) {
                        //if($conta['idpagamento_compartilhado'])
                            //$valorParcela = ($conta["valor_matricula"] / $conta['total_contas_compartilhadas']);

                        $style = '';
                        if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao']  || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                            $style = ' style="text-decoration:line-through;"';
                        } else {
                            //if($conta['idpagamento_compartilhado'])
                                //$totalCompartilhado += $valorParcela;
                            //else
                                $total += $conta["valor_parcela"];
                        } ?>
                        <tr>
                            <td>
                                <?php
                                echo $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]];
                                if($conta['idpagamento_compartilhado']) { ?>
                                    <span style="color:red;">(<?= $idioma['conta_compartilhada'] ?>)</span>
                                <?php } ?>
                            </td>
                            <td>
                                <span style="color:#999">R$</span>
                                <?php
                                //if($conta['idpagamento_compartilhado']) {
                                    //echo '<strong><span'.$style.'>'.number_format($valorParcela, 2, ",", ".").'</span></strong> <span style="color:#999"> / '. number_format($conta["valor"], 2, ",", ".").'</span>';
                                //} else {
                                    echo '<strong><span'.$style.'>'.number_format($conta["valor_parcela"], 2, ",", ".").'</span></strong>';
                                //} ?>
                            </td>
                            <td>
                                <?php
                                $styleVencimento = '';
                                if ($conta["situacao_cancelada"] == 'N' && $conta["situacao_renegociada"] == 'N' && $conta["situacao_transferida"] == 'N' && $conta["situacao_paga"] == 'N') {
                                    if (date('Y-m-d') > $conta["data_vencimento"])
                                        $styleVencimento = 'color:#FF0000;font-weight:bold;';
                                    else if (date('Y-m-d') == $conta["data_vencimento"]) {
                                        $styleVencimento = 'color:#FFA500;font-weight:bold;';
                                    }
                                } else if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao'] || $situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                    $styleVencimento .= 'text-decoration:line-through;';
                                } ?>
                                <font style="<?php echo $styleVencimento; ?>">
                                    <?php echo formataData($conta["data_vencimento"],'br',0); ?>
                                </font>
                            </td>
                            <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
                            <td><?php if($conta["data_pagamento"] && $conta["data_pagamento"] != '0000-00-00') echo formataData($conta["data_pagamento"], 'br', 0); else echo "--"; ?></td>
                            <?php /* ?><td><?php if($conta["bandeira_cartao"]) echo $conta["bandeira_cartao"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["autorizacao_cartao"]) echo $conta["autorizacao_cartao"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["banco"]) echo $conta["banco"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["agencia_cheque"]) echo $conta["agencia_cheque"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["cc_cheque"]) echo $conta["cc_cheque"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["numero_cheque"]) echo $conta["numero_cheque"]; else echo "--"; ?></td><?php */ ?>
                            <?php /* ?><td><?php if($conta["emitente_cheque"]) echo $conta["emitente_cheque"]; else echo "--"; ?></td><?php */ ?>
                            <td>
                                <?php
                                if($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao']) {
                                    echo $conta["parcelas_renegociadas"];
                                } elseif($situacaoTransferidaConta['idsituacao'] == $conta['idsituacao']) {
                                    echo $conta['matricula_transferida'] . ' (' . $conta["idconta_transferida"] . ')';
                                } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <span style="color:#999">R$</span>
                            <?php
                            //if($conta['idpagamento_compartilhado']) {
                                //echo '<strong>'.number_format(($totalCompartilhado + $total), 2, ",", ".").'</strong>';
                            //} else {
                                echo '<strong>'.number_format($total, 2, ",", ".").'</strong>';
                            //} ?>
                        </td>
                        <td colspan="11">&nbsp;</td>
                    </tr>
                </table>
                <br />
            <?php } ?>
        </td>
    </tr>
</table>
<table class="quebra_pagina"><tr><td></td></tr></table>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["progresso_curso"]; ?></strong></td>
  </tr>
  <tr>
    <?php
  if($matricula['porcentagem_manual']) {
    $matricula["andamento"]["porc_aluno"] = $matricula['porcentagem_manual'];
    $matricula["andamento"]["porc_aluno_formatada"] = number_format($matricula["andamento"]["porc_aluno"], 2, ',', '.');
  }
  ?>
    <td colspan="9">
      <strong><?= $idioma["percentual_aluno"]; ?></strong>
      <?php/*<div class="progress progress-success">
        <div class="bar" style="width: <?php echo $matricula["andamento"]["porc_aluno"]; ?>px;"><?php echo $matricula["andamento"]["porc_aluno_formatada"]; ?>%</div>
      </div>*/?>
    <div>
      <?php
      $width = intval($matricula["andamento"]["porc_aluno"]*2);
    ?>
    <?php if ($width) { ?>
    <div style="background:#5eb95e; width:<?php echo $width; ?>px;">
      <?php for($i=0; $i< $width;$i++) echo '&nbsp;'; ?>
      <?php echo $matricula["andamento"]["porc_aluno_formatada"]; ?>%
    </div>
    <?php } else { ?>
      <?php echo $matricula["andamento"]["porc_aluno_formatada"]; ?>%
    <?php } ?>
    </div>
      <strong><?= $idioma["percentual_ideal"]; ?></strong>
      <?php /*<div class="progress progress-danger">
        <div class="bar" style="width: <?php echo $matricula["curso"]["percentual_ideal_ava"]; ?>%"><?php echo number_format($matricula["curso"]["percentual_ideal_ava"],2,",","."); ?>%</div>
      </div>*/?>
    <div>
      <?php
      $width_c = intval($matricula["curso"]["percentual_ideal_ava"]*2);
    ?>
    <?php if ($width_c) { ?>
    <div style="background:#dd514c; width:<?php echo $width_c; ?>px;">
      <?php for($i=0; $i< $width_c;$i++) echo '&nbsp;'; ?>
      <?php echo number_format($matricula["curso"]["percentual_ideal_ava"],2,",","."); ?>%
    </div>
    <?php } else { ?>
      <?php echo number_format($matricula["curso"]["percentual_ideal_ava"],2,",","."); ?>%
    <?php } ?>
    </div>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma['conteudos']; ?></strong></td>
  </tr>
    <tr>
        <td colspan="9">
            <table width="720" cellpadding="5" cellspacing="0" class="table-condensed tabelaSemTamanho">
                <?php
                foreach ($matricula['disciplinas'] as $indDisciplina => $disciplina) {
                    ?>
                    <tr>
                        <td colspan="3">
                            <?= ($indDisciplina > 0) ? '<br /><br />' : ''; ?>
                            <h4><?= $disciplina['bloco']; ?> - <?= $disciplina['nome']; ?> - <?= $disciplina['andamento']['porc_aluno_formatada']; ?> %</h4>
                        </td>
                    </tr>
                    <tr class="table table-bordered">
                        <td bgcolor="#F4F4F4"><strong><?= $idioma['conteudos_prova_virtual']; ?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?= $idioma['conteudos_data']; ?></strong></td>
                        <td bgcolor="#F4F4F4"><strong><?= $idioma['conteudos_nota']; ?></strong></td>
                    </tr>
                    <?php
                    foreach ($disciplina['exercicios'] as $exercicio) {
                        ?>
                        <tr class="table table-bordered">
                            <td><?= $exercicio['nome']; ?></td>
                            <td><?= formataData($exercicio['inicio'],'br',1); ?></td>
                            <td><?= number_format($exercicio['nota'],2,',',''); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </td>
    </tr>
</table>
<table class="quebra_pagina"><tr><td></td></tr></table>
<table width="100%" border="1" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["detalhes"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
      <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["detalhes_ferramenta"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["detalhes_contribuicao"];?></strong></td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_duvidas"]; ?></td>
          <td><?= $contribuicao["tiraduvidas"]; ?></td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_forum"]; ?></td>
          <td><?= $contribuicao["forum"]; ?></td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_biblioteca"]; ?></td>
          <td><?= $contribuicao["biblioteca"]; ?></td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_chat"]; ?></td>
          <td><?= $contribuicao["chat"]; ?></td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_simulado"]; ?></td>
          <td><?= $contribuicao["simulado"]; ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["estatisticas_acesso"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
      <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["estatisticas_acesso_estatistica"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["estatisticas_acesso_acesso"];?></strong></td>
        </tr>
        <tr>
          <td><?= $idioma["estatisticas_acesso_quantidade"]; ?></td>
          <td><?php echo $matricula["total_acessos_ava"]; ?></td>
        </tr>
        <tr>
          <td><?= $idioma["estatisticas_acesso_ultimo"]; ?></td>
          <td><?php echo formatadata($matricula["ultimo_acesso_ava"],"br",1); ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["participacao_aluno"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
      <?php
    $porcentagemTotal = $porcentagem["conteudo"] + $porcentagem["forum"] + $porcentagem["tiraduvida"] + $porcentagem["biblioteca"] + $porcentagem["simulado"] + $porcentagem["chat"];
    ?>
      <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["participacao_aluno_ferramenta"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong>%</strong></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_conteudo"]; ?></td>
          <td><?= number_format($porcentagem["conteudo"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_forum"]; ?></td>
          <td><?= number_format($porcentagem["forum"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_duvidas"]; ?></td>
          <td><?= number_format($porcentagem["tiraduvida"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_biblioteca"]; ?></td>
          <td><?= number_format($porcentagem["biblioteca"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_simulado"]; ?></td>
          <td><?= number_format($porcentagem["simulado"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_chat"]; ?></td>
          <td><?= number_format($porcentagem["chat"],2,',',''); ?></td>
        </tr>
        <tr>
          <td><strong><strong><?= $idioma["participacao_aluno_total"]; ?></strong></strong></td>
          <td><?= number_format($porcentagemTotal,2,',',''); ?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top">
      <span style="color:#999999;">
        Gerado dia <?= date("d/m/Y "); ?> por <?= $usu_vendedor["nome"]; ?> (<?= $usu_vendedor["email"]; ?>)<br>
        Alfama Oráculo - Sistema de disponibilidade. <br>
        www.alfamaoraculo.com.br
      </span>
    </td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
</body>
</html>