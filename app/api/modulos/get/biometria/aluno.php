<?php

$matricula    = $_POST['matricula'];
$matricula_escape = addslashes($matricula);

$sql = "SELECT m.idmatricula,
               p.idpessoa,
               p.nome,
               p.email,
               p.avatar_servidor,
               p.biometria,
               p.biometria_arquivo
               
                 FROM matriculas m
                        inner join pessoas p on (m.idpessoa = p.idpessoa)
                        inner join cursos c on (m.idcurso = c.idcurso)
                        WHERE idmatricula='{$matricula_escape}' ";
$queryMatricula = mysql_query($sql) or die(incluirLib("erro", $config, array(
    "sql" => $sql,
    "session" => $_SESSION,
    "get" => $_GET,
    "post" => $_POST,
    "mysql_error" => mysql_error()
)));
$total_matricula = mysql_num_rows($queryMatricula);

if ($total_matricula == 1) {
    
    $matricula = mysql_fetch_assoc($queryMatricula);

    $retorno["matricula"] = $matricula["idmatricula"];
    $retorno["aluno_nome"] = $matricula["nome"];
    $retorno["aluno_email"] = $matricula["email"];
    $retorno["aluno_avatar"] = $matricula["avatar_servidor"];
    $retorno["aluno_idpessoa"] = $matricula["idpessoa"];
    $retorno["aluno_biometria"] = $matricula["biometria"];
    $retorno["aluno_arquivo"] = $matricula["biometria_arquivo"];
    
    $retorno["sucesso"]       = true;
    $retorno["erro"]          = false;
    $retorno["erro_mensagem"] = NULL;    
    
    echo json_encode($retorno);
    exit();
    
} else {
    
    $retorno["sucesso"]       = false;
    $retorno["erro"]          = true;
    $retorno["erro_mensagem"] = "Matrícula não encontrada.";
    echo json_encode($retorno);
    exit();
    
}