<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $config["tituloEmpresa"]; ?> - <?= $config["tituloSistema"]; ?> - <?= $config["tituloPainel"]; ?></title>
<link rel="icon" href="/assets/img/favicon.ico">

</head>
<body>

<?	
    (strpos($informacoes["url"],'?'))?
		$url_action = $informacoes["url"]."&tk=".date("ymdhis")."&msg=".$informacoes["msg"] : 
		$url_action = $informacoes["url"]."?tk=".date("ymdhis")."&msg=".$informacoes["msg"];	
?>
<form action="<?= $url_action; ?><? if($informacoes["ancora"]) { echo "#".$informacoes["ancora"]; } ?>" method="post" name="formProcessa" id="formProcessa" >
  <input name="msg" type="hidden" value="<?= $informacoes["msg"]; ?>">
</form>
<script>
	document.getElementById("formProcessa").submit();
</script>
</body>
</html>
