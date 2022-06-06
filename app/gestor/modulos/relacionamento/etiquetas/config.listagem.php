<?

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "idetiqueta",
							  	  "variavel_lang" => "tabela_idetiqueta", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idetiqueta",  
							  	  "valor" => '
											$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
											if($diferenca > 24) {
												return "<span title=\"$diferenca\">".$linha["idetiqueta"]."</span>";
											} else {
												return "<span title=\"$diferenca\">".$linha["idetiqueta"]."</span> <i class=\"novo\"></i>";
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
								  "busca_metodo" => 2),
								  
							/*array("id" => "ativo_painel", 
								  "variavel_lang" => "tabela_ativo_painel", 
								  "tipo" => "php",
								  "coluna_sql" => "ativo_painel", 
								  "valor" => 'if($linha["ativo_painel"] == "S") {
												  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
											  } else {
												  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
											  }',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "ativo",
								  "busca_metodo" => 1,
								  "tamanho" => 60), */								  
										  
							array("id" => "data_cad", 
								  "variavel_lang" => "tabela_datacad", 
								  "coluna_sql" => "data_cad",
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_cad"],"br",1);',
								  "tamanho" => "140",
								  //"busca" => true,
								  //"busca_class" => "inputPreenchimentoCompleto",
								  //"busca_metodo" => 3
								  ), 
						
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idetiqueta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',/*<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_preview_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idetiqueta"]."/gerar_etiquetas\" data-placement=\"left\" target=\"_blank\" rel=\"tooltip \">".$idioma["tabela_preview"]."</a>&nbsp; */
								  "busca_botao" => true,
								  "tamanho" => "130") 
				
						   );
						   
?>