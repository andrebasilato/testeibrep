<?
	$nome_banco['pt_br']["341"] = "Banco Itaú";
	$nome_banco['pt_br']["033"] = "Banco Santander";
		
	$bancos = array(
					  array(
							  "codigo"		 =>	"341", 
							  "posicaocodigo"		 =>	"0|3", 
							  "titulo"		 => "ITAÚ",
							  "imagem"		 => "/assets/img/boletos/logoitau.jpg",
							  "label"  		 => "BANCO ITAÚ",
							  "arquivo_boleto" => "boleto_itau.php",
							  "arquivo_imagem" => "logoitau.jpg",
							  "arquivo_extensao" => "rem",
							  "retorno"		 =>
										  array(
												"segmento" => "13|1", 
												"nossonumero" => "40|8", 
												"vencimento" =>  "nao", //BOLETO NÃO REGISTRADO NÃO VEM VENCIMENTO
												"valorboleto" => "81|13|94|2",
												"datacredito" => "145|8",
												"valorpago"   => "77|13|90|2"					
										  ),
						),
				 	array(
							  "codigo"		 =>	"033", 
							  "posicaocodigo"		 =>	"0|3", 
							  "titulo"		 => "SANTANDER",
							  "imagem"		 => "/assets/img/boletos/logosantander.jpg",
							  "label"  		 => "BANCO SANTANDER",
							  "arquivo_boleto" => "boleto_santander.php",
							  "arquivo_imagem" => "logosantander.jpg",
							  "arquivo_extensao" => "rem",
							  "retorno"		 =>
										  array(
												"segmento" => "13|1", 
												"nossonumero" => "40|12", 
												"vencimento" => "nao", //BOLETO NÃO REGISTRADO NÃO VEM VENCIMENTO
												"valorboleto" => "77|13|90|2",
												"datacredito" => "137|8",
												"valorpago"   => "77|13|90|2"					
										  ),
						),
						
	);
?>