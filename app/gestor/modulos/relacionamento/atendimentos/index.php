<?php 
	include("../classes/atendimentos.class.php");
	include("../classes/pessoas.class.php");
	include("../classes/assuntosatendimentos.class.php");
	include("../classes/respostasatendimentos.class.php");
	include("../classes/imobiliarias.class.php");
	include("../classes/usuarios.class.php");
	include("../classes/gruposusuariosadm.class.php");
	include("../classes/cursos.class.php");
	include("../classes/matriculas.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");	
	
	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

	
	$linhaObjPessoa = new Pessoas();
	$linhaObjPessoa->Set("idusuario",$usuario["idusuario"]);
	
	$linhaObjCurso = new Cursos();
	$linhaObjCurso->Set("idusuario",$usuario["idusuario"]);
	$linhaObjMatricula = new Matriculas();
	$linhaObjMatricula->Set("idusuario",$usuario["idusuario"]);
	$linhaObjAssunto = new Assuntos_Atendimentos();
	$linhaObjResposta = new Respostas_Atendimentos();
	
	$linhaObj = new Atendimentos();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);
	$linhaObj->Set("idgestor",$usuario['idusuario']);
	
	$linhaObjPessoa->Set("onde","G"); //Informa de que modulo foi feita a modificação dos dadps da pessoa (I - Imobiliária, U - Pessoa, C - Corretor, G - Gestor)
	$linhaObjPessoa->Set("idsolicita_alteracao",$usuario["idusuario"]);// Id do usuário de fez a modificação


	if($_POST["acao"] == "buscar_pessoa"){
		
		$linhaObjPessoa->Set("post",$_POST);
		$linhaObjPessoa->Set("campos","p.*");
		$linha = $linhaObjPessoa->verificaCadastro();

		if(!$linha["erro"]){
			$assuntos = $linhaObjAssunto->RetornarTodosAssuntos();
			$cursos = $linhaObjCurso->RetornarTodosCursos();
			$linhaObjMatricula->Set("campos","m.idmatricula, CONCAT(m.idmatricula,' - ',p.nome) as nome");
			$matriculas = $linhaObjMatricula->RetornarTodosMatriculas();
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();	
		}
		
	} elseif($_POST["acao"] == "salvar"){

		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		
		if($_POST["acao_url"]){
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
		}else{
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
		}
		
		$linhaObjPessoa->Set("post", $_POST);
		$linhaObjPessoa->config["banco"] = $config["banco_pessoas"];
		$linhaObjPessoa->config["formulario"] = $config["formulario_pessoas"];

		
		$linhaObjPessoa->Set("monitora_onde", 98);

		if($_POST["idpessoa"]){
			$salvar = $linhaObjPessoa->Modificar();
		}else {
			$salvar = $linhaObjPessoa->Cadastrar();
		}

		$_POST["idpessoa"] = $salvar["id"];
		
		//SE CADASTRAR OU ATUALIZAR DADOS COM SUCESSO
		if($salvar["sucesso"] && $_POST["idpessoa"]){
			
			$linhaObjAssunto->Set("id",(int)$_POST["idassunto"]);
			$linhaObjAssunto->Set("campos","subassunto_obrigatorio");
			$assunto = $linhaObjAssunto->RetornarAssunto();
			if($assunto["subassunto_obrigatorio"] == "N" || is_null($assunto["subassunto_obrigatorio"])) {
			  unset($config["formulario"][0]["campos"][1]["validacao"]);
			}

			$linhaObj->Set("post", $_POST);
			$linhaObj->config["banco"] = $config["banco"];
			$linhaObj->config["formulario"] = $config["formulario"];
			$salvar = $linhaObj->Cadastrar();

			if($salvar["sucesso"]){
				$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
				$linhaObj->Set("url",$url_redireciona);
				$linhaObj->Processando();
			} else {
				$linhaObjPessoa->Set("post",$_POST);
				$linhaObjPessoa->Set("campos","p.*");
				$linha = $linhaObjPessoa->verificaCadastro();
				
				if(!$linha["erro"]){
					$linhaObjAssunto = new Assuntos_Atendimentos();
					$assuntos = $linhaObjAssunto->RetornarTodosAssuntos();
					//$unidades = $linhaObj->RetornarUnidadesCliente($linha["idpessoa"]);
					include("idiomas/".$config["idioma_padrao"]."/formulario.php");
					include("telas/".$config["tela_padrao"]."/formulario.php");
					exit();	
				}
			}
		} else {
			$linhaObjPessoa->Set("post",$_POST);
			$linhaObjPessoa->Set("campos","p.*");
			$linha = $linhaObjPessoa->verificaCadastro();
			
			if(!$linha["erro"]){
				$assuntos = $linhaObjAssunto->RetornarTodosAssuntos();
				include("idiomas/".$config["idioma_padrao"]."/formulario.php");
				include("telas/".$config["tela_padrao"]."/formulario.php");
				exit();	
			}
		}
	} elseif($_POST["acao"] == "alterarSituacao") {
		$linhaObj->Set("id",(int)$url[3]);
		$linhaObj->Set("post",$_POST);
		$alterar = $linhaObj->AlterarSituacao();

	  if($alterar["sucesso"]){
		$linhaObj->Set("pro_mensagem_idioma",$alterar["mensagem"]);
		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
		$linhaObj->Set("ancora","situacao_reserva");
		$linhaObj->Processando();
	  } else {	
		$mensagem["erro"] = $alterar["mensagem"];
	  }
	} elseif($_POST["acao"] == "alterar_assunto"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		$salvar = $linhaObj->alterarAssunto((int)$url[3], $_POST);

		if($salvar["sucesso"]){
			if ($salvar['msg']) 
				$linhaObj->Set("pro_mensagem_idioma",$salvar['msg']);
			else 
				$linhaObj->Set("pro_mensagem_idioma","alterar_assunto_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		}
		
	} elseif($_POST["acao"] == "alterar_informacoes"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		$salvar = $linhaObj->alterarInformacoesGerenciais(intval($url[3]), $_POST);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","alterar_informacoes_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		}	
	} elseif($_POST["acao"] == "clonar"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		
		$salvar = $linhaObj->Clonar(intval($url[3]));
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","clonar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$salvar["id"]."/visualiza");
			$linhaObj->Processando();
		}	
	} elseif($_POST["acao"] == "convidar_imobiliaria"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		$salvar = $linhaObj->convidarImobiliaria(intval($url[3]), $_POST["imobiliaria"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","convidar_imobiliaria_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "convidar_corretor"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");		
		$salvar = $linhaObj->convidarCorretor(intval($url[3]), $_POST["corretor"]);
		
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","convidar_corretor_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "editar_mensagem"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		$verifica = $linhaObj->verificaEditarMensagem($url[3], $url[5]);
		
		if($verifica["sucesso"]) {
			include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
			$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
			$salvar = $linhaObj->editarMensagem(intval($url[3]), intval($url[5]), $_POST, $_FILES, $erros);
			if($salvar["sucesso"]){
				$linhaObj->Set("pro_mensagem_idioma","alterar_resposta");
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
				$linhaObj->Processando();
			}
		}
	} elseif($_POST["acao"] == "salvar_checklist"){
		//$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|DEFINIR");
		$salvar = $linhaObj->responderChecklist(intval($url[3]), $_POST["opcao"]);

		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","salvar_checklist_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		}
	} elseif ($_POST['acao'] == 'responder_atendimento') {
		include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
		$erros['arquivo_invalido'] = $idioma['arquivo_invalido'];
		$salvar = $linhaObj->responderAtendimento(intval($url[3]), $_POST, $_FILES, $erros);

		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","responder_atendimento_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/visualiza");
			$linhaObj->Processando();
		} 
	} elseif($url[5] == "cliente_liberar") {
		$salvar = $linhaObj->alterar_bloqueio_cliente($url[3], 'S');
		if($salvar){
			$linhaObj->Set("pro_mensagem_idioma",'solicitacao_liberacao_sucesso');
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
			$linhaObj->Processando();
		} else {	
			$mensagem["erro"] = 'erro_solicitacao_liberacao';
		}
	} elseif($url[5] == "cliente_bloquear") {
		$salvar = $linhaObj->alterar_bloqueio_cliente($url[3], 'N');
		if($salvar){
			$linhaObj->Set("pro_mensagem_idioma",'solicitacao_liberacao_remover_sucesso');
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
			$linhaObj->Processando();
		} else {	
			$mensagem["erro"] = 'erro_solicitacao_liberacao';
		}
	
	}
	
	if(isset($url[3])){	
		
		if($url[4] == "json" && $url[3] == "cadastrar"){
			include("idiomas/".$config["idioma_padrao"]."/json.php");
			include("telas/".$config["tela_padrao"]."/json.php");
			exit();
		}
		
		if($url[3] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
			include("idiomas/".$config["idioma_padrao"]."/formulario.busca.php");
			include("telas/".$config["tela_padrao"]."/formulario.busca.php");
			exit();
		} else {
			
			$linhaObj->Set("ordem_campo","idsituacao");
			$linhaObj->Set("ordem","asc");
			$situacaoWorkflow = $linhaObj->RetornarSituacoesWorkflow();

			$linhaObj->Set("idusuario",$usuario["idusuario"]);
			$linhaObj->Set("id",(int)$url[3]);
			$linhaObj->Set("campos","ate.*, 
									ass.nome as assunto, 
									ass.subassunto_obrigatorio, 
									sub.nome as subassunto, 
									aw.nome as situacao, 
									p.nome as cliente,
									c.nome as curso");	
			$linha = $linhaObj->Retornar();
			if($linha) {
				switch ($url[4]) {
				    case "ficha":
					    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						$obj = new Atendimentos();
						$respostas = $obj->retornaRespostas($linha['idatendimento']);
						$pessoa = new Pessoas();
						$pessoa->Set("id",$linha['idpessoa']);
						$pessoaDados = $pessoa->Retornar();
						$visualizadores = $obj->retornarQuemVisualiza($linha['idatendimento']);
						$ultimoAtendente = $obj->retornaUltimaInteracaoAtendente($linha['idatendimento']);
						$ultimaInteracao = $obj->retornaUltimaInteracao($linha['idatendimento']);
						$situacoes_workflow = $obj->retornarRelacionamentosWorkflow($linha['idsituacao']);
						foreach($situacoes_workflow as $sit)
							$array_situacoes[] = $sit['idsituacao_para'];
		
						//$historicos = $linhaObj->retornarHistorico();
						
						if ($linha['idclone']) {
							$obj->Set("id",$linha['idclone']);                            
							$obj->Set("idusuario",$usuario["idusuario"]);
                            $obj->Set("idgestor",$usuario["idusuario"]);
                            $obj->Set("campos","ate.*, 
									ass.nome as assunto, 
									ass.subassunto_obrigatorio, 
									sub.nome as subassunto, 
									aw.nome as situacao, 
									p.nome as cliente,
									c.nome as curso");	
							$clone = $obj->Retornar();		
						}
						
					    include("idiomas/".$config["idioma_padrao"]."/ficha.php");
					    include("telas/".$config["tela_padrao"]."/ficha.php");
					    break;					
					case "editarmensagem":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						$verifica = $linhaObj->verificaEditarMensagem($url[3], $url[5]);
						if($verifica["sucesso"]) {
							$linha = $linhaObj->retornaResposta($url[5]);
							include("idiomas/".$config["idioma_padrao"]."/editar.mensagem.php");
							include("telas/".$config["tela_padrao"]."/editar.mensagem.php");
						} else {
							incluirLib("sempermissao",$linhaObj->config);
						}
						break;
					case "remover":			
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
					case "opcoes":	
						$obj = new Atendimentos();
						$respostas = $obj->retornaRespostas($linha['idatendimento']);
						
						$pessoa = new Pessoas();
						$pessoa->Set("id",$linha['idpessoa']);
						$pessoaDados = $pessoa->Retornar();							
						$visualizadores = $obj->retornarQuemVisualiza($linha['idatendimento']);
						$ultimoAtendente = $obj->retornaUltimaInteracaoAtendente($linha['idatendimento']);
						$ultimaInteracao = $obj->retornaUltimaInteracao($linha['idatendimento']);
								
						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
						break;
					case "visualiza":
						$obj = new Atendimentos();
						$respostas = $obj->retornaRespostas($linha['idatendimento']);
						$arquivos = $obj->retornaArquivosAtendimentos($linha['idatendimento']);
						$situacaoRespondidoGestor = $obj->retornaSituacaoRespondidoGestor();
						
						$pessoa = new Pessoas();
						$pessoa->Set("id",$linha['idpessoa']);
						$pessoaDados = $pessoa->Retornar();			
						$ultimoAtendente = $obj->retornaUltimaInteracaoAtendente($linha['idatendimento']);
						$ultimaInteracao = $obj->retornaUltimaInteracao($linha['idatendimento']);
						$respostas_automaticas = $linhaObjResposta->ListarTodasAssunto($linha['idassunto'], $linha['idmatricula']);
						$situacoes_workflow = $obj->retornarRelacionamentosWorkflow($linha['idsituacao']);
						foreach($situacoes_workflow as $sit)
							$array_situacoes[] = $sit['idsituacao_para'];
		
						$historicos = $linhaObj->retornarHistorico();
						
						if ($linha['idclone']) {
							$obj->Set("id",$linha['idclone']);                            
							$obj->Set("idusuario",$usuario["idusuario"]);
                            $obj->Set("idgestor",$usuario["idusuario"]);
                            $obj->Set("campos","ate.*, 
									ass.nome as assunto, 
									ass.subassunto_obrigatorio, 
									sub.nome as subassunto, 
									aw.nome as situacao, 
									p.nome as cliente,
									c.nome as curso");	
							$clone = $obj->Retornar();		
						}
						$situacao_atendimento = $obj->retornarWorkflow($linha['idsituacao']);						
						$editar_mensagem_cliente = $obj->retornaPermissaoAlterarMensagem($linha['idatendimento'], $pessoaDados['ultimo_view']);
                        
                        if ($linha['idmatricula']) {
                            $matriculaObj = new Matriculas();
                            $matriculaObj->Set("id",$linha['idmatricula']);
                            $matriculaDados = $matriculaObj->Retornar();
                            
                            $pessoa->Set("campos","p.*, pa.nome as pais");
                            $pessoa->Set("id",intval($linha['idpessoa']));
                            $matriculaDados['pessoa'] = $pessoa->Retornar();
                            
                            $matriculaDados['atendimento'] = $linha;
                        }

						include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
						include("telas/".$config["tela_padrao"]."/visualiza.php");
						break;
					case "json":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
						include("idiomas/".$config["idioma_padrao"]."/json.php");
						include("telas/".$config["tela_padrao"]."/json.php");
						break;
					case "encaminhar":						
						$assuntos = $linhaObjAssunto->RetornarTodosAssuntos();						
						$subassuntos = $linhaObj->RetornarSubassuntos($linha['idassunto'], false);
				        //$unidades = $linhaObj->RetornarUnidadesCliente($linha["idpessoa"]);
						
						include("idiomas/".$config["idioma_padrao"]."/encaminhar.php");
						include("telas/".$config["tela_padrao"]."/encaminhar.php");
						break;
					case "checklist":						
						$checklists = $linhaObj->retornarChecklists($linha['idatendimento'], false);

						include("idiomas/".$config["idioma_padrao"]."/checklist.php");
						include("telas/".$config["tela_padrao"]."/checklist.php");
						break;
					case "informacoesgerenciais":
						include("idiomas/".$config["idioma_padrao"]."/informacoes.gerenciais.php");
						include("telas/".$config["tela_padrao"]."/informacoes.gerenciais.php");
						break;						
					case "download":
						$arquivo = $linhaObj->retornaArquivoDownload($url[3], $url[5], $url[6]);
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "download_ate":
						$arquivo = $linhaObj->retornaArquivoAtendimentoDownload($url[3], $url[5]);
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					case "quem_visualiza":						
						$visualizadores = $linhaObj->retornarQuemVisualiza($linha['idatendimento']);

						include("idiomas/".$config["idioma_padrao"]."/quem_visualiza.php");
						include("telas/".$config["tela_padrao"]."/quem_visualiza.php");
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
		$linhaObj->Set("campos","ate.*, 
								p.idpessoa, 
								p.nome as cliente, 
								IF( ( HOUR(TIMEDIFF(NOW(),ate.data_cad)) >= aa.sla ),'S','N' ) as sla_vencido, 
								aa.nome as assunto, 
								c.nome as curso, 
								i.nome_abreviado as sindicato,
								aw.nome as situacao, 
								aw.cor_bg as situacao_cor_bg, 
								aw.cor_nome as situacao_cor_nome, 
								aas.nome as subassunto");
		$dadosArray = $linhaObj->ListarTodas();
		
		$linhaObjUsuario = new Usuarios();
		$linhaObjUsuario->Set("campos","e.nome");
		$linhaObjUsuario->Set("id",$usuario["idusuario"]);
		$grupos_associados = $linhaObjUsuario->retornarGruposAss($usuario["idusuario"]);
		
		$linhaObjGrupo = new Grupos_Usuarios_Adm();	
		
		foreach($grupos_associados as $grupo) {	
			$linhaObjGrupo->Set("campos","a.nome, a.idassunto");
			$linhaObjGrupo->Set("id",$grupo["idgrupo"]);
			$retorno_grupos[$grupo['idgrupo']]['nome'] = $grupo['nome'];
			$retorno_grupos[$grupo['idgrupo']]['assuntos'] = $linhaObjGrupo->ListarAssuntosAss();
			$linhaObjGrupo->Set("campos","s.nome, s.idsubassunto");
			$retorno_grupos[$grupo['idgrupo']]['subassuntos'] = $linhaObjGrupo->ListarSubassuntosAss();
		}	
		
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");

	}

?>