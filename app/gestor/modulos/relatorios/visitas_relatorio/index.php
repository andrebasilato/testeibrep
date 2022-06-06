<?php

include("config.php");
include("classe.class.php");

include("../classes/relatorios.class.php");
$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->Set("idusuario",$usuario["idusuario"]);
$relatorioObj->Set("monitora_onde",1);
$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

if($_POST['acao'] == 'salvar_relatorio') {
    $relatoriosObj->Set("post",$_POST);
    $salvar = $relatoriosObj->salvarRelatorio();
    if($salvar['sucesso']){
        $mensagem_sucesso = "salvar_relatorio_sucesso";
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}

if($url[3] == "html" || $url[3] == "xls"){
    $relatorioObj->Set("pagina",1);
    //$relatorioObj->Set("ordem","desc");
    $relatorioObj->Set("limite",-1);
    //$relatorioObj->Set("ordem_campo","vv.idvisita");
    $relatorioObj->Set("campos","vv.*, pe.idpessoa, pe.nome as pessoa, pe.email as email_pessoa, pe.telefone as telefone_pessoa, pe.celular as celular, lov.nome as local, miv.nome as midia, ve.nome as vendedor, e.nome as estado, c.nome as cidade, '' as iditeracao, '' as data_visita");
    $relatorioObj->Set("campos2","vv.*, pe.idpessoa, pe.nome as pessoa, pe.email as email_pessoa, pe.telefone as telefone_pessoa, pe.celular as celular, lov.nome as local, miv.nome as midia, ve.nome as vendedor, e.nome as estado, c.nome as cidade, vvi.iditeracao, vvi.data_visita");
    $dadosArray = $relatorioObj->gerarRelatorio();
}

switch ($url[3]) {
    case "html":
        $relatoriosObj->atualiza_visualizacao_relatorio();
        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/html.php");
        break;
    case "xls":
        include("idiomas/".$config["idioma_padrao"]."/xls.php");
        include("telas/".$config["tela_padrao"]."/xls.php");
        break;
    default:
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
}