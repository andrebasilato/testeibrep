<?php

// Array de configuração para a formulario
$config['formulario'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'form_titulo',
        'nome' => 'titulo',
        'nomeidioma' => 'form_titulo',
        'tipo' => 'input',
        'valor' => 'titulo',
        'validacao' => array('required' => 'titulo_vazio'),
        'class' => 'span6',
        'banco' => true,
        'banco_string' => true,
      ),
      array(
        'id' => 'form_resumo',
        'nome' => 'resumo',
        'nomeidioma' => 'form_resumo',
        'tipo' => 'text',
        'valor' => 'resumo',
        'class' => 'span8',
        'contador' => 240,
        'idiomacaracteres' => 'form_caracteres_restantes',
        'banco' => true,
        'validacao' => array('required' => 'resumo_vazio'),
        'banco_string' => true,
      ),
      array(
        'id' => 'form_botao_variaveis_imagens', // Id do atributo HTML
        'nome' => 'botao_variaveis', // Name do atributo HTML
        'nomeidioma' => 'form_botao_variaveis_imagens', // Referencia a variavel de idioma
        'tipo' => 'php', // Tipo do input
        'ajudaidioma' => 'variaveis_imagens_ajuda',
        'botao_hide' => true,
        'colunas' => 3,
        'tabela' => array(
                        'titulo' => 'titulo_variaveis_imagens',
                        'tabela_nome' => 'murais_imagens',
                        'chave_extrangeira' => 'idmural',
                        'chave_primaria' => 'idmural_imagem',
                        'flag_identificacao' => 'I',
                        'tabela_colunas' => array('idmural_imagem', 'nome'),
                        'tabela_colunas_adicionais' => array("<a href=\"/$url[0]/$url[1]/$url[2]/$url[3]/visualiza_imagem/\" rel=\"facebox\"><div class=\"btn btn-mini\"><i class=\"icon-picture\"></i></div></a>"),
        ),
        'class' => 'span4', //Class do atributo HTML
      ),
      array(
        'id' => 'form_botao_variaveis_arquivos', // Id do atributo HTML
        'nome' => 'botao_arquivos', // Name do atributo HTML
        'nomeidioma' => 'form_botao_variaveis_arquivos', // Referencia a variavel de idioma
        'tipo' => 'php', // Tipo do input
        'ajudaidioma' => 'variaveis_arquivos_ajuda',
        'botao_hide' => true,
        'colunas' => 3,
        'tabela' => array(
                        'titulo' => 'titulo_variaveis_arquivos',
                        'tabela_nome' => 'murais_arquivos',
                        'chave_extrangeira' => 'idmural',
                        'chave_primaria' => 'idmural_arquivo',
                        'flag_identificacao' => 'A',
                        'tabela_colunas' => array('idmural_arquivo', 'nome'),
                        'tabela_colunas_adicionais' => array("<a href=\"/$url[0]/$url[1]/$url[2]/$url[3]/downloadArquivo/\"><div class=\"btn btn-mini\"><i class=\"icon-download-alt\"></i></div></a>"),
        ),
        'class' => 'span4', //Class do atributo HTML
      ),
      array(
        'id' => 'form_descricao',
        'nome' => 'descricao',
        'nomeidioma' => 'form_descricao',
        'tipo' => 'text',
        'editor' => true,
        'valor' => 'descricao',
        'class' => 'xxlarge',
        'banco' => true,
        'validacao' => array('required' => 'descricao_vazio'),
        'banco_string' => true,
      ),
      array(
        'id' => 'data_de',
        'nome' => 'data_de',
        'nomeidioma' => 'form_datade',
        'tipo' => 'input',
        'valor' => 'data_de',
        'validacao' => array('required' => 'data_de_vazio'),
        'valor_php' => 'if($dados["data_de"]) return formataData("%s", "br", 0)',
        'class' => 'span2',
        'mascara' => '99/99/9999',
        'datepicker' => true,
        'banco' => true,
        'banco_php' => 'return formataData("%s", "en", 0)',
        'banco_string' => true,
      ),
      array(
        'id' => 'data_ate',
        'nome' => 'data_ate',
        'nomeidioma' => 'form_dataate',
        'tipo' => 'input',
        'valor' => 'data_ate',
        /*"validacao" => array("required" => "data_ate_vazio"), */
        'valor_php' => 'if($dados["data_ate"]) return formataData("%s", "br", 0)',
        'class' => 'span2',
        'mascara' => '99/99/9999',
        'datepicker' => true,
        'banco' => true,
        'banco_php' => 'return formataData("%s", "en", 0)',
        'banco_string' => true,
      ),
    ),
  ),
);

$config['formulario_pessoas'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_pessoas', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'estado_civil',
        'nome' => 'estado_civil',
        'nomeidioma' => 'form_estado_civil',
        'tipo' => 'select',
        'array' => 'estadocivil', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'data_nasc_dia',
        'nome' => 'data_nasc_dia',
        'nomeidioma' => 'form_data_nasc_dia',
        'tipo' => 'select',
        'array' => 'dia_mes', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'data_nasc_mes',
        'nome' => 'data_nasc_mes',
        'nomeidioma' => 'form_data_nasc_mes',
        'tipo' => 'select',
        'array' => 'meses_idioma', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'idestado',
        'nome' => 'idestado',
        'nomeidioma' => 'form_idestado',
        'tipo' => 'select',
        'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome', // SQL que alimenta o select
        'sql_valor' => 'idestado', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
        'class' => 'span2',
      ),
      array(
        'id' => 'idcidade',
        'nome' => 'idcidade',
        'nomeidioma' => 'form_idcidade',
        'json' => true,
        'json_idpai' => 'idestado',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cidades/',
        'json_input_pai_vazio' => 'form_selecione_estado',
        'json_input_vazio' => 'form_selecione_cidade',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idcidade',
        'class' => 'span3',
      ),
      array(
            'id' => 'nome',
            'nome' => 'nome',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'documento',
            'nome' => 'documento',
            'numerico' => true,
            'nomeidioma' => 'form_documento',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'email',
            'nome' => 'email',
            'nomeidioma' => 'form_email',
            'tipo' => 'input',
            'class' => 'span2',
        ),
    ),
  ),
);

$config['formulario_usuarios_adm'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_usuarios_adm', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
        array(
            'id' => 'idsindicato',
            'nome' => 'idsindicato',
            'nomeidioma' => 'form_idsindicato',
            'tipo' => 'select',
            'sql' => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
            'sql_valor' => 'idsindicato',
            'sql_label' => 'nome',
        ),
        array(
            'id' => 'idescola',
            'nome' => 'idescola',
            'nomeidioma' => 'form_idescola',
            'tipo' => 'select',
            'sql' => "select idescola, nome_fantasia as nome FROM escolas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome_fantasia", // SQL que alimenta o select
            'sql_valor' => 'idescola',
            'sql_label' => 'nome',
        ),
        /*array(
            "id" => "idescola",
            "nome" => "idescola",
            "nomeidioma" => "form_idescola",
            "json" => true,
            "json_idpai" => "idsindicato",
            "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/escolas/",
            "json_input_pai_vazio" => "form_selecione_sindicato",
            "json_input_vazio" => "form_selecione_escola",
            "json_campo_exibir" => "nome_fantasia",
            "tipo" => "select",
            "valor" => "idescola"
          ),*/
      array(
        'id' => 'data_nasc_dia',
        'nome' => 'data_nasc_dia',
        'nomeidioma' => 'form_data_nasc_dia',
        'tipo' => 'select',
        'array' => 'dia_mes', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'data_nasc_mes',
        'nome' => 'data_nasc_mes',
        'nomeidioma' => 'form_data_nasc_mes',
        'tipo' => 'select',
        'array' => 'meses_idioma', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'idestado',
        'nome' => 'idestado',
        'nomeidioma' => 'form_idestado',
        'tipo' => 'select',
        'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome', // SQL que alimenta o select
        'sql_valor' => 'idestado', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
        'class' => 'span2',
      ),
      array(
        'id' => 'idcidade',
        'nome' => 'idcidade',
        'nomeidioma' => 'form_idcidade',
        'json' => true,
        'json_idpai' => 'idestado',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cidades/',
        'json_input_pai_vazio' => 'form_selecione_estado',
        'json_input_vazio' => 'form_selecione_cidade',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idcidade',
        'class' => 'span3',
      ),
      array(
            'id' => 'nome',
            'nome' => 'nome',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'documento',
            'nome' => 'documento',
            'numerico' => true,
            'nomeidioma' => 'form_documento',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'email',
            'nome' => 'email',
            'nomeidioma' => 'form_email',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'idperfil',
            'nome' => 'idperfil',
            'nomeidioma' => 'form_idperfil',
            'tipo' => 'select',
            'sql' => "SELECT idperfil, nome FROM usuarios_adm_perfis where ativo='S' ORDER BY nome ", // SQL que alimenta o select
            'sql_valor' => 'idperfil', // Coluna da tabela que será usado como o valor do options
            'sql_label' => 'nome',
            'class' => 'span2',
        ),
    ),
  ),
);

$config['formulario_professores'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_professores', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'idava',
        'nome' => 'idava',
        'nomeidioma' => 'form_idava',
        'tipo' => 'select',
        'sql' => "SELECT idava, nome FROM avas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
        'sql_valor' => 'idava',
        'sql_label' => 'nome',
      ),
      array(
        'id' => 'idoferta',
        'nome' => 'idoferta',
        'nomeidioma' => 'form_idoferta',
        'tipo' => 'select',
        'sql' => 'SELECT idoferta, nome FROM ofertas ORDER BY nome', // SQL que alimenta o select
        'sql_valor' => 'idoferta', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
        //"class" => "span2",
      ),
      array(
        'id' => 'idcurso',
        'nome' => 'idcurso',
        'nomeidioma' => 'form_idcurso',
        'json' => true,
        'json_idpai' => 'idoferta',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cursos/',
        'json_input_pai_vazio' => 'form_selecione_oferta',
        'json_input_vazio' => 'form_selecione_curso',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idcurso',
        //"class" => "span2",
      ),
      array(
        'id' => 'data_nasc_dia',
        'nome' => 'data_nasc_dia',
        'nomeidioma' => 'form_data_nasc_dia',
        'tipo' => 'select',
        'array' => 'dia_mes', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'data_nasc_mes',
        'nome' => 'data_nasc_mes',
        'nomeidioma' => 'form_data_nasc_mes',
        'tipo' => 'select',
        'array' => 'meses_idioma', // Array que alimenta o select
        'class' => 'span2',
      ),
      array(
        'id' => 'idestado',
        'nome' => 'idestado',
        'nomeidioma' => 'form_idestado',
        'tipo' => 'select',
        'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome', // SQL que alimenta o select
        'sql_valor' => 'idestado', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
        'class' => 'span2',
      ),
      array(
        'id' => 'idcidade',
        'nome' => 'idcidade',
        'nomeidioma' => 'form_idcidade',
        'json' => true,
        'json_idpai' => 'idestado',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cidades/',
        'json_input_pai_vazio' => 'form_selecione_estado',
        'json_input_vazio' => 'form_selecione_cidade',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idcidade',
        'class' => 'span3',
      ),
      array(
            'id' => 'nome',
            'nome' => 'nome',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'documento',
            'nome' => 'documento',
            'numerico' => true,
            'nomeidioma' => 'form_documento',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'email',
            'nome' => 'email',
            'nomeidioma' => 'form_email',
            'tipo' => 'input',
            'class' => 'span2',
        ),
    ),
  ),
);

$config['formulario_vendedores'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_vendedores', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'idsindicato',
        'nome' => 'idsindicato',
        'nomeidioma' => 'form_idsindicato',
        'tipo' => 'select',
        'sql' => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
        'sql_valor' => 'idsindicato',
        'sql_label' => 'nome',
      ),
      array(
            'id' => 'idescola',
            'nome' => 'idescola',
            'nomeidioma' => 'form_idescola',
            'tipo' => 'select',
            'sql' => "select idescola, nome_fantasia as nome FROM escolas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome_fantasia", // SQL que alimenta o select
            'sql_valor' => 'idescola',
            'sql_label' => 'nome',
        ),
      array(
            'id' => 'nome',
            'nome' => 'nome',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'documento',
            'nome' => 'documento',
            'numerico' => true,
            'nomeidioma' => 'form_documento',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
            'id' => 'email',
            'nome' => 'email',
            'nomeidioma' => 'form_email',
            'tipo' => 'input',
            'class' => 'span2',
        ),
        array(
        'id' => 'idestado',
        'nome' => 'idestado',
        'nomeidioma' => 'form_idestado',
        'tipo' => 'select',
        'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome', // SQL que alimenta o select
        'sql_valor' => 'idestado', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
        'class' => 'span2',
      ),
      array(
        'id' => 'idcidade',
        'nome' => 'idcidade',
        'nomeidioma' => 'form_idcidade',
        'json' => true,
        'json_idpai' => 'idestado',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cidades/',
        'json_input_pai_vazio' => 'form_selecione_estado',
        'json_input_vazio' => 'form_selecione_cidade',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idcidade',
        'class' => 'span3',
      ),
    ),
  ),
);

$config['formulario_atendimentos'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_atendimentos', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'idassunto',
        'nome' => 'idassunto',
        'nomeidioma' => 'form_idassunto',
        'tipo' => 'select',
        'sql' => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
        'sql_valor' => 'idassunto', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
      ),
      array(
        'id' => 'idsubassunto',
        'nome' => 'idsubassunto',
        'nomeidioma' => 'form_idsubassunto',
        'json' => true,
        'json_idpai' => 'idassunto',
        'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/subassunto',
        'json_input_pai_vazio' => 'form_selecione_assunto',
        'json_input_vazio' => 'form_selecione_subassunto',
        'json_campo_exibir' => 'nome',
        'tipo' => 'select',
        'valor' => 'idsubassunto',
      ),
      array(
        'id' => 'idsituacao',
        'nome' => 'idsituacao',
        'nomeidioma' => 'form_idsituacao',
        'tipo' => 'select',
        'sql' => "SELECT idsituacao, nome FROM atendimentos_workflow WHERE ativo = 'S' order by nome", // SQL que alimenta o select
        'sql_valor' => 'idsituacao', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
      ),
    ),
  ),
);

$config['formulario_matriculas'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_matriculas', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
        array(
            'id' => 'data_cad_de',
            'nome' => 'data_cad_de',
            'nomeidioma' => 'form_data_cad_de',
            'tipo' => 'input',
            'class' => 'span2',
            'mascara' => '99/99/9999',
            'datepicker' => true,
          ),
        array(
            'id' => 'data_cad_ate',
            'nome' => 'data_cad_ate',
            'nomeidioma' => 'form_data_cad_ate',
            'tipo' => 'input',
            'class' => 'span2',
            'mascara' => '99/99/9999',
            'datepicker' => true,
          ),
        array(
            'id' => 'idsindicato',
            'nome' => 'idsindicato',
            'nomeidioma' => 'form_idsindicato',
            'tipo' => 'select',
            'sql' => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
            'sql_valor' => 'idsindicato',
            'sql_label' => 'nome',
        ),

      array(
            'id' => 'idoferta',
            'nome' => 'idoferta',
            'nomeidioma' => 'form_idoferta',
            'tipo' => 'select',
            'sql' => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
            'sql_valor' => 'idoferta', // Coluna da tabela que será usado como o valor do options
            'sql_label' => 'nome', // Coluna da tabela que será usado como o label do options
            'valor' => 'idoferta',
            'class' => 'span5',
            'sql_filtro' => 'SELECT * FROM ofertas WHERE idoferta=%',
            'sql_filtro_label' => 'nome',
            ),
    array(
            'id' => 'idcurso',
            'nome' => 'idcurso',
            'nomeidioma' => 'form_idcurso',
            'json' => true,
            'json_idpai' => 'idoferta',
            'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_cursos/',
            'json_input_pai_vazio' => 'form_selecione_oferta',
            'json_input_vazio' => 'form_selecione_curso',
            'json_campo_exibir' => 'nome',
            'tipo' => 'select',
            'valor' => 'idcurso',
            'sql_filtro' => 'SELECT * FROM cursos WHERE idcurso=%',
            'sql_filtro_label' => 'nome',
            ),
    array(
            'id' => 'idturma',
            'nome' => 'idturma',
            'nomeidioma' => 'form_idturma',
            'json' => true,
            'json_idpai' => 'idoferta',
            'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_turmas/',
            'json_input_pai_vazio' => 'form_selecione_oferta',
            'json_input_vazio' => 'form_selecione_turma',
            'json_campo_exibir' => 'nome',
            'tipo' => 'select',
            'valor' => 'idturma',
            'sql_filtro' => 'select * from ofertas_turmas where idturma=%',
            'sql_filtro_label' => 'nome',
            ),
    array(
            'id' => 'idescola',
            'nome' => 'idescola',
            'nomeidioma' => 'form_idescola',
            'json' => true,
            'json_idpai' => 'idoferta',
            'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/ajax_escolas/',
            'json_input_pai_vazio' => 'form_selecione_oferta',
            'json_input_vazio' => 'form_selecione_escola',
            'json_campo_exibir' => 'nome',
            'tipo' => 'select',
            'valor' => 'idescola',
            'sql_filtro' => 'select * from escolas where idescola=%',
            'sql_filtro_label' => 'nome',
            ),

        array(
            'id' => 'idmatricula',
            'nome' => 'idmatricula',
            'nomeidioma' => 'form_idmatricula',
            'tipo' => 'input',
            'class' => 'span2',
          ),

        array(
            'id' => 'data_matricula',
            'nome' => 'data_matricula',
            'nomeidioma' => 'form_data_matricula',
            'tipo' => 'input',
            'class' => 'span2',
            'mascara' => '99/99/9999',
            'datepicker' => true,
          ),

      array(
        'id' => 'idsituacao',
        'nome' => 'idsituacao',
        'nomeidioma' => 'form_idsituacao',
        'tipo' => 'select',
        'sql' => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = 'S' order by nome", // SQL que alimenta o select
        'sql_valor' => 'idsituacao', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
      ),
      array(
            'id' => 'data_alteracao_de',
            'nome' => 'data_alteracao_de',
            'nomeidioma' => 'form_data_alteracao_de',
            'tipo' => 'input',
            'class' => 'span2',
            'mascara' => '99/99/9999',
            'datepicker' => true,
          ),
        array(
            'id' => 'data_alteracao_ate',
            'nome' => 'data_alteracao_ate',
            'nomeidioma' => 'form_data_alteracao_ate',
            'tipo' => 'input',
            'class' => 'span2',
            'mascara' => '99/99/9999',
            'datepicker' => true,
          ),

        array(
            'id' => 'nome',
            'nome' => 'nome',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'class' => 'span2',
          ),

        array(
            'id' => 'documento',
            'nome' => 'documento',
            'nomeidioma' => 'form_documento',
            'tipo' => 'input',
            'class' => 'span2',
          ),
      array(
        'id' => 'idvendedor',
        'nome' => 'idvendedor',
        'nomeidioma' => 'form_idvendedor',
        'tipo' => 'select',
        'sql' => "SELECT idvendedor, nome FROM vendedores WHERE ativo = 'S' order by nome", // SQL que alimenta o select
        'sql_valor' => 'idvendedor', // Coluna da tabela que será usado como o valor do options
        'sql_label' => 'nome',
      ),
    ),
  ),
);

$config['formulario_sindicatos'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
    'legendaidioma' => 'legendadadosdados_sindicatos', // Legenda do fomrulario (referencia a variavel de idioma)
    'campos' => array( // Campos do formulario
      array(
        'id' => 'idsindicato',
        'nome' => 'idsindicato',
        'nomeidioma' => 'form_idsindicato',
        'tipo' => 'select',
        'sql' => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
        'sql_valor' => 'idsindicato',
        'sql_label' => 'nome',
      ),
    ),
  ),
);

$config['formulario_escolas'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
        'legendaidioma' => 'legendadadosdados_escolas', // Legenda do fomrulario (referencia a variavel de idioma)
        'campos' => array( // Campos do formulario
            array(
                'id' => 'idsindicato',
                'nome' => 'idsindicato',
                'nomeidioma' => 'form_idsindicato',
                'tipo' => 'select',
                'sql' => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                'sql_valor' => 'idsindicato',
                'sql_label' => 'nome',
            ),
            array(
                'id' => 'idescola',
                'nome' => 'idescola',
                'nomeidioma' => 'form_idescola',
                'json' => true,
                'json_idpai' => 'idsindicato',
                'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/escolas/',
                'json_input_pai_vazio' => 'form_selecione_sindicato',
                'json_input_vazio' => 'form_selecione_escola',
                'json_campo_exibir' => 'nome_fantasia',
                'tipo' => 'select',
                'valor' => 'idescola',
            ),
            array(
                'id' => 'idestado',
                'nome' => 'idestado',
                'nomeidioma' => 'form_idestado',
                'tipo' => 'select',
                'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome', // SQL que alimenta o select
                'sql_valor' => 'idestado', // Coluna da tabela que será usado como o valor do options
                'sql_label' => 'nome',
                'class' => 'span2',
            ),
            array(
                'id' => 'idcidade',
                'nome' => 'idcidade',
                'nomeidioma' => 'form_idcidade',
                'json' => true,
                'json_idpai' => 'idestado',
                'json_url' => '/'.$url['0'].'/'.$url['1'].'/'.$url['2'].'/'.$url['3'].'/json/cidades/',
                'json_input_pai_vazio' => 'form_selecione_estado',
                'json_input_vazio' => 'form_selecione_cidade',
                'json_campo_exibir' => 'nome',
                'tipo' => 'select',
                'valor' => 'idcidade',
                'class' => 'span3',
            ),
        ),
    ),
);
