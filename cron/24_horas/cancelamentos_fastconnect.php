<?php
require_once $caminhoApp . '/app/classes/fastconnect.class.php';
require_once $caminhoApp . '/app/classes/contas.class.php';



$coreObj->sql = "SELECT fastconnect_client_key, fastconnect_client_code FROM escolas WHERE ativo = 'S' AND fastconnect_client_key IS NOT NULL AND fastconnect_client_code IS NOT NULL";
$coreObj->limite = -1;
$coreObj->ordem_campo = "idescola";
$escolas = $coreObj->retornarLinhas();

foreach($escolas as $escola) {

    $fastConnectObj = new FastConnect($escola['fastconnect_client_code'], $escola['fastconnect_client_key']);

    $contasObj = new Contas();

    $vendasDia = $fastConnectObj->consultarVendasDia((new DateTime)->modify('-1 days')->format('Y-m-d'))[0];

    foreach ($vendasDia as $ind => $conta) {
        if ($conta['situacao'] == "Cancelado" || $conta['situacao'] == "Cancelado por estorno(Cartão)" || $conta['situacao'] == "Cancelado por estorno(Banco)") {
            $linha = $coreObj->retornarLinha("SELECT * FROM fastconnect WHERE nu_venda = '" . $conta['nu_venda'] . "'");

            $idsituacao = $fastConnectObj->getIdSituacao($conta['situacao']);
            $sql = "UPDATE fastconnect SET situacao = '" . $conta['situacao'] . "', idsituacao = " . $idsituacao . " WHERE idfastconnect = '" . $linha['idfastconnect'] . "'";
            //echo $sql."<br><br>";
            $coreObj->executaSql($sql);

            $situacaoCanceladoConta = $contasObj->retornarSituacaoCancelada();

            $linhaConta = $coreObj->retornarLinha("SELECT * FROM contas WHERE idconta = '" . $linha['idconta'] . "'");

            if ($linhaConta['idsituacao'] != $situacaoCanceladoConta['idsituacao']) {
                $matriculaObj = new Matriculas();
                $sql = "UPDATE contas SET idsituacao = '" . $situacaoCanceladoConta['idsituacao'] . "' WHERE idconta = '" . $linhaConta['idconta'] . "'";
                //echo $sql."<br><br>";
                $coreObj->executaSql($sql);
                $coreObj->Set("id", $linhaConta['idconta']);
                $contasObj->AdicionarHistorico("situacao", "modificou", $linhaConta["idsituacao"], $situacaoCanceladoConta['idsituacao'], $linhaConta['idconta']);
                $matriculaObj->Set('id', $linhaConta['idmatricula']);
                $matriculaObj->AdicionarHistorico(NULL, "parcela_situacao", "modificou", $linhaConta["idsituacao"], $situacaoCanceladoConta['idsituacao'], $linhaConta['idconta']);
            }
        }
    }
}
