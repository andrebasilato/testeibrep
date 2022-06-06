<?php

echo "Liberações executadas: \n";
foreach ($estadosDetran as $sigla => $id) {
    $sigla = strtolower($sigla);
    if ($detran->obterSituacaoIntegracao($id) && $sigla != 'ma') { //Filtra somente as integrações ativas
        try {
            require_once "liberacao/index.{$sigla}.php";
        } catch (Exception $e) {
            echo "Exceção capturada na liberação de {$sigla}: ", $e->getMessage(), "\n";
        }
        echo $sigla . "\n";
    }
}
