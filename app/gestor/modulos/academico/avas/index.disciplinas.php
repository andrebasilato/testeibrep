<?php
$linhaObj->Set("config",$config);

$linhaObj = new Ava();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_rotas_aprendizagem"]);
$linhaObj->Set("id",intval($url[3]));

if($_POST["acao"] == "cadastrar_disciplina"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  $salvar = $linhaObj->AssociarDisciplinas(intval($_POST['iddisciplina']));

  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","cadastrar_objeto_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_disciplina"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->DesassociarDisciplinas();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_objeto_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}elseif( $_POST["acao"] == "salvar_horas_offline" ){
  $linhaObj->Set("post",$_POST);
  $atualizar = $linhaObj->atualizarHorasOfflineDisciplinas();
  if( $atualizar ){
    $linhaObj->Set("pro_mensagem_idioma","atualiza_objeto_sucesso");
    $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  }
}

$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

$linhaObj->Set("limite",-1);
$linhaObj->Set("ordem_campo","d.nome");
$linhaObj->Set("ordem","asc");
$linhaObj->Set("campos","d.*");
$disciplinas = $linhaObj->ListarDisciplinas();

$linhaObj->Set("limite","-1");
$linhaObj->Set("ordem","asc");
$linhaObj->Set("ordem_campo","d.nome");
$linhaObj->Set("campos","aa.idavaliacao, d.*, ad.idava_disciplina,ad.tempo_offline");
$disciplinasAva = $linhaObj->ListarTodasDisciplinas();
include("idiomas/".$config["idioma_padrao"]."/formulario.disciplinas.php");
include("telas/".$config["tela_padrao"]."/formulario.disciplinas.php");
exit;
?>