<?php
require '../classes/bannersavaaluno.class.php';
$linhaObjMatricula = new Matriculas();
$matriculas = $linhaObjMatricula->listarTotalMatriculas(false, false, false, $usuario["idescola"]);
$arrayMatriculas20 = $linhaObjMatricula->totalMatriculas20Dias(false, false, false, $usuario["idescola"]);

$escolaObj = new Escolas();
$escolaObj->set('id',$_SESSION['escola_idescola']);

if($_POST['acao'] == 'concordar' && $_POST['contrato']){

    $aceito = $escolaObj->aceitarContrato($_POST['contrato']);

    if(!$aceito){
        echo "<script>alert('Ocorreu um erro ao aceitar o contrato!');</script>";
    }else{
        $escolaObj->alterarSituacaoContratosAceitos((int)$_SESSION['escola_idescola']);
        echo "<script>alert('Seu contrato foi aceito com sucesso!');</script>";
    }
}

$contratosPendentes = $escolaObj->ContratoPendente();

if(!$contratosPendentes){
    $contratosPendentes = $escolaObj->ContratoPendenteExterno();
}

if($contratosPendentes['idescola_contrato']){

    $contrato = $escolaObj->retornarContrato(intval($contratosPendentes['idescola_contrato']));

    if($_GET['pdf'] == 'true'){
        include("telas/".$config["tela_padrao"]."/administrar.download.contrato.php");
    }

    include("idiomas/".$config["idioma_padrao"]."/administrar.contrato.php");
    include("telas/".$config["tela_padrao"]."/administrar.contrato.php");
    exit;

}


$datas = array();
$totais = array();

if ($config['tela_padrao'] == 'mobile') {
    $dias = 6;
} else {
    $dias = 19;
}

$cont_total = 0;
for ($i = 0; $i <= $dias; ++$i) {
    $data = date('d/m/Y', mktime(0, 0, 0, date('m'), (date('d') - $dias)  + $i, date('Y')));
    $datas[] = "'".$data."'";
    $cont_total += $arrayMatriculas20[$data];
    $totais_geral[] = $cont_total;
    if (empty($arrayMatriculas20[$data])) {
        $totais[] = 0;
    } else {
        $totais[] = $arrayMatriculas20[$data];
    }
}
$datas_matriculas = implode(', ', $datas);
$totais_matriculas = implode(', ', $totais);
$grafico_totais_matriculas = implode(', ', $totais_geral);
///////////

$bannerObj = new Banners_Ava_Aluno;
$bannerObj->set('idescola', (int)$_SESSION['escola_idescola']);
$banners = $bannerObj->retornarBannersCFC();

include 'idiomas/'.$config['idioma_padrao'].'/index.php';
include 'telas/'.$config['tela_padrao'].'/index.php';
