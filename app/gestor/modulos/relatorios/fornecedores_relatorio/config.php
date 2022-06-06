<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	

// Array de configuração para a listagem	
$config["listagem"] = array(


					  		array("id" => "tabela_numero", // Id do atributo
							  	  "variavel_lang" => "tabela_numero", // Referencia a variavel de idioma
							  	  "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
							  	  "coluna_sql" => "f.idfornecedor", // Nome da coluna no banco de dados
								  "valor" => 'return "<a href=\"/".$this->url["0"]."/cadastros/corretores/".$linha["idfornecedor"]."/editar\" target=\"_blank\">".$linha["idfornecedor"]."</a>";',
								  "tamanho" => "60"
								  ),

							array("id" => "tabela_fornecedor", 
							  	  "variavel_lang" => "tabela_fornecedor", 
								  "tipo" => "banco", 
								  "coluna_sql" => "f.nome", 
								  "valor" => "nome"),								

							array("id" => "tabela_documento", 
								  "variavel_lang" => "tabela_documento", 
								  "tipo" => "banco", 
								  "coluna_sql" => "f.documento", 
								  "valor" => "documento"),	
								  
							array("id" => "tabela_fax", 
								  "variavel_lang" => "tabela_fax", 
								  "tipo" => "banco", 
								  "coluna_sql" => "f.fax", 
								  "valor" => "fax"),

							array("id" => "tabela_telefone", 
								  "variavel_lang" => "tabela_telefone", 
								  "tipo" => "banco", 
								  "coluna_sql" => "f.telefone", 
								  "valor" => "telefone"),								  

							array("id" => "tabela_email", 
								  "variavel_lang" => "tabela_email", 
								  "tipo" => "banco", 
								  "coluna_sql" => "f.email", 
								  "valor" => "email"),
								  
							array("id" => "tabela_sindicato", 
								  "variavel_lang" => "tabela_sindicato", 
								  "tipo" => "banco", 
								  "coluna_sql" => "i.nome", 
								  "valor" => "sindicato")

						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario																						
													  array(
															"id" => "form_nome",
															"nome" => "q[2|f.nome]", 
															"nomeidioma" => "form_nome",
															"tipo" => "input",
															"valor" => "nome", 
															"class" => "span5",
															"evento" => "maxlength='100'"
															),
													  array(
															"id" => "idsindicato",
															"nome" => "q[1|f.idsindicato]",
															"nomeidioma" => "form_idsindicato",
															"tipo" => "select",
															"sql" => $sqlSindicato,
															"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
															"valor" => "idsindicato",
															"sql_filtro" => "select * from sindicatos where idsindicato=%",
															"sql_filtro_label" => "nome_abreviado"															
															),
													 array(
															"id" => "idestado",
															"nome" => "q[1|f.idestado]",
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
															"nome" => "q[1|f.idcidade]",
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
													  )
									)					  
						);						
						
						
?>