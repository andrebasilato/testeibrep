<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';

// Array de configuração para a listagem
$config["listagem"] = array(

    array("id" => "tabela_numero",
        "variavel_lang" => "tabela_numero",
        "tipo" => "php",
        "coluna_sql" => "v.idvendedor",
        "valor" => 'return "<a href=\"/".$this->url["0"]."/cadastros/corretores/".$linha["idvendedor"]."/editar\" target=\"_blank\">".$linha["idvendedor"]."</a>";',
        "tamanho" => "60"
    ),

    array("id" => "tabela_vendedor",
        "variavel_lang" => "tabela_vendedor",
        "tipo" => "banco",
        "coluna_sql" => "v.nome",
        "valor" => "nome"),

    array("id" => "tabela_documento",
        "variavel_lang" => "tabela_documento",
        "tipo" => "banco",
        "coluna_sql" => "v.documento",
        "valor" => "documento"),

    array("id" => "tabela_datanasc",
        "variavel_lang" => "tabela_datanasc",
        "tipo" => "php",
        "coluna_sql" => "v.data_nasc",
        "valor" => 'if($linha["data_nasc"] && $linha["data_nasc"] != "0000-00-00") return formataData($linha["data_nasc"], "br", 0)',),

    array("id" => "tabela_celular",
        "variavel_lang" => "tabela_celular",
        "tipo" => "banco",
        "coluna_sql" => "v.celular",
        "valor" => "celular"),

    array("id" => "tabela_telefone",
        "variavel_lang" => "tabela_telefone",
        "tipo" => "banco",
        "coluna_sql" => "v.telefone",
        "valor" => "telefone"),

    array("id" => "tabela_email",
        "variavel_lang" => "tabela_email",
        "tipo" => "banco",
        "coluna_sql" => "v.email",
        "valor" => "email"),

    array("id" => "tabela_venda_bloqueada",
        "variavel_lang" => "tabela_venda_bloqueada",
        "tipo" => "php",
        "coluna_sql" => "v.venda_bloqueada",
        "valor" => 'return $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["venda_bloqueada"]]'),

	array("id" => "tabela_ativo_login",
        "variavel_lang" => "tabela_ativo_login",
        "tipo" => "php",
        "coluna_sql" => "v.ativo_login",
        "valor" => 'return $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$linha["ativo_login"]]'),


    array("id" => "tabela_cep",
        "variavel_lang" => "tabela_cep",
        "tipo" => "banco",
        "coluna_sql" => "v.cep",
        "valor" => "cep"),

    array("id" => "tabela_endereco",
        "variavel_lang" => "tabela_endereco",
        "tipo" => "banco",
        "coluna_sql" => "v.endereco",
        "valor" => "endereco"),

    array("id" => "tabela_bairro",
        "variavel_lang" => "tabela_bairro",
        "tipo" => "banco",
        "coluna_sql" => "v.bairro",
        "valor" => "bairro"),

    array("id" => "tabela_nu",
        "variavel_lang" => "tabela_nu",
        "tipo" => "banco",
        "coluna_sql" => "v.numero",
        "valor" => "numero"),

    array("id" => "tabela_regiao",
        "variavel_lang" => "tabela_regiao",
        "tipo" => "banco",
        "coluna_sql" => "v.regiao",
        "valor" => "regiao"),

    array("id" => "tabela_cidade",
        "variavel_lang" => "tabela_cidade",
        "tipo" => "banco",
        "coluna_sql" => "v.cidade",
        "valor" => "cidade"),

    array("id" => "tabela_estado",
        "variavel_lang" => "tabela_estado",
        "tipo" => "banco",
        "coluna_sql" => "v.estado",
        "valor" => "estado"),

    array("id" => "tabela_placa",
        "variavel_lang" => "tabela_placa",
        "tipo" => "banco",
        "coluna_sql" => "v.placa_carro",
        "valor" => "placa_carro"),

    array("id" => "tabela_cartao_combustivel",
        "variavel_lang" => "tabela_cartao_combustivel",
        "tipo" => "banco",
        "coluna_sql" => "v.cartao_combustivel",
        "valor" => "cartao_combustivel"),

    array("id" => "tabela_observacoes",
        "variavel_lang" => "tabela_observacoes",
        "tipo" => "banco",
        "coluna_sql" => "v.observacoes",
        "valor" => "observacoes"),

);


// Array de configuração para a formulario
$config["formulario"] = array(
    array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_nome",
                "nome" => "q[2|v.nome]",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "class" => "span5",
                "evento" => "maxlength='100'"
            ),
			array(
                "id" => "form_documento",
                "nome" => "q[2|v.documento]",
                "nomeidioma" => "form_documento",
                "tipo" => "input",
                "valor" => "documento",
                "class" => "span3",
				"numerico" => true,
                "evento" => "maxlength='14'"
            ),
            /*array(
                "id" => "idsindicato",
                "nome" => "q[1|vi.idsindicato]",
                "nomeidioma" => "form_idsindicato",
                "tipo" => "select",
                "sql" => $sqlSindicato, // SQL que alimenta o select
                "sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
                "valor" => "idsindicato",
                "sql_filtro" => "select * from sindicatos where idsindicato=%",
                "sql_filtro_label" => "nome_abreviado"
            ),*/
			array(
				"id" => "form_idsindicato",
				"nome" => "idsindicato",
				"nomeidioma" => "form_idsindicato",
				"tipo" => "checkbox",
				"sql" => $sqlSindicato, // SQL que alimenta o select
				"sql_ordem_campo" => "nome_abreviado",
				"sql_ordem" => "asc",
				"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
				"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
				"valor" => "idsindicato",
				"sql_filtro" => "select * from sindicatos where idsindicato = %",
				"sql_filtro_label" => "nome_abreviado",
				"class" => "span4",
			  ),
            array(
                "id" => "idestado",
                "nome" => "q[1|v.idestado]",
                "nomeidioma" => "form_idestado",
                "tipo" => "select",
                "sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idestado",
                "sql_filtro" => "select * from estados where idestado=%",
                "sql_filtro_label" => "nome"
            ),
            array(
                "id" => "idcidade",
                "nome" => "q[1|v.idcidade]",
                "nomeidioma" => "form_idcidade",
                "json" => true,
                "json_idpai" => "idestado",
                "json_url" => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . "/ajax_cidades/",
                "json_input_pai_vazio" => "form_selecione_estado",
                "json_input_vazio" => "form_selecione_cidade",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idcidade",
                "sql_filtro" => "select * from cidades where idcidade=%",
                "sql_filtro_label" => "nome"
            ),
			/*array(
                "id" => "idgrupo",
                "nome" => "q[1|gvv.idgrupo]",
                "nomeidioma" => "form_idgrupo",
                "tipo" => "select",
                "sql" => 'select idgrupo, nome from grupos_vendedores where ativo = "S" and ativo_painel = "S"', // SQL que alimenta o select
                "sql_valor" => "idgrupo", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idgrupo",
                "sql_filtro" => "select * from grupos_vendedores where idgrupo=%",
                "sql_filtro_label" => "nome"
            ),*/
			array(
				"id" => "ativo_login",
				"nome" => "q[1|v.ativo_login]",
				"nomeidioma" => "form_ativo_login",
				"tipo" => "select",
				"array" => "sim_nao", // Array que alimenta o select
				"class" => "span2",
				"valor" => "ativo_login",
				"sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
				"sql_filtro_label" => "sim_nao"
				),
        )
    )
);