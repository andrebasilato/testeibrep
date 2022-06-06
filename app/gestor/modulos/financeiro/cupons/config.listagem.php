<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id"            => "idcupom",
        "variavel_lang" => "tabela_idcupom",
        "tipo"          => "php",
        "coluna_sql"    => "idcupom",
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idcupom"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idcupom"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "nome",
        "valor"         => "nome",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "codigo",
        "variavel_lang" => "tabela_codigo",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "codigo",
        "valor"         => "codigo",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "porcentagem",
        "variavel_lang" => "tabela_porcentagem",
        "tipo"          => "php",
        "valor"         => 'if($linha["tipo_desconto"] == "P") {
                                return number_format($linha["porcentagem"],"2",",",".");
                            } else {
                                return "--";
                            }',
        "coluna_sql"    => "porcentagem",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "valor",
        "variavel_lang" => "tabela_valor",
        "tipo"          => "php",
        "valor"         => 'if($linha["tipo_desconto"] == "V") {
                                return number_format($linha["valor"],"2",",",".");
                            } else {
                                return "--";
                            }',
        "coluna_sql"    => "valor",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "quantidade",
        "variavel_lang" => "tabela_quantidade",
        "tipo"          => "banco",
        "valor"         => 'quantidade',
        "coluna_sql"    => "quantidade",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "utilizado",
        "variavel_lang" => "tabela_utilizado",
        "tipo"          => "banco",
        "valor"         => 'utilizado',
        "coluna_sql"    => "utilizado",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "validade",
        "variavel_lang" => "tabela_validade",
        "tipo"          => "php",
        "coluna_sql"    => "validade",
        "valor"         => 'if($linha["validade"]) {
                                return formataData($linha["validade"],"br",1);
                            } else {
                                return "--";
                            }',
        "tamanho"       => "140"
    ),
    array(
        "id"            => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo"          => "php",
        "coluna_sql"    => "ativo_painel",
        "valor"         => 'if($linha["ativo_painel"] == "S") {
                                return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
                            } else {
                                return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
                            }',
        "busca"         => true,
        "busca_tipo"    => "select",
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_array"   => "ativo",
        "busca_metodo"  => 1,
        "tamanho"       => 60
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idcupom"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao"   => true,
        "tamanho"       => "80"
    )
);
?>