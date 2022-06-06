<?php	
require 'idiomas/'.$config['idioma_padrao'].'/index.php';

if ($_POST['email'] && !empty($_POST['email']) && $_POST["opLogin"] == "esqueciMinhaSenha") {
	$coreObj = new Core();
		
	$sql = "SELECT idpessoa, email, nome FROM pessoas WHERE email = '".$_POST['email']."' and ativo = 'S' LIMIT 1";
	$pessoa = $coreObj->retornarLinha($sql);
	if ($pessoa["idpessoa"]) {
		$hash = md5(uniqid());
		
        $sql = "UPDATE solicitacoes_senhas SET ativo = 'N' WHERE id = " . $pessoa["idpessoa"] . " AND modulo = 'aluno'";
		$coreObj->executaSql($sql);
		
        $sql = "INSERT INTO solicitacoes_senhas SET id = " . $pessoa["idpessoa"] . ", modulo = 'aluno', data_cad = now(), hash = '".$hash."'";
		$coreObj->executaSql($sql);	

        $nomeDe = utf8_decode($config['tituloEmpresa']);
        $emailDe = $config['emailEsqueci'];
        $nomePara = utf8_decode($pessoa['nome']);
        $emailPara  = $pessoa['email'];
        $assunto  = utf8_decode($idioma['assunto']);
								   
		$message  = "Ol&aacute; <strong>" . $nomePara . "</strong>,
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
					 
		$coreObj->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout');

        $coreObj->set('pro_mensagem_idioma', 'recuperar_senha_enviado_sucesso');
        $coreObj->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3]);
        $coreObj->processando();
	} else {
        $coreObj->set('pro_mensagem_idioma', 'email_nao_encontrado');
        $coreObj->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3]);
        $coreObj->processando();
	}
}

if ($url[4] == 'json') {
	$coreObj = new Core();
    if ($_REQUEST['idestado']) {
        $coreObj->RetornarJSON(
            'cidades',
            mysql_real_escape_string($_REQUEST['idestado']),
            'idestado',
            'idcidade, nome',
            'ORDER BY nome'
        );
    }

    exit;
}

if ($_SESSION["novaAcao"] == 'criar_novo' || $_SESSION["novaAcao"] == 'logar') {
	require_once '../classes/pessoas.class.php';
	$pessoaObj = new Pessoas;
	$pessoaObj->set('modulo', $url[0]);
	$pessoaObj->Set('nao_monitara',true);

	//Retorna os logradouros
	$logradouros = $pessoaObj->retornarLogradouros();

	//Retorna os estados
	$estados = $pessoaObj->retornarEstados();

	//Vai para segunda etapa para atualizar os dados
	require 'idiomas/'.$config['idioma_padrao'].'/atualizar_dados.php';
	require 'telas/'.$config["tela_padrao"].'/atualizar_dados.php';
	exit();
} else {
	require 'telas/'.$config["tela_padrao"].'/index.php';
	exit();
}
