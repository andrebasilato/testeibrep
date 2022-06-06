<?php
include("../classes/boletos.gerados.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj = new BoletosGerados();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1");

$linhaObj->Set("idusuario", $usuario["idusuario"]);
$linhaObj->Set("monitora_onde", $config["monitoramento"]["onde"]);

$linhaObj->Set("pagina", $_GET["pag"]);
if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
$linhaObj->Set("ordem", $_GET["ord"]);
if (!$_GET["qtd"]) $_GET["qtd"] = 30;
$linhaObj->Set("limite", intval($_GET["qtd"]));
if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
$linhaObj->Set("ordem_campo", $_GET["cmp"]);
$linhaObj->Set("campos", "c.*, b.nome as banco ,cw.nome as situacao, cw.cor_bg as situacao_cor_bg, cw.cor_nome as situacao_cor_nome, p.nome as aluno");
$dadosArray = $linhaObj->ListarTodas();
include("idiomas/" . $config["idioma_padrao"] . "/index.php");
include("telas/" . $config["tela_padrao"] . "/index.php");

?>