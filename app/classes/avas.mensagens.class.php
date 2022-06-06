<?php
class Mensagens extends Ava
{

    var $modulo = null;

    function ListarTodasMensagens($idmatricula)
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					avas_mensagens am
					inner join matriculas md on (am.idmatricula_de = md.idmatricula)
					inner join matriculas mp on (am.idmatricula_para = mp.idmatricula)
					inner join pessoas pd on (md.idpessoa = pd.idpessoa)
					inner join pessoas pp on (mp.idpessoa = pp.idpessoa)
				  where 
					am.ativo = 'S' and
					(am.idmatricula_de = " . intval($idmatricula) . " or am.idmatricula_para = " . intval($idmatricula) . ")";

        $this->groupby = "am.idmensagem";

        return $this->retornarLinhas();
    }

    function RetornarForum()
    {
        $forum = array();

        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns f
					inner join avas a on f.idava = a.idava
				  where 
					f.ativo = 'S' and 
					f.idforum = " . $this->id;

        $forum = $this->retornarLinha($this->sql);

        $this->idava = $forum['idava'];

        $forum["permissoes"] = unserialize($forum["permissoes"]);
        $forum["total_topicos"] = $this->RetornarTotalTopicos($forum["idforum"]);
        $forum["total_respostas"] = $this->RetornarTotalRespostas($forum["idforum"]);

        return $forum;

    }

    function CadastrarForum()
    {
        $this->post["idava"] = $this->idava;

        return $this->SalvarDados();
    }

    function ModificarForum()
    {
        $this->post["idava"] = $this->idava;

        return $this->SalvarDados();
    }

    function RemoverForum()
    {
        return $this->RemoverDados();
    }

    function ListarTodasTopico($idforum, $verOculto = false)
    {

        $topicos = array();
        //$this->campos = "*";
        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos
				  where 
					ativo = 'S' and 
					idforum = " . $idforum;
        if (!$verOculto) {
            $this->sql .= " and oculto = 'N'";
        }

        $this->groupby = "idtopico";
        $topicos = $this->retornarLinhas();
        foreach ($topicos as $ind => $topico) {
            //$topicos[$ind]["respostas"] = $this->RetornarTotalRespostasTopico($topico["idtopico"]);
            $topicos[$ind]["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
            $topicos[$ind]["ultima_resposta_pessoa"] = $this->retornarPessoa($topico["ultima_mensagem_idusuario"], $topico["ultima_mensagem_idprofessor"], $topico["ultima_mensagem_idmatricula"]);
        }

        return $topicos;

    }

    function ListarTopicosPopulares($idforum = false, $verOculto = false)
    {
        $topicos = array();

        $this->campos = "f.idforum, f.nome, t.idtopico, t.data_cad, t.nome, count(m.idtopico) as respostas";

        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos t
					inner join avas_foruns f on (t.idforum = f.idforum and f.ativo = 'S')
					inner join avas_foruns_topicos_mensagens m on (t.idtopico = m.idtopico)
				  where 
					t.ativo = 'S' and 
					f.idava = " . $this->idava;
        if ($idforum) {
            $this->sql .= " and f.idforum = " . $idforum;
        }
        if (!$verOculto) {
            $this->sql .= " and t.oculto = 'N'";
        }
        $this->sql .= " group by t.idtopico";

        $this->ordem = "desc";
        $this->ordem_campo = "respostas";
        $this->limite = "5";
        $this->groupby = "t.idtopico";
        $topicos = $this->retornarLinhas();

        foreach ($topicos as $ind => $topico) {
            $topicos[$ind]["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
        }
        return $topicos;
    }

    /*function ListarTodasMensagens($idtopico, $verOculto = false)
    {

        $respostas = array();
        $this->campos = "*";
        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos_mensagens
				  where 
					ativo = 'S' and 
					idtopico = " . $idtopico;
        if (!$verOculto) {
            $this->sql .= " and oculto = 'N'";
        }

        $this->ordem = "asc";
        $this->ordem_campo = "idmensagem";
        $this->groupby = "idmensagem";
        $respostas = $this->retornarLinhas();
        foreach ($respostas as $ind => $resposta) {
            $respostas[$ind]["criado_por"] = $this->retornarPessoa($resposta["idusuario"], $resposta["idprofessor"], $resposta["idmatricula"]);
            if ($resposta["idmensagem_associada"]) {
                $mensagemAssociada = $this->RetornarMensagem($resposta["idmensagem_associada"]);
                $mensagemAssociada["criado_por"] = $this->retornarPessoa($mensagemAssociada["idusuario"], $mensagemAssociada["idprofessor"], $mensagemAssociada["idmatricula"]);
                $respostas[$ind]["associada"] = $mensagemAssociada;
            }
        }

        return $respostas;

    }*/

    function retornarPessoa($idusuario, $idprofessor, $idmatricula)
    {
        $retorno = array();

        if ($idusuario) {
            $this->sql = "select
					  nome,
					  'gestor' as tipo,
					  avatar_servidor as avatar
					from
					  usuarios_adm 
					where 
					  idusuario = " . $idusuario;
            $retorno = $this->retornarLinha($this->sql);
        } elseif ($idprofessor) {
            $this->sql = "select
					  nome,
					  'professor' as tipo,
					  '' as avatar
					from
					  professores
					where 
					  idprofessor = " . $idprofessor;
            $retorno = $this->retornarLinha($this->sql);
        } elseif ($idmatricula) {
            $this->sql = "select
					  p.nome,
					  'aluno' as tipo,
					  avatar_servidor as avatar
					from
					  pessoas p 
					  inner join matriculas m on (p.idpessoa = m.idpessoa)
					where 
					  m.idmatricula = " . $idmatricula;
            $retorno = $this->retornarLinha($this->sql);
        }

        return $retorno;

    }

    function ListarAlunosAtivos($idforum = false)
    {

        $this->campos = "tm.idmensagem, tm.idmatricula, p.nome, p.avatar_servidor, count(tm.idmensagem) as mensagens";

        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos_mensagens tm
					inner join avas_foruns_topicos t on (tm.idtopico = t.idtopico)
					inner join avas_foruns f on (t.idforum = f.idforum)
					inner join matriculas m on (tm.idmatricula = m.idmatricula)
					inner join pessoas p on (m.idpessoa = p.idpessoa)
				  where 
					tm.ativo = 'S' and 
					f.idava = " . $this->idava;

        if ($idforum) {
            $this->sql .= " and f.idforum = " . $idforum;
        }
        $this->sql .= " group by p.idpessoa";

        $this->ordem = "desc";
        $this->ordem_campo = "mensagens";
        $this->limite = "5";
        $this->groupby = "tm.idmensagem";
        return $this->retornarLinhas();

    }

    function ParticipantesTopico($idtopico)
    {
        $this->campos = "tm.idmensagem, tm.idmatricula, p.nome, p.avatar_servidor, count(tm.idmensagem) as mensagens";

        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos_mensagens tm
					inner join avas_foruns_topicos t on (tm.idtopico = t.idtopico)
					inner join matriculas m on (tm.idmatricula = m.idmatricula)
					inner join pessoas p on (m.idpessoa = p.idpessoa)
				  where 
					tm.ativo = 'S' and 
					t.idtopico = " . $idtopico . "
				  group by p.idpessoa";

        $this->ordem = "desc";
        $this->ordem_campo = "mensagens";
        $this->limite = "-1";
        $this->groupby = "tm.idmensagem";
        return $this->retornarLinhas();

    }

    function RetornarTotalTopicos($idforum)
    {
        $this->sql = "select
					count(idtopico) as total
				  from
					avas_foruns_topicos
				  where 
					idforum = " . $idforum;
        $total = $this->retornarLinha($this->sql);
        return $total["total"];
    }

    function RetornarTotalRespostas($idforum)
    {
        $this->sql = "select
					count(m.idmensagem) as total
				  from
					avas_foruns_topicos_mensagens m
					inner join avas_foruns_topicos t on (m.idtopico = t.idtopico)
				  where 
					t.idforum = " . $idforum;
        $total = $this->retornarLinha($this->sql);
        return $total["total"];
    }

    /*function RetornarTotalRespostasTopico($idtopico) {
      $this->sql = "select
                      count(idmensagem) as total
                    from
                      avas_foruns_topicos_mensagens
                    where
                      idtopico = ".$idtopico;
      $total = $this->retornarLinha($this->sql);
      return $total["total"];
    }*/

    function RetornarTopico($idtopico)
    {
        $topico = array();

        if ($this->modulo == 'aluno') {
            $this->sql = "update avas_foruns_topicos set visualizacoes = visualizacoes + 1 where idtopico = " . $idtopico;
            $this->executaSql($this->sql);
        }

        $this->sql = "select
					*
				  from
					avas_foruns_topicos
				  where 
					idtopico = " . $idtopico;
        $topico = $this->retornarLinha($this->sql);

        if ($topico["moderado"] == "S") {
            $topico["moderado_por"] = $this->retornarPessoa($topico["moderado_idusuario"], $topico["moderado_idprofessor"], $topico["moderado_idmatricula"]);
        }

        //$topico["respostas"] = $this->RetornarTotalRespostasTopico($topico["idtopico"]);
        $topico["criado_por"] = $this->retornarPessoa($topico["idusuario"], $topico["idprofessor"], $topico["idmatricula"]);
        $topico["ultima_resposta_pessoa"] = $this->retornarPessoa($topico["ultima_mensagem_idusuario"], $topico["ultima_mensagem_idprofessor"], $topico["ultima_mensagem_idmatricula"]);

        return $topico;
    }

    function countabilizarDownloadTopico($idtopico)
    {
        $this->sql = "update avas_foruns_topicos set arquivo_downloads = arquivo_downloads + 1 where idtopico = " . $idtopico;
        return $this->executaSql($this->sql);
    }

    function countabilizarDownloadMensagem($idmensagem)
    {
        $this->sql = "update avas_foruns_topicos_mensagens set arquivo_downloads = arquivo_downloads + 1 where idmensagem = " . $idmensagem;
        return $this->executaSql($this->sql);
    }

    function RetornarMensagem($idmensagem)
    {
        $mensagem = array();

        $this->sql = "select
					" . $this->campos . "
				  from
					avas_foruns_topicos_mensagens
				  where 
					ativo = 'S' and 
					idmensagem = " . $idmensagem;
        return $this->retornarLinha($this->sql);

    }

    function verificaAssinaturaTopico($idtopico, $idusuario, $idprofessor, $idmatricula)
    {

        if ($idusuario) {
            $this->sql = "select * from avas_foruns_topicos_assinantes where ativo = 'S' and idtopico = " . intval($idtopico) . " and idusuario = " . intval($idusuario);
        } elseif ($idprofessor) {
            $this->sql = "select * from avas_foruns_topicos_assinantes where ativo = 'S' and idtopico = " . intval($idtopico) . " and idprofessor = " . intval($idprofessor);
        } elseif ($idmatricula) {
            $this->sql = "select * from avas_foruns_topicos_assinantes where ativo = 'S' and idtopico = " . intval($idtopico) . " and idmatricula = " . intval($idmatricula);
        }

        $verifica = $this->retornarLinha($this->sql);
        if ($verifica["idassinatura"]) {
            return $verifica["idassinatura"];
        } else {
            return false;
        }

    }

    function CadastrarTopico()
    {
        $this->post["idforum"] = $this->id;
        if ($this->modulo == "gestor") {
            $this->post["idusuario"] = $this->idusuario;
        } elseif ($this->modulo == "aluno") {
            $this->post["idmatricula"] = $this->idmatricula;
        } elseif ($this->modulo == "professor") {
            $this->post["idprofessor"] = $this->idprofessor;
        }

        return $this->SalvarDados();
    }

    function ResponderTopico($idtopico)
    {
        $erros = array();

        if (!$idtopico) {
            $erros[] = "idtopico_vazio";
        }
        if (!$this->post["mensagem"]) {
            $erros[] = "mensagem_vazio";
        }
        if ($_FILES["arquivo"]["name"]) {
            if ($this->ValidarArquivo($_FILES["arquivo"])) {
                $erros[] = "arquivo_invalido";
            } else {
                $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
                $arquivoServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                if (!move_uploaded_file($_FILES["arquivo"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/storage/avas_foruns_topicos_mensagens_arquivo/" . $arquivoServidor)) {
                    $erros[] = "arquivo_falhou";
                }
            }
        }

        if (count($erros) <= 0) {

            $this->sql = "insert into
					  avas_foruns_topicos_mensagens
					set 
					  data_cad = now(), 
					  idtopico = " . $idtopico . ",
					  mensagem = '" . $this->post["mensagem"] . "'";
            if ($this->post["idmensagem_associada"]) {
                $this->sql .= ", idmensagem_associada = " . $this->post["idmensagem_associada"];
            }
            if ($this->modulo == "gestor") {
                $this->sql .= ", idusuario = '" . $this->idusuario . "'";
            } elseif ($this->modulo == "aluno") {
                $this->sql .= ", idmatricula = '" . $this->idmatricula . "'";
            } elseif ($this->modulo == "professor") {
                $this->sql .= ", idprofessor = '" . $this->idprofessor . "'";
            }
            if ($_FILES["arquivo"]["name"]) {
                $this->sql .= ", arquivo_nome = '" . $_FILES["arquivo"]["name"] . "',
					   arquivo_servidor = '" . $arquivoServidor . "',
					   arquivo_tipo = '" . $_FILES["arquivo"]["type"] . "',
					   arquivo_tamanho = " . $_FILES["arquivo"]["size"];
            }

            if ($this->executaSql($this->sql)) {
                $this->monitora_oque = 1;
                $this->monitora_onde = "154";
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();

                $this->sql = "update avas_foruns_topicos set total_mensagens = total_mensagens + 1, ultima_mensagem_data = now()";
                if ($this->modulo == "gestor") {
                    $this->sql .= ", ultima_mensagem_idusuario = " . $this->idusuario . "";
                } elseif ($this->modulo == "aluno") {
                    $this->sql .= ", ultima_mensagem_idmatricula = '" . $this->idmatricula . "'";
                } elseif ($this->modulo == "professor") {
                    $this->sql .= ", ultima_mensagem_idprofessor = '" . $this->idprofessor . "'";
                }
                $this->sql .= " where idtopico = " . intval($idtopico);
                $this->executaSql($this->sql);

                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["erros"] = $erros;
        }

        return $this->retorno;
    }

    function ModerarTopico($idtopico)
    {

        $erros = array();

        if (!$this->post["moderar"]) {
            $erros[] = "mensagem_vazio";
        }

        if (count($erros) <= 0) {
            $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "update
					  avas_foruns_topicos
					set 
					  moderado = 'S',
					  moderado_quando = now(),
					  moderado_mensagem = '" . $this->post["moderar"] . "'";
            if ($this->modulo == "gestor") {
                $this->sql .= ", moderado_idusuario = '" . $this->idusuario . "'";
            } elseif ($this->modulo == "aluno") {
                $this->sql .= ", moderado_idmatricula = '" . $this->idmatricula . "'";
            } elseif ($this->modulo == "professor") {
                $this->sql .= ", moderado_idprofessor = '" . $this->idprofessor . "'";
            }
            echo $this->sql .= " where idtopico = " . intval($idtopico);

            if ($this->executaSql($this->sql)) {

                $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
                $linhaNova = $this->retornarLinha($this->sql);

                $this->monitora_oque = 2;
                $this->monitora_onde = "153";
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->monitora_qual = intval($idtopico);
                $this->Monitora();
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["erros"] = $erros;
        }

        return $this->retorno;
    }

    function ModerarMensagem($idmensagem)
    {
        $erros = array();

        if (!$this->post["moderar"]) {
            $erros[] = "mensagem_vazio";
        }

        if (count($erros) <= 0) {

            $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idmensagem);
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "update
					  avas_foruns_topicos_mensagens
					set 
					  moderado = 'S',
					  moderado_quando = now(),
					  moderado_mensagem = '" . $this->post["moderar"] . "'";
            if ($this->modulo == "gestor") {
                $this->sql .= ", moderado_idusuario = '" . $this->idusuario . "'";
            } elseif ($this->modulo == "aluno") {
                $this->sql .= ", moderado_idmatricula = '" . $this->idmatricula . "'";
            } elseif ($this->modulo == "professor") {
                $this->sql .= ", moderado_idprofessor = '" . $this->idprofessor . "'";
            }
            $this->sql .= " where idmensagem = " . intval($idmensagem);

            if ($this->executaSql($this->sql)) {

                $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idmensagem);
                $linhaNova = $this->retornarLinha($this->sql);

                $this->monitora_oque = 2;
                $this->monitora_onde = "154";
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->monitora_qual = intval($idmensagem);
                $this->Monitora();
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["erros"] = $erros;
        }

        return $this->retorno;
    }

    function ocultarTopico($idtopico)
    {

        $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos
				  set 
					oculto = 'S',
					oculto_quando = now()";
        if ($this->modulo == "gestor") {
            $this->sql .= ", oculto_idusuario = '" . $this->idusuario . "'";
        } elseif ($this->modulo == "aluno") {
            $this->sql .= ", oculto_idmatricula = '" . $this->idmatricula . "'";
        } elseif ($this->modulo == "professor") {
            $this->sql .= ", oculto_idprofessor = '" . $this->idprofessor . "'";
        }
        $this->sql .= " where idtopico = " . intval($idtopico);

        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "153";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function desocultarTopico($idtopico)
    {

        $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos
				  set 
					oculto = 'N',
					oculto_quando = null, 
					oculto_idusuario = null, 
					oculto_idprofessor = null, 
					oculto_idmatricula = null
				  where 
					idtopico = " . intval($idtopico);
        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "153";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function ocultarMensagem($idmensagem, $idtopico)
    {

        $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idmensagem);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos_mensagens
				  set 
					oculto = 'S',
					oculto_quando = now()";
        if ($this->modulo == "gestor") {
            $this->sql .= ", oculto_idusuario = '" . $this->idusuario . "'";
        } elseif ($this->modulo == "aluno") {
            $this->sql .= ", oculto_idmatricula = '" . $this->idmatricula . "'";
        } elseif ($this->modulo == "professor") {
            $this->sql .= ", oculto_idprofessor = '" . $this->idprofessor . "'";
        }
        $this->sql .= " where idmensagem = " . intval($idmensagem);

        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "154";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function desocultarMensagem($idmensagem)
    {

        $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idmensagem);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos_mensagens
				  set 
					oculto = 'N',
					oculto_quando = null, 
					oculto_idusuario = null, 
					oculto_idprofessor = null, 
					oculto_idmatricula = null
				  where 
					idmensagem = " . intval($idmensagem);
        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos_mensagens where idmensagem = " . intval($idmensagem);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "154";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idmensagem);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function bloquearTopico($idtopico)
    {

        $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos
				  set 
					bloqueado = 'bloqueado',
					bloqueado_quando = now()";
        if ($this->modulo == "gestor") {
            $this->sql .= ", bloqueado_idusuario = '" . $this->idusuario . "'";
        } elseif ($this->modulo == "aluno") {
            $this->sql .= ", bloqueado_idmatricula = '" . $this->idmatricula . "'";
        } elseif ($this->modulo == "professor") {
            $this->sql .= ", bloqueado_idprofessor = '" . $this->idprofessor . "'";
        }
        $this->sql .= " where idtopico = " . intval($idtopico);

        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "153";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function desbloquearTopico($idtopico)
    {

        $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
					avas_foruns_topicos
				  set 
					bloqueado = 'desbloqueado',
					bloqueado_quando = null, 
					bloqueado_idusuario = null,
					bloqueado_idprofessor = null,
					bloqueado_idmatricula = null
				  where 
					idtopico = " . intval($idtopico);

        if ($this->executaSql($this->sql)) {
            $this->sql = "select * from avas_foruns_topicos where idtopico = " . intval($idtopico);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = "153";
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora_qual = intval($idtopico);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function assinarTopico($idtopico)
    {

        $this->sql = "select idassinatura from avas_foruns_topicos_assinantes where idtopico = " . intval($idtopico);
        if ($this->modulo == "gestor") {
            $this->sql .= " and idusuario = " . $this->idusuario;
        } elseif ($this->modulo == "aluno") {
            $this->sql .= " and idmatricula = " . $this->idmatricula;
        } elseif ($this->modulo == "professor") {
            $this->sql .= " and idprofessor = '" . $this->idprofessor . "'";
        }
        $verifica = $this->retornarLinha($this->sql);

        if ($verifica["idassinatura"]) {
            $this->sql = "update
					  avas_foruns_topicos_assinantes
					set 
					  ativo = 'S'
					where
					  idassinatura = " . $verifica["idassinatura"];
            $this->monitora_qual = $verifica["idassinatura"];
        } else {
            $this->sql = "insert into
					  avas_foruns_topicos_assinantes
					set 
					  data_cad = now(),
					  idtopico = " . intval($idtopico);
            if ($this->modulo == "gestor") {
                $this->sql .= ", idusuario = '" . $this->idusuario . "'";
            } elseif ($this->modulo == "aluno") {
                $this->sql .= ", idmatricula = '" . $this->idmatricula . "'";
            } elseif ($this->modulo == "professor") {
                $this->sql .= ", idprofessor = '" . $this->idprofessor . "'";
            }
            $this->monitora_qual = mysql_insert_id();
        }

        if ($this->executaSql($this->sql)) {

            $this->monitora_oque = 1;
            $this->monitora_onde = "155";
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function desassinarTopico($idassinatura)
    {

        $this->sql = "update
					avas_foruns_topicos_assinantes
				  set 
					ativo = 'N'
				  where
					idassinatura = " . intval($idassinatura);

        if ($this->executaSql($this->sql)) {

            $this->monitora_oque = 3;
            $this->monitora_onde = "155";
            $this->monitora_qual = intval($idassinatura);
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function CurtirTopicoMensagem()
    {

        $jaVotou = $this->VerificaVotoTopicoMensagem();

        $topico = array();

        if (!$jaVotou) {
            if ($this->post["tipo"] == "curtir_topico") {
                $tabela = "avas_foruns_topicos";
                $campo = "total_curtiu";
                $id = "idtopico";
                $tipo = "curtiu";
            } elseif ($this->post["tipo"] == "nao_curtir_topico") {
                $tabela = "avas_foruns_topicos";
                $campo = "total_nao_curtiu";
                $id = "idtopico";
                $tipo = "nao_curtiu";
            } elseif ($this->post["tipo"] == "curtir_mensagem") {
                $tabela = "avas_foruns_topicos_mensagens";
                $campo = "total_curtiu";
                $id = "idmensagem";
                $tipo = "curtiu";
            } elseif ($this->post["tipo"] == "nao_curtir_mensagem") {
                $tabela = "avas_foruns_topicos_mensagens";
                $campo = "total_nao_curtiu";
                $id = "idmensagem";
                $tipo = "nao_curtiu";
            }

            $this->sql = "update " . $tabela . " set " . $campo . " = " . $campo . " + 1 where " . $id . " = " . $this->post["id"];
            $this->executaSql($this->sql);

            $this->sql = "select * from " . $tabela . " where " . $id . " = " . $this->post["id"];
            $topico = $this->retornarLinha($this->sql);

            if ($this->modulo == "gestor") {
                $this->sql = "insert into avas_foruns_topicos_curtidas set data_cad = now(), ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "'), idusuario = " . $this->idusuario;
            } elseif ($this->modulo == "professor") {
                $this->sql = "insert into avas_foruns_topicos_curtidas set data_cad = now(), ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "'), idprofessor = " . $this->idprofessor;
            } elseif ($this->modulo == "aluno") {
                $this->sql = "insert into avas_foruns_topicos_curtidas set data_cad = now(), ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "'), idmatricula = " . $this->idmatricula;
            }
            $this->sql .= ", tipo = '" . $tipo . "', " . $id . " = " . $this->post["id"];
            $this->executaSql($this->sql);

            $topico["mensagem"] = "voto_computado_sucesso";
            $topico["contador"] = $topico[$campo];
        } else {
            $topico["mensagem"] = "ja_votou";
        }

        echo json_encode($topico);
    }

    function VerificaVotoTopicoMensagem()
    {
        if ($this->modulo == "gestor") {
            $this->sql = "select count(*) as total from avas_foruns_topicos_curtidas where idusuario = " . $this->idusuario;
        } elseif ($this->modulo == "professor") {
            $this->sql = "select count(*) as total from avas_foruns_topicos_curtidas where idprofessor = " . $this->idprofessor;
        } elseif ($this->modulo == "aluno") {
            $this->sql = "select count(*) as total from avas_foruns_topicos_curtidas where idmatricula = " . $this->idmatricula;
        }

        if ($this->post["tipo"] == "curtir_topico" || $this->post["tipo"] == "nao_curtir_topico") {
            $this->sql .= " and idtopico = " . $this->post["id"];
        } elseif ($this->post["tipo"] == "curtir_mensagem" || $this->post["tipo"] == "nao_curtir_mensagem") {
            $this->sql .= " and idmensagem = " . $this->post["id"];
        }

        $verifica = $this->retornarLinha($this->sql);

        if ($verifica["total"] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*function Cadastrar() {
      $erros = array();

      if(!$this->post["nome"]) {
        $erros[] = "nome_vazio";
      }
      if(!$this->post["mensagem"]) {
        $erros[] = "financeiro_vazio";
      }
      if($this->post["arquivo"]["name"]) {
        if($this->ValidarArquivo($this->post["arquivo"])) {
          $erros[] = "arquivo_vazio";
        } else {
          $extensao = strtolower(strrchr($this->post["arquivo"]["name"], "."));
          $arquivoServidor = date("YmdHis")."_".uniqid().$extensao;
          if(!move_uploaded_file($this->post["arquivo"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_arquivo/".$arquivoServidor)) {
            $erros[] = "arquivo_vazio";
          }
        }
      }

      if(count($erros) <= 0) {

        $this->sql = "insert into
                        avas_foruns_topicos
                      set
                        data_cad = now(),
                        idforum = ".$this->id.",
                        nome = '".$this->post["nome"]."',
                        mensagem = '".$this->post["mensagem"]."'";
        if($this->modulo == "gestor"){
           $this->sql .= ", idusuario = '".$this->idusuario."'";
        }
        if($this->post["periode_de"]){
           $this->sql .= ", periode_de = '".formataData($this->post["periode_de"], "en", 0)."'";
        }
        if($this->post["periode_ate"]){
           $this->sql .= ", periode_ate = '".formataData($this->post["periode_ate"], "en", 0)."'";
        }
        if($this->post["arquivo"]["name"]){
           $this->sql .= ", arquivo = '".$this->post["arquivo"]["name"]."',
                         arquivo_servidor = '".$arquivoServidor."',
                         arquivo_tipo = '".$this->post["arquivo"]["type"]."',
                         arquivo_tamanho = ".$this->post["arquivo"]["size"];
        }

        if($this->executaSql($this->sql)){
          $this->monitora_oque = 1;
          $this->monitora_onde = "153";
          $this->monitora_qual = mysql_insert_id();
          $this->Monitora();
          $this->retorno = $this->Retornar();
          $this->retorno["sucesso"] = true;
        } else {
          $this->retorno["erro"] = true;
          $this->retorno["erros"][] = $this->sql;
          $this->retorno["erros"][] = mysql_error();
        }
      } else {
        $this->retorno["sucesso"] = false;
        $this->retorno["erros"] = $erros;
      }

      return $this->retorno;
    }*/

    /*function ModificarTopico() {
      $this->post["idforum"] = $this->id;
      if($this->modulo == "gestor"){
        $this->post["idusuario"] = $this->idusuario;
      }

      return $this->SalvarDados();
    }*/

    function RemoverTopico()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

}

?>