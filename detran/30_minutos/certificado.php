<?php

echo "Certificados executados: \n";
foreach ($estadosDetran as $sigla => $id) {
    $sigla = strtolower($sigla);
    if ($detran->obterSituacaoIntegracao($id)) { //Filtra somente as integrações ativas
        try {
            require_once "certificado/index.{$sigla}.php";
        } catch (Exception $e) {
            echo "Exceção capturada no certificado de {$sigla}: ", $e->getMessage(), "\n";
        }
        echo $sigla . "\n";
    }
}
