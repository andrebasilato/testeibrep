<?php
ini_set('max_execution_time', 0);
// set_time_limit(0);
error_reporting(E_ALL ^ ~E_NOTICE ^ ~E_STRICT ^ E_WARNING ^ E_DEPRECATED);
ini_set('display_errors', 1);

$time_start = microtime(true);

$coreObj = new Core;

$sql = "SELECT
        idescola,
        GROUP_CONCAT(forma_pagamento) as formas_pagamento
    FROM
        escolas_formas_pagamento
    WHERE
        ativo = 'S'
    GROUP BY idescola
";

$executar = $coreObj->executaSql($sql);

$coreObj->iniciaTransacao();

while ($linha = mysql_fetch_assoc($executar)) {
    $formasPagamentos = explode(',', $linha['formas_pagamento']);

    foreach ($formasPagamentos as $formaPagamento) {
        $sql = "SELECT
                idcurso
            FROM 
                cfcs_valores_cursos
            WHERE
                ativo = 'S'
                AND idcfc = {$linha["idescola"]}
        ";

        $cursos = $coreObj->retornarLinhasArray($sql);

        foreach ($cursos as $curso) {
            $coreObj->executaSql("INSERT
                INTO
                    escolas_formas_pagamento
                SET
                    idescola = {$linha['idescola']},
                    idcurso = {$curso['idcurso']},
                    forma_pagamento = '{$formaPagamento}'
            ");
        }
    }
}

$coreObj->executaSql("UPDATE
        escolas_formas_pagamento
    SET
        ativo = 'N'
    WHERE
        idcurso is null
");


$coreObj->finalizaTransacao();

$time_end = microtime(true);
$time = $time_end - $time_start;
$time = number_format((float)$time, 2, '.', '');

echo "Script executado em $time segundos\n<br>";