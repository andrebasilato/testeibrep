<?php

$config['listagem'] = array(
    array(
        'id' => 'idtransacao',
        'variavel_lang' => 'tabela_idtransacao',
        'tipo' => 'php',
        'coluna_sql' => 'idtransacao',
        'valor' => '
            $diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d"), "H");
            if($diferenca > 24) {
                return "<span title=\"$diferenca\">".$linha["idtransacao"]."</span>";
            } else {
                return "<span title=\"$diferenca\">".$linha["idtransacao"]."</span> <i class=\"novo\"></i>";
            }',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 80,
    ),

    array(
        'id' => 'interface',
        'variavel_lang' => 'tabela_interface',
        'tipo' => 'php',
        'coluna_sql' => 'idinterface',
        'valor' => '
                    return $GLOBALS["orio_interfaces_label"]["pt_br"][$linha["idinterface"]];
                   ',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto idinterface',
        'busca_array' => 'orio_interfaces_label',
        'busca_metodo' => 9,
        'tamanho' => 150,
    ),

    array(
        'id' => 'interface_descricoes',
        'variavel_lang' => 'tabela_descricao',
        'tipo' => 'php',
        'coluna_sql' => 'idinterface',
        'valor' => '
                    return $GLOBALS["orio_interfaces_descricoes"]["pt_br"][$linha["idinterface"]];
                   ',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto idinterface_descricao',
        'busca_array' => 'orio_interfaces_descricoes',
        'busca_metodo' => 9,
        'tamanho' => 150,
    ),

    array(
        'id' => 'tipo',
        'variavel_lang' => 'tabela_tipo',
        'tipo' => 'php',
        'coluna_sql' => 'tipo',
        'valor' => 'return "<span class=\"label\" style=\"background-color:#".$GLOBALS["tipo_transacao_cores"][$linha["tipo"]]."\">".$GLOBALS["tipo_transacao"]["pt_br"][$linha["tipo"]]."</span>"; ',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_array' => 'tipo_transacao',
        'busca_metodo' => 1,
        'tamanho' => 120,
    ),

    array(
        'id' => 'ip',
        'variavel_lang' => 'tabela_ip',
        'tipo' => 'banco',
        'coluna_sql' => 'ip',
        'valor' => 'ip',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'tamanho' => '150',
        'busca_metodo' => 1,
        'tamanho' => 100,
    ),

    array(
        'id' => 'situacao',
        'variavel_lang' => 'tabela_situacao',
        'tipo' => 'php',
        'coluna_sql' => 'situacao',
        'valor' => '
                    return "<span class=\"label\" style=\"background-color:#".$GLOBALS["situacao_transacao_cores"][$linha["situacao"]]."\">".$GLOBALS["situacao_transacao"]["pt_br"][$linha["situacao"]]."</span>";
                   ',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto situacao',
        'busca_array' => 'situacao_transacao',
        'busca_metodo' => 1,
        'tamanho' => '100',
    ),

    array(
        'id' => 'tamanho',
        'variavel_lang' => 'tabela_tamanho',
        'tipo' => 'php',
        'coluna_sql' => 'tamanho',
        'valor' => 'return $linha["tamanho"];',
        'tamanho' => 50,
    ),

    array(
        'id' => 'tempo',
        'variavel_lang' => 'tabela_tempo',
        'tipo' => 'php',
        'coluna_sql' => 'tempo',
        'valor' => 'return $linha["tempo"];',
        'tamanho' => 100,
    ),

    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'coluna_sql' => 'data_cad',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_cad"],"br",1);',
        'tamanho' => 140,
    ),

    array(
        'id' => 'opcoes',
        'variavel_lang' => 'tabela_opcoes',
        'tipo' => 'php',
        'valor' => '
            $string = "<a class=\"btn dropdown-toggle btn-mini\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idtransacao"]."/informacoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_informacoes"]."</a>";
            return $string;',
        'busca_botao' => true,
        'tamanho' => 130,
    ),
);
