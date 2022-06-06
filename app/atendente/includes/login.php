<?php
$opLogin = $_GET["opLogin"];

if (!$opLogin) {
    $opLogin = $_POST["opLogin"];
}

if ($opLogin == "sair") {
    unset(
        $_SESSION['usu_vendedor_email'],
        $_SESSION['usu_vendedor_senha'],
        $_SESSION['usu_vendedor_idvendedor'],
        $_SESSION['usu_vendedor_nome'],
        $_SESSION["usu_vendedor_ultimoacesso"],
        $_SESSION["usu_vendedor_gestor"]
    );
    $_POST["msg"] = "logout_sucesso";
    header("Location: http://" . $_SERVER['SERVER_NAME'] . "/" . $url[0]);
    exit;
} elseif ($opLogin == "login") {

    $_POST['txt_usuario'] =  htmlentities($_POST['txt_usuario']);
    $email = $_POST['txt_usuario'];
    $email_escape = addslashes($email);
    $senha = senhaSegura($_POST["txt_senha"], $config["chaveLogin"]);

    $sql = "SELECT * FROM vendedores WHERE email='{$email_escape}' AND senha='{$senha}' AND ativo='S'";
    $queryEmail = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    $total_email = mysql_num_rows($queryEmail);

    if ($total_email == 1) {
        $usu_vendedor = mysql_fetch_array($queryEmail);
        if ($usu_vendedor["ativo_login"] == "S") {
            //Muda o sistema para o idioma que o usuario colocou no seu cadastro
            if ($usu_vendedor["idioma"]) {
                $config["idioma_padrao"] = $usu_vendedor["idioma"];
            }

            $_SESSION["usu_vendedor_email"] = $usu_vendedor["email"];
            $_SESSION["usu_vendedor_senha"] = $usu_vendedor["senha"];
            $_SESSION["usu_vendedor_idvendedor"] = $usu_vendedor["idvendedor"];
            $_SESSION["usu_vendedor_nome"] = $usu_vendedor["nome"];
            $_SESSION["usu_vendedor_ultimoacesso"] = $usu_vendedor["ultimo_acesso"];

            $sql = "SELECT i.idsindicato, i.idmantenedora
                    FROM vendedores_sindicatos vi, sindicatos i
                    WHERE
                    vi.idvendedor='{$usu_vendedor["idvendedor"]}' AND
                    vi.ativo='S' AND
                    vi.idsindicato=i.idsindicato AND
                    i.ativo='S'";
            $querySindicatos = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            $_SESSION["usu_vendedor_sindicatos"] = array();
            $_SESSION["usu_vendedor_mantenedoras"] = array();
            while ($sindicato = mysql_fetch_assoc($querySindicatos)) {
                $_SESSION["usu_vendedor_sindicatos"][$sindicato["idsindicato"]] = $sindicato["idsindicato"];
                $_SESSION["usu_vendedor_mantenedoras"][$sindicato["idmantenedora"]] = $sindicato["idmantenedora"];
            }

            if (count($_SESSION["usu_vendedor_sindicatos"]) <= 0) {
                unset(
                    $_SESSION['usu_vendedor_email'],
                    $_SESSION['usu_vendedor_senha'],
                    $_SESSION['usu_vendedor_idvendedor'],
                    $_SESSION['usu_vendedor_nome'],
                    $_SESSION["usu_vendedor_ultimoacesso"],
                    $_SESSION["usu_vendedor_gestor"]
                );

                $_POST["msg"] = "sem_sindicatos";
                incluirLib("login", $config);
                exit;
            } else {
                $_SESSION["usu_vendedor_sindicatos"] = implode(",", $_SESSION["usu_vendedor_sindicatos"]);
                $_SESSION["usu_vendedor_mantenedoras"] = implode(",", $_SESSION["usu_vendedor_mantenedoras"]);
            }

            $sql = "SELECT
                        e.idescola,
                        e.avatar_servidor,
                        e.acesso_bloqueado
                    FROM
                        vendedores_escolas ve, escolas e
                    WHERE
                    ve.idvendedor='{$usu_vendedor["idvendedor"]}' AND
                    ve.ativo='S' AND
                    ve.idescola=e.idescola AND
                    e.ativo='S' ORDER BY ve.idvendedor_escola DESC";
            $queryEscolas = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

            $_SESSION["usu_vendedor_escolas"] = array();
            $escolaBloqueada = true;
            while ($escola = mysql_fetch_assoc($queryEscolas)) {
                $_SESSION['logo_cfc'] = $escola['avatar_servidor'];
                $_SESSION["usu_vendedor_escolas"][$escola["idescola"]] = $escola["idescola"];

                if ($escola['acesso_bloqueado'] == 'N') {
                    $escolaBloqueada = false;
                }
            }

            if ($escolaBloqueada && mysql_num_rows($queryEscolas) > 0) {
                unset(
                    $_SESSION['usu_vendedor_email'],
                    $_SESSION['usu_vendedor_senha'],
                    $_SESSION['usu_vendedor_idvendedor'],
                    $_SESSION['usu_vendedor_nome'],
                    $_SESSION["usu_vendedor_ultimoacesso"],
                    $_SESSION["usu_vendedor_gestor"]
                );

                $_POST['msg'] = 'acesso_bloqueado_cfc';
                incluirLib('login', $config);
                exit;
            }

            if (count($_SESSION["usu_vendedor_escolas"]) > 0) {
                $_SESSION["usu_vendedor_escolas"] = implode(",", $_SESSION["usu_vendedor_escolas"]);
            } else {
                unset($_SESSION["usu_vendedor_escolas"]);
            }
            $sql = "UPDATE vendedores SET relogin = 'N', ultimo_acesso = now(), ultimo_view = now() WHERE idvendedor = '" . $usu_vendedor["idvendedor"] . "'";
            mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        } else {
            $_POST["msg"] = "aguardar_validacao";
            incluirLib("login", $config);
            exit;
        }

    } elseif ($total_email > 1) {
        $_POST["msg"] = "user_duplicado";
        incluirLib("login", $config);
        exit;
    } else {
        $_POST["msg"] = "dados_invalidos";
        incluirLib("login", $config);
        exit;
    }

} else {

    if (!isset($_SESSION["usu_vendedor_email"])) {
        incluirLib("login", $config);
        exit;
    } else {

        $sql = "SELECT * FROM vendedores WHERE email = '" . $_SESSION["usu_vendedor_email"] . "' AND senha = '" . $_SESSION["usu_vendedor_senha"] . "' AND ativo='S' AND ativo_login = 'S' AND relogin = 'N'";
        $queryEmail = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        $total_email = mysql_num_rows($queryEmail);

        if ($total_email != 1) {

            incluirLib("login", $config);
            exit;

        } else {

            $usu_vendedor = mysql_fetch_array($queryEmail);

            //Muda o sistema para o idioma que o usuario colocou no seu cadastro
            if ($usu_vendedor["idioma"]) {
                $config["idioma_padrao"] = $usu_vendedor["idioma"];
            }

            $sql = "UPDATE vendedores SET ultimo_view = now() WHERE idvendedor = '" . $usu_vendedor["idvendedor"] . "'";
            mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

            if ($_SESSION["usu_vendedor_gestor"]) {
                $sql = "SELECT * FROM usuarios_adm WHERE idusuario = '" . $_SESSION["usu_vendedor_gestor"] . "' AND ativo='S' AND ativo_login = 'S'";
                $querydocumento = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                $usu_vendedor["gestor"] = mysql_fetch_assoc($querydocumento);
            }
        }
    }
}

if ($usu_vendedor["idexcecao"]) {
    $sql = "SELECT * FROM excecoes WHERE idexcecao='" . $usu_vendedor["idexcecao"] . "' AND ativo='S'";
    $queryExcecao = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    $excecao = mysql_fetch_assoc($queryExcecao);

    if ($excecao['logo_pequena_servidor']) {
        define("URL_LOGO_PEGUENA", '/storage/excecoes_logo/' . $excecao['logo_pequena_servidor']);
    }
}

if ($_SESSION['logo_cfc']) {
   define('URL_LOGO_PEGUENA', "/api/get/imagens/escolas_avatar/x/50/{$_SESSION["logo_cfc"]}?qualidade=80");
} else {
    defined('URL_LOGO_PEGUENA') or define("URL_LOGO_PEGUENA", "/assets/img/logo_pequena.png");
}
