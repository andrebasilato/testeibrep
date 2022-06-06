<?php
// Array de configuração para a listagem
$config["listagem"] = array(
 array(
	'id' => 'idvideo',
	'variavel_lang' => 'tabela_idturma',
	'tipo' => 'php',
	'coluna_sql' => 'idvideo',
	'valor' => '

			$diferenca = dataDiferenca($linha[0]["data_cad"], date("Y-m-d"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idvideo"]."</span>";
			}
			return "<span title=\"$diferenca\">".$linha["idvideo"]."</span> <i class=\"novo\"></i>";

			',
	'busca' => true,
	'busca_class' => 'inputPreenchimentoCompleto',
	'busca_metodo' => 1,
	'tamanho' => 80
  ),
  array(
  	'id' => 'icon',
  	'variavel_lang' => 'icon_video',
  	'tipo' => 'php',
  	'valor' => 'return "icone";'
  ),
  array(
	'id' => 'titulo',
	'variavel_lang' => 'tabela_nome',
	'tipo' => 'banco',
	'evento' => 'maxlength="100"',
	'coluna_sql' => 'titulo',
	'valor' => 'titulo',
	'busca' => true,
	'busca_class' => 'inputPreenchimentoCompleto',
	'busca_metodo' => 2
  ),
  array(
	'id' => 'ativo_painel',
	'variavel_lang' => 'tabela_ativo_painel',
	'tipo' => 'php',
	'coluna_sql' => 'ativo_painel',
	'valor' => 'if($linha["ativo_painel"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
	'busca' => true,
	'busca_tipo' => 'select',
	'busca_class' => 'inputPreenchimentoCompleto',
	'busca_array' => 'ativo',
	'busca_metodo' => 1,
	'tamanho' => 60
  ),
  array(
	'id' => 'data_cad',
	'variavel_lang' => 'tabela_datacad',
	'tipo' => 'php',
	'coluna_sql' => 'data_cad',
	'valor' => 'return formataData($linha["data_cad"],"br",1);',
	'tamanho' => '140'
  ),
  array(
	'id' => 'opcoes',
	'variavel_lang' => 'tabela_opcoes',
	'tipo' => 'php',
	'valor' => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idvideo"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	'busca_botao' => true,
	'tamanho' => '80'
  )
);