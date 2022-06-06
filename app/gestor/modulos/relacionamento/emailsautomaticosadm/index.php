<?

	include("../classes/emailsautomaticosadm.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Emails_Automaticos_Adm();
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
	} elseif($_POST["acao"] == "associar_curso"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");	
		$salvar = $linhaObj->AssociarCursos(intval($url[3]), $_POST["cursos"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","associar_curso_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/email_cursos");
			$linhaObj->Processando();
		}
	}  elseif($_POST["acao"] == "remover_associacao_curso"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");	
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->DesassociarCursos();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_curso_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/email_cursos");
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
					case "email_cursos":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
						
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","nome");
						$linhaObj->Set("campos","ec.idemail_curso, ec.idemail, c.idcurso, c.nome");
						$associacoesArray = $linhaObj->ListarCursosAss();
						
						include("idiomas/".$config["idioma_padrao"]."/email.cursos.php");
						include("telas/".$config["tela_padrao"]."/email.cursos.php");
						break;
					case "download":
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "preview":					
						include("../assets/plugins/MPDF54/mpdf.php");
						
						$marginLeft = $linha['margem_left'] * 10;
						$marginRight = $linha['margem_right'] * 10;
						$marginHeader = $linha['margem_top'] * 10;
						$marginFooter = $linha['margem_bottom'] * 10;
						
						$mpdf = new mPDF('','', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');
						// margin-left,margin_right,margin_top,margin_bottom,margin_header,margin_footer
						$mpdf->ignore_invalid_utf8 = true;
						$mpdf->simpleTables = true;
						set_time_limit (0);
						
						$linha['contrato'] = str_ireplace("[[QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>",$linha['contrato']);
						$css = ".quebra_pagina {page-break-after:always;}";
						
						$mpdf->defaultfooterline = 0;	
						$mpdf->SetFooter("{PAGENO}");   
						$mpdf->WriteHTML($css,1);
						
						//contrato - dados
						$linhaContratoObj = new Contratos();
						$linhaContratoObj->Set("id",$url[3]);
						$contrato = $linhaContratoObj->Retornar();
						//contrato
						
						//background - dados
						$linhaContratoObj->Set("id",intval($contrato['idcontrato']));
						$linhaContratoObj->Set("campos","*");
						$contrato_background = $linhaContratoObj->Retornar();
						if($contrato_background['background_servidor']) {
							$css = "body{font-family:Arial;background:url(../storage/contratos_background/".$contrato_background['background_servidor'].") no-repeat;background-image-resolution:300dpi;background-image-resize:6;}";
							$mpdf->WriteHTML($css,1);
						}
						
						//background	
						$mpdf->defaultfooterline = 0;	
						$mpdf->SetFooter("{PAGENO}");   
						
						$mpdf->WriteHTML($linha['contrato']);
						$arquivo_nome = "../storage/temp/".$linha['idcontrato']."_preview.pdf";
						$mpdf->Output($arquivo_nome,"F");
						
						/*header("Content-type: ".filetype($arquivo_nome));
						header('Content-Disposition: attachment; filename="'.basename($arquivo_nome).'"');
						header('Content-Length: '.filesize($arquivo_nome));
						header('Expires: 0');
						header('Pragma: no-cache');*/
						header('Content-type: application/pdf');
						readfile($arquivo_nome);
						exit;
					case "json":
					  include("idiomas/".$config["idioma_padrao"]."/json.php");
					  include("telas/".$config["tela_padrao"]."/json.php");
					  exit;
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