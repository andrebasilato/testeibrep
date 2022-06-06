<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
	array(
		"fieldsetid" => "avatar", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma" => "legenda_avatar", // Legenda do fomrulario (referencia a variavel de idioma)	  
		"campos" => array( // Campos do formulario
			array(
				"id" => "form_avatar", // Id do atributo HTML
				"nome" => "avatar", // Name do atributo HTML
				"nomeidioma" => "form_avatar", // Referencia a variavel de idioma
				"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
				"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
				"tipo" => "file", // Tipo do input
				"extensoes" => 'jpg|jpeg|gif|png|bmp',
				"ajudaidioma" => "form_avatar_ajuda",
				//"largura" => 350,
				//"altura" => 180,
				"validacao" => array("formato_arquivo" => "arquivo_invalido"),
				"class" => "span6", //Class do atributo HTML
				"pasta" => "pessoas_avatar", 
				"download" => true,
				"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"],
				"excluir" => true,
				"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
				"banco_campo" => "avatar", // Nome das colunas da tabela do banco de dados que retorna o valor.
				"ignorarsevazio" => true 
			),																														
		)
	)								  
);