<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alfama Oráculo</title>
<link rel="icon" href="/assets/img/favicon.ico">
<link href="/assets/css/oraculo.css" rel="stylesheet">
</head>
<body>
<div class="topbar">
  <div class="topbar-inner">
    <div class="container-fluid"> <a class="brand" href="/<?= $url[0]; ?>"><span style="color:#999">ALFAMA</span>ORÁCULO</a>
      <p class="pull-right"><?= $idioma["usuariologado"]; ?> <a href="/configuracoes/meuperfil"><?= $usuario["email"]; ?></a></p>
    </div>
  </div>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="20" style="height:100%; text-align:center">
  <tr>
    <td align="center">
    
<p><img src="/assets/img/logo.png" width="250" height="80" /> </p>
<p>Ocorreu algum erro ao tentar processar sua solicitação.<br />
  Por favor, entre em contato com o administrador do sistema.<br />
  <a href="mailto:tomaz@alfamaweb.com.br">tomaz@alfamaweb.com.br </a><br />
  <br />
</p> 

<? //print_r($_SESSION); ?>   
    
    </td>
  </tr>
</table>
</body>
</html>
