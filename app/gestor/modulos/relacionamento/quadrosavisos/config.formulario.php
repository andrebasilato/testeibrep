<?php	
		   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_titulo",
		"nome" => "titulo", 
		"nomeidioma" => "form_titulo",
		"tipo" => "input",
		"valor" => "titulo",
		"validacao" => array("required" => "titulo_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_resumo",
		"nome" => "resumo",
		"nomeidioma" => "form_resumo",
		"tipo" => "text",
		"valor" => "resumo",
		"class" => "span8",
		"contador" => 240,
		"idiomacaracteres" => "form_caracteres_restantes",
		"banco" => true,
		"validacao" => array("required" => "resumo_vazio"), 
		"banco_string" => true
	  ),
	  array(
        "id" => "form_tipo_aviso",
        "nome" => "tipo_aviso",
        "nomeidioma" => "form_tipo_aviso",
        "tipo" => "select",
        "array" => "tipo_quadro_aviso", // Array que alimenta o select
        "class" => "span2",
        "valor" => "tipo_aviso",
        "validacao" => array("required" => "tipo_aviso_vazio"),
        //"ajudaidioma" => "form_tipo_aviso_ajuda",
        "banco" => true,
        "banco_string" => true
      ),
	  array(
		"id" => "form_botao_variaveis_imagens", // Id do atributo HTML
		"nome" => "botao_variaveis", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_imagens", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"ajudaidioma" => "variaveis_imagens_ajuda",
		"botao_hide" => true,
		"colunas" => 3,
		"tabela" => array(
						"titulo" 			 	     => "titulo_variaveis_imagens",
						"tabela_nome" 		 	     => "quadros_avisos_imagens", 
						"chave_extrangeira" 	     => "idquadro",
						"chave_primaria" 		     => "idquadro_imagem",
						"flag_identificacao"		 => "I",
						"tabela_colunas" 		     => array("idquadro_imagem","nome"), 
						"tabela_colunas_adicionais"  => array("<a href=\"/$url[0]/$url[1]/$url[2]/$url[3]/visualiza_imagem/\" rel=\"facebox\"><div class=\"btn btn-mini\"><i class=\"icon-picture\"></i></div></a>")
		),
		"class" => "span4" //Class do atributo HTML															
	  ),
	 /* array(
		"id" => "form_botao_variaveis_arquivos", // Id do atributo HTML
		"nome" => "botao_arquivos", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_arquivos", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"ajudaidioma" => "variaveis_arquivos_ajuda",
		"botao_hide" => true,
		"colunas" => 3,
		"tabela" => array(
						"titulo" 			 	     => "titulo_variaveis_arquivos",
						"tabela_nome" 		 	     => "murais_arquivos", 
						"chave_extrangeira" 	     => "idmural",
						"chave_primaria" 		     => "idmural_arquivo",
						"flag_identificacao"		 => "A",
						"tabela_colunas" 		     => array("idmural_arquivo","nome"), 
						"tabela_colunas_adicionais"  => array("<a href=\"/$url[0]/$url[1]/$url[2]/$url[3]/downloadArquivo/\"><div class=\"btn btn-mini\"><i class=\"icon-download-alt\"></i></div></a>")
		),
		"class" => "span4" //Class do atributo HTML															
	  ),*/																														
	  array(
		"id" => "form_descricao",
		"nome" => "descricao",
		"nomeidioma" => "form_descricao",
		"tipo" => "text",
		"editor" => true,
		"valor" => "descricao",
		"class" => "xxlarge",
		"banco" => true,
		"validacao" => array("required" => "descricao_vazio"), 
		"banco_string" => true
	  ),
	  array(
		"id" => "data_de",
		"nome" => "data_de",
		"nomeidioma" => "form_datade",
		"tipo" => "input", 
		"valor" => "data_de",
		"validacao" => array("required" => "data_de_vazio"), 
		"valor_php" => 'if($dados["data_de"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "data_ate",
		"nome" => "data_ate",
		"nomeidioma" => "form_dataate",
		"tipo" => "input", 
		"valor" => "data_ate",
		/*"validacao" => array("required" => "data_ate_vazio"), */
		"valor_php" => 'if($dados["data_ate"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  )																													
	)
  )								  
);
?>