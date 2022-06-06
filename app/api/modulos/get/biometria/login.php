<?php
//$login = true;

$email        = $_POST['email'];
$email_escape = addslashes($email);
$senha        = senhaSegura($_POST["senha"], $config["chaveLogin"]);

$sql = "SELECT idusuario, nome, email FROM usuarios_adm WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
$queryEmail = mysql_query($sql) or die(incluirLib("erro", $config, array(
    "sql" => $sql,
    "session" => $_SESSION,
    "get" => $_GET,
    "post" => $_POST,
    "mysql_error" => mysql_error()
)));
$total_email = mysql_num_rows($queryEmail);

if ($total_email == 1) {
    
    $usuario = mysql_fetch_assoc($queryEmail);
    
    if ($usuario["validade"] && strtotime($usuario["validade"]) < strtotime(date("Y-m-d"))) {
        $retorno["sucesso"]       = false;
        $retorno["erro"]          = true;
        $retorno["erro_mensagem"] = "Usuário fora da validade.";
        echo json_encode($retorno);
        exit();
    }
    
    
    $retorno["sucesso"]           = true;
    $retorno["erro"]              = false;
    $retorno["erro_mensagem"]     = NULL;
    $retorno["usuario_nome"]      = $usuario["nome"];
    $retorno["usuario_idusuario"] = $usuario["idusuario"];
    $retorno["usuario_email"]     = $usuario["email"];    
    
    
} elseif ($total_email > 1) {
    
    $retorno["sucesso"]       = false;
    $retorno["erro"]          = true;
    $retorno["erro_mensagem"] = "Usuário duplicado.";
    echo json_encode($retorno);
    exit();
    
} else {
    
    $retorno["sucesso"]       = false;
    $retorno["erro"]          = true;
    $retorno["erro_mensagem"] = "Dados inválidos.";
    echo json_encode($retorno);
    exit();
    
}