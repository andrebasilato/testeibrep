<?php
$opLogin = $_GET["opLogin"];

if(!$opLogin)
    $opLogin = $_POST["opLogin"];

if($opLogin == "sair") {

    unset($_SESSION['adm_email'],$_SESSION['adm_senha'],$_SESSION['adm_idusuario']);
    session_destroy();

    header("Location: http://".$_SERVER['SERVER_NAME']."/".$url[0]);
exit();

} elseif($opLogin == "login") {

    unset($_SESSION['adm_email'],$_SESSION['adm_senha'],$_SESSION['adm_idusuario']);

    $_POST['txt_usuario'] =  htmlentities($_POST['txt_usuario']);
    $email = $_POST['txt_usuario'];
    $email_escape = addslashes($email);
    $senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);

    $sql = "SELECT * FROM usuarios_adm WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
    $queryEmail = mysql_query($sql) or die(incluirLib("erro", $config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    $total_email = mysql_num_rows($queryEmail);

    if($total_email == 1){
        $usuario = mysql_fetch_assoc($queryEmail);

        if ($usuario["orio"] == 'N') {
            $_POST["msg"] = "sem_acesso_orio";
            incluirLib("login", $config);
            exit();
        }

        if($usuario["validade"] && strtotime($usuario["validade"]) < strtotime(date("Y-m-d"))) {
            $_POST["msg"] = "validade_expirada";
            incluirLib("login",$config);
            exit();
        }

        //Muda o sistema para o idioma que o usuário colocou no seu cadastro
        if($usuario["idioma"]) {
            $config["idioma_padrao"] = $usuario["idioma"];
        }

        if($usuario["idperfil"]){

            $sql = "SELECT * FROM usuarios_adm_perfis WHERE idperfil='".$usuario["idperfil"]."' and ativo='S'";
            $queryPerfil = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            $perfil = mysql_fetch_assoc($queryPerfil);
            $usuario["perfil"] = $perfil;
            $perfil["permissoes"] = unserialize($perfil["permissoes"]);

            $_SESSION["adm_gestor_sindicato"] = $usuario["gestor_sindicato"];
            if($usuario["gestor_sindicato"] <> "S") {
                $sql = "select i.idusuario_sindicato, i.idmantenedora from usuarios_adm_sindicatos uas INNER JOIN sindicatos i using (idusuario_sindicato) where uas.idusuario='".$usuario["idusuario"]."' and uas.ativo='S' and i.ativo='S'";
                $queryInstituicoes = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                $_SESSION["adm_sindicatos"] = array();
                $_SESSION["adm_mantenedoras"] = array();
                while($sindicato = mysql_fetch_assoc($queryInstituicoes)){
                    $_SESSION["adm_sindicatos"][$sindicato["idusuario_sindicato"]] = $sindicato["idusuario_sindicato"];
                    $_SESSION["adm_mantenedoras"][$sindicato["idmantenedora"]] = $sindicato["idmantenedora"];
                }
                if(count($_SESSION["adm_sindicatos"]) <= 0) {
                    $_POST["msg"] = "sem_sindicatos";
                    incluirLib("login",$config);
                    exit();
                }

                $_SESSION["adm_sindicatos"] = implode(",", $_SESSION["adm_sindicatos"]);
                $_SESSION["adm_mantenedoras"] = implode(",", $_SESSION["adm_mantenedoras"]);

                $sql_polos = 'select idpolo from polos where ativo = "S" and idusuario_sindicato in (' . $_SESSION["adm_sindicatos"] . ') ';
                $resultado_polos = mysql_query($sql_polos);
                while ($polo = mysql_fetch_assoc($resultado_polos)) {
                    $polos[] = $polo['idpolo'];
                }
                $_SESSION['adm_polos'] = 0;
                if ($polos)
                    $_SESSION['adm_polos'] = implode(',', $polos);

                $sql_cursos = 'select idcurso from cursos_sindicatos where ativo = "S" and idusuario_sindicato in (' . $_SESSION["adm_sindicatos"] . ') ';
                $resultado_cursos = mysql_query($sql_cursos);
                while ($curso = mysql_fetch_assoc($resultado_cursos)) {
                        $cursos[] = $curso['idcurso'];
                }
                $_SESSION['adm_cursos'] = 0;
                if ($cursos)
                    $_SESSION['adm_cursos'] = implode(',', $cursos);

            }

            $sql_assuntos = '
                            SELECT
                                aag.idassunto
                            FROM
                                atendimentos_assuntos_grupos aag
                                INNER JOIN grupos_usuarios_adm_usuarios guau ON guau.idgrupo = aag.idgrupo AND guau.ativo = "S"
                                INNER JOIN grupos_usuarios_adm gua ON guau.idgrupo = gua.idgrupo AND gua.ativo = "S"
                            WHERE
                                guau.idusuario = "' . $usuario["idusuario"] . '"
                                AND aag.ativo = "S" ';
            $resultado_assuntos = mysql_query($sql_assuntos);
            while ($assunto = mysql_fetch_assoc($resultado_assuntos)) {
                $assuntos[$assunto['idassunto']] = $assunto['idassunto'];
            }

            $sql_subassuntos = '
                                SELECT
                                    aasg.idsubassunto, aas.idassunto
                                FROM
                                    atendimentos_assuntos_subassuntos_grupos aasg
                                    INNER JOIN grupos_usuarios_adm_usuarios guau ON guau.idgrupo = aasg.idgrupo AND guau.ativo = "S"
                                    INNER JOIN grupos_usuarios_adm gua ON guau.idgrupo = gua.idgrupo AND gua.ativo = "S"
                                    INNER JOIN atendimentos_assuntos_subassuntos aas ON aasg.idsubassunto = aas.idsubassunto
                                WHERE
                                    guau.idusuario = "' . $usuario["idusuario"] . '"
                                    AND aasg.ativo = "S" ';
            $resultado_subassuntos = mysql_query($sql_subassuntos);
            while ($subassunto = mysql_fetch_assoc($resultado_subassuntos)) {
                if (isset($assuntos[$subassunto['idassunto']]))
                    unset($assuntos[$subassunto['idassunto']]);

                $subassuntos[] = $subassunto['idsubassunto'];
            }

            $_SESSION['adm_assuntos'] = 0;
            if ($assuntos)
                $_SESSION['adm_assuntos'] = implode(',', $assuntos);

            $_SESSION['adm_subassuntos'] = 0;
            if ($subassuntos)
                $_SESSION['adm_subassuntos'] = implode(',', $subassuntos);

            $sql = "update usuarios_adm set relogin = 'N', ultimo_acesso = now(), ultimo_view = now() where idusuario = '".$usuario["idusuario"]."'";
            mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //$login = false;

            include("verifica_permissoes.php");

            $_SESSION["adm_email"] = $usuario["email"];
            $_SESSION["adm_senha"] = $usuario["senha"];
            $_SESSION["adm_idusuario"] = $usuario["idusuario"];
            $_SESSION["adm_nome"] = $usuario["nome"];
            $_SESSION["adm_ultimoacesso"] = $usuario["ultimo_acesso"];

        } else {
            $_POST["msg"] = "sem_perfil";
            incluirLib("login",$config);
            exit();
        }

    } elseif($total_email > 1) {

        $_POST["msg"] = "user_duplicado";
        incluirLib("login",$config);
        exit();

    } else {

        include("../classes/usuarios.class.php");
        $usuarioObj = new Usuarios();
        $sql = "SELECT idusuario, nome, email FROM usuarios_adm WHERE email = '".$email_escape."'";
        $usuario = $usuarioObj->retornarLinha($sql);

        if($usuario['idusuario']) {
            $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
            $emailDe = $GLOBALS["config"]["emailSistema"];

            $nomePara = utf8_decode($usuario["nome"]);
            $emailPara  = $usuario["email"];
            $assunto  = utf8_decode("Senha incorreta");

            $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                                    <br /><br />
                                    Est&atilde;o sendo efetuadas tentativas de login em sua conta no sistema OR&Aacute;CULO - ".$nomeDe.".
                                    <br />
                                    Possivelmente, isso se deve ao fato de voc&ecirc; ter perdido a senha ou ao erro de digita&ccedil;&atilde;o de um usu&aacute;rio com o e-mail parecido com o seu.
                                    <br /><br />
                                    Abaixo, encontram-se os dados do seu usu&aacute;rio, a hora e data da tentativa de login e o endere&ccedil;o IP da m&aacute;quina utilizada.
                                    <br /><br />
                                    E-mail: ".$emailPara."
                                    <br />
                                    Data: ".date("d/m/Y H:i:s")."
                                    <br />
                                    IP: ".$_SERVER["REMOTE_ADDR"]."
                                    <br /><br />
                                    Caso seja voc&ecirc; mesmo que esteja efetuando esta opera&ccedil;&atilde;o, <a href=\"http://".$_SERVER["SERVER_NAME"]."/orio/esqueci\">clique aqui</a> e solicite uma nova senha que ser&aacute; enviada para o email cadastrado.
                                    <br /><br />";


            $usuarioObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
        }

        $_POST["msg"] = "dados_invalidos";
        incluirLib("login",$config);
        exit();

    }

} else {
//if($login == true){

    if(!isset($_SESSION["adm_email"])){
        incluirLib("login",$config);
        exit();
    } else {

        $sql = "SELECT * FROM usuarios_adm WHERE email = '".$_SESSION["adm_email"]."' AND senha = '".$_SESSION["adm_senha"]."' and ativo='S' and relogin = 'N' "; // and ativo_login = 'A'
        $queryEmail = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        $total_email = mysql_num_rows($queryEmail);

        if($total_email != 1){
            unset($_POST['msg']);
            $_POST['msg'] = 'informacoes_gerenciais_alteradas';
            incluirLib("login",$config);
            exit();

        } else {

            $usuario = mysql_fetch_assoc($queryEmail);

            if($usuario["validade"] && strtotime($usuario["validade"]) < strtotime(date("Y-m-d"))) {
                $_POST["msg"] = "validade_expirada";
                incluirLib("login",$config);
                exit();
            }

            //Muda o sistema para o idioma que o usuario colocou no seu cadastro
            if($usuario["idioma"]) {
                $config["idioma_padrao"] = $usuario["idioma"];
            }

            if($usuario["idperfil"]){
                $sql = "SELECT * FROM usuarios_adm_perfis WHERE idperfil='".$usuario["idperfil"]."' and ativo='S'";
                $queryPerfil = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                $perfil = mysql_fetch_assoc($queryPerfil);
                $usuario["perfil"] = $perfil;
                $perfil["permissoes"] = unserialize($perfil["permissoes"]);

                $sql = "update usuarios_adm set ultimo_view = now() where idusuario = '".$usuario["idusuario"]."'";
                mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                //$login = false;
            } else {
                $_POST["msg"] = "sem_perfil";
                incluirLib("login",$config);
                exit();
            }

            /*
            // Página de acessos
            if($url[1] && $url[2]){
                $modulo = $url[1];
                $funcionalidade = $url[2];
                $sql = "insert into _acessos set data_cad = now(), modulo = '".$modulo."', funcionalidade = '".$funcionalidade."', idusuario = '".$usuario["idusuario"]."'";
                mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            }
            */

        }
    }
}

if($opLogin != "sair") {

    if($usuario["idhorario"]){
        $sql = "SELECT
                    count(*) as total, uh.painel
                FROM
                    usuarios_adm u
                    INNER JOIN horarios_de_acesso uh ON (uh.idhorario=u.idhorario)
                    INNER JOIN horarios_de_acesso_periodos hp ON (hp.idhorario=uh.idhorario)
                WHERE
                    u.ativo = 'S' AND
                    u.idusuario = '".$usuario["idusuario"]."' AND
                    uh.ativo = 'S' AND
                    uh.painel = 'gestor' AND
                    hp.ativo = 'S' AND
                    hp.idhorario = '".$usuario["idhorario"]."' AND
                    hp.dia_semana = '".date("N")."' AND
                    DATE_FORMAT(hp.hora_de, '%H%i%s') <= DATE_FORMAT(NOW(), '%H%i%s') AND
                    DATE_FORMAT(hp.hora_ate, '%H%i%s') >= DATE_FORMAT(NOW(), '%H%i%s')";
        $verifica = mysql_query($sql) or die(incluirLib("login",$config));
        $horarios = mysql_fetch_assoc($verifica);
    }

    if($horarios["painel"] == "gestor" && ($horarios["total"] == 0 || empty($horarios["total"]) || !$usuario["idhorario"])){
        unset($_SESSION);
        $_POST["msg"] = "horario_indisponivel";
        incluirLib("login",$config);
        exit();
    }

}

if ($usuario["idexcecao"]) {
    $sql = "SELECT * FROM excecoes WHERE idexcecao='".$usuario["idexcecao"]."' and ativo='S'";
    $queryExcecao = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    $excecao = mysql_fetch_assoc($queryExcecao);

    if ($excecao['logo_pequena_servidor']) {
        define("URL_LOGO_PEGUENA", '/storage/excecoes_logo/'.$excecao['logo_pequena_servidor']);
    }
}
defined('URL_LOGO_PEGUENA') or define("URL_LOGO_PEGUENA", "/assets/img/logo_pequena.png");
