<?

	include("../classes/assuntosatendimentos.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new Assuntos_Atendimentos();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);


	if($_POST["acao"] == "salvar" || $_POST["acao"] == "salvar_subassunto"){
		$modificar_sucesso = "modificar_sucesso";
		$cadastrar_sucesso = "cadastrar_sucesso";
		if($_POST["acao"] == "salvar_subassunto") {
			$modificar_sucesso = "modificar_sucesso_subassunto";
			$cadastrar_sucesso = "cadastrar_sucesso_subassunto";
			$config["banco"] = $config["banco_subassunto"];
			$linhaObj->config["banco"] = $config["banco_subassunto"];
			$linhaObj->config["formulario"] = $config["formulario_subassunto"];
		}
		
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		if($_POST["acao_url"]){
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
		}else{
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
		}
		$linhaObj->Set("post",$_POST);
		if($_POST[$config["banco"]["primaria"]]) $salvar = $linhaObj->Modificar();
			else $salvar = $linhaObj->Cadastrar();
		if($salvar["sucesso"]){
			if($_POST[$config["banco"]["primaria"]]) {
				$linhaObj->Set("pro_mensagem_idioma",$modificar_sucesso);
				$linhaObj->Set("url",$url_redireciona);
			} else {
				$linhaObj->Set("pro_mensagem_idioma",$cadastrar_sucesso);
				$linhaObj->Set("url",$url_redireciona);
			}
			$linhaObj->Processando();
		}
	}elseif($_POST["acao"] == "remover" || $_POST["acao"] == "remover_subassunto"){
		$remover_sucesso = "remover_sucesso";
		if($_POST["acao"] == "remover_subassunto") {
			$remover_sucesso = "remover_sucesso_subassunto";
			$config["banco"] = $config["banco_subassunto"];
			$linhaObj->config["banco"] = $config["banco_subassunto"];
			$linhaObj->config["formulario"] = $config["formulario_subassunto"];
		}
		
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->Remover();
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma",$remover_sucesso);
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}	
				
	
	if(isset($url[3])){	
		
		if($url[3] == "cadastrarassunto") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			include("idiomas/".$config["idioma_padrao"]."/formulario.assunto.php");
			include("telas/".$config["tela_padrao"]."/formulario.assunto.php");
			exit();
		} elseif($url[3] == "cadastrarsubassunto") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			$config["banco"] = $config["banco_subassunto"];
			$linhaObj->config["banco"] = $config["banco_subassunto"];
			include("idiomas/".$config["idioma_padrao"]."/formulario.subassunto.php");
			include("telas/".$config["tela_padrao"]."/formulario.subassunto.php");
			exit();
		} else {
			$linhaObj->Set("id",intval($url[3]));
			
			
			if($url[4] == "editarassunto" || $url[4] == "removerassunto" || $url[4] == "opcoesassunto" ) {
				$linhaObj->Set("campos","*");
				$linha = $linhaObj->RetornarAssunto();
			} else {
				$linhaObj->Set("campos","aa.nome as assunto, aas.*");
				$linha = $linhaObj->RetornarSubassunto();
			}
			
			if($linha) {
			
				switch ($url[4]) {
					case "editarassunto":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						include("idiomas/".$config["idioma_padrao"]."/formulario.assunto.php");
						include("telas/".$config["tela_padrao"]."/formulario.assunto.php");
						break;
					case "editarsubassunto":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						$config["banco"] = $config["banco_subassunto"];
						$linhaObj->config["banco"] = $config["banco_subassunto"];
						include("idiomas/".$config["idioma_padrao"]."/formulario.subassunto.php");
						include("telas/".$config["tela_padrao"]."/formulario.subassunto.php");
						break;
					case "removerassunto":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
						include("idiomas/".$config["idioma_padrao"]."/remover.assunto.php");
						include("telas/".$config["tela_padrao"]."/remover.assunto.php");
						break;
					case "removersubassunto":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
						$config["banco"] = $config["banco_subassunto"];
						$linhaObj->config["banco"] = $config["banco_subassunto"];
						include("idiomas/".$config["idioma_padrao"]."/remover.subassunto.php");
						include("telas/".$config["tela_padrao"]."/remover.subassunto.php");
						break;
					case "opcoesassunto":			
						include("idiomas/".$config["idioma_padrao"]."/opcoes.assunto.php");
						include("telas/".$config["tela_padrao"]."/opcoes.assunto.php");
						break;
					case "opcoessubassunto":			
						include("idiomas/".$config["idioma_padrao"]."/opcoes.subassunto.php");
						include("telas/".$config["tela_padrao"]."/opcoes.subassunto.php");
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
		if(!$_GET["ordem"]) $_GET["ordem"] = "ASC";
		$linhaObj->Set("ordem",$_GET["ord"]);
		if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		$linhaObj->Set("limite",intval($_GET["qtd"]));
		if(!$_GET["cmp"]) $_GET["cmp"] = "assunto ASC, subassunto ASC, idsubassunto";
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","idassunto, nome as assunto, idassunto AS idsubassunto, '- -' AS subassunto, ativo_painel, data_cad, 'A' AS tipo, sla");
		$linhaObj->Set("campos_2","aa.idassunto, aa.nome as assunto, aas.idsubassunto, aas.nome AS subassunto, aas.ativo_painel, aas.data_cad, 'S' AS tipo, '- -' as sla");	
		$dadosArray = $linhaObj->ListarTodas();
        foreach ($dadosArray as $array => $assunto) {//Se em nenhum momento não encontrar espaco no "nome", sera colocado "espaco"! para evitar quebra do layout
            if (!mb_strpos($assunto["assunto"], ' ') || !mb_strpos($assunto["subassunto"], ' ')) {
                $assunto['assunto'] = wordwrap($assunto["assunto"], 30, " ", true);
                $assunto['subassunto'] = wordwrap($assunto["subassunto"], 30, " ", true);
                $dadosArray[$array]['assunto'] = $assunto['assunto'];
                $dadosArray[$array]['subassunto'] = $assunto['subassunto'];
            }
        }
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>