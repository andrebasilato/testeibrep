<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_acao_busca",
        "tipo"       => "php",
        "nome"       => "acao",
        "valor"      => 'return $_GET["acao"];',
        "busca"      => true
    ),
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_mes_busca",
        "tipo"       => "php",
        "nome"       => "filtro_mes",
        "valor"      => 'return $_GET["filtro_mes"];',
        "busca"      => true
    ),
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_ano_busca",
        "tipo"       => "php",
        "nome"       => "filtro_ano",
        "valor"      => 'return $_GET["filtro_ano"];',
        "busca"      => true
    ),
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_dia_busca",
        "tipo"       => "php",
        "nome"       => "filtro_dia",
        "valor"      => 'return $_GET["filtro_dia"];',
        "busca"      => true
    ),
    array(
        "busca_tipo" => "hidden",
        "id"         => "idsindicato_filtro_busca",
        "tipo"       => "php",
        "nome"       => "idsindicato_filtro",
        "valor"      => 'return $_GET["idsindicato_filtro"];',
        "busca"      => true
    ),
    array(
        "id"            => "idprevisao",
        "variavel_lang" => "tabela_idprevisao",
        "tipo"          => "php",
        "coluna_sql"    => "idprevisao",
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idprevisao"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idprevisao"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "data",
        "variavel_lang" => "tabela_data",
        "tipo"          => "php",
        "coluna_sql"    => "data",
        "valor"         => 'if ($linha["data"]){
                                return formataData($linha["data"],"br",0);
                            }',
        "tamanho"       => "80",
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca"         => true,
        "busca_metodo"  => 3
    ),
    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo"          => "php",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "pg.nome",
        "valor"         => '
					if($linha["nome"]) {
						return $linha["nome"];
					} else {
						return "<span style=\"color:#CCCCCC\">Sem descrição</span>";
					}',
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    
    array(
      "id" => "categoria",
      "variavel_lang" => "tabela_categoria",
      "tipo" => "banco",
      "nao_ordenar" => true,
      "evento" => "maxlength='100'",
      "coluna_sql" => "cat.nome",
      "valor" => "categoria",
      "busca" => true,
      "busca_class" => "inputPreenchimentoCompleto",
      "busca_metodo" => 2
    ),
    
    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "coluna_sql"    => "c.valor",
        "valor"         => 'if($linha["valor"]){return number_format(($linha["valor"]),2,",",".");}	',
        "busca_class"   => "inputPreenchimentoCompleto",
        "nao_ordenar"   => true,
        "busca_metodo"  => 1,
        "tamanho"       => 100
    ),
    /*array("id"              => "situacao",
          "variavel_lang"   => "tabela_situacao",
          "tipo"            => "php",
          "coluna_sql"      => "c.idsituacao",
          "tamanho"         => "100",
          "valor"           => 'if (!$linha["qtde_contas"]){
		  				return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";
		  			  }else
		  				return "--";',
          "busca"           => true,
          "nao_ordenar"     => true,
          "busca_tipo"      => "select",
          "busca_class"     => "inputPreenchimentoCompleto",
          "busca_sql"       => "SELECT idsituacao, nome FROM contas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
          "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
          "busca_sql_label" => "nome",
          "busca_metodo"    => 1),*/
    /*
    array(
      "id" => "data_cad",
      "variavel_lang" => "tabela_datacad",
      "tipo" => "php",
      "coluna_sql" => "data_cad",
      "valor" => 'if (!$linha["qtde_contas"]){
                      return formataData($linha["data_cad"],"br",1);
                  }else
                      return "--";',
      "tamanho" => "140"
    ),
    */
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idprevisao"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao"   => true,
        "tamanho"       => "80"
    )
);