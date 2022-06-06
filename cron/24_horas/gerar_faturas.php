<?php

include_once('../../app/classes/escolas.class.php');
include_once('../../app/classes/contas.class.php');

$escolaObj = new Escolas();

$escolaObj->Set('campos', 'p.idsindicato, p.idescola, p.parceiro, p.periodo_faturas');
$escolaObj->Set('ordem_campo', 'p.idescola');
$escolaObj->Set('ordem', 'ASC');
$escolaObj->Set('limite', -1);
$escolas = $escolaObj->listarTodas();
foreach ($escolas as $ind => $escola)
{
    if ($escola['parceiro'] === 'S')
    {
        $contasObj = new Contas();

        if ($periodo_fatura["pt_br"][$escola['periodo_faturas']] === 'Semanal' && $diaDaSemana === 1)
        {
            $contasObj->gerarFatura($escola['idescola'], $escola['idsindicato']);
        }
        if ($periodo_fatura["pt_br"][$escola['periodo_faturas']] === 'Mensal' && $mes === 01)
        {
            $contasObj->gerarFatura($escola['idescola'], $escola['idsindicato']);
        }
    }
}
