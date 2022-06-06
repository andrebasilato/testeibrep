<?php

if ($url[5] == 'subassunto') {
    echo $linhaObj->RetornarJSON('atendimentos_assuntos_subassuntos', (int) $_GET['idassunto'], 'idassunto', 'idsubassunto, nome', 'ORDER BY nome');
} elseif ($url[5] == 'cidades') {
    echo $linhaObj->RetornarJSON('cidades', (int) $_GET['idestado'], 'idestado', 'idcidade, nome', 'ORDER BY nome');
} elseif ($url[5] == 'cursos') {
    echo $linhaObj->RetornarCursosOfertas((int) $_GET['idoferta']);
} elseif ($url[5] == 'escolas') {
    echo $linhaObj->RetornarJSON('escolas', (int) $_GET['idsindicato'], 'idsindicato', 'idescola, nome_fantasia', 'ORDER BY nome_fantasia');
}
