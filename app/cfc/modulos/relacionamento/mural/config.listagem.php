<?

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "idmural",
							  	  "variavel_lang" => "tabela_mural", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idmural", 
							  	  "valor" => '
									$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d"), "H");
									if($diferenca > 24) {
										return "<span title=\"$diferenca\">".$linha["idmural"]."</span>";
									} else {
										return "<span title=\"$diferenca\">".$linha["idmural"]."</span> <i class=\"novo\"></i>";
									}
									',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 80),
								  
							array("id" => "titulo", 
								  "variavel_lang" => "tabela_titulo", 
								  "tipo" => "banco", 
								  "coluna_sql" => "titulo", 
								  "valor" => "titulo",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1),
							
							array("id" => "resumo", 
							  	  "variavel_lang" => "tabela_resumo",
							  	  "tipo" => "banco",
								  "coluna_sql" => "resumo",
							  	  "valor" => "resumo",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
							array("id" => "lido", 
								  "variavel_lang" => "tabela_lido", 
								  "coluna_sql" => "mf.data_lido",
								  "tipo" => "php", 
								  "valor" => 'if($linha["data_lido"] && !is_null($linha["data_lido"])) {
												  return "<span data-original-title=\"".$idioma["msg_lida"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Lido</span>";
											  } else {
												  return "<span data-original-title=\"".$idioma["msg_nao_lida"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Não lido</span>";
											  }',
								  "tamanho" => "60",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "leitura",
								  "busca_metodo" => 6),									  
								  						
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'return "<a class=\"btn btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmural"]."/visualizar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_abrir"]."</a>"',
								  "busca_botao" => true,
								  "tamanho" => "80") 
				
						   );
?>