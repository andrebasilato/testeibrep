<?php

if ('trocar_situacao_da_mensagem' == Request::url(7)) {

    $idmessage    = (int) Request::url(8);
    $newSituation = $_GET['changeTo'];

    $reflector = new ReflectionClass(get_class($matriculaObj));
    $modifiersAllowed = $reflector->getConstants();

    if ($statusRequires = array_search($newSituation, $modifiersAllowed)) {
        $matriculaObj->modifyMessageVisibilityTo($idmessage, $reflector->getConstant($statusRequires));

        $matriculaObj->set('status_da_mensagem_alterado', $alterar["mensagem"]);

    } else {
        $matriculaObj->set('status_da_mensagem_nao_foi_alterado', $alterar["mensagem"]);
    }

    // Redirect
    $matriculaObj->set('url', Request::url('0-6', '/'))
                 ->set('ancora', 'mensagensmatricula')
                 ->processando();
}

if($_POST["acao"] == "salvar_mensagem") {
  //if($matricula["situacao"]["visualizacoes"][72]) {
	if($_POST["mensagem"]) {
	  $salvar = $matriculaObj->cadastrarMensagem($_FILES);
	  if($salvar["sucesso"]){
		if($_POST['enviar_email']){
			$matriculaObj->EnviarMensagemEmail($salvar["id"],utf8_decode($matricula["pessoa"]["nome"]),$matricula["pessoa"]["email"]);
		}
		$matriculaObj->Set("pro_mensagem_idioma","mensagem_adicionada_sucesso");
		$matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
		$matriculaObj->Set("ancora","mensagensmatricula");
		$matriculaObj->Processando();
	  } else {
		$mensagem["erro"] = $salvar["mensagem"];
	  }
	} else {
	  $salvar["sucesso"] = false;
	  $salvar["erros"][] = "mensagem_vazio";
	}
  //} else {
	//$salvar["sucesso"] = false;
	//$salvar["mensagem"] = "mensagem_permissao_workflow";
  //}
} elseif($_POST["acao"] == "remover_mensagem") {
	if($_POST["idmensagem"]) {
	  $remover = $matriculaObj->removerMensagem(intval($_POST["idmensagem"]));
	  if($remover["sucesso"]){
		$matriculaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
		$matriculaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
		$matriculaObj->Set("ancora","mensagensmatricula");
		$matriculaObj->Processando();
	  } else {
		$mensagem["erro"] = $remover["mensagem"];
	  }
	} else {
	  $mensagem["erro"] = "mensagem_remover_vazio";
	}
} 

$matricula['oferta'] = $matriculaObj->RetornarOferta();
$matricula['curso'] = $matriculaObj->RetornarCurso();
$matricula['escola'] = $matriculaObj->RetornarEscola();

$matricula["mensagens"] = $matriculaObj->RetornarMensagens();
foreach ($matricula["mensagens"] as $key => $value) {
 $matricula["mensagens"][$key]["arquivos"] = $matriculaObj->retornarMensagensArquivos($value['idmensagem']);
}

$matricula["id_ultima_mensagem"] = $matriculaObj->matricula["id_ultima_mensagem"];

include("idiomas/".$config["idioma_padrao"]."/administrar.mensagens.php");
include("telas/".$config["tela_padrao"]."/administrar.mensagens.php");