<?php
$config["formulario_objetos_divisores"] = array(
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
		"id" => "form_cor_bg",
		"nome" => "cor_bg",
		"nomeidioma" => "form_cor_bg",
		"tipo" => "input", 
		"valor" => "cor_bg",
		"class" => "span1",
		"evento" => "maxlength='6'", 
		"validacao" => array("required" => "cor_bg_vazio"),
		"banco" => true,
		"banco_string" => true,
		"colorpicker" => true,
		"legenda" => "#",													
	  ),
	  array(
		"id" => "form_cor_letra",
		"nome" => "cor_letra",
		"nomeidioma" => "form_cor_letra",
		"tipo" => "input", 
		"valor" => "cor_letra",
		"class" => "span1",
		"evento" => "maxlength='6'", 
		"validacao" => array("required" => "cor_letra_vazio"),
		"banco" => true,
		"banco_string" => true,
		"colorpicker" => true,
		"legenda" => "#",													
	  ),
	  array(
		"id" => "form_ordem",
		"nome" => "ordem",
		"nomeidioma" => "form_ordem",
		"tipo" => "input", 
		"valor" => "ordem",
		"class" => "span1",
		"evento" => "maxlength='2'", 
		//"validacao" => array("required" => "ordem_vazio"),
		"banco" => true,
		"banco_string" => true,
		"numerico" => true															
	  ),
	  array(
		"id" => "form_agrupado",
		"nome" => "agrupado",
		"nomeidioma" => "form_agrupado",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "agrupado",
		"validacao" => array("required" => "agrupado_vazio"),
		"ajudaidioma" => "form_agrupado_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_exibir_ava",
		"nome" => "exibir_ava",
		"nomeidioma" => "form_exibir_ava",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "exibir_ava",
		"validacao" => array("required" => "exibir_ava_vazio"),
		"ajudaidioma" => "form_ativo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "idava_objeto", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);
						
$config["listagem_objetos_divisores"] = array(
  array(
	"id" => "idobjeto_divisor",
	"variavel_lang" => "tabela_idobjeto_divisor", 
	"tipo" => "banco", 
	"coluna_sql" => "idobjeto_divisor", 
	"valor" => "idobjeto_divisor", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 60
  ),
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "od.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "exibir_ava", 
	"variavel_lang" => "tabela_exibir_ava", 
	"tipo" => "php",
	"coluna_sql" => "od.exibir_ava", 
	"valor" => 'if($linha["exibir_ava"] == "S") {
				  return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">A</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">I</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "ativo",
	"busca_metodo" => 1,
	"tamanho" => 60
  ), 								  				
  array(
	"id" => "data_cad", 
	"variavel_lang" => "tabela_datacad", 
	"coluna_sql" => "od.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idobjeto_divisor"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
 );
						   						   
$linhaObj->Set("config",$config);						   
require '../classes/avas.objetosdivisores.class.php';
require '../classes/avas.videos.class.php';
require '../classes/dataaccess/db.php';
require '../classes/dataaccess/mysql.php';
		
$linhaObj = new ObjetosDivisores();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_objetos_divisores"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_objetos_divisores"];
$linhaObj->config["formulario"] = $config["formulario_objetos_divisores"];

if($_POST["acao"] == "salvar_objeto_divisor"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		
  if($_FILES) {
	foreach($_FILES as $ind => $val) {
	  $_POST[$ind] = $val;
	}
  }
  
  $linhaObj->Set("post",$_POST);		
  if($_POST[$config["banco_objetos_divisores"]["primaria"]]) 
	$salvar = $linhaObj->ModificarObjetoDivisor();
  else 
	$salvar = $linhaObj->CadastrarObjetoDivisor();
  
  if($salvar["sucesso"]){
	if($_POST[$config["banco_objetos_divisores"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_objeto_divisor") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverObjetoDivisor();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}	

if(isset($url[5])){			
  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
	include("idiomas/".$config["idioma_padrao"]."/formulario.objetosdivisores.php");
	include("telas/".$config["tela_padrao"]."/formulario.objetosdivisores.php");
	exit();
  } else {	
	$linhaObj->Set("id",intval($url[5]));
	$linhaObj->Set("campos","od.*, a.nome as ava");	
	$linha = $linhaObj->RetornarObjetoDivisor();

	if($linha) {
	  switch($url[6]) {
		case "editar":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.objetosdivisores.php");
		  include("telas/".$config["tela_padrao"]."/formulario.objetosdivisores.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		  include("idiomas/".$config["idioma_padrao"]."/remover.objetosdivisores.php");
		  include("telas/".$config["tela_padrao"]."/remover.objetosdivisores.php");
		break;
		case "opcoes":			
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.objetosdivisores.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.objetosdivisores.php");
		break;
		case "download":
		  include("telas/".$config["tela_padrao"]."/download.php");
		break;
		case "excluir":
		  include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
		  $linhaObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $linha, $idioma);
		break;		
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		  exit();
	  }				
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	  exit();
	}			
  }
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_objetos_divisores"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","od.*, a.nome as ava");	
  $dadosArray = $linhaObj->ListarTodasObjetosDivisores();		
  include("idiomas/".$config["idioma_padrao"]."/index.objetosdivisores.php");
  include("telas/".$config["tela_padrao"]."/index.objetosdivisores.php");
}
?>