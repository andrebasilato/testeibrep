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
	/*padding-top: 0px;
	margin-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
	*/
}

/*
table.zebra-striped {
	padding: 0;
	font-size: 9px;
	border-collapse: collapse;
}
table.zebra-striped th, table td {
	padding: 0px 8px 0px;
	line-height: 16px;
	text-align: left;
}
*/

table.tr-cabecalho tr td {
    border: none;
}

.vertical-text {
    -ms-transform: rotate(270deg); /* IE 9 */
    -moz-transform: rotate(270deg);   
    -webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
    transform: rotate(270deg);
}
	
.vertical-label{
    -webkit-transform: rotate(270deg) ;
    -moz-transform: rotate(270deg);
    -o-transform: rotate(270deg);
     transform: rotate(270deg);
     
    -webkit-transform: rotate(270deg) ;
    -moz-transform: rotate(270deg);
    -o-transform: rotate(270deg);
    -webkit-transform: rotate(270deg) translateX(100%) translateY(33%);
    -webkit-transform-origin: 100% 100%;    
}

@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}

th span {
  padding: 1px .5em;
  writing-mode: tb-rl;
  filter: flipv fliph;
  -webkit-transform:rotate(-90deg); 
  -moz-transform: rotate(-90deg); 
   transform: rotate(180deg);  
  /* white-space:nowrap; */ 
  display:block;
  height: 228px !important;
  width: 20px;
}

.linha {
    position: relative;
    z-index: 100px;
    width: 60px;
    height: 1px;
    background: black;
    -webkit-transform: rotate(36deg);  
    -moz-transform: rotate(36deg);
    transform: rotate(36deg);     
    float: left;
}

.sexo {
    position: relative;    
    padding: 5px 0 5px 7px;
}

.th {    
    padding: 5px 0 7px 29px;
}

</style>
<style type="text/css" media="print">
    @page {size:landscape;}
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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><a href="/<?= $url[0]; ?>" class="logo"></a>
      </tr>
    </table></td>
    <td style="text-align: center"><h2><strong><?= $idioma["pagina_titulo"]; ?></strong></h2></td>
    <td  align="right"><table border="0" align="right" cellpadding="3" cellspacing="0" class="impressao">
      <tr>
        <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
        <td><a href="javascript:window.print();">
          <?= $idioma["imprimir"]; ?>
        </a></td>
		
		<td>
			<a class="btn" href="#link_salvar" rel="facebox" ><?php echo $idioma['salvar_relatorio'] ?></a>
			<div id="link_salvar" style="display:none;"> 
				<div style="width:300px;">
				<form method="post" onsubmit="return validateFields(this, regras)">
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

<? if($dados) { ?>

<? foreach ($dados['disciplinas'] as $key => $value) {   		        
	$carga_horaria_total += $value['carga_horaria']; 
   } ?>

<table style="width:100%;" align="center">
  <tr>
    <td style="text-align: center" rowspan="3"><img src="/assets/img/logo_historico_parana.png">
    </td>
  </tr>
  <tr>
    <td style="text-align: left">
    	<p>
    		<?=$idioma['tabela_estado'] ?><br />	
    		<?=$idioma['tabela_secretaria'] ?>
    	</p>
    </td>
    <td>
    	<p align="right"><?= $idioma['tabela_fl']; ?><u><? echo date('m'); ?>/<? echo date('y'); ?></u><br/>
    	<?= $idioma['tabela_relatorio_final_seed']; ?><br/>
    	<?= $idioma['tabela_educacao_profissional']; ?><br/>
    	<p>
    </td>
  </tr>
</table>
<br/>
<!-- Cabeçalho -->
<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="tr-cabecalho"> 
  <col width="12%">
  <col width="44%">
  <col width="10%">
  <col width="30%">
  <tr>
    <td><?= $idioma['estabelecimento']; ?></td>
    <td><?= $dados['sindicato']; ?></td>
    <td><?= $idioma['municipio']; ?></td>
    <td><?= $dados['cidade']; ?></td>
  </tr>
  <tr>
    <td><?= $idioma['entidade_mantenedora']; ?></td>
    <td><?= $dados['mantenedora']; ?></td>
     <td><?= $idioma['nre']; ?></td>
    <td><?= $dados['nre']; ?></td>
  </tr>
  <tr>
    <td><?= $idioma['ato_oficial_estabelecimento']; ?></td>
    <td></td>
     <td><?= $idioma['ato_oficial_curso']; ?></td>
    <td></td>
  </tr>
  <tr>
    <td><?= $idioma['curso']; ?></td>
    <td><?= $dados['curso']; ?></td>
     <td><?= $idioma['total_horas_cabecalho']; ?></td>
    <td><?= $carga_horaria_total ?></td>
  </tr>
</table>
<!-- Turno -->
<table width="100%" border="1" align="center" cellpadding="2" cellspacing="2" class="tr-cabecalho" style="border-top:0">
	<col width="7.5%%">
  	<col width="5.3%%">
  	<col width="44.8%%">
  <tr>
    <td><?= $idioma['turno']; ?></td>   
    <td></td>
    <td><?= $idioma['turma'] ?> <?= $dados['turma']; ?></td>
    <td></td>
    <td><?= $idioma['epoca']; ?>
    	<? echo date_format($dados['data_inicio_matricula'], "d/m/Y"); ?> a 
    	<? echo date_format($dados['data_fim_matricula'], "d/m/Y"); ?>
    </td> 
  </tr>
</table>
<!-- Disciplinas -->
<table width="100%" border="1" align="center" style="border-top:0">
  <tr>
    <th align="center" colspan="3"><?= $idioma['disciplinas_subfuncoes']; ?></th>
    <? foreach ($dados['disciplinas'] as $key => $value) { ?>
    	<th style="width: 20px;">
    		<span>
    			<?= $value['nome'] ?> - <? echo $value['tipo'] == 'EAD' ? 'D' : 'P' ?>
    		</span>  
    	</th>
    <? } ?>
    <th style="width: 20px;">
    	<span><?= $idioma['resultado_final'] ?></span>
    </th>
    <th style="width: 20px;">
    	<span><?= $idioma['total_horas'] ?></span>
    </th>
  </tr>
  <tr height="30px" style="font-weight: bold;">
  	<td style="text-align: center;"><?= $idioma['numero']; ?></td>
  	<td style="text-align: center;"><?= $idioma['aluno']; ?></td>
  	<td style="width: 20px;"> 
  		<div class="th">TH</div>
  		<div class="linha"></div>
  		<div class="sexo">Sexo</div> 
	</td>
  	<? foreach ($dados['disciplinas'] as $key => $value) { ?>  		        
        <td style="text-align: center;"><?= $value['carga_horaria'] ?> </td>
    <? } ?>
    <td style="text-align: center;">-</td>
    <td style="text-align: center;"><?= $carga_horaria_total; ?></td>
  </tr>
  	<? 
  	     $n = 1;
  	     $media_final = 0.0;
  	?>
    <? foreach ($dados['alunos'] as $aluno) { ?>
    	<tr>
    		<td style="text-align: center;"><?= $n; ?></td>
        	<td><?= $aluno['nome'] ?></td>
        	<td style="text-align: center;"><?= $aluno['sexo'] ?></td>
            <? foreach ($aluno['situacao'] as $key => $value) { ?>
            	<? if ($key == 5) { ?>
            		<td style="text-align: center;"><?= $value['situacao']; ?></td>
            	<? } else { ?>
            		<? $media_final += $value['valor']; ?>
            		<td style="text-align: center;"><?= $value['valor'] ?></td>
            	<? } ?>
            <? } ?>
    		<td style="text-align: center;" >
    			<? if( $media_final/sizeof($dados['disciplinas']) > $dados['media'] ) {?>
    				AP
    			<? } else {  ?>
    				REP
    			<?} ?>
			</td>
    		<td style="text-align: center;"><?= $carga_horaria_total; ?></td>
        </tr>
        <?  
            $n++;
            $media_final = 0.0;
         ?>
    <? } ?>
</table>
<br>
<br>
<div class="page-break"></div>
<table width="100%" border="1" align="center" cellpadding="2" cellspacing="2">
 	<col width="40%">
  	<col width="60%">
	<tr>
		<td style="vertical-align: top;" height="300px" align="left"><?= $idioma['observacao']; ?></td>
		<td style="vertical-align: top;" height="300px" align="left"><?= $idioma['carga_horaria_total'].' - '.$carga_horaria_total.' HORAS' ; ?></td>
	</tr>
	<tr>
		<td style="vertical-align: top;" height="100px" align="left"><?= $idioma['legenda']; ?></td>
		<td style="vertical-align: top;" height="100px" align="left"><?= $idioma['sintese_avaliacao']; ?></td>
	</tr>
</table>
<table width="100%" border="1" align="center" style="border-top:0" class="tr-cabecalho">
	<col width="50%">
  	<col width="50%">
	<tr>
		<td colspan="2" height="100px" style="text-align: center;">
		<?             
            $hoje = getdate();
            $dia = $hoje["mday"];
            $ano = $hoje["year"];
            $diadasemana = $hoje["wday"];

            $mes = sprintf("%02d", $hoje["mon"]);
            $nomemes = $meses_idioma["pt_br"][$mes];           
            $nomediadasemana = $dia_semana["pt_br"][$diadasemana];
           
            echo "{$dados['cidade']}, $dia de $nomemes de $ano.";
           
        ?>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top;text-align: center;" height="90px"><?= $idioma['secretario']; ?></td>
		<td style="vertical-align: top;text-align: center;" height="90px"><?= $idioma['diretor']; ?></td>
	</tr>	
</table>
<table width="100%" align="center">
	<tr>
		<td></td>
		<td style="text-align: right;"><?= $idioma['cod_seed']; ?></td>
	</tr>
</table>
<? } else { 
    $relatorioObj->GerarTabela($dadosArray,$_GET["q"],$idioma);
 } ?>
<br>
<br>

<script>
$(function() {
    var header_height = 0;
    $('table th span').each(function() {
        if ($(this).outerWidth() > header_height) header_height = $(this).outerWidth();
    });

    $('table th').height(header_height);
});
</script>

<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;"><?= $idioma["rodape"]; ?></span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
<?php 
// usado no log do php
//cho bl_debug(_debug); 
?>
</body>
</html>