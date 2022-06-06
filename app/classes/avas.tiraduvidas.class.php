<?php
class Tira_Duvidas extends Ava {

  var $idava = NULL;
  var $modulo = NULL;

  function ListarTodasMensagensProfessor($idprofessor) {
	$this->sql = "select
					".$this->campos."
				  from
					avas_tiraduvidas at
					inner join matriculas m on (at.idmatricula = m.idmatricula)
					inner join ofertas o on (m.idoferta = o.idoferta)
					inner join cursos c on (m.idcurso = c.idcurso)
					inner join pessoas p on (m.idpessoa = p.idpessoa)
					inner join professores pr on (at.idprofessor = pr.idprofessor)
				  where
					at.ativo = 'S' and
					at.idprofessor = ".$idprofessor;

	$this->aplicarFiltrosBasicos();
	$this->groupby = "at.idtiraduvida";

	return $this->retornarLinhas();
  }

  function ListarTodasMensagensAluno($idmatricula, $idbloco_disciplina) {
	$this->sql = "select
					at.*,
					p.idpessoa,
					p.nome as pessoa,
					p.avatar_servidor as avatar_servidor_pessoa,
					pr.idprofessor,
					pr.nome as professor,
					pr.avatar_servidor as avatar_servidor_professor
				  from
					avas_tiraduvidas at
					inner join matriculas m on (at.idmatricula = m.idmatricula)
					inner join pessoas p on (m.idpessoa = p.idpessoa)
					inner join professores pr on (at.idprofessor = pr.idprofessor)
				  where
					at.ativo = 'S' and
					(at.idmatricula = ".$idmatricula.") and
					at.idbloco_disciplina = ".$idbloco_disciplina;

	$this->ordem = 'desc';
	$this->ordem_campo = 'at.data_cad';
	$this->limite = -1;

	return $this->retornarLinhas();
  }

  function RetornarMensagemProfessor($idtiraduvida) {
	$this->sql = "select
					at.idtiraduvida,
					atm.idmensagem,
					atm.idmatricula,
					atm.idprofessor,
					atm.data_cad,
					atm.mensagem,
					atm.arquivo_nome,
					atm.arquivo_servidor,
					atm.arquivo_tipo,
					atm.arquivo_tamanho,
					p.idpessoa,
					p.nome as pessoa,
					p.avatar_servidor as avatar_servidor_pessoa,
					pr.idprofessor,
					pr.nome as professor,
					pr.avatar_servidor as avatar_servidor_professor
				  from
					avas_tiraduvidas at
					inner join avas_tiraduvidas_mensagens atm on (at.idtiraduvida = atm.idtiraduvida)
					left outer join matriculas m on (atm.idmatricula = m.idmatricula)
					left outer join pessoas p on (m.idpessoa = p.idpessoa)
					left outer join professores pr on (atm.idprofessor = pr.idprofessor)
				  where
					at.idtiraduvida = ".$idtiraduvida." and
					at.ativo = 'S' and
					atm.ativo= 'S'";

	$this->ordem = 'asc';
	$this->ordem_campo = 'atm.data_cad';
	$this->limite = -1;

	return $this->retornarLinhas();
  }

  function RetornarMensagemDownload($idtiraduvida, $idmensagem) {
	$this->sql = "select
					atm.*
				  from
					avas_tiraduvidas at
					inner join avas_tiraduvidas_mensagens atm on (at.idtiraduvida = atm.idtiraduvida)
				  where
					at.idtiraduvida = ".$idtiraduvida." and
					atm.idmensagem = ".$idmensagem." and
					at.ativo = 'S' and
					atm.ativo = 'S'";

	return $this->retornarLinha($this->sql);
  }

  function verificaProfessor($idoferta, $idcurso, $idava, $idprofessor) {
	$this->sql = "select
					p.*
				  from
					professores p
					inner join professores_avas pa on (p.idprofessor = pa.idprofessor)
				  where
					p.idprofessor = ".$idprofessor." and
					p.ativo = 'S' and
					pa.ativo = 'S' and
					pa.idava = ".$idava;

	return $this->retornarLinha($this->sql);
  }

  function verificaMensagemProfessor($idmatricula, $idprofessor, $idbloco_disciplina) {
	$this->sql = "select
					idtiraduvida,
					sinalizador_professor
				  from
					avas_tiraduvidas
				  where
					ativo = 'S' and
					idmatricula = ".$idmatricula." and
					idprofessor = ".$idprofessor." and
					idbloco_disciplina = ".$idbloco_disciplina;

	return $this->retornarLinha($this->sql);
  }
  
  function Sinalizador($idtiraduvida, $idmatricula, $sim_nao){
	$this->sql = 'update avas_tiraduvidas set sinalizador_professor=\''.$sim_nao.'\' where idtiraduvida = '.$idtiraduvida.' and idmatricula='.$idmatricula.' ';
	return $this->executaSql($this->sql);	
  }

  function CadastrarMensagemProfessor($idmatricula, $idprofessor, $idbloco_disciplina) {

	if (verificaPermissaoAcesso(true)) {
		$arquivo = false;
		
		if($_FILES["arquivo"]["tmp_name"]) {
			$validar = $this->ValidarArquivo($_FILES["arquivo"]);
			$extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
			if($validar || ($extensao != ".jpg" && $extensao != ".jpeg" && $extensao != ".gif" && $extensao != ".png" && $extensao != ".bmp" && $extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
				$retorno = array('erro' => true);
				if($validar) {
					$retorno["mensagem"] = $validar;
				} else {
					$retorno["mensagem"] = "contratos_matricula_extensao_erro";
				}
				return $retorno;
			} else {
				$pasta = $_SERVER["DOCUMENT_ROOT"]."/storage/avas_tiraduvidas";
				$nomeServidor = date("YmdHis")."_".uniqid().$extensao;
				$envio = move_uploaded_file($_FILES["arquivo"]["tmp_name"],$pasta."/".$nomeServidor);
				chmod($pasta."/".$nomeServidor, 0777);
				if($envio) {
					$arquivo = true;
					$arquivo_nome = $_FILES["arquivo"]["name"];
					$arquivo_tipo = $_FILES["arquivo"]["type"];
					$arquivo_tamanho = $_FILES["arquivo"]["size"];
				} else {
					$retorno = array('erro' => true, 'mensagem' => '');
					return $retorno;
				}
			}
		}

		$mensagem = $this->verificaMensagemProfessor($idmatricula, $idprofessor, $idbloco_disciplina);
		if($mensagem['idtiraduvida']) {
			$idtiraduvida = $mensagem['idtiraduvida'];
		} else {
			$this->sql = 'insert into avas_tiraduvidas set data_cad = now(), idbloco_disciplina = '.$idbloco_disciplina.', idmatricula = '.$idmatricula.', idprofessor = '.$idprofessor.', sinalizador_professor=\'S\', sinalizador_aluno=\'S\' ';
			$this->executaSql($this->sql);
			$idtiraduvida = mysql_insert_id();
		}

		$this->sql = 'insert into avas_tiraduvidas_mensagens set data_cad = now(), idtiraduvida = '.$idtiraduvida.', mensagem = "'.$this->post['mensagem'].'"';
		if($this->modulo == 'professor') {
			$this->sql .= ', idprofessor = '.$idprofessor.'';
		} else {
		  	$this->sql .= ', idmatricula = '.$idmatricula.'';
		}
		if($arquivo) {
		  	$this->sql .= ', arquivo_nome = "'.$arquivo_nome.'", arquivo_servidor = "'.$nomeServidor.'", arquivo_tipo = "'.$arquivo_tipo.'", arquivo_tamanho = '.$arquivo_tamanho;
		}
		$this->executaSql($this->sql);
		$idmensagem = mysql_insert_id();
		
		if ($idmensagem && $this->modulo != 'professor') {
			$sql_professor = 'select nome, email from professores where idprofessor = ' . $idprofessor;
			$professor = $this->retornarLinha($sql_professor);
			
			$sql_aluno = 'select p.nome from pessoas p inner join matriculas m on m.idpessoa = p.idpessoa where m.idmatricula = ' . $idmatricula;
			$aluno = $this->retornarLinha($sql_aluno);
		
			$nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);
			$emailDe = $GLOBALS['config']['emailSistema'];

			$nomePara = utf8_decode($professor['nome']);
			$emailPara = $professor['email'];
			$assunto = 'Dúvida do aluno';
			
			$message  = "Ol&aacute; <strong>".$nomePara."</strong>,
							<br /><br />
							O Aluno ".$aluno["nome"].", de matr&iacute;cula " . $idmatricula . ", mandou a seguinte d&uacute;vida:
							<br /><br />";
			
			$message .= utf8_decode($this->post['mensagem']);
			$message = html_entity_decode($message);

			$this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);		
		}
		
		$this->sql = 'update avas_tiraduvidas set sinalizador_professor=\'S\', sinalizador_aluno=\'S\' where idtiraduvida = '.$idtiraduvida.' ';
		$this->executaSql($this->sql);	

		$retorno = array('sucesso' => true, 'idtiraduvida' => $idtiraduvida, 'idmensagem' => $idmensagem);

		return $retorno;
	}
  }

  function contabilizar($idmatricula, $idava, $idtiraduvida) {
	if (verificaPermissaoAcesso(false)) {
		$sql = "select count(*) as total from matriculas_rotas_aprendizagem_objetos where idmatricula = ".$idmatricula." and idava = ".$idava." and idtiraduvida is not null";
		$verifica = $this->retornarLinha($sql);
		if($verifica["total"] <= 0) {
		  $sql = "select porcentagem_tira_duvida from avas where idava = ".$idava;
		  $porcentagem = $this->retornarLinha($sql);
		  if(!$porcentagem['porcentagem_tira_duvida']) $porcentagem['porcentagem_tira_duvida'] = 0;

		  $sql = "insert into
					matriculas_rotas_aprendizagem_objetos
				  set
					data_cad = now(),
					idmatricula = ".$idmatricula.",
					idava = ".$idava.",
					idtiraduvida = ".$idtiraduvida.",
					porcentagem = ".$porcentagem['porcentagem_tira_duvida'];
		  $this->executaSql($sql);
		}

	    return true;
	}
  }

}

?>