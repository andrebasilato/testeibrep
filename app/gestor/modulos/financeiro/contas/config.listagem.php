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
        "id"            => "idconta",
        "variavel_lang" => "tabela_idconta",
        "tipo"          => "php",
        "coluna_sql"    => "c.idconta",
        "valor"         => 'if ($linha["fatura"] == "S"){
            					return "<div style=\"float: right;\"><b style=\"color: #0d6abf\">" . $idioma["faturas"] . "</b></div>";
            				} elseif ($linha["qtde_contas"]){
                                return "<div style=\"float: right;\"><b style=\"color: #0d6abf\">" . $idioma["matriculas"] . "</b></div>";
                            } else {
            					return "<div style=\"float: right;\"><b>".$linha["idconta"]."</b></div>";
            				}',
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "data_vencimento",
        "variavel_lang" => "tabela_vencimento",
        "tipo"          => "php",
        "coluna_sql"    => "data_vencimento",
        "valor"         => 'if ($linha["data_vencimento"] < date("Y-m-d") && $linha["pago"] != "S" && !$linha["qtde_contas"]){
            					return "<span style=\"color:red;\"><strong>".formataData($linha["data_vencimento"],"br",0)."</strong></span>";
            				} else {
            					return formataData($linha["data_vencimento"],"br",0);
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
        "coluna_sql"    => "c.nome",
        "valor"         => 'if ($linha["fatura"] == "S") {
            				    if ($linha["qtde_contas"] > 1) {
                                    return $linha["qtde_contas"]." " . $idioma["parcelas_faturas"];
            				    } else {
                                    return $linha["qtde_contas"]." " . $idioma["parcela_fatura"];
                                }
            				} elseif ($linha["qtde_contas"]) {
                                if ($linha["qtde_contas"] > 1) {
                                    return $linha["qtde_contas"]." " . $idioma["parcelas_matriculas"];
                                } else {
                                    return $linha["qtde_contas"]." " . $idioma["parcela_matricula"];
                                }
                            } else {
            					if ($linha["nome"]) {
            						return $linha["nome"];
            					} else {
            						return "<span style=\"color:#CCCCCC\">Sem descrição</span>";
            					}
            				}',
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "documento",
        "variavel_lang" => "tabela_documento",
        "tipo"          => "php",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "c.documento",
        "valor"         => 'if ($linha["qtde_contas"]){
				  return "<span style=\"color:#CCCCCC\">--</span>";
				}else{
					if($linha["documento"]) {
						return $linha["documento"];
					} else {
						return "<span style=\"color:#CCCCCC\">Sem doc. referência</span>";
					}
				}',
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    /*
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
    */
    array(
        "id"            => "fornecedor",
        "variavel_lang" => "tabela_fornecedor",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "forn.nome",
        "valor"         => "fornecedor",
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "numero_parcela",
        "variavel_lang" => "tabela_numero_parcela",
        "tipo"          => "php",
        //"coluna_sql" => "data_vencimento",
        "valor"         => 'if (!$linha["qtde_contas"]){
					return $linha["parcela"]."/".$linha["total_parcelas"];
				}',
        "tamanho"       => "80",
        "nao_ordenar"   => true,
        //"busca_class" => "inputPreenchimentoCompleto",
        "busca"         => false,
        //"busca_metodo" => 3
    ),
    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "coluna_sql"    => "c.valor",
        "valor"         => '
				$rs = "<span style=\"color:gray; float:left\">R$</span>";
				if ($linha["qtde_contas"]){
					return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["total"],2,",",".")."</strong></span>";
				}else{
					if($linha["valor"] < 0)
						return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
					else
						return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
				}
				
				',
        "busca_class"   => "inputPreenchimentoCompleto",
        "nao_ordenar"   => true,
        "busca_metodo"  => 1,
        "tamanho"       => 100
    ),
    array("id"              => "situacao",
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
          "busca_metodo"    => 1),
    array(
        "id"            => "numero_documento",
        "variavel_lang" => "tabela_numero_documento",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "c.numero_documento",
        "valor"         => "numero_documento",
        "busca"         => true,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'if ($linha["idpagamento_compartilhado"]) {
            					return "<a class=\"btn dropdown-toggle btn-mini\" rel=\"tooltip facebox\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/idconta/".$linha["idconta"]."/visualizacompartilhada\"> Compartilhado </a>";
            				} elseif (!$linha["qtde_contas"]) {
            					return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/idconta/".$linha["idconta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
            				} elseif ($linha["fatura"] == "S") { 
            				    return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/idconta/".$linha["idconta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";
            				} else {
                                return "<a class=\"btn dropdown-toggle btn-mini\" rel=\"tooltip facebox\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/dia/".$linha["data_vencimento"]."/visualizafacebox?".http_build_query($_GET)."\"> Detalhar </a>";
                            }',
        "busca_botao"   => true,
        "tamanho"       => "100"
    )
);

$config['listagem_matriculas'] = array(
                                    array(
                                        'id' => 'idconta',
                                        'variavel_lang' => 'tabela_idconta',
                                        'tipo' => 'banco',
                                        'coluna_sql' => 'c.idconta',
                                        'valor' => 'idconta',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'busca_class' => 'inputPreenchimentoCompleto',
                                        'busca_metodo' => 1,
                                        'tamanho' => 60
                                    ),

                                    array(
                                        'id' => 'data_vencimento',
                                        'variavel_lang' => 'tabela_vencimento',
                                        'tipo' => 'php',
                                        'coluna_sql' => 'data_vencimento',
                                        'nao_ordenar' => true,
                                        'valor' => 'if ($linha["data_vencimento"] < date("Y-m-d") && $linha["pago"] != "S"){
                                                        return "<span style=\"color:red;\"><strong>".formataData($linha["data_vencimento"],"br",0)."</strong></span>";
                                                    } else {
                                                        return formataData($linha["data_vencimento"],"br",0);
                                                    }',
                                        'busca_class' => 'inputPreenchimentoCompleto',
                                        'busca' => false,
                                        'busca_metodo' => 3,
                                        'tamanho' => 80
                                    ),

                                    array(
                                        'id' => 'matricula',
                                        'variavel_lang' => 'tabela_matricula',
                                        'tipo' => 'banco',
                                        'coluna_sql'    => 'm.idmatricula',
                                        'valor' => 'idmatricula',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'busca_class' => 'inputPreenchimentoCompleto',
                                        'busca_metodo' => 2,
                                        'evento' => 'maxlength="100"'
                                    ),

                                    array(
                                        'id' => 'aluno',
                                        'variavel_lang' => 'tabela_aluno',
                                        'tipo' => 'banco',
                                        'coluna_sql'    => 'p.nome',
                                        'valor' => 'aluno',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'busca_class' => 'inputPreenchimentoCompleto',
                                        'busca_metodo' => 2,
                                        'evento' => 'maxlength="100"'
                                    ),

                                    array(
                                        'id' => 'valor',
                                        'variavel_lang' => 'tabela_valor',
                                        'tipo' => 'php',
                                        'coluna_sql' => 'c.valor',
                                        'valor' => ' $rs = "<span style=\"color:gray; float:left\">R$</span>";
                                                    if ($linha["valor"] < 0) {
                                                        return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
                                                    } else {
                                                        return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
                                                    }',
                                        'busca_class' => 'inputPreenchimentoCompleto',
                                        'busca_metodo' => 1,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    ),

                                    array(
                                        'id' => 'situacao',
                                        'variavel_lang' => 'tabela_situacao',
                                        'tipo' => 'php',
                                        'coluna_sql' => 'c.idsituacao',
                                        'valor' => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">
                                                                " . $linha["situacao"] . "
                                                            </span>";',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    ),

                                    array(
                                        'id' => 'historico',
                                        'variavel_lang' => 'titulo_historico',
                                        'tipo' => 'php',
                                        'valor' => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_historico_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/idconta/".$linha["idconta"]."/historico\" data-placement=\"left\" rel=\"tooltip facebox\">
                                                                " . $idioma["tabela_historico"] . "
                                                            </a>";',
                                        'busca_botao' => true,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    ),

                                    array(
                                        'id' => 'opcoes',
                                        'variavel_lang' => 'tabela_opcoes',
                                        'tipo' => 'php',
                                        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/academico/matriculas/".$linha["idmatricula"]."/administrar\" data-placement=\"left\" rel=\"tooltip\">
                                                                " . $idioma["tabela_abrir"] . "
                                                            </a>";',
                                        'busca_botao' => true,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    )
                                );

$config['listagem_faturas'] = array(
                                    array(
                                        'id' => 'idconta',
                                        'variavel_lang' => 'tabela_idconta',
                                        'tipo' => 'banco',
                                        'valor' => 'idconta',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'tamanho' => 60
                                    ),

                                    array(
                                        'id' => 'data_vencimento',
                                        'variavel_lang' => 'tabela_vencimento',
                                        'tipo' => 'php',
                                        'valor' => 'if ($linha["data_vencimento"] < date("Y-m-d") && $linha["pago"] != "S"){
                                                        return "<span style=\"color:red;\"><strong>".formataData($linha["data_vencimento"],"br",0)."</strong></span>";
                                                    } else {
                                                        return formataData($linha["data_vencimento"],"br",0);
                                                    }',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'tamanho' => 80
                                    ),

                                    array(
                                        'id' => 'idescola',
                                        'variavel_lang' => 'tabela_idescola',
                                        'tipo' => 'banco',
                                        'valor' => 'escola',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                    ),

                                    array(
                                        'id' => 'valor',
                                        'variavel_lang' => 'tabela_valor',
                                        'tipo' => 'php',
                                        'valor' => ' $rs = "<span style=\"color:gray; float:left\">R$</span>";
                                                    if ($linha["valor"] < 0) {
                                                        return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
                                                    } else {
                                                        return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
                                                    }',
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    ),

                                    array(
                                        'id' => 'situacao',
                                        'variavel_lang' => 'tabela_situacao',
                                        'tipo' => 'php',
                                        'valor' => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">
                                                                " . $linha["situacao"] . "
                                                            </span>";',
                                        'busca' => false,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    ),

                                    array(
                                        'id' => 'opcoes',
                                        'variavel_lang' => 'tabela_opcoes',
                                        'tipo' => 'php',
                                        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url[0]."/".$this->url[1]."/faturas/".$linha["idconta"]."/ficha\" data-placement=\"left\" rel=\"tooltip\">
                                                                " . $idioma["tabela_ficha"] . "
                                                            </a>";',
                                        'busca_botao' => true,
                                        'nao_ordenar' => true,
                                        'tamanho' => 100
                                    )
                                );

$config["listagem_matriculas_index"] = array(
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_mes_busca",
        "tipo"       => "php",
        "nome"       => "filtro_mes",
        //"valor" => "return $_GET['teste'];",
        "busca"      => true
    ),
    array(
        "busca_tipo" => "hidden",
        "id"         => "filtro_ano_busca",
        "tipo"       => "php",
        "nome"       => "filtro_ano",
        //"valor" => "return $_GET['teste'];",
        "busca"      => true
    ),
    array(
        "id"            => "idconta",
        "variavel_lang" => "tabela_idconta",
        "tipo"          => "banco",
        "coluna_sql"    => "c.idconta",
        "valor"         => "idconta",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 60
    ),
    array(
        "id"            => "data_vencimento",
        "variavel_lang" => "tabela_vencimento",
        "tipo"          => "php",
        "coluna_sql"    => "data_vencimento",
        "valor"         => 'return formataData($linha["data_vencimento"],"br",0);',
        "tamanho"       => "80",
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca"         => true,
        "busca_metodo"  => 3
    ),
    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "c.nome",
        "valor"         => "nome",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "categoria",
        "variavel_lang" => "tabela_categoria",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "cat.nome",
        "valor"         => "categoria",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "fornecedor",
        "variavel_lang" => "tabela_fornecedor",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "forn.nome",
        "valor"         => "fornecedor",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "coluna_sql"    => "c.valor",
        "valor"         => '
				$rs = "<span style=\"color:gray; float:left\">R$</span>";
				if($linha["valor"] < 0)
					return "$rs <span style=\"color:red; float:right\"><strong>".number_format(($linha["valor"]*-1),2,",",".")."</strong></span>";
				else
					return "$rs <span style=\"color:green; float:right\"><strong>".number_format($linha["valor"],2,",",".")."</strong></span>";
				
				',
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 100
    ),
    array("id"              => "situacao",
          "variavel_lang"   => "tabela_situacao",
          "tipo"            => "php",
          "coluna_sql"      => "c.idsituacao",
          "tamanho"         => "100",
          "valor"           => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
          "busca"           => true,
          "busca_tipo"      => "select",
          "busca_class"     => "inputPreenchimentoCompleto",
          "busca_sql"       => "SELECT idsituacao, nome FROM contas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
          "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
          "busca_sql_label" => "nome",
          "busca_metodo"    => 1),
    array(
        "id"            => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "tipo"          => "php",
        "coluna_sql"    => "data_cad",
        "valor"         => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho"       => "140"
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/idconta/".$linha["idconta"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>";',
        "busca_botao"   => true,
        "tamanho"       => "80"
    )
);

$config["listagem_compartilhado"] = array(
    /*array(
      "id" => "data_vencimento",
      "variavel_lang" => "tabela_vencimento",
      "tipo" => "php",
      "coluna_sql" => "data_vencimento",
      "nao_ordenar" => true,
      "valor" => 'return formataData($linha["data_vencimento"],"br",0);',
      "tamanho" => "80",
      "busca_class" => "inputPreenchimentoCompleto",
      "busca" => false,
      "busca_metodo" => 3
    ), */
    array(
        "id"            => "matricula",
        "variavel_lang" => "tabela_matricula",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "m.idmatricula",
        "valor"         => "idmatricula",
        "busca"         => false,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "aluno",
        "variavel_lang" => "tabela_aluno",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "p.nome",
        "valor"         => "pessoa",
        "busca"         => false,
        "nao_ordenar"   => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array("id"            => "situacao",
          "variavel_lang" => "tabela_situacao",
          "tipo"          => "php",
          "coluna_sql"    => "c.idsituacao",
          "tamanho"       => "100",
          "valor"         => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
          "busca"         => false,
          "nao_ordenar"   => true,
        /*"busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_sql" => "SELECT idsituacao, nome FROM contas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
        "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
        "busca_sql_label" => "nome",
        "busca_metodo" => 1*/
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "";',
        "busca_botao"   => true,
        "nao_ordenar"   => true,
        "tamanho"       => "100"
    )

    /*array(
      "id" => "data_cad",
      "variavel_lang" => "tabela_datacad",
      "tipo" => "php",
      "coluna_sql" => "data_cad",
      "valor" => 'return formataData($linha["data_cad"],"br",1);',
      "tamanho" => "140"
    ), */
);