<?

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "idvisita",
							  	  "variavel_lang" => "tabela_idvisita", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idvisita", 
							  	  "valor" => '
									$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d"), "H");
									if($diferenca > 24) {
										return "<span title=\"$diferenca\">".$linha["idvisita"]."</span>";
									} else {
										return "<span title=\"$diferenca\">".$linha["idvisita"]."</span> <i class=\"novo\"></i>";
									}
									',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 80),
								  
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"tipo" => "php", 
	"coluna_sql" => "data_cad",
	"valor" => 'return formataData($linha["data_cad"],"br",0);',
	"tamanho" => "80",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 3,	
  ),								  
								  
							array("id" => "nome", 
							  	  "variavel_lang" => "tabela_nome",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "pe.nome",
								  "tipo" => "php", 
								  "valor" => '
											  if($linha["nome_pessoa"]) {
												return $linha["nome_pessoa"];
											  } else {
												return $linha["nome"];
											  }
										     ',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 4),
							
							/*array("id" => "documento", 
							  	  "variavel_lang" => "tabela_documento",
							  	  "tipo" => "php",
							  	  "evento" => "maxlength='11'",
								  "coluna_sql" => "pe.documento",
							  	  "valor" => 'if($linha["documento_pessoa"]) {
													return $linha["documento_pessoa"];
												  } else {
												  	return $linha["documento"];
												  }',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),*/
								  
							array("id" => "email", 
							  	  "variavel_lang" => "tabela_email",
							  	  "tipo" => "php",
								  "coluna_sql" => "pe.email",
							  	  "valor" => '
											  if($linha["email_pessoa"]) {
												return $linha["email_pessoa"];
											  } else {
												return $linha["email"];
											  }
											 ',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
							/*	  
							array("id" => "curso", 
							  	  "variavel_lang" => "tabela_curso",
							  	  "tipo" => "banco",
								  "coluna_sql" => "cursos",
							  	  "valor" => "cursos"), */
								  
							/*array("id" => "motivo", 
							  	  "variavel_lang" => "tabela_motivo",
							  	  "tipo" => "banco",
								  "coluna_sql" => "mot_v.nome",
							  	  "valor" => "motivo",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),	*/

							array(
								"id" => "situacao", 
								"variavel_lang" => "tabela_situacao", 
								"tipo" => "php",
								"coluna_sql" => "situacao", 
								"valor" => 'if($linha["situacao"] == "EMV") {
											  	return "<span class=\"label label-warning\">Em visita</span>";
											} elseif($linha["situacao"] == "MAT") {
											  	return "<span class=\"label label-success\">Matriculado</span>";
											} elseif($linha["situacao"] == "SEI") {
											  	return "<span class=\"label label-important\">Sem interesse</span>";
											} else {
												return "---";
											}',
								"busca" => true,
								"busca_tipo" => "select",
								"busca_class" => "inputPreenchimentoCompleto",
								"busca_array" => "situacao_visita_vendedores",
								"busca_metodo" => 1,
								"tamanho" => 180
							  ), 								  						  
								  
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'if ($linha["geolocation"] != "")
								  			   return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_geolocalizacao_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idvisita"]."/geolocalizacao\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_geolocalizacao"]."</a><a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idvisita"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
								  			elseif ($linha["geolocation"] == "") 
								  			   return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idvisita"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";',
								  "busca_botao" => true,
								  "tamanho" => "120") 
				
						   );
						   
?>