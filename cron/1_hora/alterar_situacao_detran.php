<?php
require_once $caminhoApp . '/app/classes/matriculas.class.php';
$matriculaObj = new Matriculas();

$situacaoPreMatricula = $matriculaObj->retornarSituacaoInicial();
$situacaoEmCurso = $matriculaObj->retornarSituacaoAtiva();

$sql = "SELECT
            idmatricula
        FROM
            matriculas
        WHERE
            (idsituacao = {$situacaoPreMatricula['idsituacao']}
            OR idsituacao = {$situacaoEmCurso['idsituacao']})
            AND detran_situacao = 'NL'
            AND ativo = 'S'";

$resultado = $matriculaObj->executaSql($sql);
while ($linha = mysql_fetch_assoc($resultado)) {
    $sql = 'UPDATE matriculas SET detran_situacao = "AL" WHERE idmatricula = ' . $linha['idmatricula'];
    $matriculaObj->executaSql($sql);

    $matriculaObj->set('id', $linha['idmatricula'])
        ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'NL', 'AL', null);
}
