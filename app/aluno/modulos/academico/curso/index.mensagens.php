<?php
require '../classes/avas.mensagem_instantanea_novo.class.php';

/*$sql = "DELETE FROM mensagens_alerta WHERE tipo_alerta = 'tiraduvidas' AND idmatricula = ".$url[3];
mysql_query($sql);*/

$mensagemObj = new MensagemInstantanea();
$mensagemObj->set('idava', $ava['idava'])
    ->set('idmatricula', $matricula['idmatricula'])
    ->set('idpessoa', $usuario['idpessoa'])
    ->set('modulo', $url[0]);

if (isset($url[6])) {
    if ($_POST['acao'] == 'atualizaConversas') {
        //Retorna as conversas de uma mensagem instantânea da pessoa
        $mensagemObj->set('idmensagem_instantanea', $_POST['idmensagem_instantanea']);
        $mensagemObj->set('ultimaIdMensagem', $_POST['ultimaIdMensagem']);
        $conversasMensagem = $mensagemObj->ListarConversasMensagemInstantanea();
        echo json_encode($conversasMensagem);
        exit;
    } elseif ($_POST['acao'] == 'enviar_mensagem_instantanea') {
        $mensagemObj->set('post', $_POST);
        $salvar = $mensagemObj->salvarNovaConversa();

        if ($salvar['erro']) {
            require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.conversa.php';
            foreach ($salvar['erros'] as $ind => $var) {
                $salvar['erros'][$ind] = $idioma[$var];
            }
        }

        $_SESSION['idmensagem_instantanea_conversa'] = $salvar['idmensagem_instantanea_conversa'];
        $matriculaObj->set('id', (int) $idmatricula);
        $matriculaObj->cadastrarHistorioAluno($url[4], 'cadastrou', 'mensagem_instantanea', $salvar['idmensagem_instantanea_conversa']);
        $mensagemObj->contabilizarTiraDuvida($matricula['idmatricula'], $url[4], $_POST["idmensagem_instantanea"]);

        echo json_encode($salvar);
        exit();
    } elseif ($_POST["acao"] == "adicionar_pessoa") {
        $mensagemObj->set("post", $_POST);
        $mensagemObj->set("idmensagem_instantanea", (int) $url[6]);
        $salvar = $mensagemObj->adicionarUsuarioMensagem();

        if ($salvar["sucesso"]) {
            $mensagemObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/' . $url[5] . '/' . $url[6]);
            $mensagemObj->Processando();
            exit;
        }
    } elseif ($url[6] == 'cadastrar') {
        require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.formulario.php';
        require 'telas/' . $config['tela_padrao'] . '/mensagens.formulario.php';
        exit;
    } elseif ($url[7] == 'adicionar') {
        require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.adicionar.pessoa.php';
        require 'telas/' . $config['tela_padrao'] . '/mensagens.adicionar.pessoa.php';
        exit;
    } elseif ($url[7] == 'download' && (int) $url[8]) {
        $download = $mensagemObj->RetornarArquivoConversa((int) $url[8]);
        require 'telas/' . $config['tela_padrao'] . '/mensagens.conversa.download.php';
    } elseif ($url[7] == 'enviar_arquivo_instantanea') {
        $mensagemObj->set('post', $_POST);
        $salvar = $mensagemObj->enviarArquivoMensagem(isset($_REQUEST['chunk']) ? intval($_REQUEST['chunk']) : 0, isset($_REQUEST['chunks']) ? intval($_REQUEST['chunks']) : 0, isset($_REQUEST['name']) ? $_REQUEST['name'] : '', $_SESSION['idmensagem_instantanea_conversa']);

        if ($salvar['erro']) {
            require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.conversa.php';
            foreach ($salvar['erros'] as $ind => $var) {
                $salvar['erros'][$ind] = $idioma[$var];
            }
        }

        echo json_encode($salvar);
        exit();
    } else {
        //Retorna as conversas de uma mensagem instantânea da pessoa
        $mensagemObj->set('idmensagem_instantanea', $url[6]);
        $mensagens = $mensagemObj->ListarConversasMensagemInstantanea();
        $integrantes = $mensagemObj->retornarIntegrantes($url[6], true);
        $matriculaObj->cadastrarHistorioAluno($url[4], 'visualizou', 'mensagem_instantanea', $url[6]);

        require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.conversa.php';
        require 'telas/' . $config['tela_padrao'] . '/mensagens.conversa.php';
        exit;
    }
} elseif ($_POST['acao'] == 'iniciar_chat') {
    $mensagemObj->set("post", $_POST);
    $salvar = $mensagemObj->iniciarConversa();

    if ($salvar["sucesso"]) {
        $mensagemObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $matricula['idmatricula'] . '/' . $url[4] . '/' . $url[5] . '/' . $salvar["idmensagem_instantanea"]);
        $mensagemObj->Processando();
        exit;
    }
} else {
    //Retorna as mensagens instantâneas que o aluno participa e os integrantes
    $mensagens = $mensagemObj->ListarMensagensInstantaneasPessoa();
    //$matriculaObj->cadastrarHistorioAluno($url[4], 'visualizou', 'mensagem_instantanea');
    //print_r2($mensagens,true);

    require 'idiomas/' . $config['idioma_padrao'] . '/mensagens.php';
    require 'telas/' . $config['tela_padrao'] . '/mensagens.php';
    exit;
}
