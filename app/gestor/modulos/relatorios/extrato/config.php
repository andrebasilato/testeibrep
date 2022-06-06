<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	

$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_idsindicato",
		"nome" => "q[1|c.idsindicato]",
		"nomeidioma" => "form_idsindicato",
		"tipo" => "select",
		"sql" => $sqlSindicato, // SQL que alimenta o select
		"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
		"valor" => "idsindicato",
		"sql_filtro" => "select * from sindicatos where idsindicato = %",
		"sql_filtro_label" => "nome_abreviado",
		"class" => "span4",
	  ),
	  array(
		"id" => "idbanco",
		"nome" => "q[1|cc.idbanco]",
		"nomeidioma" => "form_idbanco",
		"tipo" => "select",
		"sql" => "select idbanco, nome from bancos where ativo = 'S'", // SQL que alimenta o select
		"sql_valor" => "idbanco", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idbanco",
		"class" => "span3",
		"sql_filtro" => "select idbanco, nome from bancos where idbanco = %",
		"sql_filtro_label" => "nome",																
	  ), 
	  array(
		"id" => "form_idconta_corrente",
		"nome" => "q[1|c.idconta_corrente]",
		"nomeidioma" => "form_idconta_corrente",
		"json" => true,
		"json_idpai" => "idbanco",
		"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_contas_correntes/",
		"json_input_pai_vazio" => "form_selecione_banco",
		"json_input_vazio" => "form_selecione_conta_corrente",
		"json_campo_exibir" => "nome",
		"tipo" => "select",
		"valor" => "idconta_corrente",
		"sql_filtro" => "select * from contas_correntes where idconta_corrente = %",
		"sql_filtro_label" => "nome",																
	  ),
	  array(
		"id" => "form_agencia",
		"nome" => "q[1|cc.agencia]",
		"nomeidioma" => "form_agencia",
		"tipo" => "input",
		"class" => "span2",
		"input_tipo" => "number",

	  ),
	  array(
		"id" => "form_filtro_data_vencimento",
		"nome" => "filtro_data_vencimento",
		"nomeidioma" => "form_filtro_data_vencimento",
		"botao_hide" => true,
		"tipo" => "select",
		"iddiv" => "de_data_vencimento",
		"iddiv2" => "ate_data_vencimento",
		"iddiv_obr" => true,
		"iddiv2_obr" => true,
		"array" => "tipo_data_filtro", // Array que alimenta o select
		"class" => "span3",
		"valor" => "tipo_data_filtro",
		//"validacao" => array("required" => "filtro_data_vencimento_vazio"),
		"banco" => true,
		"banco_string" => true,
		"sql_filtro" => "array",
		"sql_filtro_label" => "tipo_data_filtro"
	  ),
	  array(
		"id" => "form_de_data_vencimento",
		"nome" => "de_data_vencimento",
		"nomeidioma" => "form_de_data_vencimento",
		"tipo" => "input",
		"class" => "span2",
		//"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_vencimento\",\"form_ate_data_vencimento\")'",
		//"validacao" => array("required" => "de_data_vencimento_vazio"),
		"datepicker" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_ate_data_vencimento",
		"nome" => "ate_data_vencimento",
		"nomeidioma" => "form_ate_data_vencimento",
		"tipo" => "input",
		"class" => "span2",
		//"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_vencimento\",\"form_ate_data_vencimento\")'",
		//"validacao" => array("required" => "ate_data_vencimento_vazio"),
		"datepicker" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_filtro_data_pagamento",
		"nome" => "filtro_data_pagamento",
		"nomeidioma" => "form_filtro_data_pagamento",
		"botao_hide" => true,
		"tipo" => "select",
		"iddiv" => "de_data_pagamento",
		"iddiv2" => "ate_data_pagamento",
		"iddiv_obr" => false,
		"iddiv2_obr" => false,
		"array" => "tipo_data_filtro", // Array que alimenta o select
		"class" => "span3",
		"valor" => "tipo_data_filtro",
		//"validacao" => array("required" => "filtro_data_pagamento_vazio"),
		"banco" => true,
		"banco_string" => true,
		"sql_filtro" => "array",
		"sql_filtro_label" => "tipo_data_filtro"
	  ),
	  array(
		"id" => "form_de_data_pagamento",
		"nome" => "de_data_pagamento",
		"nomeidioma" => "form_de_data_pagamento",
		"tipo" => "input",
		"class" => "span2",
		//"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_pagamento\",\"form_ate_data_pagamento\")'",
		//"validacao" => array("required" => "de_data_pagamento_vazio"),
		"datepicker" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_ate_data_pagamento",
		"nome" => "ate_data_pagamento",
		"nomeidioma" => "form_ate_data_pagamento",
		"tipo" => "input",
		"class" => "span2",
		//"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_data_pagamento\",\"form_ate_data_pagamento\")'",
		//"validacao" => array("required" => "ate_data_pagamento_vazio"),
		"datepicker" => true,
		"input_hidden" => true,
	  )
	)
  )
);	
							
?>