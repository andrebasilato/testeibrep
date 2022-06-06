<?php
// Array de configuração para a listagem	
$config['listagem'] = array(
    array(
        'id' => 'idsindicato',
        'variavel_lang' => 'tabela_idsindicato',
        'tipo' => 'php',
        'coluna_sql' => 'i.idsindicato',
        'valor' => '$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
											if($diferenca > 24) {
												return "<span title=\"$diferenca\">".$linha["idsindicato"]."</span>";
											} else {
												return "<span title=\"$diferenca\">".$linha["idsindicato"]."</span> <i class=\"novo\"></i>";
											}',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 80
    ),

    array(
        'id' => 'nome_abreviado',
        'variavel_lang' => 'tabela_nome_abreviado',
        'tipo' => 'banco',
        'evento' => 'maxlength="100"',
        'coluna_sql' => 'i.nome_abreviado',
        'valor' => 'nome_abreviado',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),

    array(
        'id' => 'mantenedora',
        'variavel_lang' => 'tabela_mantenedora',
        'tipo' => 'banco',
        'evento' => 'maxlength="100"',
        'coluna_sql' => 'm.nome_fantasia',
        'valor' => 'mantenedora',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),

    array(
        'id' => 'telefone', // Id do atributo
        'variavel_lang' => 'tabela_telefone', // Referencia a variavel de idioma
        'tipo' => 'banco', // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        'coluna_sql' => 'i.telefone', // Nome da coluna no banco de dados
        'valor' => 'telefone', // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),

    array(
        'id' => 'ativo_painel',
        'variavel_lang' => 'tabela_ativo_painel',
        'tipo' => 'php',
        'coluna_sql' => 'i.ativo_painel',
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
        'id' => 'acesso_ava',
        'variavel_lang' => 'tabela_acesso_ava',
        'tipo' => 'php',
        'coluna_sql' => 'i.acesso_ava',
        'valor' => 'return "<span data-original-title=\"" . $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["acesso_ava"]] . "\" class=\"label\" data-placement=\"left\" rel=\"tooltip\" style=\"background-color: " . $GLOBALS["sim_nao_cor"][$linha["acesso_ava"]] . ";\">
                                                        " . $linha["acesso_ava"] . "
                                                    </span>";',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_array' => 'sim_nao',
        'busca_metodo' => 1,
        'tamanho' => 70
    ),

    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'tipo' => 'php',
        'coluna_sql' => 'data_cad',
        'valor' => 'return formataData($linha["data_cad"],"br",1);',
        'tamanho' => '110'
    ),

    array(
        'id' => 'opcoes',
        'variavel_lang' => 'tabela_opcoes',
        'tipo' => 'php',
        'valor' => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idsindicato"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        'busca_botao' => true,
        'tamanho' => '80'
    )
);