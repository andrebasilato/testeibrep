<?php
require_once '../classes/pessoas.class.php';

$pessoa = new Pessoas();


if ($_FILES) {
    foreach($_FILES as $ind => $val) {
        $_POST[$ind] = $val;
    }
}

$matricula["diploma"] = $matriculaObj->hasDiploma(Request::url(4));
$matricula["pessoa"] = $matriculaObj->RetornarPessoa();
$matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
if ($matricula['oferta_curso']['idfolha']) {
    $matricula["alunoAprovadoNotas"] = $matriculaObj->verificaMatriculaAprovadaNotas($matricula['oferta_curso']['porcentagem_minima_disciplinas']);
    $matricula["alunoAprovadoNotasDias"] = $matriculaObj->verificaMatriculaAprovadaNotasDias($matricula['idmatricula'],$matricula['idoferta'], $matricula['idcurso']);
}

unset($pessoa);
if($url[5]) {

    $arquivo_controller = $_SERVER["DOCUMENT_ROOT"]."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/administrar.".$url[5].".php";
    if (file_exists($arquivo_controller)) {
        include($arquivo_controller);
    } else {
        echo "Opção não encontrada";
    }

} else {
    include("administrar.index.php");
}
