<?php
require_once("../classes/escolas.class.php");
include("config.php");

$linhaObj = new Escolas();

$linhaObj->Set("idescola", $usuario["idescola"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);

if ($_POST["acao"] == "salvar") {
    if ($_FILES) {
        foreach ($_FILES as $ind => $val) {
            $_POST[$ind] = $val;
        }
    }

    if ($_POST["acao_url"]) {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "?" . base64_decode($_POST["acao_url"]);
    } else {
        $url_redireciona = "/" . $url[0] . "/" . $url[1] . "/" . $url[2];
    }

    $linhaObj->Set("id", $usuario["idescola"]);
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->alterarMeusdados();

    if ($salvar["sucesso"]) {
        $_POST["msg"] = "modificar_sucesso";
    }
}

if (isset($usuario["idescola"])) {
    $linhaObj->Set("id", (int)$usuario["idescola"]);
    $linhaObj->Set("campos", "p.*");
    $linha = $linhaObj->Retornar();

    if (isset($url[3])) {
        switch ($url[3]) {
            case "download":
                include("telas/" . $config["tela_padrao"] . "/download.php");
                break;
            case "excluir":
                include("idiomas/" . $config["idioma_padrao"] . "/excluir.arquivo.php");
                $linhaObj->RemoverArquivo('escolas', 'avatar', $linha, $idioma);
                break;
            default:
                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2] . "");
                exit();
        }
    } else {
        include("idiomas/" . $config["idioma_padrao"] . "/formulario.php");
        include("telas/" . $config["tela_padrao"] . "/formulario.php");
    }
}