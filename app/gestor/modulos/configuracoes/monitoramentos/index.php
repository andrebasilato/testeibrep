<?php
include("../classes/monitoramentos.class.php");
include("config.php");
include("config.listagem.php");

include("../includes/monitora.onde.php");
include("../includes/monitora.oque.php");
$monitora_onde["pt_br"] = $monitora_onde;
$monitora_acao["pt_br"] = $monitora_acao;


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Monitoramentos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);

if (isset($url[3])) {
    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("campos", "*");
    $linha = $linhaObj->Retornar();
    if ($linha) {
        switch ($url[4]) {
            case "visualizar":
                $log = $linhaObj->RetornarLog(intval($url[3]));
                include("idiomas/" . $config["idioma_padrao"] . "/visualizar.php");
                include("telas/" . $config["tela_padrao"] . "/visualizar.php");
                break;
            default:
                header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                exit();
        }
    } else {
        header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
        exit();
    }
} else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "m.*, u.nome as usuario");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
?>