<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $idioma["nome_relatorio"]; ?></title>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<link rel="icon" href="/assets/img/favicon.ico">
<link rel="stylesheet" href="/assets/css/relatorios.css" type="text/css" media="screen" />
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td><img src="/assets/img/logo.png" width="250" height="80" /></td>
  </tr>
  <tr>
    <td>
       <h1><?= $idioma["nome_relatorio"]; ?></h1>
       <strong><?= $idioma["registros_encontrados"]; ?></strong> <?=$totalLinhas;?><br />
     </td>
   </tr>
   <tr>
    <td>                 
      <table width="100%" border="1" cellpadding="8" id="exportarExcel">
         <tr bgcolor="#DDDDDD">
            <th><?= $idioma["col_retorno"]; ?></th>
            <th><?= $idioma["col_cliente"]; ?></th>
            <th><?= $idioma["col_cpf"]; ?></th>
            <th><?= $idioma["col_data_cadastro"]; ?></th>
            <th><?= $idioma["col_vencimento"]; ?></th>
            <th><?= $idioma["col_valor_parcela"]; ?></th>            
            <th><?= $idioma["col_data_pagamento"]; ?></th>
            <th><?= $idioma["col_valor_pago"]; ?></th>
         </tr>
 <? 
 $totalParcelas = 0;
 $totalParcelasPagas = 0;
 if($totalLinhas > 0){
 	      foreach($linha as $ind => $valor){ ?>
         <tr>
            <td><?=$valor['arquivo_nome'];?></td>
            <td><?=$valor['nome'];?></td>
            <td><?=$valor['cpf'];?></td>
            <td><?=formataData($valor['datacad'], 'br', 1);?></td>
            <td><?=formataData($valor['vencimento'], 'br', 0);?></td>
            <td><?="R$ ".number_format($valor['valor'], 2, ',', '.');?></td>
            <td><? if(!is_null($valor['datapago']) && $valor['datapago'] != '0000-00-00') echo formataData($valor['datapago'], 'br', 0);?></td>
            <td><? if(!is_null($valor['valorpago']) && $valor['valorpago'] != 0) echo "R$ ".number_format($valor['valorpago'], 2, ',', '.');?></td>
         </tr>             	
<?
	      $totalParcelas += $valor['valor'];
            $totalParcelasPagas += $valor['valorpago'];
	      }
		  echo "<tr>
		  			<th colspan='5'>&nbsp;</th>
					<th style=\"background-color:#DDDDDD;\">R$ ".number_format($totalParcelas, 2, ',', '.')."</th>
		  			<th>&nbsp;</th>
					<th style=\"background-color:#DDDDDD;\">R$ ".number_format($totalParcelasPagas, 2, ',', '.')."</th>
				</tr>";
	  }else{
	     echo "<tr><th colspan='8'><strong>".$idioma["sem_registros"]."</strong></th></tr>";
	  }
		  
?>
    </table>
    </td>
  </tr>
  <tr>
    <td><strong><? printf($idioma["geracao_relatorio"], formataData(date("Y-m-d H:i:s"),"br",1), $usuario["nome"]);?></strong></td>
  </tr>  
</table>
</body>
</html>