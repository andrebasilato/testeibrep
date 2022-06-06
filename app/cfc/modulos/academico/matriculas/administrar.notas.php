<?php
if($_POST["acao"] == "lancar_nota") {
    $matriculaObj->LancarNotas($matricula["idmatricula"], $_POST['iddisciplina'], $_POST['nota'], $_POST['idtipo'], $_POST['idmodelo'], $_POST['id_solicitacao_prova'], $_POST['aproveitamento_estudo']);

    $matriculaObj->Set("pro_mensagem_idioma","notas_lancadas");
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","notasmatricula");
    $matriculaObj->Processando();
} elseif($_POST["acao"] == "modificar_notas") {
    $salvar = $matriculaObj->ModificarNotas($matricula["idmatricula"], $_POST["notas"]);

    if ($salvar['sucesso']) {
        $matriculaObj->Set("pro_mensagem_idioma","notas_modificadas");
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","notasmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $salvar['erros'][0];
    }
} elseif($_POST["acao"] == "remover_notas") {
    foreach($_POST["remover_nota"] as $iddisciplina => $disciplina){
        foreach($disciplina as $ind => $idnota){
            $matriculaObj->RemoverNotas($matricula["idmatricula"], $iddisciplina, $idnota);
        }
    }

    $matriculaObj->Set("pro_mensagem_idioma","notas_removidas");
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","notasmatricula");
    $matriculaObj->Processando();
}

$matricula["curriculo"] = $matriculaObj->RetornarCurriculo();
$matricula["disciplinas"] = $matriculaObj->RetornarDisciplinas($matricula["curriculo"]['media']);

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();


require("../classes/tiposnotas.class.php");
$tiposNotasObj = new Tipos_Notas();
$tiposNotas = $tiposNotasObj->retornarTiposPorCurriculo($matricula['curriculo']['idcurriculo']);
unset($_GET["q"]["1|ativo_painel"]);
$matriculaObj->set("idpessoa", $matricula['idpessoa']);
$matricula["porcentagem"] = $matriculaObj->porcentagemCursoAtual($matricula['idmatricula']);
$validacoesLancarNotas = [];
$provasEAD = array_filter($matricula["disciplinas"],
    function ($disciplina)
    {
        return $disciplina["tipo"] == "EAD" && count($disciplina["notas"]) == 0 && $disciplina["ignorar_historico"] == "N" && $disciplina["avaliacao_presencial"] == "N";
    }
);

if($matricula["porcentagem"] < 100)
{
    array_push($validacoesLancarNotas, "porcentagem_nota");
}

if(count($provasEAD) > 0)
{
    array_push($validacoesLancarNotas, "notas_lancadas_ead");
}

require("../classes/modelosprova.class.php");
$modelosProvasObj = new Modelos_Prova();
$modelosProvasObj->Set("ordem","asc");
$modelosProvasObj->Set("limite",-1);
$modelosProvasObj->Set("ordem_campo","mp.nome");
$modelosProvasObj->Set("campos","mp.idmodelo, mp.nome");
$_GET["q"]["1|mp.idsindicato"] = $matricula['idsindicato'];
$_GET["q"]["1|mp.ativo_painel"] = "S";
$modelosProvas = $modelosProvasObj->ListarTodas();
unset($_GET["q"]["1|mp.ativo_painel"]);
unset($_GET["q"]["1|mp.idsindicato"]);

require("../classes/provassolicitadas.class.php");
$solicitacoesObj = new Provas_Solicitadas();
$solicitacoesObj->Set("idmatricula",$matricula['idmatricula']);
$solicitacoes = $solicitacoesObj->retornarSolicitacoesAluno('S');

include("idiomas/".$config["idioma_padrao"]."/administrar.notas.php");
include("telas/".$config["tela_padrao"]."/administrar.notas.php");
?>
