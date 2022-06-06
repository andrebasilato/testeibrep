<?php		
if($url[1] == 'esqueci'){
  include("idiomas/".$config["idioma_padrao"]."/esqueci.php");
  function Processando($mensagem_idioma, $url){
	$informacoes["msg"] = $mensagem_idioma;
	$informacoes["url"] = $url;
	incluirLib("processando",$GLOBALS['config'],$informacoes);
	exit();
  }
			
  if($_POST['email'] and !empty($_POST['email'])){	
	$coreObj = new Core();
		
	$sql = "SELECT idprofessor, email, nome FROM professores WHERE email = '".$_POST['email']."' and ativo = 'S' LIMIT 1";
	$usuario = $coreObj->retornarLinha($sql);
	if($usuario["idprofessor"]){	
	  $hash = md5(uniqid());
	  
	  $sql = "update solicitacoes_senhas set ativo = 'N' where id = ".$usuario["idprofessor"]." and modulo = 'professor'";
	  $coreObj->executaSql($sql);
	  
	  $sql = "insert into solicitacoes_senhas set id = ".$usuario["idprofessor"].", modulo = 'professor', data_cad = now(),  hash = '".$hash."'";
	  $coreObj->executaSql($sql);	
	  							   
	  //$nomePara = utf8_decode($usuario["nome"]);
	  $nomePara = $usuario["nome"];
	  $message  = "Ol&aacute; <strong>".$usuario["nome"]."</strong>,
				  <br><br>
				  Voc&ecirc; solicitou o envio de uma nova senha de acesso.
				  <br><br>
				  Clique no link abaixo e modifique voc&ecirc; mesmo a sua senha.
				  <a href=\"http://".$_SERVER["SERVER_NAME"]."/novasenha/configuracoes/meusdados/professor/".$usuario["idprofessor"]."/".$hash."\">".$config["urlSistema"]."/novasenha/configuracoes/meusdados/professor/".$usuario["idprofessor"]."/".$hash."</a>
				  <br /><br />
				  O link estar&aacute; dispon&iacute;vel durante as pr&oacute;ximas 6 horas.<br />
				  Caso o link j&aacute; tenha expirado solicite novamente <a href=\"http://".$_SERVER["SERVER_NAME"]."/professor/esqueci\">clicando aqui</a>.
				  <br /><br />
				  Caso n&atilde;o tenha solicitado uma nova senha, gentileza desconsiderar esse e-mail.
				  <br />";
					 
	  $emailPara  = $usuario["email"];
	  //$assunto  = utf8_decode($idioma["assunto"]);	
	  $assunto  = $idioma["assunto"];
	  
	  $nomeDe = $config["tituloSistema"];
	  $emailDe = $config["emailEsqueci"];
			
	  $coreObj->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
	
	  Processando("mensagem_sucesso", "/".$url[0]."/".$url[1]."");
	} else {
	  Processando("usuario_nao_encontrado", "/".$url[0]."/".$url[1]."?erro");
	}
  }	
  include("telas/".$config["tela_padrao"]."/esqueci.php");
  exit();
} else {
  include("idiomas/".$config["idioma_padrao"]."/index.php");
  include("telas/".$config["tela_padrao"]."/index.php");
  exit();
}

?>