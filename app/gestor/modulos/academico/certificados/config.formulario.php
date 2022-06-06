<?php
// Array de configuração para a formulário
$config['formulario'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'legendadadosdados',
    'campos' => array(
      array(
        'id' => 'form_nome',
        'nome' => 'nome',
        'tipo' => 'input',
        'banco' => true,
        'valor' => 'nome',
        'class' => 'span6',
        'validacao' => array('required' => 'nome_vazio'),
        'nomeidioma' => 'form_nome',
        'banco_string' => true,
      ),
      array(
        'id' => 'form_ativo_painel',
        'nome' => 'ativo_painel',
        'tipo' => 'select',
        'array' => 'ativo',
        'class' => 'span2',
        'valor' => 'ativo_painel',
        'banco' => true,
        'validacao' => array('required' => 'ativo_vazio'),
        'nomeidioma' => 'form_ativo_painel',
        'ajudaidioma' => 'form_ativo_ajuda',
        'banco_string' => true
      ),
    )
  )
);



// Array de configuração para a formulario de páginas
$config['formulario_paginas'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'titulo_form',
    'campos' => array(
      array(
        'id' => 'form_nome',
        'nome' => 'nome',
        'tipo' => 'input',
        'banco' => true,
        'valor' => 'nome',
        'class' => 'span6',
        'validacao' => array('required' => 'nome_vazio'),
        'nomeidioma' => 'form_nome',
        'banco_string' => true
      ),
      array(
        'id' => 'form_ordem',
        'nome' => 'ordem',
        'tipo' => 'input',
        'banco' => true,
        'valor' => 'ordem',
        'class' => 'span2',
        'nomeidioma' => 'form_ordem',
        'banco_string' => true
      ),
      array(
        'id' => 'arquivo',
        'nome' => 'arquivo',
        'nomeidioma' => 'form_imagem',
        'arquivoidioma' => 'arquivo_enviado',
        'arquivoexcluir' => 'arquivo_excluir',
        'tipo' => 'file',
        'extensoes' => 'jpg|jpeg|gif|png|bmp',
        'largura' => 350,
        'altura' => 180,
        'validacao' => array('formato_arquivo' => 'arquivo_invalido'),
        'class' => 'span6',
        'pasta' => 'certificado_imagem',
        'download' => true,
        'excluir' => true,
        'banco' => true,
        'banco_campo' => 'arquivo',
        'ignorarsevazio' => true
      )
    )
  )
);


// MIDIAS
$config['formulario_midias'] = array(
  array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'titulo_form',
    'campos' => array(
      array(
        'id' => 'form_nome',
        'nome' => 'nome',
        'tipo' => 'input',
        'banco' => true,
        'valor' => 'nome',
        'class' => 'span6',
        'validacao' => array('required' => 'nome_vazio'),
        'nomeidioma' => 'form_nome',
        'banco_string' => true
      ),
      array(
        'id' => 'idcertificado',
        'nome' => 'idcertificado',
        'tipo' => 'hidden',
        'banco' => true,
        'valor' => 'return Request::url("4");',
        'class' => 'span6',
        'validacao' => array('required' => 'erro_ao_identificar_certificado'),
        'nomeidioma' => 'form_idcertificado',
        'banco_string' => true
      ),
      array(
        'id' => 'arquivo',
        'nome' => 'arquivo',
        'tipo' => 'file',
        'banco' => true,
        'class' => 'span6',
        'pasta' => 'certificados_midias',
        'altura' => 180,
        'largura' => 350,
        'excluir' => true,
        'download' => true,
        'validacao' => array('formato_arquivo' => 'arquivo_invalido'),
        'extensoes' => 'jpg|jpeg|png',
        'nomeidioma' => 'form_imagem_certificado',
        'banco_campo' => 'imagem',
        'arquivoidioma' => 'arquivo_enviado',
        'arquivoexcluir' => 'arquivo_excluir',
        'ignorarsevazio' => true
      )
    )
  )
);