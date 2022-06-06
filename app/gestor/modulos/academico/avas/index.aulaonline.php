<?php
$linhaObj->Set("config",$config);

$linhaObj = new Ava();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_rotas_aprendizagem"]);
$linhaObj->Set("id",intval($url[3]));

if($_POST["acao"] == "cadastrar_aula_online"){
    if (!$_POST['idaula']){
        $linhaObj->Set("pro_mensagem_idioma","vazio_select_aula");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    } else {
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        $salvar = $linhaObj->AssociarAulaOnLine(intval($_POST['idaula']));

        if ($salvar["sucesso"]) {
            $linhaObj->Set("pro_mensagem_idioma", "cadastrar_objeto_sucesso");
            $linhaObj->Set("url", "/" . $url[0] . "/" . $url[1] . "/" . $url[2] . "/" . $url[3] . "/" . $url[4]);
            $linhaObj->Processando();
        }
    }
} elseif($_POST["acao"] == "remover_aula_online"){
    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
    $linhaObj->Set("post",$_POST);
    $remover = $linhaObj->DesassociarAulaOnLine();

    if($remover["sucesso"]){
        $linhaObj->Set("pro_mensagem_idioma","remover_objeto_sucesso");
        $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
        $linhaObj->Processando();
    }
}

$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

$linhaObj->Set("limite",-1);
$linhaObj->Set("ordem_campo","ao.nome");
$linhaObj->Set("ordem","asc");
$linhaObj->Set("campos","ao.*");
$aulas = $linhaObj->listarAulasOnline();
//var_dump($aulas);

$linhaObj->Set("limite","-1");
$linhaObj->Set("ordem","asc");
$linhaObj->Set("ordem_campo","ao.nome");
$linhaObj->Set("campos","ao.*, aao.idavas_aulas_online, d.nome as disciplina");
$aulasAva = $linhaObj->listarTodasAulasOnLine();
//var_dump($aulasAva);die();

include("idiomas/".$config["idioma_padrao"]."/formulario.aulasonline.php");
include("telas/".$config["tela_padrao"]."/formulario.aulasonline.php");
exit;
?>
