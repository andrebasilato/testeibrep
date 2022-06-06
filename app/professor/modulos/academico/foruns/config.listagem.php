<?php
// Array de configuração para a listagem
$config["listagem"] = array(
  array(
	"id" => "idforum",
	"variavel_lang" => "tabela_idforum", 
	"tipo" => "banco", 
	"coluna_sql" => "idforum", 
	"valor" => "idforum", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "f.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
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
    "id" => "disciplina", 
    "variavel_lang" => "tabela_disciplina",
    "tipo" => "php",
    "evento" => "maxlength='100'",
    "coluna_sql" => "d.nome",
    "valor" => 'if ($linha["disciplina"]) {
                    return $linha["disciplina"];
                } else {
                    return "--";
                }',
    "busca" => false,
    "busca_class" => "inputPreenchimentoCompleto",
    "busca_metodo" => 2
  ),
  array(
	"id" => "total_topicos", 
	"variavel_lang" => "tabela_total_topicos", 
	"tipo" => "banco",
	"valor" => "total_topicos",
	"tamanho" => "60"
  ),
  array(
	"id" => "total_respostas", 
	"variavel_lang" => "tabela_total_respostas",
	"tipo" => "banco",
	"valor" => 'total_respostas',
	"tamanho" => "60"
  ), 								  				
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"coluna_sql" => "f.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php",
	"valor" => 'return "<a href=\"javascript:void(0)\" class=\"btn btn-mini\" onclick=\"window.open(\'/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idforum"]."/topicos\',\'foruns\',\'scrollbars=yes,width=1000,height=600\').focus();\">".$idioma["btn_acessar"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
);
?>