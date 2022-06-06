<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idava",
	"variavel_lang" => "tabela_idava", 
	"tipo" => "php", 
	"coluna_sql" => "a.idava", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idava"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idava"]."</span> <i class=\"novo\"></i>";
			}
			', 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),								  
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "a.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ), 								  								  
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"tipo" => "php", 
	"coluna_sql" => "a.data_cad",
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
  		"id" => "opcoes", 
        "variavel_lang" => "tabela_opcoes", 
        "tipo" => "php", 
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idava"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";',
        "busca_botao" => true,
        "tamanho" => "100"
   )				
);