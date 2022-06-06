<?php					
$config["listagem_turmas"] = array(
  array(
	"id" => "idturma",
	"variavel_lang" => "tabela_idturma", 
	"tipo" => "banco", 
	"coluna_sql" => "idturma", 
	"valor" => "idturma", 
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),
  array(
	"id" => "nome", 
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "nome",
	"valor" => "nome",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "matriculas",
	"variavel_lang" => "tabela_matriculas", 
	"tipo" => "banco", 
	"valor" => "matriculas", 
	"tamanho" => 80
  ),   
  array(
	"id" => "ativo", 
	"variavel_lang" => "tabela_ativo",
	"tipo" => "php",
	"valor" => 'if($linha["ativo_painel"] == "S") {
					return "<span data-original-title=\"".$idioma["clique_ativar_inativar"]."\" class=\"label label-success\" data-placement=\"left\" rel=\"tooltip\" onclick=\"ativarInativar(".$linha["idoferta"].",".$linha["idturma"].");\" id=\"ativo_painel".$linha["idturma"]."\" style=\"cursor:pointer;\">Sim</span>";
				} else {
					return "<span data-original-title=\"".$idioma["clique_ativar_inativar"]."\" class=\"label label-important\" data-placement=\"left\" rel=\"tooltip\" onclick=\"ativarInativar(".$linha["idoferta"].",".$linha["idturma"].");\" id=\"ativo_painel".$linha["idturma"]."\" style=\"cursor:pointer;\">Não</span>";
				}',
	"busca" => false,
	"tamanho" => 80
  ),
  array(
	"id" => "opcoes", 
	"variavel_lang" => "tabela_opcoes", 
	"tipo" => "php", 
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idturma"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  ) 
 );
						   						   
$linhaObj->Set("config",$config);				
	
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_turmas"]);

$linhaObj->config["banco"] = $config["banco_turmas"];


if($_POST["acao"] == "remover_turma"){  
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");		
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverTurmas();
  
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/turmas");
	$linhaObj->Processando();
  }
}

if(isset($url[5])){			
	if($url[5] == "json" && $url[6] == "ativardesativar") {
		$linhaObj->Set('post', $_POST);
		echo $linhaObj->ativarInativarTurma();
		exit;
	}
	
	$linhaObj->Set("idturma",intval($url[5]));
	$linhaObj->Set("campos","t.*, o.nome as oferta, o.idoferta");	
	$linha = $linhaObj->RetornarTurma();

	if($linha) {
	  switch($url[6]) {
		case "remover":			
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");
		  include("idiomas/".$config["idioma_padrao"]."/remover.turmas.php");
		  include("telas/".$config["tela_padrao"]."/remover.turmas.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.turmas.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.turmas.php");
		break;
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
} else {
	

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = -1;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_turmas"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","*");	
  $dadosArray = $linhaObj->ListarTurmas();		
  include("idiomas/".$config["idioma_padrao"]."/index.turmas.php");
  include("telas/".$config["tela_padrao"]."/index.turmas.php");
}
?>