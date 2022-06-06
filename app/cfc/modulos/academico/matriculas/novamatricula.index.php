<?php
switch($url[8]) {
    case 'aluno':
        include('novamatricula.aluno.php');
        break;
    case 'atendente':
        include('novamatricula.vendedor.php');
        break;
    case 'financeiro':
        include('novamatricula.financeiro.php');
        break;
    case 'finalizar':
        include('novamatricula.finalizar.php');
        break;
    default:
        include('novamatricula.curso.php');
        exit;
}