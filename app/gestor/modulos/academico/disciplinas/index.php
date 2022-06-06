<?php
require '../classes/disciplinas.class.php';
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';

//Incluimos o arquivo com variaveis padrÃ£o do sistema.
require 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';
	
$linhaObj = new Disciplinas;
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');	
	
$linhaObj->Set('idusuario', $usuario['idusuario']);
$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);

// Todas acões descritas nesse array serão redirecionadas para
// o banco de perguntas
$acoesRedirecionadas = array(
    'salvarpergunta',    'cadastrar_opcao',
    'remover_opcao',     'editar_opcao',
    'json_perguntas'
);

if (in_array($_POST['acao'], $acoesRedirecionadas)) {
    include 'bancodeperguntas/index.php';
    exit;
}

//filtra de onde veio a requisição
$requisicaoDePergunta = (boolean) strpos(
	$_SERVER['HTTP_REFERER'],
	'cadastrar'
);

if ($requisicaoDePergunta) {
	switch ($url[4]) {
		case 'opcoes':
			include  'bancodeperguntas/index.php';
			exit;
	}
}
 
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
} elseif($_POST["acao"] == "associar_curso"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
  $salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"]);
  
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos");
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_curso"){  
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");		
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverCursos();
  
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/cursos");
	$linhaObj->Processando();
  }
}elseif($_POST["acao"] == "associar_perguntas"){
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
  $salvar = $linhaObj->AssociarPerguntas(intval($url[3]), $_POST["perguntas"]);
  
  if($salvar["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","associar_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/perguntas");
	$linhaObj->Processando();
  }
} elseif($_POST["acao"] == "remover_pergunta"){  
  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");		
  $linhaObj->Set("post",$_POST);
  $remover = $linhaObj->RemoverPerguntas();
  
  if($remover["sucesso"]){
	$linhaObj->Set("pro_mensagem_idioma","remover_associacao_sucesso");
	$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/perguntas");
	$linhaObj->Processando();
  }
}	

if(isset($url[3])){
  
	if ('editarpergunta' == $url[4]
		|| 'removerpergunta' == $url[4]
		|| 'perguntaopcoes' == $url[4]) {
	    include 'bancodeperguntas/index.php';
		exit;
	}

	if ('cadastrar' == $url[3]) {
		$linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
		include 'idiomas/'.$config['idioma_padrao'].'/formulario.php';
		include 'telas/'.$config['tela_padrao'].'/formulario.php';
		exit;
	} else {

	$linhaObj->Set('id', (int) $url[3]);
	$linhaObj->Set('campos', '*');	
	$linha = $linhaObj->Retornar();
			
	if($linha) {				
	  switch ($url[4]) {
		case 'editar':			
		  $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');
		  include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
		  include('telas/'.$config['tela_padrao'].'/formulario.php');
		break;
		case 'remover':			
		  $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');
		  include('idiomas/'.$config['idioma_padrao'].'/remover.php');
		  include('telas/'.$config['tela_padrao'].'/remover.php');
		break;
		case 'opcoes':
		  include('idiomas/'.$config['idioma_padrao'].'/opcoes.php');
		  include('telas/'.$config['tela_padrao'].'/opcoes.php');
		break;
		case 'cursos':
		  $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');
		  
		  $linhaObj->Set('id', intval($url[3]));
		  $linhaObj->Set('ordem', 'asc');
		  $linhaObj->Set('limite', -1);
		  $linhaObj->Set('ordem_campo', 'nome');
		  $linhaObj->Set('campos', 'dc.iddisciplina_curso, dc.iddisciplina, c.idcurso, c.nome');
		  $cursos = $linhaObj->ListarCursosAssociados();
  
		  include("idiomas/".$config["idioma_padrao"]."/cursos.php");
		  include("telas/".$config["tela_padrao"]."/cursos.php");
		break;
		case "perguntas":
		  $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
		  
		  $linhaObj->Set("id",intval($url[3]));
		  $linhaObj->Set("ordem","asc");
		  $linhaObj->Set("limite",-1);
		  $linhaObj->Set("ordem_campo","nome");
		  $linhaObj->Set("campos","dp.iddisciplina_pergunta, dp.iddisciplina, p.idpergunta, p.nome");
		  $perguntas = $linhaObj->ListarPerguntasAssociados();
  
		  include("idiomas/".$config["idioma_padrao"]."/perguntas.php");
		  include("telas/".$config["tela_padrao"]."/perguntas.php");
		break;
        case 'novapergunta':
        case 'cadastrar':
        case 'editarpergunta':
        case 'perguntaopcoes':
            include 'bancodeperguntas/index.php';
            exit;
            break;
		case "json":
		  include("idiomas/".$config["idioma_padrao"]."/json.php");
		  include("telas/".$config["tela_padrao"]."/json.php");
		break;		
		default:
		  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
		  exit;
	  }	
	} else {
	  header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
	  exit;
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
  $linhaObj->Set("campos","*");	
  $dadosArray = $linhaObj->ListarTodas();		
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}