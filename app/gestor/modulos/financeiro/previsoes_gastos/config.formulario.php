<?php
$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
$sqlSindicato .= ' order by nome_abreviado';

// Array de configuração para a formulario			
$config["formulario"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos"        => array(
            array(
                "id"               => "idsindicato",
                "nome"             => "idsindicato",
                "nomeidioma"       => "form_idsindicato",
                "tipo"             => "select",
                "sql"              => $sqlSindicato,
                "sql_valor"        => "idsindicato",
                "sql_label"        => "nome_abreviado",
                "valor"            => "idsindicato",
                "validacao" => array("required" => "idsindicato_vazio"),
                "referencia_label" => "cadastro_sindicato",
                "referencia_link"  => "/gestor/cadastros/sindicatos",
                "banco"            => true,
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
                "id"           => "form_valor", // Id do atributo HTML
                "nome"         => "valor", // Name do atributo HTML
                "nomeidioma"   => "form_valor", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13'",
                "decimal"      => true,
                "valor"        => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),           
			array(
                "id" => "data",
                "nome" => "data",
                "nomeidioma" => "form_data",
                "tipo" => "input",
                "valor" => "data",
                "validacao" => array("required" => "data_vazio"),
                "valor_php" => 'if($dados["data"] && $dados["data"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),            
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
            array(
                "id"           => "form_observacoes",
                "nome"         => "observacoes",
                "nomeidioma"   => "form_observacoes",
                "tipo"         => "text",
                "valor"        => "observacoes",
                "class"        => "xxlarge",
                "banco"        => true,
                "banco_string" => true
            )
        )
    )
);