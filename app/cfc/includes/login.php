<?php

$opLogin = $_GET['opLogin'];
$objConta = new Contas;
if (!$opLogin) {
    $opLogin = $_POST['opLogin'];
}

if ($opLogin == 'sair') {
    unset(
        $_SESSION['escola_email'],
        $_SESSION['escola_senha'],
        $_SESSION['escola_idescola'],
        $_SESSION['escola_nome'],
        $_SESSION['escola_ultimoacesso'],
        $_SESSION['escola_exibir_campos'],
        $_SESSION['modificar_matricula'],
        $_SESSION['criar_matricula']
    );
    session_destroy();

    header('Location: http://'.$_SERVER['SERVER_NAME'].'/'.$url[0]);
    exit();
} elseif ($opLogin == 'login') {
    unset(
        $_SESSION['escola_email'],
        $_SESSION['escola_senha'],
        $_SESSION['escola_idescola'],
        $_SESSION['escola_nome'],
        $_SESSION['escola_ultimoacesso'],
        $_SESSION['escola_exibir_campos'],
        $_SESSION['criar_matricula'],
        $_SESSION['modificar_matricula']
    );

    $_POST['txt_usuario'] =  htmlentities($_POST['txt_usuario']);
    $email = $_POST['txt_usuario'];
    $email_escape = addslashes($email);
    $senha = senhaSegura($_POST['txt_senha'], $config['chaveLogin']);

    $sql = "SELECT *, nome_fantasia AS nome FROM escolas WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
    $queryEmail = mysql_query($sql) or die(incluirLib('erro', $config, array('sql' => $sql, 'session' => $_SESSION, 'get' => $_GET, 'post' => $_POST, 'mysql_error' => mysql_error())));
    $total_email = mysql_num_rows($queryEmail);

    if ($total_email == 1) {
        $usuario = mysql_fetch_assoc($queryEmail);

        //Muda o sistema para o idioma que o usuário colocou no seu cadastro
        if ($usuario['idioma']) {
            $config['idioma_padrao'] = $usuario['idioma'];
        }

        $sql = "update escolas set relogin = 'N', ultimo_acesso = now(), ultimo_view = now() where idescola = '".$usuario['idescola']."'";
        mysql_query($sql) or die(incluirLib('erro', $config, array('sql' => $sql, 'session' => $_SESSION, 'get' => $_GET, 'post' => $_POST, 'mysql_error' => mysql_error())));

        $_SESSION['escola_email'] = $usuario['email'];
        $_SESSION['escola_senha'] = $usuario['senha'];
        $_SESSION['escola_idescola'] = $usuario['idescola'];
        $_SESSION['escola_nome'] = $usuario['nome'];
        $_SESSION['escola_ultimoacesso'] = $usuario['ultimo_acesso'];
        $_SESSION['escola_exibir_campos'] = $usuario['exibir_campos'];
        $_SESSION['criar_matricula'] = $usuario['criar_matricula'];
        $_SESSION['modificar_matricula'] = $usuario['modificar_matricula'];
        include_once '../classes/contas.class.php';
        $contasAbertas = $objConta->retornarContasAbertasEscola($usuario['idescola']);
        $_SESSION["cfc_aviso"] = $GLOBALS['config']['cfc_aviso'] && (count($contasAbertas) > 0);
    } elseif ($total_email > 1) {
        $_POST['msg'] = 'user_duplicado';
        incluirLib('login', $config);
        exit();
    } else {
        include_once '../classes/escolas.class.php';
        $usuarioObj = new Escolas();
        $sql = "SELECT idescola, nome_fantasia AS nome, email FROM escolas WHERE email = '".$email_escape."'";
        $usuario = $usuarioObj->retornarLinha($sql);

        if ($usuario['idescola']) {
            $nomeDe = $GLOBALS['config']['tituloEmpresa'];
            $emailDe = $GLOBALS['config']['emailSistema'];

            $nomePara = utf8_decode($usuario['nome']);
            $emailPara = $usuario['email'];
            $assunto = utf8_decode('Senha incorreta');

            $message = 'Ol&aacute; <strong>'.$nomePara.'</strong>,
                                    <br /><br />
                                    Est&atilde;o sendo efetuadas tentativas de login em sua conta no sistema OR&Aacute;CULO - '.$nomeDe.'.
                                    <br />
                                    Possivelmente, isso se deve ao fato de voc&ecirc; ter perdido a senha ou ao erro de digita&ccedil;&atilde;o de um usu&aacute;rio com o e-mail parecido com o seu.
                                    <br /><br />
                                    Abaixo, encontram-se os dados do seu usu&aacute;rio, a hora e data da tentativa de login e o endere&ccedil;o IP da m&aacute;quina utilizada.
                                    <br /><br />
                                    E-mail: '.$emailPara.'
                                    <br />
                                    Data: '.date('d/m/Y H:i:s').'
                                    <br />
                                    IP: '.$_SERVER['REMOTE_ADDR'].'
                                    <br /><br />
                                    Caso seja voc&ecirc; mesmo que esteja efetuando esta opera&ccedil;&atilde;o, <a href="http://'.$_SERVER['SERVER_NAME'].'/'.$url[0].'/esqueci">clique aqui</a> e solicite uma nova senha que ser&aacute; enviada para o email cadastrado.
                                    <br /><br />';
            $usuarioObj->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout');
        }

        $_POST['msg'] = 'dados_invalidos';
        incluirLib('login', $config);
        exit();
    }
} else {
    $contasAbertas = $objConta->retornarContasAbertasEscola($_SESSION['escola_idescola']);
    $_SESSION["cfc_aviso"] = (count($contasAbertas) > 0);
    if (!isset($_SESSION['escola_email'])) {
        incluirLib('login', $config);
        exit();
    } else {
        $sql = "SELECT *,nome_fantasia AS nome FROM escolas WHERE email = '".$_SESSION['escola_email']."' AND senha = '".$_SESSION['escola_senha']."' and ativo='S' and relogin = 'N' "; // and ativo_login = 'A'
        $queryEmail = mysql_query($sql) or die(incluirLib('erro', $config, array('sql' => $sql, 'session' => $_SESSION, 'get' => $_GET, 'post' => $_POST, 'mysql_error' => mysql_error())));
        $total_email = mysql_num_rows($queryEmail);

        if ($total_email != 1) {
            unset($_POST['msg']);
            incluirLib('login', $config);
            exit();
        } else {
            $usuario = mysql_fetch_assoc($queryEmail);

            //Muda o sistema para o idioma que o usuario colocou no seu cadastro
            if ($usuario['idioma']) {
                $config['idioma_padrao'] = $usuario['idioma'];
            }

            $sql = "update escolas set ultimo_view = now() where idescola = '".$usuario['idescola']."'";
            mysql_query($sql) or die(incluirLib('erro', $config, array('sql' => $sql, 'session' => $_SESSION, 'get' => $_GET, 'post' => $_POST, 'mysql_error' => mysql_error())));
        }
    }
}

if ($usuario["avatar_servidor"]) {
    define('URL_LOGO_PEGUENA', "/api/get/imagens/escolas_avatar/x/50/{$usuario["avatar_servidor"]}?qualidade=80");
} else {
    defined('URL_LOGO_PEGUENA') or define('URL_LOGO_PEGUENA', '/assets/img/logo_pequena.png');
}
