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

$config['listagem'] = array(
    array(
        'id' => 'idmatricula',
        'variavel_lang' => 'tabela_matricula',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.idmatricula',
        'valor' => 'idmatricula',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 60
    ),
    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'tipo' => 'php',
        'coluna_sql' => 'ma.data_cad',
        'valor' => 'return formataData($linha["data_cad"],"br",0);',
        'tamanho' => '80',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'situacao',
        'variavel_lang' => 'tabela_situacao',
        'tipo' => 'php',
        'coluna_sql' => 'ma.idsituacao',
        'valor' => 'return "<span>".$linha["situacao_wf_nome"]."</span>";',
        'busca' => true,
        'tamanho' => 100,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_sql' => 'SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = "S"',
        'busca_sql_valor' => 'idsituacao',
        'busca_sql_label' => 'nome',
        'busca_metodo' => 1
    ),
    array(
        'id' => 'oferta',
        'variavel_lang' => 'tabela_oferta',
        'tipo' => 'banco',
        'coluna_sql' => 'o.nome',
        'valor' => 'oferta',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'curso',
        'variavel_lang' => 'tabela_curso',
        'tipo' => 'banco',
        'tamanho' => 60,
        'coluna_sql' => 'cu.nome',
        'valor' => 'curso',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
	array(
        'id' => 'turma',
        'variavel_lang' => 'tabela_turma',
        'tipo' => 'banco',
        'tamanho' => 60,
        'coluna_sql' => 'tu.nome',
        'valor' => 'turma',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'empresa',
        'variavel_lang' => 'tabela_empresa',
        'tipo' => 'php',
        'valor' => 'if($linha["empresa"]) {
                    return $linha["empresa"];
                  } else {
                    return "--";
                  }',
        'busca' => false
    ),
    array(
        'id' => 'cliente',
        'variavel_lang' => 'tabela_cliente',
        'tipo' => 'banco',
        'coluna_sql' => 'pe.nome',
        'valor' => 'cliente',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
	array(
        'id' => 'documento',
        'variavel_lang' => 'tabela_documento',
        'tipo' => 'banco',
        'coluna_sql' => 'pe.documento',
        'valor' => 'documento',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'vendedor',
        'variavel_lang' => 'tabela_vendedor',
        'tipo' => 'banco',
        'coluna_sql' => 've.nome',
        'valor' => 'vendedor',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'observacao',
        'variavel_lang' => 'tabela_observacao',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.observacao',
        'valor' => 'observacao',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'data_primeiro_acesso',
        'variavel_lang' => 'tabela_data_primeiro_acesso',
        'tipo' => 'php',
        'coluna_sql' => 'ma.data_primeiro_acesso',
        'valor' => 'if($linha["data_primeiro_acesso"]){ return formataData($linha["data_primeiro_acesso"],"br",1);}else{return "--";}',
        'tamanho' => '80',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'data_conclusao',
        'variavel_lang' => 'tabela_data_conclusao',
        'tipo' => 'php',
        'coluna_sql' => 'ma.data_conclusao',
        'valor' => 'if($linha["data_conclusao"]){ return formataData($linha["data_conclusao"],"br",0);}else{return "--";}',
        'tamanho' => '80',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'data_expedicao',
        'variavel_lang' => 'tabela_data_expedicao',
        'tipo' => 'php',
        'coluna_sql' => 'ma.data_conclusao',
        'valor' => 'if($linha["data_expedicao"]){ return formataData($linha["data_expedicao"],"br",0);}else{return "--";}',
        'busca' => false,
    ),
    array(
        'id' => 'numero_ordem',
        'variavel_lang' => 'tabela_numero_ordem',
        'tipo' => 'banco',
        'coluna_sql' => 'folhas_matriculas.numero_ordem',
        'valor' => 'numero_ordem',
        'busca' => false,
    ),
    array(
        'id' => 'numero_registro',
        'variavel_lang' => 'tabela_numero_registro',
        'tipo' => 'banco',
        'coluna_sql' => 'folhas_matriculas.numero_registro',
        'valor' => 'numero_registro',
        'busca' => false,
    ),
    array(
        'id' => 'numero_folha',
        'variavel_lang' => 'tabela_numero_folha',
        'tipo' => 'php',
        'coluna_sql' => 'folhas.numero_folha',
        'valor' => 'return "<a href=\"/gestor/academico/folhasregistrosdiplomas/".$linha["idfolha"]."/editar/\">".$linha["numero_folha"]."</a>";',
        'busca' => false,
    ),
    array(
        'id' => 'numero_livro',
        'variavel_lang' => 'tabela_numero_livro',
        'tipo' => 'banco',
        'coluna_sql' => 'folhas.numero_livro',
        'valor' => 'numero_livro',
        'busca' => false,
    ),
    array(
        'id' => 'numero_cnh',
        'variavel_lang' => 'tabela_numero_cnh',
        'tipo' => 'banco',
        'coluna_sql' => 'pe.cnh',
        'valor' => 'cnh',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'biometria',
        'variavel_lang' => 'tabela_gerar_biometria',
        'tipo' => 'php',
        'coluna_sql' => 'ma.biometria_liberada',
        'valor' => 'if($linha["biometria_liberada"] == "S") return "Sim"; else return "Não";',
        'busca' => true,
    )
);


// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            /*array(
                'id' => 'idmantenedoras',
                'sql' => 'SELECT idmantenedora, razao_social FROM mantenedoras WHERE ativo="S" ORDER BY razao_social',
                'nome' => 'q[1|mant.idmantenedora]',
                'tipo' => 'select',
                'valor' => 'idmantenedora',
                'class' => 'span5',
                'sql_valor' => 'idmantenedora',
                'sql_label' => 'razao_social',
                'nomeidioma' => 'form_mantenedoras',
                'sql_filtro' => 'select * from mantenedoras where ativo="S" and idmantenedora=%',
                'sql_filtro_label' => 'nome'
            ),*/

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
                'id' => 'idoferta',
                "sql" => "select idoferta, nome from ofertas where ativo = 'S' order by nome",
                'nome' => 'q[1|ma.idoferta]',
                'tipo' => 'select',
                'valor' => 'idoferta',
				"class" => "span5",
				"sql_valor" => "idoferta",
                "sql_label" => "nome",
                'nomeidioma' => 'form_idoferta',
				'sql_filtro' => 'select * from ofertas where idoferta = %',
                'sql_filtro_label' => 'nome',
            ),

			array(
                'id' => 'idcurso',
                "sql" => "select idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|ma.idcurso]',
                'tipo' => 'select',
                'valor' => 'idcurso',
				"class" => "span5",
				"sql_valor" => "idcurso",
                "sql_label" => "nome",
                'nomeidioma' => 'form_idcurso',
				'sql_filtro' => 'select * from cursos where idcurso=%',
                'sql_filtro_label' => 'nome',
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
                'id' => 'idturma',
                "sql" => "SELECT idturma, nome FROM ofertas_turmas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome",
                "nome" => "q[1|ma.idturma]",
                "tipo" => "select",
                "valor" => "idescola",
                "class" => "span5",
                "sql_valor" => "idturma",
                "sql_label" => "nome",
                "nomeidioma" => "form_turma",
                "sql_filtro" => "select * from ofertas_turmas where ativo='S' and ativo_painel = 'S' and idturma=%",
                "sql_filtro_label" => "nome"
            ),

            array(
                "id" => "idvendedor",
                "nome" => "q[1|ve.idvendedor]",
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

            /*array(
                'id' => 'idvendedor',
                'sql' => 'SELECT idvendedor, nome FROM vendedores WHERE ativo="S" ORDER BY nome',
                'nome' => 'q[1|ma.idvendedor]',
                'tipo' => 'select',
                'valor' => 'idvendedor',
                'class' => 'span3',
                'sql_valor' => 'idvendedor',
                'sql_label' => 'nome',
                'nomeidioma' => 'form_vendedores',
                'sql_filtro' => 'select * from vendedores where ativo="S" and idvendedor=%',
                'sql_filtro_label' => 'nome'
            ),*/

            array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|ma.data_cad]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'iddiv' => 'de',
                'iddiv2' => 'ate',
                'iddivs' => array('de','ate'),
                'iddiv_obr' => true,
			    //'validacao' => array('required' => 'tipo_data_filtro_vazio'),
                'nomeidioma' => 'form_tipo_data_filtro',
                'botao_hide' => true,
                'iddiv2_obr' => true,
                'sql_filtro' => 'array',
                'banco_string' => true,
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_de',
                'nome' => 'de',
				'valor' => 'de',
                'tipo' => 'input',
                'class' => 'span2',
				"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				//"validacao" => array("required" => "de_vazio"),
                'nomeidioma' => 'form_de',
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
            ),
            array(
                'id' => 'form_ate',
                'nome' => 'ate',
				'valor' => 'ate',
                'tipo' => 'input',
                'class' => 'span2',
				"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				//"validacao" => array("required" => "ate_vazio"),
                'nomeidioma' => 'form_ate',
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
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
                'iddivs' => array(
                    'de_matricula',
                    'ate_matricula'
                ),
                'iddiv_obr' => true,


				/*'validacao' => array(
                    'required' => 'tipo_data_matricula_filtro_vazio'
                ),*/

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
            /*array(
                'id' => 'idoferta',
                'sql' => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|ma.idoferta]',
                'tipo' => 'select',
                'valor' => 'idoferta',
                'class' => 'span5',
                'sql_label' => 'nome',
                'sql_valor' => 'idoferta',
                'nomeidioma' => 'form_idoferta',
                'sql_filtro' => 'select * from ofertas where idoferta=%',
                'sql_filtro_label' => 'nome'
            ),*/
            array(
                "id" => "idsituacao",
                "nome" => "situacao",
                "nomeidioma" => "form_idsituacao",
                "tipo" => "checkbox",
                "sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo='S' order by nome--",
                "sql_valor" => "idsituacao",
                "sql_label" => "nome",
                "valor" => "idsituacao",
                "sql_filtro" => "select * from matriculas_workflow where idsituacao=%",
                "sql_filtro_label" => "nome"
            ),
			array(
                "id" => "form_combo",
                "nome" => "q[1|ma.combo]",
                "nomeidioma" => "form_combo",
                "tipo" => "select",
                "array" => "sim_nao",
                "class" => "span1",
                "classe_label" => "control-label",
                "valor" => "combo",
            ),
			array(
                "id" => "form_biometria",
                "nome" => "q[1|ma.biometria_liberada]",
                "nomeidioma" => "form_biometria",
                "tipo" => "select",
                "array" => "sim_nao",
                "class" => "span1",
                "classe_label" => "control-label",
                "valor" => "biometria",
            ),
			array(
					"id" => "idestado",
					"nome" => "q[1|pe.idestado]",
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
					"nome" => "q[1|pe.idcidade]",
					"nomeidioma" => "form_idcidade",
					"json" => true,
					"json_idpai" => "idestado",
					"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cidades/",
					"json_input_pai_vazio" => "form_selecione_estado",
					"json_input_vazio" => "form_selecione_cidade",
					"json_campo_exibir" => "nome",
					"tipo" => "select",
					"valor" => "idcidade",
					"sql_filtro" => "select * from cidades where idcidade=%",
					"sql_filtro_label" => "nome"
					),
        )
    )
);
