<?php
unset($_SESSION["matricula"]);

include("../classes/ofertas.class.php");
$ofertaObj = new Ofertas();
$ofertaObj->Set("ordem", "asc");
$ofertaObj->Set("limite", "-1");
$ofertaObj->Set("ordem_campo", "o.data_fim_matricula");
$ofertaObj->Set("campos", "o.*");
$ofertaObj->Set("idescola", $usuario["idescola"]);
$ofertas = $ofertaObj->ListarTodasMatriculas();

$url[4] = intval($url[4]);
$url[5] = intval($url[5]);

if ($url[4]) {
    $_SESSION["matricula"]["idoferta"] = $url[4];
    $ofertaObj->Set("id", $url[4]);
    $ofertaObj->Set("campos", "idoferta, nome");
    $oferta = $ofertaObj->Retornar();

    $ofertaObj->Set("ordem", "asc");
    $ofertaObj->Set("limite", "-1");
    $ofertaObj->Set("idcurriculo","not null");
    $ofertaObj->Set("ordem_campo", "c.nome");
    $ofertaObj->Set("campos", "oc.idoferta_curso, c.nome as curso, ocp.idcurriculo");
    $ofertaCursos = $ofertaObj->ListarTodasCursosMatriculas(true);

    if ($url[5]) {
        $_SESSION["matricula"]["idoferta_curso"] = $url[5];
        $ofertaObj->Set("idoferta_curso", $url[5]);
        $ofertaObj->Set("campos", "oc.idoferta_curso, c.nome as curso, c.idcurso, oc.possui_financeiro");
        $ofertaCurso = $ofertaObj->RetornarCurso();
        $_SESSION['matricula']['idcurso'] = $ofertaCurso['idcurso'];
        $_SESSION['matricula']['possui_financeiro'] = $ofertaCurso['possui_financeiro'];
        $ofertaObj->Set("ordem", "asc");
        $ofertaObj->Set("limite", "-1");
        $ofertaObj->Set("ordem_campo", "p.nome_fantasia");
        $ofertaObj->Set("campos", "ocp.*, p.nome_fantasia as escola");
        $_GET['q']['1|c.idcurso'] = $ofertaCurso['idcurso'];
        $_GET['q']['1|ocp.ignorar'] = 'N';
        $ofertaCursoEscolas = $ofertaObj->ListarCursosEscolasMatricula();

        //Caso seja a parte de selecionar a escola, já seleciona direto o da escola que está.
        if (!$url[6]) {
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/' . $url[5] . '/' . $ofertaCursoEscolas[0]['idoferta_curso_escola'] . '#turmas');
        }

        unset($_GET['q']['1|c.idcurso']);
        unset($_GET['q']['1|ocp.ignorar']);
    }

    if ($url[6]) {
        $_SESSION["matricula"]["idoferta_curso_escola"] = $url[6];

        $ofertaObj->Set("idoferta_curso_escola", $_SESSION["matricula"]["idoferta_curso_escola"]);
        $escola = $ofertaObj->retornarCursoEscola($url[6]);

        $_SESSION['matricula']['idsindicato'] = $escola['idsindicato'];

        $ofertaObj->Set("ordem", "asc");
        $ofertaObj->Set("limite", "-1");
        $ofertaObj->Set("ordem_campo", "t.nome");
        $ofertaObj->Set("campos", "t.*, oti.ignorar");
        $ofertaTurmas = $ofertaObj->ListarTurmasMatricula($escola['idsindicato']);

        $turma_retorno = array();
        foreach ($ofertaTurmas as $turma) {
            $turma_retorno[$turma['idturma']] = $turma;
            $turma_retorno[$turma['idturma']]['total_turma'] = $ofertaObj->retornarTotalMatriculasPorCursoEscola($url[4], $_SESSION["matricula"]["idcurso"], $escola["idescola"], $turma['idturma']);
        }
        $ofertaTurmas = $turma_retorno;
        //print_r2($turma_retorno,true);
    }

}

include("idiomas/" . $config["idioma_padrao"] . "/novamatricula.curso.php");
include("telas/" . $config["tela_padrao"] . "/novamatricula.curso.php");
exit;
?>