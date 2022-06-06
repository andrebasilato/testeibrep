<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idtipo",
        "variavel_lang" => "tabela_idtipo",
        "tipo" => "php",
        "coluna_sql" => "idtipo",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idtipo"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idtipo"]."</span> <i class=\"novo\"></i>";
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
        "coluna_sql" => "nome",
        "valor" => "nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    /*array("id" => "obrigatorio_workflow",
                    "variavel_lang" => "tabela_obrigatorio_workflow",
                    "tipo" => "php",
                    "coluna_sql" => "obrigatorio_workflow",
                    "valor" => 'if($linha["obrigatorio_workflow"] == "S") {
                                    return "<span class=\"label label-success\">Sim</span>";
                                } else {
                                    return "<span class=\"label label-important\">Não</span>";
                                }',
                    "busca" => true,
                    "busca_tipo" => "select",
                    "busca_class" => "inputPreenchimentoCompleto",
                    "busca_array" => "sim_nao",
                    "busca_metodo" => 1,
                    "tamanho" => 60),  */
    array(
        "id" => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo" => "php",
        "coluna_sql" => "ativo_painel",
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
        "id" => "exibir_ava",
        "variavel_lang" => "tabela_exibir_ava",
        "tipo" => "php",
        "coluna_sql" => "exibir_ava",
        "valor" => 'if($linha["exibir_ava"] == "S") {
                  return "<span data-original-title=\"".$GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["exibir_ava"]]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">S</span>";
                } else {
                  return "<span data-original-title=\"".$GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["exibir_ava"]]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">N</span>";
                }',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "sim_nao",
        "busca_metodo" => 1,
        "tamanho" => 80
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
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idtipo"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
?>