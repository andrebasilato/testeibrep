<?php
set_time_limit(0);

include_once($caminhoApp . '/app/classes/escolas.class.php');
include_once($caminhoApp . '/app/classes/cursos.class.php');
include_once($caminhoApp . '/app/classes/contas.class.php');

$contas = new Contas();

$contas->set('mantem_groupby',true);
$contas->set('ordem_campo','c.data_vencimento');
$contas->set('ordem','DESC');
$contas->set("limite", 500);
$contas->set('distinct','DISTINCT ');
$contas->set('groupby','c.idconta,ma.idmatricula');
$contas->atualizaQtdParcelas("c.*,cm.idconta_matricula,cm.qtd_parcelas,ma.idcurso");
