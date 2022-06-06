<?php
class Curriculos extends Core {

  function ListarTodas() {
	$this->sql = "select
					".$this->campos."
				  from
					curriculos ca
					inner join cursos c on (ca.idcurso = c.idcurso)
				  where
					ca.ativo = 'S'";


	$this->aplicarFiltrosBasicos();
	$this->groupby = "ca.idcurriculo";
	return $this->retornarLinhas();
  }


  function Retornar() {
	$this->sql = "select
					".$this->campos."
				  from
					curriculos ca
					inner join cursos c on (ca.idcurso = c.idcurso)
				  where
					ca.ativo = 'S' and
					ca.idcurriculo = '".$this->id."'";
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

  function ListarTodasAvaliacoes() {
	$this->sql = "select
					".$this->campos."
				  from
					curriculos_avaliacoes
				  where
					ativo = 'S' and
					idcurriculo = '".$this->id."'";

	return $this->retornarLinhas();
  }

  function CadastrarAvaliacao() {
	if(!$this->post["avaliacao"]) {
	  $erros[] = "avaliacao_vazio";
	}

	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {

	  $this->sql = "insert into
					  curriculos_avaliacoes
					set
					  data_cad = now(),
					  idcurriculo = '".$this->id."',
					  avaliacao = ".$this->post["avaliacao"];
	  if($this->executaSql($this->sql)){
		$this->monitora_qual = mysql_insert_id();
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 76;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

  function RemoverAvaliacao() {
	include_once("../includes/validation.php");
	$regras = array(); // stores the validation rules

	//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";

	//VALIDANDO FORMULARIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update curriculos_avaliacoes set ativo = 'N' where idavaliacao = ".intval($this->post["remover"]);
	  if($this->executaSql($this->sql)){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 76;
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

  function ListarTodasBlocos() {
	$this->sql = "select
					".$this->campos."
				  from
					curriculos_blocos
				  where
					ativo = 'S' and
					idcurriculo = '".$this->id."'";

	return $this->retornarLinhas();
  }

  function CadastrarBloco() {
	if(!$this->post["ordem"]) {
	  $erros[] = "ordem_vazio";
	}
	if(!$this->post["nome"]) {
	  $erros[] = "nome_vazio";
	}

	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "insert into
					  curriculos_blocos
					set
					  data_cad = now(),
					  idcurriculo = '".$this->id."',
					  nome = '".$this->post["nome"]."',
					  ordem = ".$this->post["ordem"]."";
	  if($this->executaSql($this->sql)){
		$this->monitora_qual = mysql_insert_id();
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 77;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

  function ModificarBlocos() {

	foreach($this->post["blocos"] as $idbloco => $post) {
	  if(!$post["ordem"]) {
		$erros[] = "ordem_vazio";
	  }

	  $this->sql = "select * from curriculos_blocos where idcurriculo = '".$this->id."' and idbloco = ".intval($idbloco);
	  $linhaAntiga = $this->retornarLinha($this->sql);

	  $this->sql = "update
					  curriculos_blocos
					set
					  ordem = '".$post["ordem"]."'
					where
					  idcurriculo = '".$this->id."' and
					  idbloco = ".intval($idbloco);
	  $executa = $this->executaSql($this->sql);

	  $this->sql = "select * from curriculos_blocos where idcurriculo = '".$this->id."' and idbloco = ".intval($idbloco);
	  $linhaNova = $this->retornarLinha($this->sql);

	  if($executa){
		$this->monitora_oque = 2;
		$this->monitora_onde = 77;
		$this->monitora_qual = $idbloco;
		$this->monitora_dadosantigos = $linhaAntiga;
		$this->monitora_dadosnovos = $linhaNova;
		$this->Monitora();

		$this->retorno["sucesso"] = true;
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

  function RemoverBloco() {
	include_once("../includes/validation.php");
	$regras = array(); // stores the validation rules

	//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";

	//VALIDANDO FORMULARIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update curriculos_blocos set ativo = 'N' where idbloco = ".intval($this->post["remover"]);
	  if($this->executaSql($this->sql)){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 77;
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

  function ListarTodasDisciplinas($idbloco = NULL) {

	$this->sql = "select
					".$this->campos."
				  from
					curriculos_blocos_disciplinas cbd
					inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
					inner join disciplinas d on (cbd.iddisciplina = d.iddisciplina)
					left outer join avas a on (cbd.idava = a.idava)
				  where
					cbd.ativo = 'S' and
					cb.idcurriculo = '".$this->id."'";

	if($idbloco) { $this->sql .= " and cb.idbloco = '".$idbloco."'"; }

	return $this->retornarLinhas();
  }

  function CadastrarDisciplina() {
	if(!$this->post["ordem"]) {
	  $erros[] = "ordem_vazio";
	}
	if(!$this->post["iddisciplina"]) {
	  $erros[] = "iddisciplina_vazio";
	}
	if(!$this->post["idbloco"]) {
	  $erros[] = "idbloco_vazio";
	}

	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "insert into
					  curriculos_blocos_disciplinas
					set
					  data_cad = now(),
					  idbloco = '".$this->post["idbloco"]."',
					  iddisciplina = '".$this->post["iddisciplina"]."',
					  ordem = ".$this->post["ordem"]."";
	  if($this->executaSql($this->sql)){
		$this->monitora_qual = mysql_insert_id();
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 78;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

 	function ModificarDisciplinas()
 	{
		foreach($this->post['disciplinas'] as $idbloco_disciplina => $post) {
			/*if(!$post['idava']) {
				$post['idava'] = 'null';
			}*/
			if (!$post['ignorar_historico']) {
				$post['ignorar_historico'] = 'N';
			} else {
				$post['ignorar_historico'] = 'S';
			}

			if (!$post['contabilizar_media']) {
				$post['contabilizar_media'] = 'N';
			} else {
				$post['contabilizar_media'] = 'S';
			}

			if (!$post['exibir_aptidao']) {
				$post['exibir_aptidao'] = 'N';
			} else {
				$post['exibir_aptidao'] = 'S';
			}

			if (!$post['nota_conceito']) {
				$post['nota_conceito'] = 'N';
			} else {
				$post['nota_conceito'] = 'S';
			}

			/*if(!$post["peso_presencial_1"]) {
			$post["peso_presencial_1"] = "null";
			} else {
			$post["peso_presencial_1"] = str_replace('.','',$post["peso_presencial_1"]);
			$post["peso_presencial_1"] = str_replace(',','.',$post["peso_presencial_1"]);
			}
			if(!$post["peso_presencial_2"]) {
			$post["peso_presencial_2"] = "null";
			} else {
			$post["peso_presencial_2"] = str_replace('.','',$post["peso_presencial_2"]);
			$post["peso_presencial_2"] = str_replace(',','.',$post["peso_presencial_2"]);
			}
			if(!$post["peso_virtual_1"]) {
			$post["peso_virtual_1"] = "null";
			} else {
			$post["peso_virtual_1"] = str_replace('.','',$post["peso_virtual_1"]);
			$post["peso_virtual_1"] = str_replace(',','.',$post["peso_virtual_1"]);
			}
			if(!$post["peso_virtual_2"]) {
			$post["peso_virtual_2"] = "null";
			} else {
			$post["peso_virtual_2"] = str_replace('.','',$post["peso_virtual_2"]);
			$post["peso_virtual_2"] = str_replace(',','.',$post["peso_virtual_2"]);
			}*/

			$this->sql = "select * from curriculos_blocos_disciplinas where idbloco_disciplina = ".intval($idbloco_disciplina);
			$linhaAntiga = $this->retornarLinha($this->sql);

	  		$this->sql = "UPDATE
						  		curriculos_blocos_disciplinas
							SET
								ordem = '".$post["ordem"]."',
								horas = '".$post["horas"]."',
								idformula = ".$post["idformula"].",
								ignorar_historico = '".$post['ignorar_historico']."',
								contabilizar_media = '".$post['contabilizar_media']."',
								exibir_aptidao = '".$post['exibir_aptidao']."',
								nota_conceito = '".$post['nota_conceito']."'
							WHERE
						  		idbloco_disciplina = ".intval($idbloco_disciplina);
							/*
							  peso_presencial_1 = ".$post["peso_presencial_1"].",
							  peso_presencial_2 = ".$post["peso_presencial_2"].",
							  peso_virtual_1 = ".$post["peso_virtual_1"].",
							  peso_virtual_2 = ".$post["peso_virtual_2"].",
							*/
	  		$executa = $this->executaSql($this->sql);

			$this->sql = "select * from curriculos_blocos_disciplinas where idbloco_disciplina = ".intval($idbloco_disciplina);
			$linhaNova = $this->retornarLinha($this->sql);

			if($executa){
				$this->monitora_oque = 2;
				$this->monitora_onde = 78;
				$this->monitora_qual = $idbloco_disciplina;
				$this->monitora_dadosantigos = $linhaAntiga;
				$this->monitora_dadosnovos = $linhaNova;
				$this->Monitora();

				$this->retorno["sucesso"] = true;
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}

		return $this->retorno;
	}

  function RemoverDisciplina() {
	include_once("../includes/validation.php");
	$regras = array(); // stores the validation rules

	//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";

	//VALIDANDO FORMULARIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update curriculos_blocos_disciplinas set ativo = 'N' where idbloco_disciplina = ".intval($this->post["remover"]);
	  if($this->executaSql($this->sql)){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 78;
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

  function enviarArquivosCursos($idcurriculo, $arquivos, $erros = NULL) {
		if(!$this->post["titulo"]) {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = "titulo_vazio";
			return $this->retorno;
		}

		$permissoes = 'jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
		$campo = array("pasta" => "curriculos_arquivos");

		$existe_arquivos = false;
		foreach ($arquivos['arquivos']['name'] as $ind => $arq)
			if ($arq)
				$existe_arquivos = true;

		if ($existe_arquivos) {
			//VALIDA
			/*include_once("../includes/validation.php");
			$regras = array();
			$regras[] = "formato_arquivo,name,$permissoes,'',arquivo_invalido";*/

			foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
				$file['name'] = $arquivos['arquivos']['name'][$ind];
				$file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
				$file['size'] = $arquivos['arquivos']['size'][$ind];

				unset($nome_servidor);

				$file_aux['name'] = $file;
				$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
					if($validacao_tamanho) {
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = $validacao_tamanho;
						return $this->retorno;
					}
			}
			//INSERE
			foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
				$file['name'] = $arquivos['arquivos']['name'][$ind];
				$file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
				$file['size'] = $arquivos['arquivo']['size'][$ind];

				unset($nome_servidor);

				$file_aux['name'] = $file;
				$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
				if($validacao_tamanho) {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $validacao_tamanho;
					return $this->retorno;
				}
				$nome_servidor = $this->uploadFile($file, $campo);

				if($nome_servidor) {

					if($this->post["descricao"]) {
					  $this->post["descricao"] = "'".$this->post["descricao"]."'";
					} else {
					  $this->post["descricao"] = "NULL";
					}

					$sql = "insert into curriculos_arquivos set
						  idcurriculo = '".$idcurriculo."',
						  ativo = 'S',
						  data_cad = NOW(),
						  nome = '".$arquivos['arquivos']['name'][$ind]."',
						  tipo = '".$arquivos['arquivos']['type'][$ind]."',
						  tamanho = '".$arquivos['arquivos']['size'][$ind]."',
						  servidor = '".$nome_servidor."',
						  titulo = '".$this->post["titulo"]."',
						  descricao = ".$this->post["descricao"];
					$query_arquivo = $this->executaSql($sql);
					$idarquivo = mysql_insert_id();
					if (!$query_arquivo) {
						$erro = true;
					} else {
						$this->retorno["sucesso"] = true;
						$this->monitora_onde = 115;
						$this->monitora_oque = 1;
						$this->monitora_qual = $idarquivo;
						$this->Monitora();
					}
				}
			}
			//

		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'sem_arquivos';
		}
		return $this->retorno;

	}

	function totalArquivosCursos($idcurriculo) {
		$this->sql = "SELECT count(*) as total FROM curriculos_arquivos where ativo = 'S' AND ativo_painel = 'S' AND idcurriculo = ".$idcurriculo;
		$retorno = $this->retornarLinha($this->sql);
		return $retorno["total"];
	}

	function retornaArquivosCursos($idcurriculo) {
		$this->sql = "SELECT * FROM curriculos_arquivos where ativo = 'S' AND idcurriculo = ".$idcurriculo;
		$this->limite = -1;
		$this->ordem = "asc";
		$this->ordem_campo = "idarquivo";
		$this->groupby = "idarquivo";
		$dados = $this->retornarLinhas();
		return $dados;
	}

	function removerArquivosCursos($idarquivo, $idcurriculo) {
		$this->sql = "UPDATE curriculos_arquivos SET ativo='N' where idarquivo = ".$idarquivo." and idcurriculo = ".$idcurriculo;
		$dados = $this->executaSql($this->sql);

		if ($dados) {
			$this->retorno["sucesso"] = true;
			$this->monitora_onde = 115;
			$this->monitora_oque = 3;
			$this->monitora_qual = $idarquivo;
			$this->Monitora();
		}

		return $this->retorno;
	}

	function retornaArquivosCursosDownload($idcurriculo, $idarquivo) {
		$this->sql = "SELECT * FROM curriculos_arquivos
					  where
					  	idarquivo = ".$idarquivo." and
						idcurriculo = ".$idcurriculo;
		$retorno = $this->retornarLinha($this->sql);

		return $retorno;
	}

	function uploadFile($file, $campoAux){
		$extensao = strtolower(strrchr($file["name"], "."));
		$nome_servidor = date("YmdHis")."_".uniqid().$extensao;

		if(move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
			return $nome_servidor;
		} else
			return false;
	}

	function ativarDesativarArquivosCursos($idcurriculo, $idarquivo) {

		$info = array();
		$this->sql = "select * from curriculos_arquivos where idcurriculo = ".intval($idcurriculo)." and idarquivo = ".intval($idarquivo);
		$info['sql1'] = $this->sql;
		$linhaAntiga = $this->retornarLinha($this->sql);

		if($linhaAntiga["ativo_painel"] == "S"){
		   $ativo_painel = "N";
		} else {
		  $ativo_painel = "S";
		}

		$this->sql = "update curriculos_arquivos set ativo_painel = '".$ativo_painel."' where idcurriculo = ".intval($idcurriculo)." and idarquivo = ".intval($idarquivo);
		$info['sql2'] = $this->sql;
		$executa = mysql_query($this->sql) or die($info['erro'] = mysql_error());

		$this->sql = "select * from curriculos_arquivos where idcurriculo = ".intval($idcurriculo)." and idarquivo = ".intval($idarquivo);
		$info['sql3'] = $this->sql;
		$linhaNova = $this->retornarLinha($this->sql);

		if($executa){
		   $info['sucesso'] = true;
		   $info['ativo'] = $linhaNova["ativo_painel"];
		   $info['arquivo'] = $linhaNova["idarquivo"];
		} else {
		   $info['sucesso'] = false;
		   $info['ativo'] = $ativo_painel;
		   $info['arquivo'] = $linhaNova["idarquivo"];
		}

		return json_encode($info);
	}

  function AssociarTipoNota() {
	foreach($this->post["tipos_notas"] as $idtipo) {
	  $this->sql = "select count(idcurriculo_tipo) as total, idcurriculo_tipo from curriculos_notas_tipos where idcurriculo = '".$this->id."' and idtipo = '".intval($idtipo)."'";
	  $totalAssociado = $this->retornarLinha($this->sql);
	  if($totalAssociado["total"] > 0) {
		$this->sql = "update curriculos_notas_tipos set ativo = 'S' where idcurriculo_tipo = ".$totalAssociado["idcurriculo_tipo"];
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = $totalAssociado["idcurriculo_tipo"];
	  } else {
		$this->sql = "insert into curriculos_notas_tipos set ativo = 'S', data_cad = now(), idcurriculo = '".$this->id."', idtipo = '".intval($idtipo)."'";
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($associar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 205;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }


  function DesassociarTipoNota() {

	include_once("../includes/validation.php");
	$regras = array(); // stores the validation rules

	//VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";

	//VALIDANDO FORMULÃRIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update curriculos_notas_tipos set ativo = 'N' where idcurriculo_tipo = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 205;
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

  function ListarTiposNotasAssociados() {
	$this->sql = "select
					".$this->campos."
				  from
					curriculos_notas_tipos cnt
					inner join matriculas_notas_tipos mnt ON (cnt.idtipo = mnt.idtipo)
				  where
					mnt.ativo = 'S' and
					cnt.ativo= 'S' and
					cnt.idcurriculo = ".intval($this->id);

	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "mnt.nome";
	return $this->retornarLinhas();
  }

  function BuscarTiposNotas() {
	$this->sql = "select
					mnt.idtipo as 'key',
					mnt.nome as value
				  from
					matriculas_notas_tipos mnt
				  where
					mnt.nome LIKE '%".$this->get["tag"]."%' AND
					mnt.ativo = 'S' and
					mnt.ativo_painel = 'S' and
					not exists (
					  select
						cnt.idcurriculo
					  from
						curriculos_notas_tipos cnt
					  where
						cnt.idtipo = mnt.idtipo and
						cnt.idcurriculo = '".intval($this->id)."' and
						cnt.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "mnt.nome";
	$this->groupby = "mnt.idtipo";
	$this->retorno = $this->retornarLinhas();

	return json_encode($this->retorno);
  }

}
?>