<?php
// Array de configuração para a listagem	
$config["listagem"] = array(
    array(
        "id" => "idemail",
        "variavel_lang" => "tabela_idemail",
        "tipo" => "php",
        "coluna_sql" => "el.idemail",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idemail"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idemail"]."</span> <i class=\"novo\"></i>";
			}
			',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "para_nome",
        "variavel_lang" => "tabela_para_nome",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "el.para_nome",
        "valor" => "para_nome",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "para_email",
        "variavel_lang" => "tabela_para_email",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "el.para_email",
        "valor" => "para_email",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "assunto",
        "variavel_lang" => "tabela_assunto",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "el.assunto",
        "valor" => "assunto",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
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
        "id" => "data_leitura",
        "variavel_lang" => "tabela_visualizado",
        "tipo" => "php",
        "coluna_sql" => "data_leitura",
        "valor" => 'if($linha["data_leitura"]) { return formataData($linha["data_leitura"],"br",1); } else { return "- -"; }',
        "tamanho" => "140"
    ),
    array(
        "id" => "qnt_reenvio",
        "variavel_lang" => "tabela_qnt_reenvio",
        "tipo" => "banco",
        "coluna_sql" => "el.qnt_reenvio",
        "valor" => "qnt_reenvio",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 80
    ),
    array(
        "id" => "reenviar",
        "variavel_lang" => "tabela_reenviar",
        "tipo" => "php",
        "valor" => 'if ($GLOBALS["linhaObj"]->verificaPermissao($GLOBALS["perfil"]["permissoes"], $GLOBALS["url"][2] . "|2",false)) {
                        return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idemail"]."/reenvio\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_reenviar"]."</a>";
                    } else {
                        return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_reenviar_tooltip_sem_permissao"]."\" onclick=\"alert(\'".$idioma["tabela_reenviar_tooltip_sem_permissao"]."\');\" data-placement=\"left\" rel=\"tooltip facebox\" disabled=\"disabled\">".$idioma["tabela_reenviar"]."</a>";
                    }',
        "tamanho" => "90"
    ),
    array("id" => "enviado", 
        "variavel_lang" => "tabela_enviado", 
        "tipo" => "php",
        "coluna_sql" => "enviado", 
        "valor" => 'if($linha["enviado"] == "S") {
                      return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Sim</span>";
                  } else {
                      return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Não</span>";
                  }',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "sim_nao",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    array(
        "id" => "opcoes",
        "variavel_lang" => "tabela_opcoes",
        "tipo" => "php",
        "valor" => ' return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idemail"]."/visualizar\" data-placement=\"left\" rel=\"tooltip facebox\"><i class=\"icon-eye-open\"></i> ".$idioma["tabela_opcoes"]."</a>";',
        "busca_botao" => true,
        "tamanho" => "90"
    )
);
?>