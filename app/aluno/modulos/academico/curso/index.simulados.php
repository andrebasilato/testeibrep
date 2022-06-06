<?php
if(!$ava['simulados_apartirde'] || $ava['simulados_apartirde'] == '0000-00-00') 
	$ava["simulados_apartirde"] = date("Y-m-d");

if($ava["simulados_apartirde"] <= date("Y-m-d")) {
	if($ava["simulados_link"]) {
		$matriculaObj->contabilizarSimulado($matricula['idmatricula'], $ava['idava'], 0);
		header('Location: '.$ava["simulados_link"]);
	} else {
		$simuladoObj = new Simulados();
		$simuladoObj->set("idpessoa",$usuario["idpessoa"])
					->set("idmatricula",$matricula["idmatricula"])
					->set("modulo",'aluno'/*$url[0]*/)
					->set("idava",$ava["idava"]);

		switch ($url[7]) {
			case "fazer":
				if ($url[8] == 'atualizar') {
					echo json_encode(array('data' => date('d/m/Y H:i:s')));
					exit;
				}

				$simuladoObj->set("campos","aa.*");
				$simuladoObj->set("idmatricula", $matricula["idmatricula"]);
				$simuladoObj->set("idsimulado", (int) $url[6]);

				$simulado = $simuladoObj->retornarSimuladoProva((int) $url[6]);
				
				$dataHoje = strtotime(date("Y-m-d"));
				$de = strtotime($simulado["periode_de"]);
				$ate = strtotime($simulado["periode_ate"]);
				
				if ($de <= $dataHoje && $ate >= $dataHoje) {
					$prova = $simuladoObj->gerarSimulado((int) $url[6], (int) $matricula["idmatricula"]);
					if ($prova["erro_json"] == "sem_permissao") {
						$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
						$matriculaObj->Processando();
						exit;
					}

					$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', 'simulado', (int) $url[6]);
					require 'idiomas/'.$config['idioma_padrao'].'/simulado.php';
					require 'telas/'.$config['tela_padrao'].'/simulado.php';

				} else {
					$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
					$matriculaObj->Processando();
					exit;
				}
			break;
            case "tentativas":
                $simulado = $simuladoObj->retornaDadosSimulado((int) $url[6]);
                if ($url[8] && $url[9] == 'visualizar') {
                    $visualizarSimulado = $simuladoObj->retornaMatriculaSimulado((int) $url[8], $matricula['idmatricula']);
                    require 'idiomas/'.$config['idioma_padrao'].'/simulado.visualizar.php';
                    require 'telas/'.$config['tela_padrao'].'/simulado.visualizar.php';
                } else {
                    $tentativas = $simuladoObj->retornaTentativas((int) $url[6], $matricula['idmatricula'], $ava['idava'], false);
					require 'idiomas/'.$config['idioma_padrao'].'/simulado.tentativas.php';
                    require 'telas/'.$config['tela_padrao'].'/simulado.tentativas.php';
                }
                exit;
            break;
			case "resultado":
				if ($_POST["acao"] == "salvar_respostas_prova") {
                    $simuladoObj->set("id",$_POST['idmatricula_simulado']);
					$simuladoObj->set("post",$_POST);
					$salvar = $simuladoObj->salvaRespostasSimulado((int) $_POST['idmatricula_simulado'], $matricula['idmatricula']);
                    if ($salvar["sucesso"]) {
					    $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'respondeu', "simulado", $_POST['idmatricula_simulado']);
					    $matriculaObj->contabilizarSimulado($matricula['idmatricula'], $ava['idava'], $_POST['idsimulado']);
                        $matriculaObj->set("pro_mensagem_idioma","mensagem_simulado_responder_sucesso");
                        $matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
                        $matriculaObj->Processando();
                    }
				}
			break;
			default:
				$curso = $matriculaObj->set('matricula',$matricula)
									->RetornarCurso();
									
				$simuladoObj->Set("idava",$ava['idava']);
				$simuladoObj->Set("limite",-1);
				$simuladoObj->Set("ordem_campo",'idsimulado');
				$simuladoObj->Set("ordem",'DESC');
				$simuladoObj->Set("campos","aa.*, 
                                           a.nome as ava,
                                           (SELECT 
                                               COUNT(1) 
                                            FROM 
                                               matriculas_simulados 
                                            WHERE idmatricula = '{$matricula["idmatricula"]}'
                                            AND ativo = 'S'
                                            AND idsimulado = aa.idsimulado ) as tentativas ");	
				$simulados = $simuladoObj->ListarTodasSimuladoExibidos();
				$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', 'simulado');

				require 'idiomas/'.$config['idioma_padrao'].'/simulados.php';
				require 'telas/'.$config['tela_padrao'].'/simulados.php';
			break;
		}
	}
}