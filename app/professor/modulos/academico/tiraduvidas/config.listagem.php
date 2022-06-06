<?php
// Array de configuração para a listagem
$config["listagem"] = array(
  array(
	"id" => "idmensagem_instantanea",
	"variavel_lang" => "tabela_idtiraduvida", 
	"tipo" => "banco", 
	"coluna_sql" => "ami.idmensagem_instantanea", 
	"valor" => "idmensagem_instantanea", 
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
	"valor" => "aluno",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 3
  ),
  array(
	"id" => "ava", 
	"variavel_lang" => "tabela_ava",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "a.nome",
	"valor" => "ava",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),							  				
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"coluna_sql" => "ami.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ),
  array(
	"id" => "sinalizador_professor",
	"variavel_lang" => "tabela_sinalizador", 
	"tipo" => "php", 
	"coluna_sql" => "sinalizador_professor", 
	"valor" => "sinalizador_professor", 
	"valor" => '
	if($linha["sinalizador_professor"] == "S") {
				return "<div><img src=\"/assets/img/sinalizador_16.png\" width=\"16\" height=\"16\" style=\"text-align:center\" /></div>";
	} else {
				return NULL;
	}
			', 	
	
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),  
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php",
	"valor" => 'return "<a href=\"javascript:void(0)\" class=\"btn btn-mini\" onclick=\"window.open(\'/".$this->url["0"]."/".$this->url["1"]."/avas/".$linha["idava"]."/mensagem_instantanea/".$linha["idmensagem_instantanea"]."\',\'mensagem_instantanea\',\'scrollbars=yes,width=1000,height=600\').focus();\">".$idioma["btn_acessar"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
);
?>