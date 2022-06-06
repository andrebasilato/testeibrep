<?

	include("../classes/pesquisas.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Pesquisas();
	$linhaObj->Set("modulo",$url[0]);
	//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
	/*$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);*/

	if($_POST['act'] == "responder_pesquisa"){
		$linhaObj->Set("post",$_POST);
		
		$hash_valido = $linhaObj->verificarHashUsuarioPesquisa($_POST['idpesquisa_pessoa'], $_POST['hash']);
		if($hash_valido['hash']) {
			if (!$hash_valido['data_resposta']) {
				if ($hash_valido["de"] <= date("Y-m-d") && $hash_valido["ate"] >= date("Y-m-d")) {
					$reponder_pesquisa = $linhaObj->responderPesquisa();
				} else {
					$salvar["erros"][] = "pesquisa_fora_periodo";
				}
			} else {
				$salvar["erros"][] = "pesquisa_respondida";
			}
		} else {
			$salvar["erros"][] = "hash_invalido";
		}
		if($reponder_pesquisa["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","responder_pesquisa_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/");
			$linhaObj->Processando();
		} else if (count($salvar["erros"]) == 0) {
			$salvar["erros"][] = "erro_processando_informacao";
		}
	}
	
	if(isset($url[3])){	
			
		$linhaObj->Set("id",intval($url[3]));
		$linhaObj->Set("campos","*");	
		$linha = $linhaObj->Retornar();
		
		if($linha) {
			
			switch ($url[4]) {
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
				case "responder":
					//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15");
					$hash_valido = $linhaObj->verificarHashUsuarioPesquisa($url[5], $url[6]);
					$erro = false;
					if($hash_valido['hash']) {
						if (!$hash_valido['data_resposta']) {
							if (!($hash_valido["de"] <= date("Y-m-d") && $hash_valido["ate"] >= date("Y-m-d"))) {
								$erro = true;
								$mensagemErro = "pesquisa_fora_periodo";
							}
						} else {
							$erro = true;
							$mensagemErro = "pesquisa_respondida";
						}
					} else {
						$erro = true;
						$mensagemErro = "hash_invalido";
					}
					
					$linhaObj->Set("id",intval($url[3]));
					$linha = $linhaObj->RetornarPreviewPesquisa('responder');
					include("idiomas/".$config["idioma_padrao"]."/index.php");
					include("telas/".$config["tela_padrao"]."/index.php");
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

?>