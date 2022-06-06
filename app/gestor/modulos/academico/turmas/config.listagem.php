<?php
// Array de configuração para a listagem	
$config["listagem"] = array(

  array(
	"id" => "idoferta",
	"variavel_lang" => "tabela_idoferta", 
	"tipo" => "php", 
	"coluna_sql" => "o.idoferta", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad_oferta"], date("Y-m-d"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idoferta"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idoferta"]."</span> <i class=\"novo\"></i>";
			}
			', 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),								  
  array(
	"id" => "oferta", 
	"variavel_lang" => "tabela_oferta",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "o.nome",
	"valor" => "oferta",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),

  array(
	"id" => "idturma",
	"variavel_lang" => "tabela_idturma", 
	"tipo" => "php", 
	"coluna_sql" => "ot.idturma", 
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idturma"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idturma"]."</span> <i class=\"novo\"></i>";
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
	"coluna_sql" => "ot.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),	
   array(
	"id" => "matriculas", 
	"variavel_lang" => "tabela_matriculas",
	"tipo" => "php",
	"valor" => 'return "<span>".intval($linha["matriculas"])."</span>";',
	"busca" => false
  ), 						
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/relatorios/matriculas_relatorio/html?q[1|ma.idturma]=".$linha["idturma"]."\" data-placement=\"left\" rel=\"tooltip\" target=\"_blank\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 			
);						   
?>