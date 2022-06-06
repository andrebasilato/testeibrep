<?

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "idlog_sms",
							  	  "variavel_lang" => "tabela_idlog_sms", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idlog_sms", 
							  	  "valor" => '
									$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
									if($diferenca > 24) {
										return "<span title=\"$diferenca\">".$linha["idlog_sms"]."</span>";
									} else {
										return "<span title=\"$diferenca\">".$linha["idlog_sms"]."</span> <i class=\"novo\"></i>";
									}
									',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 80),
								  
							array("id" => "nome", 
							  	  "variavel_lang" => "tabela_nome",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "nome",
							  	  "valor" => "nome",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "tamanho" => 200,
								  "busca_metodo" => 2),
								  
							array("id" => "celular", 
								  "variavel_lang" => "tabela_celular", 
								  "tipo" => "banco",
								  "coluna_sql" => "celular", 
								  "valor" => 'celular',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "tamanho" => 200,
								  "busca_metodo" => 2),
								   								  
								  
						array("id" => "mensagem", 
								  "variavel_lang" => "tabela_mensagem", 
								  "tipo" => "banco",
								  "coluna_sql" => "mensagem", 
								  "valor" => 'mensagem',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								   								  
						array("id" => "origem", 
								  "variavel_lang" => "tabela_origem", 
								  "tipo" => "php",
								  "coluna_sql" => "origem", 
								  "valor" => '  return $GLOBALS["modulos_sms"]["pt_br"][$linha["origem"]];  ',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "modulos_sms",
								  "busca_metodo" =>1),
								   								  
							//	  modulos_sms
							array("id" => "enviado", 
								  "variavel_lang" => "tabela_enviado", 
								  "tipo" => "php",
								  "coluna_sql" => "enviado", 
								  "valor" => 'if($linha["enviado"] == "S") {
												  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">S</span>";
											  } else {
												  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">N</span>";
											  }',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "sim_nao",
								  "busca_metodo" => 1,
								  "tamanho" => 60), 								  
										  
							array("id" => "data_envio", 
								  "variavel_lang" => "tabela_data_envio", 
								  "coluna_sql" => "data_envio",
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_envio"],"br",1);',
								  "tamanho" => "140",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3
								  ), 
						
							array("id" => "data_cad", 
								  "variavel_lang" => "tabela_datacad", 
								  "coluna_sql" => "data_cad",
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_cad"],"br",1);',
								  "tamanho" => "140",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3
								  ), 
						
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "busca_botao" => true,
								  "tamanho" => "80") 
				
						   );						   
?>