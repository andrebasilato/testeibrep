<?php
set_time_limit(0);

$coreObj = new Core();

$matriculaObj = new Matriculas();
$contasObj = new Contas();
$situacaoPre = $matriculaObj->retornarSituacaoInicial();
$situacaoCancel = $matriculaObj->retornarSituacaoCancelada();
$situacaoAbertoConta = $contasObj->retornarSituacaoEmAberto();
$motivo = $matriculaObj->retornarMotivoCancelar();
$hoje = (new DateTime())->format('Y-m-d');

if ($motivo["idmotivo"] > 0) {
    $sql = 'SELECT
            m.idmatricula, m.idpedido, c.data_vencimento
        FROM
            matriculas m
            INNER JOIN contas c ON (m.idmatricula=c.idmatricula and  c.parcela = 1 AND c.ativo = "S" AND c.data_vencimento < "'.$hoje.'" and c.idsituacao = "'.$situacaoAbertoConta['idsituacao'].'")
        WHERE
            m.ativo = "S" AND 
            m.idsituacao = ' . $situacaoPre['idsituacao'] . '
        GROUP BY m.idmatricula';    
    $resultado1 = $matriculaObj->executaSql($sql);
    
    while ($matricula = mysql_fetch_assoc($resultado1)) {
        $_POST['situacao_para'] = $situacaoCancel['idsituacao'];
        $_POST['idmotivo'] = $motivo['idmotivo'];
        $matriculaObj->set('post',$_POST);
        $matriculaObj->set('id',$matricula['idmatricula']);
        $matriculaObj->alterarSituacao($situacaoPre['idsituacao'], $situacaoCancel['idsituacao']);

        if (! empty($matricula['idpedido'])) {
            $sql = 'SELECT                    
                    idmatricula
                FROM
                    matriculas
                WHERE
                    ativo = "S" AND
                    idpedido = ' . $matricula['idpedido'] . ' AND
                    idmatricula != ' . $matricula['idmatricula'] . '
                GROUP BY idmatricula';  
            $resultado2 = $matriculaObj->executaSql($sql);

            while ($outrasmat = mysql_fetch_assoc($resultado2)) {
                $matriculaObj->set('id', $outrasmat['idmatricula']);
                $matriculaObj->alterarSituacao($situacaoPre["idsituacao"], $situacaoCancel["idsituacao"]);
            }
        }
    }
}
