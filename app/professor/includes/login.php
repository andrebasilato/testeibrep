<?php
$opLogin = $_GET["opLogin"];

if(!$opLogin)
	$opLogin = $_POST["opLogin"];

if($opLogin == "sair"){

  	unset($_SESSION['usu_professor_email'],$_SESSION['usu_professor_senha'],$_SESSION['usu_professor_idprofessor'],$_SESSION['usu_professor_nome'],$_SESSION["usu_professor_ultimoacesso"],$_SESSION["usu_professor_gestor"]);
	$_POST["msg"] = "logout_sucesso";
	header("Location: http://".$_SERVER['SERVER_NAME']."/".$url[0]);
	exit();

} elseif($opLogin == "login"){

    $_POST['txt_usuario'] =  htmlentities($_POST['txt_usuario']);
	$email = $_POST['txt_usuario'];
	$email_escape = addslashes($email);
	$senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);

	$sql = "SELECT * FROM professores WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S'";
	$queryEmail = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	$total_email = mysql_num_rows($queryEmail);

	if($total_email == 1){
		$usu_professor = mysql_fetch_array($queryEmail);
		if($usu_professor["ativo_login"] == "S") {
		  //Muda o sistema para o idioma que o usuario colocou no seu cadastro
		  if($usu_professor["idioma"]) {
			  $config["idioma_padrao"] = $usu_professor["idioma"];
		  }

		  $_SESSION["usu_professor_email"] 			= $usu_professor["email"];
		  $_SESSION["usu_professor_senha"] 			= $usu_professor["senha"];
		  $_SESSION["usu_professor_idprofessor"] 		= $usu_professor["idprofessor"];
		  $_SESSION["usu_professor_nome"] 			    = $usu_professor["nome"];
		  $_SESSION["usu_professor_ultimoacesso"] 	    = $usu_professor["ultimo_acesso"];

		  $sql = "update professores set ultimo_acesso = now(), ultimo_view = now() where idprofessor = '".$usu_professor["idprofessor"]."'";
		  mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		} else {
		  $_POST["msg"] = "aguardar_validacao";
		  incluirLib("login",$config);
		  exit();
		}

	} elseif($total_email > 1) {
		$_POST["msg"] = "user_duplicado";
		incluirLib("login",$config);
	    exit();
	} else {
	  $_POST["msg"] = "dados_invalidos";
	  incluirLib("login",$config);
	  exit();
	}

} else {

	if(!isset($_SESSION["usu_professor_email"])){
		incluirLib("login",$config);
		exit();
	} else {

		$sql = "SELECT * FROM professores WHERE email = '".$_SESSION["usu_professor_email"]."' AND senha = '".$_SESSION["usu_professor_senha"]."' and ativo='S' and ativo_login = 'S'";
		$queryEmail = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		$total_email = mysql_num_rows($queryEmail);

		if($total_email != 1){

			incluirLib("login",$config);
			exit();

		} else {

			$usu_professor = mysql_fetch_assoc($queryEmail);

			//Muda o sistema para o idioma que o usuario colocou no seu cadastro
			if($usu_professor["idioma"]) {
				$config["idioma_padrao"] = $usu_professor["idioma"];
			}

			$sql = "update professores set ultimo_view = now() where idprofessor = '".$usu_professor["idprofessor"]."'";
			mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));



			if($_SESSION["usu_professor_gestor"]){
				$sql = "SELECT * FROM usuarios_adm WHERE idusuario = '".$_SESSION["usu_professor_gestor"]."' and ativo='S' and ativo_login = 'S'";
				$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
				$usu_professor["gestor"] = mysql_fetch_assoc($querydocumento);
			}


		}
	}
}
?>
