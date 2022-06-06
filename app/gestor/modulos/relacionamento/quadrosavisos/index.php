<?php
	
	include("../classes/quadrosavisos.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Quadros_Avisos();
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
		
	}elseif ($_POST['acao'] == 'salvar_imagens') {
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
		include("idiomas/".$config["idioma_padrao"]."/imagens.php");
		$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
		$linhaObj->Set("id",$url[3]);
		$linhaObj->Set("files",$_FILES);
		$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
		$salvar = $linhaObj->CadastrarImagens($erros);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","imagem_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
			$linhaObj->Processando();
		} 
	}elseif($_POST["acao"] == "remover_imagem"){

		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
		$linhaObj->Set("id",$_POST['remover']);
		$remover = $linhaObj->RemoverImagens();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_imagem_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/imagens");
			$linhaObj->Processando();
		}
	}elseif($_POST["acao"] == "remover"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->Remover();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}/*elseif($_POST["acao"] == "salvar_arquivos"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
		$linhaObj->Set("id",intval($url[3]));
		$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
		$linhaObj->Set("files",$_FILES);
		$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
		$salvar = $linhaObj->CadastrarArquivos($erros);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","arquivo_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/arquivos");
			$linhaObj->Processando();
		}
		
	}elseif($_POST["acao"] == "remover_arquivos"){
				
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->RemoverArquivo($erros);
				
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_arquivo_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/arquivos");
			$linhaObj->Processando();
		}

	}*/	elseif($_POST["acao"] == "associar_oferta"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");	
		$salvar = $linhaObj->AssociarOfertas(intval($url[3]), $_POST["ofertas"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","associar_oferta_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_ofertas");
			$linhaObj->Processando();
		}
	}  elseif($_POST["acao"] == "remover_associacao_oferta"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");	
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->DesassociarOfertas();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_oferta_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_ofertas");
			$linhaObj->Processando();
		}	
	}	elseif($_POST["acao"] == "associar_escola"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");	
		$salvar = $linhaObj->AssociarEscolas(intval($url[3]), $_POST["escolas"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","associar_escola_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_escolas");
			$linhaObj->Processando();
		}
	}  elseif($_POST["acao"] == "remover_associacao_escola"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");	
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->DesassociarEscolas();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_escola_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_escolas");
			$linhaObj->Processando();
		}	
	}elseif($_POST["acao"] == "associar_curso"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");	
		$salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"]);

		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","associar_curso_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_cursos");
			$linhaObj->Processando();
		}
	}  elseif($_POST["acao"] == "remover_associacao_curso"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");	
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->DesassociarCursos();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_curso_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/quadro_cursos");
			$linhaObj->Processando();
		}	
	}
	
	if(isset($url[3])){	
				
		if($url[4] == "json"){
			include("idiomas/".$config["idioma_padrao"]."/json.php");
			include("telas/".$config["tela_padrao"]."/json.php");
			exit();
		}
		
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
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","nome");
						$linhaObj->Set("campos","idquadro_imagem, nome");
						$associacoesImagensArray = $linhaObj->ListarImagens();
						/*$linhaObj->Set("limite",5);
						$linhaObj->Set("ordem_campo",'idquadro');
						$linhaObj->Set("campos","*");	
						$manualArquivosArray = $linhaObj->ListarTodosArquivos();*/
												
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
					case "imagens":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10");	
						$linhaObj->Set("id",intval($url[3]));
						$imagensArray = $linhaObj->RetornaImagens();
						include("idiomas/".$config["idioma_padrao"]."/imagens.php");
						include("telas/".$config["tela_padrao"]."/imagens.php");
						break;
					case "download":
						$linhaObj->Set("id",intval($url[5]));
						$imagem = $linhaObj->RetornarImagemDownload();
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "visualiza_imagem":					
						$linhaObj->Set("id",intval($url[5]));
						$imagem = $linhaObj->RetornarImagemDownload();
						include("telas/".$config["tela_padrao"]."/visualiza_imagem.php");
						break;	
					/*case "arquivos":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9");
						$linhaObj->Set("id",intval($url[3]));
						$arquivosArray = $linhaObj->RetornaArquivos();
						include("idiomas/".$config["idioma_padrao"]."/arquivos.php");
						include("telas/".$config["tela_padrao"]."/arquivos.php");
						break;						
					case "downloadArquivo":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11");
						$linhaObj->Set("id",intval($url[5]));
						$arquivo = $linhaObj->RetornarArquivoDownload();
						include("telas/".$config["tela_padrao"]."/download_arquivo.php");
						break;
					case "previewpopup":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7");
						$linhaObj->Set("id",intval($url[3]));
						$linha = $linhaObj->RetornarPreviewQuadro();
						include("idiomas/".$config["idioma_padrao"]."/preview.php");
						include("telas/".$config["tela_padrao"]."/preview.popup.php");
						break;*/
					case "quadro_ofertas":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
						
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","nome");
						$linhaObj->Set("campos","qao.idquadro_oferta, qao.idquadro, o.idoferta, o.nome");
						$associacoesArray = $linhaObj->ListarOfertasAss();
						
						include("idiomas/".$config["idioma_padrao"]."/quadro.ofertas.php");
						include("telas/".$config["tela_padrao"]."/quadro.ofertas.php");
						break;
					case "quadro_escolas":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
						
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","nome_fantasia");
						$linhaObj->Set("campos","qap.idquadro_escola, qap.idquadro, p.idescola, p.nome_fantasia");
						$associacoesArray = $linhaObj->ListarEscolasAss();
						
						include("idiomas/".$config["idioma_padrao"]."/quadro.escolas.php");
						include("telas/".$config["tela_padrao"]."/quadro.escolas.php");
						break;
					case "quadro_cursos":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8");
						
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","nome");
						$linhaObj->Set("campos","qac.idquadro_curso, qac.idquadro, c.idcurso, c.nome");
						$associacoesArray = $linhaObj->ListarCursosAss();
						
						include("idiomas/".$config["idioma_padrao"]."/quadro.cursos.php");
						include("telas/".$config["tela_padrao"]."/quadro.cursos.php");
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