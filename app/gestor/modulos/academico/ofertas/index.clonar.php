<?php

$config["formulario_clonar"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_nome",
		"nome" => "nome", 
		"nomeidioma" => "form_nome",
		"tipo" => "input",
		"valor" => "nome",
		"validacao" => array("required" => "nome_vazio"), 
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),	  
	  array(
		"id" => "data_inicio_matricula",
		"nome" => "data_inicio_matricula",
		"nomeidioma" => "form_data_inicio_matricula",
		"tipo" => "input", 
		"valor" => "data_inicio_matricula",
		"valor_php" => 'if($dados["data_inicio_matricula"]) return formataData("%s", "br", 0)',
		"evento" => "readonly='readonly' style='cursor:text;'",
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"validacao" => array("required" => "data_inicio_matricula_vazio"),
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "data_fim_matricula",
		"nome" => "data_fim_matricula",
		"nomeidioma" => "form_data_fim_matricula",
		"tipo" => "input", 
		"valor" => "data_fim_matricula",
		"valor_php" => 'if($dados["data_fim_matricula"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"evento" => "readonly='readonly' style='cursor:text;'",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"validacao" => array("required" => "data_fim_matricula_vazio"),
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),																															
	)
  )								  
);

$config['formulario'] = $config['formulario_clonar'];
$linhaObj->Set("config",$config);	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");
$linhaObj->Set("idusuario",$usuario["idusuario"]);

if ($_POST["acao"] == "clonar") {  	
	
  	$salvar = $linhaObj->clonar((int) $url[3], $_POST);
  
  	if ($salvar["sucesso"]) {
		$linhaObj->Set("pro_mensagem_idioma","clonar_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$salvar["idoferta"]."/editar");
		$linhaObj->Processando();
  	}
	
}

$cursos = $linhaObj->listarDadosCursosAssociados((int) $url[3]);

include("idiomas/".$config["idioma_padrao"]."/clonar.php");
include("telas/".$config["tela_padrao"]."/clonar.php");
