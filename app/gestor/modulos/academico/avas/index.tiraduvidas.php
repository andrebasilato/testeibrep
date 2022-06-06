<?php
$config["formulario_tira_duvidas"] = array(
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
		"id" => "form_pergunta",
		"nome" => "pergunta",
		"nomeidioma" => "form_pergunta",
		"tipo" => "text", 
		"editor" => true,
		"valor" => "pergunta",
		"validacao" => array("required" => "pergunta_vazio"),
		"class" => "xxlarge",
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
		"id" => "form_autoriza_exibir",
		"nome" => "autoriza_exibir",
		"nomeidioma" => "form_autoriza_exibir",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "autoriza_exibir",
		"validacao" => array("required" => "autoriza_exibir_vazio"),
		"ajudaidioma" => "form_autoriza_exibir_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "idusuario_cad", // Id do atributo HTML
		"nome" => "idusuario_cad", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->idusuario;',
		"banco" => true
	  ),
	  array(
		"id" => "idava_tira_duvidas", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);

$config["formulario_responder_duvida"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_resposta",
		"nome" => "resposta",
		"nomeidioma" => "form_resposta",
		"tipo" => "text", 
		"editor" => true,
		"valor" => "resposta",
		"validacao" => array("required" => "resposta_vazio"),
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_interesse_exibir",
		"nome" => "interesse_exibir",
		"nomeidioma" => "form_interesse_exibir",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "interesse_exibir",
		"validacao" => array("required" => "interesse_exibir_vazio"),
		"ajudaidioma" => "form_interesse_exibir_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_data_resposta", // Id do atributo HTML
		"nome" => "data_resposta", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => '',
		"banco" => true
	  ),
	  array(
		"id" => "idava_tira_duvidas", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);
						
$config["listagem_tira_duvidas"] = array(
  array(
	"id" => "idduvida",
	"variavel_lang" => "tabela_idduvida", 
	"tipo" => "banco", 
	"coluna_sql" => "idduvida", 
	"valor" => "idduvida", 
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
	"coluna_sql" => "d.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
   array(
	"id" => "aluno", 
	"variavel_lang" => "tabela_aluno",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "pe.nome",
	"valor" => "aluno",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "professor", 
	"variavel_lang" => "tabela_professor",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "po.nome",
	"valor" => "professor",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "exibir_ava", 
	"variavel_lang" => "tabela_exibir_ava", 
	"tipo" => "php",
	"coluna_sql" => "d.exibir_ava", 
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
	"coluna_sql" => "d.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ),
  array(
	"id" => "data_resposta", 
	"variavel_lang" => "tabela_dataresposta", 
	"coluna_sql" => "d.data_resposta",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_resposta"],"br",1);',
	"tamanho" => "140"
  ),
  array(
	"id" => "situacao", 
	"variavel_lang" => "tabela_status", 
	"tipo" => "php",
	"coluna_sql" => "d.situacao", 
	"valor" => 'if($linha["data_resposta"]) {
				  return "<span data-original-title=\"".$idioma["respondida"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">R</span>";
				} else {
				  return "<span data-original-title=\"".$idioma["nao_respondida"]."\" class=\"label label-warning\" data-placement=\"left\" rel=\"tooltip\">P</span>";
				}',
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_array" => "situacao_duvida_ava",
	"busca_metodo" => 4,
	"nao_ordenar" => true,
	"tamanho" => 90
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idduvida"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
 );
						   						   
$linhaObj->Set("config",$config);						   
include("../classes/avas.tiraduvidas.class.php");
		
$linhaObj = new Tira_Duvidas();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_tira_duvidas"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_tira_duvidas"];
$linhaObj->config["formulario"] = $config["formulario_tira_duvidas"];

if($_POST["acao"] == "salvar_duvida"){
  //$linhaObj->config["formulario"] = $config["formulario_responder_duvida"];
  $linhaObj->Set("post",$_POST);
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		
  $linhaObj->Set("post",$_POST);		
  if($_POST[$config["banco_tira_duvidas"]["primaria"]])
	$salvar = $linhaObj->ModificarDuvida();
  else
	$salvar = $linhaObj->CadastrarDuvida();
  
  if($salvar["sucesso"]){
	if($_POST[$config["banco_tira_duvidas"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
}if($_POST["acao"] == "salvar_resposta"){
  $linhaObj->config["formulario"] = $config["formulario_responder_duvida"];
  $linhaObj->Set("post",$_POST);
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->ResponderDuvida();
  
  if($salvar["sucesso"]){
	  $linhaObj->Set("pro_mensagem_idioma","responder_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	  $linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_duvida") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverDuvida();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}	

if(isset($url[5])){			
  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
	include("idiomas/".$config["idioma_padrao"]."/formulario.tiraduvidas.php");
	include("telas/".$config["tela_padrao"]."/formulario.tiraduvidas.php");
	exit();
  } else {	
	$linhaObj->Set("id",intval($url[5]));
	$linhaObj->Set("campos","d.*, a.nome as ava, us.nome as usu_adm, pe.nome as aluno, po.nome as professor");	
	$linha = $linhaObj->RetornarDuvida();

	if($linha) {
	  switch($url[6]) {
		case "editar":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.tiraduvidas.php");
		  include("telas/".$config["tela_padrao"]."/formulario.tiraduvidas.php");
		break;
		case "responder":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		  include("idiomas/".$config["idioma_padrao"]."/tiraduvidas.responder.php");
		  include("telas/".$config["tela_padrao"]."/tiraduvidas.responder.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		  include("idiomas/".$config["idioma_padrao"]."/remover.tiraduvidas.php");
		  include("telas/".$config["tela_padrao"]."/remover.tiraduvidas.php");
		break;
		case "opcoes":			
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.tiraduvidas.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.tiraduvidas.php");
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
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_tira_duvidas"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","d.*, a.nome as ava, pe.nome as aluno, po.nome as professor");	
  $dadosArray = $linhaObj->ListarTodasDuvidas();		
  include("idiomas/".$config["idioma_padrao"]."/index.tiraduvidas.php");
  include("telas/".$config["tela_padrao"]."/index.tiraduvidas.php");
}
?>