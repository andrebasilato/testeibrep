<?
$config["listagem_cobrancas"] = array(
    array(
        "id"            => "idcobranca",
        "variavel_lang" => "tabela_idcobranca",
        "tipo"          => "php",
        "coluna_sql"    => "c.idcobranca",
        "valor"         => '
					$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
					if($diferenca > 24) {
						return "<span title=\"$diferenca\">".$linha["idcobranca"]."</span>";
					} else {
						return "<span title=\"$diferenca\">".$linha["idcobranca"]."</span> <i class=\"novo\"></i>";
					}
					',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 80
    ),
    array(
        "id"            => "idmatricula",
        "variavel_lang" => "tabela_idmatricula",
        "tipo"          => "banco",
        "coluna_sql"    => "c.idmatricula",
        "valor"         => "idmatricula",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 60
    ),
    array(
        "id"            => "aluno",
        "variavel_lang" => "tabela_aluno",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "p.nome",
        "valor"         => 'aluno',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "data_prox_acao",
        "variavel_lang" => "tabela_prox_acao",
        "tipo"          => "php",
        "coluna_sql"    => "c.proxima_acao",
        "valor"         => 'return formataData($linha["proxima_acao"],"br",0);',
        "tamanho"       => "140"
    ),
    array(
        "id"            => "responsavel",
        "variavel_lang" => "tabela_responsavel",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "ua.nome",
        "valor"         => 'usuario',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),
    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "<a href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/administrar/".$linha["idmatricula"]."\" class=\"btn dropdown-toggle btn-mini\">".$idioma["btn_administrar"]."</a> ";',
        "busca_botao"   => true,
        "tamanho"       => "110"
    )
);
?>