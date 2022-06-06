<?php
class MensagemInstantanea extends Core
{

    var $idava = NULL;
    var $modulo = NULL;
    var $idpessoa = NULL;
    var $idmatricula = NULL;
    var $idprofessor = NULL;
    var $idmensagem_instantanea = NULL;
    var $ultimaIdMensagem = NULL;


    function buscarParticipantes()
    {
        $retorno = array();

        if ($this->idpessoa) {
            $campo_aluno_diferente = " AND p.idpessoa <> '".$this->idpessoa."'";
        } else if ($this->idprofessor) {
            $campo_professor_diferente = " AND p.idprofessor <> '".$this->idprofessor."'";
        }

        $sql = "SELECT
                    DISTINCT(p.idpessoa),
                    concat('ALUNO|',p.idpessoa) as 'key',
                    concat(p.nome,' (ALUNO)') as value
                FROM
                    pessoas p
                    INNER JOIN matriculas m ON (m.idpessoa = p.idpessoa AND m.ativo = 'S')
                    INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = m.idoferta AND ocp.idcurso = m.idcurso AND ocp.idescola = m.idescola AND ocp.ativo = 'S')
                    INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = ocp.idcurriculo AND cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S')
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = m.idoferta AND oca.idcurriculo = ocp.idcurriculo AND oca.ativo = 'S')
                    INNER JOIN matriculas_workflow_acoes mwa ON (mwa.idsituacao = m.idsituacao AND mwa.ativo = 'S' AND mwa.idopcao = '27')
                  WHERE
                    oca.idava = '".$this->idava."' AND
                    (
                        (
                            (date_format(DATE_ADD(m.data_cad, INTERVAL ocp.dias_para_ava DAY),'%Y-%m-%d') >= NOW() OR ocp.dias_para_ava IS NULL) AND
                            (ocp.data_inicio_ava <= NOW() OR ocp.data_inicio_ava IS NULL) AND
                            (ocp.data_limite_ava >= NOW() OR ocp.data_limite_ava IS NULL) AND
                            m.data_prolongada IS NULL
                        )
                        OR
                        (m.data_prolongada >= now() OR m.data_prolongada IS NOT NULL)
                    ) AND
                      p.nome LIKE '%".$_GET["tag"]."%' AND
                    p.ativo = 'S'
                    ".$campo_aluno_diferente."
                LIMIT 10";//ORDER BY value ASC
        $query = $this->executaSql($sql);

        while ($linha = mysql_fetch_assoc($query)) {
            $retorno[] = $linha;
        }

        $sql = "SELECT
                    DISTINCT(p.idprofessor),
                    concat('PROFESSOR|',p.idprofessor) as 'key',
                    concat(p.nome,' (PROFESSOR)') as value
                FROM
                    professores p
                    INNER JOIN professores_avas pa on (p.idprofessor = pa.idprofessor AND pa.ativo = 'S')
                WHERE
                    pa.idava = '".$this->idava."' AND
                    p.nome LIKE '%".$_GET["tag"]."%' AND
                    p.ativo = 'S'
                    ".$campo_professor_diferente."
                LIMIT 10";//ORDER BY value ASC
        $query = $this->executaSql($sql);

        while ($linha = mysql_fetch_assoc($query)) {
            $retorno[] = $linha;
        }

        //Ordena o array por ordem alfabética
        $auxRetorno = array_sort($retorno, 'value', SORT_ASC);

        $retorno = array();
        foreach ($auxRetorno as $key => $value) {
            $retorno[] = $value;
        }

        return json_encode($retorno);
    }

    //Cria a mensagem com os integrantes selecionados e a mensagem informada
    public function iniciarConversa()
    {
        if (verificaPermissaoAcesso(true)) {
            $this->retorno = array();

            include_once("../includes/validation.php");
            $regras = array();
            $regras[] = "required,participantes,participantes_vazio";
            $regras[] = "required,mensagem,mensagem_vazio";
            $erros = validateFields($this->post, $regras);

            if($_FILES["arquivo"]["tmp_name"]) {
                $validar = $this->ValidarArquivo($_FILES["arquivo"]);
                $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));

                if($validar || ($extensao != ".jpg" && $extensao != ".jpeg" && $extensao != ".gif" && $extensao != ".png" && $extensao != ".bmp" && $extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
                    $retorno = array('erro' => true);
                    if($validar) {
                        $retorno["mensagem"] = $validar;
                    } else {
                        $retorno["mensagem"] = "mensagens_instantanea_arquivo_extensao_erro";
                    }
                    return $retorno;
                } else {
                    $pasta = $_SERVER["DOCUMENT_ROOT"]."/storage/avas_mensagens_instantaneas";
                    $nomeServidor = date("YmdHis")."_".uniqid().$extensao;
                    $envio = move_uploaded_file($_FILES["arquivo"]["tmp_name"],$pasta."/".$nomeServidor);
                    chmod($pasta."/".$nomeServidor, 0777);
                    if($envio) {
                        $arquivo = true;
                        $arquivo_nome = $_FILES["arquivo"]["name"];
                        $arquivo_tipo = $_FILES["arquivo"]["type"];
                        $arquivo_tamanho = $_FILES["arquivo"]["size"];
                    } else {
                        $retorno = array('erro' => true, 'mensagem' => 'mensagens_instantanea_arquivo_upload_erro');
                        return $retorno;
                    }
                }
            }
            if ($erros) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"] = $erros;
                return $this->retorno;
            }
            $this->executaSql("BEGIN");

            $arrayParticipantes = array();
            foreach ($this->post["participantes"] as $ind => $var) {
                $participante = explode("|", $var);

                if ($participante[0] == "ALUNO" && intval($participante[1])) {
                    $arrayParticipantes["alunos"][] = intval($participante[1]);
                } elseif ($participante[0] == "PROFESSOR" && intval($participante[1])) {
                    $arrayParticipantes["professores"][] = intval($participante[1]);
                }
            }

            if (count($arrayParticipantes["alunos"]) > 0 || count($arrayParticipantes["professores"]) > 0) {
                $this->sql = "INSERT INTO
                                    avas_mensagem_instantanea
                                  SET
                                    data_cad = NOW(),
                                    ativo = 'S',
                                    ultima_interacao = NOW(),
                                    idava = '".$this->idava."'";
                $salvar = $this->executaSql($this->sql);
                $idmensagem_instantanea = mysql_insert_id();

                if ($salvar) {
                    if ($this->idpessoa) {
                        $campo_usuario = "idpessoa = '".$this->idpessoa."'";
                    } else if ($this->idprofessor) {
                        $campo_usuario = "idprofessor = '".$this->idprofessor."'";
                    }

                    //Salva quem criou a mensagem como integrante, o aluno ou professor
                    $this->sql = "INSERT INTO
                                        avas_mensagem_instantanea_integrantes
                                      SET
                                        data_cad = NOW(),
                                        ativo = 'S',
                                        criador = 'S',
                                        idmensagem_instantanea = '".$idmensagem_instantanea."',
                                        ".$campo_usuario;
                    $salvar = $this->executaSql($this->sql);
                    $idmensagem_instantanea_integrante_criador = mysql_insert_id();

                    //Salva a mensagem enviada
                    $this->sql = "INSERT INTO
                                        avas_mensagem_instantanea_conversas
                                      SET
                                        data_cad = NOW(),
                                        idmensagem_instantanea = '".$idmensagem_instantanea."',
                                        idmensagem_instantanea_integrante = '".$idmensagem_instantanea_integrante_criador."',
                                        mensagem = '".mysql_real_escape_string($this->post["mensagem"])."'";
                    if ($arquivo) {
                        $this->sql .= ", arquivo_nome = '".$arquivo_nome."',
                                        arquivo_servidor = '".$nomeServidor."',
                                        arquivo_tipo = '".$arquivo_tipo."',
                                        arquivo_tamanho = ".$arquivo_tamanho;
                    }
                    $salvar = $this->executaSql($this->sql);
                    $idmensagem_instantanea_conversa = mysql_insert_id();

                    if (count($arrayParticipantes["alunos"]) > 0) {
                        foreach ($arrayParticipantes["alunos"] as $ind => $var) {
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_integrantes
                                              SET
                                                data_cad = NOW(),
                                                ativo = 'S',
                                                idmensagem_instantanea = '".$idmensagem_instantanea."',
                                                idpessoa = '".$var."'";
                            $salvar = $this->executaSql($this->sql);
                            $idmensagem_instantanea_integrante = mysql_insert_id();

                            //Marca como não visto a mensagem pela pessoa
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_conversas_visualizar
                                              SET
                                                idmensagem_instantanea_conversa = '".$idmensagem_instantanea_conversa."',
                                                idmensagem_instantanea_integrante = '".$idmensagem_instantanea_integrante."'";
                            $salvar = $this->executaSql($this->sql);
                        }
                    }

                    if (count($arrayParticipantes["professores"]) > 0) {
                        if ($this->idpessoa) {
                            $sql_aluno = 'select nome from pessoas where idpessoa = ' . $this->idpessoa;
                            $aluno = $this->retornarLinha($sql_aluno);

                            $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);
                            $emailDe = $GLOBALS['config']['emailSistema'];

                            $assunto = utf8_decode('Dúvida do aluno');
                        }

                        foreach ($arrayParticipantes["professores"] as $ind => $var) {
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_integrantes
                                              SET
                                                data_cad = NOW(),
                                                ativo = 'S',
                                                idmensagem_instantanea = '".$idmensagem_instantanea."',
                                                idprofessor = '".$var."'";
                            $salvar = $this->executaSql($this->sql);
                            $idmensagem_instantanea_integrante = mysql_insert_id();

                            //Marca como não visto a mensagem pela pessoa
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_conversas_visualizar
                                              SET
                                                idmensagem_instantanea_conversa = '".$idmensagem_instantanea_conversa."',
                                                idmensagem_instantanea_integrante = '".$idmensagem_instantanea_integrante."'";
                            $salvar = $this->executaSql($this->sql);

                            if ($this->idpessoa) {
                                $sql_professor = 'select nome, email from professores where idprofessor = ' . $var;
                                $professor = $this->retornarLinha($sql_professor);

                                $nomePara = $professor['nome'];
                                $emailPara = $professor['email'];
                                $assunto = utf8_decode('Dúvida do aluno');

                                $message  = "Olá <strong>".$nomePara."</strong>,
                                                <br /><br />
                                                O Aluno(a) ".$aluno["nome"].", mandou a seguinte dúvida:
                                                <br /><br />";

                                $message .= nl2br($this->post['mensagem']);


                                $message .= '<br/><br/>
                                            <a href="'.$GLOBALS['config']["urlSistema"].'/professor/academico/avas/'.$this->idava.'/mensagem_instantanea/'.$idmensagem_instantanea.'">
                                                Clique aqui para abrir o tira d&uacute;vida.
                                            <a>';

                                $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
                            }

                        }
                    }
                }
            }

            if ($salvar) {
                $this->executaSql("COMMIT");
                $this->retorno["sucesso"] = true;
                $this->retorno["idmensagem_instantanea"] = $idmensagem_instantanea;
                if ($this->idpessoa && $this->idmatricula) {
                    $this->contabilizarTiraDuvida($this->idmatricula, $this->idava, $idmensagem_instantanea);
                }
            } else {
                $this->executaSql("ROLLBACK");
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
            return $this->retorno;
        }
    }//Fim iniciarConversa()

    /*Traz as mensagem que a pessoa participa com os integrantes
    Variáveis:
        $this->idpessoa: id do aluno caso seja para listar as mensagem que ele participa
        $this->idprofessor: id do professor caso seja para listar as mensagem que ele participa
        $this->idava: id do ava que será retornado as mensagem
    */
    function ListarMensagensInstantaneasPessoa() {
        $retorno = array();

        if ($this->idpessoa) {
            $campo_usuario = "amii.idpessoa = '".$this->idpessoa."'";
        } else if ($this->idprofessor) {
            $campo_usuario = "amii.idprofessor = '".$this->idprofessor."'";
        }

        $this->sql = "SELECT
                        ami.idmensagem_instantanea,
                        ami.data_cad,
                        ami.ultima_interacao,
                        (SELECT
                                count(amicv2.idmensagem_instantanea_conversas_visualizar)
                            FROM
                                avas_mensagem_instantanea_conversas amic2
                                INNER JOIN avas_mensagem_instantanea_conversas_visualizar amicv2 ON (amicv2.idmensagem_instantanea_conversa = amic2.idmensagem_instantanea_conversa)
                            WHERE
                                amic2.idmensagem_instantanea = ami.idmensagem_instantanea AND
                                amicv2.idmensagem_instantanea_integrante = amii.idmensagem_instantanea_integrante
                        ) as qnt_conversas_nao_lidas
                      FROM
                        avas_mensagem_instantanea ami
                        INNER JOIN avas_mensagem_instantanea_integrantes amii ON (amii.idmensagem_instantanea = ami.idmensagem_instantanea)
                      WHERE
                        ".$campo_usuario." AND
                        ami.idava = '".$this->idava."' AND
                        ami.ativo = 'S' AND
                        amii.ativo = 'S'";

        $this->ordem = "DESC";
        $this->ordem_campo = "ami.ultima_interacao";
        $this->limite = -1;

        $retorno = $this->retornarLinhas();

        foreach ($retorno as $ind => $var) {
            /*if ($this->idpessoa) {
                $campo_usuario = " (amii.idpessoa <> '".$this->idpessoa."' OR amii.idpessoa IS NULL)";
            } else if ($this->idprofessor) {
                $campo_usuario = " (amii.idprofessor <> '".$this->idprofessor."' OR amii.idprofessor IS NULL)";
            }

            $this->sql = "SELECT
                            if (amii.idpessoa IS NOT NULL, pes.nome, prof.nome) as nome_usuario,
                            if (amii.idpessoa IS NOT NULL, 'ALUNO', 'PROFESSOR') as tipo_usuario,
                            if (amii.idpessoa IS NOT NULL, pes.avatar_servidor, prof.avatar_servidor) as avatar_servidor,
                            if (amii.idpessoa IS NOT NULL, 'pessoas_avatar', 'professores_avatar') as pasta_servidor,
                            amii.ativo as ativo_usuario
                          FROM
                            avas_mensagem_instantanea ami
                            INNER JOIN avas_mensagem_instantanea_integrantes amii ON (amii.idmensagem_instantanea = ami.idmensagem_instantanea)
                            LEFT OUTER JOIN pessoas pes ON (pes.idpessoa = amii.idpessoa)
                            LEFT OUTER JOIN professores prof ON (prof .idprofessor = amii.idprofessor)
                          WHERE
                            ami.idmensagem_instantanea = '".$var["idmensagem_instantanea"]."' AND
                            ".$campo_usuario." AND
                            ami.ativo = 'S'";

            $this->ordem = "ASC";
            $this->ordem_campo = "nome_usuario";
            $this->limite = -1;

            $retorno[$ind]["integrantes"] = $this->retornarLinhas();*/

            $retorno[$ind]["integrantes"] = $this->retornarIntegrantes($var["idmensagem_instantanea"]);
        }

        return $retorno;
    }//FIM ListarMensagensInstantaneasPessoa()


    function retornarIntegrantes($idmensagem_instantanea, $todos = false) {
        $campo_usuario = "";
        if(!$todos) {
            if ($this->idpessoa) {
                $campo_usuario = "  AND (amii.idpessoa <> '".$this->idpessoa."' OR amii.idpessoa IS NULL)";
            } else if ($this->idprofessor) {
                $campo_usuario = "  AND (amii.idprofessor <> '".$this->idprofessor."' OR amii.idprofessor IS NULL)";
            }
        }

        $this->sql = "SELECT
                        if (amii.idpessoa IS NOT NULL, pes.nome, prof.nome) as nome_usuario,
                        if (amii.idpessoa IS NOT NULL, 'ALUNO', 'PROFESSOR') as tipo_usuario,
                        if (amii.idpessoa IS NOT NULL, pes.avatar_servidor, prof.avatar_servidor) as avatar_servidor,
                        if (amii.idpessoa IS NOT NULL, 'pessoas_avatar', 'professores_avatar') as pasta_servidor,
                        amii.ativo as ativo_usuario
                      FROM
                        avas_mensagem_instantanea_integrantes amii
                        LEFT OUTER JOIN pessoas pes ON (pes.idpessoa = amii.idpessoa)
                        LEFT OUTER JOIN professores prof ON (prof .idprofessor = amii.idprofessor)
                      WHERE
                        amii.idmensagem_instantanea = '".$idmensagem_instantanea."'
                        ".$campo_usuario;

        $this->ordem = "ASC";
        $this->ordem_campo = "nome_usuario";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    /*Retorna as conversas de uma mensagem instantânea da pessoa
    Variáveis:
        $this->idpessoa: id do aluno caso seja para ver as conversas da mensagem dele
        $this->idprofessor: id do professor caso seja para ver as conversas da mensagem dele
        $this->idava: id do ava que será retornado as conversas da mensagem
        $this->idmensagem_instantanea: id da mensagem instantânea que será retornado as conversas
        $this->ultimaIdMensagem: id da última conversa listada para o aluno, caso exista irá retornar apenas as conversas após essa
    */
    function RetornarArquivoConversa($idmensagem_instantanea_conversa)
    {
          $this->sql = 'SELECT
                    amic.*
                  FROM
                    avas_mensagem_instantanea_conversas amic
                  WHERE
                    amic.idmensagem_instantanea_conversa = '.$idmensagem_instantanea_conversa;
        return $this->retornarLinha($this->sql);
      }

    function ListarConversasMensagemInstantanea() {
        $retorno = array();

        if ($this->idpessoa) {
            $campo_usuario = "(SELECT count(idmensagem_instantanea_integrante) FROM avas_mensagem_instantanea_integrantes WHERE idmensagem_instantanea = ami.idmensagem_instantanea AND idpessoa = '".$this->idpessoa."') = 1";
        } else if ($this->idprofessor) {
            $campo_usuario = "(SELECT count(idmensagem_instantanea_integrante) FROM avas_mensagem_instantanea_integrantes WHERE idmensagem_instantanea = ami.idmensagem_instantanea AND idprofessor = '".$this->idprofessor."') = 1";
        }

        $this->sql = "SELECT
                        amic.idmensagem_instantanea_conversa,
                        amic.arquivo_nome,
                        amic.arquivo_tipo,
                        amic.arquivo_tamanho,
                        amic.arquivo_servidor,
                        amii.idmensagem_instantanea_integrante,
                        if (amii.idpessoa IS NOT NULL, pes.idpessoa, prof.idprofessor) as id_usuario,
                        if (amii.idpessoa IS NOT NULL, pes.nome, prof.nome) as nome_usuario,
                        if (amii.idpessoa IS NOT NULL, 'ALUNO', 'PROFESSOR') as tipo_usuario,
                        if (amii.idpessoa IS NOT NULL, pes.avatar_servidor, prof.avatar_servidor) as avatar_servidor,
                        if (amii.idpessoa IS NOT NULL, 'pessoas_avatar', 'professores_avatar') as pasta_servidor,
                        DATE_FORMAT(amic.data_cad,'%d/%m/%Y às %H:%i:%S') as data_cad,
                        amic.mensagem,
                        amii.ativo as ativo_usuario
                      FROM
                        avas_mensagem_instantanea_conversas amic
                        INNER JOIN avas_mensagem_instantanea_integrantes amii ON (amii.idmensagem_instantanea_integrante = amic.idmensagem_instantanea_integrante)
                        INNER JOIN avas_mensagem_instantanea ami ON (ami.idmensagem_instantanea = amii.idmensagem_instantanea AND ami.idmensagem_instantanea = amic.idmensagem_instantanea)
                        LEFT OUTER JOIN pessoas pes ON (pes.idpessoa = amii.idpessoa)
                        LEFT OUTER JOIN professores prof ON (prof.idprofessor = amii.idprofessor)
                      WHERE
                        ami.idmensagem_instantanea = '".$this->idmensagem_instantanea."' AND";

        if($this->ultimaIdMensagem) {
            $this->sql .= " amic.idmensagem_instantanea_conversa > '".$this->ultimaIdMensagem."' AND";
        }

        $this->sql .= " ami.idava = '".$this->idava."' AND
                    ".$campo_usuario." AND
                    ami.ativo = 'S' AND
                    amic.liberada = 'S'";

        $this->ordem = "ASC";
        $this->ordem_campo = "amic.idmensagem_instantanea_conversa";
        $this->limite = -1;

        $retorno = $this->retornarLinhas();

        if (verificaPermissaoAcesso(false)) {
            if (is_array($retorno)) {
                if ($this->idpessoa) {
                    $campo_usuario = "idpessoa = '".$this->idpessoa."'";
                } else if ($this->idprofessor) {
                    $campo_usuario = "idprofessor = '".$this->idprofessor."'";
                }

                //Retorna o integrante que está visualizando a mensagem, para marcar que ele já viu as conversas
                $this->sql = "SELECT
                                  idmensagem_instantanea_integrante
                                FROM
                                  avas_mensagem_instantanea_integrantes
                                WHERE
                                  idmensagem_instantanea = '".$this->idmensagem_instantanea."' AND
                                  ".$campo_usuario;
                $integrante = $this->retornarLinha($this->sql);

                foreach ($retorno as $ind => $var) {
                    $this->sql = "DELETE FROM
                                        avas_mensagem_instantanea_conversas_visualizar
                                      WHERE
                                        idmensagem_instantanea_conversa = '".$var["idmensagem_instantanea_conversa"]."' AND
                                        idmensagem_instantanea_integrante = '".$integrante["idmensagem_instantanea_integrante"]."'";
                    $salvar = $this->executaSql($this->sql);
                }
            }
        }

        return $retorno;
    }//FIM ListarConversasMensagemInstantanea

    /*Salva a conversa enviada pela pessoa
    Variáveis:
        $this->idpessoa: id do aluno caso seja para ver as conversas da mensagem dele
        $this->idprofessor: id do professor caso seja para ver as conversas da mensagem dele
        $this->idava: id do ava que será retornado as conversas da mensagem
        $this->idmensagem_instantanea: id da mensagem instantânea que será retornado as conversas
        $this->ultimaIdMensagem: id da última conversa listada para o aluno, caso exista irá retornar apenas as conversas após essa
    */

    function enviarArquivoMensagem($chunk = NULL, $chunks = NULL, $fileName = NULL, $idmensagem_instantanea_conversa = NULL)
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Settings
        $targetDir = $_SERVER["DOCUMENT_ROOT"]."/storage/avas_mensagens_instantaneas";
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // 5 minutes execution time
        @set_time_limit(5 * 60);
        if($_FILES["file"]["tmp_name"]) {
            $this->config["tamanho_upload_padrao"] = 2097152;
            $validar = $this->ValidarArquivo($_FILES["file"]);
            $extensao = strtolower(strrchr($_FILES["file"]["name"], "."));

            if($validar || ($extensao != ".jpg" && $extensao != ".jpeg" && $extensao != ".gif" && $extensao != ".png" && $extensao != ".bmp" && $extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
                $retorno = array('erro' => true);
                if($validar) {
                    $retorno["mensagem"] = $validar;
                } else {
                    $retorno["mensagem"] = "mensagens_instantanea_arquivo_extensao_erro";
                }
                return $retorno;
            } else {
                $pasta = $_SERVER["DOCUMENT_ROOT"]."/storage/avas_mensagens_instantaneas";
                $nomeServidor = date("YmdHis")."_".uniqid().$extensao;
                $envio = move_uploaded_file($_FILES["file"]["tmp_name"],$pasta."/".$nomeServidor);
                chmod($pasta."/".$nomeServidor, 0777);
                if($envio) {
                    $arquivo = true;
                    $arquivo_nome = $_FILES["file"]["name"];
                    $arquivo_tipo = $_FILES["file"]["type"];
                    $arquivo_tamanho = $_FILES["file"]["size"];
                } else {
                    $retorno = array('erro' => true, 'mensagem' => 'mensagens_instantanea_arquivo_upload_erro');
                    return $retorno;
                }
            }
        }

        /*$infos = explode(".", $_FILES['file']['name']);
        $tipoArquivo = $infos[count($infos) - 1];

        $extensao = strtolower(strrchr($fileName, "."));
        $fileName = date("YmdHis")."_".uniqid().$extensao;*/
        if ($arquivo) {
            $this->sql = "UPDATE
                                avas_mensagem_instantanea_conversas
                            SET
                                arquivo_nome = '".$arquivo_nome."',
                                arquivo_servidor = '".$nomeServidor."',
                                arquivo_tipo = '".$arquivo_tipo."',
                                arquivo_tamanho = ".$arquivo_tamanho.",
                                liberada = 'S'
                            WHERE
                                idmensagem_instantanea_conversa = '".$idmensagem_instantanea_conversa."'";
            $salvar = $this->executaSql($this->sql);
        }


        /*if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);

            $count = 1;
            while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

        // Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                    @unlink($tmpfilePath);
                }
            }

            closedir($dir);
        } else
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');


        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                fclose($in);
                fclose($out);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
        }

        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');*/
        return $salvar;
    }

    function salvarNovaConversa()
    {
        if (verificaPermissaoAcesso(false)) {
            $this->retorno = array();

            include_once("../includes/validation.php");
            $regras = array();
            $regras[] = "required,mensagem,mensagem_vazio";
            $erros = validateFields($this->post, $regras);
            if ($erros) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"] = $erros;

            } else {
                $this->executaSql("BEGIN");

                if ($this->idpessoa) {
                    $campo_usuario = "idpessoa = '".$this->idpessoa."'";
                    $campo_usuario_diferente = " (idpessoa <> '".$this->idpessoa."' OR idpessoa IS NULL)";

                    $sql_aluno = 'select nome from pessoas where idpessoa = ' . $this->idpessoa;
                    $aluno = $this->retornarLinha($sql_aluno);

                    $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);
                    $emailDe = $GLOBALS['config']['emailSistema'];

                    $assunto = utf8_decode('Dúvida do aluno');

                } else if ($this->idprofessor) {
                    $campo_usuario = "idprofessor = '".$this->idprofessor."'";
                    $campo_usuario_diferente = " (idprofessor <> '".$this->idprofessor."' OR idprofessor IS NULL)";
                }

                //Caso tenha arquivo não ficará liberada a mensagem, pois só será liberada após o upload do arquivo
                $liberada = 'S';
                if ($this->post['temArquivo'] == 'S') {
                    $liberada = 'N';
                }

                //Retorna o integrante que enviou a mensagem
                $this->sql = "SELECT
                                  idmensagem_instantanea_integrante
                                FROM
                                  avas_mensagem_instantanea_integrantes
                                WHERE
                                  idmensagem_instantanea = '".$this->post["idmensagem_instantanea"]."' AND
                                  ".$campo_usuario;
                $integrante = $this->retornarLinha($this->sql);

                //Salva a mensagem enviada
                $this->sql = "INSERT INTO
                                    avas_mensagem_instantanea_conversas
                                  SET
                                    data_cad = NOW(),
                                    liberada = '".$liberada."',
                                    idmensagem_instantanea = '".$this->post["idmensagem_instantanea"]."',
                                    idmensagem_instantanea_integrante = '".$integrante["idmensagem_instantanea_integrante"]."',
                                    mensagem = '".mysql_real_escape_string($this->post["mensagem"])."'";
                if ($arquivo) {
                    $this->sql .= ", arquivo_nome = '".$arquivo_nome."', arquivo_servidor = '".$nomeServidor."', arquivo_tipo = '".$arquivo_tipo."', arquivo_tamanho = ".$arquivo_tamanho;
                }
                $salvar = $this->executaSql($this->sql);
                $idmensagem_instantanea_conversa = mysql_insert_id();



                /*$this->sql = "INSERT INTO mensagens_alerta(idmatricula, idmensagem_instantanea, tipo_alerta )
                SELECT DISTINCT( m.idmatricula),".$this->post["idmensagem_instantanea"].",'tiraduvidas'
                FROM
                    pessoas p
                    INNER JOIN matriculas m ON (m.idpessoa = p.idpessoa AND m.ativo = 'S')
                    INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = m.idoferta AND ocp.idcurso = m.idcurso AND ocp.idescola = m.idescola AND ocp.ativo = 'S')
                    INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = ocp.idcurriculo AND cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S')
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = m.idoferta AND oca.idcurriculo = ocp.idcurriculo AND oca.ativo = 'S')
                    INNER JOIN avas_mensagem_instantanea ami ON (ami.idava = oca.idava)
                    INNER JOIN avas_mensagem_instantanea_integrantes amii ON (amii.idmensagem_instantanea = ami.idmensagem_instantanea)
                    WHERE exists(select * from avas_mensagem_instantanea_integrantes amii where p.idpessoa = amii.idpessoa) AND amii.idmensagem_instantanea = ".$this->post["idmensagem_instantanea"]." AND p.idpessoa <> ".$this->idpessoa = !empty($this->idpessoa) ? $this->idpessoa : "0";

                    $this->executaSql($this->sql);*/

                //Busca todos os integrantes exceto o que enviou a mensagem para setar como não lida
                $this->sql = "SELECT
                                idmensagem_instantanea_integrante,
                                idpessoa,
                                idprofessor
                              FROM
                                avas_mensagem_instantanea_integrantes
                              WHERE
                                idmensagem_instantanea = '".$this->post["idmensagem_instantanea"]."' AND
                                ativo = 'S' AND
                                ".$campo_usuario_diferente;
                $this->ordem = "ASC";
                $this->ordem_campo = "idmensagem_instantanea_integrante";
                $this->limite = -1;
                $todosIntegrantes =  $this->retornarLinhas();



                if (count($todosIntegrantes) > 0) {
                    foreach ($todosIntegrantes as $ind => $var) {
                        //Marca como não visto a mensagem pela pessoa
                        $this->sql = "INSERT INTO
                                        avas_mensagem_instantanea_conversas_visualizar
                                      SET
                                        idmensagem_instantanea_conversa = '".$idmensagem_instantanea_conversa."',
                                        idmensagem_instantanea_integrante = '".$var["idmensagem_instantanea_integrante"]."'";
                        $salvar = $this->executaSql($this->sql);

                        if ($var['idprofessor']) {
                            $sql_professor = 'select nome, email from professores where idprofessor = ' . $var['idprofessor'];
                            $professor = $this->retornarLinha($sql_professor);

                            $nomePara = $professor['nome'];
                            $emailPara = $professor['email'];

                            $message  = "Ol&aacute; <strong>".htmlentities($nomePara)."</strong>,
                                            <br /><br />
                                            O Aluno(a) ".htmlentities($aluno["nome"]).", mandou a seguinte d&uacute;vida:
                                            <br /><br />";
                            $message .= nl2br(htmlentities($this->post['mensagem']));
                            // $message = utf8_decode($message);
                            //$message = html_entity_decode(htmlentities($message));

                            $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, htmlentities($nomePara), $emailPara);
                        }
                    }
                }

                //Ativar o sinalizador do professor
                $this->sql = 'UPDATE
                                    avas_mensagem_instantanea
                                SET
                                    sinalizador_professor = "S"
                                WHERE
                                    idmensagem_instantanea = "'.$this->post["idmensagem_instantanea"].'"';
                $salvar = $this->executaSql($this->sql);

                if ($salvar) {
                    $this->executaSql("COMMIT");
                    $this->retorno["sucesso"] = true;
                    $_SESSION["idmensagem_instantanea_conversa"] = $salvar['idmensagem_instantanea_conversa'];
                    $this->retorno["idmensagem_instantanea_conversa"] = $idmensagem_instantanea_conversa;
                } else {
                    $this->executaSql("ROLLBACK");
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }
            return $this->retorno;
        } else {
            $this->retorno['erro_json'] = "sem_permissao";
            return $this->retorno;
        }
    }//FIM salvarNovaConversa

    /*Adiciona Pessoas à mensagem instantânea
    Variáveis:
        $this->idmensagem_instantanea: id da mensagem instantânea que será retornado as conversas
        $this->post: Post com os participantes a serem inseridos
    */
    public function adicionarUsuarioMensagem()
    {
        if (verificaPermissaoAcesso(true)) {
            $this->retorno = array();

            include_once("../includes/validation.php");
            $regras = array();
            $regras[] = "required,participantes,participantes_vazio";
            $erros = validateFields($this->post, $regras);

            if ($erros) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"] = $erros;

            } else {
                $this->executaSql("BEGIN");

                $arrayParticipantes = array();
                foreach ($this->post["participantes"] as $ind => $var) {
                    $participante = explode("|", $var);

                    if ($participante[0] == "ALUNO") {
                        $campo_usuario = "idpessoa = '" . intval($participante[1]) . "'";
                    } else if ($participante[0] == "PROFESSOR") {
                        $campo_usuario = "idprofessor = '" . intval($participante[1]) . "'";
                    }

                    //Retorna se o usuário já está participando da mensagem instantânea
                    $this->sql = "SELECT
                                      idmensagem_instantanea_integrante,
                                      ativo
                                    FROM
                                      avas_mensagem_instantanea_integrantes
                                    WHERE
                                      idmensagem_instantanea = '" . $this->idmensagem_instantanea . "' AND
                                      ".$campo_usuario;
                    $integranteJaExiste = $this->retornarLinha($this->sql);

                    if (!$integranteJaExiste) {
                        if ($participante[0] == "ALUNO" && intval($participante[1])) {
                            $arrayParticipantes["alunos"][] = intval($participante[1]);
                        } elseif ($participante[0] == "PROFESSOR" && intval($participante[1])) {
                            $arrayParticipantes["professores"][] = intval($participante[1]);
                        }
                    }else if($integranteJaExiste["ativo"] = 'N'){
                        //Caso o usuário tenha cadastro inativo salva ele para poder reativar
                        $arrayReativarParticipantes[] = $integranteJaExiste["idmensagem_instantanea_integrante"];
                    }
                }

                if (count($arrayParticipantes["alunos"]) > 0 || count($arrayParticipantes["professores"]) > 0 || count($arrayReativarParticipantes) > 0) {
                    if (count($arrayParticipantes["alunos"]) > 0) {
                        foreach ($arrayParticipantes["alunos"] as $ind => $var) {
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_integrantes
                                              SET
                                                data_cad = NOW(),
                                                ativo = 'S',
                                                idmensagem_instantanea = '" . $this->idmensagem_instantanea . "',
                                                idpessoa = '" . $var . "'";
                            $salvar = $this->executaSql($this->sql);

                        }
                    }

                    if (count($arrayParticipantes["professores"]) > 0) {
                        foreach ($arrayParticipantes["professores"] as $ind => $var) {
                            $this->sql = "INSERT INTO
                                                avas_mensagem_instantanea_integrantes
                                              SET
                                                data_cad = NOW(),
                                                ativo = 'S',
                                                idmensagem_instantanea = '" . $this->idmensagem_instantanea . "',
                                                idprofessor = '".$var."'";
                            $salvar = $this->executaSql($this->sql);

                        }
                    }

                    if (count($arrayReativarParticipantes) > 0) {
                        foreach ($arrayReativarParticipantes as $ind => $var) {
                            $this->sql = "UPDATE
                                                avas_mensagem_instantanea_integrantes
                                              SET
                                                data_reativado = NOW(),
                                                ativo = 'S'
                                              WHERE
                                                idmensagem_instantanea_integrante = '".$var."'";
                            $salvar = $this->executaSql($this->sql);

                        }
                    }
                }

                if ($salvar) {
                    $this->executaSql("COMMIT");
                    $this->retorno["sucesso"] = true;
                    $this->retorno["idmensagem_instantanea"] = $this->idmensagem_instantanea;
                    
                    foreach ($arrayParticipantes["professores"] as $ind => $var) {
                        $sql_professor = 'select nome, email from professores where idprofessor = ' . $var;
                        $professor = $this->retornarLinha($sql_professor);
                        
                        if ($professor) {
                            $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);
                            $emailDe = $GLOBALS['config']['emailSistema'];
                            $nomePara = $professor['nome'];
                            $emailPara = $professor['email'];
                            $assunto = utf8_decode('Adicionado ao tira-dúvidas');

                            $message  = "Olá <strong>" . $nomePara . ". Você foi adicionado a um tira-dúvidas.</strong>,
                                <br /><br />";

                            $message .= '<br/><br/>
                                        <a 
                                            href="' . $GLOBALS['config']["urlSistema"] . '/professor/academico/avas/'
                                                . $this->idava . '/mensagem_instantanea/'
                                                . $idmensagem_instantanea . '"
                                        >
                                            Clique aqui para abrir o tira d&uacute;vida.
                                        <a>';

                            $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
                        }
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }

            return $this->retorno;
        }
    }//Fim adicionarUsuarioMensagem()

    /*Remove a pessoa da conversa
    Variáveis:
        $this->idmensagem_instantanea: id da mensagem instantânea que a pessoa irá sair
        $this->idpessoa: id do aluno que sairá da conversa
        $this->idprofessor: id do professor que sairá da conversa
    */
    function sairConversa()
    {

        if (verificaPermissaoAcesso(true)) {

            $this->retorno = array();

            $this->executaSql("BEGIN");

            if ($this->idpessoa) {
                $campo_usuario = "idpessoa = '".$this->idpessoa."'";
            } else if ($this->idprofessor) {
                $campo_usuario = "idprofessor = '".$this->idprofessor."'";
            }

            $this->sql = "UPDATE
                                avas_mensagem_instantanea_integrantes
                              SET
                                ativo = 'N'
                              WHERE
                                idmensagem_instantanea = '".$this->idmensagem_instantanea."' AND
                                ".$campo_usuario;
            $salvar = $this->executaSql($this->sql);

            //Verifica se tem algum integrante na conversa ainda, pois caso não tenha irá desativar a mensagem instantânea
            $this->sql = "SELECT
                              count(idmensagem_instantanea_integrante) as total
                            FROM
                              avas_mensagem_instantanea_integrantes
                            WHERE
                              idmensagem_instantanea = '".$this->idmensagem_instantanea."' AND
                              ativo = 'S'";
            $integranteJaExiste = $this->retornarLinha($this->sql);

            if ($integranteJaExiste["total"] == 0) {
                //Inativa a mensagem instantânea
                $this->sql = "UPDATE
                                    avas_mensagem_instantanea
                                  SET
                                    ativo = 'N'
                                  WHERE
                                    idmensagem_instantanea = '".$this->idmensagem_instantanea."'";
                $salvar = $this->executaSql($this->sql);
            }

            if ($salvar) {
                $this->executaSql("COMMIT");
                $this->retorno["sucesso"] = true;
                $this->retorno["idmensagem_instantanea"] = $this->idmensagem_instantanea;
            } else {
                $this->executaSql("ROLLBACK");
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

            return $this->retorno;
        }
    }//Fim sairConversa()

    /*Verifica se o professor tem acesso ao AVA que está tentando visualizar
    Variáveis:
        $this->idprofessor: id do professor para verificar acesso ao ava
        $this->idava: id do ava que será verificado se o professor tem acesso
    */
    function VerificarAcessoAvaProfessor()
    {
        $retorno = array();

        $this->sql = "SELECT
                        a.idava
                      FROM
                        avas a
                        INNER JOIN professores_avas pa ON (a.idava = pa.idava)
                      WHERE
                        pa.idprofessor = '".$this->idprofessor."' AND
                        a.idava = '".$this->idava."' AND
                        pa.ativo = 'S' AND
                        a.ativo = 'S' AND
                        a.ativo_painel = 'S'";
        return $this->retornarLinha($this->sql);
    }//FIM VerificarAcessoAvaProfessor()

    function contabilizarTiraDuvida($idmatricula, $idava, $idmensagem_instantanea) {
        if (verificaPermissaoAcesso(true)) {
            $this->executaSql("BEGIN");

            //Busca se já foi contabilizado a porcentagem de download da biblioteca
            $this->sql = 'SELECT
                                count(*) as total
                              FROM
                                matriculas_rotas_aprendizagem_objetos
                              WHERE
                                idmatricula = '.$idmatricula.' AND
                                idava = '.$idava.' AND
                                idmensagem_instantanea IS NOT NULL';
            $mensagemContabilizado = $this->retornarLinha($this->sql);

            $sql = 'SELECT COUNT(*) AS total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = '.$idmatricula.' AND idava = '.$idava.' AND idmensagem_instantanea = '.$idmensagem_instantanea;
            $verifica = $this->retornarLinha($sql);
            if ($verifica['total'] <= 0) {
                $sql = 'SELECT porcentagem_tira_duvida AS porcentagem FROM avas WHERE idava = '.$idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem'])
                    $porcentagem['porcentagem'] = 0;

                $sql = 'INSERT INTO
                            matriculas_rotas_aprendizagem_objetos
                        SET
                            data_cad = NOW(),
                            idmatricula = '.$idmatricula.',
                            idava = '.$idava.',
                            idmensagem_instantanea = '.$idmensagem_instantanea.',
                            porcentagem = '.$porcentagem['porcentagem'];
                if($this->executaSql($sql)) {
                    if ($mensagemContabilizado['total'] == 0) {
                        $sql = 'SELECT
                                    idmatricula_ava_porcentagem,
                                    porcentagem,
                                    COUNT(*) AS total
                                FROM
                                    matriculas_avas_porcentagem
                                WHERE
                                    idmatricula = '.$idmatricula.' AND
                                    idava = '.$idava;
                        $verificaPorcentagem = $this->retornarLinha($sql);
                        if (!$verificaPorcentagem['total']) {
                            $sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$idava.', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $sql = 'UPDATE
                                        matriculas_avas_porcentagem
                                    SET
                                        porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].'))
                                    WHERE
                                        idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                        }

                        if ($this->executaSql($sql)) {
                            $sql = 'UPDATE matriculas SET porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].')) WHERE idmatricula = '.$idmatricula;
                            if ($this->executaSql($sql)) {
                                //$sql = 'SELECT porcentagem FROM matriculas WHERE idmatricula = '.$idmatricula;
                                //$matriculaPorcentagem = $this->retornarLinha($sql);
                                //if($matriculaPorcentagem['porcentagem'] > 100) $matriculaPorcentagem['porcentagem'] = '100';

                                $this->executaSql("COMMIT");

                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("COMMIT");

                        $retorno['sucesso'] = true;
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $retorno['erro'] = true;
                    $retorno['erros'][] = $sql;
                    $retorno['erros'][] = mysql_error();
                }
            }

            return $retorno;
        }
    }

}