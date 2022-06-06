<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';

$config['monitoramento']['onde'] = '152';

$config['banco'] = array(
  'tabela' => 'videotecas',
  'primaria' => 'idvideo',
  'campos_insert_fixo' => array(
	'data_cad' => 'now()',
	'ativo' => "'S'"
  ),
  'campos_unicos' => array(
	array(
	  'campo_banco' => 'idvideo',
	  'campo_form' => 'idvideo',
	  'erro_idioma' =>'nome_utilizado'
	)
  ),
);
