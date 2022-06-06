<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idmonitora",
        "variavel_lang" => "tabela_idmonitora",
        "tipo" => "php",
        "coluna_sql" => "m.idmonitora",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idmonitora"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idmonitora"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "nome",
        "variavel_lang" => "tabela_usuario",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "u.nome",
        "valor" => "usuario",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "idacao",
        "variavel_lang" => "tabela_idacao",
        "tipo" => "php",
        "evento" => "maxlength='100'",
        "coluna_sql" => "m.idacao",
        "valor" => ' return $GLOBALS["monitora_acao"]["pt_br"][$linha["idacao"]]',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "monitora_acao",
        "busca_metodo" => 1
    ), //$monitora_acao
    array(
        "id" => "idonde", // Id do atributo
        "variavel_lang" => "tabela_idonde", // Referencia a variavel de idioma
        "tipo" => "php", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "m.idonde", // Nome da coluna no banco de dados
        "valor" => ' return $GLOBALS["monitora_onde"]["pt_br"][$linha["idonde"]]', // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "monitora_onde",
        "busca_metodo" => 1
    ),
    array(
        "id" => "idqual",
        "variavel_lang" => "tabela_qual",
        "tipo" => "banco",
        "coluna_sql" => "m.id",
        "valor" => "id",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
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
        "valor" => 'if($linha["idacao"] == 2) {
				  return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idmonitora"]."/visualizar\" data-placement=\"left\" rel=\"tooltip facebox\"><i class=\"icon-eye-open\"></i> ".$idioma["tabela_opcoes"]."</a>";
				} else {
				  return "--";
				}',
        "busca_botao" => true,
        "tamanho" => "90"
    )
);

$config["listagem_log"] = array(
    array(
        "id" => "lista_campo",
        "name" => "campo",
        "variavel_lang" => "lista_campo",
        "tipo" => "php",
        "valor" => 'return $linha["campo"];',
        "busca_botao" => true
    ),
    array(
        "id" => "lista_de",
        "name" => "de",
        "variavel_lang" => "lista_de",
        "tipo" => "php",
        "valor" => 'if(!$linha["de"] && $linha["de"] !== "0") return $idioma["vazio"]; else return $linha["de"];',
        "busca_botao" => true
    ),
    array(
        "id" => "lista_para",
        "name" => "para",
        "variavel_lang" => "lista_para",
        "tipo" => "php",
        "valor" => 'if(!$linha["para"] && $linha["para"] !== "0") return $idioma["vazio"]; else return $linha["para"];',
        "busca_botao" => true
    )
);
?>