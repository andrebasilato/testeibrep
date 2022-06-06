<?php
require 'idiomas/'.$config['idioma_padrao'].'/index.php';

if($_POST['email'] && !empty($_POST['email']) && $_POST["opLogin"] = "esqueciMinhaSenha") {
	// Classe PHPMailer (e-mail)
	require '../classes/PHPMailer/PHPMailerAutoload.php';

	$coreObj = new Core();

	$sql = "SELECT idpessoa, email, nome FROM pessoas WHERE email = '".mysql_real_escape_string($_POST['email'])."' and ativo = 'S' LIMIT 1";
	$pessoa = $coreObj->retornarLinha($sql);
	if($pessoa["idpessoa"]){

		$hash = md5(uniqid());

		$sql = "update solicitacoes_senhas set ativo = 'N' where id = ".$pessoa["idpessoa"]." and modulo = 'aluno'";
		$coreObj->executaSql($sql);

		$sql = "insert into solicitacoes_senhas set id = ".$pessoa["idpessoa"].", modulo = 'aluno', data_cad = now(),  hash = '".$hash."'";
		$coreObj->executaSql($sql);

		$nomePara = $pessoa["nome"];
		$message  = "Ol&aacute; <strong>".$pessoa["nome"]."</strong>,
				  <br><br>
				  Voc&ecirc; solicitou o envio de uma nova senha de acesso.
				  <br><br>
				  Clique no link abaixo e modifique voc&ecirc; mesmo a sua senha.
				  <a href=\"http://".$_SERVER["SERVER_NAME"]."/novasenha/configuracoes/meusdados/aluno/".$pessoa["idpessoa"]."/".$hash."\">".$config["urlSistema"]."/novasenha/configuracoes/meusdados/aluno/".$pessoa["idpessoa"]."/".$hash."</a>
				  <br /><br />
				  O link estar&aacute; dispon&iacute;vel durante as pr&oacute;ximas 6 horas.<br />
				  Caso o link j&aacute; tenha expirado solicite novamente <a href=\"http://".$_SERVER["SERVER_NAME"]."/aluno\">clicando aqui</a>.
				  <br /><br />
				  Caso n&atilde;o tenha solicitado uma nova senha, gentileza desconsiderar esse e-mail.
				  <br />";

		$emailPara  = $pessoa["email"];
		$assunto  =  utf8_decode($idioma['assunto']);

		$nomeDe = utf8_decode($config["tituloEmpresa"]);
		$emailDe = $config["emailEsqueci"];

		$coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");

		$_POST['msg'] = 'recuperar_senha_enviado_sucesso';
	} else {
		$_POST['msg'] = 'email_nao_encontrado';
	}
}

require 'telas/'.$config["tela_padrao"].'/index.php';
exit();
