<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
</head>
<body>
<form action="<?= $informacoes["url"]; ?><? if($informacoes["ancora"]) { echo "#".$informacoes["ancora"]; } ?>" method="post" name="formProcessa" id="formProcessa">
	<?php
	foreach ($_POST as $ind => $var) {
		if (is_array($var)) {
			foreach ($var as $ind2 => $var2) {
				?>
				<input id="<?= $ind; ?>" name="<?= $ind; ?>[]" type="hidden" value="<?= $var2; ?>">
				<?php
			}
		} else {
			?>
			<input id="<?= $ind; ?>" name="<?= $ind; ?>" type="hidden" value="<?= $var; ?>">
			<?php
		}
	}
	?>
</form>
<script>
	document.getElementById("formProcessa").submit();
</script>
</body>
</html>
