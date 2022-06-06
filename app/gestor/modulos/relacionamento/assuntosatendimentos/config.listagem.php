<?

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "idsubassunto",
							  	  "variavel_lang" => "tabela_idassunto", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idsubassunto", 
							  	  	"valor" => '
												$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
												if($diferenca > 24) {
													return "<span title=\"$diferenca\">".$linha["idsubassunto"]."</span>";
												} else {
													return "<span title=\"$diferenca\">".$linha["idsubassunto"]."</span> <i class=\"novo\"></i>";
												}
												',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 80),
								  
							array("id" => "assunto", 
							  	  "variavel_lang" => "tabela_assunto",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "assunto",
							  	  "valor" => "assunto",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  
							array("id" => "subassunto", 
							  	  "variavel_lang" => "tabela_subassunto",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "subassunto",
							  	  "valor" => "subassunto",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  
							array("id" => "sla", 
							  	  "variavel_lang" => "tabela_sla",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "sla",
							  	  "valor" => "sla",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),								  
								  
							array("id" => "ativo_painel", 
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
								  "tamanho" => 60), 								  
										  
							array("id" => "data_cad", 
								  "variavel_lang" => "tabela_datacad", 
								  "coluna_sql" => "data_cad",
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_cad"],"br",1);',
								  "tamanho" => "140"), 
						
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'if($linha["tipo"] == "A") {
												  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsubassunto"]."/opcoesassunto\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
											  } else {
												  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsubassunto"]."/opcoessubassunto\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
											  }',
								  
								  "busca_botao" => true,
								  "tamanho" => "80") 
				
						   );
						   
?>