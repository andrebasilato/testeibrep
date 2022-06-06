<?php
/*
Enviando e-mails para o administrador do sistema na Alfama
*/

$message = '
<html>
<head>
<title>OPS!</title>
</head>
<body>
<table style="background-color: #DDDDDD; border: 1px solid #DDDDDD;" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td colspan="4" style="background-color: #DDDDDD;" valign="middle" align="center" bgcolor="#DDDDDD" height="45">&nbsp;</td>
	</tr>
    <tr>
	  <td style="background-color: #FFFFFF; padding: 0pt 0pt 2px;" align="center">
		<table cellpadding="8" cellspacing="1">
		  <tr>
			<td><img src="'.$config["url"].'/assets/img/logo_empresa.png" border="0" /></td>
			<td>
			  <span style="color:#3477B6;font-family:Calibri,Verdana,Geneva,sans-serif;text-transform:uppercase;font-size:30px;font-weight:bold">OPS!</span>
			  <br>
			  <span style="color:#BFBFBF;font-family:Calibri,Verdana,Geneva,sans-serif;text-transform:uppercase;font-size:12px;">O ORÁCULO [ALFAMA WEB] TRABALHOU DE FORMA INESPERADA!</span>
			</td>
		  </tr>
		</table>
	  </td>
	</tr>
    <tr>
	  <td colspan="4" align="center" bgcolor="#FFFFFF">
		<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td colspan="2" height="30">
			  <table border="0" align="left" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
				  <td style="color:#BFBFBF;font-family:Calibri,Verdana,Geneva,sans-serif;font-size:12px;">Segue abaixo as informa&ccedil;&otilde;es do erro.</td>
				</tr>
			  </table>
			</td>
		  </tr>
		  <tr valign="top" align="left">
			<td>
			  <table border="0" align="center" cellpadding="8" cellspacing="0">
				<tr>
				  <td align="left" bgcolor="#FFFFFF">
					<table cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
					  <tr>
						<td colspan="2" bgcolor="#E4E4E4" align="center">INFOMAÇÕES</td>
					  </tr>
					  <tr>
						<td bgcolor="#F4F4F4">VARIÁVEL</td>
						<td bgcolor="#F4F4F4">VALOR</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>DATA</strong></td>
						<td bgcolor="#FFFFFF">'.date("d/m/Y H:i:s").'</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>ERRO MYSQL</strong></td>
						<td bgcolor="#FFFFFF">'.$informacoes["mysql_error"].'</td>
					  </tr>  
					  <tr>
						<td bgcolor="#FFFFFF"><strong>SQL</strong></td>
						<td bgcolor="#FFFFFF">'.$informacoes["sql"].'</td>
					  </tr> 
					  <tr>
						<td bgcolor="#FFFFFF"><strong>caminho</strong></td>
						<td bgcolor="#FFFFFF">'.dirname(__DIR__).'</td>
					  </tr>  
					</table>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		</table>
	  </td>
    </tr>
    <tr>
	  <td colspan="4" style="background-color: #DDDDDD;" valign="middle" align="center" bgcolor="#DDDDDD" height="45">&nbsp;</td>
    </tr>
</table>
</body>
</html>
';

$nomeDe = 'ORÁCULO ['.$config["tituloEmpresa"].']';
$emailDe = 'oraculo@alfamaweb.com.br';

$nomePara1 = 'TIME SISTEMAS';
$emailPara1 = 'timesistemas@alfamaweb.com.br';
 
$nomePara2 = 'TIME QUALIDADE';
$emailPara2 = 'qualidade@alfamaweb.com.br';

$subject = 'OPS! O ORÁCULO ['.$config["tituloEmpresa"].'] TRABALHOU DE FORMA INESPERADA! - '.date("H:i d/m/Y");

$coreObj = new Core;
$coreObj->Set('naoSalvarLogEmail', true);
$coreObj->enviarEmail($nomeDe, $emailDe, $subject, $message, $nomePara1, $emailPara1);
$coreObj->enviarEmail($nomeDe, $emailDe, $subject, $message, $nomePara2, $emailPara2);
	
@mysql_query("rollback");