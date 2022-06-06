<?php
$config['monitoramento']['onde_foruns_topicos'] = '153';

$config['banco_foruns_topicos'] = array(
  'tabela' => 'avas_foruns_topicos',
  'primaria' => 'idtopico',
  'campos_insert_fixo' => array(
	'data_cad' => 'now()', 
	'ativo' => '"S"'
  ),
  'campos_unicos' => array(
	array(
	  'campo_banco' => 'nome', 
	  'campo_form' => 'nome||idforum', 
	  'erro_idioma' => 'nome_utilizado'
	)
  )
);

$config['formulario_foruns_topicos'] = array(
  array(
	'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
	'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
	'campos' => array( // Campos do formulario																						
	  array(
		'id' => 'form_nome',
		'nome' => 'nome', 
		'nomeidioma' => 'form_nome',
		'tipo' => 'input',
		'valor' => 'nome',
		'validacao' => array('required' => 'nome_vazio'), 
		'class' => 'span6',
		'banco' => true,
		'banco_string' => true,
	  ),
	  array(
		'id' => 'form_mensagem',
		'nome' => 'mensagem',
		'nomeidioma' => 'form_mensagem',
		'tipo' => 'text', 
		'valor' => 'mensagem',
		'class' => 'span6',
		'validacao' => array('required' => 'mensagem_vazio'),
		'banco' => true, 
		'banco_string' => true,
	  ),
	  /*array(
		'id' => 'form_periode_de',
		'nome' => 'periode_de',
		'nomeidioma' => 'form_periode_de',
		'tipo' => 'input', 
		'valor' => 'periode_de',
		//'validacao' => array('required' => 'periode_de_vazio'), 
		"valor_php" => 'if($dados["periode_de"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_periode_ate",
		"nome" => "periode_ate",
		"nomeidioma" => "form_periode_ate",
		"tipo" => "input", 
		"valor" => "periode_ate",
		//"validacao" => array("required" => "periode_ate_vazio"), 
		"valor_php" => 'if($dados["periode_ate"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "form_arquivo", // Id do atributo HTML
		"nome" => "arquivo", // Name do atributo HTML
		"nomeidioma" => "form_arquivo", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp|doc|docx|pdf|xls|xlsx|ppt|pptx|txt',
		"ajudaidioma" => "form_arquivo_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "avas_foruns_topicos_arquivo", 
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/".$url["4"]."/".$url["5"]."/".$url["6"]."/".$url["7"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "arquivo", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true 
	  ),*/
	  array(
		"id" => "idforum_topico", // Id do atributo HTML
		"nome" => "idforum", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => 'return $this->url["5"];',
		"banco" => true
	  ),
	  array(
		"id" => "idmatricula_topico", // Id do atributo HTML
		"nome" => "idmatricula", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => '',
		"banco" => true
	  ),
	)
  )								  
);

require '../classes/avas.foruns_novo.class.php';
$forumObj = new Foruns();
$forumObj->set("idpessoa",$usuario["idpessoa"])
		->set("idmatricula",$matricula["idmatricula"])
		//->set("modulo",$url[0])
		->set("modulo",'aluno')
		->set("idava",$ava["idava"]);

/*$sql = "DELETE FROM mensagens_alerta WHERE tipo_alerta = 'forum' AND idmatricula = ".$url[3];
mysql_query($sql);*/

if(isset($url[6])) {
	$forum = $forumObj->set("id",(int) $url[6])
					->set("campos","f.*, a.nome as ava, d.nome as disciplina")
					->RetornarForum();
	if($forum['idforum']) {
	
		if($_POST["acao"] == "salvar_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|1");
	
			$forumObj->set("monitora_onde",$config["monitoramento"]["onde_foruns_topicos"]);
			$forumObj->set("idava",$ava["idava"]);
			$forumObj->set("id",intval($url[6]));
	
			$forumObj->config["banco"] = $config["banco_foruns_topicos"];
			$forumObj->config["formulario"] = $config["formulario_foruns_topicos"];
	
			$forumObj->set("post",$_POST);
			if($_POST[$config["banco_foruns_topicos"]["primaria"]])
				$salvar = $forumObj->ModificarTopico();
			else
				$salvar = $forumObj->CadastrarTopico();
	
			if($salvar["sucesso"]){									
				if($_POST[$config["banco_foruns_topicos"]["primaria"]])  {
					$forumObj->set("pro_mensagem_idioma","modificar_topico_sucesso");
					$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				} else {
					if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|9",NULL)){
						$forumObj->InsereAssinanteTopico($salvar["id"]);
					}
					$forumObj->set("pro_mensagem_idioma","cadastrar_topico_sucesso");
					$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]);
				}
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "responder_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|2");
			$forumObj->set("post",$_POST);
			$salvar = $forumObj->ResponderTopico((int) $url[8]);
			if($salvar["sucesso"]){
				if($forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|mensagens|6",NULL)){
					$forumObj->InsereAssinantesMensagens((int) $url[8]);
				}
			}
			//$forumObj->contabilizar((int) $url[8]);
			$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'respondeu', "forum", (int) $url[6]);
			$matriculaObj->contabilizarForum($matricula['idmatricula'], $ava['idava'], (int) $url[6]);
			
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","responder_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]."/".$url[9]);
				$forumObj->Processando();
			}
		/*} elseif($_POST["acao"] == "moderar_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
			$forumObj->set("post",$_POST);
			$salvar = $forumObj->ModerarTopico(intval($url[7]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","moderar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "moderar_mensagem"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
			$forumObj->set("post",$_POST);
			$salvar = $forumObj->ModerarMensagem(intval($_POST["idmensagem"]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","moderar_mensagem_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "ocultar_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|4");
			$salvar = $forumObj->ocultarTopico(intval($url[7]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","ocultar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "desocultar_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|4");
			$salvar = $forumObj->desocultarTopico(intval($url[7]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","desocultar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "ocultar_mensagem"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|1");
			$salvar = $forumObj->ocultarMensagem(intval($_POST["idmensagem"]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","ocultar_mensagem_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "desocultar_mensagem"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|1");
			$salvar = $forumObj->desocultarMensagem(intval($_POST["idmensagem"]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","desocultar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "bloquear_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|3");
			$salvar = $forumObj->bloquearTopico(intval($url[7]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","bloquear_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "desbloquear_topico"){
			$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|topicos|3");
			$salvar = $forumObj->desbloquearTopico(intval($url[7]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","desbloquear_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "assinar_topico"){
			$salvar = $forumObj->assinarTopico((int) $url[8]);
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","assinar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]."/".$url[9]);
				$forumObj->Processando();
			}
		} elseif($_POST["acao"] == "desassinar_topico"){
			$salvar = $forumObj->desassinarTopico(intval($_POST["idassinatura"]));
	
			if($salvar["sucesso"]){
				$forumObj->set("pro_mensagem_idioma","desassinar_topico_sucesso");
				$forumObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."/".$url[5]."/".$url[6]."/".$url[7]."/".$url[8]."/".$url[9]);
				$forumObj->Processando();
			}*/
		}
		
		switch($url[7]) {
			case "topicos":
				if(isset($url[8])){
					if($url[8] == "cadastrar") {
						$forumObj->verificaPermissao($forum["permissoes"], /*$url[0].*/"aluno|topicos|1");
						require 'idiomas/'.$config['idioma_padrao'].'/foruns.topicos.formulario.php';
						require 'telas/'.$config['tela_padrao'].'/foruns.topicos.formulario.php';
						exit;
					} else {
						$topico = $forumObj->set("campos","f.*, a.nome as ava")
											->RetornarTopico((int) $url[8]);
		
						//print_r2($topico,true);
						if($topico['idtopico']) {
							switch($url[9]) {
								/*case "moderar":
									$forumObj->verificaPermissao($forum["permissoes"], $url[0]."|mensagens|3");
									include("idiomas/".$config["idioma_padrao"]."/foruns.topicos.formulario.php");
									include("telas/".$config["tela_padrao"]."/foruns.topicos.formulario.php");
								break;*/
								/*case "remover":
									$forumObj->verificaPermissao($perfil["permissoes"], $url[2]."|27");
									include("idiomas/".$config["idioma_padrao"]."/remover.foruns.topicos.php");
									include("telas/".$config["tela_padrao"]."/remover.foruns.topicos.php");
								break;*/
								case "mensagens":
									if($url[10] == "cadastrar" || ($url[10] && ($url[11] == "responder" || $url[11] == "moderar"))) {
										if($url[10] && $url[11] == "moderar") {
											$forumObj->set("campos","*");
											$mensagem = $forumObj->RetornarMensagem(intval($url[10]));
										}
										require 'idiomas/'.$config['idioma_padrao'].'/foruns.topicos.mensagens.formulario.php';
										require 'telas/'.$config['tela_padrao'].'/foruns.topicos.mensagens.formulario.php';
										exit;
									} elseif($url[10] == "json" && $url[11] == "curtir") {
										$forumObj->set("post",$_POST);
										echo $forumObj->CurtirTopicoMensagem();
										exit;
									} elseif(isset($url[10]) && $url[11] == "download") {
										$forumObj->set("campos","*");
										$mensagem = $forumObj->RetornarMensagem(intval($url[10]));
										$forumObj->countabilizarDownloadMensagem($mensagem["idmensagem"]);
										require 'telas/'.$config['tela_padrao'].'/foruns.topicos.mensagens.download.php';
									} else {
										//$assinatura = $forumObj->verificaAssinaturaTopico($topico["idtopico"],null,null,$matricula["idmatricula"]);
		
										$respostas = $forumObj->ListarTodasMensagens($topico["idtopico"], $forum["permissoes"][$url[0]."|mensagens|2"]);
										
										$participantes = $forumObj->ParticipantesTopico($topico["idtopico"]);
										
										require 'idiomas/'.$config["idioma_padrao"].'/foruns.topicos.mensagens.php';
										require 'telas/'.$config["tela_padrao"].'/foruns.topicos.mensagens.php';
										exit;
									}
								break;
								case "download":
									$forumObj->countabilizarDownloadTopico($topico["idtopico"]);
									require 'telas/'.$config['tela_padrao'].'/foruns.topicos.download.php';
								break;
								/*case "excluir":
									include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
									$forumObj->RemoverArquivo($url[2]."_".$url[4]."_".$url[6], $url[9], $topico, $idioma);
								break;*/
								default:
									header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]);
									exit();
							}
						} else {
							header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]);
							exit();
						}
					}
				} else {
					if(!$_GET["pag"]) $_GET["pag"] = 1;
					$forumObj->set("pagina",$_GET["pag"]);
					if(!$_GET["qtd"]) $_GET["qtd"] = 30;
					$forumObj->set("limite",intval($_GET["qtd"]));
					$forumObj->set("ordem_campo","idtopico");
					$forumObj->set("ordem","desc");
					$forumObj->set("campos","*");
					$topicos = $forumObj->ListarTodasTopico((int) $url[6], $forum["permissoes"][$url[0]."|topicos|5"]);
	
					$populares = $forumObj->ListarTopicosPopulares((int) $url[6], $forum["permissoes"][$url[0]."|topicos|5"]);
	
					$alunosAtivos = $forumObj->ListarAlunosAtivos((int) $url[6]);
	
					require 'idiomas/'.$config["idioma_padrao"].'/foruns.topicos.php';
					require 'telas/'.$config["tela_padrao"].'/foruns.topicos.php';
					exit;
				}
				break;
				/*case "remover":
					$forumObj->verificaPermissao($perfil["permissoes"], $url[2]."|27");
					include("idiomas/".$config["idioma_padrao"]."/remover.foruns.php");
					include("telas/".$config["tela_padrao"]."/remover.foruns.php");
				break;*/
				/*case "opcoes":
					include("idiomas/".$config["idioma_padrao"]."/opcoes.foruns.php");
					include("telas/".$config["tela_padrao"]."/opcoes.foruns.php");
				break;*/
				/*case "download":
					include("telas/".$config["tela_padrao"]."/download.foruns.php");
				break;*/
				/*case "excluir":
					include("idiomas/".$config["idioma_padrao"]."/excluir.arquivo.php");
					$forumObj->RemoverArquivo($url[2]."_".$url[4], $url[7], $forum, $idioma);
				break;*/
				default:
					header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]);
					exit();
			}
	} else { // default fóruns ***
		header("Location: /".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
		exit();
	}
} else {
	$_GET['q']['1|f.exibir_ava'] = 'S';
	$_GET['q']['4|f.periode_de'] = date('Y-m-d');
	$_GET['q']['5|f.periode_ate'] = date('Y-m-d');
	
	$foruns = $forumObj->set("ordem","desc")
						->set("limite","-1")
						->set("ordem_campo","f.ordem asc, f.idforum")
						->set("campos","f.*, a.nome as ava, d.nome as disciplina")
						->ListarTodasForum();

	$populares = $forumObj->ListarTopicosPopulares();
	
	$alunosAtivos = $forumObj->ListarAlunosAtivos();

	$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "forum");
	
	require 'idiomas/'.$config['idioma_padrao'].'/foruns.php';
	require 'telas/'.$config['tela_padrao'].'/foruns.php';
	exit;
}