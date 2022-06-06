<?php
// Array de configuração para a formulario
$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
$sqlSindicato .= ' order by nome_abreviado';

$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'idsindicato',
                'nome' => 'idsindicato',
                'nomeidioma' => 'form_sindicato',
                'tipo' => 'select',
                'class' => 'span3',
                'sql' => $sqlSindicato,
                "sql_valor" => "idsindicato",
                "sql_label" => "nome_abreviado",
                "valor" => "idsindicato",
                "validacao" => array(
                    "required" => "sindicato_vazio"
                ),
                "banco" => true
            ),
            array(
                "id" => "idcurso_sindicato",
                "nome" => "idcurso_sindicato",
                "nomeidioma" => "form_idcurso",
                "json" => true,
                "json_idpai" => "idsindicato",
                "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cursos/",
                "json_input_pai_vazio" => "form_selecione_sindicato",
                "json_input_vazio" => "form_selecione_curso",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idcurso_sindicato",
                "class" => "span6",
                "sql_filtro" => "SELECT c.idcurso, c.nome, ci.idsindicato
                                 FROM cursos_sindicatos ci inner join cursos c on c.idcurso = ci.idcurso 
                                 WHERE ci.idsindicato='".$linha['idsindicato']."'",
                "sql_filtro_label" => "nome",                
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_nome",
                "nome" => "nome",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "validacao" => array(
                    "required" => "nome_vazio"
                ),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "numero_livro",
                "nome" => "numero_livro",
                "nomeidioma" => "form_numero_livro",
                "tipo" => "input",
                "numerico" => true,
                "valor" => "numero_livro",
                "validacao" => array(
                    "required" => "numero_livro_vazio"
                ),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='10'"
            ),
            array(
                "id" => "numero_ordem",
                "nome" => "numero_ordem",
                "nomeidioma" => "form_numero_ordem",
                "tipo" => "input",
                "numerico" => true,
                "valor" => "numero_ordem",
                "validacao" => array(
                    "required" => "numero_ordem_vazio"
                ),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='10'"
            ),
            
            array(
                "id" => "numero_registro",
                "nome" => "numero_registro",
                "nomeidioma" => "form_numero_registro",
                "tipo" => "input",
                "numerico" => true,
                "valor" => "numero_registro",
                "validacao" => array(
                    "required" => "numero_registro_vazio"
                ),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='10'"
            ),
            
            array(
                "id" => "numero_relacao",
                "nome" => "numero_relacao",
                "nomeidioma" => "form_numero_relacao",
                "tipo" => "input",
                "numerico" => true,
                "valor" => "numero_relacao",
                "validacao" => array(
                    "required" => "numero_relacao_vazio"
                ),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='10'"
            ),
            
            array(
                "id" => "numero_folha",
                "nome" => "numero_folha",
                "nomeidioma" => "form_numero_folha",
                "tipo" => "input",
                "numerico" => true,
                "valor" => "numero_folha",
                "validacao" => array(
                    "required" => "numero_folha_vazio"
                ),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='10'"
            ),
            
            array(
                "id" => "data_expedicao",
                "nome" => "data_expedicao",
                "nomeidioma" => "form_data_expedicao",
                'datepicker' => true,
                "tipo" => "input",
                "numerico" => true,
                "valor_php" => '$date = new DateTime($dados["data_expedicao"]); return $date->format("d/m/Y");',
                "validacao" => array(
                    "required" => "data_expedicao"
                ),
                "class" => "span3",
                "banco" => true,
                "banco_string" => true
            ),
            
            array(
                "id" => "form_observacoes",
                "nome" => "observacoes",
                "nomeidioma" => "form_observacoes",
                "tipo" => "text",
                "valor" => "observacoes",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true
            ),
            
            array(
                'id' => 'form_ativo_painel',
                'nome' => 'ativo_painel',
                'nomeidioma' => 'form_ativo_painel',
                'tipo' => 'select',
                'array' => 'ativo', // Array que alimenta o select
                'class' => 'span2',
                'valor' => 'ativo_painel',
                'validacao' => array(
                    'required' => 'ativo_vazio'
                ),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            )
        )
    )
);
?>