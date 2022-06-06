<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	 /* array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),*/
	  array(
		"id" => "form_nome",
		"nome" => "nome",
		"nomeidioma" => "form_nome",
		"tipo" => "text", 
		"editor" => true,
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  ),
      array(
        "id" => "form_critica",
        "nome" => "critica",
        "nomeidioma" => "form_critica",
        "tipo" => "text",
        "editor" => true,
        "valor" => "critica",
        "class" => "span5",
        "banco" => true,
        "banco_string" => true
      ),
	  array(
		"id" => "form_tipo",
		"nome" => "tipo",
		"nomeidioma" => "form_tipo",
		"botao_hide" => true,
		"iddivs" => array("multipla_escolha","sentido","quantidade_colunas","simulado","exercicio"),
		"tipo" => "select",
		"iddiv" => "multipla_escolha",
		"iddiv2" => "sentido",
		"iddiv3" => "quantidade_colunas",
		"iddiv4" => "simulado",
		"iddiv5" => "exercicio",
		"array" => "tipo_pergunta", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "tipo",
		"validacao" => array("required" => "tipo_vazio"),
		"ajudaidioma" => "form_tipo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),	
	  array(
		"id" => "form_multipla_escolha",
		"nome" => "multipla_escolha",
		"nomeidioma" => "form_multipla_escolha",
		"tipo" => "select",
		"botao_hidden" => true,
		"select_hidden" => true,
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "multipla_escolha",
		"validacao" => array("required" => "multipla_escolha_vazio"),
		"ajudaidioma" => "form_multipla_escolha_ajuda",
		"banco" => true,
		"nao_nulo" => true,
		"campo_hidden" => true,
		"classe_label" => "control-label",
		"banco_string" => true															
	  ),
	  array(
		"id" => "form_sentido",
		"nome" => "sentido",
		"nomeidioma" => "form_sentido",
		"tipo" => "select",
		"array" => "sentido_pergunta", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "sentido",
		//"validacao" => array("required" => "sentido_vazio"),
		"ajudaidioma" => "form_sentido_ajuda",
		"banco" => true,
		"select_hidden" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_exercicio",
		"nome" => "exercicio",
		"nomeidioma" => "form_exercicio",
		"tipo" => "select",
		"botao_hidden" => true,
		"select_hidden" => true,
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "exercicio",
		"validacao" => array("required" => "exercicio_vazio"),
		"ajudaidioma" => "form_exercicio_ajuda",
		"banco" => true,
		"nao_nulo" => true,
		"banco_string" => true,
		"campo_hidden" => true															
	  ),
	  array(
		"id" => "form_simulado",
		"nome" => "simulado",
		"nomeidioma" => "form_simulado",
		"tipo" => "select",
		"botao_hidden" => true,
		"select_hidden" => true,
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "simulado",
		"validacao" => array("required" => "simulado_vazio"),
		"ajudaidioma" => "form_simulado_ajuda",
		"banco" => true,
		"nao_nulo" => true,
		"banco_string" => true,
		"campo_hidden" => true															
	  ),
	  array(
		"id" => "form_quantidade_colunas",
		"nome" => "quantidade_colunas", 
		"nomeidioma" => "form_quantidade_colunas",
		"tipo" => "input",
		"valor" => "quantidade_colunas",
		"class" => "span2",
		"numerico" => true,
		"input_hidden" => true,
		"banco" => true,
	  ),
	  array(
		"id" => "form_espacamento_esquerda",
		"nome" => "espacamento_esquerda", 
		"nomeidioma" => "form_espacamento_esquerda",
		"tipo" => "input",
		"valor" => "espacamento_esquerda",
		"ajudaidioma" => "espacamento_esquerda_ajuda",
		"class" => "span2",
		"numerico" => true,
		"banco" => true,
	  ),
	   array(
		"id" => "form_permite_anexo_resposta",
		"nome" => "permite_anexo_resposta",
		"nomeidioma" => "form_permite_anexo_resposta",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "permite_anexo_resposta",
		//"validacao" => array("required" => "permite_anexo_resposta_vazio"),
		"ajudaidioma" => "form_permite_anexo_resposta_ajuda",
		"banco" => true,
		"banco_string" => true															
	  ),
	  array(
		"id" => "form_dificuldade",
		"nome" => "dificuldade",
		"nomeidioma" => "form_dificuldade",
		"tipo" => "select",
		"array" => "dificuldade_pergunta", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "dificuldade",
		"validacao" => array("required" => "dificuldade_vazio"),
		"ajudaidioma" => "form_dificuldade_ajuda",
		"banco" => true,
		"banco_string" => true															
	  ),
	  array(
		"id" => "form_avaliacao_virtual",
		"nome" => "avaliacao_virtual",
		"nomeidioma" => "form_avaliacao_virtual",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "avaliacao_virtual",
		"validacao" => array("required" => "avaliacao_virtual_vazio"),
		"ajudaidioma" => "form_avaliacao_virtual_ajuda",
		"banco" => true,
		"banco_string" => true															
	  ),
	  array(
		"id" => "form_avaliacao_presencial",
		"nome" => "avaliacao_presencial",
		"nomeidioma" => "form_avaliacao_presencial",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "avaliacao_presencial",
		"validacao" => array("required" => "avaliacao_presencial_vazio"),
		"ajudaidioma" => "form_avaliacao_presencial_ajuda",
		"banco" => true,
		"banco_string" => true															
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
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "disciplinas_perguntas_imagens", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "imagem", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
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
	array(
      'id' => 'iddisciplina',
      'nome' => 'iddisciplina',
      'tipo' => 'hidden',
      'banco' => true,
      'banco_string' => true
    ),																															

	)
  )								  
);