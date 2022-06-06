<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">
<!--
<link href="/assets/css/oraculo.css" rel="stylesheet">
-->
</head>
<body>


<!--
<p><img src="/assets/img/logo.png" width="250" height="80" /> </p>
<p>O sistema está procesando sua informação.<br />
  <br />
  <img src="/assets/img/carregando.gif" width="64" height="64" /><br />
  <br />
</p>
-->
<?
	(strpos($informacoes["url"],'?'))?
		$url_action = $informacoes["url"]."&tk=".date("ymdhis") : 
		$url_action = $informacoes["url"]."?tk=".date("ymdhis");	
?>
<form action="<?= $url_action; ?><? if($informacoes["ancora"]) { echo "#".$informacoes["ancora"]; } ?>" method="post" name="formProcessa" id="formProcessa" >
  <input name="msg" type="hidden" value="<?= $informacoes["msg"]; ?>">
  <!-- <input type="submit" name="button" id="button" value="Continuar" /> -->
</form>
<script>
	document.getElementById("formProcessa").submit();
</script>
</body>
</html>
