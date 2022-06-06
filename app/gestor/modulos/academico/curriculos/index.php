<?php
include("../classes/curriculos.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Curriculos();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

if($_POST["acao"] == "salvar"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
  $linhaObj->Set("post",$_POST);
  if($_POST[$config["banco"]["primaria"]])
	$salvar = $linhaObj->Modificar();
  else
	$salvar = $linhaObj->Cadastrar();
  if($salvar["sucesso"]){
	if($_POST[$config["banco"]["primaria"]]) {
	  $linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
	  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
	}
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->Remover();
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "cadastrar_avaliacao"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->CadastrarAvaliacao();

  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","cadastrar_avaliacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_avaliacao"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");

  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverAvaliacao();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_avaliacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "cadastrar_bloco"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->CadastrarBloco();

  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","cadastrar_bloco_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_bloco"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");

  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverBloco();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_bloco_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "editar_bloco"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $editar = $linhaObj->ModificarBlocos();

  if($editar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","editar_bloco_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "cadastrar_disciplina"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
  $linhaObj->Set("id",intval($url[3]));

  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->CadastrarDisciplina();

  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","cadastrar_disciplina_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_disciplina"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");

  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverDisciplina();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_disciplina_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "editar_disciplina"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $editar = $linhaObj->ModificarDisciplinas();

  if($editar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","editar_disciplina_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif ($_POST['acao'] == 'salvar_arquivoscursos') {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|14");
	include("idiomas/".$config["idioma_padrao"]."/arquivoscursos.php");
	$linhaObj->Set("post",$_POST);
	$salvar = $linhaObj->enviarArquivosCursos(intval($url[3]), $_FILES, $erros);

	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","arquivos_cursos_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/arquivos_cursos");
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "remover_arquivoscursos"){

	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
	$remover = $linhaObj->removerArquivosCursos($_POST['remover'],intval($url[3]));

	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_arquivoscursos_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/arquivos_cursos");
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "associar_tipo_nota") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");		  
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->AssociarTipoNota();
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","associar_tipo_nota_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_tipo_nota") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|18");  
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->DesassociarTipoNota();
		
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_tipo_nota_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  }
} 


if(isset($url[3])){
  if($url[3] == "cadastrar") {
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	include("idiomas/".$config["idioma_padrao"]."/formulario.php");
	include("telas/".$config["tela_padrao"]."/formulario.php");
	exit();
  } else {
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","ca.*, c.nome as curso");
	$linha = $linhaObj->Retornar();

	if($linha) {
	  switch ($url[4]) {
		case "dadosgerais":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.php");
		  include("telas/".$config["tela_padrao"]."/formulario.php");
		break;
		case "remover":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		  include("idiomas/".$config["idioma_padrao"]."/remover.php");
		  include("telas/".$config["tela_padrao"]."/remover.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.php");
		break;
		case "resumo":
		  include("idiomas/".$config["idioma_padrao"]."/resumo.php");
		  include("telas/".$config["tela_padrao"]."/resumo.php");
		break;
		case "avaliacoes":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");

		  $linhaObj->Set("limite","-1");
		  $linhaObj->Set("ordem","asc");
		  $linhaObj->Set("ordem_campo","avaliacao");
		  $linhaObj->Set("campos","*");
		  $avaliacoes = $linhaObj->ListarTodasAvaliacoes();

		  include("idiomas/".$config["idioma_padrao"]."/avaliacoes.php");
		  include("telas/".$config["tela_padrao"]."/avaliacoes.php");
		break;
		case "blocos":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");

		  $linhaObj->Set("limite","-1");
		  $linhaObj->Set("ordem","asc");
		  $linhaObj->Set("ordem_campo","ordem");
		  $linhaObj->Set("campos","*");
		  $blocos = $linhaObj->ListarTodasBlocos();

		  include("idiomas/".$config["idioma_padrao"]."/blocos.php");
		  include("telas/".$config["tela_padrao"]."/blocos.php");
		break;
		case "disciplinas":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");

		  include("../classes/disciplinas.class.php");
		  $linhaObjDisc = new Disciplinas();
		  $disciplinas = $linhaObjDisc->ListarDisciplinasPorCurso($linha["idcurso"]);

		  $linhaObj->Set("limite","-1");
		  $linhaObj->Set("ordem","asc");
		  $linhaObj->Set("ordem_campo","avaliacao");
		  $linhaObj->Set("campos","*");
		  $avaliacoes = $linhaObj->ListarTodasAvaliacoes();

		  $linhaObj->Set("limite","-1");
		  $linhaObj->Set("ordem","asc");
		  $linhaObj->Set("ordem_campo","ordem");
		  $linhaObj->Set("campos","*");
		  $blocos = $linhaObj->ListarTodasBlocos();
		  foreach($blocos as $ind => $bloco) {
			$bloco["disciplinas"] = array();
			$linhaObj->Set("limite","-1");
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("ordem_campo","cbd.ordem");
			$linhaObj->Set("campos","cbd.*, d.nome as disciplina, a.nome as ava");
			$blocos[$ind]["disciplinas"] = $linhaObj->ListarTodasDisciplinas($bloco["idbloco"]);
		  }

		  include("../classes/avas.class.php");
		  $linhaObjAva = new Ava();

		  include("idiomas/".$config["idioma_padrao"]."/disciplinas.php");
		  include("telas/".$config["tela_padrao"]."/disciplinas.php");
		break;
		case "arquivos_cursos":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");
			$materiaisArray = $linhaObj->retornaArquivosCursos(intval($url[3]));
			include("idiomas/".$config["idioma_padrao"]."/arquivoscursos.php");
			include("telas/".$config["tela_padrao"]."/arquivoscursos.php");
			break;
		case "visualiza_imagem_arquivocurso":
			$arquivo = $linhaObj->retornaArquivosCursosDownload($url[3], $url[5]);
			include("telas/".$config["tela_padrao"]."/visualiza_imagem.curso.php");
			break;
		case "download_arquivocurso":
			$arquivo = $linhaObj->retornaArquivosCursosDownload($url[3], $url[5]);
			include("telas/".$config["tela_padrao"]."/download.curso.php");
			break;
		case "tipos_notas":
		    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16");
		    $linhaObj->Set("id",intval($url[3]));
		    $linhaObj->Set("campos","*");	
		    $tipos_notas = $linhaObj->ListarTiposNotasAssociados();
		    include("idiomas/".$config["idioma_padrao"]."/tipos_notas.php");
		    include("telas/".$config["tela_padrao"]."/tipos_notas.php");
		break;
		case "json":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
			include("idiomas/".$config["idioma_padrao"]."/json.php");
			include("telas/".$config["tela_padrao"]."/json.php");
			break;
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
		  exit();
	  }
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
	  exit();
	}
  }
} else {
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","ca.*, c.nome as curso");
  $dadosArray = $linhaObj->ListarTodas();
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
?>