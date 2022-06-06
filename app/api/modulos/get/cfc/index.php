<?php
$linhaObj = new Escolas();

$json = array();

if (isset($url[3]) && $url[3] <> "") {
    $linhaObj->Set('id', intval($url[3]));
    $linhaObj->Set('campos', 'p.*, i.nome_abreviado AS sindicato, e.nome AS estado, e.sigla AS uf, c.nome AS cidade');
    $jsonAux[] = $linhaObj->retornar(true);
} else {
    $linhaObj->Set('pagina', 1 );
    $linhaObj->Set('ordem', 'asc');
    $linhaObj->Set('limite', -1);
    $linhaObj->Set('ordem_campo', 'nome_fantasia');
    $linhaObj->Set('campos', 'p.*, i.nome_abreviado AS sindicato, e.nome AS estado, e.sigla AS uf, c.nome AS cidade');
    $jsonAux = $linhaObj->listarTodas(true);
}

foreach ($jsonAux as $ind => $escola) {
    $json[$ind] = array(
        'idescola' => $escola['idescola'],
        'nome_fantasia' => $escola['nome_fantasia'],
        'slug' => $escola['slug'],
        'email' => $escola['email'],
        'telefone' => $escola['telefone'],
        'cep' => $escola['cep'],
        'idlogradouro' => $escola['idlogradouro'],
        'endereco' => $escola['endereco'],
        'bairro' => $escola['bairro'],
        'numero' => $escola['numero'],
        'complemento' => $escola['complemento'],
        'idestado' => $escola['idestado'],
        'idcidade' => $escola['idcidade'],
        'estado' => $escola['estado'],
        'uf' => $escola['uf'],
        'cidade' => $escola['cidade'],
        'informacoes' => $escola['informacoes'],
        'pagseguro' => $escola['pagseguro'],
        'logo' => '/api/get/imagens/escolas_avatar/x/x/' . $escola['avatar_servidor'],
        'estados_cidades' => $escola['estados_cidades']
    );
    
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=UTF8');
echo json_encode($json);
