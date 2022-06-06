<?php
$url = strip_tags(rawurldecode($_SERVER["REQUEST_URI"])); //Salva a url do browser na variavel $url
$get_array = explode("?", $url); // Separando os GETS
$url = explode("/", $get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);

for ($i = 0; $i <= $qtdUrl; $i++) {
    if (isset($url[0]) && $url[0] != 'orio') {
        array_shift($url); // O primeiro índice sempre será vazio
    }
}

for ($i = 0; $i <= 9; $i++) {
    if (!isset($url[$i])) {
        $url[$i] = null;
    }
}

// Correção do BUG ativo e não ativo. (Manzano) ARMENGUE!!!
// Somente para facilitar o uso
if($_POST) {
  if(!$_POST["ativo_painel"]) $_POST["ativo_painel"] = "S";
  if(!$_POST["ativo_numeros"]) $_POST["ativo_numeros"] = "S";
}
$config['tituloPainel'] = 'Painel de integrações';
$config['tabela_monitoramento'] = 'orio_monitora_adm';
$config['tabela_monitoramento_primaria'] = 'idusuario';
$config['tabela_monitoramento_log'] = 'orio_monitora_adm_log';

$orio_interfaces_label['pt_br'] = array();
$orio_interfaces_descricoes['pt_br'] = array();

foreach ($orio_interfaces as $id => $dados) {
    $orio_interfaces_label['pt_br'][$id] = $dados["nome"];
    $orio_interfaces_descricoes['pt_br'][$id] = $dados["descricao"];
}