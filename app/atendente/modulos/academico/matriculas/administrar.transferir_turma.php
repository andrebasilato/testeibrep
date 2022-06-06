<?php
include("../classes/ofertas.class.php");
$ofertaObj = new Ofertas();
$ofertaObj->Set("idvendedor",$usu_vendedor["idvendedor"]);
$ofertaObj->Set("idcurso",$matricula['idcurso']);

$matriculaObj->Set("idvendedor",$usu_vendedor["idvendedor"]);

if ($_POST["acao"] == "transferir_turma_salvar") {
    if($matricula["situacao"]["visualizacoes"][30]) {

        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $salvar = $matriculaObj->transferirMatriculaTurma($matricula, $url[7], $url[8], $config['remover_dados_tabelas_transferencias_alunos']);

    } else {
        $salvar["sucesso"] = false;
        $salvar["mensagem"] = "mensagem_permissao_workflow";
    }
    if($salvar["sucesso"]){
        /*$matriculaObj->Set("pro_mensagem_idioma",$salvar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
        $matriculaObj->Set("ancora","financeiromatricula");
        $matriculaObj->Processando();*/
    } else {
        $mensagem["erro"] = $salvar["mensagem"];
    }
}


if($url[6]) {

    $ofertaObj->Set("id",$url[6]);
    $ofertaObj->Set("campos","idoferta, nome");
    $oferta = $ofertaObj->Retornar();
    #print_r2($oferta);

    if($url[7]) {

        $ofertaObj->Set("idoferta_curso_escola",$url[7]);
        $escola = $ofertaObj->retornarCursoEscola($url[7]);
        #print_r2($escola);

        if($url[8]) {

            $ofertaObj->Set("campos","t.nome");
            $ofertaObj->Set("idturma",$url[8]);
            $turma = $ofertaObj->RetornarTurma();
            #print_r2($turma);

        } else {

            $ofertaObj->Set("ordem","asc");
            $ofertaObj->Set("limite","-1");
            $ofertaObj->Set("ordem_campo","t.nome");
            $ofertaObj->Set("campos","t.*, oti.ignorar");
            $ofertaTurmas = $ofertaObj->ListarTurmasMatricula($escola['idsindicato']);

            $turma_retorno = array();
            foreach($ofertaTurmas as $turma) {
                $turma_retorno[$turma['idturma']] = $turma;
                $turma_retorno[$turma['idturma']]['total_turma'] = $ofertaObj->retornarTotalMatriculasPorCursoEscola($oferta["idoferta"], $matricula["idcurso"], $escola["idescola"], $turma['idturma']);
            }
            $ofertaTurmas = $turma_retorno;
            #print_r2($ofertaTurmas);

        }

    } else {

        $ofertaObj->Set("ordem","asc");
        $ofertaObj->Set("limite","-1");
        $ofertaObj->Set("ordem_campo","p.nome_fantasia");
        $ofertaObj->Set("campos","ocp.*, p.nome_fantasia as escola");
        $_GET['q']['1|c.idcurso'] = $matricula['idcurso'];
        $_GET['q']['1|ocp.ignorar'] = 'N';
        $ofertaCursoEscolas = $ofertaObj->ListarCursosEscolasMatricula();
    }
} else {
    $ofertaObj->Set("ordem","asc");
    $ofertaObj->Set("limite","-1");
    $ofertaObj->Set("ordem_campo","o.data_fim_matricula");
    $ofertaObj->Set("campos","o.*");
    $ofertas = $ofertaObj->ListarTodasMatriculas();
}

$curso = $matriculaObj->RetornarCurso();

include("idiomas/".$config["idioma_padrao"]."/administrar.transferir_turma.php");
include("telas/".$config["tela_padrao"]."/administrar.transferir_turma.php");