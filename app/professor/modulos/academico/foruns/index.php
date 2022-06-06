<?php  
$config["formulario_foruns_topicos"] = array(
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
		"id" => "form_mensagem",
		"nome" => "mensagem",
		"nomeidioma" => "form_mensagem",
		"tipo" => "text", 
		"valor" => "mensagem",
		"class" => "span6",
		"validacao" => array("required" => "mensagem_vazio"),
		"banco" => true, 
		"banco_string" => true,
	  ),
	  /*array(
		"id" => "form_periode_de",
		"nome" => "periode_de",
		"nomeidioma" => "form_periode_de",
		"tipo" => "input", 
		"valor" => "periode_de",
		//"validacao" => array("required" => "periode_de_vazio"), 
		"valor_php" => 'if($dados["periode_de"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_periode_ate",
		"nome" => "periode_ate",
		"nomeidioma" => "form_periode_ate",
		"tipo" => "input", 
		"valor" => "periode_ate",
		//"validacao" => array("required" => "periode_ate_vazio"), 
		"valor_php" => 'if($dados["periode_ate"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
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
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_foruns_topicos_arquivo", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"]."/".$url["6"]."/".$url["7"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "arquivo", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
	  ),*/
	  array(
		"id" => "idforum_topico", // Id do atributo HTML
		"nome" => "idforum", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["5"];',
		"banco" => true
	  ),
	  array(
		"id" => "idprofessor_topico", // Id do atributo HTML
		"nome" => "idprofessor", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => '',
		"banco" => true
	  ),
	)
  )								  
);

include("config.php");
include("config.listagem.php");	
include("../classes/avas.class.php");						   
include("../classes/avas.foruns.class.php");
$linhaObj = new Foruns();
//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|25");	
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
$linhaObj->Set("config",$config);
$linhaObj->Set("idprofessor",$usu_professor["idprofessor"]);
$linhaObj->Set("modulo","professor");


if(isset($url[3])){
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("campos","f.*, a.nome as ava, d.nome as disciplina");	
  $forum = $linhaObj->RetornarForum();
  if($forum) {
	
	if($_POST["acao"] == "salvar_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|1");
			
	  $linhaObj->Set("monitora_onde",$config["monitoramento"]["onde_foruns_topicos"]);
	  $linhaObj->Set("idava",intval($forum['idava']));
	  $linhaObj->Set("id",intval($url[3]));
	
	  $linhaObj->config["banco"] = $config["banco_foruns_topicos"];
	  $linhaObj->config["formulario"] = $config["formulario_foruns_topicos"];

	  $linhaObj->Set("post",$_POST);		
	  /*if($_POST[$config["banco_foruns_topicos"]["primaria"]]) 
		$salvar = $linhaObj->ModificarTopico();
	  else*/ 
		$salvar = $linhaObj->CadastrarTopico();
	  
	  if($salvar["sucesso"]){
		if($_POST[$config["banco_foruns_topicos"]["primaria"]])  {
		  $linhaObj->Set("pro_mensagem_idioma","modificar_topico_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		} else {
		  $linhaObj->Set("pro_mensagem_idioma","cadastrar_topico_sucesso");
		  $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
		}
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "responder_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|2");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ResponderTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","responder_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "moderar_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ModerarTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","moderar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "moderar_mensagem"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
	  $linhaObj->Set("post",$_POST);		
	  $salvar = $linhaObj->ModerarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","moderar_mensagem_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "ocultar_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|4");
	  $salvar = $linhaObj->ocultarTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","ocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desocultar_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|4");
	  $salvar = $linhaObj->desocultarTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "ocultar_mensagem"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|1");
	  $salvar = $linhaObj->ocultarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","ocultar_mensagem_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desocultar_mensagem"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|1");
	  $salvar = $linhaObj->desocultarMensagem(intval($_POST["idmensagem"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desocultar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "bloquear_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|3");
	  $salvar = $linhaObj->bloquearTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","bloquear_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desbloquear_topico"){
	  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|3");
	  $salvar = $linhaObj->desbloquearTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desbloquear_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "assinar_topico"){
	  $salvar = $linhaObj->assinarTopico(intval($url[5]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","assinar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	} elseif($_POST["acao"] == "desassinar_topico"){
	  $salvar = $linhaObj->desassinarTopico(intval($_POST["idassinatura"]));
	  
	  if($salvar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma","desassinar_topico_sucesso");
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]);
		$linhaObj->Processando();
	  }
	}
	
	switch($url[4]) {
	  case "topicos":	
		if(isset($url[5])){			
		  if($url[5] == "cadastrar") {
			$linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|1");
			include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.formulario.php");
			include("telas/".$config["tela_padrao"]."/foruns.topicos.formulario.php");
			exit;
		  } else {	
			$linhaObj->Set("campos","f.*, a.nome as ava");	
			$topico = $linhaObj->RetornarTopico(intval($url[5]));

			if($topico) {
			  switch($url[6]) {
				case "moderar":			
				  $linhaObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
				  include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.formulario.php");
				  include("telas/".$config["tela_padrao"]."/foruns.topicos.formulario.php");
				break;
				break;
				case "mensagens":		
				  if($url[7] == "cadastrar" || ($url[7] && ($url[8] == "responder" || $url[8] == "moderar"))) {
					if($url[7] && $url[8] == "moderar") {
					  $linhaObj->Set("campos","*");	
					  $mensagem = $linhaObj->RetornarMensagem(intval($url[7]));
					}
					include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.mensagens.formulario.php");
					include("telas/".$config["tela_padrao"]."/foruns.topicos.mensagens.formulario.php");
					exit;
				  } elseif($url[7] == "json" && $url[8] == "curtir") {
					$linhaObj->Set("post",$_POST);
					echo $linhaObj->CurtirTopicoMensagem();
					exit;
				  } elseif(isset($url[7]) && $url[8] == "download") {
					$linhaObj->Set("campos","*");	
					$mensagem = $linhaObj->RetornarMensagem(intval($url[7]));
					$linhaObj->countabilizarDownloadMensagem($mensagem["idmensagem"]);
					include("telas/".$config["tela_padrao"]."/download.foruns.topicos.mensagens.php");
				  } else {
					$assinatura = $linhaObj->verificaAssinaturaTopico($topico["idtopico"],null,$usu_professor["idprofessor"],null);
					
					$respostas = $linhaObj->ListarTodasMensagens($topico["idtopico"], true);
					
					$participantes = $linhaObj->ParticipantesTopico($topico["idtopico"]);
					
					include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.mensagens.php");
					include("telas/".$config["tela_padrao"]."/foruns.topicos.mensagens.php");
					exit;
				  }
				break;
				case "download":
				  $linhaObj->countabilizarDownloadTopico($topico["idtopico"]);
				  include("telas/".$config["tela_padrao"]."/download.foruns.topicos.php");
				break;
				case "excluir":
				  include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
				  $linhaObj->RemoverArquivo("avas_".$url[2]."_".$url[4], $url[7], $topico, $idioma);
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
		  if(!$_GET["pag"]) $_GET["pag"] = 1;
		  $linhaObj->Set("pagina",$_GET["pag"]);
		  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		  $linhaObj->Set("limite",intval($_GET["qtd"]));
		  $linhaObj->Set("ordem_campo","idtopico");
		  $linhaObj->Set("ordem","desc");
		  $linhaObj->Set("campos","*");	
		  $topicos = $linhaObj->ListarTodasTopico(intval($url[3]), $forum["permissoes"][$url[0]."|topicos|5"]);
		  
		  $populares = $linhaObj->ListarTopicosPopulares(intval($url[3]), $forum["permissoes"][$url[0]."|topicos|5"]);	

		  $alunosAtivos = $linhaObj->ListarAlunosAtivos(intval($url[3]));
			  
		  include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.php");
		  include("telas/".$config["tela_padrao"]."/foruns.topicos.php");
		  exit;
		}
	  break;	
	  case "download":
		include("telas/".$config["tela_padrao"]."/download.foruns.php");
	  break;
	  case "excluir":
		include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
		$linhaObj->RemoverArquivo("avas_".$url[2], $url[5], $forum, $idioma);
	  break;	
	  default:
		header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
		exit();
	}				
  } else {
	header("Location: /".$url[0]."/".$url[1]."/".$url[2]);
	exit();
  }
} else {  
  $buscaIdForum = $_GET['q']['1|idforum'];
  $buscaNomeForum = $_GET['q']['2|f.nome'];
  unset($_GET['q']['1|idforum']);  
  unset($_GET['q']['2|f.nome']); 
  
  include("../classes/professores.class.php");
  $professorObj = new Professores();
  $professorObj->Set("id",$usu_professor["idprofessor"]);
  $professorObj->Set("ordem","desc");
  $professorObj->Set("limite","-1");
  $professorObj->Set("ordem_campo","a.idava");
  $professorObj->Set("campos","a.idava");	
  $avasProfessor = $professorObj->ListarAvasAss();
  
  $arrayAvasProfessor = array();
  foreach($avasProfessor as $avaProfessor) {
	$arrayAvasProfessor[] = $avaProfessor['idava'];
  }
  $avasProfessor = implode(',',$arrayAvasProfessor); 
  
  $_GET['q']['1|idforum'] = $buscaIdForum;
  $_GET['q']['2|f.nome'] = $buscaNomeForum;
  
  $linhaObj->Set("pagina",$_GET["pag"]);
  if(!$_GET["ordem"]) $_GET["ordem"] = "desc";
  $linhaObj->Set("ordem",$_GET["ord"]);
  if(!$_GET["qtd"]) $_GET["qtd"] = 30;
  $linhaObj->Set("limite",intval($_GET["qtd"]));
  if(!$_GET["cmp"]) $_GET["cmp"] = "idforum";
  $linhaObj->Set("ordem_campo",$_GET["cmp"]);
  $linhaObj->Set("campos","f.*, a.nome as ava, d.nome as disciplina");	
  if(!$avasProfessor) $_GET['idavas'] = 0; else $_GET['idavas'] = $avasProfessor;
  $_GET['q']['4|f.periode_de'] = date('Y-m-d');
  $_GET['q']['5|f.periode_ate'] = date('Y-m-d');
  $foruns = $linhaObj->ListarTodasForum();
  unset($_GET['idavas']);  
  unset($_GET['q']['4|f.periode_de']);
  unset($_GET['q']['5|f.periode_ate']);  
  
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
}
?>