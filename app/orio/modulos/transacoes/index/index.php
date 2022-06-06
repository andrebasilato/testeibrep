<?php

require 'config.php';
require 'config.listagem.php';
require $_SERVER['DOCUMENT_ROOT'] . '/classes/orio/Transacoes.php';
require 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

$transacoesObj = new Transacoes();
$transacoesObj->set('idusuario', $usuario['idusuario']);

##### AÇÕES
$acao = null;
if (! empty($_POST['acao'])) {
    $acao = $_POST['acao'];
}

switch ($acao) {
    case 'reprocessar':
        $transacoesObj->set('id', (int) $_POST['idtransacao']);
        $transacoesObj->set('campos', 't.*, ct.idtransacao as idtransacaoGerada');
        $linha = $transacoesObj->retornar();
        if ('E' == $linha['tipo']) {
            $soapUrl = $config['urlSistema'] . '/api/orio/' . $orio_interfaces[$linha['idinterface']]['slug'];

            $server = json_decode($linha['_server']);
            $serverEmail = $server->HTTP_EMAIL;
            $serverSenha = $server->HTTP_SENHA;
            $headers = array(
                "email:$serverEmail",
                "senha:$serverSenha",
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, (!empty($_POST['json_alterar'])) ? json_decode($_POST['json_alterar']) : $_POST['xml_alterar']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            curl_close($ch);

            $transacoesObj->alterarSituacao($_POST['idtransacao'], 4);

            header('Location: /' . $url[0] . '/' . $url[1]);
            exit;
        }

        $reprocessou = $transacoesObj->set('post', $_POST)
            ->reprocessar((int) $_POST['idtransacao']);

        if ($reprocessou['sucesso']) {
            // $redirecionamentoObj->set('pro_mensagem_idioma', 'reprocessar_sucesso');
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
            exit;
        } elseif ($reprocessou['erro']) {
            // $redirecionamentoObj->set('pro_mensagem_idioma_erros', $reprocessou['erros']);
            header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
            exit;
        }

        $queryString = null;
        if (! empty($_SERVER['QUERY_STRING'])) {
            $queryStringArray = explode('&', $_SERVER['QUERY_STRING']);
            $queryStringArray = array_filter($queryStringArray);

            foreach ($queryStringArray as $ind => $var) {
                if (substr($var, 0, 3) == 'tk=') {
                    unset($queryStringArray[$ind]);
                }
            }

            if ($queryStringArray) {
                $queryString = '?' . implode('&', $queryStringArray);
            }
        }

        header('Location: /' . $url[0] . '/' . $url[1] . $queryString);
        break;
}

##### TELAS GERAIS
$url3 = null;
if (! empty($url[3])) {
    $url3 = $url[3];
}

##### LISTAGEM
if (empty($url3)) {

    $ordem = (! empty($_GET['ord'])) ? $_GET['ord'] : 'desc';
    $quantidade = (! empty($_GET['qtd'])) ? $_GET['qtd'] : 30;
    $ordemCampo = (! empty($_GET['cmp'])) ? $_GET['cmp'] : $config['banco']['primaria'];
    $pagina = (! empty($_GET['pag'])) ? $_GET['pag'] : 1;

    $transacoesObj->set('ordem', $ordem);
    $transacoesObj->set('ordem_campo', $ordemCampo);
    $transacoesObj->set('pagina', $pagina);
    $transacoesObj->set('limite', $quantidade);
    $transacoesObj->set('campos', '*');
    $dadosArray = $transacoesObj->listar();

    require 'idiomas/' . $config['idioma_padrao'] . '/index.php';
    require 'telas/' . $config['tela_padrao'] . '/index.php';
    exit;
}

$transacoesObj->set('id', (int) $url[3]);
$transacoesObj->set('campos', 't.*, ct.idtransacao as idtransacaoGerada');
$linha = $transacoesObj->retornar();

if (! $linha) {
    header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
    exit;
}

##### TELAS INTERNAS
switch ($url[4]) {
    case 'informacoes':
        require 'idiomas/' . $config['idioma_padrao'] . '/informacoes.php';
        require 'telas/' . $config['tela_padrao'] . '/informacoes.php';
        break;
    default:
        header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        exit;
}
