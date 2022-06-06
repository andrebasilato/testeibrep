<?php
class Banners_Ava_Aluno extends Core {

  function ListarTodas() {
	$this->sql = "SELECT ".$this->campos." FROM banners_ava_aluno
                WHERE ativo = 'S'";

	if(is_array($_GET["q"])) {
	  foreach($_GET["q"] as $campo => $valor) {
		//explode = Retira, ou seja retira a "|" da variavel campo
		$campo = explode("|",$campo);
		$valor = str_replace("'","",$valor);
		// Listagem se o valor for diferente de Todos ele faz um filtro
		if(($valor || $valor === "0") and $valor <> "todos") {
		  // se campo[0] for = 1 é pq ele tem de ser um valor exato
		  if($campo[0] == 1) {
			$this->sql .= " and ".$campo[1]." = '".$valor."' ";
			// se campo[0] for = 2, faz o filtro pelo comando like
		  } elseif($campo[0] == 2)  {
			$busca = str_replace("\\'","",$valor);
			$busca = str_replace("\\","",$busca);
			$busca = explode(" ",$busca);
			foreach($busca as $ind => $buscar){
			  $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
			}
		  } elseif($campo[0] == 3)  {
			$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
		  }
		}
	  }
	}
	if($_GET['por_periodo'] == 1) {
		$this->sql .= " and ((periodo_exibicao_de <= '".date("Y-m-d")."' and periodo_exibicao_ate >= '".date("Y-m-d")."') OR (periodo_exibicao_de <= '".date("Y-m-d")."' AND periodo_exibicao_ate IS NULL))";
	}

	$this->groupby = "idbanner";
	return $this->retornarLinhas();
  }

  function Retornar() {
	$this->sql = "select ".$this->campos." from banners_ava_aluno where ativo = 'S' and idbanner = '".$this->id."'";
	return $this->retornarLinha($this->sql);
  }

  function Cadastrar() {
	$dado = $this->SalvarDados();

	if($dado['id']){
		 $this->CadastrarDias($dado['id']);
	}

	return $dado ;
  }

  function Modificar() {
  	$this->config["formulario"][0]["campos"][1]["validacao"] = array("formato_arquivo" => "arquivo_invalido");
	$dado = $this->SalvarDados();
	if($dado['id']){
		 $this->CadastrarDias($dado['id']);
	}
	return $dado;
  }

  function CadastrarDias($id) {
	  $this->sql = "DELETE FROM banners_ava_aluno_dias WHERE idbanner = '" . $id . "' ";
      $this->executaSql($this->sql);

	  if(count($_POST['dias'])){
	  	foreach($_POST['dias'] as $ind => $val){
			$this->sql = "INSERT INTO banners_ava_aluno_dias SET data_cad = now(), idbanner = '" . $id . "', dia = '" . $val . "'";
			$associar = $this->executaSql($this->sql);
	 	 }
	  }

  }

  function Remover() {
	return $this->RemoverDados();
  }

  function RetornarDias() {
		$this->sql = "SELECT
						dia
					  FROM
						banners_ava_aluno_dias
					  WHERE
					  	idbanner =  '".$this->id."' ";

		$this->limite = -1;
        $this->ordem_campo = "dia";
        $this->groupby = "idbanner_dia";
        $dados = $this->retornarLinhas();
		$dias = false;
		if($dados){
			foreach($dados as $ind => $val){
				$dias[$val['dia']]= $val['dia'];
			}
		}
		return $dias;
	}

  function listarTotalBanners() {
		$this->sql = "SELECT
						count(ba.idbanner) AS total
					  FROM
						banners_ava_aluno ba
					  WHERE
					  	ba.ativo = 'S'";

		$dados = $this->retornarLinha($this->sql);
		return $dados['total'];
	}
    function BuscarSindicatos()
    {
        $this->sql = "select
					i.idsindicato as 'key',
					i.nome_abreviado as value
				  from
					sindicatos i
				  where
					i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' AND
					i.ativo = 'S' and
					not exists (
					  select
						bi.idbanner_sindicato
					  from
						banners_sindicatos bi
					  where
						bi.idsindicato = i.idsindicato and
						bi.idbanner = '" . intval($this->id) . "' and
						bi.ativo = 'S'
					)";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

	function AssociarSindicato()
    {
        foreach ($this->post["sindicatos"] as $idsindicato) {
            $this->sql = "SELECT COUNT(idbanner_sindicato) as total, idbanner_sindicato FROM banners_sindicatos WHERE idbanner = '" . $this->id . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "UPDATE banners_sindicatos SET ativo = 'S' WHERE idbanner_sindicato = " . $totalAssociado["idbanner_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idbanner_sindicato"];
            } else {
                $this->sql = "INSERT INTO banners_sindicatos SET ativo = 'S', data_cad = now(), idbanner = '" . $this->id . "', idsindicato = '" . intval($idsindicato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 165;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function DesassociarSindicato()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE banners_sindicatos SET ativo = 'N' WHERE idbanner_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 165;
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

    function ListarSindicatosAssociadas()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					banners_sindicatos bi
					inner join sindicatos i ON (bi.idsindicato = i.idsindicato)
				  WHERE
					i.ativo = 'S' AND
					bi.ativo= 'S' AND
					bi.idbanner = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }

    function BuscarCursos()
    {
        $this->sql = "select
					c.idcurso as 'key',
					c.nome as value
				  from
					cursos c
				  where
					c.nome LIKE '%" . $this->get["tag"] . "%' AND
					c.ativo = 'S' and
					not exists (
					  select
						bc.idbanner_curso
					  from
						banners_cursos bc
					  where
						bc.idcurso = c.idcurso and
						bc.idbanner = '" . intval($this->id) . "' and
						bc.ativo = 'S'
					)";

        $this->limite = -1;
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.idcurso";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function AssociarCurso()
    {
        foreach ($this->post["cursos"] as $idcurso) {
            $this->sql = "SELECT COUNT(idbanner_curso) as total, idbanner_curso FROM banners_cursos WHERE idbanner = '" . $this->id . "' and idcurso = '" . intval($idcurso) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "UPDATE banners_cursos SET ativo = 'S' WHERE idbanner_curso = " . $totalAssociado["idbanner_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idbanner_curso"];
            } else {
                $this->sql = "INSERT INTO banners_cursos SET ativo = 'S', data_cad = now(), idbanner = '" . $this->id . "', idcurso = '" . intval($idcurso) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 165;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function DesassociarCurso()
    {

        include_once("../includes/validation.php");
        $regras = array();

        //VERIFICANDO SE OPÇÃO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÁRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE banners_cursos SET ativo = 'N' WHERE idbanner_curso = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 165;
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

    function ListarCursosAssociados()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					banners_cursos bc
					inner join cursos c ON (bc.idcurso = c.idcurso)
				  WHERE
					c.ativo = 'S' AND
					bc.ativo= 'S' AND
					bc.idbanner = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "c.nome";
        return $this->retornarLinhas();
    }

    function BuscarEscola()
    {
        $this->sql = "SELECT
                            p.idescola AS 'key',
                            p.nome_fantasia AS value
                          FROM
                            escolas p
                          WHERE
                             p.nome_fantasia like '%".$_GET["tag"]."%' AND
                             p.ativo = 'S' AND
                             p.ativo_painel = 'S' AND
                             NOT EXISTS (SELECT
                                                bp.idescola
                                            FROM
                                                banners_escolas bp
                                            WHERE
                                                bp.idescola = p.idescola AND
                                                bp.idbanner = '".$this->id."' AND
                                                bp.ativo = 'S'
                                        )";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function ListarEscolasAssociados()
    {
        $this->sql = "SELECT
                            ".$this->campos."
                        FROM
                            escolas p
                            INNER JOIN banners_escolas bp ON (bp.idescola = p.idescola)
                        WHERE
                            bp.idbanner = '".intval($this->id)."' AND
                            bp.ativo = 'S' AND
                            p.ativo = 'S' AND
                            p.ativo_painel = 'S'";

        $this->groupby = "bp.idbanner_escola";
        return $this->retornarLinhas();
    }

    function AssociarEscolas()
    {
        foreach ($this->post["escolas"] as $ind => $idescola) {
            $this->sql = "SELECT
                                count(idbanner_escola) as total,
                                idbanner_escola
                            FROM
                                banners_escolas
                            WHERE
                                idbanner = '".$this->id."' and
                                idescola = '".intval($idescola)."'";
            $totalAssociado = $this->retornarLinha($this->sql);

            if ($totalAssociado["total"] > 0) {
                $this->sql = "UPDATE
                                    banners_escolas
                                SET
                                    ativo = 'S'
                                WHERE
                                    idbanner_escola = '".$totalAssociado["idbanner_escola"]."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idbanner_escola"];
            } else {
                $this->sql = "INSERT INTO
                                    banners_escolas
                                SET
                                    ativo = 'S',
                                    data_cad = now(),
                                    idbanner = '".$this->id."',
                                    idescola = '".intval($idescola)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 210;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }
        return $this->retorno;
    }

    function DesassociarEscolas()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE banners_escolas SET ativo = 'N' WHERE idbanner_escola = '".intval($this->post["remover"])."'";
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 210;
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

    //Função para retornar os banner para serem exibidos para os alunos
    function retornarBannersAluno() {

		$diasemana = date('N');

        $this->sql = "SELECT
                            DISTINCT(baa.idbanner),
                            baa.cor_background,
                            baa.link,
                            baa.imagem_servidor
                        FROM
                            banners_ava_aluno baa
                            LEFT OUTER JOIN banners_sindicatos bi ON (bi.idbanner = baa.idbanner AND bi.ativo = 'S' AND (SELECT count(idsindicato) FROM sindicatos  WHERE idsindicato = bi.idsindicato AND ativo = 'S') > 0)
                            LEFT OUTER JOIN banners_escolas bp ON (bp.idbanner = baa.idbanner AND bp.ativo = 'S' AND (SELECT count(idescola) FROM escolas  WHERE idescola = bp.idescola AND ativo = 'S') > 0)
                            LEFT OUTER JOIN banners_cursos bc ON (bc.idbanner = baa.idbanner AND bc.ativo = 'S' AND (SELECT count(idcurso) FROM cursos  WHERE idcurso = bc.idcurso AND ativo = 'S') > 0)
                        WHERE
                            baa.painel_aluno = 'S'
                            AND baa.ativo = 'S'
                            AND baa.ativo_painel = 'S'
							AND(
								  ( DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
								  	DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d')
								  )OR(
								   	DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_ate IS NULL
								  )OR(
									DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_de IS NULL
								  )OR(
								    baa.periodo_exibicao_ate IS NULL AND
								    baa.periodo_exibicao_de IS NULL
								  )
							)AND (
								 (
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
							 	  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')
								 )OR(
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
								  baa.hora_ate IS NULL
								 )OR(
								  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')   AND
								  baa.hora_de IS NULL
								 )OR(
								  baa.hora_de IS NULL AND
								  baa.hora_ate IS NULL
								 )
							)
							AND (
								NOT EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner LIMIT 1
								)OR EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner and dia = ".$diasemana." LIMIT 1
								)
							)
                            AND
                            (
                                (SELECT
                                        count(m.idmatricula)
                                    FROM
                                        matriculas m
                                    WHERE
                                        m.idpessoa = '".$this->idpessoa."' AND
                                        m.ativo = 'S' AND
                                        m.idsindicato = bi.idsindicato
                                ) > 0 OR
                                bi.idsindicato IS NULL
                            )
                            AND
                            (
                                (SELECT
                                        count(m.idmatricula)
                                    FROM
                                        matriculas m
                                    WHERE
                                        m.idpessoa = '".$this->idpessoa."' AND
                                        m.ativo = 'S' AND
                                        m.idescola = bp.idescola
                                ) > 0 OR
                                bp.idescola IS NULL
                            )
                            AND
                            (
                                (SELECT
                                        count(m.idmatricula)
                                    FROM
                                        matriculas m
                                    WHERE
                                        m.idpessoa = '".$this->idpessoa."' AND
                                        m.ativo = 'S' AND
                                        m.idcurso = bc.idcurso
                                ) > 0 OR
                                bc.idcurso IS NULL
                            )
                            ORDER BY RAND()";

        $this->limite = 1;
		$this->ordem = '  ';
        return $this->retornarLinhas();
    }

    //Função para retornar os banner para serem exibidos para os atendentes
    function retornarBannersAtendente() {

        $diasemana = date('N');

        $this->sql = "SELECT
                            DISTINCT(baa.idbanner),
                            baa.cor_background,
                            baa.link,
                            baa.imagem_servidor
                        FROM
                            banners_ava_aluno baa
                            LEFT OUTER JOIN banners_sindicatos bi ON (bi.idbanner = baa.idbanner AND bi.ativo = 'S' AND (SELECT count(idsindicato) FROM sindicatos  WHERE idsindicato = bi.idsindicato AND ativo = 'S') > 0)
                            LEFT OUTER JOIN banners_escolas bp ON (bp.idbanner = baa.idbanner AND bp.ativo = 'S' AND (SELECT count(idescola) FROM escolas  WHERE idescola = bp.idescola AND ativo = 'S') > 0)
                        WHERE
                            baa.painel_atendente = 'S'
                            AND baa.ativo = 'S'
                            AND baa.ativo_painel = 'S'
							AND(
								  ( DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
								  	DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d')
								  )OR(
								   	DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_ate IS NULL
								  )OR(
									DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_de IS NULL
								  )OR(
								    baa.periodo_exibicao_ate IS NULL AND
								    baa.periodo_exibicao_de IS NULL
								  )
							)AND (
								 (
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
							 	  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')
								 )OR(
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
								  baa.hora_ate IS NULL
								 )OR(
								  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')   AND
								  baa.hora_de IS NULL
								 )OR(
								  baa.hora_de IS NULL AND
								  baa.hora_ate IS NULL
								 )
							)
							AND (
								NOT EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner LIMIT 1
								)OR EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner and dia = ".$diasemana." LIMIT 1
								)
							)
                            AND
                            (
                                (SELECT
                                        count(vs.idvendedor)
                                    FROM
                                        vendedores_sindicatos vs
                                    WHERE
                                        vs.idvendedor = '".$this->idvendedor."' AND
                                        vs.ativo = 'S' AND
                                        vs.idsindicato = bi.idsindicato
                                ) > 0 OR
                                bi.idsindicato IS NULL
                            )
                            AND
                            (
                                (SELECT
                                        count(ve.idvendedor)
                                    FROM
                                        vendedores_escolas ve
                                    WHERE
                                        ve.idvendedor = '".$this->idvendedor."' AND
                                        ve.ativo = 'S' AND
                                        ve.idescola = bp.idescola
                                ) > 0 OR
                                bp.idescola IS NULL
                            )
                            ORDER BY RAND()";

        $this->limite = 1;
        $this->ordem = '  ';
        return $this->retornarLinhas();
    }

    //Função para retornar os banner para serem exibidos para os CFCs/Escolas
    function retornarBannersCFC() {

        $diasemana = date('N');

        $this->sql = "SELECT
                            DISTINCT(baa.idbanner),
                            baa.cor_background,
                            baa.link,
                            baa.imagem_servidor
                        FROM
                            banners_ava_aluno baa
                            LEFT OUTER JOIN banners_sindicatos bi ON (bi.idbanner = baa.idbanner AND bi.ativo = 'S' AND (SELECT count(idsindicato) FROM sindicatos  WHERE idsindicato = bi.idsindicato AND ativo = 'S') > 0)
                            LEFT OUTER JOIN banners_escolas bp ON (bp.idbanner = baa.idbanner AND bp.ativo = 'S' AND (SELECT count(idescola) FROM escolas  WHERE idescola = bp.idescola AND ativo = 'S') > 0)
                        WHERE
                            baa.painel_cfc = 'S'
                            AND baa.ativo = 'S'
                            AND baa.ativo_painel = 'S'
							AND(
								  ( DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
								  	DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d')
								  )OR(
								   	DATE_FORMAT(baa.periodo_exibicao_de,'%Y-%m-%d') <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_ate IS NULL
								  )OR(
									DATE_FORMAT(baa.periodo_exibicao_ate,'%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
									baa.periodo_exibicao_de IS NULL
								  )OR(
								    baa.periodo_exibicao_ate IS NULL AND
								    baa.periodo_exibicao_de IS NULL
								  )
							)AND (
								 (
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
							 	  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')
								 )OR(
								  DATE_FORMAT(baa.hora_de,'%H:%i:%s') <= DATE_FORMAT(NOW(),'%H:%i:%s') AND
								  baa.hora_ate IS NULL
								 )OR(
								  DATE_FORMAT(baa.hora_ate,'%H:%i:%s') >= DATE_FORMAT(NOW(),'%H:%i:%s')   AND
								  baa.hora_de IS NULL
								 )OR(
								  baa.hora_de IS NULL AND
								  baa.hora_ate IS NULL
								 )
							)
							AND (
								NOT EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner LIMIT 1
								)OR EXISTS(
										SELECT dia FROM banners_ava_aluno_dias WHERE idbanner  = baa.idbanner and dia = ".$diasemana." LIMIT 1
								)
							)
                            AND
                            (
                                (SELECT
                                        count(e.idescola)
                                    FROM
                                        escolas e
                                    WHERE
                                        e.idescola = '".$this->idescola."' AND
                                        e.ativo = 'S' AND
                                        e.idsindicato = bi.idsindicato
                                ) > 0 OR
                                bi.idsindicato IS NULL
                            )
                            AND
                            (
                                (SELECT
                                        count(e.idescola)
                                    FROM
                                        escolas e
                                    WHERE
                                        e.idescola = '".$this->idescola."' AND
                                        e.ativo = 'S' AND
                                        e.idescola = bp.idescola
                                ) > 0 OR
                                bp.idescola IS NULL
                            )
                            ORDER BY RAND()
                            ";

        $this->limite = 1;
        $this->ordem = '  ';
        return $this->retornarLinhas();
    }

function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
  }

}

?>
