<?php
require_once '../classes/pessoas.class.php';

$pessoa = new Pessoas();

if ($_FILES) {
    foreach($_FILES as $ind => $val) {
      $_POST[$ind] = $val;
    }
}
$matricula['inadimplente'] = $matriculaObj->inadimplente();
$matricula["diploma"] = $matriculaObj->hasDiploma(Request::url(4));
$matricula["pessoa"] = $matriculaObj->RetornarPessoa();
$matricula['escola'] = $matriculaObj->RetornarEscola();
$matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
$matricula['acesso_simultaneo'] = $matriculaObj->retornarAcessoCursoSimultaneo($matricula['idmatricula'], $matricula['idcurso']);
$acessoCursoNaoSimultaneo = $matriculaObj->retornarAcessoCursoNaoSimultaneo($matricula["pessoa"]["idpessoa"]);
$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
if ($matricula['oferta_curso']['idfolha']) {
    $matricula["alunoAprovadoNotas"] = $matriculaObj->verificaMatriculaAprovadaNotas($matricula['oferta_curso']['porcentagem_minima_disciplinas']);
    $matricula["alunoAprovadoNotasDias"] = $matriculaObj->verificaMatriculaAprovadaNotasDias($matricula['idmatricula'],$matricula['idoferta'], $matricula['idcurso']);
}

$curso_integrado = in_array(
    (int)$matricula['idcurso'],
    array_keys($GLOBALS['detran_tipo_aula'][$matricula['escola']['uf']])
);

unset($pessoa);
$detranObj = new Detran();

$ipPermitidos = array(
    '191.52.252.177',
    '191.52.252.178',
    '191.52.252.179',
    '191.52.252.180',
    '127.0.0.1',
    '::1'
);
$enderecoIp =  $_SERVER['REMOTE_ADDR'];
$vpn_alfama = in_array($enderecoIp, $ipPermitidos);

if($detranObj->obterSituacaoIntegracao((int)$GLOBALS['matricula']['escola']['idestado'])) {
    $function1 = "\$dados = \$detranObj->DadosCertificado{$GLOBALS['matricula']['escola']['uf']}({$GLOBALS['matricula']['idmatricula']});";
    if (method_exists($detranObj, "DadosCertificado{$GLOBALS['matricula']['escola']['uf']}")) {
        eval($function1);
        if (count($dados) == 1)
            $matricula['detran']['exibir_botao_certificado'] = ($dados[0]['detran_certificado'] == 'N') ? 'enviar' : 'reenviar';

    }

    $function2 = "\$dados2 = \$detranObj->DadosCredito{$GLOBALS['matricula']['escola']['uf']}({$GLOBALS['matricula']['idmatricula']});";
    if (method_exists($detranObj, "DadosCredito{$GLOBALS['matricula']['escola']['uf']}")) {
        eval($function2);
        if (count($dados2) == 1)
            $matricula['detran']['exibir_botao_credito'] = ($dados2[0]['detran_creditos'] == 'N') ? 'enviar' : 'reenviar';
    }

    $function3 = "\$dados3 = \$detranObj->DadosCancelamento{$GLOBALS['matricula']['escola']['uf']}({$GLOBALS['matricula']['idmatricula']});";
    if (method_exists($detranObj, "DadosCancelamento{$GLOBALS['matricula']['escola']['uf']}")) {
        eval($function3);
        if (count($dados3) == 1)
            $matricula['detran']['exibir_botao_cancelamento'] = 'enviar';
    }
}

if($url[5]) {

	$arquivo_controller = $_SERVER["DOCUMENT_ROOT"]."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/administrar.".$url[5].".php";
	if (file_exists($arquivo_controller)) {
		include($arquivo_controller);
	} else {
		echo "Opção não encontrada";
	}

} else {
	include("administrar.index.php");
}
