<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 10px;
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
table.zebra-striped {
    padding: 0;
    font-size: 9px;
    border-collapse: collapse;
}
table.zebra-striped th, table td {
    padding: 6px 6px 5px;
    line-height: 12px;
    text-align: left;
}
table.zebra-striped th {
    padding-top: 9px;
    font-weight: bold;
    vertical-align: middle;
}
table.zebra-striped td {
    vertical-align: top;
    border: 1px solid #000;
}
table.zebra-striped tbody th {
    border-top: 1px solid #ddd;
    vertical-align: top;
}
.zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
    background-color: #F4F4F4;
}
.zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
    background-color: #E4E4E4;
}
</style>
<style type="text/css" media="print">
body, td, th {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 9px;
    color: #000;
}
.impressao {
    display:none;   
}
</style>

<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script>
$(document).ready(function(){   
    $('a[rel*=facebox]').facebox(); 
});
var regras = new Array();
regras.push("required,nome,<?php echo $idioma['nome_obrigatorio']; ?>");
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="8">
      <tr>
        <td><a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="<?php echo $config['logo_pequena']; ?>" />*/?></td>
      </tr>
    </table></td>
    <td align="center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td  align="right" valign="top"><table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
      <tr>
        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
        <td><a href="javascript:window.print();">
          <?= $idioma["imprimir"]; ?>
        </a></td>
        
        <td>
            <a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
            <div id="link_salvar" style="display:none;"> 
                <div style="width:300px;">
                <form method="post" onSubmit="return validateFields(this, regras)">
                    <input type="hidden" name="acao" value="salvar_relatorio" />
                    <label for="nome"><strong><?php echo $idioma['tabela_nome']; ?>:</strong></label>
                    <input type="text" class="input" name="nome" id="nome" style="height:30px;" /><br /><br />
                    <input type="submit" class="btn" value="<?php echo $idioma['salvar_relatorio'] ?>" />
                </form>
                </div>
            </div>
        </td>
        
      </tr>
    </table></td>
  </tr>
</table>

<? if($mensagem_sucesso) { ?>
    <div class="alert alert-success fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_sucesso]; ?></strong>
    </div>
<? } else if($mensagem_erro) { ?>
    <div class="alert alert-error fade in">
        <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
        <strong><?= $idioma[$mensagem_erro]; ?></strong>
    </div>
<? } ?>

<center><h1><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></h1></center>
<br />
<?php 

//variavel aux adiciona devido a função GerarTabela está modificando o valor do get
$idcursoBusca = $_GET['idcurso'];
$idRegiaoBusca = $_GET['idregiao'];
$idIntituicaoBusca = $_GET['idsindicato'];

$relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma);

$_GET['idcurso'] = $idcursoBusca;
$_GET['idregiao'] = $idRegiaoBusca;
$_GET['idsindicato'] = $idIntituicaoBusca;

$porcentagem_quantidade = ($dadosArray['ano']['dados']['quantidade']/$dadosArray['ano_anterior']['dados']['quantidade'])*100;
$porcentagem_valor = ($dadosArray['ano']['dados']['valor']/$dadosArray['ano_anterior']['dados']['valor'])*100;

$mediaValorAnoAnterior = $dadosArray['ano_anterior']['dados']['valor'] / $dadosArray['ano_anterior']['dados']['quantidade'];
$mediaValorAno = $dadosArray['ano']['dados']['valor'] / $dadosArray['ano']['dados']['quantidade'];
$porcentagemMediaValor = ($mediaValorAno / $mediaValorAnoAnterior)*100;

?>
<table border="1">
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Comparativo</strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano_anterior']['nome']; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">% em relação a <?php echo $dadosArray['ano_anterior']['nome']; ?></strong></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">qtde de Matrículas</strong></td>
        <td align="center" style="text-align:center"><?= $relatorioObj->FormataNumero($dadosArray['ano_anterior']['dados']['quantidade'], false, 0); ?></td>
        <td align="center" style="text-align:center"><?= $relatorioObj->FormataNumero($dadosArray['ano']['dados']['quantidade'], false, $dadosArray['ano_anterior']['dados']['quantidade']); ?></td>
        <td align="center" style="text-align:center; color:<? if($porcentagem_quantidade <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format($porcentagem_quantidade, 2, ',', '.') . '%'; ?></strong></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Faturamento</strong></td>
        <td><?= $relatorioObj->FormataValor($dadosArray['ano_anterior']['dados']['valor'], false, 0); ?></td>
        <td><?= $relatorioObj->FormataValor($dadosArray['ano']['dados']['valor'], false, $dadosArray['ano_anterior']['dados']['valor']); ?></td>
        <td align="center" style="text-align:center;  color:<? if($porcentagem_quantidade <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format($porcentagem_valor, 2, ',', '.') . '%'; ?></strong></td>
    </tr>
    <tr>
        <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Média de valor da Mat.</strong></td>
        <td><?= $relatorioObj->FormataValor($mediaValorAnoAnterior, false, 0); ?></td>
        <td><?= $relatorioObj->FormataValor($mediaValorAno, false, $mediaValorAnoAnterior); ?></td>
        <td align="center" style="text-align:center;  color:<? if($porcentagemMediaValor <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format($porcentagemMediaValor, 2, ',', '.') . '%'; ?></strong></td>
    </tr>
</table>
<br>
<table border="1">
  <tr>
    <td colspan="11" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;">Detalhamento por Estado no mês de referência: </strong><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td width="80" rowspan="2" align="center" style="background-color:#E4E4E4; text-align:center; width:80px;"><strong style="text-transform:uppercase;">Est.</strong></td>
    <td width="60" rowspan="2" align="center" style="background-color:#E4E4E4; text-align:center; width:60px;"><strong style="text-transform:uppercase;">D. úteis</strong></td>
    <td width="60" rowspan="2" align="center" style="background-color:#E4E4E4; text-align:center; width:60px;"><strong style="text-transform:uppercase;">D. Trab.</strong></td>
    <td colspan="2" align="center" style="background-color:#E4E4E4; text-align:center;"><strong style="text-transform:uppercase;">META</strong><strong style="text-transform:uppercase;"></strong></td>
    <td colspan="4" align="center" style="background-color:#E4E4E4; text-align:center;"><strong style="text-transform:uppercase;">REALIZADO</strong><strong style="text-transform:uppercase;"></strong></td>
    <td colspan="2" align="center" style="background-color:#E4E4E4; text-align:center;"><strong style="text-transform:uppercase;">MÉDIA</strong><strong style="text-transform:uppercase;"></strong></td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><strong>Qtde de    Matrículas</strong></td>
    <td bgcolor="#F4F4F4"><strong>Faturamento</strong></td>
    <td><strong>Qtde de    Matrículas</strong></td>
    <td><strong>% Qtde de    Matriculas<br>
      em Relação a Meta</strong></td>
    <td><strong>Faturamento</strong></td>
    <td><strong>%    Faturamento<br>
      em Relação a Meta</strong></td>
    <td bgcolor="#F4F4F4"><strong>Qtde de    Matrículas</strong></td>
    <td bgcolor="#F4F4F4"><strong>Valor Matrícula</strong></td>
  </tr>
  
<?php 
    $totais['meta_matricula'] = 0;
    $totais['meta_valor'] = 0;
    $totais['matricula'] = 0;
    $totais['valor'] = 0;
	//echo '<pre>';print_r($dadosArray['feriados']);
    foreach($dadosArray['estados'] as $idestado => $dados) { 
    	
		$diasjadiminuidos = array();
		
        $dias_uteis_estado = $dadosArray['uteis'];
        $dias_trabalhado_estado = $dadosArray['uteis_trabalhados'];
        /*if($_SESSION['adm_idusuario'] == 1) {
            echo 'Dias uteis: '.$dias_uteis_estado;
            echo '<br>';
            echo 'Dias uteis: '.$dias_trabalhado_estado;
            echo '<br>';
            echo '<br>';
            print_r2($dadosArray['feriados']);
        }*/
        foreach ($dadosArray['feriados'] as $feriado_array) {
            if (((is_array($feriado_array['estados']) && in_array($idestado, $feriado_array['estados'])) or (!is_array($feriado_array['estados'])))&& !in_array($feriado_array['dia'], $diasjadiminuidos)) {
				$dias_uteis_estado--;
				$diasjadiminuidos[] = $feriado_array['dia'];
            }
			
			if((in_array($idestado, $feriado_array['estados']) or !is_array($feriado_array['estados'])) and ($feriado_array['dia'] < date('d') or $_GET['mes']<  date('m'))){
				$dias_trabalhado_estado--;
			}
        }
        /*if($_SESSION['adm_idusuario'] == 1) {
            echo 'Dias uteis: '.$dias_uteis_estado;
            echo '<br>';
            echo 'Dias uteis: '.$dias_trabalhado_estado;
            exit;
        }*/

        $metas_quantidade_grafico[$idestado] = $dadosArray['estados_metas'][$idestado]['quantidade'];
        $metas_valor_grafico[$idestado] = $dadosArray['estados_metas'][$idestado]['valor'];
      
        $reais_quantidade_grafico[$idestado] = $dados['quantidade'];
        $reais_valor_grafico[$idestado] = $dados['valor'];
    
        $metas_quantidade = ($dados['quantidade']/$dadosArray['estados_metas'][$idestado]['quantidade'])*100;
        $metas_valor = ($dados['valor']/$dadosArray['estados_metas'][$idestado]['valor'])*100;
        $matriculas_dia_media = $dados['quantidade']/$dias_trabalhado_estado;
        $matricula_valor_medio = $dados['valor']/$dados['quantidade'];
        
        $totais['meta_matricula'] += $dadosArray['estados_metas'][$idestado]['quantidade'];
        $totais['meta_valor'] += $dadosArray['estados_metas'][$idestado]['valor'];
        $totais['matricula'] += $dados['quantidade'];
        $totais['valor'] += $dados['valor'];
    
?>  
  <tr>
    <td style="text-align:center" align="center"><font title="Idestado: <?= $dados['idestado']; ?>"><?= $dados['estado']; ?></font></td>
    <td style="text-align:center" align="center"><?= $dias_uteis_estado; ?></td>
    <td style="text-align:center" align="center"><?= $dias_trabalhado_estado; ?></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center;"><?= (int) $dadosArray['estados_metas'][$idestado]['quantidade']; ?></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center;"><?= $relatorioObj->FormataValor($dadosArray['estados_metas'][$idestado]['valor'], false, 0); ?></td>
    <td align="center" style="text-align:center;"><a href="/gestor/relatorios/vendas_detalhado/html?colunas[0]=1&colunas[1]=2&colunas[3]=3&colunas[4]=4&colunas[5]=5&colunas[6]=6&colunas[7]=7&colunas[8]=8&colunas[9]=9&colunas[10]=10&colunas[11]=11&colunas[12]=12&colunas[13]=13&q[de_ate|tipo_data_filtro|ma.data_registro]=PER&de=<?= $dadosArray['primeiro_dia']; ?>&ate=<?= $dadosArray['ultimo_dia']; ?>&q[1|ma.idsindicato]=<?= $_GET['idsindicato']; ?>&q[1|ma.idcurso]=<?= $_GET['idcurso']; ?>&q[1|inst.idestado_competencia]=<?= $dados['idestado'];?><?= $dadosArray['URL_situacoesInclusas']; ?><? if($_GET['bolsa']) { echo '&q[1|ma.bolsa]='.$_GET['bolsa'].''; } else { echo '&q[1|ma.bolsa]=N'; } ?><? if($_GET['combo']) { echo '&q[1|ma.combo]='.$_GET['combo'].''; } else { echo '&q[1|ma.combo]=N'; } ?>" target="_blank"><?= $dados['quantidade']; ?></a></td>
    <td align="center" style="text-align:center; color:<? if($metas_quantidade <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format($metas_quantidade, 2, ',', '.') . '%'; ?></strong></td>
    <td align="center" style="text-align:center;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;">R$</td>
        <td style="text-align:right; padding:2px;"><?php echo number_format($dados['valor'], 2, ',', '.'); ?></td>
      </tr>
    </table></td>
    <td align="center" style="text-align:center; color:<? if($metas_valor <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format($metas_valor, 2, ',', '.') . '%'; ?></strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center;"><?php echo number_format($matriculas_dia_media, 2, ',', '.'); ?></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;">R$</td>
        <td style="text-align:right; padding:2px;"><?php echo number_format($matricula_valor_medio, 2, ',', '.'); ?></td>
      </tr>
    </table></td>
  </tr>
<? } ?>
  <tr>
    <td colspan="3" align="right" style="text-align:right;">Total:</td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; border-top: 2px #000 solid;"><strong>
      <?= (int) $totais['meta_matricula']; ?>
    </strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center; border-top: 2px #000 solid;"><?= $relatorioObj->FormataValor($totais['meta_valor'], true, 0); ?></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><strong>
      <?= (int) $totais['matricula']; ?>
    </strong></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;; color:<? if($metas_quantidade <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format(((int) $totais['matricula']/(int) $totais['meta_matricula'])*100, 2, ',', '.') . '%'; ?></strong></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;"><strong>R$</strong></td>
        <td style="text-align:right; padding:2px;"><strong><?php echo number_format($totais['valor'], 2, ',', '.'); ?></strong></td>
        </tr>
    </table></td>
    <td align="center" style="text-align:center; border-top: 2px #000 solid;; color:<? if($metas_quantidade <= 100) { echo "#FF0000"; } else { echo "#000"; } ?>"><strong><?php echo number_format(($totais['valor']/$totais['meta_valor'])*100, 2, ',', '.') . '%'; ?></strong></td>
    <td align="center" bgcolor="#F4F4F4" style="text-align:center;">&nbsp;</td>
    <td align="right" bgcolor="#F4F4F4" style="text-align:right;">&nbsp;</td>
  </tr>
  
</table>
<hr style="page-break-before: always;">
<br />


<?
 
foreach($dadosArray['vendas_dia'] as $idestado => $dados_estado) {
?>
<table border="1">
  <tr>
    <td width="140" rowspan="2" align="center" style="background-color:#E4E4E4; width:140px; text-align:center;"><strong style="text-transform:uppercase;">vENDAS DIÁRIA: <?= $dados_estado["nome"]; ?></strong><strong style="text-transform:uppercase;"></strong></td>
    <? foreach($dados_estado['vendedores'] as $idvendedor => $dados) { ?>
    <td colspan="2" align="center" style="background-color:#E4E4E4; width:120px; text-align:center;"><strong style="text-transform:uppercase;"><font title="Idvendedor: <?= $dados['idvendedor']; ?>">
      <?= $dados['nome']; ?></font></td>
    <? } ?>
    <td colspan="2" rowspan="2" align="center" bgcolor="#FEFECB" style="text-align:center; width:120px;"><strong>TOTAL</strong></td>
  </tr>
  <tr>
    
    <? 
    $i = 0;
    foreach($dados_estado['vendedores'] as $idvendedor => $dados) { 
    ?>
    <td colspan="2" align="center" style="background-color:<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>; text-align:center;"><font title="Idvendedor: <?= $dados['idvendedor']; ?>">
      <?= $dados['regiao']; ?>
    </font></td>
    <? 
        $i++;
        if($i == 2) $i = 0;
    } 
    ?>
    
  </tr>
  <tr>
    <td align="center" style="background-color:#E4E4E4; text-align:center;"><strong>Dia</strong></td>
    <? 
    $i = 0;
    foreach($dados_estado['vendedores'] as $idvendedor => $dados) { ?>
    <td width="40" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center"><strong>Mat.</strong></td>
    <td width="80" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center"><strong>Fat.</strong></td>
    <?
        $i++;
        if($i == 2) $i = 0; 
     }
    ?>
    <td width="40" bgcolor="#FEFECB" style="text-align:center"><strong>Mat.</strong></td>
    <td width="80" bgcolor="#FEFECB" style="text-align:center"><strong>Fat.</strong></td>
  </tr>
  
  <? 
  
  $totais['vendedores'] = array();
  
  for ($dia = 1; $dia <= $dadosArray['dias_mes']; $dia++) { 
	//echo '<pre>';print_r($dadosArray['feriados']);
      $dia_feriado_estado = false;
      foreach ($dadosArray['feriados'] as $ind => $feriado_array) {
          if (is_array($feriado_array['estados']) && in_array($idestado, $feriado_array['estados']) && ((int) $feriado_array['dia'] == $dia)) {
              $dia_feriado_estado = true;
          }
      }  
  
  $dia_semana = date("N", mktime(0, 0, 0, $_GET['mes'], $dia, $_GET['ano']));
  
  ?>
  <tr>
    <td style="text-transform:uppercase;">
      <? if($dia_semana <> 6 && $dia_semana <> 7 && !$dia_feriado_estado) { ?>
      <strong>
      <? } else { ?>
      <span style="color:#999;">
      <? } ?>
      <?= $dia; ?> de <?php echo $dadosArray['mes_nome']; ?> - <?= $dia_semana_min["pt_br"][$dia_semana]; ?>
      <? if($dia_semana <> 6 && $dia_semana <> 7) { ?>
      </strong>
      <? } else { ?>
      </span>
      <? } ?>
      </td>
    <? 
    $i = 0;
    
    $totais['dias'] = array();
    
    foreach($dados_estado['vendedores'] as $idvendedor => $dados) { 
    
    $totais['vendedores'][$idvendedor]['quantidade'] += $dados_estado["vendedores"][$idvendedor]['dias'][$dia]['quantidade'];
    $totais['vendedores'][$idvendedor]['valor'] += $dados_estado["vendedores"][$idvendedor]['dias'][$dia]['valor'];
    
    $totais['dias'][$dia]['quantidade'] += $dados_estado["vendedores"][$idvendedor]['dias'][$dia]['quantidade'];
    $totais['dias'][$dia]['valor'] += $dados_estado["vendedores"][$idvendedor]['dias'][$dia]['valor'];

    
    ?>
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center;">
    <a href="/gestor/relatorios/vendas_detalhado/html?colunas[0]=1&colunas[1]=2&colunas[3]=3&colunas[4]=4&colunas[5]=5&colunas[6]=6&colunas[7]=7&colunas[8]=8&colunas[9]=9&colunas[10]=10&colunas[11]=11&colunas[12]=12&colunas[13]=13&q[de_ate|tipo_data_filtro|ma.data_registro]=PER&de=<?= $dia.'/'.$dadosArray['mes_numero'].'/'.$dadosArray['ano']['nome'];//$dadosArray['primeiro_dia'] ?>&ate=<?= $dia.'/'.$dadosArray['mes_numero'].'/'.$dadosArray['ano']['nome']; ?>&q[1|ma.idsindicato]=<?= $_GET['idsindicato']; ?>&q[1|ma.idcurso]=<?= $_GET['idcurso']; ?>&q[1|ma.idvendedor]=<?= $idvendedor; ?>&q[1|inst.idestado_competencia]=<?= $idestado ?><?= $dadosArray['URL_situacoesInclusas']; ?><? if($_GET['bolsa']) { echo '&q[1|ma.bolsa]='.$_GET['bolsa'].''; } else { echo '&q[1|ma.bolsa]=N'; } ?><? if($_GET['combo']) { echo '&q[1|ma.combo]='.$_GET['combo'].''; } else { echo '&q[1|ma.combo]=N'; } ?>" target="_blank">
        <?= $relatorioObj->FormataNumero($dados_estado["vendedores"][$idvendedor]['dias'][$dia]['quantidade'], false, 0); ?>
    </a>
    </td><? // $idestado; ?>
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;">R$</td>
        <td style="text-align:right; padding:2px;"><?php echo number_format($dados_estado["vendedores"][$idvendedor]['dias'][$dia]['valor'], 2, ',', '.'); ?></td>
        </tr>
    </table>
    
    </td>
    <? 
        $i++;
        if($i == 2) $i = 0;     
    }
    ?>

    <td align="center" bgcolor="#FEFECB" style="text-align:center;"><?= (int) $totais["dias"][$dia]['quantidade']; ?></td>
    <td align="center" bgcolor="#FEFECB" style="text-align:center;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
        <tr>
          <td style="color:#CCCCCC; padding:2px;">R$</td>
          <td style="text-align:right; padding:2px;"><?php echo number_format($totais["dias"][$dia]['valor'], 2, ',', '.'); ?></td>
        </tr>
      </table>
      
    </td>
  </tr>
  <? } 
  
  
  ?>
  
  <tr>
    <td style="text-align:right"><strong>Total:</strong></td>
    <? 
    $i = 0;
    
    $totais['geral'] = array();
    
    foreach($dados_estado['vendedores'] as $idvendedor => $dados) { 

    $totais['geral']['quantidade'] += $totais['vendedores'][$idvendedor]['quantidade'];
    $totais['geral']['valor'] += $totais['vendedores'][$idvendedor]['valor'];   
    
    ?>    
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center; border-top: 2px #000 solid;"><strong><a href="/gestor/relatorios/vendas_detalhado/html?colunas[0]=1&colunas[1]=2&colunas[3]=3&colunas[4]=4&colunas[5]=5&colunas[6]=6&colunas[7]=7&colunas[8]=8&colunas[9]=9&colunas[10]=10&colunas[11]=11&colunas[12]=12&colunas[13]=13&q[de_ate|tipo_data_filtro|ma.data_registro]=PER&de=<?= $dadosArray['primeiro_dia'];// $dia.'/'.$dadosArray['mes_numero'].'/'.$dadosArray['ano']['nome']; ?>&ate=<?= ($dia - 1).'/'.$dadosArray['mes_numero'].'/'.$dadosArray['ano']['nome']; ?>&q[1|ma.idsindicato]=<?= $_GET['idsindicato']; ?>&q[1|ma.idcurso]=<?= $_GET['idcurso']; ?>&q[1|ma.idvendedor]=<?= $idvendedor; ?>&q[1|estinst.idregiao]=<?= $_GET['idregiao']; ?>&q[1|inst.idestado_competencia]=<?= $idestado; ?><?= $dadosArray['URL_situacoesInclusas']; ?><? if($_GET['bolsa']) { echo '&q[1|ma.bolsa]='.$_GET['bolsa'].''; } else { echo '&q[1|ma.bolsa]=N'; } ?><? if($_GET['combo']) { echo '&q[1|ma.combo]='.$_GET['combo'].''; } else { echo '&q[1|ma.combo]=N'; } ?>" target="_blank">
      <?= (int) $totais['vendedores'][$idvendedor]['quantidade']; ?>
    </a></strong></td>
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center; border-top: 2px #000 solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;"><strong>R$</strong></td>
        <td style="text-align:right; padding:2px;"><strong><?php echo number_format($totais['vendedores'][$idvendedor]['valor'], 2, ',', '.'); ?></strong></td>
      </tr>
    </table></td>
    <? 
        $i++;
        if($i == 2) $i = 0;     
    }
    ?> 
    <td align="center" bgcolor="#FEFECB" style="text-align:center; border-top: 2px #000 solid;"><strong><?= (int) $totais['geral']['quantidade']; ?></strong></td>
    <td align="center" bgcolor="#FEFECB" style="text-align:center; border-top: 2px #000 solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;"><strong>R$</strong></td>
        <td style="text-align:right; padding:2px;"><strong><?php echo number_format($totais['geral']['valor'], 2, ',', '.'); ?></strong></td>
        </tr>
    </table></td>     

    </tr>
  <tr>
    <td style="text-align:right">Média: </td>
    <? 
    $i = 0;
    foreach($dados_estado['vendedores'] as $idvendedor => $dados) { 
    ?>     
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center; border-top: 2px #999 solid;">
      <?= number_format((int) $totais['vendedores'][$idvendedor]['quantidade']/ (int) $dias_trabalhado_estado, 2, ',', '.'); ?>
    </td>
    <td align="center" bgcolor="<? if($i == 0) { echo "#F4F4F4"; } else { echo "#FFFFFF"; } ?>" style="text-align:center; border-top: 2px #999 solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;">R$</td>
        <td style="text-align:right; padding:2px;"><?php echo number_format($totais['vendedores'][$idvendedor]['valor']/ (int) $dias_trabalhado_estado, 2, ',', '.'); ?></td>
      </tr>
    </table></td>
    <? 
        $i++;
        if($i == 2) $i = 0;     
    }
    ?>    
    <td align="center" bgcolor="#FEFECB" style="text-align:center; border-top: 2px #999 solid;">
      <?= number_format((int) $totais['geral']['quantidade']/(int) $dadosArray['dias_mes'], 2, ',', '.'); ?>
    </td>
    <td align="center" bgcolor="#FEFECB" style="text-align:center; border-top: 2px #999 solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
      <tr>
        <td style="color:#CCCCCC; padding:2px;">R$</td>
        <td style="text-align:right; padding:2px;"><?php echo number_format($totais['geral']['valor']/ (int) $dadosArray['dias_mes'], 2, ',', '.'); ?></td>
      </tr>
    </table></td>       
    
  </tr>
</table>
<br />
    
<hr style="page-break-before: always;">
<? } ?>
<?php /*
<table border="1" style="width:800px">
  <tr>
    <td colspan="2" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center" style="width:50%"><div id="porcentagem_pizza_matricula" style="height: 300px"></div>
      <script>
$(function () {
    
    $('#porcentagem_pizza_matricula').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Estados x Matrículas'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '<strong>{point.name}</strong> ({point.percentage:.1f}%) <br> {point.y} Mat.'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Matrículas',
            data: [
<? foreach($dadosArray['estados'] as $idestado => $dados) { 
    echo "['".$dados['estado']."',   ".$dados['quantidade']."],"; 
} ?>
                
            ]
        }]
    });
});
      </script></td>
    <td align="center">
    
<div id="porcentagem_pizza_faturamento" style="height: 300px"></div>
      <script>
$(function () {
    
    
    $('#porcentagem_pizza_faturamento').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Estados x Faturamento'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '<strong>{point.name}</strong> ({point.percentage:.1f}%) <br> R${point.y}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Matrículas',
            data: [
<? foreach($dadosArray['estados'] as $idestado => $dados) { 
    echo "['".$dados['estado']."',   ".number_format($dados['valor'], 0, '.', '.')."],"; 
} ?>
                
            ]
        }]
    });
});
      </script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
*/?>
<table border="1" style="width:900px;">
  <tr>
    <td colspan="2" style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_estados_matriculas.jpg"/>
    </td>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_estados_faturamento.jpg"/>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">    
        <img src="/storage/relatorios_gerenciais/grafico_matriculas_metas.jpg"/><br>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;">
        <strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong>
    </td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_faturamento_metas.jpg"/><br>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;">
        <strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong>
    </td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_acumulado_matriculas.jpg"/><br>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;">
        <strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong>
    </td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_acumulado_faturamento.jpg"/><br>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;">
        <strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong>
    </td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_matriculas_relacao_meta.jpg"/><br>
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;">
        <strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong>
    </td>
  </tr>
  <tr>
    <td align="center">
        <img src="/storage/relatorios_gerenciais/grafico_faturamento_relacao_meta.jpg"/><br>
    </td>
  </tr>
</table>
<?php /*
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
    
<div id="matriculas_metas_estados" style="height: 400px"></div>
<script>
$(function () {
    $('#matriculas_metas_estados').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
        enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Comparativo Matrículas <br> <?php echo $dadosArray["mes_nome"] ." ". $dadosArray["ano"]["nome"] ?>/ META'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [
            {
                categories: [ 
                    <? foreach($dadosArray['estados'] as $idestado => $dados) { ?>
                        "<?= $dados['estado']; ?> (<?php echo (int)$dados['quantidade'].' / '.(int)$dadosArray['estados_metas'][$idestado]['quantidade'];?>)",
                    <? } ?>   
                ]
            }
        ],
        yAxis: [
            {
                min:0,
                title: {
                    text: 'Quantidade de matrículas'
                }
            }
        ],
        tooltip: {
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
            },
        series: [
            {
                name: 'Faturamento',
                data: [
                    <? foreach ($dadosArray['estados'] as $idestado => $dados) { 
                        echo (int)$dados['quantidade'].","; 
                    } ?>  
                ]
            },
            {
                name: 'Meta',
                data: [
                    <? foreach ($dadosArray['estados'] as $idestado => $dados) { 
                        echo (int)$dadosArray['estados_metas'][$idestado]['quantidade'].","; 
                    } ?>  
                ]
            }
        ]
    });
});
</script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<!-- Comparativo Quantidade Matrículas/ METAS -->

<!-- Comparativo Valor Faturamento Matrículas/ METAS -->
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
    
<div id="valor_matriculas_metas_estados" style="height: 400px"></div>
<script>
$(function () {
    $('#valor_matriculas_metas_estados').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
        enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Comparativo Faturamento <br> <?php echo $dadosArray["mes_nome"] ." ". $dadosArray["ano"]["nome"] ?>/ META'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [
            {
                categories: [ 
                    <? foreach($dadosArray['estados'] as $idestado => $dados) { ?>
                        "<?= $dados['estado']; ?> (<?php echo 'R$ '.number_format($dados['valor'],2,',','.').' / R$ '.number_format($dadosArray['estados_metas'][$idestado]['valor'],2,',','.'); ?>)",
                    <? } ?>   
                ]
            }
        ],
        yAxis: [
            {
                min:0,
                format: '{value}',
                title: {
                    text: 'Valor (R$)'
                }
            }
        ],
        tooltip: {
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>R$ {point.y:.2f}</b></td></tr>',
                footerFormat: '</table>',
            },
        series: [
            {
                name: 'Faturamento',
                data: [
                    <? foreach ($dadosArray['estados'] as $idestado => $dados) { 
                        echo number_format($dados['valor'], 2, '.', '').","; 
                    } ?>  
                ]
            },
            {
                name: 'Meta',
                data: [
                    <? foreach ($dadosArray['estados'] as $idestado => $dados) { 
                        echo number_format($dadosArray['estados_metas'][$idestado]['valor'], 2, '.', '').","; 
                    } ?>  
                ]
            }
        ]
    });
});
</script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<!-- Comparativo Valor Faturamento Matrículas/METAS -->

<!-- Comparativo acumulado Matrículas/ ano anterior -->
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
    
<div id="matriculas_metas_estados_ano_anterior" style="height: 400px"></div>
<script>
$(function () {
    $('#matriculas_metas_estados_ano_anterior').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
        enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Comparativo Acumulado de Matrículas <br> <?= $dadosArray["uteis_acumulados_trabalhados"] ?> dias trabalhados' 
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [
            {
                categories: [ 
                    <? foreach($dadosArray['acumulado_estados'] as $idestado => $dados) { ?>
                        "<?= $dados['estado']; ?> (<?php echo (int)$dados['quantidade'].' / '.(int)$dadosArray['acumulado_estados_ano_anterior'][$idestado]['quantidade'];?>)",
                    <? } ?>   
                ]
            }
        ],
        yAxis: [
            {
                min:0,
                title: {
                    text: 'Quantidade de matrículas'
                }
            }
        ],
        tooltip: {
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
            },
        series: [
            {
                name: '<?= $dadosArray["ano"]["nome"]?>',
                data: [
                    <? foreach ($dadosArray['acumulado_estados'] as $idestado => $dados) { 
                        echo (int)$dados['quantidade'].","; 
                    } ?>  
                ]
            },
            {
                name: '<?= $dadosArray["ano_anterior"]["nome"]?>',
                data: [
                    <? foreach ($dadosArray['acumulado_estados'] as $idestado => $dados) { 
                        echo (int)$dadosArray['acumulado_estados_ano_anterior'][$idestado]['quantidade'].","; 
                    } ?>  
                ]
            }
        ]
    });
});
</script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<!-- Comparativo acumulado Matrículas/ ano anterior -->

<!-- Comparativo Faturamento Matrículas/ ano anterior -->
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
    
<div id="valor_matriculas_metas_estados_ano_anterior" style="height: 400px"></div>
<script>
$(function () {
    $('#valor_matriculas_metas_estados_ano_anterior').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
        enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Comparativo Acumulado Faturamento <br> <?= $dadosArray["uteis_acumulados_trabalhados"] ?> dias trabalhados'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [
            {
                categories: [ 
                    <? foreach($dadosArray['acumulado_estados'] as $idestado => $dados) { ?>
                        "<?= $dados['estado']; ?> (<?php echo 'R$ '.number_format($dados['valor'],2,',','.').' / R$ '.number_format($dadosArray['acumulado_estados_ano_anterior'][$idestado]['valor'],2,',','.'); ?>)",
                    <? } ?>   
                ]
            }
        ],
        yAxis: [
            {
                min:0,
                format: '{value}',
                title: {
                    text: 'Valor (R$)'
                }
            }
        ],
        tooltip: {
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>R$ {point.y:.2f}</b></td></tr>',
                footerFormat: '</table>',
            },
        series: [
            {
                name: '<?= $dadosArray["ano"]["nome"]?>',
                data: [
                    <? foreach ($dadosArray['acumulado_estados'] as $idestado => $dados) { 
                        echo number_format($dados['valor'], 2, '.', '').","; 
                    } ?>  
                ]
            },
            {
                name: '<?= $dadosArray["ano_anterior"]["nome"]?>',
                data: [
                    <? foreach ($dadosArray['acumulado_estados'] as $idestado => $dados) { 
                        echo number_format($dadosArray['acumulado_estados_ano_anterior'][$idestado]['valor'], 2, '.', '').","; 
                    } ?>  
                ]
            }
        ]
    });
});
</script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<!-- Comparativo Faturamento Matrículas/ ano anterior -->

<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center">
    
<div id="porcentagem_meta" style="height: 400px"></div>
<script>
$(function () {
    $('#porcentagem_meta').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
                enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: '% Matrículas em relação a meta'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [{
            categories: ["Ideal para atingir meta (<?= (int) $totais['meta_matricula']; ?>)", 
<? foreach($dadosArray['estados'] as $idestado => $dados) { 
?>
                         "<?= $dados['estado']; ?> (<?= number_format($dados['quantidade'], 2, '.', ''); ?>)",
<? } ?>     
                         "Total (<?= (int) $totais['matricula']; ?>)"]
        }],
        yAxis: [{
                title: {
                    text: 'Porcentagem %'
                }
            }],
        tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y+'%' ;
                }
            },          
        series: [{
            name: 'Faturamento',
            colorByPoint: true,
            data: [
<? 
                  $porcentagem_meta = ($totais['meta_matricula']/$dadosArray['uteis'])*$dadosArray['uteis_trabalhados'];
                  echo number_format(((int) $porcentagem_meta/(int) $totais['meta_matricula'])*100, 2, '.', '').',';

    foreach($dadosArray['estados'] as $idestado => $dados) { 
    $metas_quantidade = ($dados['quantidade']/$dadosArray['estados_metas'][$idestado]['quantidade'])*100;
    echo number_format($metas_quantidade, 2, '.', '').","; 
} ?>    
                   <?php echo number_format(((int) $totais['matricula']/(int) $totais['meta_matricula'])*100, 2, '.', ''); ?>
                   ]
        }]
    });
});
</script>
    
    </td>
  </tr>
</table>
<hr style="page-break-before: always;">
<table border="1" style="width:800px">
  <tr>
    <td style="background-color:#F0F0F0;"><strong style="text-transform:uppercase;"><?php echo $dadosArray['mes_nome'] . '/' . $dadosArray['ano']['nome']; ?></strong></td>
  </tr>
  <tr>
    <td align="center"><div id="porcentagem_meta_faturamento" style="height: 400px"></div>
      <script>
$(function () {
    $('#porcentagem_meta_faturamento').highcharts({
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
                enabled: true,
                alpha: 3,
                beta: 18,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: '% Faturamento em relação a meta'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: [{
            categories: ["Ideal para atingir meta (R$<?= number_format($totais['meta_valor'], 0, '', '.'); ?>)", 
<? foreach($dadosArray['estados'] as $idestado => $dados) { ?>
                         "<?= $dados['estado']; ?> (R$<?= number_format($dados['valor'], 0, '', '.'); ?>)",
<? } ?>     
                         "Total (R$<?= number_format($totais['valor'], 0, '', '.'); ?>)"]
        }],
        yAxis: [{
                title: {
                    text: 'Porcentagem %'
                }
            }],
        tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y+'%' ;
                }
            },          
        series: [{
            name: 'Faturamento',
            colorByPoint: true,
            data: [
<?          
                  $porcentagem_meta_valor = ($totais['meta_valor']/$dadosArray['uteis'])*$dadosArray['uteis_trabalhados'];
                  echo number_format(((int) $porcentagem_meta_valor/(int) $totais['meta_valor'])*100, 2, '.', '').','; 
 foreach($dadosArray['estados'] as $idestado => $dados) { 
    $metas_valor = ($dados['valor']/$dadosArray['estados_metas'][$idestado]['valor'])*100;
    echo number_format($metas_valor, 0, '', '.').","; 
} ?>    
                   <?php echo number_format(((int) $totais['valor']/(int) $totais['meta_valor'])*100, 0, '', '.'); ?>
                   ]
        }]
    });
});
      </script></td>
  </tr>
</table>*/?>




<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>


<?php /*?><pre>
    <?= print_r($dadosArray); ?>
</pre><?php */?>


<script src="/assets/plugins/highcharts-4.0.3/js/highcharts.js"></script>
<script src="/assets/plugins/highcharts-4.0.3/js/highcharts-3d.js"></script>
<script src="/assets/plugins/highcharts-4.0.3/js/modules/exporting.js"></script>
</body>
</html>