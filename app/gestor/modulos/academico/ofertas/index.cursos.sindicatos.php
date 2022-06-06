<?php

$config["listagem_cursos_sindicatos"] = array(  
  array(
	"id" => "sindicato", 
	"variavel_lang" => "tabela_sindicato",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "i.nome",
	"valor" => "escola",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "curso", 
	"variavel_lang" => "tabela_curso",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "c.nome",
	"valor" => "curso",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "limite",
	"variavel_lang" => "tabela_limite", 
	"tipo" => "banco", 
	"coluna_sql" => "ocp.limite", 
	"valor" => "limite", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),  
 );
						   						   
$linhaObj->Set("config",$config);				
	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_cursos_sindicatos"]);

$linhaObj->config["banco"] = $config["banco_cursos_sindicatos"];


if($_POST["acao"] == "salvar_cursos_sindicatos"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");		
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->cadastrarCursoSindicatoLimite();
  
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","salvar_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos_sindicatos");
	$linhaObj->Processando();
  }
}

$dadosArray = $linhaObj->ListarCursosSindicatos();

include("idiomas/".$config["idioma_padrao"]."/index.cursos.sindicatos.php");
include("telas/".$config["tela_padrao"]."/index.cursos.sindicatos.php");

?>