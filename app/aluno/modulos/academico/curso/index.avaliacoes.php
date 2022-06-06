<?php
require_once '../classes/avas.class.php';
require '../classes/avaliacoes_novo.class.php';

$avaliacaoObj = new Avaliacoes();
$avaliacaoObj->set("idpessoa",$usuario["idpessoa"])
			 ->set("idmatricula",$matricula["idmatricula"])
			 ->set("modulo",'aluno'/*$url[0]*/)
			 ->set("idava",$ava["idava"]);

if ($_POST["acao"] == "salvar_respostas_prova") {
	$avaliacaoObj->set("id",$_POST['idprova']);
	$avaliacaoObj->set("post",$_POST);
	$avaliacaoObj->set("files",$_FILES);
	$salvar = $avaliacaoObj->salvarRespostasProva();
	if ($salvar["sucesso"]) {
		$matriculaObj->cadastrarHistorioAluno($ava['idava'], "respondeu", "avaliacoes", $_POST['idprova']);
		$avaliacaoObj->set("pro_mensagem_idioma","mensagem_prova_responder_sucesso");
		$avaliacaoObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
		$avaliacaoObj->Processando();
	}
}
	
switch ($url[7]) {
	case "realizar":
		require 'idiomas/'.$config['idioma_padrao'].'/avaliacao.aviso.php';
		require 'telas/'.$config['tela_padrao'].'/avaliacao.aviso.php';
	break;
	case "biometria":
		require 'idiomas/'.$config['idioma_padrao'].'/reconhecimento.php';
		require 'telas/'.$config['tela_padrao'].'/reconhecimento.php';
	break;
	case "fazer":
		if (verificaPermissaoAcesso(true)) {
			if ($url[8] == 'atualizar') {
				echo json_encode(array('data' => date('d/m/Y H:i:s')));
				exit;
			}

			$avaliacaoObj->set("campos","aa.*");
			$avaliacaoObj->set("idmatricula", $matricula["idmatricula"]);
			$avaliacaoObj->set("idavaliacao", (int) $url[6]);
			$avaliacao = $avaliacaoObj->RetornarAvaliacaoProva((int) $url[6]);
			$tentativas = $avaliacaoObj->retornarInformacoesAvaliacaoAluno();

            $dadosMat = $matriculaObj->getMatricula($matricula["idmatricula"]);
            $dadosSindicato = $matriculaObj->retornarSindicato();
            $curso = $matriculaObj->RetornarCurso();

			$dataHoje = new DateTime();
			$de = new DateTime($avaliacao['periode_de'] . '00:00:00');
			$ate = new DateTime($avaliacao["periode_ate"] . '00:00:00');
			
			
			if ($avaliacao['intervalo_tentativas'] && ($tentativas['tentativas'] == 0)) {
				$intervaloDisponivel = true;
			} elseif ($avaliacao["intervalo_tentativas"] && $tentativas['tentativas'] > 0) {
				$dataHoraHoje = new DateTime();
				$tempo = explode(':',  $avaliacao['intervalo_tentativas']);
				$proximaTentativa = (new \DateTime($tentativas['inicio']))->modify('+ ' . $tempo[0] . ' hours' . $tempo[1] . ' minutes' . $tempo[2] . ' seconds');
				$data_formatada = ($proximaTentativa->format('d/m/Y H:i'));
				$intervaloDisponivel = ($dataHoraHoje >= $proximaTentativa) ? true : false;
			} else {
				$intervaloDisponivel = true;
			}

			$podeFazer = $matriculaObj->set('id',$matricula['idmatricula'])->retornarPodeFazerProvaVirtual();
			if(
				(!$avaliacao["qtde_tentativas"] || ($tentativas["tentativas"] < $avaliacao["qtde_tentativas"])) &&
				($de <= $dataHoje && $ate >= $dataHoje) &&
				(!$avaliacao["nota_minima"] || ($tentativas["nota"] < $avaliacao["nota_minima"])) &&
				($avaliacao['exibir_ava'] == 'S') &&
				($intervaloDisponivel) &&
				($podeFazer)
			) {
				$recon = md5($imagemPrincipal['foto'] . date('d/m/Y H'));

				if (
					$recon != $_GET['reconhecimento']
					&& $matricula['biometria_liberada'] != 'S'
					&& $curso['usar_datavalid'] == 'S'
					&& $dadosSindicato['usar_datavalid'] == 'S'
				){
					header("Location: /aluno/academico/curso/{$matricula['idmatricula']}/{$ava['idava']}/avaliacoes/{$url[6]}/biometria");
					exit;
				}

				$prova = $avaliacaoObj->gerarProva((int) $url[6], (int) $matricula["idmatricula"]);

				if ($prova["erro_json"] == "sem_permissao") {
					$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
					$matriculaObj->Processando();
					exit;
				}

				//$matriculaObj->AdicionarHistorico($matriculaObj->idusuario, "prova", "visualizou", NULL, NULL, $prova['idprova']);
				require 'idiomas/'.$config['idioma_padrao'].'/avaliacao.php';
				require 'telas/'.$config['tela_padrao'].'/avaliacao.php';

			} else {
				$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
				echo "<script> alert('Uma nova tentativa de prova será liberada em ".$data_formatada . " no horário de Brasília. Por favor, aguarde!') </script>";
				$matriculaObj->Processando();
				exit;
			}
		}
	break;
	case "tentativas":
		$avaliacao = $avaliacaoObj->RetornarAvaliacaoProva((int) $url[6]);
		if ($url[8] && $url[9] == 'visualizar') {
			if ($url[10]) {
				if ($url[11] == 'arquivoaluno') {
					$arquivo = $avaliacaoObj->retornaArquivoPerguntaAlunoDownload((int) $url[10]);
					require 'telas/'.$config['tela_padrao'].'/avaliacao.arquivo.aluno.php';
					exit;
				} else {
					$arquivo = $avaliacaoObj->retornaArquivoPerguntaProfessorDownload((int) $url[10]);
					require 'telas/'.$config['tela_padrao'].'/avaliacao.arquivo.professor.php';
					exit;
				}
			}
			
			$prova = $avaliacaoObj->retornarMatriculaProva((int) $url[8], $matricula['idmatricula']);
			//$avaliacao = $avaliacaoObj->retornarProvaRespondida($url[8]);
			require 'idiomas/'.$config['idioma_padrao'].'/avaliacao.visualizar.php';
			require 'telas/'.$config['tela_padrao'].'/avaliacao.visualizar.php';
		} else {
			$avaliacoes = $avaliacaoObj->retornarTentativas((int) $url[6], $matricula['idmatricula'], $ava['idava']);
			
			for ($i=0; $i < count($avaliacoes) ; $i++) { 
				$questoes = $avaliacaoObj->questoesRespondidas($avaliacoes[$i]['idprova']);
				$avaliacoes[$i]['total_questoes'] = $questoes['total_questoes'];
				$avaliacoes[$i]['questoes_respondidas'] = $questoes['questoes_respondidas'];
			}
			
			require 'idiomas/'.$config['idioma_padrao'].'/avaliacao.tentativas.php';
			require 'telas/'.$config['tela_padrao'].'/avaliacao.tentativas.php';
		}
	break;
	default:
		$curso = $matriculaObj->set('matricula',$matricula)
							->RetornarCurso();
							
		$podeFazer = $matriculaObj->set('id',$matricula['idmatricula'])
								->retornarPodeFazerProvaVirtual();
								
		$avaliacoes = $avaliacaoObj->retornarAvaliacoes($matricula['idmatricula'], $ava['idava']);

		$pegaIdProva = $avaliacaoObj->retornarTentativas('semid', $matricula['idmatricula'], $ava['idava']);
		if ($pegaIdProva) {
			$prova = $avaliacaoObj->retornarMatriculaProva($pegaIdProva[0]['idprova'], $matricula['idmatricula']);
			$i = 0;
			$qtdRespostaCorreta = 0;
			foreach ($prova['perguntas'] as $ind => $pergunta) {
				$respCorreta = array_filter($pergunta['opcoes'], array($avaliacaoObj, 'qtdRespCorreta'));
				if(count($respCorreta) > 0) {
					$qtdRespostaCorreta++;
				}
			}
		}

		$cursoSindicato = $matriculaObj->RetornarCursoSindicato();
		$matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);
        if ($matricula['oferta_curso']['idfolha'] && 
            $cursoSindicato['certificado_ava'] == 'S' 
        )
        {
			$matricula["alunoAprovadoNotas"] = $matriculaObj->verificaMatriculaAprovadaNotas($matricula['oferta_curso']['porcentagem_minima_disciplinas']);
			$matricula["alunoAprovadoNotasDias"] = $matriculaObj->verificaMatriculaAprovadaNotasDias($matricula['idmatricula'],$matricula['idoferta'], $matricula['idcurso']);
        }
		$podeGerarDiploma = false;
		$situacaoDiplomaExpedido = $matriculaObj->retornarSituacaoDiplomaExpedido();
		$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();

		if ((
			$situacaoDiplomaExpedido['idsituacao'] == $matricula['idsituacao'] ||
			$situacaoConcluido['idsituacao'] == $matricula['idsituacao']
		) &&
		$cursoSindicato['certificado_ava'] == 'S' &&
		(
			$cursoSindicato['renach_obrigatorio'] == 'N' ||
			(
				$cursoSindicato['renach_obrigatorio'] == 'S' &&
				$matricula['renach']
			)
		)
	) {
		$podeGerarDiploma = $matriculaObj->temDiploma($matricula["idmatricula"]);
	}

		$matriculaObj->cadastrarHistorioAluno($ava['idava'], "visualizou", "avaliacoes");

		$matricula['escola'] = $matriculaObj->set('matricula', ['idescola' => $matricula['idescola']])
				->retornarEscola();
				
		require 'idiomas/'.$config['idioma_padrao'].'/avaliacoes.php';
		require 'telas/'.$config['tela_padrao'].'/avaliacoes.php';
	break;
}