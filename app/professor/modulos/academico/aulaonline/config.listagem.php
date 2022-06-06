<?php
// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        'id' => 'idaula',
        'variavel_lang' => 'tabela_idaula',
        'tipo' => 'banco',
        'evento' => 'maxlength="100"',
        'coluna_sql' => 'idaula',
        'valor' => 'idaula',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1
    ), //a.nome, a.data_aula, a.recorrente, a.link, a.hora_de,a.hora_ate, d.nome, p.nome
    array(
        "id" => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "ao.nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "disciplina",
        "variavel_lang" => "tabela_iddisciplina",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "d.nome",
        "valor" => "disciplina",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "ativo",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo" => "php",
        "coluna_sql" => "ao.ativo",
        "valor" => 'if($linha["ativo"] == true) {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "aula_online",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    array(
        "id" => "data_aula",
        "variavel_lang" => "tabela_data_aula",
        "tipo" => "php",
        "coluna_sql" => "data_aula",
        "valor" => 'return formataData($linha["data_aula"],"br",0);',
        "tamanho" => "140",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 3
    ),
    array(
        "id" => "data_cad",
        "variavel_lang" => "tabela_data_cad",
        "tipo" => "php",
        "coluna_sql" => "data_cad",
        "valor" => 'return formataData($linha["data_cad"],"br",0);',
        "tamanho" => "140",
        "busca_metodo" => 4
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idaula"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
