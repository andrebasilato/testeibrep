<?

	include("../classes/provaspresenciais.class.php");
	include("../classes/provassolicitadas.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");

	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");

	$linhaObj = new Provas_Presenciais();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);

	$feriadoObj = new Feriados();

	$objSolicitacao = new Provas_Solicitadas();
	$objSolicitacao->Set("idusuario",$usuario["idusuario"]);
	$objSolicitacao->Set("monitora_onde",$config["monitoramento"]["onde"]);

	if($_POST["acao"] == "salvar"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
		if($_POST["acao_url"]){
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2]."?".base64_decode($_POST["acao_url"]);
		}else{
			$url_redireciona = "/".$url[0]."/".$url[1]."/".$url[2];
		}

		$linhaObj->Set("post",$_POST);
		if ($_POST[$config["banco"]["primaria"]]) {
			$salvar = $linhaObj->Modificar();
		} else {
			$salvar = $linhaObj->Cadastrar();
		}

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
	} elseif($_POST["acao"] == "salvar_lista_comparecimento"){
		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");
		$linhaObj->Set("post",$_POST);
		$salvar = $linhaObj->salvarComparecimentosProva();
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","salvar_comparecimento_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
			$linhaObj->Processando();
		}
	} elseif($_POST["acao"] == "adicionar_matriculas"){

		$linhaObj->Set("post",$_POST);
		$linhaObj->Set("id",(int)$url[3]);
		/*foreach($_POST['matriculas'] as $matricula) {
			$dados = explode('|',$matricula);exit;
			$salvar = $linhaObj->adicionarMatriculas($dados[0],$dados[1],$dados[2]);
		}*/

        if ($_POST['idescola_idlocal']) {
            $dados = explode('|',$_POST['matricula']);
            $salvar = $linhaObj->adicionarMatriculas($dados[0],$dados[1],$_POST['idescola_idlocal']);

            if ($salvar) {
                $linhaObj->Set("pro_mensagem_idioma","adicionar_matriculas_sucesso");
                $linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]);
                $linhaObj->Processando();
            }
        } else {
            $salvar["erros"][] = 'escolha_escola_local';
        }
	} elseif($_POST["acao"] == "marcar_disciplinas"){

		$objSolicitacao->Set("post",$_POST);
		$objSolicitacao->Set("id",(int)$_POST['id_solicitacao_prova']);

		$salvar = $objSolicitacao->associarDisciplinas();

		if ($salvar['sucesso']) {
			$linhaObj->Set("pro_mensagem_idioma","marcar_disciplinas_sucesso");
		}

		$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$_POST['idprova_presencial']."/abrirlista");
		$linhaObj->Processando();
	}


	if(isset($url[3])){
		if($url[3] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

			unset($config['formulario'][0]['campos'][7]);
			$linhaObj->Set("config",$config);

			$feriadoObj->Set("campos","DATE_FORMAT(data,'%Y') as ano,
				DATE_FORMAT(data,'%m') as mes, DATE_FORMAT(data,'%d') as dia, nome");
			$arrayFeriados = $feriadoObj->ListarTodas();
			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {

			$linhaObj->Set("id",(int)$url[3]);
			$linhaObj->Set("campos","pr.*");
			$linha = $linhaObj->Retornar();

			if($linha) {
				switch ($url[4]) {
					case "editar":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");
						unset($config['formulario'][0]['campos'][6]);
						$linhaObj->Set("config",$config);

						$feriadoObj->Set("campos","DATE_FORMAT(data,'%Y') as ano,
						DATE_FORMAT(data,'%m') as mes, DATE_FORMAT(data,'%d') as dia, nome");
						$arrayFeriados = $feriadoObj->ListarTodas();

						include("idiomas/".$config["idioma_padrao"]."/formulario.php");
						include("telas/".$config["tela_padrao"]."/formulario.php");
						break;
					case "abrirlista":

                        if ($url[5] == 'adicionar_lista_pre') {

                            $url_quebrada = explode('|', $url[6]);
                            $idmatricula_lista = $url_quebrada[0];

                            if ($idmatricula_lista) {
                                $provaSolicitadaObj = new Provas_Solicitadas();
                                $provaSolicitadaObj->set('idmatricula', (int)$idmatricula_lista);
                                $provaSolicitadaObj->set('modulo', $url[0]);
                                $infoCursoEscola = $provaSolicitadaObj->retornarCursoEscola();

                                $linhaObj->Set("gestor_sindicato",$usuario["gestor_sindicato"]);
                                $linhaObj->set('campos', 'p.idescola, pp.data_realizacao, p.nome_fantasia');
                                $escolas = $linhaObj->retornarEscolasProvasDisponiveisGestor($infoCursoEscola['idsindicato'], $url[3]);
                                $linhaObj->set('campos', 'l.idlocal, pp.data_realizacao, l.nome');
                                $locais = $linhaObj->retornarLocaisProvasDisponiveisGestor($infoCursoEscola['idsindicato'], $url[3]);
                                $matriculaObj = new Matriculas;
                                $matriculaObj->Set('id', $idmatricula_lista);
                                $disciplinas = $matriculaObj->RetornarDisciplinas(0);

                            }

                            include("idiomas/".$config["idioma_padrao"]."/adicionar.lista.pre.php");
                            include("telas/".$config["tela_padrao"]."/adicionar.lista.pre.php");

                            exit;
                        }

						$alunos = $linhaObj->retornarAlunosProva();
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3");

						include("idiomas/".$config["idioma_padrao"]."/lista.presenca.php");
						include("telas/".$config["tela_padrao"]."/lista.presenca.abrir.php");
						break;

					case "disciplinas":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5");

						if (! $_GET['idmatricula']) {
							header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
					   		exit();
						}

						$disciplinas = $linhaObj->retornarDisciplinasAluno((int)$_GET['idmatricula']);
						$disciplinasSolicitacao = $objSolicitacao->retornarDisciplinas((int)$_GET['id_solicitacao_prova']);
						$idmatricula = $_GET['idmatricula'];

						include("idiomas/".$config["idioma_padrao"]."/disciplinas.php");
						include("telas/".$config["tela_padrao"]."/disciplinas.php");

						break;
					case "imprimirlistapresenca":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4");
						$linhaObj->Set("url","/".$url[0]."/relatorios/provaspresenciais_relatorio/?id_prova_presencial=".$linha['id_prova_presencial']);
						$linhaObj->Processando();
						break;
					case "remover":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6");
						$alunos = $linhaObj->retornarQtdeAlunosProva();
						include("idiomas/".$config["idioma_padrao"]."/remover.php");
						include("telas/".$config["tela_padrao"]."/remover.php");
						break;
					case "opcoes":
						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
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
        $linhaObj->Set("pagina", $_GET["pag"]);
        if (!$_GET["ord"]) $_GET["ord"] = "desc";
        $linhaObj->Set("ordem", $_GET["ord"]);
        if (!$_GET["qtd"]) $_GET["qtd"] = 30;
        $linhaObj->Set("limite", intval($_GET["qtd"]));
        if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
        $linhaObj->Set("ordem_campo", $_GET["cmp"]);

		$linhaObj->Set("campos","pr.*, mnt.nome as tipo");
		$linhaObj->Set("gestor_sindicato",$usuario["gestor_sindicato"]);
		$dadosArray = $linhaObj->ListarTodas();
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");
	}

?>
