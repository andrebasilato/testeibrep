<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["listagem"] = array(
	array(
		"id" => "protocolo", 
		"variavel_lang" => "tabela_protocolo",
		"tipo" => "banco",
		"valor" => 'protocolo',	
		"busca_class" => "inputPreenchimentoCompleto",
	),
	array(
		"id" => "idmatricula",
		"variavel_lang" => "tabela_idmatricula", 
		"tipo" => "banco", 
		"valor" => "idmatricula", 
		"busca_class" => "inputPreenchimentoCompleto",
	),
	array(
		"id" => "aluno",
		"variavel_lang" => "tabela_aluno", 
		"tipo" => "banco", 
		"valor" => "aluno", 
		"busca_class" => "inputPreenchimentoCompleto",
	),
	array(
		"id" => "tipo",
		"variavel_lang" => "tabela_tipo", 
		"tipo" => "banco", 
		"valor" => "tipo",
		"busca_class" => "inputPreenchimentoCompleto",
	),
	array(
		"id" => "data_cad",
		"variavel_lang" => "tabela_data_cad", 
		"tipo" => "php",
		'valor' => 'return formataData($linha["data_cad"],"br",1);',
		"busca_class" => "inputPreenchimentoCompleto",
	),
	array(
		"id" => "quem_cadastrou", 
		"variavel_lang" => "tabela_quem_cadastrou",
		"tipo" => "php",
		'valor' => 'if ($linha["aluno_acadastrou"]) {
						return "Aluno: ".$linha["aluno_acadastrou"];
					} else {
						return "Usuário: ".$linha["usuario_cadastrou"];
					}',
		"busca_class" => "inputPreenchimentoCompleto",
	)			
);

						
// Array de configuração para a formulario			
$config["formulario"] = array(
	array(
		"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
		"campos" => array( 																		
			array(
				"id" => "protocolo",
				"nome" => "q[1|md.protocolo]",
				"nomeidioma" => "form_protocolo",
				"tipo" => "input",
				"validacao" => array("required" => "protocolo_vazio"),										
			),

			array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|md.data_cad]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'iddiv' => 'de',
                'iddiv2' => 'ate',
                'iddivs' => array(
                    'de',
                    'ate'
                ),
                //'iddiv_obr' => true,
                'nomeidioma' => 'form_tipo_data_filtro',
                'botao_hide' => true,
                //'iddiv2_obr' => true,
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
                'nomeidioma' => 'form_de',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				//"validacao" => array("required" => "de_vazio"),
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
                'nomeidioma' => 'form_ate',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				//"validacao" => array("required" => "ate_vazio"),
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
            ),
		)
	)					  
);