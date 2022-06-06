<?php

$situacaoArray = array();
$sql = 'select 
            mw.idsituacao, 
            mw.nome, 
            mw.cor_nome, 
            mw.cor_bg,
            mw.fim,
            mw.cancelada,
            (
                select count(1) from matriculas m where m.idsituacao = mw.idsituacao and m.ativo = "S"
                and m.idescola = '.intval($_SESSION['escola_idescola']).'
            ) as matriculas 
         from 
            matriculas_workflow mw 
        where 
            mw.ativo = "S" 
        order by mw.ordem asc';

$contabiliza = mysql_query($sql);
while ($situacao = mysql_fetch_assoc($contabiliza)) {
    $situacaoArray[] = $situacao;
}

include 'idiomas/'.$config['idioma_padrao'].'/index.php';
include 'telas/'.$config['tela_padrao'].'/index.php';
