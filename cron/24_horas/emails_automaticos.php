<?php
$coreObj = new Core();
$pessoasObj = new Pessoas();
$matriculasObj = new Matriculas();

$coreObj->sql = '
	select
		ea.*,
		(
			select
				count(1)
			from
				emails_automaticos_cursos eac
			where
				eac.idemail = ea.idemail and
				eac.ativo = "S"
		) as cursos_associados,
		(
			select
				count(1)
			from
				emails_automaticos_ofertas eao
			where
				eao.idemail = ea.idemail and
				eao.ativo = "S"
		) as ofertas_associadas,
		(
			select
				count(1)
			from
				emails_automaticos_sindicatos eai
			where
				eai.idemail = ea.idemail and
				eai.ativo = "S"
		) as sindicatos_associadas
	from
		emails_automaticos ea
	where
		ea.ativo = "S" and
		ea.ativo_painel = "S"
	group by
		ea.idemail	';
$coreObj->limite = -1;
$coreObj->ordem_campo = false;
$coreObj->ordem = false;
$emails = $coreObj->retornarLinhas();

$sql = '
	select
		idsituacao
	from
		matriculas_workflow
	where
		ativa = "S" and
		ativo = "S" ';
$resultado = mysql_query($sql);
$situacao_matriculado = mysql_fetch_assoc($resultado);

$sql = '
	select
		idsituacao
	from
		matriculas_workflow
	where
		fim = "S" and
		ativo = "S" ';
$resultado = mysql_query($sql);
$situacao_concluido = mysql_fetch_assoc($resultado);

$sql = '
	select
		idsituacao
	from
		contas_workflow
	where
		ativo = "S" and
		(
			pago = "S" or
			cancelada = "S" or
            renegociada = "S" or
			pagseguro = "S"
		) ';
$resultado = mysql_query($sql);
while ($situacao = mysql_fetch_assoc($resultado)) {
    $situacoes_nao_inadimplentes[] = $situacao['idsituacao'];
}

$data_atual = new DateTime();

foreach ($emails as $email) {
    unset($cursos_associados);
    if ($email['cursos_associados']) {
        $sql = '
			select
				idcurso
			from
				emails_automaticos_cursos
			where
				idemail = ' . $email['idemail'] . ' and
				ativo = "S" ';
        $resultado_cursos = mysql_query($sql);
        while ($curso = mysql_fetch_assoc($resultado_cursos)) {
            $cursos_associados[] = $curso['idcurso'];
        }
    }

    unset($ofertas_associadas);
    if ($email['ofertas_associadas']) {
        $sql = '
			select
				idoferta
			from
				emails_automaticos_ofertas
			where
				idemail = ' . $email['idemail'] . ' and
				ativo = "S" ';
        $resultado_ofertas = mysql_query($sql);
        while ($oferta = mysql_fetch_assoc($resultado_ofertas)) {
            $ofertas_associadas[] = $oferta['idoferta'];
        }
    }

    unset($sindicatos_associadas);
    if ($email['sindicatos_associadas']) {
        $sql = '
			select
				idsindicato
			from
				emails_automaticos_sindicatos
			where
				idemail = ' . $email['idemail'] . ' and
				ativo = "S" ';
        $resultado_sindicatos = mysql_query($sql);
        while ($sindicato = mysql_fetch_assoc($resultado_sindicatos)) {
            $sindicatos_associadas[] = $sindicato['idsindicato'];
        }
    }
    switch ($email['tipo']) {
        case 'anive':
            require 'emails_automaticos/aniversariantes.php';
            break;
        case 'inadi':
            require 'emails_automaticos/inadimplentes.php';
            break;
        case 'inati':
            require 'emails_automaticos/inatividade.php';
            break;
        case 'bemvi':
            require 'emails_automaticos/bem_vindo.php';
            break;
        case 'achat':
            require 'emails_automaticos/aviso_chat.php';
            break;
        case 'propl':
            require 'emails_automaticos/prova_presencial.php';
            break;
        case 'provl':
            require 'emails_automaticos/prova_virtual.php';
            break;
        case 'dtinc':
            require 'emails_automaticos/data_inicio_curso.php';
            break;
        case 'ccurs':
            require 'emails_automaticos/conclusao_curso.php';
            break;
        case 'diame':
            require 'emails_automaticos/dia_mes.php';
            break;
        case 'notas':
            require 'emails_automaticos/notas.php';
            break;
        case 'aforu':
            require 'emails_automaticos/abertura_forum.php';
            break;
        case 'senha':
            require 'emails_automaticos/senha.php';
            break;
        case 'ecurs':
            require 'emails_automaticos/expiracao_curso.php';
            break;
        case 'chdev':
            require 'emails_automaticos/cheques_devolvidos.php';
            break;
        case 'vparc':
            require 'emails_automaticos/vencimento_parcela.php';
            break;
        case 'bvenc':
            require 'emails_automaticos/boleto_vencido.php';
            break;
        case 'docup':
            require 'emails_automaticos/documentos_pendentes.php';
            break;
        case 'dobi':
            require 'emails_automaticos/documento_biometria.php';
            break;
        case 'lprof':
            require 'emails_automaticos/lembrete_prova_final.php';
            continue 2;
    }

    if ($email['salvar_log'] == 'N') {
        $coreObj->naoSalvarLogEmail = true;
    } else {
        $coreObj->naoSalvarLogEmail = false;
    }

    while ($pessoa = mysql_fetch_assoc($resultado)) {
        enviarEmailAutomaticoPessoa($email, $pessoa, $pessoasObj, $coreObj);
        if($email['tipo'] === 'dobi')
        {
            $coreObj->executaSql("UPDATE matriculas
                                      SET email_documento_biometria = 'S'
                                      WHERE idmatricula = {$pessoa['idmatricula']}");
        }
    }
}

function retornarEmailCurso($idcurso, $coreObj)
{
    $sql = 'SELECT email FROM cursos WHERE idcurso = ' . $idcurso;
    $curso = $coreObj->retornarLinha($sql);
    return $curso['email'];
}

function enviarEmailAutomaticoPessoa($email, $pessoa, $pessoasObj, $coreObj)
{ #print_r2($pessoa);return 1;

    if ($pessoa['idcurso']) {
        $emailCurso = retornarEmailCurso($pessoa['idcurso'], $coreObj);
    }

    if ($emailCurso) {
        $emailDe = $emailCurso;
    } else {
        $emailDe = $GLOBALS['config']['emailSistema'];
    }

    $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);

    $nomePara = utf8_decode($pessoa['nome']);
    $emailPara = $pessoa['email'];
    $assunto = $email['nome'];
    $message = $email['texto'];
    $message = html_entity_decode(htmlentities($message));
    $sms = $email['corpo_sms'];
    $sms = html_entity_decode($sms);

    $indice = array();

    $variavel = explode('[[ALUNO][', $message);
    if ($variavel) {
        foreach ($variavel as $ind => $val) {
            $id = explode(']]', $val);
            $indice[] = $id[0];
        }
    }
    $variavelSMS = explode('[[ALUNO][', $sms);
    if ($variavelSMS) {
        foreach ($variavelSMS as $ind => $val) {
            $id = explode(']]', $val);
            $indice[] = $id[0];
        }
    }
    if (count($indice) > 0) {
        unset($indice[array_search('', $indice)]);
        foreach ($indice as $ind => $cli) {
            $cli = strtolower($cli);
            if ($cli == 'data_nasc') {
                $message = str_ireplace('[[ALUNO][DATA_NASC]]', formataData($pessoa[$cli], 'br', 0), $message);
                $sms = str_ireplace('[[ALUNO][DATA_NASC]]', formataData($pessoa[$cli], 'br', 0), $sms);
            } elseif ($cli == 'idpais') {
                $message = str_ireplace('[[ALUNO][NACIONALIDADE]]', utf8_decode($pessoasObj->retornarNomePais($pessoa['idpais'])), $message);
                $sms = str_ireplace('[[ALUNO][NACIONALIDADE]]', utf8_decode($pessoasObj->retornarNomePais($pessoa['idpais'])), $sms);
            } elseif ($cli == 'documento') {
                if ($pessoa['documento_tipo'] == 'cpf') {
                    $message = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cpf'), $message);
                    $sms = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cpf'), $sms);
                } else {
                    $message = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cnpj'), $message);
                    $sms = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cnpj'), $sms);
                }
            } elseif ($cli == 'banco_cpf_titular') {
                $message = str_ireplace('[[ALUNO][BANCO_CPF_TITULAR]]', formatar($pessoa[$cli], 'cpf'), $message);
                $sms = str_ireplace('[[ALUNO][BANCO_CPF_TITULAR]]', formatar($pessoa[$cli], 'cpf'), $sms);
            } elseif ($cli == 'rg_data_emissao') {
                $message = str_ireplace('[[ALUNO][RG_DATA_EMISSAO]]', formataData($pessoa[$cli], 'br', 0), $message);
                $sms = str_ireplace('[[ALUNO][RG_DATA_EMISSAO]]', formataData($pessoa[$cli], 'br', 0), $sms);
            } elseif ($cli == 'cep') {
                $message = str_ireplace('[[ALUNO][CEP]]', formatar($pessoa[$cli], 'cep'), $message);
                $sms = str_ireplace('[[ALUNO][CEP]]', formatar($pessoa[$cli], 'cep'), $sms);
            } elseif ($cli == 'renda_familiar') {
                $message = str_ireplace('[[ALUNO][RENDA_FAMILIAR]]', number_format($pessoa[$cli], 2, ',', '.'), $message);
                $sms = str_ireplace('[[ALUNO][RENDA_FAMILIAR]]', number_format($pessoa[$cli], 2, ',', '.'), $sms);
            } elseif ($cli == 'estado_civil') {
                $message = str_ireplace('[[ALUNO][ESTADO_CIVIL]]', utf8_decode($GLOBALS['estadocivil'][$config['idioma_padrao']][$pessoa['estado_civil']]), $message);
                $sms = str_ireplace('[[ALUNO][ESTADO_CIVIL]]', utf8_decode($GLOBALS['estadocivil'][$config['idioma_padrao']][$pessoa['estado_civil']]), $sms);
            } elseif ($cli == 'idlogradouro') {
                $message = str_ireplace('[[ALUNO][LOGRADOURO]]', utf8_decode($pessoasObj->retornarNomeLogradouro($pessoa['idlogradouro'])), $message);
                $sms = str_ireplace('[[ALUNO][LOGRADOURO]]', utf8_decode($pessoasObj->retornarNomeLogradouro($pessoa['idlogradouro'])), $sms);
            } elseif ($cli == 'cidade') {
                $message = str_ireplace('[[ALUNO][CIDADE]]', utf8_decode($pessoasObj->retornarNomeCidade($pessoa['idcidade'])), $message);
                $sms = str_ireplace('[[ALUNO][CIDADE]]', utf8_decode($pessoasObj->retornarNomeCidade($pessoa['idcidade'])), $sms);
            } elseif ($cli == 'estado') {
                $message = str_ireplace('[[ALUNO][ESTADO]]', utf8_decode($pessoasObj->retornarNomeEstado($pessoa['idestado'])), $message);
                $sms = str_ireplace('[[ALUNO][ESTADO]]', utf8_decode($pessoasObj->retornarNomeEstado($pessoa['idestado'])), $sms);
            } else {
                $message = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', utf8_decode($pessoa[$cli]), $message);
                $sms = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', utf8_decode($pessoa[$cli]), $sms);
            }
        }
    }

    $email['corpo_sms'] = $sms;

    if ($message) {
        if ($coreObj->enviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout_branco')) {
            $sql_update = '
				update
					emails_automaticos_log
				set
					ativo = "N"
				where
					tipo = "' . $email['tipo'] . '" and
                                        idemail = "' . $email['idemail'] . '" and
					idpessoa = "' . $pessoa['idpessoa'] . '" ';
            if ($pessoa['idcurso']) {
                $sql_update .= ' and idcurso = "' . $pessoa['idcurso'] . '" ';
            }
            if ($pessoa['idoferta']) {
                $sql_update .= ' and idoferta = "' . $pessoa['idoferta'] . '" ';
            }
            if ($pessoa['idmatricula']) {
                $sql_update .= ' and idmatricula = "' . $pessoa['idmatricula'] . '" ';
            }

            mysql_query($sql_update);

            $sql_log = '
				insert into
					emails_automaticos_log
				set
					data_cad = NOW(),
					tipo = "' . $email['tipo'] . '",
                                        idemail = "' . $email['idemail'] . '",
					idpessoa = "' . $pessoa['idpessoa'] . '"';
            if ($pessoa['idcurso']) {
                $sql_log .= ', idcurso = "' . $pessoa['idcurso'] . '" ';
            }
            if ($pessoa['idoferta']) {
                $sql_log .= ', idoferta = "' . $pessoa['idoferta'] . '" ';
            }
            if ($pessoa['idmatricula']) {
                $sql_log .= ', idmatricula = "' . $pessoa['idmatricula'] . '" ';
            }

            mysql_query($sql_log);
        }
    }

    if ($email['corpo_sms'] && $pessoa['celular'] && $GLOBALS['config']['integrado_com_sms']) {
        $sql_update = '
			update
				sms_automaticos_log
			set
				ativo = "N"
			where
                                tipo = "' . $email['tipo'] . '" and
                                idemail = "' . $email['idemail'] . '" and
				idpessoa = "' . $pessoa['idpessoa'] . '" ';
        if ($pessoa['idcurso']) {
            $sql_update .= ' and idcurso = "' . $pessoa['idcurso'] . '" ';
        }
        if ($pessoa['idoferta']) {
            $sql_update .= ' and idoferta = "' . $pessoa['idoferta'] . '" ';
        }
        if ($pessoa['idmatricula']) {
            $sql_update .= ' and idmatricula = "' . $pessoa['idmatricula'] . '" ';
        }

        mysql_query($sql_update);

        $sql_log = '
			insert into
				sms_automaticos_log
			set
				data_cad = NOW(),
				tipo = "' . $email['tipo'] . '",
                                idemail = "' . $email['idemail'] . '",
				idpessoa = "' . $pessoa['idpessoa'] . '"';
        if ($pessoa['idcurso']) {
            $sql_log .= ', idcurso = "' . $pessoa['idcurso'] . '" ';
        }
        if ($pessoa['idoferta']) {
            $sql_log .= ', idoferta = "' . $pessoa['idoferta'] . '" ';
        }
        if ($pessoa['idmatricula']) {
            $sql_log .= ', idmatricula = "' . $pessoa['idmatricula'] . '" ';
        }

        mysql_query($sql_log);
        $idchave = mysql_insert_id();
        enviarSmsAutomaticoApi($email, $pessoa, $idchave);
    }
}


function enviarSmsAutomaticoApi($email, $pessoa, $idchave)
{ #print_r2($pessoa);return 1;

        include('../../classes/sms.class.php');
    $smsobj = new Sms();
    $nomePara = ($pessoa['nome']);

    $smsobj->Set('idchave', $idchave);
    $smsobj->Set('origem', 'EA');

    $smsobj->Set('url_webservicesms', $GLOBALS['config']['linkapiSMS']);
    $dados_gateway = array(
                              'loginSMS' => $GLOBALS['config']['loginSMS'],
                              'tokenSMS' => $GLOBALS['config']['tokenSMS'],
                              'celular' => $pessoa['celular'],
                              'nome' => $nomePara,
                              'mensagem' => $email['corpo_sms']
                              );
    $smsobj->Set('dado_seguro', $dados_gateway);
    $smsobj->ExecutaIntegraSMS();
}
