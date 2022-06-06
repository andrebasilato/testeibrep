<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        'nome' => 'perguntas[]',
        'nomeidioma' => 'form_perguntas',
        'tipo' => 'php',
        'tamanho' => '20',
        "valor" => '
            return \'<input name="perguntas[]" type="checkbox" value="\'.$linha["idpergunta"].\'" >\';
            ',
    ),
  array(
	"id" => "idpergunta",
	"variavel_lang" => "tabela_idpergunta", 
	"tipo" => "banco", 
	"coluna_sql" => "idpergunta", 
	"valor" => "idpergunta", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),								  
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "php",
	"evento" => "maxlength='100'",
	"coluna_sql" => "nome",
	//"valor" => "nome",
	"valor" => 'return tamanhoTexto(100,$linha["nome"]);',
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),								  
  array(
	"id" => "tipo", 
	"variavel_lang" => "tabela_tipo", 
	"tipo" => "php",
	"coluna_sql" => "tipo", 
	"valor" => 'if($linha["tipo"] == "O") {
				  return "<span class=\"label label-success\">".$idioma["objetiva"]."</span>";
				} else {
				  return "<span class=\"label label-important\">".$idioma["subjetiva"]."</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "tipo_pergunta",
	"busca_metodo" => 1,
	"tamanho" => 70
  ),
  array(
	"id" => "ativo_painel", 
	"variavel_lang" => "tabela_ativo_painel", 
	"tipo" => "php",
	"coluna_sql" => "ativo_painel", 
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
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idpergunta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 			
);						   
?>