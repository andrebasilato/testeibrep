<?php
ini_set('display_errors', 'On');

include 'error.php';
include 'config.php';
include 'classe.class.php';

set_error_handler('throwError');

/** @var Relatorios */
$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario", $usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', 1);

if ('cidades' == Request::url(4)) {
    $query = 'SELECT nome, idcidade FROM cidades WHERE
                idestado = '.Request::post('id').'
                    ORDER BY nome ASC';

    $queryStmt = mysql_query($query);

    $_cidades = array();
    while ($row = mysql_fetch_assoc($queryStmt)) {
        $_cidades[] = (object) array(
            'nome'     => $row['nome'],
            'id'       => $row['idcidade'],
            'idcidade' => $row['idcidade'],
        );
    }

    exit( json_encode($_cidades) );
}


if ('salvar_relatorio' == Request::post('acao')) {

    $salvar = $relatoriosObj->set('post', $_POST)
        ->salvarRelatorio();

    if($salvar['sucesso']){
        $mensagem_sucesso = "salvar_relatorio_sucesso";
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}


if ('html' == $url[3] || 'xls' == $url[3]) {
    $relatorioObj->set('pagina', 1)
        ->set('ordem', 'desc')
        ->set('limite', -1)
        ->set('ordem_campo', 'vv.idvisita')
        ->set(
            'campos',
            'vv.*, pe.idpessoa, pe.nome as nome_pessoa,
            pe.documento as documento_pessoa, pe.data_nasc as nasc_pessoa,
            pe.email as email_pessoa,pe.telefone as telefone_pessoa,
            lov.nome as local, miv.nome as midia, cu.nome as curso,
            ve.nome as vendedor, vv.data_cad'
        );

    $dadosArray = $relatorioObj->gerarRelatorio();
}

switch ($url[3]) {
    case 'html':
        $relatoriosObj->atualiza_visualizacao_relatorio();
        include('idiomas/'.$config['idioma_padrao'].'/html.php');
        include('telas/'.$config['tela_padrao'].'/html.php');
        break;
    case 'xls':
        include('idiomas/'.$config['idioma_padrao'].'/xls.php');
        include('telas/'.$config['tela_padrao'].'/xls.php');
        break;
    default:
        include('idiomas/'.$config['idioma_padrao'].'/index.php');
        include('telas/'.$config['tela_padrao'].'/index.php');
}