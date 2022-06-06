<?php

// Array de configuração para a formulario
$config['formulario'] = array(
  array(
	'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
	'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
	'campos' => array( // Campos do formulario
        array(
            'id' => 'form_nome',
            'nome' => 'titulo',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'valor' => 'titulo',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
        array(
            'id' => 'form_nome_video',
            'nome' => 'video_nome',
            'nomeidioma' => 'form_nome_video',
            'tipo' => 'input',
            'valor' => 'video_nome',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
          array(
            'id' => 'form_nome_imagem',
            'nome' => 'video_imagem',
            'nomeidioma' => 'form_nome_imagem',
            'tipo' => 'input',
            'valor' => 'video_imagem',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
      array(
            'id' => 'descricao',
            'nome' => 'descricao',
            'nomeidioma' => 'form_descricao',
            'tipo' => 'text',
            'valor' => 'descricao',
            // 'validacao' => array('required' => 'descricao_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true,
        ),
      array(
            'id' => 'tags',
            'nome' => 'tags',
            'nomeidioma' => 'form_tags',
            'tipo' => 'select',
            'sql' => 'SELECT b.nome as nome, c.idtagvideo FROM videotecas_tags_videos AS c INNER JOIN videotecas_tags AS b ON b.idtag=c.idtag WHERE ativo="S"',
            'sql_valor' => 'idtagvideo',
            'sql_label' =>'nome',
            'valor' => 'nome',
            'class' => 'span6',
            'banco' => true
      ),
      array(
            'id' => 'idpasta',
            'nome' => 'idpasta',
            'nomeidioma' => 'form_pasta',
            'tipo' => 'select',
            'sql' => 'SELECT * FROM videotecas_pastas WHERE ativo=\'S\'',
            'sql_valor' => 'idpasta',
            'sql_label' =>'nome',
            'valor' => 'idpasta',
            'validacao' => array('required' => 'pasta_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
      ),
      array(
            'id' => 'arquivo', // Id do atributo HTML
            'nome' => 'arquivo', // Name do atributo HTML
            'nomeidioma' => 'form_file', // Referencia a variavel de idioma
            'tipo' => 'php', // Tipo do input
            'class' => 'span6', //Class do atributo HTML
            'valor_php' => 'return "asdasd";',
      ),
      array(
            'id' => 'form_ativo_painel',
            'nome' => 'ativo_painel',
            'nomeidioma' => 'form_ativo_painel',
            'tipo' => 'select',
            'array' => 'ativo', // Array que popula o select
            'class' => 'span2',
            'valor' => 'ativo_painel',
            'validacao' => array('required' => 'ativo_vazio'),
            'ajudaidioma' => 'form_ativo_ajuda',
            'banco' => true,
            'banco_string' => true
      ),
      array(
            'id' => 'variavel',
            'nome' => 'variavel',
            'tipo' => 'hidden',
            'valor' => 'variavel',
            'sql_valor' => 'variavel',
            'banco' => true,
            'banco_string' => true
      ),
      array(
           'id' => 'duracao',
            'nome' => 'duracao',
            'tipo' => 'hidden',
            'valor' => 'duracao',
            'sql_valor' => 'duracao',
            'banco' => true,
            'banco_string' => true
      ),
      array(
           'id' => 'arquivo',
            'nome' => 'arquivo',
            'tipo' => 'hidden',
            'valor' => 'arquivo',
            'sql_valor' => 'arquivo',
            'banco' => true,
            'banco_string' => true
      ),
      array(
           'id' => 'imagem',
            'nome' => 'imagem',
            'tipo' => 'hidden',
            'valor' => 'imagem',
            'sql_valor' => 'imagem',
            'banco' => true,
            'banco_string' => true
      )
    )
  )
);


$config['formulario_editar'] = array(
  array(
      'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
      'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
      'campos' => array( // Campos do formulario
        array(
            'id' => 'form_nome',
            'nome' => 'titulo',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'valor' => 'titulo',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
      array(
            'id' => 'form_nome_video',
            'nome' => 'video_nome',
            'nomeidioma' => 'form_nome_video',
            'tipo' => 'input',
            'valor' => 'video_nome',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6" readonly="readonly',
            'banco' => true,
            'banco_string' => true
        ),
          array(
            'id' => 'form_nome_imagem',
            'nome' => 'video_imagem',
            'nomeidioma' => 'form_nome_imagem',
            'tipo' => 'input',
            'valor' => 'video_imagem',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6" readonly="readonly',
            'banco' => true,
            'banco_string' => true
        ),    
      array(
           'id' => 'imagem',
            'nome' => 'imagem',
            'tipo' => 'hidden',
            'valor' => 'imagem',
            'sql_valor' => 'imagem',
            'banco' => true,
            'banco_string' => true
      ),
      array(
            'id' => 'descricao',
            'nome' => 'descricao',
            'nomeidioma' => 'form_descricao',
            'tipo' => 'text',
            'valor' => 'descricao',
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true,
        ),
      array(
            'id' => 'tags',
            'nome' => 'tags',
            'nomeidioma' => 'form_tags',
            'tipo' => 'select',
            'sql' => 'SELECT b.nome as nome, c.idtagvideo FROM videotecas_tags_videos AS c INNER JOIN videotecas_tags AS b ON b.idtag=c.idtag WHERE ativo="S"',
            'sql_valor' => 'idtagvideo',
            'sql_label' =>'nome',
            'valor' => 'nome',
            'class' => 'span6',
            'banco' => true
      ),
      array(
            'id' => 'idpasta__',
            'nome' => 'idpasta__',
            'nomeidioma' => 'form_pasta',
            'tipo' => 'input',
            'valor_php' => 'return videoteca::getFolder((int) str_replace("/gestor/academico/videoteca/", "", $_SERVER["REQUEST_URI"]))',
            'class' => 'span6" readonly="readonly',
      ),
      array(
            'id' => 'form_ativo_painel',
            'nome' => 'ativo_painel',
            'nomeidioma' => 'form_ativo_painel',
            'tipo' => 'select',
            'array' => 'ativo',
            'class' => 'span2',
            'valor' => 'ativo_painel',
            'validacao' => array('required' => 'ativo_vazio'),
            'ajudaidioma' => 'form_ativo_ajuda',
            'banco' => true,
            'banco_string' => true
      ),
      array(
           'id' => 'duracao',
            'nome' => 'duracao',
            'tipo' => 'hidden',
            'valor' => 'return videoteca::getDuration((int) str_replace("/gestor/academico/videoteca/", "", $_SERVER["REQUEST_URI"]));',
            // 'sql_valor' => 'duracao',
            'banco' => true,
            'banco_string' => true
      ),
      array(
           'id' => 'arquivo',
            'nome' => 'arquivo',
            'tipo' => 'hidden',
            'valor' => 'return videoteca::getFile((int) str_replace("/gestor/academico/videoteca/", "", $_SERVER["REQUEST_URI"]));',
            'banco' => true,
            'banco_string' => true
      ),
    )
  )
);



$config['formulario_youtube_editar'] = array(
  array(
      'fieldsetid' => 'dadosdoobjeto',
      'legendaidioma' => 'legendadadosdados',
      'campos' => array(
        array(
            'id' => 'form_nome',
            'nome' => 'titulo',
            'nomeidioma' => 'form_nome',
            'tipo' => 'input',
            'valor' => 'titulo',
            'validacao' => array('required' => 'nome_vazio'),
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
      array(
            'id' => 'descricao',
            'nome' => 'descricao',
            'nomeidioma' => 'form_descricao',
            'tipo' => 'text',
            'valor' => 'descricao',
            'class' => 'span6',
            'banco' => true,
            'banco_string' => true
        ),
      array(
            'id' => 'tags',
            'nome' => 'tags',
            'nomeidioma' => 'form_tags',
            'tipo' => 'select',
            'sql' => 'SELECT b.nome as nome, c.idtagvideo FROM videotecas_tags_videos AS c INNER JOIN videotecas_tags AS b ON b.idtag=c.idtag WHERE ativo="S"',
            'sql_valor' => 'idtagvideo',
            'sql_label' =>'nome',
            'valor' => 'nome',
            'class' => 'span6',
            'banco' => true
      ),
      array(
            'id' => 'form_ativo_painel',
            'nome' => 'ativo_painel',
            'nomeidioma' => 'form_ativo_painel',
            'tipo' => 'select',
            'array' => 'ativo',
            'class' => 'span2',
            'valor' => 'ativo_painel',
            'validacao' => array('required' => 'ativo_vazio'),
            'ajudaidioma' => 'form_ativo_ajuda',
            'banco' => true,
            'banco_string' => true
      ),
    )
  )
);

//print_r2($config['formulario'][0]['campos'][4]);

if($config['videoteca_local']){
    unset($config['formulario'][0]['campos'][1]);
    unset($config['formulario'][0]['campos'][2]);
    unset($config['formulario_editar'][0]['campos'][1]);
    unset($config['formulario_editar'][0]['campos'][2]);
}else{
    unset($config['formulario'][0]['campos'][6]);
}