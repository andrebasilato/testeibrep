<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body, td, th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
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
</style>
<style type="text/css" media="print">
body, td, th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 9px;
	color: #000;
}
.impressao {
	display:none;	
}
</style>
</head>
<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td height="80"><table border="0" cellspacing="0" cellpadding="8">
        <tr>
          <td><? if($linha["logo_servidor"]) { ?><img src="/storage/empreendimentos_logo/<?= $linha["logo_servidor"]; ?>"><? }  else { ?>            
			<a href="/<?= $url[0]; ?>" class="logo"></a><?php/*<img src="/especifico/img/logo_empresa_peq.png" width="135" height="50">*/?>
<? } ?>
          </td>
        </tr>
      </table></td>
    <td align="right" valign="top"><table border="0" cellspacing="0" cellpadding="5" class="print" id="print">
        <tr>
          <td><img src="/assets/img/print_24x24.png" width="24" height="24"></td>
          <td><a href="javascript:window.print();"><?= $idioma["imprimir"]; ?></a></td>
        </tr>
      </table>
      <? if($linha["logo_servidor"]) { ?><img src="/especifico/img/logo_empresa_peq.png"><? } ?></td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td>
      <table width="100%" border="1" cellpadding="8" cellspacing="0">
	    <tr>
          <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["dados_cliente"]; ?></strong></td>                                     
        </tr>
        <tr>
          <td align="left" width="150px;"><?= $idioma['nome_cliente']; ?></td>
          <td colspan="5"><strong><?= $linha["cliente"]; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma["email_cliente"]; ?></td>
          <td colspan="2"><strong><?= $pessoaDados["email"]; ?></strong></td>                
        </tr>
        <tr>
          <td  align="left" width="150px;"><?= $idioma['telefone_cliente']; ?></td>
          <td colspan="8"><strong><?= $pessoaDados['telefone']; ?></strong></td>
        </tr>
        <?php/*<tr>
          <td align="left" width="150px;"><?= $idioma['empreendimento_cliente']; ?></td>
          <td colspan="5"><strong><?= $linha["empreendimento"]; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma["etapa_cliente"]; ?></td>
          <td colspan="2"><strong><?= $linha["etapa"]; ?></strong></td>                
        </tr>
        <tr>
          <td align="left" width="150px;"><?= $idioma['bloco_cliente']; ?></td>
          <td colspan="5"><strong><?= $linha["bloco"]; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma["unidade_cliente"]; ?></td>
          <td colspan="2"><strong><?= $unidadeDados["nome"]; ?></strong></td>                
        </tr>*/?>        
        <tr>
          <td colspan="9" bgcolor="#F4F4F4"><strong><?= $idioma["dados_gerais"]; ?></strong></td>
        </tr>     
        <tr>
          <td align="left" width="150px;"><?= $idioma["protocolo"]; ?></td>
          <td colspan="5"><strong><?= $linha["protocolo"]; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma["data_abertura"]; ?></td>
          <td colspan="2"><strong><?= formataData($linha["data_cad"],"br",1); ?></strong></td>
        </tr>
        <tr>
          <td align="left" width="150px;"><?= $idioma["assunto"]; ?></td>
          <td colspan="5"><strong><?= $linha["assunto"]; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma["subassunto"]; ?></td>
          <td colspan="2"><strong><?= $linha["subassunto"]; ?></strong></td>
        </tr>
        <tr>
          <td align="left"><?= $idioma["situacao"]; ?></td>
          <td colspan="8" align="left"><strong><?= $linha["situacao"]; ?></strong></td>
        </tr>
        <tr>
          <td align="left"><?= $idioma["titulo"]; ?></td>
          <td colspan="8" align="left"><strong><?= $linha["nome"]; ?></strong></td>
        </tr>   
        <tr>
          <td align="left" width="150px;"><?= $idioma["atend_descricao"]; ?></td>
          <td colspan="8"><strong><?= $linha["descricao"]; ?></strong></td>                                     
        </tr>
        <tr>
          <td align="left" width="150px;"><?= $idioma['atend_interacao']; ?></td>
          <td colspan="5"><strong><? if ($ultimaInteracao['data_cad']) echo formataData($ultimaInteracao['data_cad'],'pt',1); else echo '--'; ?></strong></td>
          <td align="left" width="150px;"><?= $idioma['atend_atendente']; ?></td>
          <td colspan="2"><strong><? if ($ultimoAtendente['usuario']) echo $ultimoAtendente['usuario']; else echo '--'; ?></strong></td>
        </tr>
                            
      </table>
      <br />
      <table width="100%" border="1" cellpadding="8" cellspacing="0">
        <tr>
          <td colspan="6" bgcolor="#F4F4F4"><strong><?=$idioma["resp_label"];?></strong></td>
        </tr>
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["resp_id"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["resp_autor"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["resp_criacao"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["resp_descricao"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["publicaprivada"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["anexo"];?></strong></td>
        </tr>
<?      
     if ($respostas) {  
        foreach($respostas as $resposta) { 
?>
        <tr>
          <td><?=$resposta["idresposta"];?></td>
          <td><?php 
					if($resposta["usuario"]) { 
						echo $resposta["usuario"].' (Gestor)'; 
					} elseif($resposta["cliente"]) { 
						echo $resposta["cliente"].' (Cliente)'; 
					} elseif($resposta["usuario_imobiliaria"]) { 
						echo $resposta["usuario_imobiliaria"].' (Imobili&aacute;ria)'; 
					} elseif($resposta["corretor"]) { 
						echo $resposta["corretor"].' (Corretor)'; 
					} ?></td>
          <td><?= formataData($resposta["data_cad"], 'br', 1);?><br /><span style="color:#999;"><?=$idioma["hist_repasse_id"];?> <?= $val["idhistorico"]; ?></span></td>
          <td><?php if ($resposta['resposta']) echo nl2br($resposta['resposta']); else echo nl2br($resposta['automatica']); ?></td>
          <td><?php if ($resposta['publica'] == 'S') echo $idioma["publica"]; else echo $idioma["privada"]; ?></td>
          <td><?php if ($resposta['arquivos']) echo $idioma["sim"]; else echo $idioma["nao"]; ?></td>
        </tr>
<?       } 
	  } else {
?>
		<tr>
          <td colspan="6"><?= $idioma["sem_respostas"]; ?></td>
        </tr>
<?    }   ?>
      </table>
      <br />
      <!--VISUALIZADORES-->
      <table width="100%" border="1" cellpadding="8" cellspacing="0">
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["visu_label"];?></strong></td>
        </tr>
<?
        $tamanho = (count($visualizadores) - 1);
        if ($visualizadores) {
          foreach($visualizadores as $ind => $visualizador) {   
        ?>
            <tr <?php if ($tamanho != $ind) { ?>class="linha" <?php } ?>>
              <td class="coluna"><?php echo $visualizador['nome']; ?></td>
            </tr>
<?
          }
        } 
?>       
      </table>
      <br />
      <table width="100%" border="1" cellpadding="8" cellspacing="0">
        <tr>
          <td bgcolor="#F4F4F4" colspan="9"><strong><?= $idioma['titulo_historico']; ?></strong></td>
        </tr>
        <tr>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["hist_quem"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["hist_quando"];?></strong></td>
          <td bgcolor="#F4F4F4"><strong><?=$idioma["hist_oque"];?></strong></td> 
        </tr>          
        <tr>
          <td><?= $linha["cliente"]; ?></td>
          <td><?= formataData($linha["data_cad"],'pt',1); ?></td>
		  <td><?=$idioma['atendimento_aberto']; ?></td>
        </tr>
        <? foreach($historicos as $historico) { ?>
            <tr>
              <td><?php 
					if($historico["usuario"]) { 
						echo $historico["usuario"].' (Gestor)'; 
					} elseif($historico["cliente"]) { 
						echo $historico["cliente"].' (Cliente)'; 
					} elseif($historico["usuario_imobiliaria"]) { 
						echo $historico["usuario_imobiliaria"].' (Imobili&aacute;ria)'; 
					} elseif($historico["corretor"]) { 
						echo $historico["corretor"].' (Corretor)'; 
					} ?></td>
              <td><?= formataData($historico["data_cad"],'pt',1); ?></td>
              <td>
                    <?php
                    echo $idioma["historico_".$historico["tipo"]]; 
                    switch ($historico["tipo"]) {
							case "E":
								echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["assunto_de"].'</span> -&gt; ';
								echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["assunto_para"].'</span><br />';
								break;
							case "ES":
								if (empty($historico["subassunto_de"])) 
								    echo '<br /><br />'.$idioma['historico_vazio'].' -&gt; ';
								else	
									echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["subassunto_de"].'</span> -&gt; ';
								if (empty($historico["subassunto_para"])) 
								    echo $idioma['historico_vazio'];
								else								
									echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["subassunto_para"].'</span><br />';
								break;
							case "IP":
								echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >'.$prioridades[$config["idioma_padrao"]][$historico["de"]].'</span> -&gt; ';
								echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >'.$prioridades[$config["idioma_padrao"]][$historico["para"]].'</span><br />';
								break;
							case "IA":
								echo "<br />";
								if($historico["de"]) {
									echo "De: ".formataData($historico["de"],'pt',0)." -> ";
								}
								echo "Para: ".formataData($historico["para"],'pt',0);
								break;
							case "CU":
								echo $historico["usuario_convidado"];
							case "CI":
								if ($historico['idimobiliaria_convidada'])
									echo "Convidou imobiliária: <br />".$historico["imobiliaria_convidada"];
								else
									echo "Alterou o atendimento para: \"não encaminhar para imobiliária\" <br />";
								break;
							case "CC":
								echo "Convidou corretor: <br />".$historico["corretor_convidado"];
								break;
							case "CAO":
								echo $historico["protocolo_clone"];
								break;
							case "CAD":
								echo $historico["protocolo_clone"];
								break;
							case "S":
								echo "<br /><br />";
								if($historico["status_de"]) {
									echo '<span class="status" style="background-color:#'.$historico["cor_de"].'" >'.$historico["status_de"].'</span> -&gt; ';
								}
								echo '<span class="status" style="background-color:#'.$historico["cor_para"].'" >'.$historico["status_para"].'</span><br />';
								break;
							case "A":
								echo "<br />";
								if($historico["de"]) {
									echo "De: ".$historico["de"]." -> ";
								}
								echo "Para: ".$historico["para"];
								break;
							case "UNI":
							    if (empty($historico["unidade_de"])) 
								    echo '<br /><br />'.$idioma['historico_vazio'].' -&gt; ';
								else
								    echo '<br /><br /><span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["unidade_de"].'</span> -&gt; ';
								if (empty($historico["unidade_para"])) 
								    echo $idioma['historico_vazio'];
								else
								    echo '<span class="status" style="background-color:#EEEEEE;color:#000000" >'.$historico["unidade_para"].'</span><br />';
								break;														
						
						}
                    ?>
					
              </td>
            </tr>
        <?php } ?>
          </table>
    </td>
  </tr>
</table>
<br />
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td valign="top"><span style="color:#999999;">Gerado dia <?= date("d/m/Y "); ?> por <?= $usuario["nome"]; ?> (<?= $usuario["email"]; ?>)<br>
      Or&aacute;culo - Sistema acad&ecirc;mico. <br>
      www.alfamaoraculo.com.br
    </span></td>
    <td align="right" valign="top"><div align="right"><a href="/<?= $url[0]; ?>" class="logo"></a></div><?php/*<img src="/assets/img/logo_pequena.png" width="135" height="50" align="right">*/?></td>
  </tr>
</table>
</body>
</html>