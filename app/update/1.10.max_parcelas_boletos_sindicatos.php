<?php
ini_set('max_execution_time', 0);
set_time_limit(0);
error_reporting(E_ALL ^ ~E_NOTICE ^ ~E_STRICT ^ E_WARNING ^ E_DEPRECATED);
ini_set('display_errors', 1);

$time_start = microtime(true);

$coreObj = new Core;

$sql = "SELECT
        idsindicato,
        max_parcelas,
        max_boletos
    FROM 
        sindicatos
    WHERE
        ativo = 'S'
";

$executar = $coreObj->executaSql($sql);

$coreObj->iniciaTransacao();

while ($linha = mysql_fetch_assoc($executar)) {
    $maxParcelas = $linha['max_parcelas'] ? $linha['max_parcelas'] : 'NULL';
    $maxBoletos = $linha['max_boletos'] ? $linha['max_boletos'] : 'NULL';

    $coreObj->executaSql("UPDATE
            sindicatos_valores_cursos
        SET
            max_parcelas = {$maxParcelas},
            max_boletos = {$maxBoletos}
        WHERE
            idsindicato = {$linha['idsindicato']}
    ");
}

$coreObj->finalizaTransacao();

$time_end = microtime(true);
$time = $time_end - $time_start;
$time = number_format((float)$time, 2, '.', '');

echo "Script executado em $time segundos\n<br>";