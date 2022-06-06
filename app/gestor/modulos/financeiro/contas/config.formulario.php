<?php
$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
$sqlSindicato .= ' order by nome_abreviado';

$sqlFornecedor = 'select f.idfornecedor, CONCAT(f.nome," (",i.nome_abreviado,")") as nome from fornecedores f inner join sindicatos i on (f.idsindicato = i.idsindicato)  where f.ativo = "S" and i.ativo="S" ';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlFornecedor .= ' and f.idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
$sqlFornecedor .= ' order by f.nome';

$sqlContaCorrente = 'select
						cc.idconta_corrente,
						concat(b.nome," -> ",cc.nome) as nome
					from
						contas_correntes cc
						inner join bancos b on (cc.idbanco = b.idbanco)
						inner join contas_correntes_sindicatos cci on (cc.idconta_corrente = cci.idconta_corrente and cci.ativo = "S")
					where
						cc.ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlContaCorrente .= ' and cci.idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
$sqlContaCorrente .= ' group by cc.idconta_corrente order by nome ';

// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos"        => array(
            /*array(
                "id" => "form_idmantenedora",
                "nome" => "idmantenedora",
                "nomeidioma" => "form_idmantenedora",
                "tipo" => "select",
                "sql" => "SELECT idmantenedora, nome_fantasia FROM mantenedoras where ativo = 'S' AND ativo_painel = 'S'",
                "sql_valor" => "idmantenedora",
                "sql_label" => "nome_fantasia",
                "valor" => "idmantenedora",
                //"validacao" => array("required" => "idmantenedora_vazio"),
                "referencia_label" => "cadastro_mantenedoras",
                "referencia_link" => "/gestor/cadastros/mantenedoras",
                "banco" => true
            ),
            */
            array(
                "id"               => "idsindicato",
                "nome"             => "idsindicato",
                "nomeidioma"       => "form_idsindicato",
                "tipo"             => "select",
                "sql"              => $sqlSindicato,
                "sql_valor"        => "idsindicato",
                "sql_label"        => "nome_abreviado",
                "valor"            => "idsindicato",
                //"validacao" => array("required" => "idsindicato_vazio"),
                "referencia_label" => "cadastro_sindicato",
                "referencia_link"  => "/gestor/cadastros/sindicatos",
                "banco"            => true,
                "validacao"        => array("required" => "idsindicato_vazio"),
            ),
            array(
                "id"               => "form_idescola",
                "nome"             => "idescola",
                "nomeidioma"       => "form_idescola",
                "json"                 => true,
                "json_idpai"           => "idsindicato",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_escolas/" . $linha['idconta'],
                "json_input_pai_vazio" => "form_selecione_inst",
                "json_input_vazio"     => "form_selecione_instescola",
                "json_campo_exibir"    => "razao_social",
                "tipo"             => "select",
                "sql_valor"        => "idescola",
                "sql_label"        => "razao_social",
                "valor"            => "idescola",
                //"validacao" => array("required" => "idsindicato_vazio"),
                "referencia_label" => "cadastro_escola",
                "referencia_link"  => "/gestor/cadastros/cfc",
                "banco"            => true,
                "banco_string"     => true,
            ),
             array(
                "id"           => "form_numdoc", // Id do atributo HTML
                "nome"         => "numero_documento", // Name do atributo HTML
                "nomeidioma"   => "form_numdoc", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='20'",
                "valor"        => "numero_documento", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_nome",
                "nome"         => "nome",
                "nomeidioma"   => "form_nome",
                "tipo"         => "input",
                "valor"        => "nome",
                "validacao"    => array("required" => "nome_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_tipo",
                "nome"         => "tipo",
                "nomeidioma"   => "form_tipo",
                "botao_hide"   => true,
                "iddivs"       => array("idcliente", "idfornecedor", "idproduto"),
                "iddiv"        => "idcliente",
                "iddiv2"       => "idfornecedor",
                "iddiv3"       => "idproduto",
                "tipo"         => "select",
                "array"        => "tipo_contas", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "tipo",
                "validacao"    => array("required" => "tipo_vazio"),
                "ajudaidioma"  => "form_tipo_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),
            /*array(
                "id"               => "idcliente",
                "nome"             => "idcliente",
                "nomeidioma"       => "form_idcliente",
                "tipo"             => "select",
                "sql"              => "SELECT idcliente, nome FROM clientes where ativo = 'S' order by nome asc ",
                "sql_valor"        => "idcliente",
                "sql_label"        => "nome",
                "valor"            => "idcliente",
                //"validacao" => array("required" => "idpessoa_vazio"),
                "referencia_label" => "cadastro_cliente",
                "referencia_link"  => "/gestor/cadastros/clientes",
                "banco"            => true,
                "select_hidden"    => true,
                "banco_string"     => true,
            ),*/
            array(
                "id"               => "idfornecedor",
                "nome"             => "idfornecedor",
                "nomeidioma"       => "form_idfornecedor",
                "tipo"             => "select",
                "sql"              => $sqlFornecedor,
                "sql_valor"        => "idfornecedor",
                "sql_label"        => "nome",
                "valor"            => "idfornecedor",
                //"validacao" => array("required" => "idfornecedor_vazio"),
                "referencia_label" => "cadastro_fornecedor",
                "referencia_link"  => "/gestor/financeiro/fornecedores",
                "banco"            => true,
                "select_hidden"    => true,
                "banco_string"     => true,
            ),
            array(
                "id"                   => "idproduto",
                "nome"                 => "idproduto",
                "nomeidioma"           => "form_idproduto",
                "tipo"                 => "select",
                "sql"                  => "SELECT idproduto, nome FROM produtos where ativo = 'S' AND ativo_painel = 'S' order by nome",
                "sql_valor"            => "idproduto",
                "sql_label"            => "nome",
                "valor"                => "idproduto",
                "json"                 => true,
                "json_idpai"           => "idfornecedor",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_produtos/" . $linha['idfornecedor'],
                "json_input_pai_vazio" => "form_selecione_fornecedor",
                "json_input_vazio"     => "form_selecione_produto",
                "json_campo_exibir"    => "nome",
                //"validacao" => array("required" => "idproduto_vazio"),
                "referencia_label"     => "cadastro_produto",
                "referencia_link"      => "/gestor/financeiro/produtos",
                "banco"                => true,
                "select_hidden"        => true,
                "banco_string"         => true,
            ),
            array(
                "id"           => "vencimento",
                "nome"         => "data_vencimento",
                "nomeidioma"   => "form_vencimento",
                "tipo"         => "input",
                "valor"        => "data_vencimento",
                "validacao"    => array("required" => "vencimento_vazio"),
                "valor_php"    => 'if($dados["data_vencimento"] && $dados["data_vencimento"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id"           => "form_valor", // Id do atributo HTML
                "nome"         => "valor", // Name do atributo HTML
                "nomeidioma"   => "form_valor", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularParcelas()'",
                "decimal"      => true,
                "valor"        => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_parcela", // Id do atributo HTML
                "nome"         => "parcela", // Name do atributo HTML
                "nomeidioma"   => "form_parcela", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='2'",
                "numerico"     => true,
                "valor"        => "parcela", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "parcela_vazio"), // Validação do campo
                "class"        => "span1", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_total_parcelas", // Id do atributo HTML
                "nome"         => "total_parcelas", // Name do atributo HTML
                "nomeidioma"   => "form_total_parcelas", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='2'",
                "numerico"      => true,
                "valor"        => "total_parcelas", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "total_parcelas_vazio"), // Validação do campo
                "class"        => "span1", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_forma_pagamento",
                "nome"         => "forma_pagamento",
                "nomeidioma"   => "form_forma_pagamento",
                "botao_hide"   => true,
                "iddivs"       => array("idbandeira", "autorizacao_cartao", "idbanco", "agencia_cheque", "cc_cheque", "numero_cheque", "emitente_cheque"),
                "iddiv3"       => "idbandeira",
                "iddiv4"       => "autorizacao_cartao",
                "iddiv5"       => "idbanco",
                "iddiv6"       => "agencia_cheque",
                "iddiv7"       => "cc_cheque",
                "iddiv8"       => "numero_cheque",
                "iddiv9"       => "emitente_cheque",
                "tipo"         => "select",
                "array"        => "forma_pagamento_conta", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "forma_pagamento",
                "validacao"    => array("required" => "forma_pagamento_vazio"),
                "ajudaidioma"  => "form_forma_pagamento_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"               => "form_idbandeira",
                "nome"             => "idbandeira",
                "nomeidioma"       => "form_idbandeira",
                "tipo"             => "select",
                "sql"              => "SELECT idbandeira, nome FROM bandeiras_cartoes where ativo = 'S' AND ativo_painel = 'S'",
                "sql_valor"        => "idbandeira",
                "sql_label"        => "nome",
                "valor"            => "idbandeira",
                "validacao"        => array("required" => "idbandeira_vazio"),
                "referencia_label" => "cadastro_bandeiras",
                "referencia_link"  => "/gestor/financeiro/bandeirascartoes",
                "banco"            => true,
                "select_hidden"    => true,
                "banco_string"     => true,
            ),
            array(
                "id"           => "form_autorizacao_cartao",
                "nome"         => "autorizacao_cartao",
                "nomeidioma"   => "form_autorizacao_cartao",
                "tipo"         => "input",
                "valor"        => "autorizacao_cartao",
                //"validacao" => array("required" => "autorizacao_cartao_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"               => "form_idbanco",
                "nome"             => "idbanco",
                "nomeidioma"       => "form_idbanco",
                "tipo"             => "select",
                "sql"              => "SELECT idbanco, nome FROM bancos where ativo = 'S' AND ativo_painel = 'S'",
                "sql_valor"        => "idbanco",
                "sql_label"        => "nome",
                "valor"            => "idbanco",
                "referencia_label" => "cadastro_bancos",
                "validacao"        => array("required" => "idbanco_vazio"),
                "referencia_link"  => "/gestor/financeiro/bancos",
                "banco"            => true,
                "select_hidden"    => true,
                "banco_string"     => true,
            ),
            array(
                "id"           => "form_agencia_cheque",
                "nome"         => "agencia_cheque",
                "nomeidioma"   => "form_agencia_cheque",
                "tipo"         => "input",
                "valor"        => "agencia_cheque",
                "validacao"    => array("required" => "agencia_cheque_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"           => "form_cc_cheque",
                "nome"         => "cc_cheque",
                "nomeidioma"   => "form_cc_cheque",
                "tipo"         => "input",
                "valor"        => "cc_cheque",
                "validacao"    => array("required" => "cc_cheque_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"           => "form_numero_cheque",
                "nome"         => "numero_cheque",
                "nomeidioma"   => "form_numero_cheque",
                "tipo"         => "input",
                "valor"        => "numero_cheque",
                "validacao"    => array("required" => "numero_cheque_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"           => "form_emitente_cheque",
                "nome"         => "emitente_cheque",
                "nomeidioma"   => "form_emitente_cheque",
                "tipo"         => "input",
                "valor"        => "emitente_cheque",
                "validacao"    => array("required" => "emitente_cheque_vazio"),
                "class"        => "span4",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"           => "form_parcelas",
                "nome"         => "parcelas",
                "nomeidioma"   => "form_parcelas",
                "ajudaidioma"  => "form_parcelas_ajuda",
                "evento"       => "maxlength='2' onblur='calcularParcelas()'",
                "tipo"         => "input",
                "valor"        => "parcelas",
                "class"        => "span1",
                "numerico"     => true,
                "banco"        => true,
                "banco_string" => true
            ),
            /*array(
                "id" => "form_valor_parcela", // Id do atributo HTML
                "nome" => "valor_parcela", // Name do atributo HTML
                "nomeidioma" => "form_valor_parcela", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "disabled='disabled'",
                "decimal" => true,
                "valor" => "valor_parcela", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_parcela_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),*/
            array(
                "id"           => "form_valor_juros", // Id do atributo HTML
                "nome"         => "valor_juros", // Name do atributo HTML
                "nomeidioma"   => "form_valor_juros", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularLiquido()'",
                "decimal"      => true,
                "valor"        => "valor_juros", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_juros_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_multa", // Id do atributo HTML
                "nome"         => "valor_multa", // Name do atributo HTML
                "nomeidioma"   => "form_valor_multa", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularLiquido()'",
                "decimal"      => true,
                "valor"        => "valor_multa", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_multa_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_outro", // Id do atributo HTML
                "nome"         => "valor_outro", // Name do atributo HTML
                "nomeidioma"   => "form_valor_outro", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularLiquido()'",
                "decimal"      => true,
                "valor"        => "valor_outro", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_outro_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_desconto", // Id do atributo HTML
                "nome"         => "valor_desconto", // Name do atributo HTML
                "nomeidioma"   => "form_valor_desconto", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularLiquido()'",
                "decimal"      => true,
                "valor"        => "valor_desconto", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_desconto_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_liquido", // Id do atributo HTML
                "nome"         => "valor_liquido", // Name do atributo HTML
                "nomeidioma"   => "form_valor_liquido", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor_liquido", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_liquido_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
			array(
                "id" => "data_pagamento",
                "nome" => "data_pagamento",
                "nomeidioma" => "form_data_pagamento",
                "tipo" => "input",
                "valor" => "data_pagamento",
                //"validacao" => array("required" => "data_pagamento_vazio"),
                "valor_php" => 'if($dados["data_pagamento"] && $dados["data_pagamento"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),

			array(
                "id"           => "documento",
                "nome"         => "documento",
                "nomeidioma"   => "form_documento",
                "tipo"         => "input",
                "valor"        => "documento",
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true
            ),
            /*
            array(
                "id" => "form_valor_pago", // Id do atributo HTML
                "nome" => "valor_pago", // Name do atributo HTML
                "nomeidioma" => "form_valor_pago", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='8'",
                "decimal" => true,
                "valor" => "valor_pago", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_pago_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            */
            array(
                "id"         => "idcategoria",
                "nome"       => "idcategoria",
                "nomeidioma" => "form_idcategoria",
				"json"                 => true,
                "json_idpai"           => "idsindicato",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_categorias/" . $linha['idconta'],
                "json_input_pai_vazio" => "form_selecione_inst",
                "json_input_vazio"     => "form_selecione_instcategoria",
                "json_campo_exibir"    => "nome",
                "tipo"       => "select",
                "valor"      => "idcategoria",
                "validacao"  => array("required" => "idcategoria_vazio"),
                "banco"      => true
            ),
            array(
                "id"                   => "idsubcategoria",
                "nome"                 => "idsubcategoria",
                "nomeidioma"           => "form_idsubcategoria",
                "tipo"                 => "select",
                "valor"                => "idsubcategoria",
                "validacao"            => array("required" => "idsubcategoria_vazio"),
                "banco"                => true
            ),
            array(
                "id"                   => "nome_unidade",
                "nome"                 => "nome_unidade",
                "nomeidioma"           => "nome_unidade",
                "tipo"                 => "input",
                "valor"                => "nome",
                "evento"             => "disabled",
                //"validacao"            => array("required" => "idsubcategoria_vazio"),
                //"banco"                => true
            ),
            array(
                "id"                   => "desc_unidade",
                "nome"                 => "desc_unidade",
                "nomeidioma"           => "desc_unidade",
                "tipo"                 => "input",
                "valor"                => "descricao",
                "class"                => "span6",
                "evento"             => "disabled",
                //"validacao"            => array("required" => "idsubcategoria_vazio"),
                //"banco"                => true
            ),
            array(
                "id"               => "form_idconta_corrente",
                "nome"             => "idconta_corrente",
                "nomeidioma"       => "form_idconta_corrente",
                "tipo"             => "select",
                "sql"              => $sqlContaCorrente,
                "sql_valor"        => "idconta_corrente",
                "sql_label"        => "nome",
                "valor"            => "idconta_corrente",
                //"validacao" => array("required" => "idconta_corrente_vazio"),
                "referencia_label" => "cadastro_contacorrente",
                "referencia_link"  => "/gestor/financeiro/contascorrentes",
                "banco"            => true,
                "banco_string"     => true,
            ),
			array(
                "id"         => "form_idcentro_custo",
                "nome"       => "idcentro_custo",
                "nomeidioma" => "form_idcentro_custo",
				"json"                 => true,
                "json_idpai"           => "idsindicato",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_centros_custos/" . $linha['idconta'],
                "json_input_pai_vazio" => "form_selecione_inst",
                "json_input_vazio"     => "form_selecione_instcentro",
                "json_campo_exibir"    => "nome",
                "tipo"       => "select",
                "valor"      => "idcentro_custo",
                "validacao"  => array("required" => "idcentro_custo_vazio"),
                "banco"      => true
            ),
            /*array(
                "id"               => "form_idcentro_custo",
                "nome"             => "idcentro_custo",
                "nomeidioma"       => "form_idcentro_custo",
                "tipo"             => "select",
                "sql"              => "SELECT idcentro_custo, nome FROM centros_custos where ativo = 'S' AND ativo_painel = 'S'",
                "sql_valor"        => "idcentro_custo",
                "sql_label"        => "nome",
                "valor"            => "idcentro_custo",
                "validacao"        => array("required" => "idcentro_custo_vazio"),
                "referencia_label" => "cadastro_centro_custo",
                "referencia_link"  => "/gestor/financeiro/centrosdecustos",
                "banco"            => true
            ),*/
            array(
                "id"           => "form_quantidade_centro_custo",
                "nome"         => "quantidade_centro_custo",
                "nomeidioma"   => "form_quantidade_centro_custo",
                "ajudaidioma"  => "form_quantidade_centro_custo_ajuda",
                "tipo"         => "input",
                "valor"        => "quantidade_centro_custo",
                "class"        => "span1",
                "numerico"     => true,
                "input_hidden" => true,
            ),
            array(
                "id"                   => "idordemdecompra",
                "nome"                 => "idordemdecompra",
                "nomeidioma"           => "form_idordemdecompra",
                "tipo"                 => "select",
                "valor"                => "idordemdecompra",
                "banco"                => true
            ),
            /*
            array(
                "id" => "form_idpessoa",
                "nome" => "idpessoa",
                "nomeidioma" => "form_idpessoa",
                "tipo" => "select",
                "sql" => "SELECT idpessoa, nome FROM pessoas where ativo = 'S' ",
                "sql_valor" => "idpessoa",
                "sql_label" => "nome",
                "valor" => "idpessoa",
                //"validacao" => array("required" => "idpessoa_vazio"),
                "referencia_label" => "cadastro_pessoa",
                "referencia_link" => "/gestor/cadastros/pessoas",
                "banco" => true
            ),
            array(
                "id" => "form_ativo_painel",
                "nome" => "ativo_painel",
                "nomeidioma" => "form_ativo_painel",
                "tipo" => "select",
                "array" => "ativo", // Array que alimenta o select
                "class" => "span2",
                "valor" => "ativo_painel",
                "validacao" => array("required" => "ativo_vazio"),
                "ajudaidioma" => "form_ativo_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            */
        )
    )
);

$config["formulario_quitar"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario

            array(
                "id"           => "form_valor", // Id do atributo HTML
                "nome"         => "valor", // Name do atributo HTML
                "nomeidioma"   => "form_valor", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularParcelas()' disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_juros", // Id do atributo HTML
                "nome"         => "valor_juros", // Name do atributo HTML
                "nomeidioma"   => "form_valor_juros", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8' onblur='calcularLiquido()' disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor_juros", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_juros_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_multa", // Id do atributo HTML
                "nome"         => "valor_multa", // Name do atributo HTML
                "nomeidioma"   => "form_valor_multa", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8' onblur='calcularLiquido()' disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor_multa", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_multa_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_outro", // Id do atributo HTML
                "nome"         => "valor_outro", // Name do atributo HTML
                "nomeidioma"   => "form_valor_outro", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8' onblur='calcularLiquido()' disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor_outro", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_outro_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_valor_desconto", // Id do atributo HTML
                "nome"         => "valor_desconto", // Name do atributo HTML
                "nomeidioma"   => "form_valor_desconto", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8' onblur='calcularLiquido()' disabled='disabled'",
                "decimal"      => true,
                "valor"        => "valor_desconto", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_desconto_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "data_pagamento",
                "nome"         => "data_pagamento",
                "nomeidioma"   => "form_data_pagamento",
                "tipo"         => "input",
                "valor"        => "data_pagamento",
                "validacao"    => array("required" => "data_pagamento_vazio"),
                "valor_php"    => 'if($dados["data_pagamento"] && $dados["data_pagamento"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id"           => "form_valor_pago", // Id do atributo HTML
                "nome"         => "valor_pago", // Name do atributo HTML
                "nomeidioma"   => "form_valor_pago", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8'",
                "decimal"      => true,
                "valor"        => "valor_pago", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_pago_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    )
);
?>