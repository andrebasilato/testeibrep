<?php

echo "Créditos executados: \n";
foreach ($estadosDetran as $sigla => $id) {
    $sigla = strtolower($sigla);
    if ($detran->obterSituacaoIntegracao($id) && ($sigla == 'se' || $sigla == 'pe' )) { //Filtra somente as integrações ativas
        try {
            require_once "creditos/index.{$sigla}.php";
        } catch (Exception $e) {
            echo "Exceção capturada no créditos de {$sigla}: ", $e->getMessage(), "\n";
        }
        echo $sigla . "\n";
    }
}