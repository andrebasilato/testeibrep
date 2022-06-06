<?php
ini_set('max_execution_time', 0);
set_time_limit(0);

require_once 'acesso.php';
require_once '../includes/config.php';
require_once '../includes/funcoes.php';

$ignorar = ["index.php", ".", "..", "acesso.php", "CBO2002 - Ocupacao.csv"];

if (!in_array($ipUpdate, $ipsLiberados)) {
    echo "Acesso não permitido...<br>";
    echo "<b>Seu IP: " . $ipUpdate . "</b><br>";
    exit();
}

$caminho = $_SERVER["DOCUMENT_ROOT"] . "/update";
if ($_GET["exe"]) {
    if (!in_array($_GET["exe"] . ".php", $ignorar)) {
        echo "<b>Executando: " . $_GET["exe"];
        echo "<br> =================================== </b><br>";
        include($caminho . "/" . $_GET["exe"] . ".php");
        echo "<br> <b>=================================== </b><br>";
        echo "<br><br>";
    }
}

if ($_GET["rem"]) {
    if (!in_array($_GET["rem"] . ".php", $ignorar)) {
        echo "<b>Removendo: " . $_GET["rem"];
        echo "<br> =================================== </b><br>";
        unlink($caminho . "/" . $_GET["rem"] . ".php");
        echo "Removido: " . $_GET["rem"];
        echo "<br> =================================== </b><br>";
        echo "<br><br>";
    }
}

$arquivos = scandir($caminho, 1);
echo "<b>Seu IP: " . $ipUpdate . "</b><br>";
echo "<b>Versão atual do Oráculo: " . $config["oraculo_versao"] . "</b><br>";

foreach ($arquivos as $ind => $arquivo) {
    if (!in_array($arquivo, $ignorar)) {
        $arquivo = str_replace(".php", "", $arquivo);
        echo "Arquivo <b>$arquivo</b>: <a href='?exe=$arquivo'>Executar</a> / <a href='?rem=$arquivo'>Remover</a> <br>";
    }
}
