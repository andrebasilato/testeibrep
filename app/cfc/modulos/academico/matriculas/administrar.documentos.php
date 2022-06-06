<?php

if($_POST["acao"] == "adicionar_documento") {
    if($matricula["situacao"]["visualizacoes"][69]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("post", $_POST);
        $adicionar = $matriculaObj->adicionarDocumento();
    } else {
        $adicionar["sucesso"] = false;
        $adicionar["mensagem"] = "mensagem_permissao_workflow";;
    }
    if($adicionar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","pastavirtual");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "editar_documento") {
    if($matricula["situacao"]["visualizacoes"][69]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("post", $_POST);
        $adicionar = $matriculaObj->editarDocumento();
    } else {
        $adicionar["sucesso"] = false;
        $adicionar["mensagem"] = "mensagem_permissao_workflow";;
    }
    if($adicionar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","pastavirtual");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "adicionar_documentos_lote") {
    if($matricula["situacao"]["visualizacoes"][69]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("post", $_POST);
        $adicionar = $matriculaObj->adicionarLoteDocumento();
    } else {
        $adicionar["sucesso"] = false;
        $adicionar["mensagem"] = "mensagem_permissao_workflow";;
    }
    if($adicionar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","documentosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "enviar_documento") {
    if($matricula["situacao"]["visualizacoes"][69]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $adicionar = $matriculaObj->enviarDocumento((int) $_POST['iddocumento']);
    } else {
        $adicionar["sucesso"] = false;
        $adicionar["mensagem"] = "mensagem_permissao_workflow";;
    }
    if($adicionar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","documentosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "remover_documento") {
    if($matricula["situacao"]["visualizacoes"][70]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("iddocumento", $_POST["iddocumento"]);
        $remover = $matriculaObj->removerDocumento();
    } else {
        $remover["sucesso"] = false;
        $remover["mensagem"] = "mensagem_permissao_workflow";
    }
    if($remover["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","documentosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $remover["mensagem"];
    }
} elseif($_POST["acao"] == "aprovar_documento") {


    if ($matricula["situacao"]["visualizacoes"][71]) {
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $matriculaObj->Set("iddocumento", intval($_POST["iddocumento"]));
        $matriculaObj->Set("post", $_POST);
        $adicionar = $matriculaObj->aprovarDocumento();
    } else {
        $adicionar["sucesso"] = false;
        $adicionar["mensagem"] = "mensagem_permissao_workflow";
    }

    if ($adicionar["sucesso"]){
        $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
        $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->Set("ancora","documentosmatricula");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
}


if($url[6]) {
    switch ($url[6]) {
        case "validardocumento":

            $matriculaObj->Set("id", $matricula["idmatricula"]);
            $matriculaObj->Set("iddocumento", intval($url[7]));
            $documento = $matriculaObj->retornarDocumento();
            include("idiomas/".$config["idioma_padrao"]."/administrar.validar.documento.php");
            include("telas/".$config["tela_padrao"]."/administrar.validar.documento.php");
            exit;
            break;
        case "downloaddocumento":
            $matriculaObj->Set("iddocumento", intval($url[7]));
            $download = $matriculaObj->retornarDocumento();
            include("telas/".$config["tela_padrao"]."/administrar.download.documentos.php");
            exit;
            break;
        case "visualizardocumento":
            $matriculaObj->Set("iddocumento", intval($url[7]));
            $download = $matriculaObj->retornarDocumento();
            include("telas/".$config["tela_padrao"]."/administrar.visualizar.documentos.php");
            exit;
            break;
        case "editarprotocolo":
            $matriculaObj->Set("iddocumento", intval($url[7]));
            $documento = $matriculaObj->retornarDocumento();
            include("telas/".$config["tela_padrao"]."/administrar.documentos.editar.php");
            exit;
            break;
    }
}

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

require_once("../classes/tiposdocumentos.class.php");
$tiposDocumentosObj = new Tipos_Documentos();

$tiposDocumentos = $tiposDocumentosObj->set('idmatricula', intval($url[3]))
    ->retornarTodosComObrigatorio($matricula["escola"]["idsindicato"], $matricula["curso"]["idcurso"]);

$matricula["documentos"] = $matriculaObj->RetornarDocumentos();

include("idiomas/".$config["idioma_padrao"]."/administrar.documentos.php");
include("telas/".$config["tela_padrao"]."/administrar.documentos.php");
?>