<?php
//Cancela matrículas em curso que excederam o vencimento do Ava

set_time_limit(0);

$coreObj = new Core();
$matriculaObj = new Matriculas();
$motivoObj = new Motivos_Cancelamento();
$motivo = $motivoObj->retornarPadrao();
$situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();


$sql = 'SELECT
                    m.idmatricula,
                    m.data_matricula
                FROM
                    matriculas m
                    INNER JOIN ofertas_cursos_escolas oce ON ( m.idoferta = oce.idoferta AND m.idcurso = oce.idcurso AND m.idescola = oce.idescola )
                WHERE
                    m.idsituacao = ' . (int)$situacaoEmCurso['idsituacao'] . '
                    AND oce.dias_para_ava IS NOT NULL
                    AND m.data_matricula IS NOT NULL
                    AND DATE_ADD( m.data_matricula, INTERVAL oce.dias_para_ava DAY ) < DATE_FORMAT( NOW(), "%Y-%m-%d" )
                    AND (data_prolongada < DATE_FORMAT( NOW(), "%Y-%m-%d" ) or data_prolongada is null)
                GROUP BY
                    m.idmatricula
                ORDER BY
                    m.data_matricula
                LIMIT 500';

$matriculas = $matriculaObj->retornarLinhasArray($sql);

foreach ($matriculas as $matricula) {

    $_POST['situacao_para'] = $situacaoCancelada['idsituacao'];
    $_POST['idmotivo'] = $motivo['idmotivo'];
    $matriculaObj->set('post', $_POST);
    $matriculaObj->set('id', $matricula['idmatricula']);
    $sucesso = $matriculaObj->alterarSituacao($situacaoEmCurso['idsituacao'], $situacaoCancelada['idsituacao']);

}

