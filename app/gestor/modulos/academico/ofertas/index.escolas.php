<?php
$config["listagem_escolas"] = array(
  array(
	"id" => "idescola",
	"variavel_lang" => "tabela_idescola",
	"tipo" => "banco",
	"coluna_sql" => "p.idescola",
	"valor" => "idescola",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 80
  ),
  array(
	"id" => "idoferta_escola",
	"variavel_lang" => "tabela_idoferta_escola",
	"tipo" => "banco",
	"coluna_sql" => "op.idoferta_escola",
	"valor" => "idoferta_escola",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 1,
	"tamanho" => 100
  ),
  array(
	"id" => "nome",
	"variavel_lang" => "tabela_nome",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "p.nome_fantasia",
	"valor" => "nome_fantasia",
	"busca" => true,
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_metodo" => 2
  ),
  array(
	"id" => "sindicato",
	"variavel_lang" => "tabela_sindicato",
	"tipo" => "banco",
	"evento" => "maxlength='100'",
	"coluna_sql" => "i.nome_abreviado",
	"valor" => "sindicato",
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
	"id" => "opcoes",
	"variavel_lang" => "tabela_opcoes",
	"tipo" => "php",
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["idoferta_escola"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  )
 );

$linhaObj->Set("config",$config);

$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_escolas"]);

$linhaObj->config["banco"] = $config["banco_escolas"];


if($_POST["acao"] == "remover_escola"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverEscolas();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cfc");
	$linhaObj->Processando();
  }
}

if(isset($url[5])){
	$linhaObj->Set("idoferta_escola",intval($url[5]));
	$linhaObj->Set("campos","op.*, p.nome_fantasia as escola, o.nome as oferta, o.idoferta");
	$linha = $linhaObj->RetornarEscola();

	if($linha) {
	  switch($url[6]) {
		case "remover":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		  include("idiomas/".$config["idioma_padrao"]."/remover.escolas.php");
		  include("telas/".$config["tela_padrao"]."/remover.escolas.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.escolas.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.escolas.php");
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

  $escolasArray = $linhaObj->ListarEscolasNaoAssociados($url[3]);

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_escolas"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","p.nome_fantasia, op.*, i.nome_abreviado as sindicato");
  $dadosArray = $linhaObj->ListarEscolasAssociados();
  include("idiomas/".$config["idioma_padrao"]."/index.escolas.php");
  include("telas/".$config["tela_padrao"]."/index.escolas.php");
}
?>