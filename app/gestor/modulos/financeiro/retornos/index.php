<?php

	include("../classes/retornos.class.php");
	include("../includes/config.bancario.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");
	
	$linhaObj = new Retornos();
	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",1);


	if($_POST["acao"] == "salvar"){
		
		if($_FILES) {
			foreach($_FILES as $ind => $val) {
			  $_POST[$ind] = $val;
			}
		}
		$linhaObj->Set("post",$_POST);
		if($_POST[$config["banco"]["primaria"]]) 
			$salvar = $linhaObj->Modificar();
		else 
			$salvar = $linhaObj->Cadastrar();
		if($salvar["sucesso"]){
			if($_POST[$config["banco"]["primaria"]]) {
				$linhaObj->Set("pro_mensagem_idioma","modificar_sucesso");
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
			} else {
				$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$salvar["id"]."/processar");
			}
			$linhaObj->Processando();
		}
	}
	
	if($_POST["acao"] == "remover"){
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->Remover();
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","remover_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}	
				
	if($_POST["acao"] == "processar" and intval($url[3])){
		$linhaObj->Set("id",intval($url[3]));
		$linhaObj->Set("post",$_POST);
		$remover = $linhaObj->ProcessarRetorno();
		if($remover["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","processado_com_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."");
			$linhaObj->Processando();
		}
	}	
				
	
	if(isset($url[3])){	
		
		if($url[3] == "cadastrar") {
			if($url[4] == "ajax_cidades"){
				$linhaObj->RetonaConteudoAjax('cidades', 'idestado', $url[5], 'idcidade', 'nome', $url[6]);
				exit();
			}
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {

			$linhaObj->Set("id",intval($url[3]));
			$linhaObj->Set("campos","*");	
			$linha = $linhaObj->Retornar();
			if(is_array($linha))
				$linha = array_map(stripslashes,$linha);
			
			if($linha) {
				
				switch ($url[4]) {
					case "editar":
						include("idiomas/".$config["idioma_padrao"]."/formulario.php");
						include("telas/".$config["tela_padrao"]."/formulario.php");
						break;
					case "remover":			
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
				    case "listar":	
						$linhaObj->Set("campos", "re.datacad,re.arquivo_nome,cpe.vencimento,cpe.valor,cpe.datapago,cpe.valorpago,cpe.situacao,pe.nome,pe.cpf");	
						$linha = $linhaObj->RetornarParcelasRetorno();				
						$totalLinhas = count($linha);
						include("idiomas/".$config["idioma_padrao"]."/listar.php");
						include("telas/".$config["tela_padrao"]."/listar.php");
						break;
					case "processar":	
					    if($linha['processado'] == 'S'){
							 header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
						}
						$dadosArray = $linhaObj->Preview();
						include("idiomas/".$config["idioma_padrao"]."/preview.php");
						include("telas/".$config["tela_padrao"]."/preview.php");
						break;
					case "excluir":
						include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
						$linhaObj->RemoverArquivo($url[2], $url[5], $linha, $idioma);
						break;
					case "ajax_cidades":						
						$linhaObj->RetonaConteudoAjax('cidades', 'idestado', $url[5], 'idcidade', 'nome', $url[6]);
						break;
					case "download":
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "pagamentos":
						$linhaObj->Set("ordem_campo","p.nome");
						$linhaObj->Set("ordem","ASC");
						$linhaObj->Set("limite",-1);
						$linhaObj->Set("campos","   c.idconta,
													p.nome as pessoa,
													p.documento as cpf,
													p.email,
													c.data_vencimento as vencimento,
													c.valor as valorInscricao,
													rc.multa,
													rc.juros,
													(rc.juros + rc.multa) as jurosMulta,
													rc.status,
													rc.data_debito as dataPagamento,
													rc.valorpago as valorPagamento
													");
						$dadosArray = $linhaObj->RetornarParcelasRetorno();

						include("idiomas/".$config["idioma_padrao"]."/pagamentos.php");
						include("telas/".$config["tela_padrao"]."/pagamentos.php");
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
		if(!$_GET["cmp"]) $_GET["cmp"] = "re.idretorno";
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","re.*, ua.nome, re.quantidade_processado as parcelas, DATE_FORMAT(re.datacad, '%d/%m/%Y às %H:%i') as datacad ");	
		$dadosArray = $linhaObj->ListarTodas();		
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>