<?php

$config['listagem'] = array(
    array(
        'id'            => 'idevento',
        'variavel_lang' => 'tabela_idbanco',
        'tipo'          => 'php',
        'coluna_sql'    => 'idevento',
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idevento"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idevento"]."</span> <i class=\"novo\"></i>";
			}
			',
        'busca'         => true,
        'busca_class'   => 'inputPreenchimentoCompleto',
        'busca_metodo'  => 1,
        "tamanho"       => 80
    ),
    array(
        'id'            => 'nome',
        'variavel_lang' => 'tabela_nome',
        'tipo'          => 'banco',
        'evento'        => 'maxlength="100"',
        'coluna_sql'    => 'nome',
        'valor'         => 'nome',
        'busca'         => true,
        'busca_class'   => 'inputPreenchimentoCompleto',
        'busca_metodo'  => 2
    ),
    array(
        'id'            => 'mensalidade',
        'variavel_lang' => 'tabela_mensalidade',
        'tipo'          => 'php',
        'coluna_sql'    => 'mensalidade',
        'valor'         => 'if($linha["mensalidade"] == "S") {
        			  return "<span data-original-title=\"".$idioma["sim"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Sim</span>";
        			} else {
        			  return "<span data-original-title=\"".$idioma["nao"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Não</span>";
        			}',
        'busca'         => true,
        'busca_tipo'    => 'select',
        'busca_class'   => 'inputPreenchimentoCompleto',
        'busca_array'   => 'sim_nao',
        'busca_metodo'  => 1,
        'tamanho'       => 60
    ),
   array(
    'id'            => 'taxa_reativacao',
    'variavel_lang' => 'tabela_taxa_reativacao',
    'tipo'          => 'php',
    'coluna_sql'    => 'taxa_reativacao',
    'valor'         => 'if($linha["taxa_reativacao"] == "S") {
          			  return "<span data-original-title=\"".$idioma["sim"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Sim</span>";
          			} else {
          			  return "<span data-original-title=\"".$idioma["nao"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Não</span>";
          			}',
    'busca'         => true,
    'busca_tipo'    => 'select',
    'busca_class'   => 'inputPreenchimentoCompleto',
    'busca_array'   => 'sim_nao',
    'busca_metodo'  => 1,
    'tamanho'       => 120
   ),
    array(
        'id'            => 'ativo_painel',
        'variavel_lang' => 'tabela_ativo_painel',
        'tipo'          => 'php',
        'coluna_sql'    => 'ativo_painel',
        'valor'         => 'if($linha["ativo_painel"] == "S") {
        			  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
        			} else {
        			  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
        			}',
        'busca'         => true,
        'busca_tipo'    => 'select',
        'busca_class'   => 'inputPreenchimentoCompleto',
        'busca_array'   => 'ativo',
        'busca_metodo'  => 1,
        'tamanho'       => 60
    ),
    array(
        'id'            => 'opcoes',
        'variavel_lang' => 'tabela_opcoes',
        'tipo'          => 'php',
        'valor'         => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idevento"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        'busca_botao'   => true,
        'tamanho'       => '80'
    ),
);