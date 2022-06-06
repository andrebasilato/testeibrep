<?php
$config ["funcionalidade"] = "funcionalidade";
$config ["acoes"] [1] = "visualizar";

$config ["banco"] = array (
		"tabela" => "contratos",
		"primaria" => "idcontrato" 
);

// Array de configuração para a formulario
$config ["formulario"] = array (
		array (
				"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
				"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
				"campos" => array ( // Campos do formulario
						array (
								"id" => "form_contrato",
								"nome" => "q[1|m.numero_contrato]",
								"nomeidioma" => "form_contrato",
								"tipo" => "input",
								"valor" => "idcontrato",
								"class" => "span5",
								"evento" => "maxlength='100'" 
						),
						array (
								"id" => "form_matricula",
								"nome" => "q[1|m.idmatricula]",
								"nomeidioma" => "form_matricula",
								"tipo" => "input",
								"valor" => "matricula",
								"class" => "span5",
								"evento" => "maxlength='50'" 
						),
						array (
								"id" => "form_idcurso",
								"nome" => "q[1|m.idcurso]",
								"nomeidioma" => "form_idcurso",
								"tipo" => "select",
								"sql" => "select idcurso, nome from cursos where ativo = 'S' order by nome", // SQL que alimenta o select
								"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
								"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
								"sql_filtro" => "select * from cursos where idcurso = %",
								"valor" => "idcurso",
								"sql_filtro_label" => "nome" 
						),
						array (
								"id" => "form_nome",
								"nome" => "q[1|p.nome]",
								"nomeidioma" => "form_nome",
								"tipo" => "input",
								"valor" => "nome",
								"class" => "span5",
								"evento" => "maxlength='100'" 
						),
						array (
								"id" => "form_email",
								"nome" => "q[1|p.email]",
								"nomeidioma" => "form_email",
								"tipo" => "input",
								"valor" => "email",
								"class" => "span5",
								"evento" => "maxlength='100'",
                                'input_tipo' => "email"
						),
						array (
								"id" => "form_idvendedor",
								"nome" => "q[1|m.idvendedor]",
								"nomeidioma" => "form_idvendedor",
								"tipo" => "select",
								"sql" => "select idvendedor, nome from vendedores where ativo = 'S' order by nome", // SQL que alimenta o select
								"sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
								"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
								"sql_filtro" => "select * from vendedores where idvendedor = %",
								"valor" => "idvendedor",
								"sql_filtro_label" => "nome" 
						),

						array (
								"id" => "form_dias_matricula",
								"nome" => "dias_matricula",
								"nomeidioma" => "form_dias_matricula",
								"tipo" => "input",
								"valor" => "dias_matricula",
								"class" => "span5",
								"evento" => "maxlength='5'" ,
                                'input_tipo' => "number",
						),
						array(
								"id" => "validado_aluno",
								"nome" => "validado_aluno",
								"nomeidioma" => "form_validado_aluno",
								"tipo" => "checkbox",
								"array" => "sim_nao", // Array que alimenta o select
								"class" => "span2",
								"sql_filtro" => "array",
								"sql_filtro_label" => "sim_nao"
						),					
						array(
								"id" => "form_tipo_data_validacao_filtro",
								'nome' => 'q[de_ate_validado|tipo_data_validado_filtro|mc.assinado]',
								"nomeidioma" => "form_tipo_data_filtro",
								"botao_hide" => true,
								"iddivs" => array("de_validacao","ate_validacao"),
								"tipo" => "select",
								"iddiv" => "de_validacao",
								"iddiv2" => "ate_validacao",
								"iddiv_obr" => true,
								"iddiv2_obr" => true,
								"array" => "tipo_data_filtro", // Array que alimenta o select
								"class" => "span3",
								"valor" => "tipo_data_filtro",
								//"validacao" => array("required" => "tipo_data_filtro_vazio"),
								"banco" => true,
								"banco_string" => true,
								"sql_filtro" => "array",
								"sql_filtro_label" => "tipo_data_filtro"
						),
						array(
								"id" => "form_de_validacao",
								"nome" => "de_validacao",
								"nomeidioma" => "de_validacao",
								"tipo" => "input",
								"class" => "span2",
								"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_validacao\",\"form_ate_validacao\")'",
								//"validacao" => array("required" => "de_situacao_vazio"),
								"datepicker" => true,
								"input_hidden" => true,
						),
						array(
								"id" => "form_ate_validacao",
								"nome" => "ate_validacao",
								"nomeidioma" => "ate_validacao",
								"tipo" => "input",
								"class" => "span2",
								"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_validacao\",\"form_ate_validacao\")'",
								//"validacao" => array("required" => "ate_situacao_vazio"),
								"datepicker" => true,
								"input_hidden" => true,
						),
						array(
								"id" => "idsituacao",
								"nome" => "situacao",								
								"nomeidioma" => "form_idsituacao",
								"tipo" => "checkbox",
								"sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo='S' order by nome--",
								"sql_valor" => "idsituacao",
								"sql_label" => "nome",
								"valor" => "idsituacao",
								"sql_filtro" => "select * from matriculas_workflow where idsituacao=%",
								"sql_filtro_label" => "nome"
						),
						array(
								"id" => "aprovado_comercial",
								"nome" => "aprovado_comercial",
								"nomeidioma" => "form_aprovado_comercial",
								"tipo" => "checkbox",
								"array" => "sim_nao", // Array que alimenta o select
								"class" => "span2",
								"sql_filtro" => "array",
								"sql_filtro_label" => "sim_nao"
						),
						array(
								"id" => "form_tipo_data_filtro",
								'nome' => 'q[de_ate_aprovado|tipo_data_aprovado_filtro|mc.validado]',
								"nomeidioma" => "form_tipo_data_validado_filtro",
								"botao_hide" => true,
								"iddivs" => array("de_aprovacao","ate_aprovacao"),
								"tipo" => "select",
								"iddiv" => "de_aprovacao",
								"iddiv2" => "ate_aprovacao",
								"iddiv_obr" => true,
								"iddiv2_obr" => true,
								"array" => "tipo_data_filtro", // Array que alimenta o select
								"class" => "span3",
								"valor" => "tipo_data_filtro",
								//"validacao" => array("required" => "tipo_data_filtro_vazio"),
								"banco" => true,
								"banco_string" => true,
								"sql_filtro" => "array",
								"sql_filtro_label" => "tipo_data_filtro"
						),
						array(
								"id" => "form_de_aprovacao",
								"nome" => "de_aprovacao",
								"nomeidioma" => "de_aprovacao",
								"tipo" => "input",
								"class" => "span2",
								"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_aprovacao\",\"form_ate_aprovacao\")'",
								//"validacao" => array("required" => "de_situacao_vazio"),
								"datepicker" => true,
								"input_hidden" => true,
						),
						array(
								"id" => "form_ate_aprovacao",
								"nome" => "ate_aprovacao",
								"nomeidioma" => "ate_aprovacao",
								"tipo" => "input",
								"class" => "span2",
								"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_aprovacao\",\"form_ate_aprovacao\")'",
								//"validacao" => array("required" => "ate_situacao_vazio"),
								"datepicker" => true,
								"input_hidden" => true,
						),

				) 
		) 
)
;

?>