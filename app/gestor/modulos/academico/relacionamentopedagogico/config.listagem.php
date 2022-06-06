<?
$config["listagem_mensagens"] = array(
	array(
		"id" => "idmensagem",
	  	"variavel_lang" => "tabela_idmensagem", 
		"tipo" => "php", 
		"coluna_sql" => "rp.idmensagem", 
		"valor" => '
					$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
					if($diferenca > 24) {
						return "<span title=\"$diferenca\">".$linha["idmensagem"]."</span>";
					} else {
						return "<span title=\"$diferenca\">".$linha["idmensagem"]."</span> <i class=\"novo\"></i>";
					}
					',  
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 80
	),
	array(
		"id" => "idpessoa",
	  	"variavel_lang" => "tabela_idpessoa", 
		"tipo" => "banco", 
		"coluna_sql" => "rp.idpessoa", 
		"valor" => "idpessoa", 
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 60
	),
	array(
		"id" => "pessoa", 
		"variavel_lang" => "tabela_pessoa",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "p.nome",
		"valor" => 'pessoa',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "data_prox_acao", 
		"variavel_lang" => "tabela_prox_acao", 
		"tipo" => "php", 
		"coluna_sql" => "rp.proxima_acao",
		"valor" => 'return formataData($linha["proxima_acao"],"br",0);',
		"tamanho" => "140"
  	),
	array(
		"id" => "usuario_responsavel", 
		"variavel_lang" => "tabela_usuario_responsavel",
		"tipo" => "php",
		"evento" => "maxlength='100'",
		"coluna_sql" => "ua.nome",
		"valor" => 'if ($linha["usuario"]) {
						return $linha["usuario"];
					} else {
						return "---";
					}',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	/*array(
		"id" => "vendedor_responsavel", 
		"variavel_lang" => "tabela_vendedor_responsavel",
		"tipo" => "php",
		"evento" => "maxlength='100'",
		"coluna_sql" => "v.nome",
		"valor" => 'if ($linha["vendedor"]) {
						return $linha["vendedor"];
					} else {
						return "---";
					}',
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),*/	
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_opcoes", 
		"tipo" => "php", 
		"valor" => 'return "<a href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/administrar/".$linha["idpessoa"]."\" class=\"btn dropdown-toggle btn-mini\">".$idioma["btn_administrar"]."</a> ";',
		"busca_botao" => true,
		"tamanho" => "110"
	)   
);						   
?>