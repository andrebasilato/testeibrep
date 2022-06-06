<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id"            => "idconta_corrente",
        "variavel_lang" => "tabela_idconta_corrente",
        "tipo"          => "php",
        "coluna_sql"    => "cc.idconta_corrente",
        "valor"         => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idconta_corrente"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idconta_corrente"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 1,
        "tamanho"       => 40
    ),

    array(
        "id"            => "nome",
        "variavel_lang" => "tabela_nome",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "cc.nome",
        "valor"         => "nome",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),


    array(
        "id"            => "banco",
        "variavel_lang" => "tabela_banco",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "b.nome",
        "valor"         => "banco",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2
    ),

    array(
        "id"            => "agencia",
        "variavel_lang" => "tabela_agencia",
        "tipo"          => "banco",
        "evento"        => "maxlength='10'",
        "coluna_sql"    => "cc.agencia",
        "valor"         => "agencia",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => 80
    ),

    array(
        "id"            => "agencia_dig",
        "variavel_lang" => "tabela_agencia_dig",
        "tipo"          => "banco",
        "evento"        => "maxlength='10'",
        "coluna_sql"    => "cc.agencia_dig",
        "valor"         => "agencia_dig",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => 40
    ),

    array(
        "id"            => "conta",
        "variavel_lang" => "tabela_conta",
        "tipo"          => "banco",
        "evento"        => "maxlength='100'",
        "coluna_sql"    => "cc.conta",
        "valor"         => "conta",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => 80
    ),

    array(
        "id"            => "conta_dig",
        "variavel_lang" => "tabela_conta_dig",
        "tipo"          => "banco",
        "evento"        => "maxlength='10'",
        "coluna_sql"    => "cc.conta_dig",
        "valor"         => "conta_dig",
        "busca"         => true,
        "busca_class"   => "inputPreenchimentoCompleto",
        "busca_metodo"  => 2,
        "tamanho"       => 40
    ),

    array(
        "id"            => "sindicatos",
        "variavel_lang" => "tabela_qtsindicatos",
        "tipo"          => "php",
        //"valor"         => "sindicatos",
        "valor"         => 'return "<a class=\"dropdown-toggle\" data-original-title=\"".$idioma["tabela_sindicatos_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idconta_corrente"]."/visualizarsindicatos\" data-placement=\"left\" rel=\"tooltip facebox\">".$linha["sindicatos"]."</a>"',
        "busca"         => false,
        "busca_class"   => "inputPreenchimentoCompleto",
        "tamanho"       => 70
    ),
	array("id" => "idsindicato", 
		"variavel_lang" => "tabela_sindicatos", 
		"tipo" => "php", 
		"coluna_sql" => "ccinst.idsindicato", 
		"valor" => 'return "<span data-original-title=\"".$linha["sindicato"]."\" data-placement=\"left\" rel=\"tooltip\">".$linha["inst"].$linha["inst2"]."</span>";',
		"busca" => true,
		"busca_tipo" => "select",
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_sql" => "SELECT i.idsindicato, i.nome_abreviado 
						  FROM sindicatos i
						  WHERE   i.ativo = 'S' GROUP BY i.idsindicato", // SQL que alimenta o select
		"busca_sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"busca_sql_label" => "nome_abreviado",
		"busca_metodo" => 4,
		"tamanho" => "150",
		"overflow" => true),//fim das categorias
    array(
        "id"            => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo"          => "php",
        "coluna_sql"    => "cc.ativo_painel",
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

    /*array(
        "id"            => "data_cad",
        "variavel_lang" => "tabela_datacad",
        "tipo"          => "php",
        "coluna_sql"    => "data_cad",
        "valor"         => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho"       => "140"
    ),*/

    array(
        "id"            => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo"          => "php",
        "valor"         => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idconta_corrente"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao"   => true,
        "tamanho"       => "80"
    )
);
?>