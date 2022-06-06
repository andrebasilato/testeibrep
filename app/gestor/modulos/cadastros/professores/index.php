<?php
include("../classes/professores.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");


//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

$linhaObj = new Professores();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


if($_POST["acao"] == "salvar"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

  if($_FILES) {
    foreach($_FILES as $ind => $val) {
     $_POST[$ind] = $val;
    }
  }

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
} elseif($_POST["acao"] == "resetar_senha"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  $salvar = $linhaObj->ResetarSenha();

  if(!$salvar["sucesso"]){
	if($salvar["tela_senha"]) {
	  $linhaObj->Set("pro_mensagem_idioma","sucesso_senha_tela");
	} else {
	  $linhaObj->Set("pro_mensagem_idioma","sucesso");
	}
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/resetar_senha");
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "associar_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
	$salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"]);

	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","associar_curso_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_cursos");
		$linhaObj->Processando();
	}
}  elseif($_POST["acao"] == "remover_associacao_curso"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->DesassociarCursos();

	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_curso_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_cursos");
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "associar_ava"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
	$salvar = $linhaObj->AssociarAvas(intval($url[3]), $_POST["avas"]);

	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","associar_ava_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_avas");
		$linhaObj->Processando();
	}
}  elseif($_POST["acao"] == "remover_associacao_ava"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->DesassociarAvas();

	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_ava_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_avas");
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "associar_oferta"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
	$salvar = $linhaObj->AssociarOfertas(intval($url[3]), $_POST["ofertas"]);

	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","associar_oferta_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_ofertas");
		$linhaObj->Processando();
	}
}  elseif($_POST["acao"] == "remover_associacao_oferta"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->DesassociarOfertas();

	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_oferta_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_ofertas");
		$linhaObj->Processando();
	}
} elseif($_POST["acao"] == "associar_disciplina"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");
	$salvar = $linhaObj->AssociarDisciplinas(intval($url[3]), $_POST["disciplinas"]);

	if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","associar_disciplina_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_disciplinas");
		$linhaObj->Processando();
	}
}  elseif($_POST["acao"] == "remover_associacao_disciplina"){
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|14");
	$linhaObj->Set("post",$_POST);
	$remover = $linhaObj->DesassociarDisciplinas();

	if($remover["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","remover_disciplina_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/professor_disciplinas");
		$linhaObj->Processando();
	}
} if($_POST["acao"] == "adicionar_arquivo") {
  $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|16');
  $adicionar = $linhaObj->set('id', $url[3])
                              ->set('post', $_POST)
                              ->adicionarArquivo();

  if($adicionar["sucesso"]){
    $linhaObj->set("pro_mensagem_idioma", $adicionar["mensagem"]);
    $linhaObj->set("url", "/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
    $linhaObj->Processando();
  } else {
    $mensagem["erro"] = $adicionar["mensagem"];
  }
} elseif($_POST["acao"] == "remover_arquivo") {
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17");

	$linhaObj->Set("id", $url[3]);
	$linhaObj->Set("idarquivo", $_POST["idarquivo"]);
	$remover = $linhaObj->removerArquivo();

  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
	$linhaObj->Processando();
  } else {
	$mensagem["erro"] = $remover["mensagem"];
  }
}

if(isset($url[3])){
  if($url[4] == "ajax_cidades"){
	if($_REQUEST['idestado']) {
	  $linhaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
	} else {
	  $linhaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
	}
	exit;
  } elseif($url[3] == "cadastrar") {
	if($url[4] == "json") {
		include("idiomas/".$config["idioma_padrao"]."/json.php");
		include("telas/".$config["tela_padrao"]."/json.php");
		exit;
	} else {
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		include("idiomas/".$config["idioma_padrao"]."/formulario.php");
		include("telas/".$config["tela_padrao"]."/formulario.php");
		exit();
	}
  } else {
	$linhaObj->Set("id",intval($url[3]));
	$linhaObj->Set("campos","p.*, pa.nome as pais");
	$linha = $linhaObj->Retornar();

	if($linha) {
	  switch ($url[4]) {
		case "editar":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		  include("idiomas/".$config["idioma_padrao"]."/formulario.php");
		  include("telas/".$config["tela_padrao"]."/formulario.php");
		break;
		case "acessarcomo":

		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12");

		  $_SESSION["usu_professor_email"] 				= $linha["email"];
		  $_SESSION["usu_professor_senha"] 				= $linha["senha"];
		  $_SESSION["usu_professor_idprofessor"] 		= $linha["idprofessor"];
		  $_SESSION["usu_professor_nome"] 			    = $linha["nome"];
		  $_SESSION["usu_professor_ultimoacesso"] 	    = $linha["ultimo_acesso"];
		  $_SESSION["usu_professor_gestor"] 			= $usuario["idusuario"];

		  $linhaObj->Set("monitora_oque","9");
		  $linhaObj->Set("monitora_qual",$linha["idprofessor"]);
		  $linhaObj->Set("monitora_dadosnovos",null);
		  $linhaObj->Monitora();

		  $linhaObj->Set("url","/professor");
		  $linhaObj->Processando();

		break;
		case "remover":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		  include("idiomas/".$config["idioma_padrao"]."/remover.php");
		  include("telas/".$config["tela_padrao"]."/remover.php");
		break;
		case "desativar_login":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
		  include("idiomas/".$config["idioma_padrao"]."/desativar_login.php");
		  include("telas/".$config["tela_padrao"]."/desativar_login.php");
		break;
		case "download":
			$linhaObj->Set("id", intval($url[3]));
			$download = $linhaObj->retornarAvatar();
			include("telas/".$config["tela_padrao"]."/download.avatar.php");
		break;
		case "excluir":
			include("idiomas/".$config["idioma_padrao"]."/excluir_avatar.php");
			$linhaObj->RemoverImgAvatar("professores", "avatar", $linha, $idioma);
		break;
		case "resetar_senha":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
		  include("idiomas/".$config["idioma_padrao"]."/resetar_senha.php");
		  include("telas/".$config["tela_padrao"]."/resetar_senha.php");
		break;
		case "opcoes":
		  include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
		  include("telas/".$config["tela_padrao"]."/opcoes.php");
		break;
		case "professor_cursos":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");

			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("limite",-1);
			$linhaObj->Set("ordem_campo","nome");
			$linhaObj->Set("campos","pc.idprofessor_curso, pc.idprofessor, c.idcurso, c.nome");
			$associacoesArray = $linhaObj->ListarCursosAss();

			include("idiomas/".$config["idioma_padrao"]."/professor.cursos.php");
			include("telas/".$config["tela_padrao"]."/professor.cursos.php");
			break;
		case "professor_avas":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");

			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("limite",-1);
			$linhaObj->Set("ordem_campo","nome");
			$linhaObj->Set("campos","pa.idprofessor_ava, pa.idprofessor, a.idava, a.nome");
			$associacoesArray = $linhaObj->ListarAvasAss();

			include("idiomas/".$config["idioma_padrao"]."/professor.avas.php");
			include("telas/".$config["tela_padrao"]."/professor.avas.php");
			break;
		case "professor_ofertas":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");

			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("limite",-1);
			$linhaObj->Set("ordem_campo","nome");
			$linhaObj->Set("campos","po.idprofessor_oferta, po.idprofessor, o.idoferta, o.nome");
			$associacoesArray = $linhaObj->ListarOfertasAss();

			include("idiomas/".$config["idioma_padrao"]."/professor.ofertas.php");
			include("telas/".$config["tela_padrao"]."/professor.ofertas.php");
			break;
		case "professor_disciplinas":
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13");

			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("ordem","asc");
			$linhaObj->Set("limite",-1);
			$linhaObj->Set("ordem_campo","nome");
			$linhaObj->Set("campos","pd.idprofessor_disciplina, pd.idprofessor, d.iddisciplina, d.nome");
			$associacoesArray = $linhaObj->ListarDisciplinasAss();

			include("idiomas/".$config["idioma_padrao"]."/professor.disciplinas.php");
			include("telas/".$config["tela_padrao"]."/professor.disciplinas.php");
			break;
		case "json":
		  include("idiomas/".$config["idioma_padrao"]."/json.php");
		  include("telas/".$config["tela_padrao"]."/json.php");
		  exit;
		break;
		case "pastavirtual":

			if ($url[5] == 'downloadarquivo') {
				$linhaObj->Set("iddocumento", intval($url[6]));
			    $download = $linhaObj->retornarArquivo();
			    include("telas/".$config["tela_padrao"]."/download.arquivos.php");
				exit;
			} else if ($url[5] == 'visualizararquivo') {
				$download = $linhaObj->set('iddocumento', (int) $url[6])
								   ->retornarArquivo();
			    include("telas/".$config["tela_padrao"]."/visualizar.arquivos.php");
				exit;
			}

			$arquivos = $linhaObj->retornarListaArquivos();
			include("idiomas/".$config["idioma_padrao"]."/pastavirtual.php");
			include("telas/".$config["tela_padrao"]."/pastavirtual.php");
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
  $linhaObj->Set("campos","p.*, pa.nome as pais, c.nome as cidade, e.nome as estado");
  $dadosArray = $linhaObj->ListarTodas();
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
