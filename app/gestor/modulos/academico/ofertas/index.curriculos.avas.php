<?php

$config["listagem_cursos_escolas"] = array(  
  array(
	"id" => "escola", 
	"variavel_lang" => "tabela_escola",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "p.nome_fantasia",
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
	"id" => "curriculo", 
	"variavel_lang" => "tabela_curriculo",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "cur.nome",
	"valor" => "curriculo",
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
  array(
	"id" => "dias_para_ava",
	"variavel_lang" => "tabela_dias_para_ava", 
	"tipo" => "banco", 
	"coluna_sql" => "ocp.dias_para_ava", 
	"valor" => "dias_para_ava", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ), 
  array("id" => "data_limite_ava", 
			  "variavel_lang" => "tabela_data_limite_ava", 
			  "coluna_sql" => "ocp.data_limite_ava",
			  "tipo" => "php", 
			  "valor" => 'return formataData($linha["data_limite_ava"],"br",0);',
			  "busca" => true,
			  "tamanho" => "90",
			  "busca_class" => "inputPreenchimentoCompleto",
			  "busca_metodo" => 3),   
  /*array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idoferta_turma"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) */
 );
						   						   
$linhaObj->Set("config",$config);				
	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_turmas"]);

$linhaObj->config["banco"] = $config["banco_cursos_escolas"];


if($_POST["acao"] == "salvar_curriculos_avas"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");		
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->cadastrarCurriculoDisciplinaAva();
  
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","salvar_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/curriculos_avas");
	//$linhaObj->Processando();
  }
}

/*if(isset($url[5])){			
	$linhaObj->Set("idoferta_escola",intval($url[5]));
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","op.*, p.nome_fantasia as escola, o.nome as oferta, o.idoferta");	
	$linha = $linhaObj->RetornarEscolaOferta();

	if($linha) {
	  switch($url[6]) {	 
		case "json":
		  include("idiomas/".$config["idioma_padrao"]."/json.php");
		  include("telas/".$config["tela_padrao"]."/json.php");
		break;
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		  exit();
	  }				
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	  exit();
	}			
} else {*/

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_cursos_escolas"]["primaria"];
  $linhaObj->Set("ordem_campo",'idoferta_curso_escola');
  $linhaObj->Set("campos","ocp.*, p.nome_fantasia as escola, c.nome as curso, cur.nome as curriculo");
  $linhaObj->Set("id",intval($url[3]));
  $dadosArray = $linhaObj->ListarCurriculosAvas(); 
  
  include("idiomas/".$config["idioma_padrao"]."/index.curriculos.avas.php");
  include("telas/".$config["tela_padrao"]."/index.curriculos.avas.php");
//}
?>