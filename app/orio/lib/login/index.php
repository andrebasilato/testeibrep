<?php
$painel = $url[0];
$painelNome = 'Painel de Integrações';
$painelCor = '#0ead69';
$temCadastro = false;

if ($url[1] == 'esqueci') {
    include "idiomas/" . $config["idioma_padrao"] . "/esqueci.php";

    function Processando($mensagem_idioma, $url)
    {
        $informacoes["msg"] = $mensagem_idioma;
        $informacoes["url"] = $url;
        incluirLib("processando", $GLOBALS['config'], $informacoes);
        exit();
    }

    if ($_POST['email'] and !empty($_POST['email']) and $_POST["opLogin"] = "esqueciMinhaSenha") {
        $coreObj = new Core();

        $sql = "SELECT idusuario, email, nome FROM usuarios_adm WHERE email = '" . mysql_real_escape_string($_POST['email']) . "' and ativo = 'S' LIMIT 1";
        $usuario = $coreObj->retornarLinha($sql);
        if ($usuario["idusuario"]) {

            $hash = md5(uniqid());

            $sql = "update solicitacoes_senhas set ativo = 'N' where id = " . $usuario["idusuario"] . " and modulo = 'gestor'";
            $coreObj->executaSql($sql);

            $sql = "insert into solicitacoes_senhas set id = " . $usuario["idusuario"] . ", modulo = 'gestor', data_cad = now(),  hash = '" . $hash . "'";
            $coreObj->executaSql($sql);

            //$nomePara = utf8_decode($usuario["nome"]);
            $nomePara = utf8_decode($usuario["nome"]);
            $message = "Ol&aacute; <strong>" . $usuario["nome"] . "</strong>,
				  <br><br>
				  Voc&ecirc; solicitou o envio de uma nova senha de acesso.
				  <br><br>
				  Clique no link abaixo e modifique voc&ecirc; mesmo a sua senha.
				  <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/novasenha/configuracoes/meusdados/gestor/" . $usuario["idusuario"] . "/" . $hash . "\">" . $config["urlSistema"] . "/novasenha/configuracoes/meusdados/gestor/" . $usuario["idusuario"] . "/" . $hash . "</a>
				  <br /><br />
				  O link estar&aacute; dispon&iacute;vel durante as pr&oacute;ximas 6 horas.<br />
				  Caso o link j&aacute; tenha expirado solicite novamente <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/gestor/esqueci\">clicando aqui</a>.
				  <br /><br />
				  Caso n&atilde;o tenha solicitado uma nova senha, gentileza desconsiderar esse e-mail.
				  <br />";

            $emailPara = $usuario["email"];
            $assunto = utf8_decode($idioma["assunto"]);

            $nomeDe = utf8_decode($config["tituloEmpresa"]);
            //$emailDe = $GLOBALS["config"]["emailSistema"];
            $emailDe = $config["emailEsqueci"];

            $coreObj->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, "layout");

            Processando("mensagem_sucesso", "/" . $url[0] . "/" . $url[1] . "");
        } else {
            Processando("usuario_nao_encontrado", "/" . $url[0] . "/" . $url[1] . "?erro");
        }
    }

    include "telas/" . $config["tela_padrao"] . "/esqueci.php";
    exit();
} else {
    include "idiomas/" . $config["idioma_padrao"] . "/index.php";
    include "telas/" . $config["tela_padrao"] . "/index.php";
    exit();
}
