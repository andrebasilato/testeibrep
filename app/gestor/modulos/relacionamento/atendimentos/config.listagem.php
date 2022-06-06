<?

$sqlSindicato = 'select idsindicato, nome_abreviado as nome from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
//$sqlSindicato .= ' order by nome_abreviado';	

		// Array de configuração para a listagem	
		$config["listagem"] = array(
					  		array("id" => "protocolo",
							  	  "variavel_lang" => "tabela_protocolo", 
							  	  "tipo" => "php",
							  	  "coluna_sql" => "protocolo", 
								  "valor" => '
												$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
												if($linha["idclone"]) {
													  $linha["protocolo"] = "#".$linha["protocolo"];
												  }
												if($diferenca > 24) {
													return "<span title=\"$diferenca\">".$linha["protocolo"]."</span>";
												} else {
													return "<span title=\"$diferenca\">".$linha["protocolo"]."</span> <i class=\"novo\"></i>";
												}
												', 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 110),
															  
							array("id" => "cliente", 
							  	  "variavel_lang" => "tabela_cliente",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "p.nome",
								  "tipo" => "banco", 
								  "valor" => 'cliente',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  
							array("id" => "assunto", 
							  	  "variavel_lang" => "tabela_assunto",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "ate.idassunto",
							  	  "valor" => "assunto",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => "SELECT idassunto,nome FROM atendimentos_assuntos where ativo='S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idassunto", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),
								  
							array("id" => "subassunto", 
							  	  "variavel_lang" => "tabela_subassunto",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "ate.idsubassunto",
							  	  "valor" => "subassunto",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => "SELECT asub.idsubassunto, concat(assunto.nome, ' - ', asub.nome) as nome FROM atendimentos_assuntos_subassuntos asub, atendimentos_assuntos assunto where assunto.ativo='S' and asub.ativo='S' and assunto.idassunto=asub.idassunto", // SQL que alimenta o select
								  "busca_sql_valor" => "idsubassunto", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),
															  
							array("id" => "matricula", 
							  	  "variavel_lang" => "tabela_matricula",
							  	  "tipo" => "banco",
								  "coluna_sql" => "ate.idmatricula",
							  	  "valor" => "idmatricula",
								  "busca" => true,
								  "busca_metodo" => 1,
								  "busca_class" => "inputPreenchimentoCompleto"),
								  
							array("id" => "sindicato", 
							  	  "variavel_lang" => "tabela_sindicato",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "m.idsindicato",
							  	  "valor" => "sindicato",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => $sqlSindicato,
								  //"busca_sql" => "SELECT idsindicato,nome FROM sindicatos where ativo='S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),

							array("id" => "curso", 
							  	  "variavel_lang" => "tabela_curso",
							  	  "tipo" => "banco",
							  	  "evento" => "maxlength='100'",
								  "coluna_sql" => "m.idcurso",
							  	  "valor" => "curso",
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => "SELECT idcurso,nome FROM cursos where ativo='S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),
								  
							array("id" => "situacao", 
								  "variavel_lang" => "tabela_situacao", 
								  "tipo" => "php", 
								  "coluna_sql" => "ate.idsituacao", 
								  "tamanho" => "120", 
								  "valor" => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_sql" => "SELECT idsituacao, nome FROM atendimentos_workflow WHERE ativo = 'S'", // SQL que alimenta o select
								  "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
								  "busca_sql_label" => "nome",
								  "busca_metodo" => 1),
								  
							/*array("id" => "proxima_acao", 
								  "variavel_lang" => "tabela_proxima_acao", 
								  "coluna_sql" => "ate.proxima_acao",
								  "tipo" => "php", 
								  "valor" => 'if($linha["proxima_acao"]) { return formataData($linha["proxima_acao"],"br",0); } else { return "-"; }',
								  "busca" => true,
								  "tamanho" => "80",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3), */

  						    array("id" => "data_cad", 
								  "variavel_lang" => "tabela_datacad", 
								  "coluna_sql" => "ate.data_cad",
								  "tipo" => "php", 
								  "valor" => 'if($linha["data_cad"]) { return formataData($linha["data_cad"],"br",0); } else { return "-"; }',
								  "busca" => true,
								  "tamanho" => "80",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3),
								  
							/*array("id" => "sla_vencido", 
								  "variavel_lang" => "tabela_sla_vencido", 
								  "tipo" => "php",
								  "coluna_sql" => "sla_vencido", 
								  "valor" => 'if($linha["sla_vencido"] == "S") {
												  return "<span data-original-title=\"".$idioma["sla_vencido"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">".$idioma["vencido"]."</span>";
											  } else {
												  return "<span data-original-title=\"".$idioma["sla_nao_vencido"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">".$idioma["no_prazo"]."</span>";
											  }',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "sim_nao",
								  "busca_metodo" => 5,
								  "tamanho" => 60), */
								  
							array("id" => "prioridade", 
								  "variavel_lang" => "tabela_prioridade", 
								  "tipo" => "php",
								  "coluna_sql" => "ate.prioridade", 
								  "valor" => 'if($linha["prioridade"] == "A") {
												  return "<span class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">".$idioma["prioridade_alta"]."</span>";
											  } else if($linha["prioridade"] == "B") {
												  return "<span class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">".$idioma["prioridade_baixa"]."</span>";
											  } else {
												  return "<span class=\"label label-info\" data-placement=\"left\" rel=\"tooltip\">".$idioma["prioridade_normal"]."</span>";
											  }',
								  "busca" => true,
								  "busca_tipo" => "select",
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_array" => "prioridades",
								  "busca_metodo" => 1,
								  "tamanho" => 60),
							
							//Era opcoes... virou visualiza					
							array("id" => "opcoes", 
								  "variavel_lang" => "tabela_opcoes", 
								  "tipo" => "php", 
								  "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_ficha_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idatendimento"]."/ficha\" target=\"_blank\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_ficha"]."</a>&nbsp;<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idatendimento"]."/visualiza\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_abrir"]."</a>"',
								  "busca_botao" => true,
								  "tamanho" => "100") 
				
						   );						   
?>