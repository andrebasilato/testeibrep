<?php
require '../classes/avaliacoes.class.php';
require '../classes/formulasnotas.class.php';
require 'config.php';
require 'config.formulario.php';
require 'config.listagem.php';
require '../classes/matriculas.class.php';
require '../classes/folhasregistrosdiplomas.class.php';
//Incluimos o arquivo com variaveis padrão do sistema.
require 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';
$solicitacaoDeclaracaoObj = new SolicitacoesDeclaracoes();
$matriculaObj = new Matriculas();
$matriculaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$matriculaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde'])
    ->set('modulo', $url[0]);

if (array_key_exists(3, $url)) {
    $idmatricula = intval(soNumeros($url[3]));
    if ($idmatricula > 0) {
        $matriculaObj->Set("id", $idmatricula);
        $matricula = $matriculaObj->retornar();
    }
}
if (array_key_exists(5, $url)) {
    if ($url[4] == "ajax_cidades") {
        if ($_REQUEST['idestado']) {
            $matriculaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
        } else {
            $matriculaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
        }
        exit;
    } elseif ($url[4] == "ajax_cidades_curso_anterior") {
        if ($_REQUEST['curso_anterior_idestado']) {
            $matriculaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['curso_anterior_idestado']), "idestado", "idcidade, nome", "ORDER BY nome");
        } else {
            $matriculaObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
        }
        exit;
    } elseif ($url[4] == "ajax_cidades_ensino_medio") {
        if ($_REQUEST['idestado_ensino_medio']) {
            $matriculaObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado_ensino_medio']), "idestado", "idcidade, nome", "ORDER BY nome");
        } else {
            $matriculaObj->RetornarJSON("cidades", $url[6], "idestado", "idcidade, nome", "ORDER BY nome");
        }
        exit;
    }
}

if (array_key_exists(3, $url)) {
    if ('novamatricula' === $url[3]) {
        $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
        include("novamatricula.index.php");
    } elseif ($url[3] === "json") {
        include("telas/" . $config["tela_padrao"] . "/json.php");
    } elseif ($url[3] === "detran_certificados") {
        $matriculaObj->Set("pagina", $_GET["pag"]);
        if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
        $matriculaObj->Set("ordem", $_GET["ord"]);
        if (!$_GET["qtd"]) $_GET["qtd"] = 30;
        $matriculaObj->Set("limite", intval($_GET["qtd"]));
        if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
        require_once '../classes/detran.class.php';
        $detanObj = new Detran();
        $estadosDetran = $detanObj->listarEstadosIntegrados();

        $where = "cw.fim = 'S' and m.detran_certificado = 'N'";
        if($estadosDetran){
            $where .= " and (";
            foreach($estadosDetran as $siglaEstado => $idEstado){
                $cursos = implode(', ', array_keys($GLOBALS['detran_tipo_aula'][$siglaEstado]));
                if($detanObj->obterSituacaoIntegracao($idEstado)) {
                    $relacao[] = "(e.idestado = {$idEstado} and m.idcurso in ($cursos)) ";
                }
            }
            $where .= implode('OR  ', ($relacao));
            $where .= ")";
        }

        $matriculaObj->Set("ordem_campo", $_GET["cmp"]);
        $matriculaObj->Set("campos", "m.*, p.nome as aluno, p.documento");
        $matriculaObj->Set("groupby", "m.idmatricula");
        $matriculaObj->Set('incluirPessoas', true);
        $matriculaObj->Set('incluirEscolas', true);
        $matriculaObj->Set('incluirWorkflow', true);
        $matriculaObj->Set('where', $where);

        $dadosArray = $matriculaObj->ListarTodas();
        include("idiomas/" . $config["idioma_padrao"] . "/listagem.detran.php");
        include("telas/" . $config["tela_padrao"] . "/listagem.detran.php");
    } elseif ($url[3] === "falha_biometrica") {
        include_once '../classes/reconhecimento.class.php';
        $reconhecimentoObj = new Reconhecimento;
        $dadosArray = $reconhecimentoObj->ListarFalhas();
        include("idiomas/" . $config["idioma_padrao"] . "/index.php");
        include("telas/" . $config["tela_padrao"] . "/listagem.falha.biometrica.php");
    } else {
        if ($matricula['idmatricula']) {
            $se_historico__ = $matriculaObj->se_historico();
            $matricula["historico"] = $se_historico__;
            $matricula["situacao"] = $matriculaObj->retornarSituacao($matricula['idsituacao']);

            switch ($url[4]) {
                case "enviar_credito_detran":
                case "reenviar_credito_detran":
                    require_once '../classes/detran.class.php';
                    $escola = $matriculaObj->RetornarEscola();
                    $detanObj = new Detran();
                    $detranStr = 'Detran-'.strtoupper($escola['uf']);
                    $retornoDetran = [
                        'erro' => true,
                        'mensagem' => $detranStr.' não respondeu.'
                    ];
                    if ($detanObj->obterSituacaoIntegracao($escola['idestado'])) {
                        $function = "\$dados = \$detanObj->DadosCredito{$escola['uf']}({$matricula["idmatricula"]});";
                        if (method_exists($detanObj, "DadosCredito{$escola['uf']}")) {
                            eval($function);
                            if (count($dados) == 1 and method_exists($detanObj, "Creditos{$escola['uf']}")) {
                                $function = "\$retornoDetran = \$detanObj->Creditos{$escola['uf']}(\$dados[0]);";
                                eval($function);
                                if (!empty($retornoDetran['mensagem']) && !$retornoDetran['falha_tecnica'])
                                    $retornoDetran['mensagem'] =  '<b>'.$detranStr.' respondeu:</b><br> '.$retornoDetran['mensagem'];
                                if (!$retornoDetran['erro'])
                                    $retornoDetran['mensagem'] = '<b>O Oráculo informa:</b><br> Créditos ' . (($dados['detran_certificado'] == 'N') ? 'enviado' : 'reenviado') . ' ao '.$detranStr.'!';
                            }
                        }
                    }
                    echo json_encode($retornoDetran);
                    exit;
                case "enviar_certificado_detran":
                case "reenviar_certificado_detran":
                    require_once '../classes/detran.class.php';
                    $detanObj = new Detran();
                    $escola = $matriculaObj->RetornarEscola();
                    $detranStr = 'Detran-'.strtoupper($escola['uf']);
                    $retornoDetran = [
                        'erro' => true,
                        'mensagem' => $detranStr.' não respondeu.'
                    ];

                    if ($detanObj->obterSituacaoIntegracao($escola['idestado'])) {
                        $function = "\$dados = \$detanObj->DadosCertificado{$escola['uf']}({$matricula["idmatricula"]});";
                        if (method_exists($detanObj, "DadosCertificado{$escola['uf']}")) {
                            eval($function);
                            if (count($dados) == 1 and method_exists($detanObj, "Certificado{$escola['uf']}")) {
                                $function = "\$retornoDetran = \$detanObj->Certificado{$escola['uf']}(\$dados[0]);";
                                eval($function);
                                if (!empty($retornoDetran['mensagem']) && !$retornoDetran['falha_tecnica'])
                                    $retornoDetran['mensagem'] =  '<b>'.$detranStr.' respondeu:</b><br> '.$retornoDetran['mensagem'];
                                if (!$retornoDetran['erro'])
                                    $retornoDetran['mensagem'] = '<b>O Oráculo informa:</b><br> Certificado ' . (($dados['detran_certificado'] == 'N') ? 'enviado' : 'reenviado') . ' ao '.$detranStr.'!';
                            }
                        }
                    }

                    echo json_encode($retornoDetran);
                    exit;
                case "enviar_cancelamento_detran":
                case "reenviar_cancelamento_detran":
                    require_once '../classes/detran.class.php';
                    $escola = $matriculaObj->RetornarEscola();
                    $detanObj = new Detran();
                    $detranStr = 'Detran-'.strtoupper($escola['uf']);
                    $retornoDetran = [
                        'erro' => true,
                        'mensagem' => $detranStr.' não respondeu.'
                    ];
                    if ($detanObj->obterSituacaoIntegracao($escola['idestado'])) {
                        $function = "\$dados = \$detanObj->DadosCancelamento{$escola['uf']}({$matricula["idmatricula"]});";
                        if (method_exists($detanObj, "DadosCancelamento{$escola['uf']}")) {
                            eval($function);
                            if (count($dados) == 1 and method_exists($detanObj, "Cancelamento{$escola['uf']}")) {
                                $function = "\$retornoDetran = \$detanObj->Cancelamento{$escola['uf']}(\$dados[0]);";
                                eval($function);
                                if (!empty($retornoDetran['mensagem']) && !$retornoDetran['falha_tecnica'])
                                    $retornoDetran['mensagem'] =  '<b>'.$detranStr.' respondeu:</b><br> '.$retornoDetran['mensagem'];
                                if (!$retornoDetran['erro'])
                                    $retornoDetran['mensagem'] = '<b>O Oráculo informa:</b><br> Cancelamento enviado ao '.$detranStr.'!';
                            }
                        }
                    }
                    echo json_encode($retornoDetran);
                    exit;
                case "gerar_historico":
                    include("telas/" . $config["tela_padrao"] . "/administrar.menu.gerar.historico.php");
                    exit;
                case "administrar": // *****
                    $situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();
                    $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    include("administrar.php");
                    break;
                case "downloaddocumento":
                    $documentos = $matriculaObj->retornarDocumentosPorMatricula((int)$url['3']);
                    if (count($documentos) == 1) {
                        include_once "telas/{$config["tela_padrao"]}/administrar.download.documento.nomeado.php";
                    } else {
                        include_once "telas/{$config["tela_padrao"]}/administrar.download.documentos.zipado.php";
                    }
                    break;
                case "dossie":
                    $matriculaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);
                    $matricula['oferta'] = $matriculaObj->RetornarOferta();
                    $matricula['curso'] = $matriculaObj->RetornarCurso();
                    $matricula['escola'] = $matriculaObj->RetornarEscola();
                    $matricula['turma'] = $matriculaObj->RetornarTurma();
                    $matricula['sindicato'] = $matriculaObj->RetornarSindicato();
                    $matricula['vendedor'] = $matriculaObj->RetornarVendedor();
                    $matricula['pessoa'] = $matriculaObj->RetornarPessoa();
                    $matricula["documentos"] = $matriculaObj->RetornarDocumentos();
                    $matricula["contas"] = $matriculaObj->RetornarContas();
                    $matricula["curriculo"] = $matriculaObj->RetornarCurriculo();
                    $matricula["disciplinas"] = $matriculaObj->RetornarDisciplinas($matricula["curriculo"]['media']);
                    $matricula["solicitacoes"] = $matriculaObj->RetornarProvasSolicitadas();

                    $situacaoRenegociadaConta = $matriculaObj->retornarSituacaoRenegociadaConta();
                    $situacaoCanceladaConta = $matriculaObj->retornarSituacaoCanceladaConta();
                    $situacaoTransferidaConta = $matriculaObj->retornarSituacaoTransferidaConta();

                    $matricula["documentos_pendentes"] = $matriculaObj->retornarDocumentosPendentes($matricula["idmatricula"], $matricula["escola"]["idsindicato"], $matricula["curso"]["idcurso"]);

                    $matriculaObj->Set("idpessoa", $matricula["idpessoa"]);
                    $matriculaObj->Set("idmatricula", $matricula["idmatricula"]);
                    $matricula["andamento"] = $matriculaObj->retornarAndamento();

                    $contribuicao = $matriculaObj->retornarContribuicao($matricula["idmatricula"], $matricula["idpessoa"], $matricula["disciplinas"][0]["idava"]);
                    $porcentagem = $matriculaObj->retornarPorcentagem($matricula["idmatricula"]);
                    $simulados = $matriculaObj->retornaSimuladosRealizadosMatricula($matricula["idmatricula"], $matricula["disciplinas"][0]["idava"]);

                    require '../classes/reconhecimento.class.php';
                    $reconhecimentoObj = new Reconhecimento();
                    $imagensPriDV = $reconhecimentoObj->retornaImagensPrincipaisDatavalid($url[3]);

                    include("../assets/plugins/MPDF54/mpdf.php");

                    $mpdf = new mPDF('c', 'A4');

                    ob_start();

                    include("idiomas/" . $config["idioma_padrao"] . "/dossie.php");
                    include("telas/" . $config["tela_padrao"] . "/dossie.php");

                    $saida = ob_get_contents();
                    ob_end_clean();

                    $arquivo_nome = "../storage/temp/dossie_" . $url[3] . ".pdf";

                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
                    set_time_limit(0);

                    $mpdf->use_kwt = true;
                    $css = ".quebra_pagina {page-break-after:always;}";

                    $mpdf->WriteHTML($css, 1);
                    $mpdf->WriteHTML($saida);

                    $mpdf->Output($arquivo_nome, "F");

                    header("Content-type: " . filetype($arquivo_nome));
                    header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
                    header('Content-Length: ' . filesize($arquivo_nome));
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    readfile($arquivo_nome);

                    exit;
                case 'historico_escolar':
                    $sindicatoCurso = $matriculaObj->RetornarCursoSindicato();

                    if (is_numeric($sindicatoCurso['idhistorico_escolar'])) {
                        $historico = new Historicos();
                        $resultado = $historico->gerarCertificado((int)$url[3], $matriculaObj);
                        $historico->Set("idmatricula", (int)$url[3]);
                        $historico->downloadPages($resultado);
                        exit;
                    }

                    $boletim = new Boletim(new Avaliacoes);
                    $boletim['idmatricula'] = (int)$url[3];
                    $boletim->buscarDadosDaMatriculaHistorico();

                    $matriculas->groupby = 'idmensagem';
                    $messageCollection = $matriculaObj->listarMensagensParaCertificado($matricula["idmatricula"]);
                    $mensagens = '';
                    foreach ($messageCollection as $message) {
                        $mensagens .= $message['mensagem'] . nl2br(PHP_EOL);
                    }

                    $formula = new Formulas_Notas;
                    $controls = false;

                    include("../assets/plugins/MPDF54/mpdf.php");

                    $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
                    $mpdf = new mPDF('c', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

                    ob_start();

                    include 'idiomas/' . $config['idioma_padrao'] . '/historico_escolar.php';
                    include 'telas/' . $config['tela_padrao'] . '/historico_escolar.php';

                    $saida = ob_get_contents();
                    ob_end_clean();

                    $arquivo_nome = "../storage/temp/historico_escolar_" . $url[3] . ".pdf";

                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
                    set_time_limit(120);
                    $mpdf->WriteHTML($saida);

                    $mpdf->Output($arquivo_nome, "F");

                    header("Content-type: " . filetype($arquivo_nome));
                    header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
                    header('Content-Length: ' . filesize($arquivo_nome));
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    readfile($arquivo_nome);

                    exit;
                // no break
                case "download":
                    $arquivo = $matriculaObj->retornarMensagensArquivo($url[5]);
                    include("telas/" . $config["tela_padrao"] . "/mensagem.download.php");
                    break;
                default:
                    header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
                    exit;
                // no break
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            exit;
        }
    }
} else {
    $diplomas = new Folhas_Registros_Diplomas();

    if (!empty($_GET["conta"])) {
        $matriculaObj->Set("conta", intval($_GET["conta"]));
    }
    $matriculaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) $_GET["ordem"] = "desc";
    $matriculaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $matriculaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
    $matriculaObj->Set("ordem_campo", $_GET["cmp"]);
    $matriculaObj->Set("incluirOfertas", true);
    $matriculaObj->Set("incluirWorkflow", true);
    $matriculaObj->Set("incluirVendedores", true);
    $matriculaObj->Set("incluirPessoas", true);
    $matriculaObj->Set("campos", "m.*, cw.nome as situacao, cw.cor_bg as situacao_cor_bg,
    cw.cor_nome as situacao_cor_nome, p.nome as aluno, p.documento, o.nome as oferta, ot.nome as turma,
    v.nome as vendedor, m.faturada");
    $matriculaObj->Set("groupby", "m.idmatricula");
    $dadosArray = $matriculaObj->ListarTodas();
    foreach ($dadosArray as $dadoArray) {
        $folha = $diplomas->coletarIdFolhaPorMatricula((int)$dadoArray['idmatricula']);
        $index = array_search($dadoArray['idmatricula'], array_column($dadosArray, 'idmatricula'));
        $dadosArray[$index]['documentos'] = $matriculaObj->retornarDocumentosPorMatricula((int)$dadoArray['idmatricula']);
        if ($folha) {
            $dadosArray[$index]['idfolha'] = $folha['idfolha'];
        }
    }
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}
