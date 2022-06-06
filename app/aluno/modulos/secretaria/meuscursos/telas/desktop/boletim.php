<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head", $config, $usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        body, td, th {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 11px;
            color: #000;
        }

        body {
            background-color: #FFF;
            background-image: none;
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
            display: none;
        }

        @media print {
            .impressao_hidden {
                display: none;
            }
        }
    </style>
    <link href="/assets/css/progress.css" rel="stylesheet">
</head>
<body>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
    <tr>
        <td height="80" width="15%">
            <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td><img alt="<?= $config["tituloSistema"]; ?>" src="<?php echo URL_LOGO_PEGUENA; ?>" /></td>
                </tr>
            </table>
        </td>
        <td align="center" width="65%">
            <h4><strong><?= $boletim['aluno']->sindicato; ?></strong></h4>
            <h4><strong><?= $boletim['aluno']->mantenedora; ?></strong></h4>
            <font><?= $boletim['aluno']->ins_endereco; ?></font>

            <h3><strong><?= $idioma["boletim_titulo"]; ?></strong></h3>
        </td>
        <td>
            <table class="controls" align="right" border="0" cellpadding="3" cellspacing="0">
                <tbody>
                <tr>
                    <td>Emissão:</td>
                    <td><?php echo $data->format('d/m/Y'); ?></td>
                </tr>
                <tr>
                    <td>Hora:</td>
                    <td><?php echo $data->format('H:i:s'); ?></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<br/>
<?php // print_r2($boletim, true); ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td colspan="2" style="border-top:1px solid black; border-left:1px solid black; border-right:1px solid black; ">
            <?= $idioma['nome_aluno'] . ' ' . strtoupper($boletim['aluno']->nome) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="border-left:1px solid black; ">
            <?= $idioma['curso'] . ' ' . mb_strtoupper($boletim['aluno']->nome_curso, 'UTF-8'); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="border-left:1px solid black; border-right:1px solid black; border-bottom:1px solid black; ">
            <?= $idioma['codigo'] . ' ' . strtoupper($boletim['idmatricula']) . str_repeat('&nbsp;', 10); ?>
            <?= $idioma['turma'] . ' ' . strtoupper($boletim['turma']['nome']) . str_repeat('&nbsp;', 10);  ?>
            <?= $idioma['ano'] . ' ' . date('Y') ?>
        </td>
    </tr>
</table>
<br/>

<table width="100%" border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td bgcolor="#F4F4F4" width="70%"><strong><?= $idioma["dados_disciplina"]; ?></strong></td>
        <td bgcolor="#F4F4F4"><strong><?= $idioma["dados_media"]; ?></strong></td>
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
               if ($boletim['aluno']->porcentagem_ava) {
                    if ($disciplina['porcentagem_aluno_ava']['porc_aluno'] < $boletim['aluno']->porcentagem_ava) {
                        $materia_reprovada = true;
                        ?>
                        <font style="color:red"> (não atingiu a porcentagem mínima do AVA)</font>
                    <?php
                    }
                }
                ?>
            </td>
            <?php
			
			$aproveitamento_estudo = boletim::getAproveitamentoEstudos($url[3], $disciplina['iddisciplina']); 
				
				if (!$aproveitamento_estudo['idmatricula_nota']) {
			
					$notas_disciplina = Boletim::getProvasTipos($url[3], $disciplina['iddisciplina']);
					foreach ($notas_disciplina as $nota) {
						$notas[$nota['idtipo']] = number_format($nota['nota'], 2, ',', '.');
						#$notas[$tipo_class[$tipo_peso[$nota['tipo_avaliacao']]]] = $disciplina[$tipo_peso[$nota['tipo_avaliacao']]];
					}
					#print_r2($notas);
					$formula->post = null;
					$formResult = $formula->set('id', $disciplina['idformula'])
						->set('post', $notas)
						->validarFormula($boletim['aluno']->media_curriculo);
					#print_r2($formResult);
				
				}
            ?>

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

                if ($formResult['valor'] < $boletim['aluno']->media_curriculo)
                    $materia_reprovada = true;
                ?>
            </td>
        </tr>
    <?php
    } ?>
    <tr>
        <td colspan="2"><p style="text-align: center;"><strong>Sucesso Profissional! <br />É o nosso desejo para você</strong></p></td>
    </tr>
</table>