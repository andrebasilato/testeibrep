<?php
$config["formulario_downloads"] = array(
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
		"id" => "idpasta",
		"nome" => "idpasta",
		"nomeidioma" => "form_idpasta",
		"tipo" => "select",
		"sql" => "SELECT idpasta, nome FROM avas_downloads_pastas WHERE ativo = 'S' AND idava = ".$url[3]." ORDER BY nome ", // SQL que alimenta o select
		"sql_valor" => "idpasta", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idpasta",
		"validacao" => array("required" => "idpasta_vazio"),
		"banco" => true
	  ),
        array(
            "id" => "form_ebook",
            "nome" => "ebook",
            "nomeidioma" => "form_ebook",
            "tipo" => "select",
            "array" => "sim_nao",
            "sem_primeira_linha" => true,
            "valor" => "ebook",
            "class" => "span2",
            "banco" => true,
            "banco_string" => true,
        ),
	  array(
		"id" => "form_descricao",
		"nome" => "descricao",
		"nomeidioma" => "form_descricao",
		"tipo" => "text",
		"valor" => "descricao",
		"class" => "span6",
		//"validacao" => array("required" => "descricao_vazio"),
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_arquivo", // Id do atributo HTML
		"nome" => "arquivo", // Name do atributo HTML
		"nomeidioma" => "form_arquivo", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp|doc|docx|pdf|xls|xlsx|ppt|pptx|txt',
		"ajudaidioma" => "form_arquivo_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido", "file_required" => "arquivo_vazio"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_downloads_arquivo",
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "arquivo", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true
	  ),
	  /*array(
		"id" => "form_imagem_exibicao", // Id do atributo HTML
		"nome" => "imagem_exibicao", // Name do atributo HTML
		"nomeidioma" => "form_imagem_exibicao", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp',
		"ajudaidioma" => "form_exibir_ava_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_downloads_imagem_exibicao",
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "imagem_exibicao", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true
	  ),*/
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
		"id" => "idava_download", // Id do atributo HTML
		"nome" => "idava", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["3"];',
		"banco" => true
	  ),
	)
  )
);

$config["listagem_downloads"] = array(
  array(
	"id" => "iddownload",
	"variavel_lang" => "tabela_iddownload",
	"tipo" => "banco",
	"coluna_sql" => "iddownload",
	"valor" => "iddownload",
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
	"id" => "pasta",
	"variavel_lang" => "tabela_pasta",
	"tipo" => "banco",
	"coluna_sql" => "d.idpasta",
	"tamanho" => "120",
	"valor" => "pasta",
	"busca" => true,
	"busca_tipo" => "select",
	"busca_class" => "inputPreenchimentoCompleto",
	"busca_sql" => "SELECT idpasta, nome FROM avas_downloads_pastas WHERE ativo = 'S' and idava = ".$url[3], // SQL que alimenta o select
	"busca_sql_valor" => "idpasta", // Coluna da tabela que será usado como o valor do options
	"busca_sql_label" => "nome",
	"busca_metodo" => 1
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
	"id" => "opcoes",
	"variavel_lang" => "tabela_opcoes",
	"tipo" => "php",
	"valor" => 'return "<a class=\"btn dropdown-toggle btn-mini\" data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\" href=\"/".$this->url["0"]."/".$this->url["1"]."/".$this->url["2"]."/".$this->url["3"]."/".$this->url["4"]."/".$linha["iddownload"]."/opcoes\" data-placement=\"left\" rel=\"tooltip facebox\">".$idioma["tabela_opcoes"]."</a>"',
	"busca_botao" => true,
	"tamanho" => "80"
  )
 );

$linhaObj->Set("config",$config);
include("../classes/avas.downloads.class.php");

$linhaObj = new Downloads();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_downloads"]);
$linhaObj->Set("idava",intval($url[3]));

$linhaObj->config["banco"] = $config["banco_downloads"];
$linhaObj->config["formulario"] = $config["formulario_downloads"];
$linhaObj->config["tamanho_upload_padrao"] = 16777216;

if($_POST["acao"] == "salvar_download"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  if($_FILES) {
	foreach($_FILES as $ind => $val) {
	  $_POST[$ind] = $val;
	}
  }

  $linhaObj->Set("post",$_POST);
  if($_POST[$config["banco_downloads"]["primaria"]])
	$salvar = $linhaObj->ModificarDownload();
  else
	$salvar = $linhaObj->CadastrarDownload();

  if($salvar["sucesso"]){
	if($_POST[$config["banco_downloads"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_download") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverDownload();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif ('criarpasta' == $url[5]) {
  echo $linhaObj->CadastrarPasta();
  exit;
} elseif ('removerpasta' == $url[5]) {
  echo $linhaObj->RemoverPasta();
  exit;
} elseif ('renomearpasta' == $url[5]) {
  echo $linhaObj->ModificarPasta();
  exit;
}

if(isset($url[5])){
  if($url[5] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
	include("idiomas/".$config["idioma_padrao"]."/formulario.downloads.php");
	include("telas/".$config["tela_padrao"]."/formulario.downloads.php");
	exit();
  } else {
	$linhaObj->Set("id",intval($url[5]));
	$linhaObj->Set("campos","d.*, a.nome as ava");
	$linha = $linhaObj->RetornarDownload();

	if($linha) {
	  switch($url[6]) {
		case "editar":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.downloads.php");
		  include("telas/".$config["tela_padrao"]."/formulario.downloads.php");
		break;
		case "remover":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		  include("idiomas/".$config["idioma_padrao"]."/remover.downloads.php");
		  include("telas/".$config["tela_padrao"]."/remover.downloads.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.downloads.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.downloads.php");
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
  $listaDePastas = $linhaObj->ListarTodasPastas();

  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco_downloads"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","d.*, a.nome as ava, p.nome as pasta");
  $dadosArray = $linhaObj->ListarTodasDownload();
  include("idiomas/".$config["idioma_padrao"]."/index.downloads.php");
  include("telas/".$config["tela_padrao"]."/index.downloads.php");
}
?>
