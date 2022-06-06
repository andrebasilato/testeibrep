<?php
// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_nome",
                "nome" => "nome",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "validacao" => array("required" => "nome_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "form_iddisciplina",
                "nome" => "iddisciplina",
                "nomeidioma" => "form_iddisciplina",
                "tipo" => "select",//depois ver essa regra para listar as disciplinas do curso
                "sql" => "select iddisciplina, nome from disciplinas where ativo = 'S' and ativo_painel = 'S' order by nome", // SQL que alimenta o select
                "sql_valor" => "iddisciplina", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "iddisciplina",
                'banco' => true,
                "validacao" => array("required" => "iddisciplina_vazio")//colocar tambem em idiomas
            ),
            array(
                "id" => "form_idprofessor",
                "nome" => "idprofessor",
                "nomeidioma" => "form_idprofessor",
                "tipo" => "select",//depois ver essa regra para listar as disciplinas do curso
                "sql" => "select idprofessor, nome from professores where ativo = 'S' and ativo_login = 'S' order by nome", // SQL que alimenta o select
                "sql_valor" => "idprofessor", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idprofessor",
                'banco' => true,
                "validacao" => array("required" => "idprofessor_vazio")//colocar tambem em idiomas
            ),
            array(
                'id' => 'data_aula',
                'nome' => 'data_aula',
                'nomeidioma' => 'form_data_aula',
                'tipo' => 'input',
                'valor' => 'data_aula',
                'valor_php' => 'if($dados["data_aula"]) return formataData("%s", "br", 0)',
                'class' => 'span2',
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'banco' => true,
                //'validacao' => array('required' => 'data_aula_vazio'),//colocar em idiomas
                'banco_php' => 'return formataData("%s", "en", 0)',
                'banco_string' => true
            ),
            array(
                'id' => 'hora_de',
                'nome' => 'hora_de',
                'nomeidioma' => 'form_hora_de',
                'tipo' => 'input',
                'valor' => 'hora_de',
                'class' => 'span1',
                "mascara" => "99:99", //Mascara do campo
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'hora_ate',
                'nome' => 'hora_ate',
                'nomeidioma' => 'form_hora_ate',
                'tipo' => 'input',
                'valor' => 'hora_ate',
                'class' => 'span1',
                "mascara" => "99:99", //Mascara do campo
                'banco' => true,
                //'validacao' => array('required' => 'hora_ate_vazio'),//colocar em idiomas
                'banco_string' => true
            ),
//OCULTAR POR TEMPO INDETERMINADO
//            array(
//                "id" => "tipo_repeticao",
//                "nome" => "tipo_repeticao",
//                "nomeidioma" => "form_tipo_repeticao",
//                "tipo" => "select",
//                "array" => "tipo_repeticao", // Array que alimenta o select
//                "class" => "span2",
//                "valor" => "tipo_repeticao",
//                //"validacao" => array("required" => "tipo_repeticao_vazio"),
//                "banco" => true,
//            ),
//            array(
//                'id' => 'data_limite',
//                'nome' => 'data_limite',
//                'nomeidioma' => 'form_data_limite',
//                'tipo' => 'input',
//                'valor' => 'data_limite',
//                'valor_php' => 'if($dados["data_limite"]) return formataData("%s", "br", 0)',
//                'class' => 'span2',
//                'mascara' => '99/99/9999',
//                'datepicker' => true,
//                'banco' => true,
//                //'validacao' => array('required' => 'data_limite_vazio'),//colocar em idiomas
//                'banco_php' => 'return formataData("%s", "en", 0)',
//                'banco_string' => true
//            ),
            array(
                "id" => "link",
                "nome" => "link",
                "nomeidioma" => "form_link",
                "tipo" => "input",
                "valor" => "link",
                "validacao" => array("required" => "link_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='300'"
            ),
            array(
                "id" => "cod_sala",
                "nome" => "cod_sala",
                "nomeidioma" => "form_cod_sala",
                "tipo" => "input",
                "valor" => "cod_sala",
                "validacao" => array("required" => "cod_sala_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='50'"
            ),
            array(
                "id" => "ativo",
                "nome" => "ativo",
                "nomeidioma" => "form_ativo_painel",
                "tipo" => "select",
                "array" => "aula_online", // Array que alimenta o select
                "class" => "span2",
                "valor" => "ativo",
                "validacao" => array("required" => "ativo_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_idgestor",
                "nome" => "idgestor",
                "nomeidioma" => "form_idgestor",
                "tipo" => "hidden",

                "class" => "span2",
                "valor" => "idgestor",
                //"validacao" => array("required" => "tipo_repeticao_vazio"),
                "banco" => true,
            ),
        )
    )
);
?>
