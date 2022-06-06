<?php

$aluno    = $_POST['codigodoaluno'];
$aluno_escape = addslashes($aluno);

$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

if ($_FILES["arquivo"]["error"] > 0) {
    $retorno["sucesso"]       = false;
    $retorno["erro"]          = true;
    $retorno["erro_mensagem"] = "Ocorreu algum erro com o upload do arquivo: ".$_UP['erros'][$_FILES["file"]["error"]]; 
    echo json_encode($retorno);
    exit();
}
if($_FILES["arquivo"]["name"]) {
    $extensoes = array("fpt");
    $temp = explode(".", $_FILES["arquivo"]["name"]);
    $extensao = end($temp);
    if (!in_array($extensao, $extensoes)) {
        $retorno["sucesso"]       = false;
        $retorno["erro"]          = true;
        $retorno["erro_mensagem"] = "Extensão inválida.";
        echo json_encode($retorno);
        exit();
    }
} else {
    $retorno["sucesso"]       = false;
    $retorno["erro"]          = true;
    $retorno["erro_mensagem"] = "Arquivo não enviado."; 
    echo json_encode($retorno);
    exit();
}
 

$sql = "SELECT * FROM pessoas p WHERE idpessoa='{$aluno_escape}' ";
$queryAluno = mysql_query($sql) or die(incluirLib("erro", $config, array(
    "sql" => $sql,
    "session" => $_SESSION,
    "get" => $_GET,
    "post" => $_POST,
    "mysql_error" => mysql_error()
)));
$total_aluno = mysql_num_rows($queryAluno);

if ($total_aluno == 1) {
    
    $aluno = mysql_fetch_assoc($queryAluno);

    //﻿ -> /var/www/oraculo_desenv/desenvolvimento
    $caminho = $_SERVER['DOCUMENT_ROOT']."/storage/biometria/";
    $arquivo = time().".".$extensao;
    
    move_uploaded_file($_FILES["arquivo"]["tmp_name"],$caminho.$arquivo);
    
    $sql = "UPDATE pessoas SET biometria='S', biometria_arquivo='$arquivo' WHERE idpessoa='{$aluno_escape}' ";
    $queryAluno = mysql_query($sql) or die(incluirLib("erro", $config, array(
        "sql" => $sql,
        "session" => $_SESSION,
        "get" => $_GET,
        "post" => $_POST,
        "mysql_error" => mysql_error()
    ))); 
    
    
    
    
    if( file_exists($caminho.$aluno["biometria_arquivo"]) ){
        unlink($caminho.$aluno["biometria_arquivo"]);
    }
    $aluno["biometria_arquivo"] =  $arquivo;
    
    
              

    $retorno["aluno_codigo"] = $aluno["idpessoa"];
    $retorno["aluno_nome"] = $aluno["nome"];
    $retorno["aluno_email"] = $aluno["email"];
    $retorno["aluno_avatar"] = $aluno["avatar_servidor"];
    $retorno["aluno_biometria"] = $aluno["biometria"];
    $retorno["aluno_arquivo"] = $aluno["biometria_arquivo"];
    
    
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