<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlSindicato = 'SELECT idsindicato, nome_abreviado FROM sindicatos WHERE ativo = "S"';

if ($_SESSION['adm_gestor_sindicato'] != 'S') {
	$sqlSindicato .= ' AND idsindicato IN ('.$_SESSION['adm_sindicatos'].')';	
}

$sqlSindicato .= ' ORDER BY nome_abreviado';	

// Array de configuração para a listagem	
$config['listagem'] = array(
							array(
								'id' => 'idusuario',
								'variavel_lang' => 'tabela_idusuario', 
								'tipo' => 'banco', 
								'coluna_sql' => 'u.idusuario', 
								'valor' => 'idusuario',
								'tamanho' => 50
							),
								  
							array(
								'id' => 'tabela_nome',
								'variavel_lang' => 'tabela_nome_usuario',
								'tipo' => 'banco',
								'coluna_sql' => 'u.nome',
								'valor' => 'usuario',
								'tamanho' => 200,
							), 
							
							array(
								'id' => 'tabela_email', 
								'variavel_lang' => 'tabela_email', 
								'tipo' => 'php',
								'coluna_sql' => 'u.email', 
								'valor' => 'return "<a data-original-title=\"".$linha["email"]."\" data-placement=\"top\" rel=\"tooltip\" href=\"mailto:".$linha["email"]."\">".$linha["email"]."</a>";',
							),
					
							array(
								'id' => 'perfil', 
								'variavel_lang' => 'tabela_perfil', 
								'tipo' => 'banco', 
								'coluna_sql' => 'p.idperfil', 
								'valor' => 'perfil',
								'tamanho' => 100,
							),

							array(
								'id' => 'sindicato', 
								'variavel_lang' => 'tabela_sindicato', 
								'tipo' => 'banco', 
								'coluna_sql' => 'p.idsindicato', 
								'valor' => 'sindicatos',
							)
				   );

						
// Array de configuração para a formulario			
$config['formulario'] = array(
							array(
								'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
								'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
								'campos' => array( // Campos do formulario																						
												array(
													'id' => 'form_idperfil',
													'nome' => 'q[1|u.idperfil]',
													'nomeidioma' => 'form_idperfil',
													'tipo' => 'select',
													'sql' => 'SELECT idperfil, nome FROM usuarios_adm_perfis WHERE ativo = "S" ORDER BY nome',
													'sql_valor' => 'idperfil', // Coluna da tabela que será usado como o valor do options
													'sql_label' => 'nome', // Coluna da tabela que será usado como o label do options
													'valor' => 'idperfil',
													'class' => 'span5',
													'sql_filtro' => 'SELECT * FROM usuarios_adm_perfis WHERE idperfil = %',
													'sql_filtro_label' => 'nome'
												),

												array(
													'id' => 'data_cad',
													'nome' => 'q[3|u.data_cad]', 
													'nomeidioma' => 'data_cad',
													'tipo' => 'input',
													'class' => 'span2',
													'datepicker' => true,
													'mascara' => '99/99/9999',
												),

												array(
                                                    'id' => 'idsindicato',
                                                    'sql' => $sqlSindicato,
                                                    'nome' => 'q[8|i.idsindicato]',
                                                    'nomeidioma' => 'form_sindicato',
                                                    'tipo' => 'select',
                                                    'valor' => 'idsindicato',
                                                    'class' => 'span5',
                                                    'sql_valor' => 'idsindicato',
                                                    'sql_label' => 'nome_abreviado',
                                                    'sql_filtro' => 'SELECT * FROM sindicatos WHERE ativo = "S" AND idsindicato = %',
                                                    'sql_filtro_label' => 'nome_abreviado'
                                                ),
											)
							)					  
						);