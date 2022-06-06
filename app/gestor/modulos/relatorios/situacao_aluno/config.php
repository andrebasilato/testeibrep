<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	


$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlEscola .= ' order by razao_social';

// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                "id" => "matricula",
                "nome" => "q[1|ma.idmatricula]",
                "nomeidioma" => "form_matricula",
                "tipo" => "input",
                //"validacao" => array("required" => "matricula_vazio"),										
            ),
            array(
                'id' => 'idsindicato',
                "sql" => $sqlSindicato,
                "nome" => "q[1|ma.idsindicato]",
                "tipo" => "select",
                "valor" => "idsindicato",
                "class" => "span5",
                "sql_valor" => "idsindicato",
                "sql_label" => "nome_abreviado",
                // "validacao" => array("required" => "tipo_data_filtro_vazio"),
                "nomeidioma" => "form_sindicato_filtro",
                "sql_filtro" => "select * from sindicatos where idsindicato=%",
                "sql_filtro_label" => "nome_abreviado"
            ),
			
            array(
                'id' => 'idescola',
                "sql" => $sqlEscola,
                "nome" => "q[1|ma.idescola]",
                "tipo" => "select",
                "valor" => "idescola",
                "class" => "span5",
                "sql_valor" => "idescola",
                "sql_label" => "razao_social",
                "nomeidioma" => "form_escola",
                "sql_filtro" => "select * from escolas where ativo='S' and idescola=%",
                "sql_filtro_label" => "razao_social"
            ),
            array(
                "id" => "idvendedor",
                "nome" => "q[1|ma.idvendedor]",
                "nomeidioma" => "form_vendedores",
                "json" => true,
                "json_idpai" => "idsindicato",
                "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_vendedores/",
                "json_input_pai_vazio" => "form_selecione_sindicato",
                "json_input_vazio" => "form_selecione_vendedor",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                'class' => 'span3',
                "valor" => "idvendedor",
                "sql_filtro" => "select * from vendedores where idvendedor=%",
                "sql_filtro_label" => "nome"                                                            
                ),
            array(
                'id' => 'form_tipo_data_matricula_filtro',
                'nome' => 'q[de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_matricula_filtro',
                'banco' => true,
                'iddiv' => 'de_matricula',
                'iddiv2' => 'ate_matricula',
                'iddivs' => array('de_matricula', 'ate_matricula'),
                'iddiv_obr' => true,
                'validacao' => array('required' => 'tipo_data_matricula_filtro_vazio'),
		'nomeidioma' => 'form_tipo_data_matricula_filtro',
                'botao_hide' => true,
                'iddiv2_obr' => true,
                'sql_filtro' => 'array',
                'banco_string' => true,
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_de_matricula',
                'nome' => 'de_matricula',
                'tipo' => 'input',
                'class' => 'span2',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_matricula\",\"form_ate_matricula\")'",
                //"validacao" => array("required" => "de_matricula_vazio"),
                'nomeidioma' => 'form_de_matricula',
                'datepicker' => true,
                'input_hidden' => true
            ),
            array(
                'id' => 'form_ate_matricula',
                'nome' => 'ate_matricula',
                'tipo' => 'input',
                'class' => 'span2',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_matricula\",\"form_ate_matricula\")'",
                //"validacao" => array("required" => "ate_matricula_vazio"),
                'nomeidioma' => 'form_ate_matricula',
                'datepicker' => true,
                'input_hidden' => true
            ),
        )
    )
);