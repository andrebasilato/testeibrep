<?php

	include("../classes/perguntaspesquisas.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Perguntas_Pesquisas();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


	if($_POST["acao"] == "salvar"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
	
		if($_POST["acao_url"]){
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
		}else{
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
		}
		
		if($_POST["tipo"] == "S") {
			unset($config["formulario"][0]["campos"][2]["validacao"]);
			$linhaObj->Set("config",$config);
		}
		
		$linhaObj->Set("post",$_POST);
		if ($_POST[$config["banco"]["primaria"]]){
			$linhaObj->Set("id", $_POST[$config["banco"]["primaria"]]);
			$salvar = $linhaObj->Modificar();
			if ($_POST["tipo"] == "O") $pergunta = $linhaObj->retornarUltimaPerguntaDeUsuario(2);
		} else {
			if ($_POST["tipo"] == "O") $pergunta = $linhaObj->retornarUltimaPerguntaDeUsuario(1);
			$salvar = $linhaObj->Cadastrar();
		}
		
		if($salvar["sucesso"]){
			if ($_POST["tipo"] == "O") $url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];//."/".$pergunta['id']."/editar"
			
			if($_POST[$config["banco"]["primaria"]]) {
				$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
				$linhaObj->Set("url",$url_redireciona);
			} else {
				$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
				$linhaObj->Set("url",$url_redireciona);
			}
			$linhaObj->Processando();
		}
	}elseif($_POST["acao"] == "remover"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		$linhaObj->Set("post",$_POST);
		
		$respondida = $linhaObj->verificaPerguntaRespondida($url[3]);
		if (!$respondida)
			$remover = $linhaObj->Remover();
		else {
			$erros['alerta_pergunta_respondida'] = 'alerta_pergunta_respondida';
		}
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "inserir_opcao"){ 
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

		$salvar = $linhaObj->cadastrarOpcao(intval($url[3]), $_POST["numero"], $_POST["titulo"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","editar_opcoes_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/editar_opcoes");
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "remover_opcao"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->removerOpcao();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_opcao_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/editar_opcoes");
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "editar_opcao"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		
		$linhaObj->Set("post",$_POST);
		$editar = $linhaObj->editarOrdemOpcoes(intval($url[3]));
		
		if($editar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","editar_opcao_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/editar_opcoes");
			$linhaObj->Processando();
		}
	}  
	
	if($_POST["acao"] == "filtrar_grafico"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		$dados_grafico = $linhaObj->ListarPerguntaGrafico(intval($url[3]), $_POST['idpesquisa'],$_POST['idempreendimento'], $_POST['de'], $_POST['ate']);
	} else {	
		$de = date("d/m/Y", mktime(date("H"), date("i"), date("s"), date("m") - 3, date("d"), date("Y")));
		$ate = date("d/m/Y");
		$dados_grafico = $linhaObj->ListarPerguntaGrafico(intval($url[3]), "", "", $de, $ate);	
	}
	
	if(isset($url[3])){	
		
		if($url[3] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {
			
			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("campos","*");	
			$linha = $linhaObj->Retornar();
			
			if($linha) {
				
				switch ($url[4]) {
					case "editar":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						include("idiomas/".$config["idioma_padrao"]."/formulario.php");
						include("telas/".$config["tela_padrao"]."/formulario.php");
						break;
					case "remover":		
						$respondida = $linhaObj->verificaPerguntaRespondida($url[3]);

						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
					case "opcoes":			
						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
						break;
					case "editar_opcoes":
						if ($linha['tipo'] == 'O') {			
							$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
							$linhaObj->Set("ordem_campo","titulo");
							$linhaObj->Set("campos","*");	
							$opcoesArrayAss = $linhaObj->ListarOpcoes($url[3]);
							include("idiomas/".$config["idioma_padrao"]."/editar_opcoes.php");
							include("telas/".$config["tela_padrao"]."/editar_opcoes.php");	
							break;						
						} else {
							header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
					   		exit();		
						}						
					case "json":
						include("idiomas/".$config["idioma_padrao"]."/json.php");
						include("telas/".$config["tela_padrao"]."/json.php");
						break;
					case "grafico_respostas":
						if ($linha['tipo'] == 'O') {
							$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
							$linhaObj->Set("ordem_campo","pp.idpesquisa");
							$linhaObj->Set("campos","pp.*, p.nome as pesquisa");	
							$pesquisasArrayAss = $linhaObj->ListarPesquisasPergunta($url[3]);
						
							include("idiomas/".$config["idioma_padrao"]."/grafico_respostas.php");
							include("telas/".$config["tela_padrao"]."/grafico_respostas.php");
							break;
						} else {
							header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
					   		exit();		
						}
					case "ajax_empreendimentos":
						include("telas/".$config["tela_padrao"]."/ajax_empreendimentos.php");
						break;
					default:
					   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
					   exit();
				}
				
			} else {
			   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
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
		$linhaObj->Set("campos","*");	
		$dadosArray = $linhaObj->ListarTodas();		
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>