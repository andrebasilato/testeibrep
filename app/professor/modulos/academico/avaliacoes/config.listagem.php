<?php
// Array de configuração para a listagem
$config["listagem"] = array(
  array(
	"id" => "aluno",
	"variavel_lang" => "tabela_aluno",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "p.nome",
	"valor" => "aluno",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "disciplina",
	"variavel_lang" => "tabela_disciplina",
	"tipo" => "banco",
	"coluna_sql" => "d.nome",
	"valor" => "disciplina",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "prova",
	"variavel_lang" => "tabela_prova",
	"tipo" => "banco",
	"coluna_sql" => "aa.nome",
	"valor" => "avaliacao",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "data_realizada",
	"variavel_lang" => "tabela_data_realizada",
	"tipo" => "php",
	"coluna_sql" => "ma.fim",
	"valor" => 'return formataData($linha["fim"],"br",1);',
	"tamanho" => "140"
  ),
  array(
	"id" => "corrigida", // Id do atributo
	"variavel_lang" => "tabela_corrigida", // Referencia a variavel de idioma
	"tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
	"coluna_sql" => "ma.prova_corrigida",
	"valor" => 'if($linha["prova_corrigida"] == "S") {
				  return "<span data-original-title=\"".$idioma["sim"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">S</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["nao"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">N</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "sim_nao",
	"busca_metodo" => 1
  ),
  array(
	"id" => "data_correcao",
	"variavel_lang" => "tabela_data_correcao",
	"tipo" => "php",
	"coluna_sql" => "ma.data_correcao",
	"valor" => 'return formataData($linha["data_correcao"],"br",1);',
	"tamanho" => "120"
  ),
  array(
	"id" => "professor_correcao",
	"variavel_lang" => "tabela_professor_correcao",
	"tipo" => "php",
	"coluna_sql" => "prof.nome",
	"valor" => 'if($linha["prova_corrigida"] == "S")
					if($linha["professor_correcao"])
						return $linha["professor_correcao"];
					else
						return "SISTEMA";
				',
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "nota",
	"variavel_lang" => "tabela_nota",
	"tipo" => "banco",
	"evento" => "maxlength='10'",
	"coluna_sql" => "ma.nota",
	"valor" => "nota",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2,
	"tamanho" => 20
  ),

  array(
	"id" => "opcoes",
	"variavel_lang" => "tabela_opcoes",
	"tipo" => "php",
	"valor" => '
				if($linha["prova_corrigida"] == "S")
					$corrigir = "Recorrigir";
				else
					$corrigir = "Corrigir";
					
				if($linha["idprofessor"] || $linha["prova_corrigida"] == "N")
					return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_dossie_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idprova"]."/visualizar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_visualizar"]."</a>
					&nbsp;
					<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_administrar_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idprova"]."/corrigir\" data-placement=\"left\" rel=\"tooltip\">".$corrigir."</a>";
				else
					return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_dossie_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idprova"]."/visualizar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_visualizar"]."</a>";
			   ',
	"busca_botao" => true,
	"tamanho" => "180"
  ),
);
?>