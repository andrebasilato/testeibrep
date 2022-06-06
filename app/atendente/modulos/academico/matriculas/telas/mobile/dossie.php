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
<table width="100%" border="0" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan="9" bgcolor="#FF0000"><strong style="color:#FFFFFF">AS INFORMAÇÕES EM VERMELHO ESTÃO POR FAZER!</strong></td>
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
    <td colspan="2"><strong><?= $matricula["curso"]["nome"]; ?></strong></td>
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
	  if (count($matricula["documentos_pendentes"]) > 0) {			  
		foreach($matricula["documentos_pendentes"] as $documento) { ?>
		  <?= $documento["nome"]; ?>
		<? }
	  } else { ?>
		<?= $idioma["todos_documento_entregues"]; ?>
	  <? } ?>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["avaliacoes"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["ava"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["financeiro"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
	  <? foreach($matricula["contas"] as $contas) { ?>
        <h4><?= $contas[0]["evento"]; ?></h4>
        <br />
        <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
          <tr>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_forma_pagamento"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_valor"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_vencimento"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_situacao"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_bandeira_cartao"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_autorizacao_cartao"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_banco_cheque"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_agencia_cheque"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_cc_cheque"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_numero_cheque"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["financeiro_emitente_cheque"];?></strong></td>
          </tr>
          <? 
          $total = 0;
          $total_compartilhado = 0;
          $totalDesconto = 0;
          foreach($contas as $conta) { 
            $total += $conta["valor"];
            $totalDesconto += $conta["desconto"];
            ?>
            <tr>
              <td><?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?></td>
              <td>
                <span style="color:#999">R$</span>                   
				<?php
				if($conta['valor_matricula']) {
				  $valor_parcela = ($conta["valor_matricula"]/$conta['total_contas_compartilhadas']);
				  $total_compartilhado += $valor_parcela;
				  echo '<strong>'.number_format($valor_parcela, 2, ",", ".").'</strong> <span style="color:#999"> / '. number_format($conta["valor"], 2, ",", ".").'</span>';
				} else {
				  echo '<strong>'.number_format($conta["valor"], 2, ",", ".").'</strong>';
				} 
				?>                  
              </td>
              <td><?php echo formataData($conta["data_vencimento"],'br',0); ?></td>
              <td><span data-original-title="<?php echo $conta["situacao"]; ?>" class="label" style="background:#<?php echo $conta["cor_bg"]; ?>;color:#<?php echo $conta["cor_nome"]; ?>" data-placement="left" rel="tooltip"><?php echo $conta["situacao"]; ?></span></td>
              <td><?php if($conta["bandeira_cartao"]) echo $conta["bandeira_cartao"]; else echo "--"; ?></td>
              <td><?php if($conta["autorizacao_cartao"]) echo $conta["autorizacao_cartao"]; else echo "--"; ?></td>
              <td><?php if($conta["banco"]) echo $conta["banco"]; else echo "--"; ?></td>
              <td><?php if($conta["agencia_cheque"]) echo $conta["agencia_cheque"]; else echo "--"; ?></td>
              <td><?php if($conta["cc_cheque"]) echo $conta["cc_cheque"]; else echo "--"; ?></td>
              <td><?php if($conta["numero_cheque"]) echo $conta["numero_cheque"]; else echo "--"; ?></td>
              <td><?php if($conta["emitente_cheque"]) echo $conta["emitente_cheque"]; else echo "--"; ?></td>
            </tr>
            <?php } ?>
          <tr>
            <td>&nbsp;</td>
            <td>
              <span style="color:#999">R$</span> 
			  <?php 
			  if($total_compartilhado) {
				echo '<strong>'.number_format($total_compartilhado, 2, ",", ".").'</strong> <span style="color:#999"> / ' . number_format($total, 2, ",", ".").'</span>';
			  } else {
				echo '<strong>'.number_format($total, 2, ",", ".").'</strong>';
			  }
			  ?>              
            </td>
            <td colspan="9">&nbsp;</td>
          </tr>                    
        </table>
        <br />
      <?php } ?> 
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["progresso_curso"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
      <strong><?= $idioma["percentual_aluno"]; ?></strong>
      <div class="progress progress-success">
        <div class="bar" style="width: <?php echo $matricula["andamento"]["porc_aluno"]; ?>%"><?php echo $matricula["andamento"]["porc_aluno_formatada"]; ?>%</div>
      </div>
      <strong><?= $idioma["percentual_ideal"]; ?></strong>
      <div class="progress progress-danger">
        <div class="bar" style="width: <?php echo $matricula["curso"]["percentual_ideal_ava"]; ?>%"><?php echo number_format($matricula["curso"]["percentual_ideal_ava"],2,",","."); ?>%</div>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["conteudos"]; ?></strong></td>
  </tr>
  <tr>
    <td colspan="9">
	  <? foreach($matricula["disciplinas"] as $disciplina) { ?>
        <h4><?= $disciplina["bloco"]; ?> - <?= $disciplina["nome"]; ?> - <?= $disciplina["andamento"]["porc_aluno_formatada"]; ?> %</h4>
        <br />
        <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
          <tr>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["conteudos_prova_virtual"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["conteudos_data"];?></strong></td>
            <td bgcolor="#F4F4F4"><strong><?=$idioma["conteudos_nota"];?></strong></td>
          </tr>
          <?php foreach($disciplina["provas"] as $prova) { ?>
            <tr>
              <td><?= $prova["nome"]; ?></td>
              <td><?= $prova["nota"]; ?></td>
              <td><?= $prova["data_cad"]; ?></td>
            </tr> 
		  <?php } ?>            
        </table>
        <br />
      <?php } ?> 
    </td>
  </tr>
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
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_forum"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_biblioteca"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_chat"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["detalhes_acesso_simulado"]; ?></td>
          <td>0</td>
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
      <table width="720" cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["participacao_aluno_ferramenta"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong>%</strong></td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_conteudo"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_forum"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_duvidas"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><?= $idioma["participacao_aluno_simulado"]; ?></td>
          <td>0</td>
        </tr>
        <tr>
          <td><strong><strong><?= $idioma["participacao_aluno_total"]; ?></strong></strong></td>
          <td>0</td>
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