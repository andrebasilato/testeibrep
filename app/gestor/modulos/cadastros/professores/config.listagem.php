<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idprofessor",
	"variavel_lang" => "tabela_idprofessor", 
	"tipo" => "php", 
	"coluna_sql" => "p.idprofessor", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idprofessor"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idprofessor"]."</span> <i class=\"novo\"></i>";
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
	"coluna_sql" => "p.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  /*
  array(
	"id" => "cidade", 
	"variavel_lang" => "tabela_cidade", 
	"tipo" => "banco", 
	"coluna_sql" => "c.nome", 
	"valor" => "cidade",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2,
	"tamanho" => "100",
	"overflow" => true
  ), 
  array(
	"id" => "estado", 
	"variavel_lang" => "tabela_estado", 
	"tipo" => "banco", 
	"coluna_sql" => "e.nome", 
	"valor" => "estado",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2,
	"tamanho" => "100",
	"overflow" => true
  ), 
  */  
  array(
	"id" => "documento", 
	"variavel_lang" => "tabela_documento",
	"tipo" => "banco",
	"coluna_sql" => "p.documento",
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
	"coluna_sql" => "p.email",
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
	"coluna_sql" => "p.telefone",
	"valor" => "telefone",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "celular", 
	"variavel_lang" => "tabela_celular",
	"tipo" => "banco",
	"evento" => "maxlength='14'",
	"coluna_sql" => "p.celular",
	"valor" => "celular",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
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
	"id" => "ativo_login", // Id do atributo
	"variavel_lang" => "tabela_situacao", // Referencia a variavel de idioma
	"tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
	"coluna_sql" => "p.ativo_login", 
	"valor" => 'if($linha["ativo_login"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "ativo",
	"busca_metodo" => 1
  ), // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
   array(
	"id" => "tipo", // Id do atributo
	"variavel_lang" => "tabela_tipo", // Referencia a variavel de idioma
	"tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
	"coluna_sql" => "p.tipo", 
	"valor" => 'if($linha["tipo"] == "P") {
				  return $idioma["monitor_online"];
				} else {
				  return $idioma["tutor_online"];
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "tipo_professor_config",
	"busca_metodo" => 1
  ),  
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idprofessor"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 			
);						   
?>