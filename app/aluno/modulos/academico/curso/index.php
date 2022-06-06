<?php

if (!empty($url[3])) {
    ## Matrícula
    $idmatricula = (int) $url[3];

    require '../classes/matriculas_novo.class.php';
    require '../classes/pessoas.class.php';
    require '../classes/datavalid.class.php';
    require '../classes/avas.class.php';
    require '../classes/avas.conteudos.class.php';
    require '../classes/avas.simulados.class.php';
    require '../classes/filtros/interface.php';
    require '../classes/filtros/video.php';
    require '../classes/filtros/linkacao.php';
    require '../classes/filtrarVariaveis.class.php';
    require '../classes/videoteca/videoteca.class.php';
    require '../classes/reconhecimento.class.php';

    if (!empty($_POST) && $_POST['funcao'] == 'reconhecimento') {
        require 'index.reconhecimento.php';
        exit;
    }

    $matriculaObj = new Matriculas();
    $matricula = $matriculaObj->set('id', $idmatricula)
        ->set('modulo', $url[0])
        ->set('idpessoa', $usuario['idpessoa'])
        ->retornar();
    if (empty($matricula["idcurso"]) && (int)$matricula["idcurso"] <=0) {
        $url = "/aluno/secretaria/meuscursos";
        echo '<script>
            alert("Esta matrícula não está vinculada ao seu usuário!");
            window.location.href = "' . $url . '";
        </script>';
        exit;
    }

    //Curso Nome
    $curso = new Cursos();
    $curso->set("id", (int)$matricula["idcurso"]);
    $curso->set("campos", "idcurso, nome");
    $cursoNome = $curso->Retornar(false, (int)$matricula["idcurso"]);
    $informacoesTopoCurso['curso_nome'] = $cursoNome["nome"];

    ## Informações para a lib topo_curso
    $informacoesTopoCurso['porcentagem'] = $matriculaObj->porcentagemCursoAtual((int)$matricula['idmatricula']);
    $informacoesTopoCurso['link_pagina_anterior'] = '/' . $url[0] . '/secretaria/meuscursos';
    $informacoesTopoCurso['pagina_anterior'] = 'meus_cursos';
    $informacoesTopoCurso['pagina'] = 'ambiente_estudo';

    require '../classes/gestaoacessos.class.php';
    $gestaoAcessosObj = new GestaoAcessos();
    if (isset($url[4]) && $url[5] <> 'avaliacoes') {
            if( empty($_SESSION['cliente_gestor']) && ( $matricula['idpessoa'] == $_SESSION['cliente_idpessoa']) ){
            $retornoAcesso = $gestaoAcessosObj->contabilizarAcessoMatricula($matricula["idpessoa"], $idmatricula, $url[4]);
        }
    } else {
        // Não contabiliza o acessso
    }

    if ($_GET['contabiliza'] == 'acesso') {
        echo json_encode($retornoAcesso);
        exit();
    }

    if (empty($matricula['data_primeiro_acesso'])) {
        if ($matricula['idpessoa'] == $_SESSION['cliente_idpessoa']) {
            $matriculaObj->atualizaPrimeiroAcesso();

            require '../classes/emailsautomaticos.class.php';
            $emailAutomaticoObj = new Emails_Automaticos();
            $emailAutomatico = $emailAutomaticoObj->retornaEmailPorTipo('priac');

            if (!empty($emailAutomatico)) {
                $sindicatos = $emailAutomaticoObj->retornarSindicatosEmails($emailAutomatico['idemail']);
                $ofertas = $emailAutomaticoObj->retornarOfertasEmails($emailAutomatico['idemail']);
                $cursos = $emailAutomaticoObj->retornarCursosEmails($emailAutomatico['idemail']);

                $flags['sindicato'] = true;
                $flags['oferta'] = true;
                $flags['curso'] = true;

                if (!empty($sindicatos)) {
                    $flags['sindicato'] = false;

                    foreach ($sindicatos as $key => $sindicato) {
                        if ($sindicato['idsindicato'] == $matricula['idsindicato']) {
                            $flags['sindicato'] = true;
                        }
                    }
                }

                if (!empty($ofertas)) {
                    $flags['oferta'] = false;

                    foreach ($ofertas as $key => $oferta) {
                        if ($oferta['idoferta'] == $matricula['idoferta']) {
                            $flags['oferta'] = true;
                        }
                    }
                }

                if (!empty($cursos)) {
                    $flags['curso'] = false;

                    foreach ($cursos as $key => $curso) {
                        if ($curso['idcurso'] == $matricula['idcurso']) {
                            $flags['curso'] = true;
                        }
                    }
                }

                if ($flags['sindicato'] && $flags['oferta'] && $flags['curso']) {
                    $pessoaObj = new Pessoas();
                    $coreObj = new Core();

                    $pessoaObj->set('id', $matricula['idpessoa']);
                    $pessoaObj->set('campos', 'p.*');
                    $pessoa = $pessoaObj->retornar();

                    if (!empty($pessoa)) {
                        if (!empty($pessoa['receber_email']) && $pessoa['receber_email'] == 'S') {
                            $emailAutomaticoObj->enviarEmailAutomaticoPessoa($emailAutomatico, $pessoa, $pessoaObj, $coreObj);
                        }
                    }
                }
            }
        }
    }
    if (empty($matricula['idmatricula'])) {
        header('Location: /' . $url[0] . '/secretaria/meuscursos');
        exit;
    }


    //INÍCIO CONTRATOS PARA SEREM ACEITOS
    $matriculaObj->criarContratosPendentes($matricula['idsindicato'], $matricula['idcurso']);
    $contratoPendente = $matriculaObj->retornarUltimoContratoPendente();

    if ($contratoPendente['idmatricula_contrato']) {
        if ($_POST['acao'] == 'concordar' && $_POST['contrato']) {
            $aceito = $matriculaObj->aceitarContratoPendente($_POST['contrato']);

            if (!$aceito) {
                echo '<script>alert("Ocorreu um erro ao aceitar o contrato!");</script>';
            } else {
                $matriculaObj->alterarSituacaoContratosAceitos((int)$contratoPendente['idmatricula']);
                echo '<script>
                    alert("Seu contrato foi aceito com sucesso!");
                    window.location.href = "' . $_SERVER['REQUEST_URI'] . '";
                </script>';
            }
        }

        include 'idiomas/' . $config['idioma_padrao'] . '/administrar.contrato.php';
        include 'telas/' . $config['tela_padrao'] . '/administrar.contrato.php';
        exit;
    }
    //FIM CONTRATOS PARA SEREM ACEITOS

    $conteudoObj = new Conteudos();
    $conteudoObj->set('idava', $matriculaObj->retornarAvas());
    $reconhecimentoObj = new Reconhecimento();
    $imagemPrincipal = $reconhecimentoObj->retornaImagemPrincipal($matricula['idmatricula']);
    $cursoMatricula = $matriculaObj->RetornarCurso();
    $sindicatoMatricula = $matriculaObj->RetornarSindicato();
    if (
        $matricula["biometria_liberada"] != 'S'
        && empty($imagemPrincipal)
        && $cursoMatricula["usar_datavalid"] == "S"
        && $sindicatoMatricula["usar_datavalid"] == "S"
    ) {
        require 'idiomas/' . $config['idioma_padrao'] . '/index.reconhecimento.php';
        require 'telas/' . $config['tela_padrao'] . '/index.reconhecimento.php';
        exit; 
    }

    $usuario['escola'] = $matriculaObj->retornarEscola();

    $visualizacoesSituacao = $matriculaObj->retornarVisualizacoesSituacao($matricula['idsituacao']);
    $acesso_ava['pode_acessar_ava'] = false;
    if ($visualizacoesSituacao[27]) {
        $acesso_ava = $matriculaObj->retornarAcessoAva();
    }

    //Para ter acesso tem que ter matrícula e a sindicato da matrícula tem que estar com acesso ao AVA liberado
    if (
        $matricula['idmatricula']
        && $acesso_ava['pode_acessar_ava']
        && $matricula['acesso_ava'] == 'S'
        && ($matricula['detran_situacao'] == 'LI' || empty($usuario['escola']['detran_codigo']))
    ) {
        $curriculo = $matriculaObj->retornaCurriculo();
        $porcentagemAva = $matriculaObj->retornaPorcentagemAva((int)$url[4]);
        $informacoesTopoCurso['porcentagem_ava'] = $curriculo['porcentagem_ava'];
        ## /Informações para a lib topo_curso

        if (isset($url[4])) {
            $ava = $matriculaObj->retornarAvaMatricula((int)$url[4]);
            $ava['data_inicio_ava'] = $matriculaObj->matriculaAvaPorcentagem((int)$url[4], (int)$matricula['idmatricula'])['data_ini'];
            $ava['avaliacao_pendente'] = $matriculaObj->possuiAvaliacaoPendente($matricula['idmatricula'], (int)$url[4]);
            $ava['porcentagem_ava'] = $matriculaObj->retornaPorcentagemAva($ava['idava']);
            if (!empty($ava['contabilizar_datas']) && $ava['contabilizar_datas'] == "S") {
                $dataIni = somaHoras($porcentagemAva['data_ini'], $ava['carga_horaria_min']);
                $dataHoj = getCurrentDate();
                $cargaCompleta = ($dataIni < $dataHoj) ? true : false;
            } else {
                $cargaCompleta = false;
            }
            if ($ava['idava']) {
                switch ($url[5]) {
                    case 'rota':
                        if ($url[6] && substr($url[6], -4) == '.swf' && ($matricula['idcurso'] == 28 || $matricula['idcurso'] == 21)) {

                            if ($matricula['idcurso'] == 21) {
                                $arquivo = '/discovirtual/avas/avaliacao_alfama/assets/res/geral/' . $url[6];
                            } else {
                                $arquivo = '/discovirtual/avas/terrenos_alfama/assets/res/geral/' . $url[6];
                            }

                            header('Location: ' . $arquivo);
                            exit;
                        }
                        require 'index.rota.objeto.php';
                        exit;
                    case 'arquivos':
                        require 'index.arquivos.php';
                        exit;
                    case 'diploma':
                        $certificado = new Certificados;
                        $paginas = $certificado->set('idpessoa', $usuario['idpessoa'])->gerarCertificado($matricula['idmatricula'], new Matriculas, (int) $url[6]);
                        $certificado->downloadPages($paginas);
                        exit;
                    case 'foruns':
                        require 'index.foruns.php';
                        exit;
                    case 'chats':
                        require 'index.chats.php';
                        exit;
                    case 'mensagens':
                        require 'index.mensagens.php';
                        exit;
                    case 'simulado':
                        require 'index.simulados.php';
                        exit;
                    case 'avaliacoes':
                        $reconhecimentoObj = new Reconhecimento();
                        $imagemPrincipal = $reconhecimentoObj->retornaImagemPrincipal($matricula['idmatricula']);
                        require 'index.avaliacoes.php';
                        exit;
                    case 'favoritos':
                        require 'index.favoritos.php';
                        exit;
                    case 'anotacoes':
                        require 'index.anotacoes.php';
                        exit;
                    case 'meusprofessores':
                        require 'index.meusprofessores.php';
                        exit;
                    case 'colegasdesala':
                        require 'index.colegasdesala.php';
                        exit;
                    case 'faq':
                        require 'index.faq.php';
                        exit;
                    case 'json':
                        //require 'idiomas/'.$config['idioma_padrao'].'/json.php';
                        require 'telas/' . $config['tela_padrao'] . '/json.php';
                        exit;
                    case 'link':
                        require 'index.link.php';
                        exit;
                    default:
                        require 'index.rota.php';
                        exit;
                }
            } else {
                header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula']);
                exit;
            }
        } else {
            ## Avas e disciplinas
            $avas = $matriculaObj->retornarAvas();
            $avaliacoes = $matriculaObj->avaliacoesConcluidas($matricula['idmatricula']);
            $disciplinasAvas = $matriculaObj->retornarAvasDisciplinas($avas);
            ## /Avas e disciplinas
            $idAvas = array();
            foreach ($avas as $ava) {
                $idAvas[] = $ava['idava'];

            }
            ## Quadro de avisos
            require '../classes/quadrosavisos.class.php';
            $quadroDeAvisoObj = new Quadros_Avisos();
            $quadroDeAvisoObj->set('limite', 5);
            $quadroDeAvisoObj->set('ordem_campo', 'qa.data_de');
            $quadroDeAvisoObj->set('ordem', 'asc');
            $quadroDeAvisoObj->set('campos', 'qa.*');
            $quadroDeAvisos = $quadroDeAvisoObj->retornarQuadroDeAvisosDaMatricula($matricula['idoferta'], $matricula['idescola'], $matricula['idcurso'], 'cur');
            ## /Quadro de avisos

            //Curso Nome
            $curso = new Cursos();
            $curso->set("id", $matricula["idcurso"]);
            $curso->set("campos", "nome");
            $cursoN = $curso->Retornar();

            $informacoesTopoCurso['curso_nome'] = $cursoN["nome"];

            ## Chats
            require '../classes/chat_novo.class.php';
            $chatsObj = new Chat(new Core);

            $_GET['cmp'] = 'inicio_entrada_aluno';
            $_GET['ord'] = 'asc';
            $_GET['qtd'] = -1;
            $chats = $chatsObj->allConversation($idAvas, true)->getResult();

            unset($_GET['cmp']);
            unset($_GET['ord']);
            unset($_GET['qtd']);

            ## Oferta Curso
            $matricula['oferta_curso'] = $matriculaObj->retornaDadosOfertaCurso($matricula['idoferta'], $matricula['idcurso']);

            require 'idiomas/' . $config['idioma_padrao'] . '/index.php';
            require 'telas/' . $config['tela_padrao'] . '/index.php';
            exit;
        }
    } else {
        header('Location: /' . $url[0] . '/secretaria/meuscursos');
        exit;
    }
} else {
    header('Location: /' . $url[0] . '/secretaria/meuscursos');
    exit;
}
