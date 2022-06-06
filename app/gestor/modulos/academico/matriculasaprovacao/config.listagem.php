<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
  array(
	"id" => "idmatricula",
	"variavel_lang" => "tabela_matricula", 
	"tipo" => "php", 
	"coluna_sql" => "ma.idmatricula", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span> <i class=\"novo\"></i>";
			}
			',  
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),

  array(
	"id" => "numero_contrato", 
	"variavel_lang" => "tabela_numero_contrato",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "numero_contrato",
	"valor" => "numero_contrato",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ), 
  
  array(
	"id" => "idpessoa", 
	"variavel_lang" => "tabela_codaluno",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "pe.idpessoa",
	"valor" => "idpessoa",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),  								  
  array(
	"id" => "aluno", 
	"variavel_lang" => "tabela_aluno",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "pe.nome",
	"valor" => "aluno",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "oferta", 
	"variavel_lang" => "tabela_oferta", 
	"tipo" => "banco", 
	"coluna_sql" => "of.nome", 
	"valor" => "oferta",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "curso", 
	"variavel_lang" => "tabela_curso", 
	"tipo" => "banco", 
	"coluna_sql" => "cu.nome", 
	"valor" => "curso",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "sindicato", 
	"variavel_lang" => "tabela_sindicato", 
	"tipo" => "banco", 
	"coluna_sql" => "i.nome_abreviado", 
	"valor" => "sindicato_sigla",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "escola", 
	"variavel_lang" => "tabela_escola", 
	"tipo" => "banco", 
	"coluna_sql" => "po.nome_fantasia", 
	"valor" => "escola",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ), 					

  array(
	"id" => "aprovar", 
	"variavel_lang" => "tabela_aprovar",
	"tipo" => "php",
	"valor" => '
				return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/aprovar\" data-placement=\"left\" rel=\"tooltip facebox\"><i class=\"icon-eye-open\"></i> ".$idioma["tabela_aprovar"]."</a>"
				'
  ),
 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/matriculas/".$linha["idmatricula"]."/administrar\" data-placement=\"left\" rel=\"tooltip\" target=\"_blank\">".$idioma["tabela_abrir"]."</a>"',//#documentosmatricula
	"busca_botao" => true,
	"tamanho" => "100"
  )
);						   
?>