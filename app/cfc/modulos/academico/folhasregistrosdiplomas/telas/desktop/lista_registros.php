<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
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

@media print {
	.impressao_hidden{
		display: none;
	}
}
.impressao {
	display:none;
}
.linha {
	border-bottom:1px solid black;
	border-left:1px solid black;
	border-right:1px solid black;
}
.quebra_pagina {
	page-break-after:always;
}
</style>
<link href="/assets/css/progress.css" rel="stylesheet">
</head>
<body>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
    <tr>
        <td height="80" width="10%">
            <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td><?php /* <a href="/<?= $url[0]; ?>" class="logo"></a> */ ?><img alt="<?= $config["tituloSistema"]; ?>" src="<?php echo URL_LOGO_PEGUENA; ?>" /></td>
                </tr>
            </table>
        </td>
        <td align="center">
            <h4><strong><?= $linha["nome_abreviado"]; ?></strong></h4>
            <h4><strong><?= $linha["curso"]; ?></strong></h4>
            <font><?= $linha["ins_endereco"]; ?></font>
            <h3><strong><?= $idioma["boletim_titulo"]; ?> - <?= $linha["numero_livro"]; ?></strong></h3>
        </td>
		<?php if(count($linha)) { ?>
			<?php if($url[4] != 'abrir_folhas_pdf') { ?>
                <td class="impressao_hidden" >
                    <table align="right" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                            <td><img src="/assets/img/print_24x24.png" height="24" width="24"></td>
                            <td><a href="javascript:window.print();"><?= $idioma["imprimir"] ?></a></td>
                            <td><img src="/assets/img/pdf.png" height="32" width="32"></td>
                            <td><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/abrir_folhas_pdf" onClick="document.location.href = '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/abrir_folhas_pdf';"><?= $idioma["pdf"] ?></a> </td>
                        </tr>
                    </table>
                </td>
            <?php } ?>
        <?php } ?>
    </tr>
</table>
<br />
<?php
$ordem = $_POST['numero_ordem'];
$registro = $_POST['numero_registro'];
if (!count($linha)) {
	?>
    <form method="post">
        <table width="100%" border="1" cellpadding="8" cellspacing="0">
            <tr>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["numero_ordem"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="20%"><strong><?php echo $idioma["matricula"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="20%"><strong><?php echo $idioma["nome"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["rg"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["numero_registro"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["numero_relacao"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["data_expedicao"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["estado"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["data_retirada"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["assinatura"]; ?></strong></td>
            </tr>
			<?php
            foreach($matriculas as $matricula) {
				$ind_mat++; ?>
                <tr>
                    <td><?php echo $ordem; ?></td>
                    <td><?php echo $matricula['idmatricula']; ?></td>
                    <td><?php echo $matricula['nome']; ?></td>
                    <td><?php echo $matricula['rg']; ?></td>
                    <td><?php echo $registro; ?></td>
                    <td><?php echo $_POST['numero_relacao']; ?></td>
                    <td><?php echo $_POST['data_expedicao']; ?></td>
                    <td><?php echo $sindicato['estado']; ?></td>
                    <td>&nbsp;</td>
                    <td>
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][ordem]" value="<?= $ordem ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][idmatricula]" value="<?= $matricula['idmatricula'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][nome]" value="<?= $matricula['nome'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][rg]" value="<?= $matricula['rg'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][registro]" value="<?= $registro ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][numero_relacao]" value="<?= $_POST['numero_relacao'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][data_expedicao]" value="<?= $_POST['data_expedicao'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][estado]" value="<?= $sindicato['estado'] ?>" />
                        <input type="hidden" name="matriculas[<?= $ind_mat; ?>][idmatricula]" value="<?= $matricula['idmatricula'] ?>" />
                    </td>
                </tr>
				<?php
                $ordem++;
                $registro++;
			}
			?>
        </table>
        <input type="hidden" name="acao" value="salvar_folha" />
        <input type="hidden" name="idoferta" value="<?= $_POST['idoferta'] ?>" />
        <input type="hidden" name="idcurso" value="<?= $_POST['idcurso'] ?>" />
        <input type="hidden" name="idsindicato" value="<?= $_POST['idsindicato'] ?>" />
        <input type="hidden" name="numero_ordem" value="<?= $_POST['numero_ordem'] ?>" />
        <input type="hidden" name="numero_registro" value="<?= $_POST['numero_registro'] ?>" />
        <input type="hidden" name="numero_relacao" value="<?= $_POST['numero_relacao'] ?>" />
        <input type="hidden" name="data_expedicao" value="<?= $_POST['data_expedicao'] ?>" />
        <div style="padding-top:15px; padding-right:15px; text-align:right">
            <input type="submit" name="salvar" value="Salvar folha" class="btn" />
        </div>
    </form>
<?php
} else {
    $pessoas = new Pessoas;
    $quebra_pagina_numero_linha = 25;
    foreach($matriculas as $matricula) {
        if ($matricula['cancelado'] == 'S') {
			continue 1;
        }

		$num_linha++;
		if($num_linha == 1) {
		?>
		<table width="100%" border="1" cellpadding="8" cellspacing="0">
			<tr>
				<td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["numero_ordem"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?php echo $idioma["matricula"]; ?></strong></td>
				<td bgcolor="#F4F4F4" width="20%"><strong><?= $idioma["nome"]; ?></strong></td>
				<td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["rg"] ?></strong></td>
				<td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["numero_registro"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["numero_relacao"]; ?></strong></td>
                <td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["data_expedicao"]; ?></strong></td>
				<?php /*?><td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["estado"]; ?></strong></td><?php */?>
				<td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["data_retirada"]; ?></strong></td>
				<td bgcolor="#F4F4F4" width="10%"><strong><?= $idioma["assinatura"]; ?></strong></td>
			</tr>
		<?php
		}

        $pessoa = $pessoas->set('id', $matricula['idpessoa'])->retornar();
        $pessoa['nome'];

        unset($info);
        $info = $linhaObj->getMatriculationByIdFolha($matricula['idfolha'], $matricula['idmatricula']);
        ?>
        <tr>
            <td class="linha" width="10%"><?php echo $info['numero_ordem']; ?></td>
            <td class="linha" width="10%"><?php echo $matricula['idmatricula']; ?></td>
            <td class="linha" width="20%"><?php echo $matricula['nome_aluno']; ?></td>
            <td class="linha" width="10%"><?php echo ($matricula['rg']) ? $matricula['rg'] : '--'; ?></td>
            <td class="linha" width="10%"><?php echo $info['numero_registro']; ?></td>
            <td class="linha" width="10%"><?php echo $matricula['numero_relacao']; ?></td>
            <td class="linha" width="10%"><?php echo formataData($linha['data_expedicao'], 'pt', 0); ?></td>
            <?php /*?><td class="linha" width="10%"></td><?php */?>
            <td class="linha" width="10%"></td>
            <td class="linha" width="10%"></td>
        </tr>
        <?php if($num_linha == $quebra_pagina_numero_linha) { ?>
            </table>
			<?php
			$num_linha = 0;
		}
	}
    if($num_linha != 0) { ?>
        </table>
	<?php
    }
} ?>
</body>
</html>
