<?php
require '../classes/reconhecimento.class.php';
require '../classes/datavalid.class.php';
include_once '../classes/pessoas.class.php';

$reconhecimentoObj = new Reconhecimento();

#lista as imagens que passaram por testes do Datavalid
$imagensPriDV = $reconhecimentoObj->retornaImagensPrincipaisDatavalid($url[3]);
$comparacoesDV = $reconhecimentoObj->retornarTodasFotosDatavalid($url[3]);

$imagemPrincipal = $reconhecimentoObj->retornaImagemPrincipal($url[3]);
$todasComparacoes = $reconhecimentoObj->retornarTodasComparacoes($url[3]);

if($_POST["acao"] == "remover_imagem") {
    $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|33");
    $reconhecimentoObj->removerLiberacaoTemporaria($url[3]);
    $deletar = $reconhecimentoObj->removerImagemPrincipal();

    if($deletar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma", $idioma["removido_sucesso"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","reconhecimento");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
}

include("idiomas/".$config["idioma_padrao"]."/administrar.reconhecimento.php");
include("telas/".$config["tela_padrao"]."/administrar.reconhecimento.php");

?>