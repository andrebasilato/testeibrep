<?php

//Cancela matriculas em pré-matrícula por mais de 30 dias

set_time_limit(0);
$coreObj = new Core();
$matriculaObj = new Matriculas();

include_once $caminhoApp . '/app/classes/motivoscancelamento.class.php';
$motivoObj = new Motivos_Cancelamento();

$situacaoPre = $matriculaObj->retornarSituacaoInicial();
$situacaoCancel = $matriculaObj->retornarSituacaoCancelada();
$motivo = $motivoObj->retornarPadrao();

$data = new DateTime();
$data->sub(new DateInterval('P30D')); //30 dias atrás
$data = $data->format('Y-m-d');

$sql = 'SELECT
            m.*
        FROM
            matriculas m
            INNER JOIN matriculas_historicos mh ON ( m.idmatricula = mh.idmatricula )
        WHERE
            m.ativo = "S"
            AND m.idsituacao =  ' . $situacaoPre['idsituacao'] . '
            AND mh.para = ' . $situacaoPre['idsituacao'] . '
            AND mh.data_cad <= "' . $data . '"
            AND mh.idhistorico = ( SELECT Max( mh2.idhistorico ) FROM matriculas_historicos AS mh2 WHERE mh2.idmatricula = m.idmatricula )
        GROUP BY
            m.idmatricula';

$resultados = $matriculaObj->retornarLinhasArray($sql);

foreach ($resultados as $resultado){

    $_POST['situacao_para'] = $situacaoCancel['idsituacao'];
    $_POST['idmotivo'] = $motivo['idmotivo'];
    $matriculaObj->set('post',$_POST);
    $matriculaObj->set('id',$resultado['idmatricula']);
    $sucesso = $matriculaObj->alterarSituacao($situacaoPre['idsituacao'], $situacaoCancel['idsituacao']);

}

