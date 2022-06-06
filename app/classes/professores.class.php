<?php
class Professores extends Core {

  function ListarTodas() {
	$this->sql = "select
					".$this->campos."
				  from
					professores p
					left outer join cidades c on (p.idcidade = c.idcidade)
					left outer join estados e on (p.idestado = e.idestado)
					left outer join paises pa on (p.idpais = pa.idpais)
				  where
					p.ativo = 'S'";

	$this->aplicarFiltrosBasicos();

	$this->groupby = "idprofessor";
	return $this->retornarLinhas();
  }


  function Retornar() {
	$this->sql = "select
					".$this->campos."
				  from
					professores p
					left outer join paises pa on (p.idpais = pa.idpais)
				  where
					p.ativo = 'S' and
					p.idprofessor = '".$this->id."'";
	return $this->retornarLinha($this->sql);
  }

  function Cadastrar() {
	return $this->SalvarDados();
  }

  function Modificar() {
	return $this->SalvarDados();
  }

  function Remover() {
	return $this->RemoverDados();
  }

  function RetornarPaises() {
	$this->sql = "select idpais as 'key', nome as value from paises where nome like '%".$_GET["tag"]."%'";
	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->groupby = "nome";
	$dados = $this->retornarLinhas();

	return json_encode($dados);
  }

  function AtivarLogin($situacao) {

	if($situacao <> "S" && $situacao <> "N"){
	   $info['sucesso'] = false;
	   $info['situacao'] = $situacao;
	   return json_encode($info);
	}

	$this->sql = "select * from professores where idprofessor = ".intval($this->id);
	$linhaAntiga = $this->retornarLinha($this->sql);

	$this->sql = "update professores set ativo_login = '".mysql_real_escape_string($situacao)."' where idprofessor = '".intval($this->id)."'";
	$executa = $this->executaSql($this->sql);

	$this->sql = "select * from professores where idprofessor = ".intval($this->id);
	$linhaNova = $this->retornarLinha($this->sql);

	$info = array();

	if($executa){
	  $this->monitora_oque = 2;
	  $this->monitora_qual = $this->id;
	  $this->monitora_dadosantigos = $linhaAntiga;
	  $this->monitora_dadosnovos = $linhaNova;
	  $this->Monitora();

	  $info['sucesso'] = true;
	  $info['situacao'] = $linhaNova["ativo_login"];
	} else {
	  $info['sucesso'] = false;
	  $info['situacao'] = $situacao;
	}

	return json_encode($info);
  }

  function ResetarSenha($confirmacao, $enviarEmail, $exibirNovaSenha) {

	if(!$confirmacao){
	  $info['sucesso'] = false;
	  $info['confirmacao'] = $confirmacao;
	  $info['enviar_email'] = $enviarEmail;
	  $info['exibir_nova_senha'] = $exibirNovaSenha;
	  $info['mensagem'] = "Erro ao tentar resetar a senha.";
	  return json_encode($info);
	}

	$novaSenha = gerarNovaSenha();
	$senha = senhaSegura($novaSenha,$this->config["chaveLogin"]);

	$this->sql = "select * from professores where idprofessor = ".intval($this->id);
	$linhaAntiga = $this->retornarLinha($this->sql);

	$this->sql = "update professores set senha = '".$senha."' where idprofessor = ".intval($this->id);
	$modificou = $this->executaSql($this->sql);

	$this->sql = "select * from professores where idprofessor = ".intval($this->id);
	$linhaNova = $this->retornarLinha($this->sql);

	$info = array();

	if($modificou){

	  $this->monitora_oque = 2;
	  $this->monitora_qual = $this->id;
	  $this->monitora_dadosantigos = $linhaAntiga;
	  $this->monitora_dadosnovos = $linhaNova;
	  $this->Monitora();

	  $info['sucesso'] = true;
	  $info['confirmacao'] = $confirmacao;
	  $info['enviar_email'] = $enviarEmail;
	  $info['exibir_nova_senha'] = $exibirNovaSenha;
	  $info['nova_senha'] = $novaSenha;

	  if($enviarEmail) {
		$message = 'Algu&eacute;m, possivelmente voc&ecirc;, solicitou uma nova senha de acesso ao sistema.
					<br />
					<br />
					<strong>Acesse:</strong> <a href="'.$this->config["urlSistema"].'/professor">'.$this->config["urlSistema"].'/professor</a>
					<br />
					<br />
					<strong>E-mail de acesso:</strong> '.$linhaNova["email"].'
					<br />
					<strong>Senha de acesso:</strong> '.$novaSenha;

		$nomePara = utf8_decode($linhaNova["nome"]);
		$emailPara = $linhaNova["email"];
		$assunto = utf8_decode("ESQUECI MINHA SENHA");

		$nomeDe = $GLOBALS["config"]["tituloEmpresa"];
		$emailDe = $GLOBALS["config"]["emailSistemaReserva"];

		if ($this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout"))
		  $info['sucesso_email'] = 'sucesso_email';
	  }
	} else {
	   $info['sucesso'] = false;
	   $info['confirmacao'] = $confirmacao;
	   $info['enviar_email'] = $enviarEmail;
	   $info['exibir_nova_senha'] = $exibirNovaSenha;
	}

	return json_encode($info);
  }

    function BuscarCurso() {
		$this->sql = "select
						c.idcurso as 'key', c.nome as value
					  from
						cursos c
					  where
					     c.nome like '%".$_GET["tag"]."%' AND
						 c.ativo = 'S' AND
						 c.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT pc.idcurso FROM professores_cursos pc WHERE pc.idcurso = c.idcurso AND pc.idprofessor = '".$this->id."' AND pc.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";

		$dados = $this->retornarLinhas();

		return json_encode($dados);
	}

	function ListarCursosAss() {
		$this->sql = "SELECT
						".$this->campos."
					  FROM
						cursos c
						INNER JOIN professores_cursos pc ON (c.idcurso = pc.idcurso)
					  WHERE
						pc.ativo = 'S' and c.ativo = 'S' and c.ativo_painel = 'S' and
						pc.idprofessor = ".intval($this->id);

		$this->groupby = "pc.idprofessor_curso";
		return $this->retornarLinhas();
	}

	function AssociarCursos($idprofessor, $arrayCursos) {
		foreach($arrayCursos as $ind => $id) {

			  $this->sql = "select count(idprofessor_curso) as total, idprofessor_curso from professores_cursos where idprofessor = '".intval($idprofessor)."' and idcurso = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql);
			  if($totalAss["total"] > 0) {
				  $this->sql = "update professores_cursos set ativo = 'S' where idprofessor_curso = ".$totalAss["idprofessor_curso"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idprofessor_curso"];
			  } else {
				  $this->sql = "insert into professores_cursos set ativo = 'S', data_cad = now(), idprofessor = '".intval($idprofessor)."', idcurso = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }

			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 58;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}

		}
		return $this->retorno;
	}

	function DesassociarCursos() {

		include_once("../includes/validation.php");
		$regras = array(); // stores the validation rules

		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";

		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update professores_cursos set ativo = 'N' where idprofessor_curso = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 58;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}

		return $this->retorno;

	}

	function BuscarAva() {
		$this->sql = "select
						a.idava as 'key', a.nome as value
					  from
						avas a
					  where
					     a.nome like '%".$_GET["tag"]."%' AND
						 a.ativo = 'S' AND
						 a.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT pa.idava FROM professores_avas pa WHERE pa.idava = a.idava AND pa.idprofessor = '".$this->id."' AND pa.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";

		$dados = $this->retornarLinhas();

		return json_encode($dados);
	}

	function ListarAvasAss() {
		$this->sql = "SELECT
						".$this->campos."
					  FROM
						avas a
						INNER JOIN professores_avas pa ON (a.idava = pa.idava)
					  WHERE
						pa.ativo = 'S' and a.ativo = 'S' and a.ativo_painel = 'S' and
						pa.idprofessor = ".intval($this->id);

		$this->aplicarFiltrosBasicos();
		$this->groupby = "pa.idprofessor_ava";
		return $this->retornarLinhas();
	}

	function AssociarAvas($idprofessor, $arrayAvas) {
		foreach($arrayAvas as $ind => $id) {

			  $this->sql = "select count(idprofessor_ava) as total, idprofessor_ava from professores_avas where idprofessor = '".intval($idprofessor)."' and idava = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql);
			  if($totalAss["total"] > 0) {
				  $this->sql = "update professores_avas set ativo = 'S' where idprofessor_ava = ".$totalAss["idprofessor_ava"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idprofessor_ava"];
			  } else {
				  $this->sql = "insert into professores_avas set ativo = 'S', data_cad = now(), idprofessor = '".intval($idprofessor)."', idava = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }

			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 59;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}

		}
		return $this->retorno;
	}

	function DesassociarAvas() {

		include_once("../includes/validation.php");
		$regras = array(); // stores the validation rules

		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";

		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update professores_avas set ativo = 'N' where idprofessor_ava = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 59;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}

		return $this->retorno;

	}

	function BuscarOferta() {
		$this->sql = "select
						o.idoferta as 'key', o.nome as value
					  from
						ofertas o
					  where
					     o.nome like '%".$_GET["tag"]."%' AND
						 o.ativo = 'S' AND
						 o.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT po.idoferta FROM professores_ofertas po WHERE po.idoferta = o.idoferta AND po.idprofessor = '".$this->id."' AND po.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";

		$dados = $this->retornarLinhas();
		return json_encode($dados);
	}

	function ListarOfertasAss() {
		$this->sql = "SELECT
						 ".$this->campos."
					  FROM
						ofertas o
						inner join ofertas_workflow ow on o.idsituacao = ow.idsituacao
						INNER JOIN professores_ofertas po ON (o.idoferta = po.idoferta)
					  WHERE
						po.ativo = 'S' and o.ativo = 'S' and o.ativo_painel = 'S' and
						po.idprofessor = ".intval($this->id);

		$this->groupby = "po.idprofessor_oferta";
		return $this->retornarLinhas();
	}

	function AssociarOfertas($idprofessor, $arrayOfertas) {
		foreach($arrayOfertas as $ind => $id) {

			  $this->sql = "select count(idprofessor_oferta) as total, idprofessor_oferta from professores_ofertas where idprofessor = '".intval($idprofessor)."' and idoferta = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql);
			  if($totalAss["total"] > 0) {
				  $this->sql = "update professores_ofertas set ativo = 'S' where idprofessor_oferta = ".$totalAss["idprofessor_oferta"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idprofessor_oferta"];
			  } else {
				  $this->sql = "insert into professores_ofertas set ativo = 'S', data_cad = now(), idprofessor = '".intval($idprofessor)."', idoferta = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }

			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 60;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}

		}
		return $this->retorno;
	}

	function DesassociarOfertas() {

		include_once("../includes/validation.php");
		$regras = array(); // stores the validation rules

		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";

		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update professores_ofertas set ativo = 'N' where idprofessor_oferta = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 60;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}

		return $this->retorno;

	}
	function listarTotalProfessores() {
		$this->sql = "select
						count(p.idprofessor) as total
					  from
						professores p
					  where
					  	p.ativo = 'S' ";
		$dados = $this->retornarLinha($this->sql);
		return $dados['total'];
	}

	public function associacoesDoProfessor($idprofessor)
	{
		$result = array();
		$this->sql = 'SELECT * FROM professores_avas WHERE idprofessor = '.$idprofessor;
		$result['avas'] = $this->retornarLinhas($this->sql);

		$this->sql = 'SELECT * FROM professores_cursos WHERE idprofessor = '.$idprofessor;
		$result['cursos'] = $this->retornarLinhas($this->sql);

		$this->sql = 'SELECT * FROM professores_ofertas WHERE idprofessor = '.$idprofessor;
		$result['ofertas'] = $this->retornarLinhas($this->sql);

		return $result;
	}
	
	function BuscarDisciplina() {
		$this->sql = "select
						d.iddisciplina as 'key', d.nome as value
					  from
						disciplinas d
					  where
					     d.nome like '%".$_GET["tag"]."%' AND
						 d.ativo = 'S' AND
						 d.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT pd.iddisciplina FROM professores_disciplinas pd WHERE pd.iddisciplina = d.iddisciplina AND pd.idprofessor = '".$this->id."' AND pd.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";

		$dados = $this->retornarLinhas();
		return json_encode($dados);
	}

	function ListarDisciplinasAss() {
		$this->sql = "SELECT
						 ".$this->campos."
					  FROM
						disciplinas d
						INNER JOIN professores_disciplinas pd ON (d.iddisciplina = pd.iddisciplina)
					  WHERE
						pd.ativo = 'S' and d.ativo = 'S' and d.ativo_painel = 'S' and
						pd.idprofessor = ".intval($this->id);

		$this->groupby = "po.idprofessor_disciplina";
		return $this->retornarLinhas();
	}

	function AssociarDisciplinas($idprofessor, $arrayDisciplinas) {
		foreach($arrayDisciplinas as $ind => $id) {

			  $this->sql = "select count(idprofessor_disciplina) as total, idprofessor_disciplina from professores_disciplinas where idprofessor = '".intval($idprofessor)."' and iddisciplina = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql);
			  if($totalAss["total"] > 0) {
				  $this->sql = "update professores_disciplinas set ativo = 'S' where idprofessor_disciplina = ".$totalAss["idprofessor_disciplina"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idprofessor_disciplina"];
			  } else {
				  $this->sql = "insert into professores_disciplinas set ativo = 'S', data_cad = now(), idprofessor = '".intval($idprofessor)."', iddisciplina = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }
			  
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 197;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}

		}
		return $this->retorno;
	}

	function DesassociarDisciplinas() {

		include_once("../includes/validation.php");
		$regras = array(); // stores the validation rules

		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";

		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update professores_disciplinas set ativo = 'N' where idprofessor_disciplina = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 197;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}

		return $this->retorno;

	}
	
	public function adicionarArquivo()
    {
        $this->return = array();
        if ($_FILES['documento']['error'] === 0) {
            $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/professores_arquivos/' . $this->id;
            $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
            $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
            mkdir($pasta, 0777);
            chmod($pasta, 0777);
            $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
            chmod($pasta . '/' . $nomeServidor, 0777);
            $db = new Zend_Db_Select(new Zend_Db_MySql);
            if ($envio) {
                $insert = $db->insert('professores_arquivos', array(
                    'data_cad' => 'NOW()',
                    'idprofessor' => $this->id,
                    'arquivo_nome' => $db->quote($_FILES["documento"]["name"]),
                    'arquivo_servidor' => $db->quote($nomeServidor),
                    'arquivo_tipo' => $db->quote($_FILES["documento"]["type"]),
                    'arquivo_tamanho' => $db->quote($_FILES["documento"]["size"])
                ));
                $salvar = $this->executaSql((string)$insert);
                if ($salvar) {
					$this->monitora_oque = 1;
					$this->monitora_onde = 212;
					$this->monitora_qual = mysql_insert_id();
					$this->Monitora();
				
                    $this->return["sucesso"] = true;
                    $this->return["mensagem"] = "arquivos_professor_envio_sucesso";
                } else {
                    $this->return["sucesso"] = false;
                    $this->return["mensagem"] = "arquivos_professor_envio_erro";
                }
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_professor_envio_erro";
            }
        } else {
            $this->sql = "insert into
            professores_arquivos
            set
            data_cad = now(),
            idprofessor = " . $this->id . ",
            idtipo = " . $this->post["idtipo"] . ",
            idtipo_associacao = " . $this->post["idtipo_associacao"];
            $salvar = $this->executaSql($this->sql);
            if ($salvar) {
				$this->monitora_oque = 1;
				$this->monitora_onde = 212;
				$this->monitora_qual = mysql_insert_id();
				$this->Monitora();
			
                $this->return["sucesso"] = true;
                $this->return["mensagem"] = "arquivos_professor_envio_sucesso";
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_professor_envio_erro";
            }
        }
        return $this->return;
    }
	
	public function removerArquivo()
    {
        $this->retorno = array();
        $this->sql = "UPDATE professores_arquivos SET ativo ='N' WHERE idarquivo = {$this->idarquivo} AND idprofessor = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
			$this->monitora_oque = 3;
			$this->monitora_onde = 212;
			$this->monitora_qual = $this->idarquivo;
			$this->Monitora();
		
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "arquivo_professor_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivo_professor_remover_erro";
        }
        return $this->retorno;
    }

    public function retornarListaArquivos()
    {
        $this->sql = "SELECT *,idarquivo as iddocumento FROM professores_arquivos md
                    WHERE idprofessor = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }
	
	public function retornarArquivo()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM professores_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idprofessor = " . $this->id;
        return $this->retornarLinha($this->sql);
    }
	public function retornarAvatar(){
		$this->sql = "SELECT avatar_nome,avatar_servidor,avatar_tipo,avatar_tamanho FROM professores WHERE idprofessor = ".$this->id." and ativo = 'S'";
		return $this->retornarLinha($this->sql);
	}
	public function RemoverImgAvatar($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
	}
}