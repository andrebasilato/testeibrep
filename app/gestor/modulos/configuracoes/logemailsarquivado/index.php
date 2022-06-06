<?php
include("../classes/logemails.class.php");
include("config.php");
include("config.listagem.php");

include("../includes/monitora.onde.php");
include("../includes/monitora.oque.php");
$monitora_onde["pt_br"] = $monitora_onde;
$monitora_acao["pt_br"] = $monitora_acao;


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Log_Emails();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");
//ALTERADA CONEXÃO PARA O BANCOS SEPARADO DE LOG DE EMAILS
$conexao = $linhaObj->iniciaConexao($config["host_log"],
                                    $config["usuario_log"],
                                    $config["senha_log"],
                                    $config["database_log"]);

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("naomonitora", true);

if ($_POST['acao'] == 'reenviar_email') {
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $reenviarEmail = $linhaObj->reenviarEmail(intval($_POST['idemail']), $_POST['email']);

    $acao_url = explode("?",$_SERVER["HTTP_REFERER"]);
    if ($acao_url[1]) {
        $acao_url[1] = "?".$acao_url[1];
    }

    if ($reenviarEmail["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "reenviar_email_sucesso");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . $acao_url[1]);
        $linhaObj->Processando();
    } else {
        $linhaObj->Set("pro_mensagem_idioma", "reenviar_email_erro");
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . $acao_url[1]);
        $linhaObj->Processando();
    }
}


if (isset($url[3])) {

    $linhaObj->Set("id", intval($url[3]));
    $linhaObj->Set("campos", "*");
    $linha = $linhaObj->Retornar();
    if ($linha) {
        switch ($url[4]) {
            case "visualizar":
                include("idiomas/" . $config["idioma_padrao"] . "/visualizar.php");
                include("telas/" . $config["tela_padrao"] . "/visualizar.php");
                break;
            case "reenvio":
                include("idiomas/" . $config["idioma_padrao"] . "/reenvio.php");
                include("telas/" . $config["tela_padrao"] . "/reenvio.php");
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
    $linhaObj->Set("campos", "el.*");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
//FECHA A CONEXAO COM O BANCO DE EMAILS
$linhaObj->fechaConexao($conexao);

?>