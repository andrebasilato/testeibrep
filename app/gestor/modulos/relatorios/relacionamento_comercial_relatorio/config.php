<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["banco"] = array("tabela" => "pessoas",
						 "primaria" => "idpessoa",
						);

// Array de configuração para a listagem	
$config["listagem"] = array(

					  		/*array("id" => "tabela_numero", // Id do atributo
							  	  "variavel_lang" => "tabela_numero", // Referencia a variavel de idioma
							  	  "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
							  	  "coluna_sql" => "c.idpessoa", // Nome da coluna no banco de dados
								  "valor" => 'return "<a href=\"/".$this->url["0"]."/cadastros/pessoas/".$linha["idpessoa"]."/editar\" target=\"_blank\">".$linha["idpessoa"]."</a>";',
								  "tamanho" => "60"
								  ),

							array("id" => "tabela_pessoa", 
							  	  "variavel_lang" => "tabela_pessoa", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.nome", 
								  "valor" => "nome"), 

							array("id" => "tabela_documento", 
								  "variavel_lang" => "tabela_documento", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.documento", 
								  "valor" => "documento"),	
								  
							array("id" => "tabela_profissao", 
								  "variavel_lang" => "tabela_profissao", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.profissao", 
								  "valor" => "profissao"),	

							array("id" => "tabela_email", 
								  "variavel_lang" => "tabela_email", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.email", 
								  "valor" => "email"),	

							array("id" => "tabela_telefone", 
								  "variavel_lang" => "tabela_telefone", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.telefone", 
								  "valor" => "telefone"),	
							
							array("id" => "tabela_estado", 
								  "variavel_lang" => "tabela_estado", 
								  "tipo" => "banco", 
								  "coluna_sql" => "e.nome", 
								  "valor" => "estado"),
								  
							array("id" => "tabela_cidade", 
								  "variavel_lang" => "tabela_cidade", 
								  "tipo" => "banco", 
								  "coluna_sql" => "c.nome", 
								  "valor" => "cidade"),

							array("id" => "tabela_endereco", 
								  "variavel_lang" => "tabela_endereco", 
								  "tipo" => "php", 
								  "valor" => ' return $linha["endereco"]." ".$linha["bairro"] ."<br />Complemento: ".$linha["completo"]."<br />CEP:".$linha["cep"] '
								  ),								  

							array("id" => "tabela_estadocivil",
								  "variavel_lang" => "tabela_estadocivil",
								  "coluna_sql" => "p.estado_civil", 
								  "tipo" => "php",  
								  "valor" => 'return $GLOBALS["estadocivil"][$GLOBALS["config"]["idioma_padrao"]][$linha["estado_civil"]];',
								  "tamanho" => "100"), // Coluna da tabela que será usado como o label do options), 	
								
							array(
								'id' => 'data_nasc',
								'variavel_lang' => 'tabela_data_nasc',
								'tipo' => 'php',
								'coluna_sql' => 'p.data_nasc',
								'valor' => 'return formataData($linha["data_nasc"],"br",0);',
								'tamanho' => '80',
							),
								  	  
							array("id" => "tabela_datacad", 
								  "variavel_lang" => "tabela_datacad", 
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_cad"],"br",1);',
								  "tamanho" => "160"),							
								  
							array("id" => "tabela_sindicatos", 
								  "variavel_lang" => "tabela_sindicatos", 
								  "tipo" => "banco", 
								  "coluna_sql" => "sindicatos", 
								  "valor" => "sindicatos"),*/
														  
								  
				
						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario																						
													  /*array(
															"id" => "form_nome",
															"nome" => "q[2|p.nome]", 
															"nomeidioma" => "form_nome",
															"tipo" => "input",
															"valor" => "nome", 
															"class" => "span5",
															"evento" => "maxlength='100'"
															),
													  array(
															"id" => "estado_civil",
															"nome" => "q[1|p.estado_civil]",
															"nomeidioma" => "form_estadocivil",
															"tipo" => "select",
															"array" => "estadocivil", // Array que alimenta o select
															"class" => "span2",
															"valor" => "estado_civil",
															"sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
															"sql_filtro_label" => "estadocivil"															
															),
													 array(
															"id" => "idestado",
															"nome" => "q[1|p.idestado]",
															"nomeidioma" => "form_idestado",
															"tipo" => "select",
															"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idestado",
															"sql_filtro" => "select * from estados where idestado=%",
															"sql_filtro_label" => "nome"															
															),
													 array(
															"id" => "idcidade",
															"nome" => "q[1|p.idcidade]",
															"nomeidioma" => "form_idcidade",
															"json" => true,
															"json_idpai" => "idestado",
															"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cidades/",
															"json_input_pai_vazio" => "form_selecione_estado",
															"json_input_vazio" => "form_selecione_cidade",
															"json_campo_exibir" => "nome",
															"tipo" => "select",
															"valor" => "idcidade",
															"sql_filtro" => "select * from cidades where idcidade=%",
															"sql_filtro_label" => "nome"															
															),
													 array(
															"id" => "idsindicato",
															"nome" => "idsindicato",
															"nomeidioma" => "form_idsindicato",
															"tipo" => "select",
															"sql" => "SELECT idsindicato, nome_abreviado FROM sindicatos where ativo = 'S' ORDER BY nome_abreviado", // SQL que alimenta o select
															"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
															"valor" => "idsindicato",
															"sql_filtro" => "select * from sindicatos where idsindicato=%",
															"sql_filtro_label" => "nome_abreviado"															
															),*/
															
													  array(
														'id' => 'form_tipo_data_filtro',
														'nome' => 'q[de_ate|tipo_data_filtro|rc.data_cad]',
														'tipo' => 'select',
														'array' => 'tipo_data_filtro',
														'class' => 'span3',
														'valor' => 'tipo_data_filtro',
														'banco' => true,
														'iddiv' => 'de',
														'iddiv2' => 'ate',
														'iddivs' => array('de','ate'),
														'iddiv_obr' => true,
														//'validacao' => array('required' => 'tipo_data_filtro_vazio'),
														'nomeidioma' => 'form_tipo_data_filtro',
														'botao_hide' => true,
														'iddiv2_obr' => true,
														'sql_filtro' => 'array',
														'banco_string' => true,
														'sql_filtro_label' => 'tipo_data_filtro'
													),
													array(
														'id' => 'form_de',
														'nome' => 'de',
														'valor' => 'de',
														'tipo' => 'input',
														'class' => 'span2',
														"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
														//"validacao" => array("required" => "de_vazio"),
														'nomeidioma' => 'form_de',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
													array(
														'id' => 'form_ate',
														'nome' => 'ate',
														'valor' => 'ate',
														'tipo' => 'input',
														'class' => 'span2',
														"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
														//"validacao" => array("required" => "ate_vazio"),
														'nomeidioma' => 'form_ate',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
													
													array(
														'id' => 'form_tipo_data_filtro_proxima_acao',
														'nome' => 'q[de_ate|tipo_data_filtro|rcm.data_cad]',
														'tipo' => 'select',
														'array' => 'tipo_data_filtro',
														'class' => 'span3',
														'valor' => 'tipo_data_filtro',
														'banco' => true,
														'iddiv' => 'de_proxima_acao',
														'iddiv2' => 'ate_proxima_acao',
														'iddivs' => array('de_proxima_acao','ate_proxima_acao'),
														'iddiv_obr' => true,
														//'validacao' => array('required' => 'tipo_data_filtro_vazio'),
														'nomeidioma' => 'form_tipo_data_filtro_proxima_acao',
														'botao_hide' => true,
														'iddiv2_obr' => true,
														'sql_filtro' => 'array',
														'banco_string' => true,
														'sql_filtro_label' => 'tipo_data_filtro'
													),
													array(
														'id' => 'form_de_proxima_acao',
														'nome' => 'de_proxima_acao',
														'valor' => 'de_proxima_acao',
														'tipo' => 'input',
														'class' => 'span2',
														"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_proxima_acao\",\"form_ate_proxima_acao\")'",
														//"validacao" => array("required" => "de_vazio"),
														'nomeidioma' => 'form_de_proxima_acao',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
													array(
														'id' => 'form_ate_proxima_acao',
														'nome' => 'ate_proxima_acao',
														'valor' => 'ate_proxima_acao',
														'tipo' => 'input',
														'class' => 'span2',
														"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_proxima_acao\",\"form_ate_proxima_acao\")'",
														//"validacao" => array("required" => "ate_vazio"),
														'nomeidioma' => 'form_ate_proxima_acao',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
															
													  )
									)					  
						);						
						
						
						
						
?>