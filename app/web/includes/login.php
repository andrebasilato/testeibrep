<?php
$opLogin = $_GET["opLogin"];
unset($_SESSION["novaAcao"]);
if (!$opLogin) {
	$opLogin = $_POST["opLogin"];
}

if ($opLogin == 'sair') {
    $slug =  $_SESSION['dados_escola']['slug'];

  	unset(
        $_SESSION['cliente_email'],
  		$_SESSION['cliente_senha'],
	  	$_SESSION['cliente_idpessoa'],
	  	$_SESSION['cliente_nome'],
	  	$_SESSION["cliente_ultimoacesso"],
	  	$_SESSION["cliente_gestor"],
	  	$_SESSION["cliente_professor"],
	  	$_SESSION["ultimo_acesso_ava"],
	  	$_SESSION["cliente_avatar_servidor"],
	  	$_SESSION['loja_etapa'],
	  	$_SESSION['loja'],
        $_SESSION['dados_escola']
    );
	$_POST['msg'] = 'logout_sucesso';
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $slug);
	exit;
} elseif ($opLogin == 'login') {
	$email_escape = addslashes(strtolower($_POST['txt_usuario']));
	$senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);

	$sql = "SELECT * FROM pessoas WHERE email='{$email_escape}' AND senha='{$senha}' and ativo='S' and ativo_login = 'S'";
	$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	$total_documento = mysql_num_rows($querydocumento);

	if ($total_documento == 1) {
		$usuario = mysql_fetch_assoc($querydocumento);

		$_SESSION["cliente_email"] 		     = $usuario["email"];
		$_SESSION["cliente_senha"] 			 = $usuario["senha"];
		$_SESSION["cliente_idpessoa"] 		 = $usuario["idpessoa"];
		$_SESSION["cliente_nome"] 			 = $usuario["nome"];
		$_SESSION["cliente_ultimoacesso"] 	 = $usuario["ultimo_acesso"];
		$_SESSION["cliente_avatar_servidor"] = $usuario["avatar_servidor"];

		$sql = "update pessoas set ultimo_acesso = now(), ultimo_view = now() where idpessoa = '".$usuario["idpessoa"]."'";
		mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
	} elseif ($total_documento > 1) {
		$_POST["msg"] = "user_duplicado";
		incluirLib("login",$config,$curso);
	    exit;
	} else {
		$_POST["msg"] = "dados_invalidos";
		incluirLib("login",$config,$curso);
	    exit;
	}
} elseif ($opLogin == 'atualizar_cadastro') {
	//Se tiver cadastrando um novo usuário
	if ($_POST['acao'] == 'criar_novo') {
		unset($_SESSION["cliente_email2"], $_SESSION["cliente_senha2"]);
		$erros = array();
		$email_escape = addslashes(strtolower($_POST['email']));
		$documento = addslashes(str_replace(array(".", "-","/"),"",$_POST['documento']));

		$sql = "SELECT count(*) as total FROM pessoas WHERE email = '{$email_escape}' AND ativo = 'S' AND ativo_login = 'S'";
		$queryExisteEmail = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		$existeEmail = mysql_fetch_assoc($queryExisteEmail);

		if ($existeEmail['total'] > 0) {
			$erros[] = 'email_utilizado';
		}

		$sql = "SELECT count(*) as total FROM pessoas WHERE documento = '{$documento}' AND ativo = 'S' AND ativo_login = 'S'";
		$queryExisteCpf = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		$existeCpf = mysql_fetch_assoc($queryExisteCpf);

		if ($existeCpf['total'] > 0) {
			$erros[] = 'cpf_utilizado';
		}

		if (count($erros) > 0) {
			$curso['pessoa']["erros"] = $erros;
			incluirLib("login",$config,$curso);
		    exit;
		} else {
			/*if (count($curso['pessoa']["erros"]) == 0) {
				$localizei = localizei($_POST["documento"]);
				$localizei = array_map(trim,$localizei);

				if ($localizei['tipo_registro_1'] == '005' && $localizei['nome'] != 'NAO CONSTAM INFORMACOES') {
					$_POST['nome'] = $localizei['nome'];
				    $_POST['sexo'] = strtoupper($localizei['sexo']);

					$_POST['estado_civil'] = $estadocivil_localizei[$localizei['estado_civil']];

					if ($localizei['nascimento'] != '00000000') {
						$_POST['data_nasc'] = substr($localizei['nascimento'], 0, 4).'-'.substr($localizei['nascimento'], 4, 2).'-'.substr($localizei['nascimento'], 6, 2);
						$_POST['data_nasc'] = formataData($_POST['data_nasc'], 'br', 0);
					}

					if ($localizei['nacionalidade'] = 'brasileira') {
						$_POST['idpais'] = 33;
					}

					$_POST['naturalidade'] = $localizei['naturalidade'];

					//if ($localizei['numero_identidade'])
						//$_POST['rg'] = $localizei['numero_identidade'];

					$_POST['filiacao_mae'] = $localizei['nome_mae'];
					$_POST['filiacao_pai'] = $localizei['nome_pai'];

					if ($localizei['endereco_cep'] != '00000000') {
						$_POST['cep'] = $localizei['endereco_cep'];
						$_POST['cep'] = mascara(str_replace(array("-", ""),"",$_POST['cep']), "#####-###");
					}


					$core = new Core();
					if ($localizei['endereco_tipo_logradouro']) {
						$core->sql = "SELECT idlogradouro FROM logradouros WHERE nome = '".$localizei['endereco_tipo_logradouro']."'";
						$logradouro = $core->retornarLinha($core->sql);
						$_POST['idlogradouro'] = $logradouro['idlogradouro'];
					}

					$_POST['endereco'] = $localizei['endereco_logradouro'];
					$_POST['bairro'] = $localizei['endereco_bairro'];
					$_POST['numero'] = $localizei['endereco_numero'];
					$_POST['complemento'] = $localizei['endereco_complemento'];

					if ($localizei['endereco_estado']) {
						$core->sql = "SELECT idestado FROM estados WHERE sigla = '".$localizei['endereco_estado']."'";
						$estado = $core->retornarLinha($core->sql);
						$_POST['idestado'] = $estado['idestado'];

						if ($_POST['idestado'] && $localizei['endereco_cidade']) {
							$core->sql = "SELECT idcidade FROM cidades WHERE idestado = '".$_POST['idestado']."' AND nome = '".$localizei['endereco_cidade']."'";
							$cidade = $core->retornarLinha($core->sql);
							$_POST['idcidade'] = $cidade['idcidade'];
						}
					}

					if ($localizei['ddd'] && $localizei['telefone']) {
						$_POST['telefone'] = '('.$localizei['ddd'].') '.$localizei['telefone'];
					}
				}
			}*/
			$_SESSION['novaAcao'] = 'criar_novo';
			incluirLib('login', $config, $curso);
		    exit;
		}
	} else {
		if ($_POST['txt_usuario'] && $_POST["txt_senha"]) {
			$email_escape = addslashes(strtolower($_POST['txt_usuario']));
			$senha = senhaSegura($_POST["txt_senha"],$config["chaveLogin"]);
		} else {
			$email_escape = $_SESSION["cliente_email2"];
			$senha = $_SESSION["cliente_senha2"];
		}

		$sql = "SELECT * FROM pessoas WHERE email = '{$email_escape}' AND senha = '{$senha}' AND ativo = 'S' AND ativo_login = 'S'";
		$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		$total_documento = mysql_num_rows($querydocumento);

		if ($total_documento == 1) {
			$pessoa = mysql_fetch_assoc($querydocumento);
			$_SESSION['cliente_email2'] = $pessoa['email'];
			$_SESSION['cliente_senha2'] = $pessoa['senha'];

			$_SESSION['cliente_idpessoa'] = $pessoa['idpessoa'];
			$_SESSION['novaAcao'] = 'logar';

			$sobre_nome = explode(' ', $pessoa['nome']);
			$_POST['nome'] = $sobre_nome[0];
			$_POST['sobrenome'] = $sobre_nome[1];
			$_POST['email'] = $pessoa['email'];
			$_POST['documento'] = mascara($pessoa['documento'], '###.###.###-##');
			$_POST['sexo'] = $pessoa['sexo'];
			$_POST['estado_civil'] = $pessoa['estado_civil'];
			$_POST['data_nasc'] = formataData($pessoa['data_nasc'], 'br', 0);
			$_POST['nacionalidade'] = $pessoa['nacionalidade'];
			$_POST['naturalidade'] = $pessoa['naturalidade'];
			$_POST['rg'] = $pessoa['rg'];
			$_POST['rg_orgao_emissor'] = $pessoa['rg_orgao_emissor'];
			$_POST['rg_data_emissao'] = formataData($pessoa['rg_data_emissao'], 'br', 0);
			$_POST['rne'] = $pessoa['rne'];
            $_POST['cnh'] = $pessoa['cnh'];
            $_POST['categoria'] = $pessoa['categoria'];
            $_POST['data_primeira_habilitacao'] = $pessoa['data_primeira_habilitacao'];
            $_POST['cnh_data_emissao'] = $pessoa['cnh_data_emissao'];
            $_POST['data_validade'] = $pessoa['data_validade'];
			$_POST['profissao'] = $pessoa['profissao'];
			$_POST['telefone'] = $pessoa['telefone'];
			$_POST['celular'] = $pessoa['celular'];
			$_POST['filiacao_mae'] = $pessoa['filiacao_mae'];
			$_POST['filiacao_pai'] = $pessoa['filiacao_pai'];
			$_POST['cep'] = mascara($pessoa['cep'], '#####-###');
			$_POST['idestado'] = $pessoa['idestado'];
			$_POST['idcidade'] = $pessoa['idcidade'];
			$_POST['idlogradouro'] = $pessoa['idlogradouro'];
			$_POST['endereco'] = $pessoa['endereco'];
			$_POST['bairro'] = $pessoa['bairro'];
			$_POST['numero'] = $pessoa['numero'];
			$_POST['complemento'] = $pessoa['complemento'];

			incluirLib('login',$config,$curso);
		    exit;
		} elseif ($total_documento > 1) {
			$_POST['msg'] = 'user_duplicado';
			incluirLib('login',$config,$curso);
		    exit;
		} else {
			$_POST['msg'] = 'dados_invalidos';
			incluirLib('login',$config,$curso);
		    exit;
		}
	}
} else {
	if (!isset($_SESSION['cliente_email'])) {
		incluirLib('login',$config,$curso);
		exit;
	} else {
		$sql = "SELECT * FROM pessoas WHERE email = '".$_SESSION["cliente_email"]."' AND
			senha = '".$_SESSION["cliente_senha"]."' and ativo='S' and ativo_login = 'S'";
		$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
		$total_documento = mysql_num_rows($querydocumento);

		if ($total_documento != 1) {
			incluirLib("login",$config,$curso);
			exit;
		} else {
			$usuario = mysql_fetch_assoc($querydocumento);

			$sobre_nome = explode(' ', $usuario['nome']);
			$validar['nome'] = $sobre_nome[0];
			$validar['sobrenome'] = $sobre_nome[1];
			$validar['email'] = $usuario['email'];
			$validar['documento'] = mascara($usuario['documento'], '###.###.###-##');
			$validar['sexo'] = $usuario['sexo'];
			$validar['estado_civil'] = $usuario['estado_civil'];
			$validar['data_nasc'] = formataData($usuario['data_nasc'], 'br', 0);
			$validar['nacionalidade'] = $usuario['nacionalidade'];
			$validar['naturalidade'] = $usuario['naturalidade'];
			$validar['rg'] = $usuario['rg'];
			$validar['rg_orgao_emissor'] = $usuario['rg_orgao_emissor'];
			$validar['rg_data_emissao'] = formataData($usuario['rg_data_emissao'], 'br', 0);
			$validar['rne'] = $usuario['rne'];
			$validar['cnh'] = $usuario['cnh'];
			$validar['categoria'] = $usuario['categoria'];
			$validar['data_primeira_habilitacao'] = $usuario['data_primeira_habilitacao'];
			$validar['cnh_data_emissao'] = $usuario['cnh_data_emissao'];
			$validar['data_validade'] = $usuario['data_validade'];
			$validar['profissao'] = $usuario['profissao'];
			$validar['telefone'] = $usuario['telefone'];
			$validar['celular'] = $usuario['celular'];
			$validar['filiacao_mae'] = $usuario['filiacao_mae'];
			$validar['filiacao_pai'] = $usuario['filiacao_pai'];
			$validar['cep'] = mascara($usuario['cep'], '#####-###');
			$validar['idestado'] = $usuario['idestado'];
			$validar['idcidade'] = $usuario['idcidade'];
			$validar['idlogradouro'] = $usuario['idlogradouro'];
			$validar['endereco'] = $usuario['endereco'];
			$validar['bairro'] = $usuario['bairro'];
			$validar['numero'] = $usuario['numero'];
			$validar['complemento'] = $usuario['complemento'];

			require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/pessoas.class.php';

			$config['formulario'] = $config['formulario_pessoas'];
        	$verificarPessoaObj = new Pessoas;
		    $erros = $verificarPessoaObj->set('post', $validar)
		    	->set('config',$config)
		    	->validarCamposLoja();

		    if (! empty($erros)) {
		    	unset(
			  		$_SESSION['cliente_email'],
			  		$_SESSION['cliente_senha'],
				  	$_SESSION['cliente_idpessoa'],
				  	$_SESSION['cliente_nome'],
				  	$_SESSION['cliente_ultimoacesso'],
				  	$_SESSION['cliente_gestor'],
				  	$_SESSION['cliente_professor'],
				  	$_SESSION['ultimo_acesso_ava'],
				  	$_SESSION['cliente_avatar_servidor'],
			        $_SESSION['cliente_email2'],
			        $_SESSION['cliente_senha2']
				);

				$_POST['msg'] = 'dados_incompletos';
				incluirLib('login', $config, $produto);
				exit;
		    }

			if ($_SESSION["cliente_gestor"]) {
				$sql = "SELECT * FROM usuarios_adm WHERE idusuario = '".$_SESSION["cliente_gestor"]."' and
					ativo = 'S' and ativo_login = 'S'";
				$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
				$usuario["gestor"] = mysql_fetch_assoc($querydocumento);
			} elseif ($_SESSION["cliente_professor"]) {
				$sql = "SELECT * FROM professores WHERE idprofessor = '".$_SESSION["cliente_professor"]."' AND
					ativo = 'S' AND ativo_login = 'S'";
				$querydocumento = mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
				$usuario["professor"] = mysql_fetch_assoc($querydocumento);
			} else {
				//Só atualiza último view, se for o aluno que estiver acessando
				$sql = "update pessoas set ultimo_view = now() where idpessoa = '".$usuario["idpessoa"]."'";
				mysql_query($sql) or die(incluirLib("erro",$config,array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
			}
		}
	}
}