    <?php
    /** Array de configuração para a listagem */
    $config["listagem"] = array(
        array(
            "id" => "idfolha",
            "variavel_lang" => "tabela_idfolha",
            "tipo" => "php",
            "coluna_sql" => "idfolha",
            "valor" => '
                $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                if($diferenca > 24) {
                    return "<span title=\"$diferenca\">".$linha["idfolha"]."</span>";
                } else {
                    return "<span title=\"$diferenca\">".$linha["idfolha"]."</span> <i class=\"novo\"></i>";
                }
                ',
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1,
            "tamanho" => 80
        ),
        
        array(
            "id" => "sindicato",
            "variavel_lang" => "tabela_sindicato",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "i.nome",
            "valor" => "sindicato",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2
        ),
        array(
            "id" => "idcurso",
            "variavel_lang" => "tabela_curso",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "c.nome",
            "valor" => "curso",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2
        ),
        array(
            "id" => "nome",
            "variavel_lang" => "tabela_nome",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "frd.nome",
            "valor" => "nome",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2
        ),
        array(
            "id" => "numero_ordem",
            "tipo" => "banco",
            "busca" => true,
            "valor" => "numero_ordem",
            "evento" => "maxlength='100'",
            "tamanho" => "60",
            "coluna_sql" => "frd.numero_ordem",
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2,
            "variavel_lang" => "tabela_numero_ordem"
        ),
        
        array(
            "id" => "numero_registro",
            "variavel_lang" => "tabela_numero_registro",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "numero_registro",
            "valor" => "numero_registro",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2,
            "tamanho" => "60"
        ),
        
        array(
            "id" => "numero_relacao",
            "variavel_lang" => "tabela_numero_relacao",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "numero_relacao",
            "valor" => "numero_relacao",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2,
            "tamanho" => "60"
        ),
        
        array(
            "id" => "data_expedicao",
            "variavel_lang" => "tabela_data_expedicao",
            "tipo" => "php",
            "busca" => true,
            "busca_metodo" => 3,
            "busca_class" => "inputPreenchimentoCompleto",
            "coluna_sql" => "data_expedicao",
            "valor" => 'return formataData($linha["data_expedicao"],"br",0);',
            "tamanho" => "100"
        ),
        
        array(
            "id" => "ativo_painel",
            "variavel_lang" => "tabela_ativo_painel",
            "tipo" => "php",
            "coluna_sql" => "frd.ativo_painel",
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
            "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idfolha"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
            "busca_botao" => true,
            "tamanho" => "80"
        )
    );
    
    $config["listagem_diplomas"] = array(
        
        array(
            "id" => "numero_ordem",
            "variavel_lang" => "tabela_numero_ordem",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "frdm.numero_ordem",
            "valor" => "numero_ordem",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1,
            "tamanho" => 80
        ),
        
        array(
            "id" => "idmatricula",
            "variavel_lang" => "tabela_idmatricula",
            "tipo" => "php",
            "coluna_sql" => "mat.idmatricula",
            "valor" => '
            $textoMatricula = "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
            if($linha["cancelado"] == "S") {
                $textoMatricula .= " <span style=\"color:#F00\"><strong>[C]</strong></span>";
            }
                return "$textoMatricula";
            ',
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1,
            "tamanho" => 60
        ),
        
        array(
            "id" => "pessoa",
            "variavel_lang" => "tabela_pessoa",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "pes.nome",
            "valor" => "pessoa",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 2
        ),
        
        array(
            "id" => "rg",
            "variavel_lang" => "tabela_rg",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "pes.rg",
            "valor" => "rg",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1
        ),
        
        array(
            "id" => "numero_registro",
            "variavel_lang" => "tabela_numero_registro",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "frdm.numero_registro",
            "valor" => "numero_registro",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1,
            "tamanho" => 60
        ),
        
        array(
            "id" => "numero_relacao",
            "variavel_lang" => "tabela_numero_relacao",
            "tipo" => "banco",
            "evento" => "maxlength='100'",
            "coluna_sql" => "frd.numero_relacao",
            "valor" => "numero_relacao",
            "busca" => true,
            "busca_class" => "inputPreenchimentoCompleto",
            "busca_metodo" => 1,
            "tamanho" => 80
        ),
        
        array(
            "id" => "opcoes",
            "variavel_lang" => "tabela_opcoes",
            "tipo" => "php",
            "valor" => 'if ($linha["cancelado"] == "N") {
                    return "<a class=\"btn dropdown-toggle btn-mini\"
                                data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\"
                                href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idmatricula"]."/gerar\"
                                target=\"_blank\" >"
                                    .$idioma["tabela_gerar"]
                            ."</a> "

                            ."<a class=\"btn dropdown-toggle btn-mini\"
                           data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\"
                           href=\"/".$this->url["0"]."/".$this->url["1"]."/"
                                .$this->url["2"]."/".$this->url["3"]."/"
                                .$this->url["4"]."/".$linha["idfolha_matricula"]."/removermatriculadafolha/".$linha["idmatricula"]."\"
                           data-placement=\"left\" rel=\"tooltip facebox\">"
                                .$idioma["tabela_remover"]
                            ."</a>";} else {
                                return "<span style=\"color:#F00\"><strong>Cancelado</strong></span>";
                            }

                            ',
            "busca_botao" => true,
            "tamanho" => "190"
        )
    );