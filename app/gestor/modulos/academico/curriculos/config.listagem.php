<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idcurriculo",
	"variavel_lang" => "tabela_idcurriculo", 
	"tipo" => "php", 
	"coluna_sql" => "ca.idcurriculo", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idcurriculo"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idcurriculo"]."</span> <i class=\"novo\"></i>";
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
	"coluna_sql" => "ca.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "idcurso", 
	"variavel_lang" => "tabela_idcurso", 
	"tipo" => "banco", 
	"coluna_sql" => "c.idcurso", 
	"valor" => "curso",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_sql" => "SELECT idcurso, nome FROM cursos where ativo = 'S'", // SQL que alimenta o select
	"busca_sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
	"busca_sql_label" => "nome",
	"busca_metodo" => 1,
	"overflow" => true
  ),
  array(
	"id" => "carga_horaria", 
	"variavel_lang" => "tabela_carga_horaria",
	"tipo" => "banco",
	"evento" => "maxlength='800'",
	"coluna_sql" => "ca.carga_horaria",
	"valor" => "carga_horaria",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),  								  
  array(
	"id" => "ativo_painel", 
	"variavel_lang" => "tabela_ativo_painel", 
	"tipo" => "php",
	"coluna_sql" => "ca.ativo_painel", 
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
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idcurriculo"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 			
);						   
?>