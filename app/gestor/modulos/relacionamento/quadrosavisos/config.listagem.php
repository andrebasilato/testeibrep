<?php
$config["listagem"] = array(
	array(
		"id" => "idquadro",
	  	"variavel_lang" => "tabela_idquadro", 
		"tipo" => "php", 
		"coluna_sql" => "idquadro", 
		"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idquadro"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idquadro"]."</span> <i class=\"novo\"></i>";
			}
			', 
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 1,
		"tamanho" => 80
	),						  
	array(
		"id" => "titulo", 
		"variavel_lang" => "tabela_titulo",
		"tipo" => "banco",
		"evento" => "maxlength='100'",
		"coluna_sql" => "titulo",
		"valor" => "titulo",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),
	array(
		"id" => "opcoes", 
		"variavel_lang" => "tabela_opcoes", 
		"tipo" => "php", 
		"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idquadro"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
		"busca_botao" => true,
		"tamanho" => "80"
	)   
);
?>