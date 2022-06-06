<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idvendedor",
        "variavel_lang" => "tabela_idvendedor",
        "tipo" => "php",
        "coluna_sql" => "v.idvendedor",
        "valor" => '
		$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
		if($diferenca > 24) {
			return "<span title=\"$diferenca\">".$linha["idvendedor"]."</span>";
		} else {
			return "<span title=\"$diferenca\">".$linha["idvendedor"]."</span> <i class=\"novo\"></i>";
		}
		',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "v.nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "cfc",
        "variavel_lang" => "tabela_cfc",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "ve.idescola",
        "valor" => "cfc",
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_sql" => "SELECT es.nome_fantasia AS nome, es.idescola FROM escolas es WHERE es.ativo = 'S' and es.ativo_painel = 'S'", // SQL que alimenta o select
        "busca_sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
        "busca_sql_label" => "nome",
        "busca_metodo" => 4
    ),

    array(
        "id" => "email",
        "variavel_lang" => "tabela_email",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "v.email",
        "valor" => "email",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
	
    array(
        "id" => "estado",
        "variavel_lang" => "tabela_estado",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "e.nome",
        "valor" => "estado",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
	
    array(
        "id" => "cidade",
        "variavel_lang" => "tabela_cidade",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "c.nome",
        "valor" => "cidade",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),		

    array(
        "id" => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo" => "php",
        "coluna_sql" => "v.ativo_login",
        "valor" => 'if($linha["ativo_login"] == "S") {
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
        "id" => "ultimo_acesso",
        "variavel_lang" => "tabela_ultimoacesso",
        "tipo" => "php",
        "coluna_sql" => "ultimo_acesso",
        "valor" => 'return formataData($linha["ultimo_acesso"],"br",1);',
        "tamanho" => "140"
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idvendedor"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
?>