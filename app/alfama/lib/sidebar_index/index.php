<?php
$valores = null;

$coreObj = new Core;
$coreObj->orderBy = "idinterface";
$coreObj->sql = "SELECT
				idinterface,
				count(idtransacao) as total
            FROM
             	orio_transacoes
			WHERE
				ativo = 'S'
			GROUP BY idinterface";

$coreObj->orderBy = 'data';
$valoresAux = $coreObj->retornarLinhas();

$orio_interfaces_label['pt_br'] = array();
$orio_interfaces_descricoes['pt_br'] = array();

$orio_interfaces = $config['orio_interfaces'];

foreach ($orio_interfaces as $id => $dados) {
    $orio_interfaces_label['pt_br'][$id] = $dados["nome"];
    $orio_interfaces_descricoes['pt_br'][$id] = $dados["descricao"];
}

foreach ($valoresAux as $ind => $val) {
    if (isset($orio_interfaces[$val["idinterface"]])) {
        $orio_interfaces[$val["idinterface"]]["total"] = $val["total"];
    }
}

include "idiomas/" . $config["idioma_padrao"] . "/index.php";
include "telas/" . $config["tela_padrao"] . "/index.php";
