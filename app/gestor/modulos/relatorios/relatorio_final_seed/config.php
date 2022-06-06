<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in (' . $_SESSION['adm_sindicatos'] .
         ')';
$sqlSindicato .= ' order by nome_abreviado';

$config["listagem"] = array(
    array(
        "id" => "idmatricula",
        "variavel_lang" => "tabela_matricula",
        "tipo" => "banco",
        "coluna_sql" => "ma.idmatricula",
        "valor" => 'idmatricula',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    
    array(
        "id" => "aluno",
        "variavel_lang" => "tabela_aluno",
        "tipo" => "banco",
        "coluna_sql" => "pe.nome",
        "valor" => "aluno",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    
    array(
        "id" => "tipo_avaliacao",
        "variavel_lang" => "tabela_tipo_avaliacao",
        "tipo" => "banco",
        "coluna_sql" => "mnt.nome",
        "valor" => "tipo",
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    array(
        "id" => "modelo",
        "variavel_lang" => "tabela_modelo",
        "tipo" => "banco",
        "coluna_sql" => "mn.nome",
        "valor" => 'modelo',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2
    ),
    
    array(
        "id" => "nota",
        "variavel_lang" => "tabela_nota",
        "tipo" => "php",
        "coluna_sql" => "mn.nota",
        "valor" => 'if ($linha["nota_conceito"] == "S") {
												return notaConceito($linha["nota"]);
											} else {
												return number_format($linha["nota"],2,",",".");
											}',
        "busca" => true,
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "tamanho" => 60
    ),
    
    array(
        "id" => "tipo_situacao",
        "variavel_lang" => "tabela_situacao",
        "tipo" => "banco",
        "valor" => "situacao",
        "tamanho" => 60
    )
);

// Array de configuração para a formulario
$config["formulario"] = array(
    
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(            
            array(
                "id" => "idsindicato",
                "nome" => "q[1|ma.idsindicato]",
                "nomeidioma" => "form_sindicato",                
                "tipo" => "select",
                "sql" => $sqlSindicato, // SQL que alimenta o select
                "sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
                "valor" => "idsindicato",
                "validacao" => array(
                    "required" => "sindicato_vazio"
                ),
                "sql_filtro" => "select * from sindicatos where idsindicato=%",
                "sql_filtro_label" => "nome_abreviado"
            ),
            
            array(
                "id" => "idescola",
                "nome" => "q[1|ma.idescola]",
                "nomeidioma" => "form_escola",
                "json" => true,
                "json_idpai" => "idsindicato",
                "json_url" => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . "/ajax_escolas/",
                "json_input_pai_vazio" => "form_selecione_sindicato",
                "json_input_vazio" => "form_selecione_escola",
                "json_campo_exibir" => "nome_fantasia",
                "tipo" => "select",
                "class" => "span5",
                "valor" => "idescola",
                /*"validacao" => array(
                    "required" => "escola_vazio"
                ),*/
                "sql_filtro" => "select * from escolas where idescola=%",
                "sql_filtro_label" => "nome_fantasia"
            ),
            
            array(
                "id" => "idoferta",
                "nome" => "q[1|ma.idoferta]",
                "nomeidioma" => "form_oferta",
                "tipo" => "select",
                "sql" => "SELECT idoferta, nome 
    						FROM ofertas 
    						WHERE ativo='S' 
    						ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idoferta",
                "class" => "span5",
                /*"validacao" => array(
                    "required" => "oferta_vazio"
                ),*/
                "sql_filtro" => "SELECT * FROM ofertas WHERE idoferta=%",
                "sql_filtro_label" => "nome"
            ),
            array(
                "id" => "idcurso",
                "nome" => "q[1|ma.idcurso]",
                "nomeidioma" => "form_curso",
                "json" => true,
                "json_idpai" => "idoferta",
                "json_url" => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . "/ajax_cursos/",
                "json_input_pai_vazio" => "form_selecione_oferta",
                "json_input_vazio" => "form_selecione_curso",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idcurso",
                "class" => "span5",
                "validacao" => array(
                    "required" => "curso_vazio"
                ),
                "sql_filtro" => "SELECT * FROM cursos WHERE idcurso=%",
                "sql_filtro_label" => "nome"
            ),
            
            array(
                "id" => "idturma",
                "nome" => "q[1|ma.idturma]",
                "nomeidioma" => "form_turma",
                "json" => true,
                "json_idpai" => "idoferta",
                "json_url" => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . "/ajax_turmas/",
                "json_input_pai_vazio" => "form_selecione_oferta",
                "json_input_vazio" => "form_selecione_turma",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idturma",
                /*"validacao" => array(
                    "required" => "turma_vazio"
                ),*/
                "sql_filtro" => "select * from ofertas_turmas where idturma=%",
                "sql_filtro_label" => "nome"
            )
        )
    )
);

?>