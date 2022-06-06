<?php

		// Array de configuração para a listagem	
		$config["listagem"] = array(
							array("id" => "id_solicitacao_declaracao",
							  	  "variavel_lang" => "tabela_id_solicitacao_declaracao", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "idsolicitacao_declaracao", 
							  	  	"valor" => '
												$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
												if($diferenca > 24) {
													return "<span title=\"$diferenca\">".$linha["idsolicitacao_declaracao"]."</span>";
												} else {
													return "<span title=\"$diferenca\">".$linha["idsolicitacao_declaracao"]."</span> <i class=\"novo\"></i>";
												}
												',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),

							array("id" => "declaracao", 
							  	  "variavel_lang" => "tabela_declaracao",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "d.nome",
							  	  "valor" => "declaracao",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "idmatricula",
							  	  "variavel_lang" => "tabela_idmatricula", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "m.idmatricula", 
							  	  "valor" => 'return $linha["idmatricula"];',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),

							array("id" => "id_cod_aluno",
							  	  "variavel_lang" => "tabela_id_cod_aluno", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "pe.idpessoa", 
							  	  	"valor" => 'return $linha["idpessoa"];',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),

							array("id" => "aluno", 
							  	  "variavel_lang" => "tabela_aluno",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "pe.nome",
							  	  "valor" => "aluno",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "data_solicitacao", 
								  "variavel_lang" => "tabela_data_solicitacao", 
								  "coluna_sql" => "sd.data_solicitacao",
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_solicitacao"],"br",1);',
								  "tamanho" => "100",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3
								  ),

							array("id" => "situacao", 
							  	  "variavel_lang" => "tabela_situacao",
							  	  "tipo" => "php",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "sd.situacao",
								  "valor" => 'return "<span class=\"label\" style=\"background-color:".$linha["situacao_legenda_cor"]."\">".$linha["situacao_legenda"]."</span>";',
							  	  "valor" => 'if($linha["situacao"] == "E") {
													return "<span class=\"label\" style=\"background-color:#FF6600\" >".$idioma["situacao_espera"]."</span>";
												} elseif ($linha["situacao"] == "D") {
													return "<span class=\"label\" style=\"background-color:#339900\" >".$idioma["situacao_deferida"]."</span>";
												} elseif ($linha["situacao"] == "I") {
													return "<span class=\"label\" style=\"background-color:#ff0000\" >".$idioma["situacao_indeferida"]."</span>";
												}',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "status_solicitacao_declaracao",
								  "busca_metodo" => 2,
								  "tamanho" => 90),

					  		array("id" => "idmatriculadeclaracao",
							  	  "variavel_lang" => "tabela_idmatriculadeclaracao", 
							  	  "tipo" => "php", 
							  	  "coluna_sql" => "md.idmatriculadeclaracao", 
							  	  "valor" => 'if($linha["idmatriculadeclaracao"]) {
													return $linha["idmatriculadeclaracao"];
												} else {
													return "---";
												}',  
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 80),
								  								  
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'if ($linha["situacao"] == "E") {
								  				return "<a class=\"btn dropdown-toggle btn-mini\" 
								  				data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsolicitacao_declaracao"]."/opcoes\" 
								  				data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
								  			} elseif ($linha["situacao"] == "D") {
								  				return "<a class=\"btn dropdown-toggle btn-mini\" 
								  				data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" target=\"_blank\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsolicitacao_declaracao"]."/baixardeclaracao/".$linha["idmatriculadeclaracao"]."/".$linha["idmatricula"]."\" 
								  				data-placement=\"left\">".$idioma["tabela_pdf"]."</a>";
								  			}elseif ($linha["situacao"] == "I") {
								  				return "<a class=\"btn dropdown-toggle btn-mini\" 
								  				data-original-title=\"".$idioma["tabela_motivo_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsolicitacao_declaracao"]."/vermotivo\" 
								  				data-placement=\"left\" rel=\"tooltip facebox\" >".$idioma["tabela_motivo"]."</a>";
								  			}
								  			',
								  "busca_botao" => true,
								  "tamanho" => "130") 
				
						   );
						   
?>