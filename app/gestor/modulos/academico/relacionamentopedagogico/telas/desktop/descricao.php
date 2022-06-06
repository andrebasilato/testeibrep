<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<style type="text/css">
body {
	padding-top: 0px;
}
.quebra_pagina {
	page-break-after:always;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
  <table width="100%" height="50%" border="0" align="left" cellpadding="2" cellspacing="0">
  <tr>
    <td><table width="99%" border="0" cellspacing="0" cellpadding="5" align="left">
        <tr>
          <td><img src="/assets/img/logo_pequena.png" width="135" height="50" /></td>
          <td><strong>Mensagem da cobrança:</td>
        </tr>
        <tr>
        	<td colspan="2">
        		<?= $mensagem[0]['mensagem']; ?>
        	</td>
        </tr>
      </table>
    </tr>
</table>
</body>
</html>