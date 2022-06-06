<?php

include("../classes/usuarios.class.php");
include("config.php");

$linhaObj = new Usuarios();

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);
//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");


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

    $linhaObj->Set("id", intval($usuario["idusuario"]));
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->AlterarMeusdados();

    if ($salvar["sucesso"]) {
        $_POST["msg"] = "modificar_sucesso";
    }
}
if (isset($usuario["idusuario"])) {
    $linhaObj->Set("id", (int)$usuario["idusuario"]);
    $linhaObj->Set("campos", "u.*, p.nome as perfil, c.nome as cidade, e.nome as estado");
    $linha = $linhaObj->Retornar();
    if (isset($url[3])) {
        switch ($url[3]) {
            case "download":
                include("telas/" . $config["tela_padrao"] . "/download.php");
                break;
            case "excluir":
                include("idiomas/" . $config["idioma_padrao"] . "/excluir.arquivo.php");
                $linhaObj->RemoverArquivo('usuariosadm', 'avatar', $linha, $idioma);
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

?>