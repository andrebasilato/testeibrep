<?php
//$login = true;
$opLogin = $_GET["opLogin"];

if(!$opLogin)
    $opLogin = $_POST["opLogin"];

if($opLogin == "sair"){

    unset($_SESSION['adm_email'],$_SESSION['adm_senha'],$_SESSION['adm_idusuario'],$_SESSION['adm_nome'],$_SESSION["adm_ultimoacesso"]);
    $_POST["msg"] = "logout_sucesso";
    header("Location: http://".$_SERVER['SERVER_NAME']."/".$url[0]);
    exit();

} elseif($opLogin == "login"){

    $email = $_POST['txt_usuario'];
    $email_escape = addslashes($email);
    $senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);

    $sql = "SELECT * FROM usuarios_adm WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
    $queryEmail = mysql_query($sql) or die(incluirLib("erro",$config));
    $total_email = mysql_num_rows($queryEmail);

    if($total_email == 1){

        $usuario = mysql_fetch_array($queryEmail);

        if($usuario["validade"] && strtotime($usuario["validade"]) < strtotime(date("Y-m-d"))) {
            $_POST["msg"] = "validade_expirada";
            $arrayLogin = array("erro" => true, "mensagem" => "Login sem validade.");
            echo json_encode($arrayLogin);
            exit();
        }

        //Muda o sistema para o idioma que o usuário colocou no seu cadastro
        if($usuario["idioma"]) {
            $config["idioma_padrao"] = $usuario["idioma"];
        }

        if($usuario["idperfil"]){

            $sql = "SELECT * FROM usuarios_adm_perfis WHERE idperfil='".$usuario["idperfil"]."' and ativo='S'";
            $queryPerfil = mysql_query($sql) or die(incluirLib("erro",$config));
            $perfil = mysql_fetch_array($queryPerfil);
            $perfil["permissoes"] = unserialize($perfil["permissoes"]);

            $_SESSION["adm_email"] = $usuario["email"];
            $_SESSION["adm_senha"] = $usuario["senha"];
            $_SESSION["adm_idusuario"] = $usuario["idusuario"];
            $_SESSION["adm_nome"] = $usuario["nome"];
            $_SESSION["adm_ultimoacesso"] = $usuario["ultimo_acesso"];

            $sql = "update usuarios_adm set ultimo_acesso = now(), ultimo_view = now() where idusuario = '".$usuario["idusuario"]."'";
            mysql_query($sql) or die(incluirLib("erro",$config));
            //$login = false;
        } else {
          $_POST["msg"] = "sem_perfil";
          incluirLib("login",$config);
          $arrayLogin = array("erro" => true, "mensagem" => "Login sem perfil.");
          echo json_encode($arrayLogin);
          exit();
        }

    } elseif($total_email > 1) {

        $_POST["msg"] = "user_duplicado";
            $arrayLogin = array("erro" => true, "mensagem" => "Login duplicado.");
            echo json_encode($arrayLogin);
        exit();

    } else {

        $_POST["msg"] = "dados_invalidos";
            $arrayLogin = array("erro" => true, "mensagem" => "Login inválido.");
            echo json_encode($arrayLogin);
        exit();

    }


} else {

    if(!isset($_SESSION["adm_email"])){
            $arrayLogin = array("erro" => true, "mensagem" => "Aguardando login");
            echo json_encode($arrayLogin);
    } else {

        $sql = "SELECT * FROM usuarios_adm WHERE email = '".$_SESSION["adm_email"]."' AND senha = '".$_SESSION["adm_senha"]."' and ativo='S'"; // and ativo_login = 'A'
        $queryEmail = mysql_query($sql) or die(incluirLib("erro",$config));
        $total_email = mysql_num_rows($queryEmail);

        if($total_email != 1){

            $arrayLogin = array("erro" => true, "mensagem" => "Aguardando login");
            echo json_encode($arrayLogin);

        } else {

            $usuario = mysql_fetch_array($queryEmail);

            if($usuario["validade"] && strtotime($usuario["validade"]) < strtotime(date("Y-m-d"))) {
                $_POST["msg"] = "validade_expirada";
                $arrayLogin = array("erro" => true, "mensagem" => "Login sem validade.");
                echo json_encode($arrayLogin);
                exit();
            }

            //Muda o sistema para o idioma que o usuario colocou no seu cadastro
            if($usuario["idioma"]) {
                $config["idioma_padrao"] = $usuario["idioma"];
            }

            if($usuario["idperfil"]){
                $sql = "SELECT * FROM usuarios_adm_perfis WHERE idperfil='".$usuario["idperfil"]."' and ativo='S'";
                $queryPerfil = mysql_query($sql) or die(incluirLib("erro",$config));
                $perfil = mysql_fetch_array($queryPerfil);
                $usuario["perfil"] = $perfil;
                $perfil["permissoes"] = unserialize($perfil["permissoes"]);

                $sql = "update usuarios_adm set ultimo_view = now() where idusuario = '".$usuario["idusuario"]."'";
                mysql_query($sql) or die(incluirLib("erro",$config));
                //$login = false;
            } else {
                $_POST["msg"] = "sem_perfil";
                $arrayLogin = array("erro" => true, "mensagem" => "Login sem perfil.");
                echo json_encode($arrayLogin);
                exit();
            }

        }
    }
}
