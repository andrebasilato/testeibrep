<?php

if ($usuario["gestor_sindicato"] <> "S") {
    $sql_vendedor = "SELECT v.idvendedor, v.nome
				FROM vendedores v
					inner join vendedores_sindicatos vi on (v.idvendedor = vi.idvendedor)
				WHERE v.ativo = 'S' and vi.idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";

    $sql_cursos = "select idcurso, nome from cursos where ativo = 'S' and ativo_painel='S'";

} else {
    $sql_vendedor = "SELECT idvendedor, nome FROM vendedores WHERE ativo = 'S'";
    $sql_cursos = "select idcurso, nome from cursos where ativo = 'S' and ativo_painel='S'";
}

// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
        'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
        'campos' => array( // Campos do formulario

            array(
                "id" => "idvendedor",
                "nome" => "idvendedor",
                "nomeidioma" => "form_idvendedor",
                "tipo" => "select",
                "sql" => $sql_vendedor, // SQL que alimenta o select
                "sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "sql_ordem_campo" => "nome",
                "sql_ordem" => "asc",
                "valor" => "idvendedor",
                "validacao" => array("required" => "idvendedor_vazio"),
                "referencia_label" => "cadastro_vendedores",
                "referencia_link" => "/gestor/cadastros/atendentes",
                "banco" => true
            ),


            array(
                "id" => "form_cursos",
                "nome" => "cursos",
                "nomeidioma" => "form_cursos",
                "tipo" => "select",
                "sql" => $sql_cursos, // SQL que alimenta o select
                "sql_ordem_campo" => "nome",
                "sql_ordem" => "asc",
                "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idcurso",
                "class" => "form_curso_usar"
            ),

            array(
                "id" => "form_nome",
                "nome" => "nome",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "validacao" => array("required" => "nome_vazio"),
                "class" => "span3",
                "banco" => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),

            array(
                "id" => "form_email",
                "nome" => "email",
                "nomeidioma" => "form_email",
                "tipo" => "input",
                "valor" => "email",
                "class" => "span3",
                "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "telefone",
                "nome" => "telefone",
                "nomeidioma" => "form_telefone",
                "tipo" => "input",
                "valor" => "telefone",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "data_nasc",
                "nome" => "data_nasc",
                "nomeidioma" => "form_nascimento",
                "tipo" => "input",
                "valor" => "data_nasc",
                //"validacao" => array("required" => "data_nasc_vazio"),
                "valor_php" => 'if($dados["data_nasc"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),

            array(
                "id" => "idmidia",
                "nome" => "idmidia",
                "nomeidioma" => "form_midia",
                "tipo" => "select",
                "sql" => "SELECT idmidia, nome FROM midias_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idmidia", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idmidia",
                //"validacao" => array("required" => "midia_vazio"),
                "banco" => true
            ),
            array(
                "id" => "idlocal",
                "nome" => "idlocal",
                "nomeidioma" => "form_local",
                "tipo" => "select",
                "sql" => "SELECT idlocal, nome FROM locais_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idlocal", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idlocal",
                //"validacao" => array("required" => "local_vazio"),
                "banco" => true
            ),

            array(
                "id" => "form_email_secundario",
                "nome" => "email_secundario",
                "nomeidioma" => "form_email_secundario",
                "tipo" => "input",
                "valor" => "email_secundario",
                "class" => "span3",
                "validacao" => array("valid_email" => "email_secundario_invalido"),
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "celular",
                "nome" => "celular",
                "nomeidioma" => "form_celular",
                "tipo" => "input",
                "valor" => "celular",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                'id' => 'geolocalizacao_endereco',
                'nome' => 'geolocalizacao_endereco',
                'nomeidioma' => 'form_endereco_rua',
                'tipo' => 'input',
                'valor' => 'return $dados["geolocalizacao_endereco"];',
                'banco_string' => true,
                "class" => "span6",
                //'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'geolocalizacao_cep',
                'nome' => 'geolocalizacao_cep',
                'nomeidioma' => 'form_endereco_cep',
                'tipo' => 'input',
                'valor' => 'return $dados["geolocalizacao_cep"];',
                'banco_string' => true,
                //'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'geolocalizacao_cidade',
                'nome' => 'geolocalizacao_cidade',
                'nomeidioma' => 'form_endereco_cidade',
                'tipo' => 'input',
                'valor' => 'return $dados["geolocalizacao_cidade"];',
                'banco_string' => true,
                //'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'geolocalizacao_estado',
                'nome' => 'geolocalizacao_estado',
                'nomeidioma' => 'form_endereco_estado',
                'tipo' => 'input',
                'valor' => 'return $dados["geolocalizacao_estado"];',
                'banco_string' => true,
                //'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),

            /*array(
                "id" => "motivo_visita",
                "nome" => "motivo_visita",
                "nomeidioma" => "form_motivo",
                "tipo" => "text",
                "editor" => true,
                "valor" => "motivo_visita",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),*/

			array(
                "id" => "idmotivo",
                "nome" => "idmotivo",
                "nomeidioma" => "form_motivo",
                "tipo" => "select",
                "sql" => "SELECT idmotivo, nome FROM motivos_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idmotivo", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idmotivo",
                "banco" => true,
				"banco_string" => true
            ),

            array(
                "id" => "form_observacoes",
                "nome" => "observacoes",
                "nomeidioma" => "form_observacoes",
                "tipo" => "text",
                "editor" => true,
                "valor" => "observacoes",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                'id' => 'geolocation',
                'nome' => 'geolocation',
                'nomeidioma' => 'geolocation',
                'tipo' => 'hidden',
                'valor' => 'geolocation',
                'banco' => true
            )
        )
    )
);
// Array de configuração para a formulario
$config['formulario_editar'] = array(
    array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario

            array(
                "id" => "idvendedor",
                "nome" => "idvendedor",
                "nomeidioma" => "form_idvendedor",
                "tipo" => "select",
                "sql" => $sql_vendedor, // SQL que alimenta o select
                "sql_ordem_campo" => "nome",
                "sql_ordem" => "asc",
                "sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "referencia_label" => "cadastro_vendedores",
                "referencia_link" => "/gestor/cadastros/atendentes",
                "valor" => "idvendedor",
                "validacao" => array("required" => "idvendedor_vazio"),
                "banco" => true
            ),

            array(
                "id" => "form_cursos",
                "nome" => "cursos",
                "nomeidioma" => "form_cursos",
                "tipo" => "checkbox",
                "sql" => $sql_cursos, // SQL que alimenta o select
                "sql_ordem_campo" => "nome",
                "sql_ordem" => "asc",
                "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idcurso"
            ),
            array(
                "id" => "form_nome",
                "nome" => "nome",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "class" => "span3",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "form_email",
                "nome" => "email",
                "nomeidioma" => "form_email",
                "tipo" => "input",
                "valor" => "email",
                "class" => "span3",
                "legenda" => "@",
                //"evento" => "disabled",
				"banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "telefone",
                "nome" => "telefone",
                "nomeidioma" => "form_telefone",
                "tipo" => "input",
                "valor" => "telefone",
                "mascara" => "(99) 9999-9999",
                //"evento" => "disabled",
				"banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "data_nasc",
                "nome" => "data_nasc",
                "nomeidioma" => "form_nascimento",
                "tipo" => "input",
                "valor" => "data_nasc",
                "valor_php" => 'if($dados["data_nasc"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "evento" => "disabled",
            ),

            array(
                "id" => "idmidia",
                "nome" => "idmidia",
                "nomeidioma" => "form_midia",
                "tipo" => "select",
                "sql" => "SELECT idmidia, nome FROM midias_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idmidia", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idmidia",
                //"validacao" => array("required" => "midia_vazio"),
                "banco" => true
            ),

            array(
                "id" => "idlocal",
                "nome" => "idlocal",
                "nomeidioma" => "form_local",
                "tipo" => "select",
                "sql" => "SELECT idlocal, nome FROM locais_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idlocal", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idlocal",
                //"validacao" => array("required" => "local_vazio"),
                "banco" => true
            ),

            array(
                "id" => "form_email_secundario",
                "nome" => "email_secundario",
                "nomeidioma" => "form_email_secundario",
                "tipo" => "input",
                "valor" => "email_secundario",
                "class" => "span3",
                "validacao" => array("valid_email" => "email_secundario_invalido"),
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "celular",
                "nome" => "celular",
                "nomeidioma" => "form_celular",
                "tipo" => "input",
                "valor" => "celular",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),

            array(
                "id" => "form_situacao",
                "nome" => "situacao",
                "nomeidioma" => "form_situacao",
                "botao_hide" => true,
                "tipo" => "select",
                "array" => "situacao_visita_vendedores", // Array que alimenta o select
                "class" => "span2",
                "valor" => "situacao",
                //"validacao" => array("required" => "situacao_vazio"),
                "ajudaidioma" => "form_situacao_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "geolocation",
                "nome" => "geolocation",
                "nomeidioma" => "form_local",
                "tipo" => "hidden",
                'valor' => 'return $dados["geolocation"];',
                'banco' => true,
                "banco_string" => true
            ),
            array(
                'id' => 'geolocalizacao_endereco',
                'nome' => 'geolocalizacao_endereco',
                'nomeidioma' => 'form_endereco_rua',
                'tipo' => 'input',
                "valor" => "email",
                'valor' => "geolocalizacao_endereco",
                "class" => "span6",
                //'evento' => 'disabled',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'geolocalizacao_cep',
                'nome' => 'geolocalizacao_cep',
                'nomeidioma' => 'form_endereco_cep',
                'tipo' => 'input',
                'valor' => "geolocalizacao_cep",
                "mascara" => "99999-999",
                //'evento' => 'disabled',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'idcidade',
                'nome' => 'idcidade',
                'nomeidioma' => 'form_endereco_cidade',
                'tipo' => 'input',
                'valor' => "cidade",
                'banco_string' => true,
                //'evento' => 'disabled',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'idestado',
                'nome' => 'idestado',
                'nomeidioma' => 'form_endereco_estado',
                'tipo' => 'input',
                'valor' => "estado",
                'banco_string' => true,
                //'evento' => 'disabled',
                'banco' => true,
                'banco_string' => true
            ),

            /*array(
                "id" => "motivo_visita",
                "nome" => "motivo_visita",
                "nomeidioma" => "form_motivo",
                "tipo" => "text",
                "editor" => true,
                "valor" => "motivo_visita",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),*/

			array(
                "id" => "idmotivo",
                "nome" => "idmotivo",
                "nomeidioma" => "form_motivo",
                "tipo" => "select",
                "sql" => "SELECT idmotivo, nome FROM motivos_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idmotivo", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idmotivo",
                "banco" => true,
				"banco_string" => true
            ),

            array(
                "id" => "form_observacoes",
                "nome" => "observacoes",
                "nomeidioma" => "form_observacoes",
                "tipo" => "text",
                "editor" => true,
                "valor" => "observacoes",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),
        )
    )
);