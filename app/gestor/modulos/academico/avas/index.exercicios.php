<?php
$config["formulario_exercicios"] = array(
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
		"id" => "form_objetivas_faceis",
		"nome" => "objetivas_faceis", 
		"nomeidioma" => "form_objetivas_faceis",
		"tipo" => "input",
		"valor" => "objetivas_faceis",
		"validacao" => array("required" => "objetivas_faceis_vazio"), 
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"numerico" => true		
	  ),
	  array(
		"id" => "form_objetivas_intermediarias",
		"nome" => "objetivas_intermediarias", 
		"nomeidioma" => "form_objetivas_intermediarias",
		"tipo" => "input",
		"valor" => "objetivas_intermediarias",
		"validacao" => array("required" => "objetivas_intermediarias_vazio"), 
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"numerico" => true		
	  ),
	  array(
		"id" => "form_objetivas_dificeis",
		"nome" => "objetivas_dificeis", 
		"nomeidioma" => "form_objetivas_dificeis",
		"tipo" => "input",
		"valor" => "objetivas_dificeis",
		"validacao" => array("required" => "objetivas_dificeis_vazio"), 
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"numerico" => true		
	  ),
	  array(
		"id" => "iddisciplina_perguntas",
		"nome" => "iddisciplina_perguntas",
		"nomeidioma" => "form_iddisciplina_perguntas",
		"tipo" => "checkbox",
		"sql" => "select 
					d.iddisciplina, d.nome
				  from
					avas a
					inner join avas_disciplinas ad on (a.idava = ad.idava)
					inner join disciplinas d on (ad.iddisciplina = d.iddisciplina)
				  where 
					ad.ativo = 'S' and d.ativo = 'S' and a.idava = $url[3]", 
		"sql_orderm_campo" => "nome",
		"sql_orderm" => "asc",
		"sql_valor" => "iddisciplina", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"validacao" => array("required" => "disciplina_perguntas_vazio"),
		"ajudaidioma" => "form_ajuda_disciplina_perguntas",
		"class" => "span2 optionzzz"
	  ),
	  array(
		"id" => "iddisciplina_nota",
		"nome" => "iddisciplina_nota",
		"nomeidioma" => "form_iddisciplina_nota",
		"tipo" => "select",
		"sql" => "select 
					d.iddisciplina, d.nome
				  from
					avas a
					inner join avas_disciplinas ad on (a.idava = ad.idava)
					inner join disciplinas d on (ad.iddisciplina = d.iddisciplina)
				  where 
					ad.ativo = 'S' and d.ativo = 'S' and a.idava = $url[3]", 
		"sql_orderm_campo" => "nome",
		"sql_orderm" => "asc",
		"sql_valor" => "iddisciplina", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"validacao" => array("required" => "disciplina_nota_vazio"),
		"ajudaidioma" => "form_ajuda_disciplina_nota",
		"valor" => "iddisciplina_nota",
		"class" => "span4",
		"banco" => true,
	  ),
	  array(
		"id" => "form_nota_minima",
		"nome" => "nota_minima", 
		"nomeidioma" => "form_nota_minima",
		"tipo" => "input",
		"valor" => "nota_minima",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"decimal" => true		
	  ),
	  /*array(
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
	  ),*/
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
		"id" => "idava_exercicio", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )								  
);
						
$config["listagem_exercicios"] = array(
  array(
	"id" => "idexercicio",
	"variavel_lang" => "tabela_idexercicio", 
	"tipo" => "banco", 
	"coluna_sql" => "ae.idexercicio", 
	"valor" => "idexercicio", 
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
	"coluna_sql" => "ae.nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "exibir_ava", 
	"variavel_lang" => "tabela_exibir_ava", 
	"tipo" => "php",
	"coluna_sql" => "ae.exibir_ava", 
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
	"coluna_sql" => "ae.data_cad",
	"tipo" => "php", 
	"valor" => 'return formataData($linha["data_cad"],"br",1);',
	"tamanho" => "140"
  ), 
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idexercicio"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
 );
						   						   
$linhaObj->Set('config', $config);
include('../classes/avas.exercicios.class.php');
		
$linhaObj = new Exercicios();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28");	
	
$linhaObj->set('idusuario', $usuario['idusuario']);
$linhaObj->set('monitora_onde', $config['monitoramento']['onde_exercicios']);
$linhaObj->set('idava', (int) $url[3]);

$linhaObj->config["banco"] = $config["banco_exercicios"];
$linhaObj->config["formulario"] = $config["formulario_exercicios"];

if($_POST["acao"] == "salvar_exercicio"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");
		
  if($_FILES) {
	foreach($_FILES as $ind => $val) {
	  $_POST[$ind] = $val;
	}
  }
  
  $linhaObj->Set("post",$_POST);
  $linhaObj->Set("id",$url[3]);		
  if($_POST[$config["banco_exercicios"]["primaria"]]) 
	$salvar = $linhaObj->ModificarExercicio();
  else 
	$salvar = $linhaObj->CadastrarExercicio();
  
  if($salvar["sucesso"]){
	if($_POST[$config["banco_exercicios"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_exercicio") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|30");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverExercicio();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
}	

if(isset($url[5])){	
  $avaObj = new Ava();

  $avaObj->Set("idusuario",$usuario["idusuario"]);
  $avaObj->Set("monitora_onde",$config["monitoramento"]["onde_rotas_aprendizagem"]);
  $avaObj->Set("id",intval($url[3]));

  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");

	$avaObj->Set("limite","-1");
	$avaObj->Set("ordem","asc");
	$avaObj->Set("ordem_campo","d.nome");
	$avaObj->Set("campos","d.*, ad.idava_disciplina");
	$disciplinasAva = $avaObj->ListarTodasDisciplinas();

	include("idiomas/".$config["idioma_padrao"]."/formulario.exercicios.php");
	include("telas/".$config["tela_padrao"]."/formulario.exercicios.php");
	exit();
  } else {	
	$linhaObj->Set("id",intval($url[5]));
	$linhaObj->Set("campos","ae.*, a.nome as ava");	
	$linha = $linhaObj->RetornarExercicio();

	if($linha) {
	  switch($url[6]) {
		case "editar":			
		  	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|29");

		  	$avaObj->Set("limite","-1");
			$avaObj->Set("ordem","asc");
			$avaObj->Set("ordem_campo","d.nome");
			$avaObj->Set("campos","d.*, ad.idava_disciplina");
			$disciplinasAva = $avaObj->ListarTodasDisciplinas();

			$linhaObj->Set("limite","-1");
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("ordem_campo","avd.idexercicio_disciplina");
			$linhaObj->Set("campos","avd.*");
			$disciplinasPerguntas = $linhaObj->RetornarDisciplinasPerguntas();

		  	include("idiomas/".$config["idioma_padrao"]."/formulario.exercicios.php");
		  	include("telas/".$config["tela_padrao"]."/formulario.exercicios.php");
		break;
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|30");
		  include("idiomas/".$config["idioma_padrao"]."/remover.exercicios.php");
		  include("telas/".$config["tela_padrao"]."/remover.exercicios.php");
		break;
		case "opcoes":			
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.exercicios.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.exercicios.php");
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
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_exercicios"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","ae.*, a.nome as ava");	
  $dadosArray = $linhaObj->ListarTodasExercicio();		
  include("idiomas/".$config["idioma_padrao"]."/index.exercicios.php");
  include("telas/".$config["tela_padrao"]."/index.exercicios.php");
}