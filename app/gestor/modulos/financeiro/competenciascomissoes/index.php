<?php
include("../classes/comissoes.competencias.class.php");
include("config.php");

//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new Comissoes_Competencias();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);

if ($_POST["acao"] == "salvar") {
    //print_r2($_POST,true);
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
    $linhaObj->Set("post", $_POST);
    $salvar = $linhaObj->CadastrarModificar();
    if ($salvar["sucesso"]) {
        $linhaObj->Set("pro_mensagem_idioma", "cadastrar_modificar_sucesso");
        $get = "";
        if ($_GET["de_mes"] || $_GET["de_ano"] || $_GET["ate_mes"] || $_GET["ate_ano"]) {
            $get = "?de_mes=" . $_GET["de_mes"] . "&de_ano=" . $_GET["de_ano"] . "&ate_mes=" . $_GET["ate_mes"] . "&ate_ano=" . $_GET["ate_ano"];
        }
        $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . $get);
        $linhaObj->Processando();
    }
}

if (!$_GET["de_mes"]) $_GET["de_mes"] = date("m", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));
if (!$_GET["de_ano"]) $_GET["de_ano"] = date("Y", mktime(0, 0, 0, date("m") - 2, 1, date("Y")));
if (!$_GET["ate_mes"]) $_GET["ate_mes"] = date("m", mktime(0, 0, 0, date("m") + 1, 1, date("Y")));
if (!$_GET["ate_ano"]) $_GET["ate_ano"] = date("Y", mktime(0, 0, 0, date("m") + 1, 1, date("Y")));

$competencias = $linhaObj->ListarTodas();
//print_r2($competencias,true);	
include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");

?>