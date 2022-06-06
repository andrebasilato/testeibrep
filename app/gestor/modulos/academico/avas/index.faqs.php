<?php
$config["formulario_faqs"] = array(
										array(
											"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
											"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
											"campos" => array( // Campos do formulario																						
														    array(
														  		  "id" => "form_pergunta",
														  		  "nome" => "pergunta",
														  		  "nomeidioma" => "form_pergunta",
														  		  "tipo" => "text", 
														  		  "editor" => true,
														  		  "valor" => "pergunta",
														  		  "class" => "span6",
														  		  "validacao" => array("required" => "pergunta_vazio"),
														  		  "banco" => true, 
														  		  "banco_string" => true,
														    	),
														    array(
														  		  "id" => "form_resposta",
														  		  "nome" => "resposta",
														  		  "nomeidioma" => "form_resposta",
														  		  "tipo" => "text", 
														  		  "editor" => true,
														  		  "valor" => "resposta",
														  		  "class" => "span6",
														  		  "validacao" => array("required" => "resposta_vazio"),
														  		  "banco" => true, 
														  		  "banco_string" => true,
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
														  		  "banco" => true,
														  		  "banco_string" => true
													    		),
														    array(
														  		  "id" => "idava_pergunta", // Id do atributo HTML
														  		  "nome" => "idava", // Name do atributo HTML
														  		  "tipo" => "hidden", // Tipo do input
														  		  "valor" => 'return $this->url["3"];',
														  		  "banco" => true
														    	),
														 	)
										  	)								  
										);
						
$config["listagem_faqs"] = array(
									array(
										  "id" => "idfaq",
										  "variavel_lang" => "tabela_idfaq", 
										  "tipo" => "banco", 
										  "coluna_sql" => "af.idfaq", 
										  "valor" => "idfaq", 
										  "busca" => true,
										  "busca_class" => "inputPreenchimentoCompleto",
										  "busca_metodo" => 1,
										  "tamanho" => 60
										),
									array(
										  "id" => "pergunta", 
										  "variavel_lang" => "tabela_pergunta",
										  "tipo" => "banco",
										  "evento" => "maxlength='100'",
										  "coluna_sql" => "af.pergunta",
										  "valor" => "pergunta",
										  "busca" => true,
										  "busca_class" => "inputPreenchimentoCompleto",
										  "busca_metodo" => 2
										),
									array(
										  "id" => "exibir_ava", 
										  "variavel_lang" => "tabela_exibir_ava", 
										  "tipo" => "php",
										  "coluna_sql" => "af.exibir_ava", 
										  "valor" => 'if($linha["exibir_ava"] == "S") {
										  			    return "<span data-original-title=\"".$idioma["ativo"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\">S</span>";
										  			  } else {
										  			    return "<span data-original-title=\"".$idioma["inativo"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\">N</span>";
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
										  "coluna_sql" => "af.data_cad",
										  "tipo" => "php", 
										  "valor" => 'return formataData($linha["data_cad"],"br",1);',
										  "tamanho" => "140"
										), 
									array(
										  "id" => "opcoes", 
										  "variavel_lang" => "tabela_opcoes", 
										  "tipo" => "php", 
										  "valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idfaq"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
										  "busca_botao" => true,
										  "tamanho" => "80"
										) 
									);
						   						   
$linhaObj->Set("config",$config);						   
include("../classes/avas.faqs.class.php");
		
$linhaObj = new Faq();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|57");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_faqs"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_faqs"];
$linhaObj->config["formulario"] = $config["formulario_faqs"];

if ($_POST["acao"] == "salvar_faq"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|58");

	if($_FILES) {
		foreach($_FILES as $ind => $val) {
		  $_POST[$ind] = $val;
		}
	}
			
	$linhaObj->Set("post",$_POST);		
	if ($_POST[$config["banco_faqs"]["primaria"]]) {
		$salvar = $linhaObj->ModificarFaq();
	} else {
		$salvar = $linhaObj->CadastrarFaq();
	}

	if ($salvar["sucesso"]){
		if ($_POST[$config["banco_faqs"]["primaria"]]) {
		  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		} else {
		  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		}
		$linhaObj->Processando();
	}
} elseif ($_POST["acao"] == "remover_faq") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|59");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->RemoverFaq();
	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		$linhaObj->Processando();
	}
}	

if (isset($url[5])) {
	if($url[5] == "cadastrar") {
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|58");
		include("idiomas/".$config["idioma_padrao"]."/formulario.faqs.php");
		include("telas/".$config["tela_padrao"]."/formulario.faqs.php");
		exit();
	} else {
		$linhaObj->Set("id",intval($url[5]));
		$linhaObj->Set("campos","af.*, a.nome as ava");	
		$linha = $linhaObj->RetornarFaq();

		if($linha) {
			switch($url[6]) {
				case "editar":			
				  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|58");
				  include("idiomas/".$config["idioma_padrao"]."/formulario.faqs.php");
				  include("telas/".$config["tela_padrao"]."/formulario.faqs.php");
				break;
				case "remover":			
				  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|59");
				  include("idiomas/".$config["idioma_padrao"]."/remover.faqs.php");
				  include("telas/".$config["tela_padrao"]."/remover.faqs.php");
				break;
				case "opcoes":			
				  include("idiomas/".$config["idioma_padrao"]."/opcoes.faqs.php");
				  include("telas/".$config["tela_padrao"]."/opcoes.faqs.php");
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
	if (!$_GET["ordem"]) {
		$_GET["ordem"] = "DESC";
	}
	if (!$_GET["qtd"]) {
		$_GET["qtd"] = 30;
	}
	if(!$_GET["cmp"]) {
		$_GET["cmp"] = $config["banco_faqs"]["primaria"];
	}

	$linhaObj->Set("pagina",$_GET["pag"]);
	$linhaObj->Set("ordem",$_GET["ord"]);
	$linhaObj->Set("limite",intval($_GET["qtd"]));
	$linhaObj->Set("ordem_campo",$_GET["cmp"]);
	$linhaObj->Set("campos","af.idfaq,
							af.data_cad,
							af.exibir_ava,
							af.pergunta,
							a.nome as ava"
					);	
	$dadosArray = $linhaObj->ListarTodasFaqs();		
	include("idiomas/".$config["idioma_padrao"]."/index.faqs.php");
	include("telas/".$config["tela_padrao"]."/index.faqs.php");
}
?>