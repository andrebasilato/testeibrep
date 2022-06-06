<?php
/**
 * Class VisitasVendedores
 */
class VisitasVendedores extends Core
{
    public $query = false;
    public $linha = array();
    public $salvar = null;
    public $linha_existente = null;
    public $idpdv = null;
    public $idvendedor = null;

    public function listarTodas()
    {

        $this->sql = "SELECT
						" . $this->campos . "
					FROM
						visitas_vendedores vv
						INNER JOIN vendedores v ON (vv.idvendedor = v.idvendedor)
						LEFT JOIN cidades as c ON (c.idcidade = vv.idcidade)
						LEFT JOIN estados as e ON (e.idestado = vv.idestado)
						LEFT JOIN midias_visitas mid_v ON (vv.idmidia = mid_v.idmidia)
						LEFT JOIN pessoas pe ON (vv.idpessoa = pe.idpessoa)
					 where
						 vv.ativo = 'S'"; //LEFT JOIN cursos c ON (vv.idcurso=c.idcurso)

        if ($this->idusuario && $_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and (
									(	select count(1)
										from vendedores_sindicatos vi
										where v.idvendedor = vi.idvendedor and vi.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") and vi.ativo = 'S'
									) > 0
								)	";

        else if ($this->idvendedor)
            $this->sql .= " AND vv.idvendedor = " . $this->idvendedor;
		if($_GET["q"]["2|vv.nome"]){
		 $this->sql .= " AND (vv.nome like '%" . urldecode($_GET["q"]["2|vv.nome"]) . "%' OR pe.nome like '%" . urldecode($_GET["q"]["2|vv.nome"]) . "%')";
		}
        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            if($campo[1] != "vv.nome"){
								$this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
							}
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(vv." . $campo[1] . ", '%d/%m/%Y') like '" . $valor . "%' ";
                    } elseif ($campo[0] == 4) {
                        $this->sql .= " and (pe.nome like '%" . urldecode($valor) . "%' or vv.nome like '%" . urldecode($valor) . "%')";
                    } elseif ($campo[0] == 5) {
                        $this->sql .= " and (pe.documento like '%" . urldecode($valor) . "%' or vv.documento like '%" . urldecode($valor) . "%')";
                    }
                }
            }
        }

        $this->groupby = "vv.idvisita";
        $visitas = $this->retornarLinhas();

        return $visitas;
    }


    public function Retornar()
    {
        $this->sql = "SELECT " . $this->campos . "
							FROM visitas_vendedores vv
							 INNER JOIN vendedores v ON (vv.idvendedor=v.idvendedor)
							 LEFT JOIN midias_visitas mid_v ON (vv.idmidia=mid_v.idmidia)
							 LEFT JOIN pessoas pe ON (vv.idpessoa=pe.idpessoa)
							 LEFT JOIN cidades c ON (vv.idcidade=c.idcidade)
							 LEFT JOIN estados e ON (vv.idestado=e.idestado)
							WHERE vv.ativo='S' AND vv.idvisita='" . $this->id . "' "; //LEFT JOIN cursos c ON (vv.idcurso=c.idcurso)

        if ($this->idusuario && $_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and (
									(	select count(1)
										from vendedores_sindicatos vi
										where v.idvendedor = vi.idvendedor and vi.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") and vi.ativo = 'S'
									) > 0
								)	";


        else if ($this->idvendedor) $this->sql .= " AND vv.idvendedor = " . $this->idvendedor;

        $this->sql .= " group by vv.idvisita ";
        return $this->retornarLinha($this->sql);
    }

    public function Cadastrar()
    {
        $erros = $this->BuscarErros();

        if ($this->post['geolocalizacao_cidade']) {
            $sql = 'SELECT idcidade FROM cidades
					WHERE nome = "' . $this->post['geolocalizacao_cidade'] . '" ';
            $cidade = $this->retornarLinha($sql);
        }

        if ($this->post['geolocalizacao_estado']) {
            $sql = 'SELECT idestado FROM estados
					WHERE sigla = "' . $this->post['geolocalizacao_estado'] . '"
					OR  nome ="' . $this->post['geolocalizacao_estado'] . '"';
            $estado = $this->retornarLinha($sql);
        }

        if ($erros) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "SELECT idpessoa FROM pessoas WHERE email = '" . $this->post["email"] . "' AND ativo = 'S'";
            $this->linha = $this->retornarLinha($this->sql);

            if ($this->linha) {

                $idvendedor = ($this->post["idvendedor"]) ? $this->post["idvendedor"] : $this->idvendedor;

                $this->sql = "SELECT
                                    idvisita
                                FROM
                                    visitas_vendedores
    							WHERE
                                    idpessoa = '" . $this->linha["idpessoa"] . "' AND
                                    idvendedor = '" . $idvendedor . "' AND
                                    ativo = 'S' ";

                if ($this->post['idcurso'])
                    $this->sql .= " AND (idcurso = '" . $this->post["idcurso"] . "' OR idcurso IS NULL) ";

                $this->linha_existente = $this->retornarLinha($this->sql);

                if (!$this->linha_existente) {

                    $this->sql = "INSERT INTO visitas_vendedores SET
					                    ativo = 'S', data_cad = NOW(),
									idpessoa = '" . $this->linha["idpessoa"] . "'	";
                    /*if($this->post["idcurso"])
                        $this->sql .= ", idcurso = '".$this->post["idcurso"]."' ";*/
                    if ($this->post["geolocation"])
                        $this->sql .= ", geolocation = '" . $this->post["geolocation"] . "' ";
                    if ($this->post["geolocalizacao_endereco"])
                        $this->sql .= ", geolocalizacao_endereco = '" . $this->post["geolocalizacao_endereco"] . "' ";
                    if ($this->post["geolocalizacao_cep"])
                        $this->sql .= ", geolocalizacao_cep = '" . $this->post["geolocalizacao_cep"] . "' ";
                    if ($this->post["observacoes"])
                        $this->sql .= ", observacoes = '" . $this->post["observacoes"] . "' ";
                    if ($cidade["idcidade"])
                        $this->sql .= ", idcidade = '" . $cidade["idcidade"] . "' ";
                    if ($estado["idestado"])
                        $this->sql .= ", idestado = '" . $estado["idestado"] . "' ";
                    if ($this->post["telefone"])
                        $this->sql .= ", telefone = '" . $this->post["telefone"] . "' ";
                    if ($this->post["idmidia"])
                        $this->sql .= ", idmidia = '" . $this->post["idmidia"] . "' ";
                    if ($this->post["idlocal"])
                        $this->sql .= ", idlocal = '" . $this->post["idlocal"] . "' ";
                    if ($this->post["data_nasc"])
                        $this->sql .= ", data_nasc = '" . formataData($this->post["data_nasc"], 'en', 0) . "' ";
                    if ($this->post["idvendedor"])
                        $this->sql .= ", idvendedor = '" . $this->post["idvendedor"] . "' ";
                    if ($this->post["celular"])
                        $this->sql .= ", celular = '" . $this->post["celular"] . "' ";
                    if ($this->post["email_secundario"])
                        $this->sql .= ", email_secundario = '" . $this->post["email_secundario"] . "' ";
                    if ($this->post["motivo_visita"])
                        $this->sql .= ", motivo_visita = '" . $this->post["motivo_visita"] . "' ";

                    if ($this->idusuario)
                        $this->sql .= ", idusuario = '" . $this->idusuario . "' ";
                    elseif ($this->idvendedor)
                        $this->sql .= ", idvendedor = '" . $this->idvendedor . "' ";

                    $this->salvar = $this->executaSql($this->sql);
                    $idvisita_cadastrada = mysql_insert_id();

                    if ($this->salvar) {

                        if ($this->post['cursos']) {
                            foreach ($this->post['cursos'] as $linha) {
                                $this->sql = "INSERT INTO visitas_vendedores_cursos SET idcurso = '" . $linha . "',
																					idvisita = '" . $idvisita_cadastrada . "',
																					data_cad = NOW()	";
                                $salvar = $this->executaSql($this->sql);
                                if (!$salvar) {
                                    $this->retorno["erro"] = true;
                                    $this->retorno["erros"][] = 'erro_atualizar_vendedores_cursos';
                                    return $this->retorno;
                                } else {
                                    $this->retorno["sucesso"] = true;
                                    $this->monitora_oque = 1;
                                    $this->monitora_onde = 151;
                                    $this->monitora_qual = mysql_insert_id();
                                    $this->Monitora();
                                }
                            }
                        }

                        $this->retorno["sucesso"] = true;
                        $this->monitora_oque = 1;
                        $this->monitora_onde = 81;
                        $this->monitora_qual = $idvisita_cadastrada;
                        $this->retorno["id"] = $this->monitora_qual;
                        $this->Monitora();
                    } else {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $this->sql;
                        $this->retorno["erros"][] = mysql_error();
                    }
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = "visita_cadastrada";
                    $this->retorno["erros"][] = $this->linha_existente["idvisita"];
                }


            } else {

                $idvendedor = ($this->post["idvendedor"]) ? $this->post["idvendedor"] : $this->idvendedor;

                $this->sql = "SELECT
                                    idvisita
                                FROM
                                    visitas_vendedores
				                WHERE
                                    email = '" . $this->post["email"] . "' AND
                                    idvendedor = '" . $idvendedor . "' AND
                                    ativo = 'S'";

                if ($this->post['idcurso'])
                    $this->sql .= " AND (idcurso = '" . $this->post["idcurso"] . "' OR idcurso IS NULL) ";

                $this->linha_existente = $this->retornarLinha($this->sql);

                if (!$this->linha_existente) {

                    $this->sql = "insert into visitas_vendedores set
								ativo = 'S', data_cad = NOW(),
								nome = '" . $this->post["nome"] . "',
								email = '" . $this->post["email"] . "'	";
                    if ($this->post["geolocation"])
                        $this->sql .= ", geolocation = '" . $this->post["geolocation"] . "' ";
                    if ($this->post["geolocalizacao_endereco"])
                        $this->sql .= ", geolocalizacao_endereco = '" . $this->post["geolocalizacao_endereco"] . "' ";
                    if ($this->post["geolocalizacao_cep"])
                        $this->sql .= ", geolocalizacao_cep = '" . $this->post["geolocalizacao_cep"] . "' ";
                    if ($cidade["idcidade"])
                        $this->sql .= ", idcidade = '" . $cidade["idcidade"] . "' ";
                    if ($estado["idestado"])
                        $this->sql .= ", idestado = '" . $estado["idestado"] . "' ";
                    if ($this->post["telefone"])
                        $this->sql .= ", telefone = '" . $this->post["telefone"] . "' ";
                    if ($this->post["idmidia"])
                        $this->sql .= ", idmidia = '" . $this->post["idmidia"] . "' ";
                    if ($this->post["idlocal"])
                        $this->sql .= ", idlocal = '" . $this->post["idlocal"] . "' ";
                    if ($this->post["data_nasc"])
                        $this->sql .= ", data_nasc = '" . formataData($this->post["data_nasc"], 'en', 0) . "' ";
                    if ($this->post["idvendedor"])
                        $this->sql .= ", idvendedor = '" . $this->post["idvendedor"] . "' ";
					if ($this->post["idmotivo"])
                        $this->sql .= ", idmotivo = '" . $this->post["idmotivo"] . "' ";
                    if ($this->post["celular"])
                        $this->sql .= ", celular = '" . $this->post["celular"] . "' ";
                    if ($this->post["email_secundario"])
                        $this->sql .= ", email_secundario = '" . $this->post["email_secundario"] . "' ";
                    if ($this->post["motivo_visita"])
                        $this->sql .= ", motivo_visita = '" . $this->post["motivo_visita"] . "' ";
                    if ($this->post["observacoes"])
                        $this->sql .= ", observacoes = '" . $this->post["observacoes"] . "' ";


                    if ($this->idusuario)
                        $this->sql .= ", idusuario = '" . $this->idusuario . "' ";
                    elseif ($this->idvendedor)
                        $this->sql .= ", idvendedor = '" . $this->idvendedor . "' ";

                    $this->salvar = $this->executaSql($this->sql);
                    $idvisita_cadastrada = mysql_insert_id();

                    if ($this->salvar) {

                        if ($this->post['cursos']) {
                            foreach ($this->post['cursos'] as $linha) {
                                $this->sql = "insert into visitas_vendedores_cursos set idcurso = '" . $linha . "',
																					idvisita = '" . $idvisita_cadastrada . "',
																					data_cad = NOW()	";
                                $salvar = $this->executaSql($this->sql);
                                if (!$salvar) {
                                    $this->retorno["erro"] = true;
                                    $this->retorno["erros"][] = 'erro_atualizar_vendedores_cursos';
                                    return $this->retorno;
                                } else {
                                    $this->retorno["sucesso"] = true;
                                    $this->monitora_oque = 1;
                                    $this->monitora_onde = 151;
                                    $this->monitora_qual = mysql_insert_id();
                                    $this->Monitora();
                                }
                            }
                        }

                        $this->retorno["sucesso"] = true;
                        $this->monitora_oque = 1;
                        $this->monitora_onde = 81;
                        $this->monitora_qual = $idvisita_cadastrada;
                        $this->retorno["id"] = $this->monitora_qual;
                        $this->Monitora();
                    } else {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = $this->sql;
                        $this->retorno["erros"][] = mysql_error();
                    }

                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = "visita_cadastrada";
                    $this->retorno["erros"][] = $this->linha_existente["idvisita"];
                }

            }
        }
        return $this->retorno;
    }

    public function Modificar()
    {

		$this->sql = "SELECT idpessoa FROM pessoas WHERE email = '" . $this->post["email"] . "' AND ativo = 'S'";
		$this->linha = $this->retornarLinha($this->sql);

        $idvendedor = ($this->post["idvendedor"]) ? $this->post["idvendedor"] : $this->idvendedor;

        $this->sql = "SELECT
                            idvisita
                        FROM
                            visitas_vendedores
                        WHERE (";

        if ($this->linha["idpessoa"]) {
            $this->sql .= " idpessoa = '" . $this->linha["idpessoa"] . "' OR ";
        }

        if ($this->linha["email"]) {
            $this->sql .= " email = '" . $this->linha["email"] . "' OR ";
        }

        $this->sql .= " email = '" . $this->post["email"] . "') AND
                        idvendedor = '" . $idvendedor . "' AND
                        ativo = 'S' AND
                        idvisita <> '" . $this->url[3] . "'";
        $this->linha_existente = $this->retornarLinha($this->sql);

		if ($this->linha_existente) {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_email_possui_visita';
			return $this->retorno;
		}

        mysql_query("START TRANSACTION");
        if (!$this->idvendedor)
            unset($this->config['formulario'][0]['campos'][1]);
        else
            unset($this->config['formulario'][0]['campos'][0]);

        if ($this->post['idcidade']) {
            $sql = 'SELECT idcidade FROM cidades WHERE nome = "' . $this->post['idcidade'] . '" ';
            $cidade = $this->retornarLinha($sql);
            $this->post['idcidade'] = $cidade['idcidade'];
        }

        if ($this->post['idestado']) {
            $sql = 'SELECT idestado FROM estados
					WHERE sigla = "' . $this->post['idestado'] . '"
					OR nome ="' . $this->post['idestado'] . '"';
            $estado = $this->retornarLinha($sql);
            $this->post['idestado'] = $estado['idestado'];
        }

        if ($this->post['cursos']) {
            foreach ($this->post['cursos'] as $linha) {
                $sql = "SELECT idvisita_curso, ativo
						FROM visitas_vendedores_cursos
						WHERE idvisita = '" . $this->url[3] . "'
						AND idcurso = '" . $linha . "' ";
                $visita_curso = $this->retornarLinha($sql);

                if ($visita_curso['idvisita_curso']) {
                    if ($visita_curso['ativo'] == 'N') {
                        $sql_atualiza = "UPDATE visitas_vendedores_cursos
										SET ativo = 'S'
										WHERE idvisita_curso = '" . $visita_curso['idvisita_curso'] . "' ";
                        $atualiza = $this->executaSql($sql_atualiza);
                        if (!$atualiza) {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = 'erro_atualizar_cursos';
                            return $this->retorno;
                        } else {
                            $this->retorno["sucesso"] = true;
                            $this->monitora_oque = 1;
                            $this->monitora_onde = 151;
                            $this->monitora_qual = $visita_curso['idvisita_curso'];
                            $this->Monitora();
                        }
                    }
                } else {
                    $this->sql = "INSERT INTO visitas_vendedores_cursos
									SET idcurso = '" . $linha . "',
										idvisita = '" . $this->url[3] . "',
										data_cad = NOW()	";
                    $salvar = $this->executaSql($this->sql);
                    if (!$salvar) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_atualizar_vendedores_cursos';
                        return $this->retorno;
                    } else {
                        $this->retorno["sucesso"] = true;
                        $this->monitora_oque = 1;
                        $this->monitora_onde = 151;
                        $this->monitora_qual = mysql_insert_id();
                        $this->Monitora();
                    }
                }
                $cursos_atualizados[] = $linha;
            }
        }

        $this->sql = "SELECT idvisita_curso
					FROM visitas_vendedores_cursos
					WHERE idvisita = '" . $this->url[3] . "' ";
        if ($cursos_atualizados)
            $this->sql .= " AND idcurso NOT IN (" . implode(',', $cursos_atualizados) . ") ";

        $this->limite = -1;
        $this->ordem_campo = 'idvisita_curso';
        $cursos_remover = $this->retornarLinhas();

        if ($cursos_remover) {
            foreach ($cursos_remover as $linha_remover) {
                $sql = "UPDATE visitas_vendedores_cursos
						SET ativo = 'N'
						WHERE idvisita_curso = '" . $linha_remover['idvisita_curso'] . "' ";
                $remove = $this->executaSql($sql);
                if (!$remove) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_atualizar_cursos';
                    return $this->retorno;
                } else {
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 3;
                    $this->monitora_onde = 151;
                    $this->monitora_qual = $linha_remover['idvisita_curso'];
                    $this->Monitora();
                }
            }
        }

        $salvar = $this->SalvarDados();

        if ($salvar['sucesso'])
            mysql_query("COMMIT");

        return $salvar;
    }

    public function adicionarMensagem()
    {
        $this->sql = "INSERT INTO
                        visitas_mensagens
                    SET
                        data_cad = NOW(),
                        idvisita = {$this->id},
                        ativo = 'S',
                        mensagem = '" . $this->post["mensagem"] . "', ";
        if ($this->idvendedor) {
            $this->sql .= "idvendedor = '" . $this->idvendedor . "'";
        } else {
            $this->sql .= "idusuario = '" . $this->idusuario . "'";
        }
        $salvar = $this->executaSql($this->sql);

        if ($salvar) {
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_adicionada_sucesso";
            if ($this->modulo == 'vendedor') {
                $this->idusuario = $this->idvendedor;
            }
            $idmensagem = mysql_insert_id();
            $this->monitora_onde = 196;
            $this->monitora_oque = 1;
            $this->monitora_qual = $idmensagem;
            $this->Monitora();
            //$this->AdicionarHistorico($this->idusuario, "mensagem", "cadastrou", NULL, NULL, $idmensagem);
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_adicionada_erro";
        }
        return $this->retorno;
    }

    public function removerMensagem($idmensagem)
    {
        $this->sql = "UPDATE
                    visitas_mensagens
                  SET
                    ativo = 'N'
                  WHERE
                    idmensagem = '" . $idmensagem . "'";
        $remover = $this->executaSql($this->sql);

        if ($remover) {
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_removida_sucesso";

            if ($this->modulo == 'atendente') {
                $this->idusuario = $this->idvendedor;
            }
            $this->monitora_onde = 196;
            $this->monitora_oque = 3;
            $this->monitora_qual = $this->id;
            $this->Monitora();
            //$this->AdicionarHistorico($this->idusuario, "mensagem", "removeu", NULL, NULL, $idmensagem);

        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_removida_erro";
        }
        return $this->retorno;
    }

    public function retornarMensagensVisita()
    {
        $this->sql = "SELECT {$this->campos}
                FROM
                    visitas_mensagens vm
                LEFT OUTER JOIN
                    usuarios_adm ua ON (ua.idusuario = vm.idusuario)
                LEFT OUTER JOIN
                    vendedores v ON (v.idvendedor = vm.idvendedor)
                WHERE
                    vm.ativo = 'S' AND
                    vm.idvisita = '{$this->id}'";
        return $this->retornarLinhas();
    }

    public function Remover()
    {
        return $this->RemoverDados();
    }

    public function BuscarPessoa()
    {

        $this->sql = "SELECT
						p.idpessoa AS 'key', concat(p.nome, ' (', p.documento, ')') AS value
					  FROM
						pessoas p
					  WHERE
					  	(p.nome LIKE '%" . $_GET["tag"] . "%' or p.documento like '%" . $_GET["tag"] . "%') AND p.ativo = 'S' AND
					  NOT EXISTS (SELECT sj.idpessoa FROM sinalizador_juridico sj WHERE sj.idpessoa = p.idpessoa AND sj.ativo = 'S')";

        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->groupby = "p.idpessoa";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    public function RetornarCursosVendedor($idvendedor)
    {
        $this->sql = "SELECT c.idcurso AS 'key', c.nome AS value
						  FROM cursos c
						  INNER JOIN cursos_sindicatos ci on c.idcurso = ci.idcurso and ci.ativo = 'S'
						  INNER JOIN vendedores_sindicatos vi on ci.idsindicato = vi.idsindicato and vi.ativo = 'S' ";

        if ($this->idusuario)
            $this->sql .= " inner join usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
						left join usuarios_adm_sindicatos uai on ci.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario ";

        $this->sql .= " WHERE vi.idvendedor = '" . $idvendedor . "' and c.nome like '%" . $_GET["tag"] . "%' ";

        if ($this->idusuario)
            $this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ";

        //and NOT EXISTS (SELECT vvc.idcurso FROM visitas_vendedores_cursos vvc WHERE vvc.idcurso = c.idcurso AND vvc.ativo = 'S')

        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    public function AssociarMatriculas($idvisita, $arrayCursos)
    {
        foreach ($arrayCursos as $ind => $id) {

            $this->sql = "select count(idpagamento_matricula) as total, idpagamento_matricula from pagamentos_compartilhados_matriculas where idpagamento = '" . intval($idpagamento) . "' and idmatricula = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update pagamentos_compartilhados_matriculas set ativo = 'S' where idpagamento_matricula = " . $totalAss["idpagamento_matricula"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idpagamento_matricula"];
            } else {
                $this->sql = "insert into pagamentos_compartilhados_matriculas set ativo = 'S', data_cad = now(), idpagamento = '" . intval($idpagamento) . "', idmatricula = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 151;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function retornarCursosVisita($idvisita)
    {
        $this->sql = "SELECT c.idcurso, c.nome
					FROM visitas_vendedores_cursos vvc
					INNER JOIN cursos c ON vvc.idcurso = c.idcurso
					WHERE vvc.idvisita = '" . $idvisita . "' AND vvc.ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "c.nome";
        return $this->retornarLinhas();
    }

	function ListarIteracoes()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
							visitas_vendedores_iteracoes vvi
						where vvi.ativo = 'S' and vvi.idvisita = " . intval($this->id);

        $this->groupby = "vvi.idvisita";
        return $this->retornarLinhas();
    }

    function adicionarIteracao()
    {
		if (!$this->post["data"] || !$this->post["numero"]) {
			$salvar["erro"] = false;
			$salvar["erros"][] = 'campos_obrigatorios_vazios';
			return $salvar;
		}

        $this->retorno = array();
        $this->sql = "insert into visitas_vendedores_iteracoes set
						data_cad=now(), ativo='S', idvisita='" . $this->id . "', data_visita='" . formataData($this->post["data"],'en',0) . "', numero='" . $this->post["numero"] . "', descricao='" . nl2br($this->post["descricao"]) . "' ";
        $cadastrar = $this->executaSql($this->sql);
        if ($cadastrar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 207;
            $this->monitora_qual = mysql_insert_id();
            $this->Monitora();
        } else {
            $this->retorno["sucesso"] = false;
        }
        return $this->retorno;
    }

    function RemoverIteracao()
    {
        $this->sql = "update visitas_vendedores_iteracoes set ativo='N' where iditeracao='" . intval($this->post["remover"]) . "' and idvisita = " . intval($this->id);
        if ($this->executaSql($this->sql)) {
            $remover["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 207;
            $this->monitora_qual = $this->post["remover"];
            $this->Monitora();
        } else {
            $remover["sucesso"] = false;
        }

        return $remover;

    }

}