<?php		   
// Array de configuração para a formulario			
$config["formulario_pessoas"] = array(
  array(
	"fieldsetid" => "dadosdocliente", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdadosdocliente", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
			"id" => "documento",
			"nome" => "documento",
			"nomeidioma" => "form_cpf",
			"tipo" => "input", 
			"valor" => "documento",
			"validacao" => array("required" => "cpf_vazio"),
			"class" => "span3",
			"ajudaidioma" => "form_cpf_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
			"evento" => "maxlength='11'",
			"mascara" => "999.999.999-99", //Mascara do campo
			"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
			"banco_php" => 'return str_replace(array(".", "-","/"),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
			"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados) 
			),
	  array(
			"id" => "form_nome",
			"nome" => "nome", 
			"nomeidioma" => "form_nome",
			"tipo" => "input",
			"valor" => "nome",
			"validacao" => array("required" => "nome_vazio"), 
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
			"id" => "form_email",
			"nome" => "email",
			"nomeidioma" => "form_email",
			"tipo" => "input", 
			"valor" => "email",
			"validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
			"class" => "span5",
			"legenda" => "@",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
			"id" => "form_telefone",
			"nome" => "telefone",
			"nomeidioma" => "form_telefone",
			"tipo" => "input", 
			"valor" => "telefone",
			"validacao" => array("required" => "telefone_vazio"),
			"class" => "span2",
			"mascara" => "(99) 9999-9999",
			"banco" => true,
			"banco_string" => true
			),
	  array(
			"id" => "form_celular",
			"nome" => "celular",
			"nomeidioma" => "form_celular",
			"tipo" => "input", 
			"valor" => "celular",
			"class" => "span2",
			"mascara" => "(99) 9999-9999",
			"banco" => true,
			"banco_string" => true
			)
	)
  )
);

$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoatendimento", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdadosdoatendimento", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
			"id" => "idassunto",
			"nome" => "idassunto",
			"nomeidioma" => "form_idassunto",
			"tipo" => "select",
			"sql" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idassunto", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idassunto",
			"validacao" => array("required" => "assunto_vazio"),
			"banco" => true
			),
	  array(
			"id" => "idsubassunto",
			"nome" => "idsubassunto",
			"nomeidioma" => "form_idsubassunto",
			"json" => true,
			"json_idpai" => "idassunto",
			"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/subassunto",
			"json_input_pai_vazio" => "form_selecione_assunto",
			"json_input_vazio" => "form_selecione_subassunto",
			"json_campo_exibir" => "nome",
			"tipo" => "select",
			"valor" => "idsubassunto",
			"validacao" => array("required" => "subassunto_vazio"),
			"banco" => true
			),
		array(
			"id" => "idcurso",
			"nome" => "idcurso",
			"nomeidioma" => "form_idcurso",
			"tipo" => "select",
			"sql" => "SELECT c.idcurso, c.nome 
						FROM cursos c 
						WHERE c.ativo = 'S' AND c.ativo_painel = 'S' 
							and (	select ua.idusuario 
										from usuarios_adm ua
											left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
											left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
										where ua.idusuario = ".$usuario['idusuario']."									   
											and (	ua.gestor_sindicato = 'S' 
													or 
													(	ci.idcurso = c.idcurso and 
														uai.idusuario is not null and 
														ci.idsindicato is not null
													) 
												)
										limit 1
									) is not null
						ORDER BY c.nome", // SQL que alimenta o select
			"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idcurso",
			//"validacao" => array("required" => "curso_vazio"),
			"banco" => true
			),
		array(
			"id" => "idmatricula",
			"nome" => "idmatricula",
			"nomeidioma" => "form_idmatricula",
			"tipo" => "select",
			"sql" => "SELECT m.idmatricula, CONCAT(m.idmatricula,' - ',p.nome) as nome 
						FROM matriculas m 
						INNER JOIN pessoas p ON m.idpessoa = p.idpessoa 
						WHERE m.ativo = 'S'
							and (	select ua.idusuario 
									from usuarios_adm ua
										left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
										left join escolas p on uai.idsindicato = p.idsindicato
										left join matriculas m on p.idescola = m.idescola
									where ua.idusuario = ".$usuario['idusuario']."									   
										and (	ua.gestor_sindicato = 'S' 
												or 
												(	
													(ate.idmatricula is null)
													or
													(	ate.idmatricula = m.idmatricula and
														uai.idusuario is not null and 
														p.idsindicato is not null													
													)
												) 
											)
									limit 1
								) is not null
						ORDER BY m.idmatricula", // SQL que alimenta o select
			"sql_valor" => "idmatricula", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idmatricula",
			//"validacao" => array("required" => "matricula_vazio"),
			"banco" => true
			),
	  /*array(
			"id" => "idunidade",
			"nome" => "idunidade",
			"nomeidioma" => "form_idunidade",
			"tipo" => "select",
			"sql" => "SELECT idunidade, nome FROM empreendimentos_unidades WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idunidade", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idunidade",
			"validacao" => array("required" => "unidade_vazio"),
			"banco" => true
			),*/
	  array(
			"id" => "form_titulo",
			"nome" => "titulo", 
			"nomeidioma" => "form_titulo",
			"tipo" => "input",
			"valor" => "titulo",
			"validacao" => array("required" => "titulo_vazio"), 
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),

	  array(
			"id" => "form_descricao",
			"nome" => "descricao",
			"nomeidioma" => "form_descricao",
			"tipo" => "text",
			"valor" => "descricao",
			"validacao" => array("required" => "descricao_vazio"), 
			"class" => "xxlarge",
			"banco" => true,
			"banco_string" => true
			)
	)
  )											  
);
?>