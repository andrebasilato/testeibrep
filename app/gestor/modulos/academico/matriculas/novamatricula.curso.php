<?php
if(empty($url[4])){
    unset($_SESSION["matricula"]);
}

include("../classes/ofertas.class.php");
include("../classes/escolas.class.php");
$ofertaObj = new Ofertas();
$escola = new Escolas();
$ofertaObj->Set("ordem","asc");
$ofertaObj->Set("limite","-1");
$ofertaObj->Set("ordem_campo","o.data_fim_matricula");
$ofertaObj->Set("campos","o.*");
$ofertaObj->Set("idusuario",$usuario["idusuario"]);
$ofertas = $ofertaObj->ListarTodasMatriculas();

if((int)$url[4] && !$url[5] && !$url[6]) {

	$_SESSION["matricula"]["idoferta"] = $url[4];
	$ofertaObj->Set("id",$url[4]);
	$ofertaObj->Set("campos","idoferta, nome");
	$oferta = $ofertaObj->Retornar();
    $_SESSION["matricula"]["oferta_nome"] = $oferta["nome"];

	$ofertaObj->Set("ordem","asc");
	$ofertaObj->Set("limite","-1");
//    $ofertaObj->Set("idcurriculo","not null");
	$ofertaObj->Set("ordem_campo","c.nome");
	$ofertaObj->Set("campos","oc.idoferta_curso, c.nome as curso, o.idoferta, c.idcurso");
	$ofertaCursos = $ofertaObj->ListarTodasCursosMatriculas(true);
    $ofertaCursos = $ofertaObj->FiltrarCursosComCurriculo($ofertaCursos);

}

if((int)$url[5] && !$url[6]) {

    $_SESSION["matricula"]["idoferta_curso"] = $url[5];
    $ofertaObj->Set("id",$url[4]);
    $ofertaObj->Set("idoferta_curso",$url[5]);
    $ofertaObj->Set("campos","oc.idoferta_curso, c.nome as curso, c.idcurso, oc.possui_financeiro");
    $ofertaCurso = $ofertaObj->RetornarCurso();
    $_SESSION["matricula"]["idcurso"] = $ofertaCurso['idcurso'];
    $_SESSION['matricula']['possui_financeiro'] = $ofertaCurso['possui_financeiro'];
    $_SESSION['matricula']['curso_nome'] = $ofertaCurso['curso'];

    $ofertaObj->Set("ordem","asc");
    $ofertaObj->Set("limite","-1");
    $ofertaObj->Set("ordem_campo","p.nome_fantasia");
    $ofertaObj->Set("campos","ocp.*, p.nome_fantasia as escola");
    $_GET['q']['1|c.idcurso'] = $ofertaCurso['idcurso'];
    $_GET['q']['1|ocp.ignorar'] = 'N';
    $ofertaCursoEscolas = $ofertaObj->ListarCursosEscolasMatricula();
    $qtd = count($ofertaCursoEscolas);
    for($i = 0; $i < $qtd; $i++)
    {
        $ofertaCursoEscolas[$i]['contratos_nao_aceitos'] = $escola->contratosNaoAceitos((int) $ofertaCursoEscolas[$i]['idescola']);
    }
    unset($_GET['q']['1|c.idcurso'], $_GET['q']['1|ocp.ignorar']);

}

if((int)$url[6]) {

    $ofertaObj->Set("id",$url[4]);
    $ofertaObj->Set("idoferta_curso",$url[5]);
    $_SESSION["matricula"]["idoferta_curso_escola"] = $url[6];
    $ofertaObj->Set("idoferta_curso_escola",$_SESSION["matricula"]["idoferta_curso_escola"]);
    $escola = $ofertaObj->retornarCursoEscola($url[6]);

    $_SESSION['matricula']['idsindicato'] = $escola['idsindicato'];
    $ofertaObj->Set("ordem","asc");
    $ofertaObj->Set("limite","-1");
    $ofertaObj->Set("ordem_campo","t.nome");
    $ofertaObj->Set("campos","t.*, oti.ignorar");
    $ofertaTurmas = $ofertaObj->ListarTurmasMatricula($escola['idsindicato']);

    $turma_retorno = array();
    foreach($ofertaTurmas as $turma) {
        $turma_retorno[$turma['idturma']] = $turma;
        $turma_retorno[$turma['idturma']]['total_turma'] = $ofertaObj->retornarTotalMatriculasPorCursoEscola($url[4], $_SESSION["matricula"]["idcurso"], $escola["idescola"], $turma['idturma']);
    }
    $ofertaTurmas = $turma_retorno;
}

include("idiomas/".$config["idioma_padrao"]."/novamatricula.curso.php");
include("telas/".$config["tela_padrao"]."/novamatricula.curso.php");
