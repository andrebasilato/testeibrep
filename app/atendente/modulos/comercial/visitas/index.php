<?

	include("../classes/visitasvendedores.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");

	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
	$linhaObj = new VisitasVendedores();
	
	$linhaObj->Set("idvendedor",$usu_vendedor["idvendedor"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);
	
	include("../classes/pdv.class.php");
	//$linhaPdvObj = new PDV();//PDV


	if($_POST["acao"] == "salvar"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		$linhaObj->Set("post",$_POST);
		if($_POST[$config["banco"]["primaria"]]) {
			//unset($config["formulario_editar"][0]["campos"][10]);
			//unset($config["formulario_editar"][0]["campos"][11]);
			$linhaObj->config["formulario"] = $config["formulario_editar"];
			$salvar = $linhaObj->Modificar();
		} else { 
			$linhaObj->config["banco"] = $config["banco_pessoa"];
			$salvar = $linhaObj->Cadastrar();
		}
		if($salvar["sucesso"]){
			if($_POST[$config["banco"]["primaria"]]) {
				$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
				$linhaObj->Processando();
			} else {
				$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			}
			$linhaObj->Processando();
		}
	}
	
	if($_POST["acao"] == "remover"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->Remover();
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}

	if($_POST["acao"] == "salvar_mensagem") {
    	if($_POST["mensagem"]) {
	        $linhaObj->Set("post",$_POST);
	        $linhaObj->Set('id',$url[3]);
	        $salvar = $linhaObj->adicionarMensagem();
	        if($salvar["sucesso"]){
	            $linhaObj->Set("pro_mensagem_idioma","mensagem_adicionada_sucesso");
	            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
	            $linhaObj->Processando();
        	} else {  
            	$mensagem["erro"] = $salvar["mensagem"];
        	}     
    	} else {
        	$salvar["sucesso"] = false;
        	$salvar["erros"][] = "mensagem_vazia";
    	}
	} 

	if($_POST["acao"] == "remover_mensagem") {
	    if($_POST["idmensagem"]) {
	        $linhaObj->Set('id',$url[3]);
	        $remover = $linhaObj->removerMensagem((int) $_POST["idmensagem"]);
	        if($remover["sucesso"]){
	            $linhaObj->Set("pro_mensagem_idioma",$remover["mensagem"]);
	            $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]);
	            $linhaObj->Processando();
	        } else {  
	            $mensagem["erro"] = $remover["mensagem"];
	        }
	    } else {
	        $mensagem["erro"] = "mensagem_remover_vazio";
	    }
	}  elseif($_POST["acao"] == "adicionar_iteracao"){
		
		$linhaObj->Set("id",intval($url[3]));
		$linhaObj->Set("post",$_POST);
		$salvar = $linhaObj->adicionarIteracao();
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visitas");
			$linhaObj->Processando();
		}	
	} elseif($_POST["acao"] == "remover_iteracao"){
		
		$linhaObj->Set("id",intval($url[3]));
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->RemoverIteracao();
		
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visitas");
			$linhaObj->Processando();
		}
	}			
	
	if(isset($url[3])){
		
		if($url[3] == "cadastrar") {
		
			if($url[4] == "json") {
			  include("idiomas/".$config["idioma_padrao"]."/json.php");
			  include("telas/".$config["tela_padrao"]."/json.php");
			  exit;
			}
		
			if($url[4] == "ajax_cursos"){
				if ($_REQUEST['idvendedor']) {
					$linhaObj->Set("id",intval($_REQUEST['idvendedor']));
					$linhaObj->RetornarCursosVendedor();
				}								
				exit();
			}
		
			//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {
			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("campos","vv.*, 
				pe.idpessoa, 
				pe.nome as nome_pessoa, 
				pe.documento as documento_pessoa, 
				pe.data_nasc as data_nasc_pessoa, 
				pe.email as email_pessoa, 
				pe.telefone as telefone_pessoa,
				c.nome as cidade,
            	e.nome as estado");
			$linha = $linhaObj->Retornar();

			if($linha['documento_pessoa']) $linha['documento'] = $linha['documento_pessoa'];
			if($linha['nome_pessoa']) $linha['nome'] = $linha['nome_pessoa'];	
			if($linha['email_pessoa']) $linha['email'] = $linha['email_pessoa'];
			if($linha['telefone_pessoa']) $linha['telefone'] = $linha['telefone_pessoa'];
			if($linha['data_nasc_pessoa']) $linha['data_nasc'] = $linha['data_nasc_pessoa'];

			if($url[4] == "ajax_cursos"){	
				if ($_REQUEST['idvendedor']) {
					$linhaObj->Set("id",intval($_REQUEST['idvendedor']));
					$linhaObj->RetornarCursosVendedor();	
				}
				else {
					$linhaObj->Set("id",intval($linha['idvendedor']));
					$linhaObj->RetornarCursosVendedor();
				}
				exit();
			}
			
			if($linha) {
				switch ($url[4]) {
					case "editar":			
						$cursos_associados = $linhaObj->retornarCursosVisita($url[3]);
						
						include("idiomas/".$config["idioma_padrao"]."/formulario.php");
						include("telas/".$config["tela_padrao"]."/formulario.php");
						break;
					case 'mensagens':
	                    $linhaObj->Set("campos","vm.*,
	                            ua.nome as usuario,
	                            ua.idusuario,
	                            v.nome as vendedor,
	                            v.idvendedor");
	                    $linhaObj->Set('ordem','desc');
	                    $linhaObj->Set('groupby','vm.idmensagem');
	                    $linhaObj->Set('ordem_campo','vm.idmensagem');
	                    $linhaObj->Set('limite',-1);
	                    $mensagensVisita = $linhaObj->retornarMensagensVisita();
	                    include 'idiomas/'.$config['idioma_padrao'].'/mensagens.php';
	                    include 'telas/'.$config['tela_padrao'].'/mensagens.php';
	                    break;
					case "remover":			
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
					case 'geolocalizacao':
	                    include 'idiomas/'.$config['idioma_padrao'].'/geolocalizacao.php';
	                    include 'telas/'.$config['tela_padrao'].'/geolocalizacao.php';
	                    break;
					case "opcoes":			
						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
						break;	
					case "visitas":			
						$linhaObj->Set("id",intval($url[3]));
						$linhaObj->Set("ordem","asc");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("ordem_campo","vvi.numero");
						$linhaObj->Set("campos","vvi.*");
						$associacoesArray = $linhaObj->ListarIteracoes();
						include("idiomas/".$config["idioma_padrao"]."/visitas.php");
						include("telas/".$config["tela_padrao"]."/visitas.php");
						break;
					case "json":
					  include("idiomas/".$config["idioma_padrao"]."/json.php");
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
		$linhaObj->Set("limite",(int) $_GET["qtd"]);
		if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","vv.*, 
			pe.idpessoa, 
			pe.nome as nome_pessoa, 
			pe.documento as documento_pessoa, 
			pe.data_nasc as data_nasc_pessoa, 
			pe.email as email_pessoa, 
			pe.telefone as telefone_pessoa, 
			v.nome as vendedor, 
			mid_v.nome as midia");
		$dadosArray = $linhaObj->ListarTodas();	
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>