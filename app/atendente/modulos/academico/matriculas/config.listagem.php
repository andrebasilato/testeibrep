<?php
// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        "id" => "idmatricula",
        "variavel_lang" => "tabela_idmatricula",
        "tipo" => "php",
        "coluna_sql" => "idmatricula",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idmatricula"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "aluno",
        "variavel_lang" => "tabela_aluno",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "p.nome",
        "valor" => "aluno",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),


    array(
        "id" => "documento",
        "variavel_lang" => "tabela_documento",
        "tipo" => "banco",
        "coluna_sql" => "p.documento",
        "valor" => "documento",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "email",
        "variavel_lang" => "tabela_email",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "p.email",
        "valor" => "email",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "curso",
        "variavel_lang" => "tabela_curso",
        "tipo" => "php",
        "coluna_sql" => "m.idcurso",

        "valor" => 'return $linha["curso"]["nome"];',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_sql" => "SELECT idcurso, nome FROM cursos WHERE ativo = 'S'", // SQL que alimenta o select
        "busca_sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
        "busca_sql_label" => "nome",
        "busca_metodo" => 1

    ),
    array("id" => "situacao",
        "variavel_lang" => "tabela_situacao",
        "tipo" => "php",
        "coluna_sql" => "m.idsituacao",

        "valor" => 'return "<span data-original-title=\"".$linha["situacao"]."\" class=\"label\" style=\"background:#".$linha["situacao_cor_bg"]."; color:#".$linha["situacao_cor_nome"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["situacao"]."</span>";',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = 'S'", // SQL que alimenta o select
        "busca_sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
        "busca_sql_label" => "nome",
        "busca_metodo" => 1),

    array(
        "id" => "vendedor",
        "variavel_lang" => "tabela_vendedor",
        "tipo" => "banco",
        "coluna_sql" => "v.nome",
        "valor" => 'vendedor',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2

    ),
    array(
        "id" => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "tipo" => "php",
        "coluna_sql" => "m.data_cad",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "140",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 3
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_administrar_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmatricula"]."/administrar\" data-placement=\"left\" rel=\"tooltip\">".$idioma["tabela_administrar"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "160"
    ),
);
