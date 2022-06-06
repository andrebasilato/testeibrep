<?php
class Tipos_Documentos extends Core
{
    public function ListarTodas()
    {
        $this->sql = "select " . $this->campos . " from tipos_documentos where ativo = 'S'";
        $this->aplicarFiltrosBasicos()->set('groupby', 'idtipo');
        return $this->retornarLinhas();
    }

    public function Retornar()
    {
        $this->sql = "select " . $this->campos . " from tipos_documentos where ativo = 'S' and idtipo = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    public function Cadastrar()
    {
        return $this->SalvarDados();
    }

    public function Modificar()
    {
        return $this->SalvarDados();
    }

    public function Remover()
    {
        return $this->RemoverDados();
    }

    public function BuscarCurso()
    {
        $this->sql = "select
						c.idcurso as 'key', 
						c.nome as value 
					  from 
						cursos c 
					  where 
						c.nome LIKE '%" . $this->get["tag"] . "%' and
						c.ativo = 'S' and
						c.ativo_painel = 'S' and
						not exists (
						  select 
							tc.idtipo 
						  from 
							tipos_documentos_cursos tc 
						  where 
							tc.idcurso = c.idcurso and 
							tc.idtipo = '" . intval($this->id) . "' and
							tc.ativo = 'S'
						)";

        $this->limite = -1;
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.idcurso";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function AssociarCursos($idtipo, $arrayCurso)
    {
        foreach ($arrayCurso as $ind => $idcurso) {
            $this->sql = "select count(idtipo_curso) as total, idtipo_curso from tipos_documentos_cursos where idtipo = '" . intval($idtipo) . "' and idcurso = '" . intval($idcurso) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update tipos_documentos_cursos set ativo = 'S' where idtipo_curso = " . $totalAss["idtipo_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idtipo_curso"];
            } else {
                $this->sql = "insert into tipos_documentos_cursos set ativo = 'S', data_cad = now(), idtipo = '" . intval($idtipo) . "', idcurso = '" . intval($idcurso) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 109;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function DesassociarCursos()
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
            $this->sql = "update tipos_documentos_cursos set ativo = 'N' where idtipo_curso = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 109;
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

    public function TodosCursosObrigatorio($idtipo)
    {
        if (!$this->post["todos_cursos_obrigatorio"])
            $this->post["todos_cursos_obrigatorio"] = "N";


        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update tipos_documentos set todos_cursos_obrigatorio = '" . $this->post["todos_cursos_obrigatorio"] . "' where idtipo = " . intval($idtipo);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaNova = $this->retornarLinha($this->sql);

        if ($modificou) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idtipo;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    public function ListarCursosAss()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
							tipos_documentos t
							INNER JOIN tipos_documentos_cursos tc ON (t.idtipo = tc.idtipo)
							INNER JOIN cursos c ON (tc.idcurso = c.idcurso) where tc.ativo='S' and t.idtipo = " . intval($this->id);

        $this->groupby = "tc.idtipo_curso";
        return $this->retornarLinhas();
    }

    public function BuscarSindicato()
    {
        $this->sql = "select
						i.idsindicato as 'key', 
						i.nome_abreviado as value 
					  from 
						sindicatos i 
					  where 
						i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' and
						i.ativo = 'S' and
						i.ativo_painel = 'S' and
						not exists (
						  select 
							ti.idtipo 
						  from 
							tipos_documentos_sindicatos ti 
						  where 
							ti.idsindicato = i.idsindicato and 
							ti.idtipo = '" . intval($this->id) . "' and
							ti.ativo = 'S'
						)";

        $this->limite = -1;
        $this->ordem_campo = "i.nome_abreviado";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function AssociarSindicatos($idtipo, $arraySindicato)
    {
        foreach ($arraySindicato as $ind => $idsindicato) {
            $this->sql = "select count(idtipo_sindicato) as total, idtipo_sindicato from tipos_documentos_sindicatos where idtipo = '" . intval($idtipo) . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update tipos_documentos_sindicatos set ativo = 'S' where idtipo_sindicato = " . $totalAss["idtipo_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idtipo_sindicato"];
            } else {
                $this->sql = "insert into tipos_documentos_sindicatos set ativo = 'S', data_cad = now(), idtipo = '" . intval($idtipo) . "', idsindicato = '" . intval($idsindicato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 110;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function DesassociarSindicatos()
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
            $this->sql = "update tipos_documentos_sindicatos set ativo = 'N' where idtipo_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 110;
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

    public function TodasSindicatosObrigatorio($idtipo)
    {
        if (!$this->post["todas_sindicatos_obrigatorio"])
            $this->post["todas_sindicatos_obrigatorio"] = "N";


        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update tipos_documentos set todas_sindicatos_obrigatorio = '" . $this->post["todas_sindicatos_obrigatorio"] . "' where idtipo = " . intval($idtipo);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaNova = $this->retornarLinha($this->sql);

        if ($modificou) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idtipo;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    public function ListarSindicatosAss()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
							tipos_documentos t
							INNER JOIN tipos_documentos_sindicatos ti ON (t.idtipo = ti.idtipo)
							INNER JOIN sindicatos i ON (ti.idsindicato = i.idsindicato) where ti.ativo='S' and t.idtipo = " . intval($this->id);

        $this->groupby = "ti.idtipo_sindicato";
        return $this->retornarLinhas();
    }

    public function retornarTodosComObrigatorio($idsindicato, $idcurso)
    {
        $this->sql = "SELECT
                            td.*,
                            ifnull(
                                    (
                                        SELECT
                                            'S'
                                        FROM
                                            tipos_documentos_sindicatos
                                        WHERE
                                            idtipo = td.idtipo AND
                                            idsindicato = " . $idsindicato . " AND
                                            ativo = 'S'
                                        ORDER BY
                                            idtipo_sindicato DESC
                                        LIMIT 1
                                    ), 'N') AS sindicato_obrigatorio,
                            ifnull(
                                    (
                                        SELECT
                                            'S'
                                        FROM
                                            tipos_documentos_cursos
                                        WHERE
                                            idtipo = td.idtipo AND
                                            idcurso = " . $idcurso . " AND
                                            ativo = 'S'
                                        ORDER BY
                                            idtipo_curso DESC
                                        LIMIT 1
                                    ), 'N') AS curso_obrigatorio
                        FROM 
                            tipos_documentos td
                        WHERE 
                            td.ativo = 'S' AND
                            td.ativo_painel = 'S'";

        if ($this->modulo == 'aluno') {
            $this->sql .= " AND td.exibir_ava = 'S'";
        }

        if ($this->idmatricula) {
            $this->sql .= " AND (
                                    SELECT
                                        count(iddocumento)
                                    FROM
                                        matriculas_documentos
                                    WHERE
                                        idmatricula = '".$this->idmatricula."' AND
                                        idtipo = td.idtipo AND
                                        situacao = 'aprovado' AND
                                        ativo =  'S'
                                ) = 0";
        }

        $this->ordem = "asc";
        $this->ordem_campo = "nome";
        $this->limite = "-1";
        return $this->retornarLinhas();
    }

    public function retornarTodosObrigatorios($idsindicato,$idcurso)
    {
        $this->sql = "SELECT
                            td.*
                        FROM 
                            tipos_documentos td
                        WHERE 
                            td.ativo = 'S' AND
                            td.ativo_painel = 'S' AND
                            td.exibir_ava = 'S' AND
                            ((
                                SELECT
                                    COUNT(*)
                                FROM
                                    tipos_documentos_sindicatos
                                WHERE
                                    idtipo = td.idtipo AND
                                    idsindicato = " . $idsindicato . " AND
                                    ativo = 'S'
                            ) > 0 OR td.todas_sindicatos_obrigatorio = 'S'
                                  OR td.documento_foto_oficial = 'S' ) AND
                            ((
                                SELECT
                                    COUNT(*)
                                FROM
                                    tipos_documentos_cursos
                                WHERE
                                    idtipo = td.idtipo AND
                                    idcurso = " . $idcurso . " AND
                                    ativo = 'S'
                            ) > 0 OR td.todos_cursos_obrigatorio = 'S'
                                  OR td.documento_foto_oficial = 'S' ) ";

                            if ($this->idmatricula) {
                                $this->sql .= "AND
                                (
                                    SELECT
                                        count(iddocumento)
                                    FROM
                                        matriculas_documentos
                                    WHERE
                                        idmatricula = '".$this->idmatricula."' AND
                                        idtipo = td.idtipo AND
                                        situacao = 'aprovado' AND
                                        ativo =  'S'
                                ) = 0";
                            }

        $this->ordem = "asc";
        $this->ordem_campo = "nome";
        $this->limite = "-1";
        return $this->retornarLinhas();
    }
    
    public function BuscarSindicatoAgendamento()
    {
        $this->sql = "select
						i.idsindicato as 'key', 
						i.nome_abreviado as value 
					  from 
						sindicatos i 
					  where 
						i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' and
						i.ativo = 'S' and
						i.ativo_painel = 'S' and
						not exists (
						  select 
							ti.idtipo 
						  from 
							tipos_documentos_sindicatos_agendamento ti 
						  where 
							ti.idsindicato = i.idsindicato and 
							ti.idtipo = '" . intval($this->id) . "' and
							ti.ativo = 'S'
						)";

        $this->limite = -1;
        $this->ordem_campo = "i.nome_abreviado";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function AssociarSindicatosAgendamento($idtipo, $arraySindicato)
    {
        foreach ($arraySindicato as $ind => $idsindicato) {
            $this->sql = "select count(idtipo_sindicato) as total, idtipo_sindicato from tipos_documentos_sindicatos_agendamento where idtipo = '" . intval($idtipo) . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update tipos_documentos_sindicatos_agendamento set ativo = 'S' where idtipo_sindicato = " . $totalAss["idtipo_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idtipo_sindicato"];
            } else {
                $this->sql = "insert into tipos_documentos_sindicatos_agendamento set ativo = 'S', data_cad = now(), idtipo = '" . intval($idtipo) . "', idsindicato = '" . intval($idsindicato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 236;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function DesassociarSindicatosAgendamento()
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
            $this->sql = "update tipos_documentos_sindicatos_agendamento set ativo = 'N' where idtipo_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 236;
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

    public function TodasSindicatosAgendamentoObrigatorio($idtipo)
    {
        if (!$this->post["todas_sindicatos_obrigatorio_agendamento"])
            $this->post["todas_sindicatos_obrigatorio_agendamento"] = "N";


        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update tipos_documentos set todas_sindicatos_obrigatorio_agendamento = '" . $this->post["todas_sindicatos_obrigatorio_agendamento"] . "' where idtipo = " . intval($idtipo);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from tipos_documentos where idtipo = " . intval($idtipo);
        $linhaNova = $this->retornarLinha($this->sql);

        if ($modificou) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idtipo;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    public function ListarSindicatosAgendamentoAss()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
							tipos_documentos t
							INNER JOIN tipos_documentos_sindicatos_agendamento ti ON (t.idtipo = ti.idtipo)
							INNER JOIN sindicatos i ON (ti.idsindicato = i.idsindicato) where ti.ativo='S' and t.idtipo = " . intval($this->id);

        $this->groupby = "ti.idtipo_sindicato";
        return $this->retornarLinhas();
    }
    
}