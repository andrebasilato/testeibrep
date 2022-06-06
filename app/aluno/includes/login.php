<?php

$opLogin = $_GET["opLogin"];

if(!$opLogin)
    $opLogin = $_POST["opLogin"];

//Atualiza o idsessao atual no banco
if ($opLogin == "atualiza_sessao" && $_SESSION["idsessao"] && $_SESSION["cliente_idpessoa"] && $_SESSION["outra_sessao"]){

    $sql = "update pessoas set  ultimo_view = now(), idsessao = '". $_SESSION["idsessao"] ."' where idpessoa = '".$_SESSION["cliente_idpessoa"]."'";
    mysql_query($sql);
    $_SESSION["outra_sessao"] = false;
}

if($opLogin == "sair" || $opLogin == "inatividade"){

    if($opLogin == "inatividade" && $_SESSION['idacesso'] && $_SESSION['cliente_idpessoa']){
        include_once '../../classes/gestaoacessos.class.php';
        $gestaoAcessosObjeto = new GestaoAcessos();
        $gestaoAcessosObjeto->logoutInatividade($_SESSION['idacesso'], $_SESSION['cliente_idpessoa']);
    }

    if($opLogin == "sair" && $config['datavalid']['logout'] && !$_SESSION['cliente_gestor']){

        include_once '../../classes/core.class.php';
        $coreObj = new Core();
        include_once '../../classes/matriculas_novo.class.php';
        $matriculaObj = new Matriculas();
        $situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
        if(isset($url['3'])){
            $sql = "SELECT 
                        m.idmatricula, c.usar_datavalid AS curso_datavalid, 
                        s.usar_datavalid AS sindicato_datavalid 
                    FROM matriculas m
                    INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                    INNER JOIN sindicatos s ON (m.idsindicato = s.idsindicato)
                    INNER JOIN matriculas_reconhecimentos mr ON (m.idmatricula = mr.idmatricula AND mr.foto_principal = 'S')
                    WHERE m.idmatricula = ".$url['3']."
                    AND m.ativo = 'S'
                    AND m.idsituacao = ".$situacaoAtiva['idsituacao']."
                    AND c.ativo = 'S'
                    AND s.ativo = 'S'
                    ORDER BY m.idmatricula ASC"; 
            $queryBiometria = $coreObj->retornarLinha($sql);

            if($queryBiometria){
                if($queryBiometria['curso_datavalid'] == 'S' && $queryBiometria['sindicato_datavalid'] == 'S'){
                    $sqlUsuario = "SELECT * FROM pessoas WHERE idpessoa = ".$_SESSION['cliente_idpessoa']."";
                    $usuario = mysql_fetch_array(mysql_query($sqlUsuario));
                    $idmatricula = $queryBiometria['idmatricula'];
                    require DIRNAME(__DIR__).'/modulos/academico/curso/idiomas/'. $config['idioma_padrao'] .'/index.reconhecimento.php';
                    require DIRNAME(__DIR__).'/modulos/academico/curso/telas/' . $config['tela_padrao'] . '/index.reconhecimento.php';
                    exit;
                }
            }
        } else {
            $coreObj->sql = "SELECT 
                        m.idmatricula, c.idcurso, c.usar_datavalid AS curso_datavalid, 
                        s.idsindicato, s.usar_datavalid AS sindicato_datavalid
                    FROM matriculas m
                    INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                    INNER JOIN sindicatos s ON (m.idsindicato = s.idsindicato)
                    INNER JOIN matriculas_reconhecimentos mr ON (m.idmatricula = mr.idmatricula AND mr.foto_principal = 'S')
                    WHERE m.idpessoa = ".$_SESSION['cliente_idpessoa']."
                    AND m.ativo = 'S'
                    AND m.idsituacao = ".$situacaoAtiva['idsituacao']."
                    AND c.ativo = 'S'
                    AND s.ativo = 'S'";
            $coreObj->limite = -1;
            $queryBiometria = $coreObj->retornarLinhas();
            foreach($queryBiometria as $biometria){
                if($biometria['curso_datavalid'] == 'S' && $biometria['sindicato_datavalid'] == 'S'){
                    $sqlUsuario = "SELECT * FROM pessoas WHERE idpessoa = ".$_SESSION['cliente_idpessoa']."";
                    $usuario = mysql_fetch_array(mysql_query($sqlUsuario));
                    $idmatricula = $biometria['idmatricula'];
                    require DIRNAME(__DIR__).'/modulos/academico/curso/idiomas/'. $config['idioma_padrao'] .'/index.reconhecimento.php';
                    require DIRNAME(__DIR__).'/modulos/academico/curso/telas/' . $config['tela_padrao'] . '/index.reconhecimento.php';
                    exit;
                }     
            }  
        }
    }

    unset($_SESSION['cliente_email'],
            $_SESSION['cliente_senha'],
            $_SESSION['cliente_idpessoa'],
            $_SESSION['cliente_nome'],
            $_SESSION["cliente_ultimoacesso"],
            $_SESSION["cliente_gestor"],
            $_SESSION["cliente_professor"],
            $_SESSION["ultimo_acesso_ava"],
            $_SESSION['idacesso'],
            $_SESSION['idsessao'],
            $_SESSION["outra_sessao"]);

    $_POST["msg"] = "logout_sucesso";
    header("Location: http://".$_SERVER['SERVER_NAME']."/".$url[0]);
    exit();

} elseif($opLogin == "login"){

    $_POST['txt_usuario'] =  htmlentities($_POST['txt_usuario']);
    $email_escape = addslashes(strtolower($_POST['txt_usuario']));
    $senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);

    $sql = "SELECT * FROM pessoas WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
    $querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    $total_documento = mysql_num_rows($querydocumento);

    if($total_documento == 1){

        $usuario = mysql_fetch_array($querydocumento);

        $tempoMin = date("H");
        $tempoMin = date("Y-m-d H:i:s",mktime($tempoMin, date("i")-10, date("s"), date("m"), date("d"), date("Y")));

        $_SESSION["idsessao"] = rand(11111,99999); // Gera um idSessao de 5 digitos

        //Verificar se usuário estava logado recentemente
        if($usuario["ultimo_acesso"] >= $tempoMin){
            $_SESSION["outra_sessao"] = true;
            $campoIdSessao = null; //não atualiza o idsessao, aguarda o aluno decidir qual sessão irá permanecer
        }else{
            $campoIdSessao = ', idsessao = ' . $_SESSION["idsessao"];
        }


        $_SESSION["cliente_email"]          = $usuario["email"];
        $_SESSION["cliente_senha"]          = $usuario["senha"];
        $_SESSION["cliente_idpessoa"]       = $usuario["idpessoa"];
        $_SESSION["cliente_nome"]           = $usuario["nome"];
        $_SESSION["cliente_ultimoacesso"]   = $usuario["ultimo_acesso"];
        $_SESSION["cliente_alerta"]         = true;

        // Criar um acesso para a pessoa
        $sql = "update pessoas set ultimo_acesso = now(), ultimo_view = now()". $campoIdSessao ." where idpessoa = '".$usuario["idpessoa"]."'";
        mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));




    } elseif($total_documento > 1) {

        $_POST["msg"] = "user_duplicado";
        incluirLib("login",$config);
        exit();

    } else {

        /*
        include("../classes/core.class.php");
        include("../classes/pessoas.class.php");
        $pessoaObj = new Pessoas();
        $sql = "SELECT nome, email FROM pessoas WHERE email = '".$email_escape."'";
        $pessoa = $pessoaObj->retornarLinha($sql);

        $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
        $emailDe = $GLOBALS["config"]["emailSistema"];

        $nomePara = utf8_decode($pessoa["nome"]);
        $emailPara  = $pessoa["email"];
        $assunto  = utf8_decode("Senha incorreta");

        $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                    <br /><br />
                    Est&atilde;o sendo efetuadas tentativas de login em sua conta no sistema ORÁCULO - ".$nomeDe.".
                    <br />
                    Possivelmente, isso se deve ao fato de voc&ecirc; ter perdido a senha ou ao erro de digita&ccedil;&atilde;o de um usu&aacute;rio com o CPF / CNPJ parecido com o seu.
                    <br /><br />
                    Abaixo, encontram-se os dados do seu usu&aacute;rio, a hora e data da tentativa de login e o endereço IP da m&aacute;quina utilizada.
                    <br /><br />
                    E-mail: ".$emailPara."
                    <br />
                    Data: ".date("d/m/Y H:i:s")."
                    <br />
                    IP: ".$_SERVER["REMOTE_ADDR"]."
                    <br /><br />
                    Caso seja voc&ecirc; mesmo que esteja efetuando esta opera&ccedil;&atilde;o, <a href=\"http://".$_SERVER["SERVER_NAME"]."/web/esqueci\">clique aqui</a> que informando seu CPF / CNPJ e sua data de nascimento ser&aacute; poss&iacute;vel cadastrar uma nova senha.
                    <br /><br />";

        $pessoaObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
        */
        $_POST["msg"] = "dados_invalidos";
        incluirLib("login",$config);
        exit();

    }

} else {

    if(!isset($_SESSION["cliente_email"])){
        incluirLib("login",$config);
        exit();
    } else {

        $sql = "SELECT * FROM pessoas WHERE email = '".$_SESSION["cliente_email"]."' AND senha = '".$_SESSION["cliente_senha"]."' and ativo='S' and ativo_login = 'S'";
        $querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        $total_documento = mysql_num_rows($querydocumento);

        if($total_documento != 1){

            incluirLib("login",$config);
            exit();

        } else {

            $usuario = mysql_fetch_assoc($querydocumento);

            if ($_SESSION["cliente_gestor"]) {
                $sql = "SELECT * FROM usuarios_adm WHERE idusuario = '".$_SESSION["cliente_gestor"]."' and ativo = 'S' and ativo_login = 'S'";
                $querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                $usuario["gestor"] = mysql_fetch_assoc($querydocumento);
            } elseif ($_SESSION["cliente_professor"]) {
                $sql = "SELECT * FROM professores WHERE idprofessor = '".$_SESSION["cliente_professor"]."' AND ativo = 'S' AND ativo_login = 'S'";
                $querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                $usuario["professor"] = mysql_fetch_assoc($querydocumento);
            } else {
                //Só atualiza último view, se for o aluno que estiver acessando
                $sql = "update pessoas set ultimo_view = now() where idpessoa = '".$usuario["idpessoa"]."'";
                //mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            }

        }
    }
}
