<?php
$usuario = $GLOBALS["usuario"]; 
include("idiomas/".$config["idioma_padrao"]."/index.php");
/*
Enviando e-mails para o administrador do sistema na Alfama
*/
$sessao = "";
foreach($informacoes["session"] as $ind => $val){
  if(is_array($val)) {
	foreach($val as $ind2 => $val2){
	  $sessao .= " $ind ==> $ind2 => $val2 <br>";	
	}
  } elseif($ind != "adm_senha")  {
	$sessao .= " $ind => ".strip_tags($val)."  <br>";
  }
}

$post = "";
foreach($informacoes["post"] as $ind => $val){
  $post .= " $ind => ".strip_tags($val)."  <br>";	
}

$get = "";
foreach($informacoes["get"] as $ind => $val){
  $get .= " $ind => ".strip_tags($val)."  <br>";	
}

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
			<td><img src="'.$_SERVER["SERVER_NAME"].'/assets/img/logo_empresa.png" border="0" /></td>
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
						<td bgcolor="#FFFFFF"><strong>HTTP_REFERER</strong></td>
						<td bgcolor="#FFFFFF">'.$_SERVER['HTTP_REFERER'].'</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>SCRIPT_NAME</strong></td>
						<td bgcolor="#FFFFFF">'.$_SERVER['SCRIPT_NAME'].'</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>REQUEST_URI</strong></td>
						<td bgcolor="#FFFFFF">'.$_SERVER['REQUEST_URI'].'</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>SQL</strong></td>
						<td bgcolor="#FFFFFF">'.$informacoes["sql"].'</td>
					  </tr> 
					  <tr>
						<td bgcolor="#FFFFFF"><strong>SCRIPT_FILENAME</strong></td>
						<td bgcolor="#FFFFFF">'.$_SERVER['SCRIPT_FILENAME'].'</td>
					  </tr>
					  <tr>
						<td bgcolor="#FFFFFF"><strong>SESSION</strong></td>
						<td bgcolor="#FFFFFF">'.$sessao.'</td>
					  </tr>  
					  <tr>
						<td bgcolor="#FFFFFF"><strong>POST</strong></td>
						<td bgcolor="#FFFFFF">'.$post.'</td>
					  </tr> 
					  <tr>
						<td bgcolor="#FFFFFF"><strong>GET</strong></td>
						<td bgcolor="#FFFFFF">'.$get.'</td>
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
$coreObj->enviarEmail($nomeDe, $emailDe, utf8_decode($subject), $message, $nomePara1, $emailPara1);
$coreObj->enviarEmail($nomeDe, $emailDe, utf8_decode($subject), $message, $nomePara2, $emailPara2);
	
@mysql_query("rollback");		
		
include("telas/".$config["tela_padrao"]."/index.php");
