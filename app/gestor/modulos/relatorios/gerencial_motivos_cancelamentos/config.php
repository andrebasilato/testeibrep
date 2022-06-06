<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["banco"] = array("tabela" => "pessoas",
						 "primaria" => "idpessoa",
						);
						
$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';

// Array de configuração para a listagem	
$config["listagem"] = array(

							array("id" => "tabela_numero", // Id do atributo
							  	  "variavel_lang" => "tabela_numero", // Referencia a variavel de idioma
							  	  "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
							  	  "coluna_sql" => "mc.idmotivo", // Nome da coluna no banco de dados
								  "valor" => 'idmotivo',
								  "tamanho" => "60"
								  ),

							array("id" => "tabela_motivo", 
							  	  "variavel_lang" => "tabela_motivo", 
								  "tipo" => "banco", 
								  "coluna_sql" => "mc.nome", 
								  "valor" => "nome"), 
							  

							array("id" => "tabela_quantidade_matriculas",
								  "variavel_lang" => "tabela_quantidade_matriculas",
								  "coluna_sql" => "quantidade_matriculas", 
								  "tipo" => "php",  
								  "valor" => 'return "<a href=\"/".$this->url["0"]."/relatorios/vendas_detalhado/html?q[1|ma.idsindicato]=".$_GET["q"]["1|m.idsindicato"]."&q[1|ma.idcurso]=".$_GET["q"]["1|m.idcurso"]."&q[1|ma.idsituacao]=".$linha["situacao_cancelada"]."&data_cancelamento_de=".$_GET["de"]."&data_cancelamento_ate=".$_GET["ate"]."&motivo_cancelamento=".$linha["idmotivo"]."\" target=\"_blank\">".$linha["quantidade_matriculas"]."</a>";',
								  "tamanho" => "100"),
								  	  
							/*array("id" => "tabela_datacad", 
								  "variavel_lang" => "tabela_datacad", 
								  "tipo" => "php", 
								  "valor" => 'return formataData($linha["data_cad"],"br",1);',
								  "tamanho" => "160"),*/
				
						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario																						
													  
													 array(
														'id' => 'idsindicato',
														"sql" => $sqlSindicato,
														"nome" => "q[1|m.idsindicato]",
														"tipo" => "select",
														"valor" => "idsindicato",
														"class" => "span5",
														"sql_valor" => "idsindicato",
														"sql_label" => "nome_abreviado",
														// "validacao" => array("required" => "tipo_data_filtro_vazio"),
														"nomeidioma" => "form_idsindicato",
														"sql_filtro" => "select * from sindicatos where idsindicato=%",
														"sql_filtro_label" => "nome_abreviado"
													),
													
													array(
														'id' => 'idcurso',
														"sql" => "select idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome",
														'nome' => 'q[1|m.idcurso]',
														'tipo' => 'select',
														'valor' => 'idcurso',
														"class" => "span5",
														"sql_valor" => "idcurso",
														"sql_label" => "nome",
														'nomeidioma' => 'form_idcurso',
														'sql_filtro' => 'select * from cursos where idcurso=%',
														'sql_filtro_label' => 'nome',
													),
															
													  array(
														'id' => 'form_tipo_data_filtro',
														'nome' => 'q[de_ate|tipo_data_filtro|mhi.data_cad]',
														'tipo' => 'select',
														'array' => 'tipo_data_filtro',
														'class' => 'span3',
														'valor' => 'tipo_data_filtro',
														'banco' => true,
														'iddiv' => 'de',
														'iddiv2' => 'ate',
														'iddivs' => array('de','ate'),
														'iddiv_obr' => true,
														'validacao' => array('required' => 'tipo_data_filtro_vazio'),
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
														"validacao" => array("required" => "de_vazio"),
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
														"validacao" => array("required" => "ate_vazio"),
														'nomeidioma' => 'form_ate',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
															
													  )
									)					  
						);						
						
						
						
						
?>