<?php
if ($_SESSION['matricula']['possui_financeiro'] == 'N') {
    unset($_SESSION['matricula']['idvendedor']);
    $_SESSION['matricula']['idvendedor'] = $_POST['idvendedor'];
} else {
    unset($_SESSION['matricula']['financeiro']);
    $_SESSION['matricula']['financeiro'] = $_POST;
}
require 'novamatricula.seguranca.php';
include '../classes/pessoas.class.php';
$pessoaObj = new Pessoas();
$config['formulario'] = $config['formulario_pessoas'];
$config['banco'] = $config['banco_pessoas'];
$pessoaObj->Set('config', $config);
$pessoaObj->Set('idusuario', $usuario['idusuario']);
$pessoaObj->Set('monitora_onde', $config['monitoramento_pessoa']['onde']);
unset($config['formulario_pessoas'][0]['campos'][2]);
unset($config['formulario_pessoas'][0]['campos'][3]);
unset($config['formulario_pessoas'][0]['campos'][4]);
unset($config['formulario_pessoas'][0]['campos'][5]);
unset($config['formulario_pessoas'][0]['campos'][6]);
unset($config['formulario_pessoas'][0]['campos'][7]);
unset($config['formulario_pessoas'][0]['campos'][8]);
unset($config['formulario_pessoas'][0]['campos'][15]);
unset($config['formulario_pessoas'][1]['campos'][3]);
unset($config['formulario_pessoas'][1]['campos'][4]);
unset($config['formulario_pessoas'][1]['campos'][5]);
unset($config['formulario_pessoas'][1]['campos'][6]);
unset($config['formulario_pessoas'][1]['campos'][7]);
unset($config['formulario_pessoas'][0]['campos'][15]);
if (!$_SESSION['matricula']['pessoa']['idpessoa']) {
    $_SESSION['matricula']['pessoa']['senha'] = str_replace(array('.', '-', '/'), '', $_SESSION['matricula']['pessoa']['documento']);
}
$_SESSION['matricula']['pessoa']['documento_tipo'] = 'cpf';
$pessoaObj->Set('post', $_SESSION['matricula']['pessoa']);
if ($_SESSION['matricula']['pessoa']['idpessoa']) {
    $pessoa = $pessoaObj->Modificar();
} else {
    $config['formulario_pessoas'][1]['campos'][] =
  array(
        'id' => 'form_senha',
        'nome' => 'senha',
        'tipo' => 'input',
        'valor' => 'senha',
        'banco' => true,
        'banco_php' => 'return senhaSegura("%s","'.$config['chaveLogin'].'")',
        'banco_string' => true,
     );
    $config['formulario'] = $config['formulario_pessoas'];
    $pessoaObj->Set('config', $config);
    $pessoa = $pessoaObj->Cadastrar();
}
if ($pessoa['sucesso'] && $pessoa['id']) {
    $_SESSION['matricula']['idpessoa'] = $pessoa['id'];
    $matriculaObj = new Matriculas();
    $matriculaObj->Set('idusuario', $usuario['idusuario']);
    $matriculaObj->Set('modulo', $url[0]);
    $matriculaObj->Set('post', $_SESSION['matricula']);
    $matricula = $matriculaObj->Cadastrar();
    if ($matricula['visita_erros']) {
        $alerta_visita = $matricula['visita_erros'];
    /*include("idiomas/".$config["idioma_padrao"]."/novamatricula.erro.php");
    include("telas/".$config["tela_padrao"]."/novamatricula.erro.php");
    exit;*/
    }
    unset($_SESSION['matricula']);
    include 'idiomas/'.$config['idioma_padrao'].'/novamatricula.finalizar.php';
    include 'telas/'.$config['tela_padrao'].'/novamatricula.finalizar.php';
} else {
    $erro[] = 'pessoa_erro';
    include 'idiomas/'.$config['idioma_padrao'].'/novamatricula.erro.php';
    include 'telas/'.$config['tela_padrao'].'/novamatricula.erro.php';
}
