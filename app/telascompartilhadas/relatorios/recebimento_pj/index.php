<?php

require 'config.php';
require 'classe.class.php';

require '../classes/relatorios.class.php';
$relatoriosObj = new Relatorios();
$relatorioObj = new Relatorio();
$relatorioObj->Set('monitora_onde', 1);

switch ($url[0]) {
    case 'cfc':
        $relatoriosObj->set('idescola', $usuario['idescola']);
        $relatorioObj->set('idescola', $usuario['idescola']);
        break;
    default:
        $relatoriosObj->set('idusuario', $usuario['idusuario']);
        $relatorioObj->set('idusuario', $usuario['idusuario']);
        break;
}

require 'config.permissoes.php';

if (! $permissoes['visualizar']) {
    incluirLib('sempermissao_modulos', $config, null);
    exit;
}

if ($_POST['acao'] == 'salvar_relatorio') {     
    $relatoriosObj->Set('post', $_POST);        
    $salvar = $relatoriosObj->salvarRelatorio();

    if ($salvar['sucesso']){
        $mensagem_sucesso = 'salvar_relatorio_sucesso';
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}

if ($url[3] == 'html' || $url[3] == 'xls') {
    $dadosArray = $relatorioObj->gerarRelatorio();

    if (! empty($dadosArray['erro'])) {
        $erros = $dadosArray;
        unset($dadosArray);
    }
}

switch ($url[3]) {
    case 'buscar_conta':
        echo $relatorioObj->buscarConta();
        exit;
    case 'html':
        $relatoriosObj->atualiza_visualizacao_relatorio();

        require 'idiomas/' . $config['idioma_padrao'] . '/html.php';
        require 'telas/' . $config['tela_padrao'] . '/html.php';
        break;
    case 'xls':
        require 'telas/' . $config['tela_padrao'] . '/xls.php';
        break;          
    default:
        require 'idiomas/' . $config['idioma_padrao'] . '/index.php';
        require 'telas/' . $config['tela_padrao'] . '/index.php';
}
