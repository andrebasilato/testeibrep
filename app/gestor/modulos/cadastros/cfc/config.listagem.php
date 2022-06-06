<?php
// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        "id" => "idescola",
        "variavel_lang" => "tabela_idescola",
        "tipo" => "php",
        "coluna_sql" => "p.idescola",
        "valor" => '
			$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
			if($diferenca > 24) {
				return "<span title=\"$diferenca\">".$linha["idescola"]."</span>";
			} else {
				return "<span title=\"$diferenca\">".$linha["idescola"]."</span> <i class=\"novo\"></i>";
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
        "coluna_sql" => "p.nome_fantasia",
        "valor" => "nome_fantasia",
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
        "id" => "documento",
        "variavel_lang" => "tabela_documento",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "p.documento",
        "valor" => "documento",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1
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
        "id" => "sindicato",
        "variavel_lang" => "tabela_sindicato",
        "tipo" => "banco",
        "evento" => "maxlength='100'",
        "coluna_sql" => "i.nome_abreviado",
        "valor" => "sindicato",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "telefone", // Id do atributo
        "variavel_lang" => "tabela_telefone", // Referencia a variavel de idioma
        "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "p.telefone", // Nome da coluna no banco de dados
        "valor" => "telefone", // Se for do tipo banco o valor é o nome da coluna da tabela, se for do tipo php o valor é um script php
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    array(
        "id" => "Acessou",
        "variavel_lang" => "tabela_acessou",
        "tipo" => "php",
        "coluna_sql" => "p.ultimo_view",
        "valor" => 'if($linha["ultimo_view"] != null) {
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
        "id" => "contrato_aceito",
        "variavel_lang" => "tabela_contrato_aceito",
        "tipo" => "php",
        "coluna_sql" => "p.contratos_aceitos",
        "valor" => 'if($linha["contratos_aceitos"] == "S") {
				  return "<span data-original-title=\"".$idioma["todos_contratos_aceitos"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">Sim</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["contratos_nao_aceitos"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">Não</span>";
				}',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "igual_maior",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    array(
        "id" => "ativo_painel",
        "variavel_lang" => "tabela_ativo_painel",
        "tipo" => "php",
        "coluna_sql" => "p.ativo_painel",
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
        "id" => "acesso_bloqueado",
        "variavel_lang" => "tabela_acesso_bloqueado",
        "tipo" => "php",
        "coluna_sql" => "p.acesso_bloqueado",
        "valor" => 'if($linha["acesso_bloqueado"] == "S") {
				  return "<span data-original-title=\"".$idioma["bloqueado"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">S</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["liberado"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">N</span>";
				}',
        "busca" => true,
        "busca_tipo" => "select",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_array" => "sim_nao",
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
        "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$linha["idescola"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
        "busca_botao" => true,
        "tamanho" => "80"
    )
);
?>
