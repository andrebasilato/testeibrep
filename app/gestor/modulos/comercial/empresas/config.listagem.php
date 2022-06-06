<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idempresa",
	"variavel_lang" => "tabela_idempresa", 
	"tipo" => "php", 
	"coluna_sql" => "e.idempresa", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idempresa"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idempresa"]."</span> <i class=\"novo\"></i>";
			}
			', 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),								  
  array(
	"id" => "codigo", 
	"variavel_lang" => "tabela_codigo",
	"tipo" => "banco",
	"evento" => "maxlength='10'",
	"coluna_sql" => "e.codigo",
	"valor" => "codigo",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "e.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "idsindicato", 
	"variavel_lang" => "tabela_idsindicato", 
	"tipo" => "banco", 
	"coluna_sql" => "e.idsindicato", 
	"valor" => "sindicato",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_sql" => "SELECT idsindicato, nome FROM sindicatos where ativo = 'S'", // SQL que alimenta o select
	"busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
	"busca_sql_label" => "nome",
	"busca_metodo" => 1,
	"tamanho" => "100",
	"overflow" => true
  ),
  array(
	"id" => "documento", 
	"variavel_lang" => "tabela_documento",
	"tipo" => "banco",
	"coluna_sql" => "e.documento",
	"valor" => "documento",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "email", 
	"variavel_lang" => "tabela_email",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "e.email",
	"valor" => "email",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),								  
  array(
	"id" => "telefone", 
	"variavel_lang" => "tabela_telefone",
	"tipo" => "banco",
	"evento" => "maxlength='14'",
	"coluna_sql" => "e.telefone",
	"valor" => "telefone",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
   array(
	"id" => "fax", 
	"variavel_lang" => "tabela_fax",
	"tipo" => "banco",
	"evento" => "maxlength='14'",
	"coluna_sql" => "e.fax",
	"valor" => "fax",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),								  
  array(
	"id" => "ativo_painel", 
	"variavel_lang" => "tabela_ativo_painel", 
	"tipo" => "php",
	"coluna_sql" => "e.ativo_painel", 
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
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"tipo" => "php", 
	"coluna_sql" => "data_cad",
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 						
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idempresa"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 			
);						   
?>