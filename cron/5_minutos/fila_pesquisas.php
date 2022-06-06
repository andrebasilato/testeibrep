<?php
  $coreObj = new Core();
  
  $coreObj->sql = "select 
					p.idpesquisa, 
					p.nome,
					p.email_padrao,
					p.corpo_email, 
					pf.hash, 
					pf.idpesquisa_pessoa, 
					pf.email, 
					pf.nome as nome_pessoa
				  from 
					pesquisas p 
					inner join pesquisas_fila pf on p.idpesquisa = pf.idpesquisa
				  where 
					p.situacao in (0, 1) and 
					p.ativo = 'S' and 
					pf.ativo = 'S' and 
					pf.enviado = 'N' and
					pf.enviar_email = 'S'
				  order by
				  	pf.data_cad, pf.idpesquisa_pessoa
				  limit 
					500";
  $coreObj->limite = -1;
  $coreObj->ordem_campo = false;
  $coreObj->ordem = false;
  $fila = $coreObj->retornarLinhas();
  if ($fila[0]["email_padrao"] == "S"){ // Se o corpo do email for no formato PADRÃO--------------------
	  foreach($fila as $linha) {
		$nomePara = utf8_decode($linha["nome_pessoa"]);
		$message  = "Ol&aacute; <strong>".$nomePara."</strong>,
					<br /><br />				
					Estamos convidando-o para participar de nossa Pesquisa de Satisfa&ccedil;&atilde;o. Para participar, basta clicar no link abaixo e responder a pesquisa.
					<br /><br />
					<a href=\"http://".$GLOBALS["config"]["urlSistemaFixa"]."/pesquisa/relacionamento/pesquisas/".$linha["idpesquisa"]."/responder/".$linha["idpesquisa_pessoa"]."/".$linha["hash"]."\">Clique aqui para responder</a>
					<br /><br />";
		$emailPara = $linha["email"];
		$assunto = $linha["nome"];
		
		$nomeDe = utf8_decode($config["tituloEmpresa"]);
		$emailDe = $config["emailSistemaPesquisa"];
		
		//if($coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout")) {
		  $coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
		  $coreObj->sql = "update pesquisas_fila set enviado = 'S', data_envio = NOW() where idpesquisa_pessoa = '".$linha["idpesquisa_pessoa"]."'";
		  $coreObj->executaSql($coreObj->sql);
		//} 
	  }
  }elseif($fila[0]["email_padrao"] == "N"){// Se o corpo do email for no formato PERSONALIZADO--------------------
	  foreach($fila as $linha) {
		$nomePara = utf8_decode($linha["nome_pessoa"]);
		$message = $linha["corpo_email"];
		
		//Substituindo as variáveis de imagens pelas imagens---------
		$variavel = explode("[[I][",$message);
		if($variavel){
		  foreach($variavel as $ind => $val){
			  $id = explode("]]",$val);
			  $indice[] = $id[0];
		  }
		  unset($indice[array_search("", $indice)]);
		  foreach($indice as $ind => $val){
			  $coreObj->sql = "SELECT idpesquisa_imagem, servidor FROM pesquisas_imagens WHERE ativo = 'S' AND idpesquisa = ".$linha["idpesquisa"]." AND idpesquisa_imagem = ".intval($val)."";
			  $imagem = $coreObj->retornarLinha($coreObj->sql);
			  $message = str_ireplace("[[I][".$val."]]", "<br/><div style=\"text-align:left; text-align:center\"><img src=\"http://".$GLOBALS["config"]["urlSistemaFixa"]."/storage/pesquisas_imagens/".$imagem["servidor"]."\" border=\"0\" /></div><br/>", $message);
		  }
		 }
		 //(END)Substituindo as variáveis de imagens pelas imagens---------
		 
		//Substituir Destinatário-------
		$message = str_ireplace("[[DESTINATARIO]]","<strong>".$nomePara."</strong>",$message);
		
		//Substituir Link-------
		$message = str_ireplace("[[LINK]]","<br /><a href=\"http://".$GLOBALS["config"]["urlSistemaFixa"]."/pesquisa/relacionamento/pesquisas/".$linha["idpesquisa"]."/responder/".$linha["idpesquisa_pessoa"]."/".$linha["hash"]."\">Clique aqui para responder</a></br>",$message);
		$emailPara = $linha["email"];
		$assunto = $linha["nome"];
		
		$nomeDe = utf8_decode($config["tituloEmpresa"]);
		$emailDe = $config["emailSistemaPesquisa"];
		
		//if($coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout")) {
		  $coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
		  $coreObj->sql = "update pesquisas_fila set enviado = 'S', data_envio = NOW() where idpesquisa_pessoa = '".$linha["idpesquisa_pessoa"]."'";
		  $coreObj->executaSql($coreObj->sql);
		//} 
	  }
  }
  
  $coreObj->sql = "select 
					idpesquisa
				  from 
					pesquisas
				  where 
					ativo = 'S' and situacao <> '3'";
  $coreObj->limite = -1;
  $coreObj->ordem_campo = false;
  $coreObj->ordem = false;
  $pesquisas = $coreObj->retornarLinhas();
  foreach($pesquisas as $pesquisa) {
	  $coreObj->sql = "select count(*) as total from pesquisas_fila where idpesquisa = ".$pesquisa["idpesquisa"]." and enviado = 'N' and enviar_email = 'S' and ativo = 'S'";	
	  $verifica = $coreObj->retornarLinha($coreObj->sql);
	  if ($verifica["total"] == 0) {
		$coreObj->sql = "update pesquisas set situacao = '2' where idpesquisa = ".$pesquisa["idpesquisa"];
		$coreObj->executaSql($coreObj->sql);
	  } else {
		$coreObj->sql = "update pesquisas set situacao = '1' where idpesquisa = ".$pesquisa["idpesquisa"];
		$coreObj->executaSql($coreObj->sql);
	  }
  }
?>