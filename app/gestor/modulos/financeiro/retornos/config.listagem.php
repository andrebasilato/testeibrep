<?php

$config["listagem"] = array(
					  		array("id" => "idretorno",
							  	  "variavel_lang" => "tabela_idretorno", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "re.idretorno", 
							  	  "valor" => "idretorno", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 100),
								  
					  		array("id" => "nome",
							  	  "variavel_lang" => "tabela_usuario", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "ua.nome", 
							  	  "valor" => "nome", 
								  "tamanho" => 250,
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2
								  ),
								  
							array("id" => "banco", // id do atributo
								  "variavel_lang" => "tabela_banco", //variável de idioma
								  "coluna_sql" => "re.banco",//referência do tipo do campo, banco=valor retorna do BD, php=código php que retorna o valor
								  "tipo" => "php",
								  "valor" => 'return $GLOBALS["nome_banco"]["pt_br"][$linha["banco"]];',
								  "busca" => true,
								  "busca_tipo" => "select", //tipo do input da busca
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "nome_banco", //array que vai popular o select da busca
								  "busca_metodo" => 1,//1=utiliza o igual(=), 2=utiliza o like, 3=utiliza o date_format
								  "tamanho" => "200"), // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php	
							
							array("id" => "arquivo",
							  	  "variavel_lang" => "tabela_retorno", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "re.arquivo_nome", 
							  	  "valor" => "arquivo_nome", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2,
								  "tamanho" => 100),
								  
					  	  array("id" => "datacad",
							  	  "variavel_lang" => "tabela_datacad", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "re.datacad", 
							  	  "valor" => "datacad", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3,
								  "tamanho" => 100),
								  
					  		array("id" => "parcelas",
							  	  "variavel_lang" => "tabela_parcelas", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "re.quantidade_processado", 
							  	  "valor" => '
								  if($linha["processado"]=="S"){
								  return "<a target=\"_blank\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idretorno"]."/pagamentos\">".$linha["parcelas"]." (Visualizar)</a>";
								  }else{
									  return "<a class=\"btn dropdown-toggle btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idretorno"]."/processar?"."\"> Processar </a>";
								  }',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 100),
								  
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => '
								   if($linha["processado"]=="N"){
									  return "
									  <a data-original-title=\"".$idioma["tabela_download_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idretorno"]."/download/arquivo/\" data-placement=\"left\" rel=\"tooltip\" ><img src=\"/assets/img/download_img_16x16.png\"></a>
									   									  ";
								     }else{
									  return "<a data-original-title=\"".$idioma["tabela_download_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idretorno"]."/download/arquivo/\" data-placement=\"left\" rel=\"tooltip\" ><img src=\"/assets/img/download_img_16x16.png\"></a>";
								   }
								   
								   ',
								  "busca_botao" => true,
								  "tamanho" => 100)
						   );

$config["ficha"] = array(
							  array("fieldsetid" => "dadosdoretorno",
									"legendaidioma" => "legendadadosretornos",
									"campos" => array(
													  array(
															"id" => "arquivo_nome",
															"nomeidioma" => "form_nome_retorno",
															"valor" => "arquivo_nome"
															),
													  array(
															"id" => "nome",
															"nomeidioma" => "form_cliente",
															"valor" => "nome"
															),
													  array(
															"id" => "form_cpf",
															"nomeidioma" => "form_cpf",
															"valor" => "cpf"
															),
													  array(
															"id" => "datacad",
															"nomeidioma" => "form_data", 
															"valor" => "datacad",
															"valor_php" => 'if($linha["datacad"]) return formataData("%s", "br", 0)'
															),
													  array(
															"id" => "vencimento",
															"nomeidioma" => "form_vencimento", 
															"valor" => "vencimento",
															"valor_php" => 'if($dados["vencimento"]) return formataData("%s", "br", 0)'
															),

													  array(
															"id" => "valor",
															"nomeidioma" => "form_valor",
															"valor" => "valor",
															"valor_php" => 'return str_replace(".",",",$dados["valor"])'
															
															),
													  array(
															"id" => "datapago",
															"nomeidioma" => "form_datapago", 
															"valor" => "datapago",
															"valor_php" => 'if($dados["datapago"]) return formataData("%s", "br", 0)'
															),
													  array(
															"id" => "valorpago",
															"nomeidioma" => "form_valorpago",
															"valor" => "valorpago",
															"valor_php" => 'return str_replace(".",",",$dados["valorpago"])'
															),
													  array(
															"id" => "situacao",
															"nomeidioma" => "form_situacao",
															"valor" => "situacao",
															"valor_php" => 'if($dados["situacao"] == 1)return "Pago com valor acima."; elseif($dados["situacao"] == 2)return "Pago com valor abaixo."; else return "Pago";'
															)
													  )
									)
						);


// Array de configuração para a listagem	
$config["listagemRelatorioPagamentos"] = array(
										  		array("id" => "tabela_idconta", // Id do atributo
												  	  "variavel_lang" => "tabela_idconta", // Referencia a variavel de idioma
												  	  "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
													  "valor" => "idconta",
													  "tamanho" => "40"
													  ),

												array("id" => "tabela_pessoa", 
												  	  "variavel_lang" => "tabela_pessoa", 
													  "tipo" => "banco", 
													  "valor" => "pessoa"
													  ),
												
												array("id" => "tabela_cpf", 
												  	  "variavel_lang" => "tabela_cpf", 
													  "tipo" => "banco", 
													  "valor" => "cpf"
													  ),

												array("id" => "tabela_email", 
												  	  "variavel_lang" => "tabela_email", 
													  "tipo" => "banco", 
													  "valor" => "email"
													  ), 
												
												array("id" => "tabela_datavencimento", 
													  "variavel_lang" => "tabela_datavencimento", 
													  "tipo" => "php", 
													  "coluna_sql" => "c.data_vencimento", 
													  "valor" => 'return formataData($linha["vencimento"],"br",3);'
													  ),

												array("id" => "tabela_valorBoleto", 
													  "variavel_lang" => "tabela_valorBoleto", 
													  "tipo" => "php", 
													  "valor" => 'return "R$ ".number_format($linha["valorInscricao"], 2, ",", ".");'
													  ),
												array("id" => "tabela_valorAtualizado", 
													  "variavel_lang" => "tabela_valorAtualizado", 
													  "tipo" => "php", 
													  "valor" => 'return "R$ ".number_format($linha["valorInscricao"]+ $linha["jurosMulta"], 2, ",", ".");'
													  ),
												array("id" => "tabela_dataPagamento", 
													  "variavel_lang" => "tabela_dataPagamento", 
													  "tipo" => "php", 
													  "valor" => 'return formataData($linha["dataPagamento"],"br",0);'
													  ),
												array("id" => "tabela_valorPagamento", 
													  "variavel_lang" => "tabela_valorPagamento", 
													  "tipo" => "php", 
													  "valor" => 'return "R$ ".number_format($linha["valorPagamento"], 2, ",", ".");'
													  ),

											  	array("id" => "tabela_situacao", 
													  "variavel_lang" => "tabela_situacao", 
													  "tipo" => "php", 
													  "valor" => 'return $idioma[$linha["status"]];'
													  ),

											   );

$config["listagemRelatorioPreview"] = array(
				array("id" => "tabela_nossonumero", // Id do atributo
					  "variavel_lang" => "tabela_nossonumero", // Referencia a variavel de idioma
					  "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
					  "valor" => "nossonumero",
					  "tamanho" => "90"
					  ),

				array("id" => "tabela_pessoa", 
					  "variavel_lang" => "tabela_pessoa", 
					  "tipo" => "php", 
					  "tamanho" => "280",
					  "valor" => '
					  if($linha["pessoa"]){
						  return $linha["pessoa"];
					  }else{
						  return "-";
					  }'
					  ),  //alunonaofazparte
				
				array("id" => "tabela_documento", 
					  "variavel_lang" => "tabela_documento", 
					  "tipo" => "php", 
					  "valor" => '
					  if($linha["documento"]){
						  return $linha["documento"];
					  }else{
						  return "-";
					  }',
					  "tamanho" => "90"
					  ),
				
				array("id" => "tabela_datavencimento", 
					  "variavel_lang" => "tabela_datavencimento", 
					  "tipo" => "php", 
					  "valor" => 'return formataData($linha["datavencimento"],"br",3);',
					  "tamanho" => "90"
					  ),

				array("id" => "tabela_valorBoleto", 
					  "variavel_lang" => "tabela_valorBoleto", 
					  "tipo" => "php", 
					  "valor" => '
					  	if($linha["atualizado"] != $linha["valorboleto"]){
					  		return "R$ <span style=\"text-decoration:line-through;\"> ".number_format($linha["valorboleto"], 2, ",", ".")."</span>";
						}else{
							return "R$ <span> ".number_format($linha["valorboleto"], 2, ",", ".")."</span>";
						}',
					  "tamanho" => "110"
					  ),

				array("id" => "tabela_valorAtualizado", 
					  "variavel_lang" => "tabela_valorAtualizado", 
					  "tipo" => "php", 
					  "valor" => 'return "R$ <span> ".number_format($linha["atualizado"], 2, ",", ".")."</span>";',
					  "tamanho" => "110"
					  ),
				array("id" => "tabela_dataPagamento", 
					  "variavel_lang" => "tabela_dataPagamento", 
					  "tipo" => "php", 
					  "valor" => 'return formataData($linha["datacredito"],"br",0);',
					  "tamanho" => "150"
					  ),

				array("id" => "tabela_valorPagamento", 
					  "variavel_lang" => "tabela_valorPagamento", 
					  "tipo" => "php", 
					  "valor" => '

						if($linha["atualizado"]>0){
					  		return "R$ <span> ".number_format($linha["valorpago"], 2, ",", ".")."</span>";
						}else{
							return "R$ <span style=\"text-decoration:line-through;\"> ".number_format($linha["valorpago"], 2, ",", ".")."</span>";
						}',
					  "tamanho" => "90"
					  ),

			 	array("id" => "tabela_mensagem", 
					  "variavel_lang" => "tabela_mensagem", 
					  "tipo" => "php", 
					  "valor" => '
					  if($linha["pago"] and $linha["pessoa"]){
						  return "<span style=\"color: green;font-size: 10.5px;\">".$idioma["japago"]."<span>";
					  }elseif($linha["valorboleto"] and !$linha["pessoa"]){
						  return "<span style=\"color: red;font-size: 10.5px;\">".$idioma["alunonaofazparte"]."<span>";
					  }else{
						  if($linha["atualizado"] == $linha["valorpago"]){
							  return "<span style=\"color: green;font-size: 10.5px;\">".$idioma["pagamentoOk"]."<span>";
						  }elseif($linha["atualizado"] > $linha["valorpago"]){
							  return "<span style=\"color: red;font-size: 10.5px;\">".$idioma["pagamentoMenorNOK"]."<span>";
						  }elseif($linha["atualizado"] < $linha["valorpago"]){
							  return "<span style=\"color: red;font-size: 10.5px;\">".$idioma["pagamentoMaiorNOK"]."<span>";
						  }
					  }',
					  ),

			   );

?>