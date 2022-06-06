<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php/*<link href="/assets/css/progress.css" rel="stylesheet" />*/?>
  <style type="text/css">
    body, td, th {
      font-family: Verdana, Geneva, sans-serif;
      font-size: 12px;
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
    .impressao {
        display:none;
    }

    @media print {
        .impressao_hidden{
            display: none;
        }
    }
  </style>
 
</head>
<body>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td height="100" width="10%">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td>
		  <?php if($boletim['aluno']->sindicato_logo) { ?>
              <img alt="<?= $config["tituloSistema"]; ?>" src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/api/get/imagens/sindicatos_logo/x/95/'.$boletim['aluno']->sindicato_logo; ?>" />
          <?php } else { ?>
              <img alt="<?= $config["tituloSistema"]; ?>" src="<?= URL_LOGO_PEGUENA; ?>" />
		  <?php } ?>
          </td>
        </tr>
      </table>
    </td>
    <td align="center">
		<h5><strong><?= $boletim['aluno']->sindicato; ?></strong></h5>
		<h5><strong><?= $boletim['aluno']->mantenedora; ?></strong></h5>
		<font><?= $boletim['aluno']->ins_logradouro.'&nbsp;'.$boletim['aluno']->ins_endereco; ?></font>
		<h3><strong><?= $idioma["boletim_titulo"]; ?></strong></h3>
	</td>
    <?php if ($controls): ?>
		<td>
			<table class="controls" align="right" border="0" cellpadding="3" cellspacing="0">
			  <tbody><tr>
				<td><img src="/assets/img/print_24x24.png" height="24" width="24" /></td>
				<td><a href="javascript:window.print();"><?= $idioma["imprimir"] ?></a></td>

				<td><img src="/assets/img/pdf.png" height="32" width="32" /></td>
				<td> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/historico_escolar_pdf" onClick="document.location.href = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/historico_escolarpdf';"><?= $idioma["PDF"] ?></a> </td>

			  </tr>
			</tbody>
		</table>
		</td>
    <?php endif ?>
  </tr>
</table>
<br />

<table width="100%" border="0" cellpadding="5" cellspacing="0" style="text-transform:uppercase;">
    <tr>
		<td colspan="2">
            <strong><?= $boletim['aluno']->nome_curso ?></strong><br />
            <?php /*?><strong><?= $boletim['aluno']->areas ?></strong><br /><?php */?>
            <strong><?= $boletim['aluno']->autorizacao ?></strong>
        </td>
	</tr>
</table>
<br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="text-transform:uppercase;">
    <tr>
		<td colspan="3" style="border-top:1px solid black; border-left:1px solid black; border-right:1px solid black; ">
			<?= $idioma['nome_aluno'] . ' ' . $boletim['aluno']->nome ?>
		</td>		
	</tr>
	<tr>
		<td style="border-left:1px solid black; " width="30%">
			<?= $idioma['data_nasc'] . '&nbsp;' . formataData($boletim['aluno']->data_nasc, 'pt', 0) ?>
		</td>
		<td>
			<?= $idioma['rg'] . '&nbsp;' . $boletim['aluno']->rg . ' ' . $boletim['aluno']->rg_orgao_emissor ?>
		</td>
		<td style="border-right:1px solid black; ">Natural de: <?= $boletim['aluno']->naturalidade; ?></td>			
	</tr>
	<?php /*?><tr>
		<td style="border-left:1px solid black; " width="30%">
			<?= $idioma['uf'] . '&nbsp;' . $boletim['aluno']->estado ?>
		</td>
		<td colspan="2" style="border-right:1px solid black; ">
			<?= $idioma['local'] . '&nbsp;' . $boletim['aluno']->cidade ?>
		</td>			
	</tr><?php */?>
	<tr>
		<td colspan="3" style="border-left:1px solid black; border-right:1px solid black; ">
			<?= $idioma['pai'] . ' ' . $boletim['aluno']->filiacao_pai ?>
		</td>		
	</tr>
	<tr>
		<td colspan="3" style="border-left:1px solid black; border-right:1px solid black; border-bottom:1px solid black; ">
			<?= $idioma['mae'] . ' ' . $boletim['aluno']->filiacao_mae ?>
		</td>		
	</tr>
	<tr>
		<td colspan="3" style="border-left:1px solid black; border-right:1px solid black; ">
			<?= $idioma['sindicato_em'] . ' ' . $boletim['aluno']->curso_anterior_sindicato ?>
		</td>		
	</tr>
	<tr>
		<td style="border-left:1px solid black; " width="30%">
			<?= $idioma['cidade_em'] . ' ' . $boletim['aluno']->curso_anterior_cidade ?>
		</td>
		<td colspan="2" style="border-right:1px solid black; ">
			<?= $idioma['uf_em'] . ' ' . $boletim['aluno']->curso_anterior_estado ?>
		</td>			
	</tr>
	<tr>
		<td colspan="3" style="border-left:1px solid black; border-right:1px solid black; border-bottom:1px solid black; ">
			<?= $idioma['ano_em'] . ' ' . $boletim['aluno']->curso_anterior_ano_conclusao ?>
		</td>		
	</tr>
</table><br />

<table width="100%" border="1" cellpadding="4" cellspacing="0">
    <tr>
        <td bgcolor="#F4F4F4" width="70%"><strong><?= $idioma["dados_disciplina"]; ?></strong></td>
		<td bgcolor="#F4F4F4" align="center"><strong><?= $idioma["dados_carga"]; ?></strong></td>
		<td bgcolor="#F4F4F4" align="center"><strong><?= $idioma["dados_media"]; ?></strong></td>
    </tr>

    <?php
        foreach ($boletim['aluno_disciplinas'] as $disciplina) {
			if ($disciplina['ignorar_historico'] == 'S') {
				continue;
			}
			unset($notas);
		?>
		    <tr>
                <td>
					<?= $disciplina['nome']; ?> 
					<?php 
					if($boletim['aluno']->porcentagem_ava > 0) {
						if($boletim['aluno']->porcentagem_manual) {
							$disciplina['porcentagem_aluno_ava']["porc_aluno"] = $boletim['aluno']->porcentagem_manual;
						}
					
						if($disciplina['porcentagem_aluno_ava']['porc_aluno'] < $boletim['aluno']->porcentagem_ava) {
							$materia_reprovada = true;
					?>
						<font style="color:red"> (não atingiu a porcentagem mínima do AVA)</font>
					<?php } 
					}
					?>
				</td>
                <?php 
				$aproveitamento_estudo = boletim::getAproveitamentoEstudos($url[3], $disciplina['iddisciplina']); 
				
				if (!$aproveitamento_estudo['idmatricula_nota']) {
					
					$notas_disciplina = boletim::getProvasTipos($url[3], $disciplina['iddisciplina']); 
					foreach ($notas_disciplina as $nota) { 
						$notas[$nota['idtipo']] = number_format($nota['nota'], 2, ',', '.');
					}	
					unset($formResult);
					$formula->post = null;
					$formResult = $formula->set('id', $disciplina['idformula'])
						->set('post', $notas)
						->validarFormula($boletim['aluno']->media_curriculo);
					
				}
				?>
				
				<td align="center"><?= $disciplina['horas']; $carga_total += $disciplina['horas']; ?></td>
				<td align="center">
					<?php 
					if ($aproveitamento_estudo['idmatricula_nota']) { #print_r2($aproveitamento_estudo, true);						
							echo 'AE - Aproveitamento de Estudos';	
							$disciplina['contabilizar_media'] = 'N';
					} else if ($disciplina['exibir_aptidao'] == 'S') {
						if ($formResult['valor'] == '10.00' || $formResult['valor'] == '10') {
							echo 'APTO';
						} else {
							$materia_reprovada = true;
							echo 'INAPTO';
							$formResult['valor'] = 0;
						}
					} else {
						if ($disciplina['nota_conceito'] == 'S') {
							echo notaConceito($formResult['valor']);
						} else {
							echo number_format($formResult['valor'], 2, ',', '.');
						}
					}
					if ($disciplina['contabilizar_media'] == 'S') {
						$media_total += $formResult['valor']; 
						$disciplinas++; 
					}
					
					if (!$aproveitamento_estudo['idmatricula_nota'] && $formResult['valor'] < $boletim['aluno']->media_curriculo) {
						$materia_reprovada = true;
                    }
					?>
				</td>
            </tr>    
	<?php 
	} ?>

	<tr>
		<td colspan="3" align="center">
			<strong>Total: </strong><?= $carga_total; ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<?php 
			if($media_total && $disciplinas) {
				$media_final = ($media_total/$disciplinas);
			}
			?>
			<strong>Resultado final: </strong>

			<?php  
			if ($boletim['aluno']->dias_minimo) {
				$data_atual = date('Y-m-d');
				$data_minima = new DateTime(formataData($boletim['aluno']->data_matricula, 'en', 0));
				$data_minima->modify('+' . $boletim['aluno']->dias_minimo . ' days'); 
				if($data_minima->format('Y-m-d') < $data_atual) {
					$pode_aprovar_curriculo = true;
				}
			} else {
				$pode_aprovar_curriculo = true;
			}
			?>
			
			<?php if($media_final >= $boletim['aluno']->media_curriculo && $pode_aprovar_curriculo && !$materia_reprovada) { ?>
				APROVADO
			<?php } else { ?>
				REPROVADO
			<?php } ?>
		</td>
	</tr>

</table>
<div style="margin:10px;">
	<strong>Observações:</strong>
</div>
<div style="margin:5px;">
	<?php if($mensagens) { echo nl2br($mensagens); } else { echo '--'; } ?>
</div>
<div style="margin:5px;">
	<?= nl2br($boletim['aluno']->perfil); ?>
</div>

<?php
if($_POST['data_historico']) {
	$array_data = explode('/', $_POST['data_historico']);
	$dia = $array_data[0];
	$mes = $array_data[1];
	$ano = $array_data[2];
} else {
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');
}
?>

<div style="margin-top:10px; text-align:center;">
		<?= ($boletim['aluno']->cidade_ins) ? $boletim['aluno']->cidade_ins.', ' : ''; ?> <?= $dia ?> 
		de <?= $GLOBALS["meses_idioma"][$GLOBALS["config"]["idioma_padrao"]][$mes]; ?> de <?= $ano ?>
</div>
<div style="margin-top:30px;">
	<div style="float:left; width:50%; text-align:center;">
			Secretário(a)<br />
			<?= $boletim['aluno']->secretario ?><br />
			<?= $boletim['aluno']->secretario_portaria ?>
	</div>
	<div style="text-align:center;">
			Diretor(a)<br />
			<?= $boletim['aluno']->diretor ?><br />
			<?= $boletim['aluno']->diretor_portaria ?>
	</div>
</div>
</body>
</html>