<?

	include("../classes/etiquetas.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Etiquetas();
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
		if($_FILES) {
			foreach($_FILES as $ind => $val) {
			  $_POST[$ind] = $val;
			}
		}
		
		$linhaObj->Set("post",$_POST);
		if($_POST[$config["banco"]["primaria"]]) { $salvar = $linhaObj->Modificar();
		} else { $salvar = $linhaObj->Cadastrar(); } 
		
		if($salvar["sucesso"]){
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
		$remover = $linhaObj->Remover();
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}elseif($_POST['acao'] == 'gerar_etiquetas') {
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
		include("../assets/plugins/MPDF54/mpdf.php");
		
		$linhaObj->Set("id",(int)$url[3]);
		$linhaObj->Set("campos","*");	
		$linha = $linhaObj->Retornar();
						
		$marginLeft = $linha['margem_left'] * 10;
		$marginRight = $linha['margem_right'] * 10;
		$marginHeader = $linha['margem_top'] * 10;
		$marginFooter = $linha['margem_bottom'] * 10;
		
		$mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
		// margin-left,margin_right,margin_top,margin_bottom,margin_header,margin_footer
		$mpdf->ignore_invalid_utf8 = true;
		$mpdf->simpleTables = true;
		set_time_limit (120);
		
		$linhaEtiquetaObj = new Etiquetas();
		$linhaEtiquetaObj->Set("id",$url[3]);
		$etiquetas = $linhaEtiquetaObj->gerarEtiquetas();
		
		$etiquetas['etiquetas'] = str_ireplace("[[QUEBRA_DE_PAGINA]]", '<table class="quebra_pagina"><tr><td></td></tr></table>',$etiquetas['etiquetas']);
		$css = ".quebra_pagina {page-break-after:always;}";

		$mpdf->WriteHTML($css,1);
		$mpdf->WriteHTML($etiquetas['etiquetas']);
		$arquivo_nome = "../storage/temp/".$linha['idetiqueta']."_etiqueta.pdf";
		$mpdf->Output($arquivo_nome,"F");
		
		header("Content-type: application/save");
		header('Content-Disposition: attachment; filename="'.$linha['idetiqueta']."_etiqueta.pdf".'"');
		//header('Content-type: application/pdf');
		readfile($arquivo_nome);
		exit;
	}	
				
	
	if(isset($url[3])){	
		
		if($url[3] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {
			
			$linhaObj->Set("id",(int)$url[3]);
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
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
					case "opcoes":			
						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
						break;
					case "excluir":
						include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
						$linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
						break;
					case "download":
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "preview":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");
						include("../assets/plugins/MPDF54/mpdf.php");
						
						$linhaObj->Set("id",(int)$url[3]);
						$linhaObj->Set("campos","*");	
						$linha = $linhaObj->Retornar();
										
						$marginLeft = $linha['margem_left'] * 10;
						$marginRight = $linha['margem_right'] * 10;
						$marginHeader = $linha['margem_top'] * 10;
						$marginFooter = $linha['margem_bottom'] * 10;
						
						$mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
						// margin-left,margin_right,margin_top,margin_bottom,margin_header,margin_footer
						$mpdf->ignore_invalid_utf8 = true;
						$mpdf->simpleTables = true;
						set_time_limit (120);
						
						$linhaEtiquetaObj = new Etiquetas();
						$linhaEtiquetaObj->Set("id",$url[3]);
						$etiquetas = $linhaEtiquetaObj->gerarEtiquetasPreview();

						$etiquetas['etiquetas'] = str_ireplace("[[QUEBRA_DE_PAGINA]]", '<table class="quebra_pagina"><tr><td></td></tr></table>',$etiquetas['etiquetas']);
						$css = ".quebra_pagina {page-break-after:always;}";

						$mpdf->WriteHTML($css,1);
						$mpdf->WriteHTML($etiquetas['etiquetas']);
						$arquivo_nome = "../storage/temp/".$linha['idetiqueta']."_etiqueta.pdf";
						$mpdf->Output($arquivo_nome,"F");
						
						header("Content-type: application/save");
						header('Content-Disposition: attachment; filename="'.$linha['idetiqueta']."_etiqueta.pdf".'"');
						readfile($arquivo_nome);
						exit;
						break;
					case "gerar":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
						
						include("../classes/ofertas.class.php");
						$linhaOfeObj = new Ofertas();
						$linhaOfeObj->Set("idusuario",$usuario["idusuario"]);												
						$linhaOfeObj->Set("limite",-1);
						$linhaOfeObj->Set("ordem_campo",'o.nome');
						$linhaOfeObj->Set("ordem",'asc');
						$linhaOfeObj->Set("campos","o.idoferta, o.nome");	
						$ofertasArray = $linhaOfeObj->ListarTodas();
						
						include("../classes/cursos.class.php");
						$linhaCurObj = new Cursos();
						$linhaCurObj->Set("idusuario",$usuario["idusuario"]);												
						$linhaCurObj->Set("limite",-1);
						$linhaCurObj->Set("ordem_campo",'nome');
						$linhaCurObj->Set("ordem",'asc');
						$linhaCurObj->Set("campos","c.idcurso, nome");	
						$cursosArray = $linhaCurObj->ListarTodas();

						include("idiomas/".$config["idioma_padrao"]."/gerar.php");
						include("telas/".$config["tela_padrao"]."/gerar.php");
						break;
					case "json":
						include("telas/".$config["tela_padrao"]."/json.php");
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
		$linhaObj->Set("limite",(int)$_GET["qtd"]);
		if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","*");	
		$dadosArray = $linhaObj->ListarTodas();		
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>