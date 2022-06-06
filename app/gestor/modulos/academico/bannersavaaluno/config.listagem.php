<?php
// Array de configuração para a listagem
$config["listagem"] = array(
  array(
	"id" => "idbanner",
	"variavel_lang" => "tabela_idbanner",
	"tipo" => "php",
	"coluna_sql" => "idbanner",
	"valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idbanner"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idbanner"]."</span> <i class=\"novo\"></i>";
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
	"coluna_sql" => "nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array("id" => "periodo_exibicao_de",
		"variavel_lang" => "tabela_periodo_exibicao_de",
		"coluna_sql" => "periodo_exibicao_de",
		"tipo" => "php",
		"valor" => 'return formataData($linha["periodo_exibicao_de"],"br",0);',
		"tamanho" => "100",
		"busca" => false,
		//"busca_class" => "inputPreenchimentoCompleto",
		//"busca_metodo" => 3
  ),
  array("id" => "periodo_exibicao_ate",
		"variavel_lang" => "tabela_periodo_exibicao_ate",
		"coluna_sql" => "periodo_exibicao_ate",
		"tipo" => "php",
		"valor" => 'return formataData($linha["periodo_exibicao_ate"],"br",0);',
		"tamanho" => "100",
		"busca" => false,
		//"busca_class" => "inputPreenchimentoCompleto",
		//"busca_metodo" => 3
  ),
	array(
		"id" => "painel_aluno",
		"variavel_lang" => "tabela_painel_aluno",
		"tipo" => "php",
		"coluna_sql" => "painel_aluno",
		"valor" => 'if($linha["painel_aluno"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo_aluno"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo_aluno"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "ativo",
		"busca_metodo" => 1,
		"tamanho" => 60
	),
	array(
		"id" => "painel_cfc",
		"variavel_lang" => "tabela_painel_cfc",
		"tipo" => "php",
		"coluna_sql" => "painel_cfc",
		"valor" => 'if($linha["painel_cfc"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo_cfc"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo_cfc"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "ativo",
		"busca_metodo" => 1,
		"tamanho" => 60
	),
	array(
		"id" => "painel_atendente",
		"variavel_lang" => "tabela_painel_atendente",
		"tipo" => "php",
		"coluna_sql" => "painel_atendente",
		"valor" => 'if($linha["painel_atendente"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo_atendente"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo_atendente"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_array" => "ativo",
		"busca_metodo" => 1,
		"tamanho" => 60
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
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idbanner"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  )
);
?>
