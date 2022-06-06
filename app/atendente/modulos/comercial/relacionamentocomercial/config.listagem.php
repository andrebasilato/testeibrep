<?
$config["config_listagem"] = array(
	array(
		"id" => "idrelacionamento",
	  	"variavel_lang" => "tabela_idrelacionamento", 
		"tipo" => "php", 
		"coluna_sql" => "rc.idrelacionamento", 
		"valor" => '
					$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d"), "H");
					if($diferenca > 24) {
						return "<span title=\"$diferenca\">".$linha["idrelacionamento"]."</span>";
					} else {
						return "<span title=\"$diferenca\">".$linha["idrelacionamento"]."</span> <i class=\"novo\"></i>";
					}
					',  
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 80
	),
	array(
		"id" => "email_pessoa", 
		"variavel_lang" => "tabela_email_pessoa",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "rc.email_pessoa",
		"valor" => 'email_pessoa',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "nome_pessoa", 
		"variavel_lang" => "tabela_pessoa",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "rc.nome_pessoa",
		"valor" => 'nome_pessoa',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	
	array("id" => "proxima_acao", 
		"variavel_lang" => "tabela_proxima_acao", 
		"coluna_sql" => "proxima_acao",
		"tipo" => "php", 
		"valor" => 'return formataData($linha["proxima_acao"],"br",0);',
		"tamanho" => "100",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 7
	),
	
	array("id" => "data_cad", 
		"variavel_lang" => "tabela_datacad", 
		"coluna_sql" => "data_cad",
		"tipo" => "php", 
		"valor" => 'return formataData($linha["data_cad"],"br",1);',
		"tamanho" => "140"
	),
	array("id" => "ativo_painel", 
		"variavel_lang" => "tabela_ativo_painel", 
		"tipo" => "php",
		"coluna_sql" => "rc.ativo_painel", 
		"valor" => 'if($linha["ativo_painel"] == "S") {
						  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
					  } else {
						  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
					  }',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "ativo",
		"busca_metodo" => 1,
		"tamanho" => 60
	),	
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_opcoes", 
		"tipo" => "php", 
		"valor" => 'return "<a href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idrelacionamento"]."/administrar\" class=\"btn dropdown-toggle btn-mini\">".$idioma["btn_administrar"]."</a> ";',
		"busca_botao" => true,
		"tamanho" => "110"
	)   
);						   
?>