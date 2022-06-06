<?php
ini_set('max_execution_time', 0);
// set_time_limit(0);
error_reporting(E_ALL ^ ~E_NOTICE ^ ~E_STRICT ^ E_WARNING ^ E_DEPRECATED);
ini_set('display_errors', 1);

$time_start = microtime(true);

$coreObj = new Core;

$sql = "SELECT
        idescola,
        valor_por_matricula,
        quantidade_faturas_ciclo,
        qtd_parcelas
    FROM
        escolas
    WHERE
        ativo = 'S'
";

$executar = $coreObj->executaSql($sql);

$coreObj->iniciaTransacao();

while ($linha = mysql_fetch_assoc($executar)) {
    $coreObj->executaSql("UPDATE
            cfcs_valores_cursos
        SET
            valor_por_matricula = '{$linha["valor_por_matricula"]}',
            quantidade_faturas_ciclo = '{$linha["quantidade_faturas_ciclo"]}',
            qtd_parcelas = '{$linha["qtd_parcelas"]}'
        WHERE
            idcfc = {$linha["idescola"]}
    ");
}

$coreObj->finalizaTransacao();

$time_end = microtime(true);
$time = $time_end - $time_start;
$time = number_format((float)$time, 2, '.', '');

echo "Script executado em $time segundos\n<br>";