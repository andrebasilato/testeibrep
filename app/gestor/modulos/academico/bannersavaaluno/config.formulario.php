<?php
// Array de configuração para a formulario
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_nome",
		"nome" => "nome",
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"),
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_imagem", // Id do atributo HTML
		"nome" => "imagem", // Name do atributo HTML
		"nomeidioma" => "form_imagem", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp',
		"ajudaidioma" => "form_imagem_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido", "file_required" => "arquivo_vazio"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "bannersavaaluno_imagem",
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "imagem", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true
	  ),
	  array(
		"id" => "form_link",
		"nome" => "link",
		"nomeidioma" => "form_link",
		"tipo" => "input",
		"valor" => "link",
		//"validacao" => array("required" => "nome_vazio"),
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_cor_background",
		"nome" => "cor_background",
		"nomeidioma" => "form_cor_background",
		"tipo" => "input",
		"valor" => "cor_background",
		"ajudaidioma" => "form_cor_fundo_ajuda",
		"class" => "span2",
		"banco" => true,
		"colorpicker" => true,
		"banco_string" => true,
	  ),
	  array(
			"id" => "periodo_exibicao_de",
			"nome" => "periodo_exibicao_de",
			"nomeidioma" => "form_periodo_exibicao_de",
			"tipo" => "input",
			"valor" => "periodo_exibicao_de",
			"valor_php" => 'if($dados["periodo_exibicao_de"]) return formataData("%s", "br", 0)',
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true,
			"banco" => true,
			"banco_php" => 'return formataData("%s", "en", 0)',
			"banco_string" => true
	  	),
	  array(
			"id" => "periodo_exibicao_ate",
			"nome" => "periodo_exibicao_ate",
			"nomeidioma" => "form_periodo_exibicao_ate",
			"tipo" => "input",
			"valor" => "periodo_exibicao_ate",
			"valor_php" => 'if($dados["periodo_exibicao_ate"]) return formataData("%s", "br", 0)',
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true,
			"banco" => true,
			//"validacao" => array("required" => "periodo_exibicao_ate_vazio"),
			"banco_php" => 'return formataData("%s", "en", 0)',
			"banco_string" => true
	  	),
	  array(
			"id" => "form_hora_de",
			"nome" => "hora_de",
			"nomeidioma" => "form_hora_de",
			"tipo" => "input",
			"valor" => "hora_de",
			"mascara" => "99:99",
			"class" => "span2",
			"banco" => true,
			"banco_string" => true,
	  	),
	  	array(
			"id" => "form_hora_ate",
			"nome" => "hora_ate",
			"nomeidioma" => "form_hora_ate",
			"tipo" => "input",
			"valor" => "hora_ate",
			"mascara" => "99:99",
			"class" => "span2",
			"banco" => true,
			"banco_string" => true,
	  	),
		 array(
			"id" => "dias",
			"nome" => "dias",
			"nomeidioma" => "form_dias",
			"tipo" => "checkbox",
			"array" => "dia_semana",
			"banco" => false,
			"array_serializado" => "dias"
		),
		array(
			"id" => "painel_aluno",
			"nome" => "painel_aluno",
			"nomeidioma" => "form_painel_aluno",
			"tipo" => "select",
			"array" => "ativo",
			"class" => "span2",
			"validacao" => array("required" => "painel_aluno_vazio"),
			"valor" => "painel_aluno",
			"banco" => true,
			"banco_string" => true
		),
		array(
			"id" => "painel_cfc",
			"nome" => "painel_cfc",
			"nomeidioma" => "form_painel_cfc",
			"tipo" => "select",
			"array" => "ativo",
			"class" => "span2",
			"validacao" => array("required" => "painel_cfc_vazio"),
			"valor" => "painel_cfc",
			"banco" => true,
			"banco_string" => true
		),
		array(
			"id" => "painel_atendente",
			"nome" => "painel_atendente",
			"nomeidioma" => "form_painel_atendente",
			"tipo" => "select",
			"array" => "ativo",
			"class" => "span2",
			"validacao" => array("required" => "painel_atendente_vazio"),
			"valor" => "painel_atendente",
			"banco" => true,
			"banco_string" => true
		),
	  array(
		"id" => "form_ativo_painel",
		"nome" => "ativo_painel",
		"nomeidioma" => "form_ativo_painel",
		"tipo" => "select",
		"array" => "ativo", // Array que alimenta o select
		"class" => "span2",
		"valor" => "ativo_painel",
		"validacao" => array("required" => "ativo_vazio"),
		"ajudaidioma" => "form_ativo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	)
  )
);
?>
