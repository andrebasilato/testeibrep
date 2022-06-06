<?php

if($_POST["acao"] == "adicionar_arquivo") {

    $matriculaObj->verificaPermissao($perfil['permissoes'], $url[2].'|24');

    if (!$_POST['idconta']) {
        $adicionar = $matriculaObj->set('id', $matricula['idmatricula'])
            ->set('post', $_POST)
            ->adicionarArquivo();
    } else {
        $contaObj = new Contas();
        $contaObj->set('idusuario', $usuario['idusuario']);
        $adicionar = $contaObj->set('id', $_POST['idconta'])
            ->set('idusuario', $usuario['idusuario'])
            ->set('modulo', $url[0])
            ->set('post', $_POST)
            ->adicionarArquivo($matricula['idmatricula']);
    }

    if($adicionar["sucesso"]){
        $matriculaObj->set("pro_mensagem_idioma", $adicionar["mensagem"]);
        $matriculaObj->set("url", "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
        $matriculaObj->set("ancora", "pastavirtual");
        $matriculaObj->Processando();
    } else {
        $mensagem["erro"] = $adicionar["mensagem"];
    }
} elseif($_POST["acao"] == "editar_arquivo") {

    $matriculaObj->Set("id", $matricula["idmatricula"]);
    $matriculaObj->Set("post", $_POST);
    $adicionar = $matriculaObj->editarArquivo();

  if($adicionar["sucesso"]){
    $matriculaObj->Set("pro_mensagem_idioma",$adicionar["mensagem"]);
    $matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
    $matriculaObj->Set("ancora","pastavirtual");
    $matriculaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }

} elseif($_POST["acao"] == "remover_arquivo") {
  $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2]."|26");

  if (!$_POST['idconta_remover']) {
	$matriculaObj->Set("id", $matricula["idmatricula"]);
	$matriculaObj->Set("idarquivo", $_POST["idarquivo"]);
	$remover = $matriculaObj->removerArquivo();
  } else {
	$contaObj = new Contas();
	$contaObj->Set("id", $_POST['idconta_remover']);
	$contaObj->Set("idarquivo", $_POST["idarquivo"]);
	$contaObj->set('idusuario', $usuario['idusuario']);
	$remover = $contaObj->removerArquivo($matricula['idmatricula']);
  }



  if($remover["sucesso"]){
	$matriculaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
	$matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
	$matriculaObj->Set("ancora","documentosmatricula");
	$matriculaObj->Processando();
  } else {
	$mensagem["erro"] = $remover["mensagem"];
  }
} elseif($_POST["acao"] == "enviar_arquivo") {

    if ($_POST['idconta_enviar']) {
        $contaObj = new Contas();
        $contaObj->set('idusuario', $usuario['idusuario']);
        $adicionar = $contaObj->set('id', $_POST['idconta_enviar'])
                                  ->set('idusuario', $usuario['idusuario'])
                                  ->set('modulo', $url[0])
                                  ->set('post', $_POST)
                                  ->enviarArquivo((int) $_POST['idarquivo_enviar']);
    }else{
        $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2].'|24');
        $matriculaObj->Set("id", $matricula["idmatricula"]);
        $adicionar = $matriculaObj->enviarArquivo((int) $_POST['idarquivo_enviar']);
    }

  if($adicionar["sucesso"]){
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
	case "downloadarquivo":
      $matriculaObj->Set("iddocumento", intval($url[7]));
      $download = $matriculaObj->retornarArquivo();
      include("telas/".$config["tela_padrao"]."/administrar.download.arquivos.php");
      exit;
    break;
    case "visualizararquivo":
      $download = $matriculaObj->set('iddocumento', (int) $url[7])
                               ->retornarArquivo();
      include("telas/".$config["tela_padrao"]."/administrar.visualizar.arquivos.php");
      exit;
    case "visualizararquivopdf":
      $download = $matriculaObj->set('iddocumento', (int) $url[7])
                               ->retornarArquivo();
      include("telas/".$config["tela_padrao"]."/administrar.visualizar.arquivos.pdf.php");
      exit;
    break;
	case "editarprotocolo":
      $matriculaObj->Set("iddocumento", intval($url[7]));
      $documento = $matriculaObj->retornarArquivo();
      include("telas/".$config["tela_padrao"]."/administrar.arquivos.editar.php");
      exit;
    break;
	case "editarprotocoloconta":
      $matriculaObj->Set("iddocumento", intval($url[7]));
      $documento = $matriculaObj->retornarArquivoConta();
	  $idcontaflag = true;
      include("telas/".$config["tela_padrao"]."/administrar.arquivos.editar.php");
      exit;
    break;
	}
}

#$matricula["pastavirtual"] = $matriculaObj->RetornarPastavirtual();
$arquivos = $matriculaObj->retornarListaArquivos();
$contas = $matriculaObj->RetornarContas();
$arquivos_contas = $matriculaObj->retornarListaArquivosMatricula();

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

include("idiomas/".$config["idioma_padrao"]."/administrar.pastavirtual.php");
include("telas/".$config["tela_padrao"]."/administrar.pastavirtual.php");