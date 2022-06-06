<?php
if((int) $url[6]) {
    if($url[7] == 'mensagens') {
        require 'idiomas/'.$config['idioma_padrao'].'/meusprofessores.mensagens.php';
        require 'telas/'.$config['tela_padrao'].'/meusprofessores.mensagens.php';
        exit;
    } else {
        $disciplinas = $matriculaObj->retornarDisciplinasProfessor((int) $url[6]);

        require 'idiomas/'.$config['idioma_padrao'].'/meusprofessores.disciplinas.php';
        require 'telas/'.$config['tela_padrao'].'/meusprofessores.disciplinas.php';
        exit;
    }
} else {

    include_once '../classes/categoriastiraduvidas.class.php';
    $objCategoria = new CategoriasTiraDuvidas();
    $objCategoria->Set('campos','*');
    $objCategoria->Set('campos_professor','p.*');
    $professoresCategorias = $objCategoria->listarProfessoresCategorias($ava['idava']);
    
    $professores = $matriculaObj->retornarProfessores($ava['idava']);
    $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "professores");

    require 'idiomas/'.$config['idioma_padrao'].'/meusprofessores.php';
    require 'telas/'.$config['tela_padrao'].'/meusprofessores.php';
    exit;
}