<?php
class Ofertas extends Core
{

    var $idoferta_curso = null;
    var $idoferta_escola = null;
    var $idoferta_turma = null;
    var $idcurriculo = null;
    var $idoferta_curso_escola = null;
    var $idcuro = null;
    var $idescola = null;
    var $naoAgrupar = false;

    public function ListarTodas()
    {
        $this->sql = sprintf("SELECT %s FROM ofertas o inner join ofertas_workflow ow on (o.idsituacao = ow.idsituacao) where o.ativo = 'S'", $this->campos);
        $this->aplicarFiltrosBasicos()->set('groupby', 'o.idoferta');
        return $this->retornarLinhas();
    }

    function ListarTodasMatriculas()
    {
        $this->sql = "select
            " . $this->campos . "
        from
            ofertas o
        inner join ofertas_cursos oc on (o.idoferta = oc.idoferta and oc.ativo = 'S')
        inner join ofertas_escolas oe on (o.idoferta = oe.idoferta and oe.ativo = 'S')
        inner join escolas p on (oe.idescola = p.idescola and p.ativo = 'S')
        where
            o.data_inicio_matricula <= '" . date("Y-m-d") . "' and
            o.data_fim_matricula >= '" . date("Y-m-d") . "' and
            o.ativo = 'S' and
            o.ativo_painel = 'S'";

        if ($this->idusuario && $_SESSION["adm_gestor_sindicato"] <> "S") {
            $this->sql .= " and p.idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";
        } elseif ($this->idvendedor && !$_SESSION["usu_vendedor_escolas"]) {
            $this->sql .= " and p.idsindicato in (" . $_SESSION["usu_vendedor_sindicatos"] . ")";
        } elseif ($this->idescola && !$_SESSION["usu_vendedor_escolas"]) {
            $this->sql .= " and p.idescola = " . $this->idescola . "";
        } elseif ($_SESSION["usu_vendedor_escolas"]) {
            $this->sql .= " and p.idescola  in (" . $_SESSION["usu_vendedor_escolas"] . ")";
        }

        if ($this->idcurso) {
            $this->sql .= " and oc.idcurso = '" . $this->idcurso . "' ";
        }

        if ($this->url[0] == 'atendente') {
            $this->sql .= ' AND p.acesso_bloqueado = "N"';
        }

        $this->sql .= " group by
                    o.idoferta";
        $this->groupby = "o.idoferta";
        return $this->retornarLinhas();
    }

    function ListarTodasLoja($idescola, $idcurso)
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    ofertas o
                    inner join ofertas_cursos oc on (o.idoferta = oc.idoferta and oc.ativo = 'S')
                    inner join ofertas_cursos_escolas ocp on (oc.idoferta = ocp.idoferta and oc.idcurso = ocp.idcurso and ocp.ativo = 'S' and ocp.ignorar = 'N')
                    inner join escolas p on (ocp.idescola = p.idescola and p.ativo = 'S')
                  where
                    p.idescola = '" . $idescola . "' and
                    oc.idcurso = '" . $idcurso . "' and
                    o.data_inicio_matricula <= '" . date("Y-m-d") . "' and
                    o.data_fim_matricula >= '" . date("Y-m-d") . "' and
                    o.ativo = 'S' and
                    o.ativo_painel = 'S'
                  group by
                    o.idoferta";

        $this->groupby = "o.idoferta";
        return $this->retornarLinhas();
    }

    function Retornar()
    {
        $this->sql = "select " . $this->campos . " from ofertas o
                    where o.ativo = 'S' and o.idoferta = '" . $this->id . "'";
        $this->retorno = $this->retornarLinha($this->sql);

        $this->sql = "select
                    owa.idacao,
                    owa.idopcao
                  from
                    ofertas_workflow_acoes owa
                  where
                    owa.idsituacao = '" . $this->retorno["idsituacao"] . "' and
                    owa.ativo = 'S' ";
        $resultado = mysql_query($this->sql);

        while ($acao = mysql_fetch_assoc($resultado)) {
            foreach ($GLOBALS['workflow_parametros_ofertas'] as $op)
                if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "visualizacao")
                    $this->retorno["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;
        }

        return $this->retorno;
    }

    function AlterarSituacao($de, $para)
    {
        $this->retorno = array();

        if ($this->VerificaPreRequesito($de, $para)) {
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_situacao_sucesso";

            $this->sql = "select idsituacao, idoferta from ofertas where idoferta = " . intval($this->id);
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "update ofertas set idsituacao = '" . $para . "' where idoferta = '" . $this->id . "'";
            $salvar = $this->executaSql($this->sql);

            $this->sql = "select idsituacao, idoferta from ofertas where idoferta = " . intval($this->id);
            $linhaNova = $this->retornarLinha($this->sql);

            //$this->AdicionarHistorico($this->id, "situacao", "modificou", $linhaAntiga["idsituacao"], $linhaNova["idsituacao"]);
            $this->ProcessaAcoes($de, $para);
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_situacao_erro_prerequesitos";
        }
        return $this->retorno;
    }

    function VerificaPreRequesito($de, $para)
    {

        $this->sql = "select idrelacionamento from ofertas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                    owa.idopcao
                  from
                    ofertas_workflow_acoes owa
                  where
                    owa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                    owa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "owa.idopcao";
        $resultado = $this->executaSql($this->sql);

        while ($acao = mysql_fetch_assoc($resultado)) {
            foreach ($GLOBALS['workflow_parametros_ofertas'] as $op) {
                if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "prerequisito") {
                    $preRequisitos[] = $acao;
                }
            }
        }

        if (count($preRequisitos) > 0) {
            $this->sql = "select * from ofertas where idoferta = " . intval($this->id);
            $oferta = $this->retornarLinha($this->sql);
            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito["idopcao"]) {

                }
            }
        }
        return true;
    }

    function ProcessaAcoes($de, $para)
    {
        $this->sql = "select idrelacionamento from ofertas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                    owa.idopcao,
                    owap.valor
                  from
                    ofertas_workflow_acoes owa
                    left outer join ofertas_workflow_acoes_parametros owap on (owa.idacao = owap.idacao)
                  where
                    owa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                    owa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "owa.idopcao";
        $acoes = $this->retornarLinhas();

        foreach ($acoes as $acao) {
            foreach ($GLOBALS['workflow_parametros_ofertas'] as $op) {
                if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "acao") {
                    $preRequisitos[] = $acao;
                }
            }
        }

        if (count($preRequisitos) > 0) {
            $this->sql = "select * from ofertas where idoferta = " . intval($this->id);
            $comissao = $this->retornarLinha($this->sql);

            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito["idopcao"]) {

                }
            }
        }
    }

    function Cadastrar()
    {
        $this->sql = "SELECT
                    idsituacao
                  FROM
                     ofertas_workflow
                 WHERE
                     inicio =  'S' ORDER BY idsituacao DESC limit 1 ";
        $situacaoInicial = $this->retornarLinha($this->sql);
        $this->post["idsituacao"] = $situacaoInicial["idsituacao"];
        if (!$this->post["idsituacao"]) {
            $retorno['erros'][] = 'workflow_nao_configurado';
            return $retorno;
        }

        return $this->SalvarDados();
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function AssociarCursos($idoferta, $curso, $possuiFinanceiro) {

        $this->sql = "select count(idoferta_curso) as total, idoferta_curso
                              from ofertas_cursos
                               where idoferta = '".$idoferta.
            "' AND idcurso = '".$curso.
            "' AND ativo = 'N' ";
        $totalAss = $this->retornarLinha($this->sql);
        if ($totalAss["total"] > 0) {
            $this->sql = "update ofertas_cursos
                                  set
                                        ativo = 'S',
                                        possui_financeiro = '".$possuiFinanceiro."'
                                  where idoferta_curso = " . $totalAss["idoferta_curso"];
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = $totalAss["idoferta_curso"];
        } else {
            $this->sql = "insert into ofertas_cursos
                                  set
                                        ativo = 'S',
                                        data_cad = now(),
                                        possui_financeiro = '".$possuiFinanceiro."',
                                        idoferta = '" . intval($idoferta) . "',
                                        idcurso = '" . intval($curso) . "' ";
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = mysql_insert_id();
        }

        if ($associar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 11;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function AssociarEscolas($idoferta, $escola)
    {

        $this->sql = "select count(idoferta_escola) as total, idoferta_escola from ofertas_escolas where idoferta = '" . $idoferta . "' and idescola = '" . $escola . "' AND ativo = 'N' ";
        $totalAss = $this->retornarLinha($this->sql);
        if ($totalAss["total"] > 0) {
            $this->sql = "update ofertas_escolas set ativo = 'S' where idoferta_escola = " . $totalAss["idoferta_escola"];
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = $totalAss["idoferta_escola"];
        } else {
            $this->sql = "insert into ofertas_escolas set ativo = 'S', data_cad = now(), idoferta = '" . intval($idoferta) . "', idescola = '" . intval($escola) . "' ";
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = mysql_insert_id();
        }


        if ($associar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 161;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function CadastrarTurma($idoferta, $turma)
    {

        $this->sql = "select count(idturma) as total, idturma from ofertas_turmas where idoferta = '" . $idoferta . "' and nome = '" . $turma . "' AND ativo = 'N' ";
        $totalAss = $this->retornarLinha($this->sql);
        if ($totalAss["total"] > 0) {
            $this->sql = "update ofertas_turmas set ativo = 'S' where idturma = " . $totalAss["idturma"];
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = $totalAss["idturma"];
        } else {
            $this->sql = "select count(idturma) as total, idturma from ofertas_turmas where idoferta = '" . $idoferta . "' and nome = '" . $turma . "' AND ativo = 'S' ";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss['total']) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_turma_existente';
                return $this->retorno;
            }

            $this->sql = "insert into ofertas_turmas set ativo = 'S', data_cad = now(), idoferta = '" . intval($idoferta) . "', nome = '" . mysql_real_escape_string($turma) . "' ";
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = mysql_insert_id();
        }


        if ($associar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 162;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function RemoverCursos()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULARIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update ofertas_cursos set ativo = 'N' where idoferta_curso = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 11;
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

    function salvarInformacoesProvaPresencial($idoferta_curso)
    {

        $this->post["porcentagem_minima"] =
            str_replace('.', '', $this->post["porcentagem_minima"]);

        $this->post["porcentagem_minima"] =
            str_replace(',', '.', $this->post["porcentagem_minima"]);

        if(!$this->post['porcentagem_minima'])
            $this->post['porcentagem_minima'] = 'NULL';

        if(!$this->post['qtde_minima_dias'])
            $this->post['qtde_minima_dias'] = 'NULL';

        $this->sql = "UPDATE
                    ofertas_cursos
                SET
                    porcentagem_minima = " . $this->post['porcentagem_minima'] . ",
                    qtde_minima_dias = " . $this->post['qtde_minima_dias'] . "
                WHERE
                    idoferta_curso = " . (int)$idoferta_curso;
        $salvar = $this->executaSql($this->sql);
        $this->monitora_qual = $idoferta_curso;

        if (!$salvar) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
            return $this->retorno;
        }
        $this->retorno["sucesso"] = true;
        $this->monitora_oque = 2;
        $this->monitora_onde = 11;
        $this->Monitora();
        return $this->retorno;
    }

    function salvarInformacoesAvaliacaoVirtual($idoferta_curso)
    {

        $this->post["porcentagem_minima_virtual"] =
            str_replace('.', '', $this->post["porcentagem_minima_virtual"]);

        $this->post["porcentagem_minima_virtual"] =
            str_replace(',', '.', $this->post["porcentagem_minima_virtual"]);

        $this->sql = "UPDATE
                    ofertas_cursos
                SET
                    porcentagem_minima_virtual = '" . $this->post['porcentagem_minima_virtual'] . "'                WHERE
                    idoferta_curso = " . (int)$idoferta_curso;
        $salvar = $this->executaSql($this->sql);
        $this->monitora_qual = $idoferta_curso;

        if ($salvar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_onde = 11;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    public function salvarDadosCurso($idoferta_curso)
    {
        if (! $this->post['data_inicio_aula']) {
            $this->post['data_inicio_aula'] = "NULL";
        } else {
            $this->post['data_inicio_aula'] = "'" . formataData($this->post['data_inicio_aula'], 'en', 0) . "'";
        }

        $this->sql = 'UPDATE
                        ofertas_cursos
                      SET
                        data_inicio_aula = ' . $this->post['data_inicio_aula'];

        if (! empty($this->post['possui_financeiro'])) {
            $this->sql .= ', possui_financeiro = "' . $this->post['possui_financeiro'] . '"';
        }

        $this->sql .= ' WHERE idoferta_curso = ' . $idoferta_curso;

        $salvar = $this->executaSql($this->sql);
        $this->monitora_qual = $idoferta_curso;

        if ($salvar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_onde = 11;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function RemoverEscolas()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULARIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update ofertas_escolas set ativo = 'N' where idoferta_escola = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 161;
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

    function RemoverTurmas()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULARIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update ofertas_turmas set ativo = 'N' where idturma = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 162;
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

    function RetornarSituacoesWorkflow()
    {
        $this->sql = "SELECT * FROM ofertas_workflow WHERE ativo = 'S' ";
        $this->ordem_campo = "ordem";
        $this->ordem = "asc";
        $this->groupby = "idsituacao";
        $retorno = $this->retornarLinhas();
        $this->retorno = NULL;

        foreach ($retorno as $ind => $var) {
            $this->retorno[$var["idsituacao"]] = $var;
        }
        return $this->retorno;
    }

    function RetornarRelacionamentosWorkflow($idsituacao)
    {
        $this->sql = "select idsituacao_para from ofertas_workflow_relacionamentos where idsituacao_de = " . mysql_real_escape_string($idsituacao) . " and ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "idsituacao_para";
        $this->groupby = "idsituacao_para";
        return $this->retornarLinhas();
    }

    function ListarCursosAssociados($listar_curriculos = null)
    {
        $this->sql = "select
                    " . $this->campos . ", (select count(1) from matriculas m where m.idcurso = c.idcurso and m.idoferta = " . $this->id . " and m.ativo = 'S' ) as matriculas
                  from
                    ofertas_cursos oc
                    inner join cursos c on (oc.idcurso = c.idcurso)
                    inner join ofertas o on (oc.idoferta = o.idoferta)
                  where
                    oc.ativo = 'S' and
                    o.idoferta = " . $this->id;

        if ($this->idusuario)
            $this->sql .= " and (   select ua.idusuario
                                    from usuarios_adm ua
                                        left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                        left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
                                    where ua.idusuario = " . $this->idusuario . "
                                        and (   ua.gestor_sindicato = 'S'
                                                or
                                                (   ci.idcurso = c.idcurso and
                                                    uai.idusuario is not null and
                                                    ci.idsindicato is not null
                                                )
                                            )
                                    limit 1
                                ) is not null ";

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
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "oc.idcurso";

        if ($listar_curriculos) {
            $cursos = $this->retornarLinhas();
            foreach ($cursos as $curso) {
                $sql = 'select idcurriculo, nome from curriculos where ativo = "S" and idcurso = ' . $curso['idcurso'] . ' ';
                $resultado = $this->executaSql($sql);
                while ($curriculo = mysql_fetch_assoc($resultado)) {
                    $curso['curriculos'][] = $curriculo;
                }
                $retorno[] = $curso;
            }
            return $retorno;
        } else {
            return $this->retornarLinhas();
        }

    }

    function ListarCursosNaoAssociados($idoferta)
    {
        $this->sql = "select
                    c.idcurso, c.nome
                  from
                    cursos c
                  where
                    c.ativo = 'S'
                    and c.ativo_painel = 'S'
                  having
                    ( select count(1) from ofertas_cursos oc where (c.idcurso = oc.idcurso and oc.ativo = 'S' and oc.idoferta = " . $idoferta . ") ) = 0 ";

        if ($this->idusuario)
            $this->sql .= " and (   select ua.idusuario
                                    from usuarios_adm ua
                                        left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                        left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
                                    where ua.idusuario = " . $this->idusuario . "
                                        and (   ua.gestor_sindicato = 'S'
                                                or
                                                (   ci.idcurso = c.idcurso and
                                                    uai.idusuario is not null and
                                                    ci.idsindicato is not null
                                                )
                                            )
                                    limit 1
                                ) is not null ";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function ListarEscolasAssociados()
    {
        $this->sql = "SELECT
                    " . $this->campos . ", (select count(idmatricula) from matriculas m where m.idescola = p.idescola and m.idoferta = " . $this->id . " and m.ativo = 'S' ) as matriculas
                  FROM
                    ofertas_escolas op
                    inner join escolas p on (op.idescola = p.idescola)
                    inner join ofertas o on (op.idoferta = o.idoferta)
                    inner join sindicatos i on (i.idsindicato = p.idsindicato)
                  WHERE op.ativo = 'S' AND o.idoferta = " . $this->id;

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
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "op.idescola";
        return $this->retornarLinhas();
    }

    function ListarEscolasNaoAssociados($idoferta)
    {
        $this->sql = "select
                    p.idescola, p.nome_fantasia, i.nome_abreviado as sindicato
                  from
                    escolas p
                    inner join sindicatos i on (p.idsindicato = i.idsindicato)
                  where
                    p.ativo = 'S' and
                    (select count(1) from ofertas_escolas op where p.idescola = op.idescola and op.ativo = 'S' and op.idoferta = ".$idoferta.") = 0";

        $this->limite = -1;
        $this->ordem = 'asc';
        $this->ordem_campo = 'i.nome_abreviado asc, p.nome_fantasia';

        return $this->retornarLinhas();
    }

    function ListarTurmas()
    {
        $this->sql = "select
                    " . $this->campos . ",
                    (select count(idmatricula) from matriculas m where m.idturma = t.idturma and m.idoferta = " . $this->id . " and m.ativo = 'S' ) as matriculas
                  from
                    ofertas_turmas t
                  where
                    t.ativo = 'S' and
                    t.idoferta = " . $this->id;

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
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "t.idturma";
        return $this->retornarLinhas();
    }

    function ListarTurmasMatricula($idsindicato)
    {
        $this->sql = "select
                    " . $this->campos . ",
                    (select count(idmatricula) from matriculas m where m.idturma = t.idturma and m.idoferta = " . $this->id . " and m.ativo = 'S' ) as matriculas
                  from
                    ofertas_turmas t
                    left outer join ofertas_turmas_sindicatos oti on (t.idturma = oti.idturma and t.idoferta = oti.idoferta and oti.idsindicato = " . $idsindicato . " and oti.ativo = 'S')
                  where
                    t.idoferta = " . $this->id . " and
                    (oti.ignorar = 'N' or oti.ignorar is null) and
                    t.ativo = 'S' and
                    t.ativo_painel = 'S'";
        //echo $this->sql;
        $this->groupby = "t.idturma";
        return $this->retornarLinhas();
    }


    function listarTodasCursosMatriculas($listandoOfertasSelect = false)
    {
        $this->sql = "SELECT
                " . $this->campos . "
            FROM
                ofertas_cursos oc
                INNER JOIN cursos c ON (c.idcurso = oc.idcurso AND c.ativo = 'S')
                INNER JOIN ofertas o ON (o.idoferta = oc.idoferta AND o.ativo = 'S' AND o.ativo_painel = 'S')
                LEFT JOIN sindicatos_valores_cursos svc ON (svc.idcurso = c.idcurso AND svc.ativo = 'S')
            ";

        if (!$listandoOfertasSelect) {
            $this->sql .= "
                INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = o.idoferta AND ocp.idcurso = c.idcurso AND ocp.ativo = 'S')
                INNER JOIN ofertas_escolas op ON (op.idescola = ocp.idescola AND op.idoferta = o.idoferta AND op.ativo = 'S')
                INNER JOIN escolas e ON (e.idescola = op.idescola)
                INNER JOIN cidades cid ON (cid.idcidade = e.idcidade)
                INNER JOIN estados est ON (est.idestado = e.idestado)
                INNER JOIN escolas_estados_cidades eec ON (eec.idescola = e.idescola AND eec.ativo = 'S')
                INNER JOIN estados est2 ON (est2.idestado = eec.idestado)
                INNER JOIN cidades cid2 ON (cid2.idcidade = eec.idcidade)
                LEFT JOIN cfcs_valores_cursos cvc ON (cvc.idcurso = c.idcurso AND cvc.idcfc = e.idescola AND cvc.ativo = 'S')
            ";
        }  elseif($this->idescola) {
            $this->sql .= "INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = o.idoferta AND ocp.idcurso = c.idcurso AND ocp.ativo = 'S')";
        }

        if ($this->idusuario) {
            $this->sql .= " INNER JOIN usuarios_adm ua ON (ua.idusuario = " . $this->idusuario . ")
                LEFT OUTER JOIN cursos_sindicatos ci ON (c.idcurso = ci.idcurso AND ci.ativo = 'S')
                LEFT OUTER JOIN usuarios_adm_sindicatos uai ON (ci.idsindicato = uai.idsindicato AND uai.ativo = 'S' AND uai.idusuario = ua.idusuario) ";
        } elseif ($this->idvendedor) {
            $this->sql .= " INNER JOIN vendedores v ON (v.idvendedor = " . $this->idvendedor . ")
                INNER JOIN cursos_sindicatos ci ON (c.idcurso = ci.idcurso AND ci.ativo = 'S')
                INNER JOIN vendedores_sindicatos vi ON (ci.idsindicato = vi.idsindicato AND vi.ativo = 'S' AND v.idvendedor = vi.idvendedor) ";
        }

        $this->sql .= " WHERE";

        if ($this->url[0] == 'atendente') {
            $this->sql .= ' e.acesso_bloqueado = "N" AND';
        }

        if ($this->idoferta_curso) {
            $this->sql .= ' oc.idoferta_curso = ' . $this->idoferta_curso . ' AND';
        }

        if ($this->idusuario) {
            $this->sql .= " (ua.gestor_sindicato = 'S' or uai.idusuario is not null) AND";
        }

        if ($this->idcurso) {
            $this->sql .= " c.idcurso='" . $this->idcurso . "' AND";
        }

        if ($this->idescola) {
            $this->sql .= ' ocp.idescola = ' . $this->idescola . ' AND';
        }

        if ($this->idestado) {
            $this->sql .= ' est.idestado = ' . $this->idestado . ' AND';
        }

        if ($this->idestadoVinculo) {
            $this->sql .= ' est2.idestado = ' . $this->idestadoVinculo . ' AND';
        }

        if ($this->siglaUf) {
            $this->sql .= ' est.sigla = "' . $this->siglaUf . '" AND';
        }

        if ($this->siglaUfVinculo) {
            $this->sql .= ' est2.sigla = "' . $this->siglaUfVinculo . '" AND';
        }

        if ($this->idcidade) {
            $this->sql .= ' cid.idcidade = ' . $this->idcidade . ' AND';
        }

        if ($this->idcidadeVinculo) {
            $this->sql .= ' cid2.idcidade = ' . $this->idcidadeVinculo . ' AND';
        }

        $this->sql .= " oc.ativo = 'S'";

        if ($this->modulo == 'web') {
            $this->sql .= " AND o.data_inicio_matricula <= DATE_FORMAT(NOW(), '%Y-%m-%d') AND o.data_fim_matricula >= DATE_FORMAT(NOW(), '%Y-%m-%d')";
        }

        if ($this->modulo != 'web') {
            $this->sql .= " AND o.idoferta = " . $this->id;
        } else {
            $this->sql .= " AND ocp.ignorar = 'N'";
        }
        if($this->idcurriculo == 'not null')
            $this->sql .= " AND ocp.idcurriculo is not null";
        if ($this->agruparPor) {
            $this->sql .= ' GROUP BY ' . $this->agruparPor;
        } elseif (! $this->naoAgrupar) {
            $this->sql .= ' GROUP BY oc.idoferta_curso';
        }

        $this->groupby = "oc.idcurso";

        $retorno = $this->retornarLinhas();

        return $retorno;
    }

    function FiltrarCursosComCurriculo($ofertaCursos){

        $ofertaCursoFiltrado = array();

        foreach ($ofertaCursos as $ofertaCurso){

            $sql = "SELECT COUNT(idoferta_curso_escola) 
                    FROM ofertas_cursos_escolas 
                    WHERE ativo = 'S' 
                     AND idcurriculo is not null                             
                     AND idoferta = ". $ofertaCurso['idoferta']. "
                     AND idcurso = ". $ofertaCurso['idcurso'];

            $qtd = $this->retornarLinha($sql);

            if($qtd > 1){
                $ofertaCursoFiltrado[] = $ofertaCurso;
            }
        }
        return $ofertaCursoFiltrado;
    }

    function RetornarCurso()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    ofertas_cursos oc
                    inner join cursos c on oc.idcurso = c.idcurso
                    inner join ofertas o on oc.idoferta = o.idoferta
                  where
                    oc.ativo = 'S' and
                    oc.idoferta_curso = " . $this->idoferta_curso;
        $this->retorno = $this->retornarLinha($this->sql);
        return $this->retorno;
    }

    function RetornarEscola()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    ofertas_escolas op
                    inner join escolas p on op.idescola = p.idescola
                    inner join ofertas o on op.idoferta = o.idoferta
                  where
                    op.ativo = 'S' and
                    op.idoferta_escola = " . $this->idoferta_escola;
        $this->retorno = $this->retornarLinha($this->sql);
        return $this->retorno;
    }

    function RetornarTurma()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    ofertas_turmas t
                    inner join ofertas o on t.idoferta = o.idoferta
                  where
                    t.ativo = 'S' and
                    t.idturma = " . $this->idturma;
        $this->retorno = $this->retornarLinha($this->sql);
        return $this->retorno;
    }

    function ativarInativarTurma()
    {
        $this->sql = "select * from ofertas_turmas where idturma = " . intval($this->post["idturma"]);
        $linhaAntiga = $this->retornarLinha($this->sql);

        if ($linhaAntiga["ativo_painel"] == "S") {
            $ativo_painel = "N";
        } else {
            $ativo_painel = "S";
        }

        $this->sql = "update ofertas_turmas set ativo_painel = '" . $ativo_painel . "' where idturma = " . intval($this->post["idturma"]);
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from ofertas_turmas where idturma = " . intval($this->post["idturma"]);
        $linhaNova = $this->retornarLinha($this->sql);

        $this->retorno = array();

        if ($executa) {
            $this->monitora_oque = 2;
            $this->monitora_onde = 162;
            $this->monitora_qual = intval($this->post["idturma"]);
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->idusuario = $_SESSION['adm_idusuario'];
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["ativo"] = $linhaNova["ativo_painel"];
            $this->retorno["turma"] = $linhaNova["idturma"];
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["ativo"] = $linhaAntiga["ativo_painel"];
            $this->retorno["turma"] = $linhaAntiga["idturma"];
        }

        return json_encode($this->retorno);
    }

    function ModificarCurso()
    {
        unset($this->config["formulario"][0]["campos"][5]);
        $this->post["idoferta"] = $this->id;

        return $this->SalvarDados();
    }

    function listarTotalOfertas()
    {
        $this->sql = "SELECT COUNT(o.idoferta) AS total
                      FROM ofertas o
                      INNER JOIN ofertas_workflow ow ON o.idsituacao = ow.idsituacao
                      WHERE o.ativo = 'S'
                      AND o.ativo_painel = 'S'
                      AND o.data_fim_matricula > now()
                      AND ow.inscricao_aberta = 'S'";

        return $this->retornarLinha($this->sql)['total'];
    }

    function RetornarSituacaoInicial()
    {
        $this->sql = "SELECT
                    idsituacao
                  FROM
                     ofertas_workflow
                 WHERE
                     inicio =  'S' ORDER BY idsituacao DESC limit 1 ";
        $dados = $this->retornarLinha($this->sql);
        return $dados['idsituacao'];
    }

    function RetornarEscolaOferta()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    ofertas_escolas op
                    inner join escolas p on op.idescola = p.idescola
                    inner join ofertas o on op.idoferta = o.idoferta
                  where
                    op.ativo = 'S' and
                    op.idescola = " . $this->idoferta_escola . " and
                    op.idoferta = " . $this->id . "";
        $this->retorno = $this->retornarLinha($this->sql);
        return $this->retorno;
    }

    function ListarCurriculosCurso($idcurso)
    {
        $this->sql = "select * from curriculos where idcurso = '" . $idcurso . "' and ativo = 'S' and ativo_painel = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "idcurso";
        return $this->retornarLinhas();
    }

    function cadastrarCursoEscola()
    {

        //echo '<pre>';
        //print_r($this->post);
        //echo '</pre>';

        //mysql_query('START TRANSACTION') or die(mysql_error());
        foreach ($this->post['escolas'] as $idescola => $escola) {
            foreach ($escola['cursos'] as $idcurso => $curso) {
                $continue = false;
                if (!$curso['ignorar'] && !$curso['idcurriculo']) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_campos_obrigatorios';
                    $continue = true;
                }

                $sql = 'SELECT * FROM
                            ofertas_cursos_escolas
                        WHERE
                            idcurso = ' . $idcurso . ' AND
                            idescola = ' . $idescola . ' AND
                            idoferta = ' . $this->url[3] . ' AND
                            ativo = "S" ';
                //if( $_SESSION['adm_idusuario'] == "93" ){
                //    echo $sql."<br>";

                //}
                //echo "SQL 1 ->".$sql."<br>";
                $resultado = $this->executaSql($sql);
                if (!$resultado) {
                    //mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_consultar_ofertas_cursos_blocos';
                    return $this->retorno;
                }

                $existe_cadastro = mysql_fetch_assoc($resultado);
                //echo '<pre>';
                //print_r($existe_cadastro);
                //echo '</pre>';
                $idOfertaCursoEscola = null;
                $idOfertaCursoEscola = (int)$existe_cadastro['idoferta_curso_escola'];
                //echo 'Escola = '.$idOfertaCursoEscola;
                if (!empty($existe_cadastro['idoferta_curso_escola'])) {
                    if ($curso['ignorar']){
                        $ignorar = 'S';
                    }else{
                        $ignorar = 'N';
                    }
                    /*
                    if (
                        $continue ||
                        ($existe_cadastro['ignorar'] == $ignorar &&
                        $existe_cadastro['dias_para_ava'] == $curso['dias_ava'] &&
                        $existe_cadastro['data_inicio_ava'] == formataData($curso['data_inicio_ava'], 'en', 0) &&
                        $existe_cadastro['data_limite_ava'] == formataData($curso['data_limite_ava'], 'en', 0) &&
                        $existe_cadastro['dias_para_contrato'] == $curso['dias_contrato'] &&
                        $existe_cadastro['dias_para_prova'] == $curso['dias_para_prova'] &&
                        (int)$existe_cadastro['idcurriculo'] == (int)$curso['idcurriculo'] &&
                        $existe_cadastro['ordem'] == $curso['ordem'])
                    ) {
					*/
                    #continue;
                    $sql = 'UPDATE ofertas_cursos_escolas
                                    SET ativo = "S" ';
                    if ($curso['ignorar']){
                        $sql .= ', ignorar = "S" ';
                    }else{
                        $sql .= ', ignorar = "N" ';
                    }
                    if ($curso['dias_ava'] || $curso['dias_ava'] === '0'){
                        $sql .= ', dias_para_ava = "' . $curso['dias_ava'] . '" ';
                    }else{
                        $sql .= ', dias_para_ava = NULL ';
                    }
                    if ($curso['data_inicio_ava']){
                        $sql .= ', data_inicio_ava = "' . formataData($curso['data_inicio_ava'], 'en', 0) . '" ';
                    }else{
                        $sql .= ', data_inicio_ava = NULL ';
                    }
                    if ($curso['data_limite_ava']){
                        $sql .= ', data_limite_ava = "' . formataData($curso['data_limite_ava'], 'en', 0) . '" ';
                    }else{
                        $sql .= ', data_limite_ava = NULL ';
                    }
                    if ($curso['dias_contrato'] || $curso['dias_contrato'] === '0'){
                        $sql .= ', dias_para_contrato = "' . $curso['dias_contrato'] . '" ';
                    }else{
                        $sql .= ', dias_para_contrato = NULL ';
                    }
                    if ($curso['dias_para_prova'] || $curso['dias_para_prova'] === '0'){
                        $sql .= ', dias_para_prova = "' . $curso['dias_para_prova'] . '" ';
                    }else{
                        $sql .= ', dias_para_prova = NULL ';
                    }
                    if ($curso['idcurriculo']){
                        $sql .= ', idcurriculo = ' . $curso['idcurriculo'] . '  ';
                    }else{
                        $sql .= ', idcurriculo = NULL ';
                    }
                    if ($curso['ordem']){
                        $sql .= ', ordem = ' . $curso['ordem'] . '  ';
                    }else{
                        $sql .= ', ordem = NULL ';
                    }
                    $sql .= ' where idoferta_curso_escola = ' . $idOfertaCursoEscola;
                    //if( $_SESSION['adm_idusuario'] == "93" ){
                    //    echo $sql."<br>";
                    //}
                    //echo "SQL UPDATE ->".$sql."<br>";
                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 2;
                        $this->monitora_qual = $idOfertaCursoEscola;
                        $this->monitora_onde = 80;
                        $this->Monitora();
                    } else {
                        //mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_cursos_blocos';
                        return $this->retorno;
                    }
                    //}
                } else {
                    $sql = 'INSERT INTO ofertas_cursos_escolas
                                SET
                                    idcurso = ' . $idcurso . ',
                                    idescola = ' . $idescola . ',
                                    idoferta = ' . $this->url[3] . ',
                                    data_cad = now() ';
                    if ($curso['ignorar'])
                        $sql .= ', ignorar = "S" ';
                    if ($curso['dias_ava'])
                        $sql .= ', dias_para_ava = "' . $curso['dias_ava'] . '" ';
                    if ($curso['data_inicio_ava'])
                        $sql .= ', data_inicio_ava = "' . formataData($curso['data_inicio_ava'], 'en', 0) . '" ';
                    if ($curso['data_limite_ava'])
                        $sql .= ', data_limite_ava = "' . formataData($curso['data_limite_ava'], 'en', 0) . '" ';
                    if ($curso['dias_contrato'])
                        $sql .= ', dias_para_contrato = "' . $curso['dias_contrato'] . '" ';
                    if ($curso['dias_para_prova'])
                        $sql .= ', dias_para_prova = "' . $curso['dias_para_prova'] . '" ';
                    if ($curso['idcurriculo'])
                        $sql .= ', idcurriculo = ' . $curso['idcurriculo'] . '  ';
                    if ($curso['ordem'])
                        $sql .= ', ordem = ' . $curso['ordem'] . '  ';
                    //echo "SQL INSERT ->".$sql."<br>";
                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 1;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 80;
                        $this->Monitora();
                    } else {
                        //mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_cursos_blocos';
                        return $this->retorno;
                    }
                }
            }
        }
        //exit();
        $this->retorno["sucesso"] = true;
        //mysql_query('COMMIT') or die(mysql_error());;
        return $this->retorno;
    }

    function ListarCursosEscolas()
    {

        $sql = '
            select
                oc.idcurso as idcurso_teste,
                op.idoferta_escola,
                oc.*,
                ocp.idoferta,
                ocp.dias_para_ava,
                ocp.data_inicio_ava,
                ocp.data_limite_ava,
                ocp.dias_para_contrato,
                ocp.dias_para_prova,
                ocp.ordem,
                ocp.ignorar,
                cur.idcurriculo,
                c.nome as curso,
                p.idescola,
                p.nome_fantasia as escola,
                i.nome_abreviado as sindicato
            from ofertas_escolas op
            inner join escolas p on op.idescola = p.idescola and p.ativo = "S"
            inner join sindicatos i on i.idsindicato = p.idsindicato and i.ativo = "S"
            inner join ofertas_cursos oc on op.idoferta = oc.idoferta and oc.ativo = "S"
            inner join cursos c on oc.idcurso = c.idcurso and c.ativo = "S"
            left join ofertas_cursos_escolas ocp on ocp.ativo = "S" and ocp.idoferta = op.idoferta and ocp.idescola = op.idescola and ocp.idcurso = oc.idcurso
            left join curriculos cur on (ocp.idcurriculo = cur.idcurriculo) ';

        if ($this->idusuario) {
            $sql .= ' inner join usuarios_adm ua on ua.idusuario = ' . $this->idusuario . '
                            left join usuarios_adm_sindicatos uai on p.idsindicato = uai.idsindicato and uai.ativo = "S" and uai.idusuario = ua.idusuario ';
        } else if ($this->idvendedor) {
            $sql .= ' inner join vendedores v on v.idvendedor = ' . $this->idvendedor . '
                            inner join vendedores_sindicatos vi on p.idsindicato = vi.idsindicato and vi.ativo = "S" and v.idvendedor = vi.idvendedor ';
        }

        $sql .= ' where op.idoferta = ' . $this->id . ' and p.idescola = ' . intval($this->idescola) . ' and op.ativo = "S" ';

        if ($this->idusuario) {
            $sql .= ' and (ua.gestor_sindicato = "S" or uai.idusuario is not null) ';
        }

        //$sql .= ' limit 2 ';

        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            if ($linha['idcurso']) {
                $sql = 'select idcurriculo, nome from curriculos where ativo = "S" and idcurso = ' . $linha['idcurso'];
                $resultado_curriculos = $this->executaSql($sql);
                while ($curriculo = mysql_fetch_assoc($resultado_curriculos)) {
                    $linha['curriculos'][$curriculo['idcurriculo']] = $curriculo;
                }
            }
            $ofertas_cursos_escolas[$linha['idescola']]['escola'] = $linha['escola'];
            $ofertas_cursos_escolas[$linha['idescola']]['sindicato'] = $linha['sindicato'];
            $ofertas_cursos_escolas[$linha['idescola']]['cursos'][$linha['idcurso']]['curso_escola'] = $linha;
        }

        return $ofertas_cursos_escolas;
    }

    function ListarCursosEscolasMatricula()
    {
        $this->sql = "select
                        " . $this->campos . "
                      from
                        ofertas_cursos_escolas ocp
                        INNER JOIN escolas p ON (ocp.idescola = p.idescola AND p.ativo = 'S')
                        INNER JOIN cursos c ON (ocp.idcurso = c.idcurso AND c.ativo = 'S')
                        INNER JOIN ofertas o ON (ocp.idoferta = o.idoferta AND o.ativo = 'S')
                        INNER JOIN ofertas_escolas op ON (p.idescola = op.idescola AND o.idoferta = op.idoferta AND op.ativo = 'S')
                        LEFT JOIN curriculos cur ON (ocp.idcurriculo = cur.idcurriculo) ";

        if ($this->idusuario) {
            $this->sql .= " inner join usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
                                    left join usuarios_adm_sindicatos uai on p.idsindicato = uai.idsindicato AND uai.ativo = 'S' AND uai.idusuario = ua.idusuario ";
        } elseif ($this->idvendedor) {
            $this->sql .= " INNER JOIN vendedores_sindicatos vi ON (vi.idsindicato = p.idsindicato AND vi.ativo = 'S')
                INNER JOIN vendedores_escolas ve ON (ve.idescola = p.idescola AND ve.ativo = 'S')
                INNER JOIN vendedores v ON (v.idvendedor = " . $this->idvendedor . " AND v.idvendedor = vi.idvendedor AND v.idvendedor = ve.idvendedor)";
        }

        $this->sql .= "where
                        ocp.ativo = 'S' AND
                        o.idoferta = " . $this->id;

        if ($this->url[0] == 'atendente') {
            $this->sql .= ' AND p.acesso_bloqueado = "N"';
        }

        if ($this->idescola) {
            $this->sql .= " AND p.idescola = " . $this->idescola;
        }

        if ($this->idusuario) {
            $this->sql .= " AND (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ";
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
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->groupby = "ocp.idoferta_curso_escola";
        return $this->retornarLinhas();
    }

    function retornarCursoEscola($idoferta_curso_escola)
    {
        $sql = 'select o.nome as oferta, o.idoferta, c.nome as curso, c.idcurso, p.nome_fantasia, p.idescola,  p.idsindicato, oci.limite
                from ofertas_cursos_escolas ocp
                    inner join ofertas o on ocp.idoferta = o.idoferta and o.ativo = "S"
                    inner join cursos c on ocp.idcurso = c.idcurso and c.ativo = "S"
                    inner join escolas p on ocp.idescola = p.idescola and p.ativo = "S"
                    left join ofertas_cursos_sindicatos oci on oci.idsindicato = p.idsindicato and oci.idcurso = c.idcurso and oci.idoferta = o.idoferta and oci.ativo = "S"
                where ocp.ativo = "S" and ocp.idoferta_curso_escola = ' . $idoferta_curso_escola;
        return $this->retornarLinha($sql);
    }

    function retornarDadosTurma($idturma)
    {
        $sql = 'select * from ofertas_turmas where idturma = ' . $idturma . ' ';
        return $this->retornarLinha($sql);
    }

    function verificarMatriculasCursoEscola($idoferta, $idcurso, $idescola, $idturma)
    {
        if (!(int)$idoferta || !(int)$idcurso || !(int)$idescola || !(int)$idturma) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'erro_parametros_incompletos';
            return $retorno;
        }

        $sql_sindicato = 'select idsindicato from escolas where idescola = ' . $idescola;
        $sindicato = $this->retornarLinha($sql_sindicato);

        $sql = 'select count(1) as total, (select oci.limite
                                                from ofertas_cursos_escolas ocp
                                                inner join escolas p on ocp.idescola = p.idescola
                                                inner join ofertas_cursos_sindicatos oci
                                                    on ocp.idoferta = oci.idoferta and ocp.idcurso = oci.idcurso
                                                    and p.idsindicato = oci.idsindicato and oci.ativo = "S"
                                            where
                                                ocp.idoferta = ' . $idoferta . ' and ocp.idcurso = ' . $idcurso . '
                                                and p.idescola = ' . $idescola . ' and ocp.ativo = "S") as maximo_turma
                from matriculas
                where idoferta = ' . $idoferta . ' and idcurso = ' . $idcurso . ' and idsindicato = ' . $sindicato['idsindicato'] . ' and idturma = ' . $idturma . ' and ativo = "S" ';
        $resultado = $this->executaSql($sql);
        if (!$resultado) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'erro_verificar_matriculas';
            return $retorno;
        }
        $matriculas = mysql_fetch_assoc($resultado);
        return $matriculas;
    }

    function ListarMatriculasCursosEscolasTurmas()
    {
        $this->retorno = array();
        $this->sql = "select
                        idescola, idcurso, idturma, count(idmatricula) as matriculas
                      from
                        matriculas
                      where
                        idoferta = " . $this->id . " AND ativo ='S' group by idescola, idcurso, idturma";
        $seleciona = $this->executaSql($this->sql);
        while ($turma = mysql_fetch_assoc($seleciona)) {
            $this->retorno[$turma["idescola"]][$turma["idcurso"]][$turma["idturma"]] = $turma["matriculas"];
        }
        return $this->retorno;
    }


    function ConfiguracaoEscolasCursos()
    {
        $this->retorno = array();
        $this->sql = "select
                        ocp.*,
                        c.nome as curriculo
                      from
                        ofertas_cursos_escolas ocp
                        INNER JOIN curriculos c ON (c.idcurriculo = ocp.idcurriculo)
                      where
                        ocp.idoferta = " . $this->id;
        $seleciona = $this->executaSql($this->sql);
        while ($config = mysql_fetch_assoc($seleciona)) {
            $this->retorno[$config["idescola"]][$config["idcurso"]] = $config;
        }
        return $this->retorno;
    }

    function retornarTotalMatriculasPorCursoEscola($idoferta, $idcurso, $idescola, $idturma)
    {

        $sql_sindicato = 'select idsindicato from escolas where idescola = ' . $idescola;
        $sindicato = $this->retornarLinha($sql_sindicato);

        $sql = 'select count(1) as total
                    from matriculas
                    where idoferta = ' . $idoferta . ' and
                          idcurso = ' . $idcurso . ' and
                          idsindicato = ' . $sindicato['idsindicato'] . ' and
                          idturma = ' . $idturma . ' and ativo = "S"';
        $resultado = $this->retornarLinha($sql);
        return $resultado['total'];
    }

    function verificarAcessoAlunoCurso($idmatricula)
    {

        $sql = 'SELECT
                ocp.*, m.data_cad as data_matricula, m.data_prolongada
            FROM
                matriculas m
            INNER JOIN
                ofertas_cursos_escolas ocp ON (ocp.ativo = "S" AND ocp.idcurso = m.idcurso AND ocp.idescola = m.idescola AND ocp.idoferta = m.idoferta)
            WHERE
                m.idmatricula = ' . $idmatricula;
        $retorno = $this->retornarLinha($sql);

        if (!count($retorno)) {
            return false;
        } else {
            $data_atual = date('Y-m-d');
            if ($retorno['data_inicio_ava'] && $retorno['data_inicio_ava'] > $data_atual) {
                return false;
            } else if ($retorno['data_prolongada']) { // Data da matricula tem prioridade sobre as outras
                if ($data_atual > $retorno['data_prolongada']) {
                    return false;
                }
            } else if ($retorno['data_limite_ava'] && $retorno['data_limite_ava'] < $data_atual) {
                return false;
            } else if ($retorno['dias_para_ava']) {
                $data_limite_acesso = new DateTime(formataData($retorno['data_matricula'], 'en', 0));
                $data_limite_acesso->modify('+' . $retorno['dias_para_ava'] . ' days');
                if ($data_limite_acesso->format('Y-m-d') < $data_atual) {
                    return false;
                }
            }
        }
        /*
        // Data da matricula tem prioridade sobre as outras
        if ($retorno['data_prolongada']) {
            if ($data_atual <= $retorno['data_prolongada']) {
                return true;
            } elseif ($data_atual >= $retorno['data_prolongada']) {
                return false;
            }
        }


        if ($retorno['data_inicio_ava'] && $retorno['data_inicio_ava'] > $data_atual) {
            return false;
        } else if ($retorno['data_limite_ava'] && $retorno['data_limite_ava'] < $data_atual) {
            return false;
        } else if ($retorno['dias_para_ava']) {
            $data_limite_acesso = new DateTime(formataData($retorno['data_matricula'], 'en', 0));
            $data_limite_acesso->modify('+' . $retorno['dias_para_ava'] . ' days');
            if ($data_limite_acesso->format('Y-m-d') < $data_atual) {
                return false;
            }
        }
        */

        return true;
    }

    function ListarCurriculosAvas()
    {

        $sql = '
            select
                ocp.idoferta_curso_escola,
                cur.idcurriculo,
                cur.nome as curriculo,
                cbd.iddisciplina,
                cbd.idbloco_disciplina,
                oca.idava,
                d.nome as disciplina,
                cursos.nome as curso
            from ofertas_cursos_escolas ocp
            inner join escolas p on ocp.idescola = p.idescola
            inner join ofertas_escolas op on p.idescola = op.idescola and ocp.idoferta = op.idoferta and op.ativo = "S"
            inner join curriculos cur on ocp.idcurriculo = cur.idcurriculo and cur.ativo = "S"
            inner join cursos on cursos.idcurso = cur.idcurso and cursos.ativo = "S"
            inner join curriculos_blocos cb on cur.idcurriculo = cb.idcurriculo and cb.ativo = "S"
            inner join curriculos_blocos_disciplinas cbd on cb.idbloco = cbd.idbloco and cbd.ativo = "S"
            inner join disciplinas d on cbd.iddisciplina = d.iddisciplina
            left join ofertas_curriculos_avas oca on oca.ativo = "S" and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cur.idcurriculo and oca.idoferta = ocp.idoferta  ';

        if ($this->idusuario) {
            $sql .= ' inner join usuarios_adm ua on ua.idusuario = ' . $this->idusuario . '
                            left join usuarios_adm_sindicatos uai on p.idsindicato = uai.idsindicato and uai.ativo = "S" and uai.idusuario = ua.idusuario ';
        } else if ($this->idvendedor) {
            $sql .= ' inner join vendedores v on v.idvendedor = ' . $this->idvendedor . '
                            inner join vendedores_sindicatos vi on p.idsindicato = vi.idsindicato and vi.ativo = "S" and v.idvendedor = vi.idvendedor ';
        }

        $sql .= ' where ocp.ativo = "S" and ocp.idoferta = ' . $this->id;

        if ($this->idusuario) {
            $sql .= ' and (ua.gestor_sindicato = "S" or uai.idusuario is not null) ';
        }

        $sql .= ' group by cur.idcurriculo, cbd.iddisciplina  ';

        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {

            $sql = '
                select
                    a.idava, a.nome
                from
                    avas a
                    inner join avas_disciplinas ad on (a.idava = ad.idava)
                where
                    a.ativo = "S" and
                    ad.ativo = "S" and
                    ad.iddisciplina = ' . $linha['iddisciplina'] . '
                group by a.idava';
            $resultado_curriculos = $this->executaSql($sql);
            while ($ava = mysql_fetch_assoc($resultado_curriculos)) {
                $linha['avas'][$ava['idava']] = $ava;
            }

            $curriculos[$linha['idcurriculo']]['curriculo'] = $linha['curriculo'];
            $curriculos[$linha['idcurriculo']]['curso'] = $linha['curso'];
            $curriculos[$linha['idcurriculo']]['disciplinas'][$linha['iddisciplina']] = $linha;
        }

        return $curriculos;
    }

    function cadastrarCurriculoDisciplinaAva()
    { //print_r2($this->post,true);
        mysql_query('START TRANSACTION');
        foreach ($this->post['curriculos'] as $idcurriculo => $curriculo) {
            foreach ($curriculo['disciplinas'] as $iddisciplina => $disciplina) {

                if (!$idcurriculo && !$iddisciplina) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_campos_obrigatorios';
                    return $this->retorno;
                }

                $sql = 'select * from ofertas_curriculos_avas
                        where iddisciplina = ' . $iddisciplina . ' and idcurriculo = ' . $idcurriculo . ' and idoferta = ' . $this->url[3] . ' and ativo = "S" ';
                $resultado = $this->executaSql($sql);
                if (!$resultado) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_consultar_ofertas_curriculos_avas';
                    return $this->retorno;
                }
                $existe_cadastro = mysql_fetch_assoc($resultado);
                if ($existe_cadastro['idoferta_curriculo_ava']) {

                    if ($existe_cadastro['idava'] == $disciplina['idava']) {
                        continue;
                    }

                    $sql = 'update ofertas_curriculos_avas
                                set ativo = "S" ';
                    if ($disciplina['idava'])
                        $sql .= ', idava = ' . $disciplina['idava'] . '  ';
                    else
                        $sql .= ', idava = NULL ';
                    $sql .= ' where idoferta_curriculo_ava = ' . $existe_cadastro['idoferta_curriculo_ava'];

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 2;
                        $this->monitora_qual = $existe_cadastro['idoferta_curriculo_ava'];
                        $this->monitora_onde = 181;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_curriculos_avas';
                        return $this->retorno;
                    }
                } else {
                    $sql = 'insert into ofertas_curriculos_avas
                                set
                                    iddisciplina = ' . $iddisciplina . ',
                                    idcurriculo = ' . $idcurriculo . ',
                                    idoferta = ' . $this->url[3] . ',
                                    data_cad = now() ';
                    if ($disciplina['idava'])
                        $sql .= ', idava = ' . $disciplina['idava'] . '  ';

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 1;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 181;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_curriculos_avas';
                        return $this->retorno;
                    }
                }
            }
        }
        $this->retorno["sucesso"] = true;
        mysql_query('COMMIT');
        return $this->retorno;
    }

    function ListarCursosSindicatos()
    {

        $sql = '
            select
                op.idoferta_escola,
                oc.*,
                c.nome as curso,
                p.idescola,
                p.nome_fantasia as escola,
                i.nome_abreviado as sindicato,
                i.idsindicato,
                oci.limite
            from ofertas_escolas op
            inner join escolas p on op.idescola = p.idescola
            inner join sindicatos i on i.idsindicato = p.idsindicato
            inner join ofertas_cursos oc on oc.ativo = "S" and op.idoferta = oc.idoferta
            inner join cursos c on oc.idcurso = c.idcurso
            left join ofertas_cursos_sindicatos oci on oci.idcurso = oc.idcurso and oci.idsindicato = i.idsindicato and oci.idoferta = oc.idoferta and oci.ativo = "S"    ';

        if ($this->idusuario) {
            $sql .= ' inner join usuarios_adm ua on ua.idusuario = ' . $this->idusuario . '
                            left join usuarios_adm_sindicatos uai on p.idsindicato = uai.idsindicato and uai.ativo = "S" and uai.idusuario = ua.idusuario ';
        } else if ($this->idvendedor) {
            $sql .= ' inner join vendedores v on v.idvendedor = ' . $this->idvendedor . '
                            inner join vendedores_sindicatos vi on p.idsindicato = vi.idsindicato and vi.ativo = "S" and v.idvendedor = vi.idvendedor ';
        }

        $sql .= ' where op.ativo = "S" and op.idoferta = ' . $this->id;

        if ($this->idusuario) {
            $sql .= ' and (ua.gestor_sindicato = "S" or uai.idusuario is not null) ';
        }

        $sql .= ' group by i.idsindicato, c.idcurso ';

        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $ofertas_cursos_sindicatos[$linha['idsindicato']]['sindicato'] = $linha['sindicato'];
            $ofertas_cursos_sindicatos[$linha['idsindicato']]['cursos'][$linha['idcurso']]['curso_sindicato'] = $linha;
        }

        return $ofertas_cursos_sindicatos;
    }

    function cadastrarCursoSindicatoLimite()
    {
        mysql_query('START TRANSACTION');
        foreach ($this->post['sindicatos'] as $idsindicato => $sindicato) {
            foreach ($sindicato['cursos'] as $idcurso => $curso) {

                if (!$idsindicato && !$idcurso) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_campos_obrigatorios';
                    return $this->retorno;
                }

                $sql = 'select * from ofertas_cursos_sindicatos
                        where idcurso = ' . $idcurso . ' and idsindicato = ' . $idsindicato . ' and idoferta = ' . $this->url[3] . ' and ativo = "S" ';
                $resultado = $this->executaSql($sql);
                if (!$resultado) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_consultar_ofertas_cursos_sindicatos';
                    return $this->retorno;
                }
                $existe_cadastro = mysql_fetch_assoc($resultado);
                if ($existe_cadastro['idoferta_curso_sindicato']) {

                    if ($existe_cadastro['limite'] == $curso['limite']) {
                        continue;
                    }

                    $sql = 'update ofertas_cursos_sindicatos
                                set ativo = "S" ';
                    if ($curso['limite'] || $curso['limite'] === '0')
                        $sql .= ', limite = "' . $curso['limite'] . '" ';
                    else
                        $sql .= ', limite = NULL ';
                    $sql .= ' where idoferta_curso_sindicato = ' . $existe_cadastro['idoferta_curso_sindicato'];

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 2;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 189;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_cursos_sindicatos';
                        return $this->retorno;
                    }
                } else {
                    $sql = 'insert into ofertas_cursos_sindicatos
                                set
                                    idcurso = ' . $idcurso . ',
                                    idsindicato = ' . $idsindicato . ',
                                    idoferta = ' . $this->url[3] . ',
                                    data_cad = now() ';
                    if ($curso['limite'] || $curso['limite'] === '0')
                        $sql .= ', limite = "' . $curso['limite'] . '" ';
                    else
                        $sql .= ', limite = NULL ';

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 1;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 189;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_cursos_sindicatos';
                        return $this->retorno;
                    }
                }
            }
        }
        $this->retorno["sucesso"] = true;
        mysql_query('COMMIT');
        return $this->retorno;
    }

    function ListarTurmasSindicatos()
    {

        $sql = 'select
                    op.idoferta_escola,
                    ot.*,
                    ot.nome as turma,
                    p.idescola,
                    p.nome_fantasia as escola,
                    i.nome_abreviado as sindicato,
                    i.idsindicato,
                    oti.ignorar
                from
                    ofertas_escolas op
                    inner join escolas p on (op.idescola = p.idescola)
                    inner join sindicatos i on (i.idsindicato = p.idsindicato)
                    inner join ofertas_turmas ot on (op.idoferta = ot.idoferta and ot.ativo = "S" and ot.ativo_painel = "S")
                    left join ofertas_turmas_sindicatos oti on (ot.idturma = oti.idturma and i.idsindicato = oti.idsindicato and ot.idoferta = oti.idoferta and oti.ativo = "S")';

        if ($this->idusuario) {
            $sql .= ' inner join usuarios_adm ua on ua.idusuario = ' . $this->idusuario . '
                      left join usuarios_adm_sindicatos uai on p.idsindicato = uai.idsindicato and uai.ativo = "S" and uai.idusuario = ua.idusuario ';
        } else if ($this->idvendedor) {
            $sql .= ' inner join vendedores v on v.idvendedor = ' . $this->idvendedor . '
                      inner join vendedores_sindicatos vi on p.idsindicato = vi.idsindicato and vi.ativo = "S" and v.idvendedor = vi.idvendedor ';
        }

        $sql .= ' where op.ativo = "S" and op.idoferta = ' . $this->id;

        if ($this->idusuario) {
            $sql .= ' and (ua.gestor_sindicato = "S" or uai.idusuario is not null) ';
        }

        $sql .= ' group by i.idsindicato, ot.idturma';

        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $ofertas_turmas_sindicatos[$linha['idsindicato']]['sindicato'] = $linha['sindicato'];
            $ofertas_turmas_sindicatos[$linha['idsindicato']]['turmas'][$linha['idturma']]['turma_sindicato'] = $linha;
        }

        return $ofertas_turmas_sindicatos;
    }

    function salvarTurmasSindicatos()
    {
        mysql_query('START TRANSACTION');

        $sql = 'update ofertas_turmas_sindicatos set ignorar = "N" where idoferta = ' . $this->url[3] . ' and ativo = "S"';
        $resultado = $this->executaSql($sql);
        if (!$resultado) {
            mysql_query('ROLLBACK');
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_atualizar_ofertas_turmas_sindicatos';
            return $this->retorno;
        }

        foreach ($this->post['sindicatos'] as $idsindicato => $sindicato) {
            foreach ($sindicato['turmas'] as $idturma => $turma) {
                if (!$idsindicato && !$idturma) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_campos_obrigatorios';
                    return $this->retorno;
                }

                $sql = 'select * from ofertas_turmas_sindicatos where idturma = ' . $idturma . ' and idsindicato = ' . $idsindicato . ' and idoferta = ' . $this->url[3] . ' and ativo = "S" ';
                $resultado = $this->executaSql($sql);
                if (!$resultado) {
                    mysql_query('ROLLBACK');
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_consultar_ofertas_turmas_sindicatos';
                    return $this->retorno;
                }
                $existe_cadastro = mysql_fetch_assoc($resultado);
                if ($existe_cadastro['idoferta_turma_sindicato']) {

                    if ($existe_cadastro['ignorar'] == $turma['ignorar']) {
                        continue;
                    }

                    $sql = 'update ofertas_turmas_sindicatos
                                set ativo = "S" ';
                    if ($turma['ignorar'])
                        $sql .= ', ignorar = "' . $turma['ignorar'] . '" ';
                    else
                        $sql .= ', ignorar = NULL ';
                    $sql .= ' where idoferta_turma_sindicato = ' . $existe_cadastro['idoferta_turma_sindicato'];

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 2;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 189;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_turmas_sindicatos';
                        return $this->retorno;
                    }
                } else {
                    $sql = 'insert into ofertas_turmas_sindicatos
                                set
                                    idturma = ' . $idturma . ',
                                    idsindicato = ' . $idsindicato . ',
                                    idoferta = ' . $this->url[3] . ',
                                    data_cad = now() ';
                    if ($turma['ignorar'])
                        $sql .= ', ignorar = "' . $turma['ignorar'] . '" ';
                    else
                        $sql .= ', ignorar = NULL ';

                    $salvar = $this->executaSql($sql);
                    if ($salvar) {
                        $this->monitora_oque = 1;
                        $this->monitora_qual = mysql_insert_id();
                        $this->monitora_onde = 189;
                        $this->Monitora();
                    } else {
                        mysql_query('ROLLBACK');
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_ofertas_turmas_sindicatos';
                        return $this->retorno;
                    }
                }
            }
        }
        $this->retorno["sucesso"] = true;
        mysql_query('COMMIT');
        return $this->retorno;
    }

    private function retornarErros($erros)
    {
        $retorno['erro'] = true;
        $retorno['erros'] = $erros;
        return $retorno;
    }

    public function listarDadosCursosAssociados($idOferta)
    {
        if (!$idOferta) {
            return null;
        }
        $sql = '
            select
                oc.*, c.nome
            from
                ofertas_cursos oc
                inner join cursos c on oc.idcurso = c.idcurso
            where
                oc.ativo = "S" and
                oc.idoferta = ' . (int) $idOferta;
        $resultado = $this->executaSql($sql);
        $cursos = array();
        while ($linha = mysql_fetch_assoc($resultado)) {
            $cursos[] = $linha;
        }
        return $cursos;
    }

    public function clonar($idOferta, $dados)
    {
        $dados['idsituacao'] = $this->RetornarSituacaoInicial();
        if (!$idOferta || !$dados['idsituacao']) {
            return $this->retornarErros(array('dados_insuficientes'));
        }

        $erros = $this->Set('post', $dados)->BuscarErros();
        if ($erros) {
            return $this->retornarErros($erros);
        }

        $this->executaSql('START TRANSACTION');

        if (!$this->clonarDadosOferta($idOferta, $dados)) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_oferta'));
        }
        $idClone = mysql_insert_id();

        $cursos = $this->listarDadosCursosAssociados($idOferta);

        if (is_array($cursos) &&
            !$this->clonarDadosCursos($idOferta, $idClone, $cursos, $dados)
        ) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_cursos'));
        }

        if (!$this->clonarDadosEscolas($idOferta, $idClone)) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_escolas'));
        }

        if ($dados['turma'] && !$this->clonarTurma($idClone, $dados['turma'])) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_turma'));
        }

        if (!$this->clonarDadosCursosEscolas($idOferta, $idClone)) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_cursos_escolas'));
        }

        if (!$this->clonarDadosCursosSindicatos($idOferta, $idClone)) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_cursos_sindicatos'));
        }

        if (!$this->clonarDadosCurriculosAvas($idOferta, $idClone)) {
            $this->executaSql('ROLLBACK');
            return $this->retornarErros(array('erro_clonar_curriculos_avas'));
        }

        $this->executaSql('COMMIT');

        $this->monitora_oque = 7;
        $this->monitora_qual = $idClone;
        $this->monitora_onde = 10;
        $this->Monitora();

        $retorno['sucesso'] = true;
        $retorno['idoferta'] = $idClone;
        return $retorno;
    }

    private function clonarDadosOferta($idOferta, $dados)
    {
        return $this->executaSql('
            insert into
                ofertas
                (
                    idsituacao,
                    ativo_painel,
                    data_cad,
                    nome,
                    data_inicio_matricula,
                    data_fim_matricula,
                    modalidade
                )
            select
                ' . $dados['idsituacao'] . ',
                ativo_painel,
                NOW(),
                "' . mysql_real_escape_string($dados['nome']) . '",
                "' . formataData($dados['data_inicio_matricula'], 'en', 0) . '",
                "' . formataData($dados['data_fim_matricula'], 'en', 0) . '",
                modalidade
            from
                ofertas
            where
                idoferta = ' . (int) $idOferta
        );
    }

    private function clonarDadosCursos($idOferta, $idClone, $cursos, $dados)
    {
        $ofertaCursos = $dados['ofertas_cursos'];
        foreach ($cursos as $curso) {
            $data = 'NULL';
            if ($ofertaCursos[$curso['idoferta_curso']]) {
                $data = '"' . formataData($ofertaCursos[$curso['idoferta_curso']], 'en', 0) . '"';
            }

            $salvou = $this->executaSql('
                insert into
                    ofertas_cursos
                    (
                        ativo,
                        data_cad,
                        idoferta,
                        idcurso,
                        matricula_liberada,
                        data_inicio_aula,
                        certificado,
                        porcentagem_minima,
                        qtde_minima_dias,
                        porcentagem_minima_virtual,
                        dias_para_prova
                    )
                select
                    ativo,
                    NOW(),
                    ' . $idClone . ',
                    ' . $curso['idcurso'] . ',
                    matricula_liberada,
                    ' . $data . ',
                    certificado,
                    porcentagem_minima,
                    qtde_minima_dias,
                    porcentagem_minima_virtual,
                    dias_para_prova
                from
                    ofertas_cursos
                where
                    idoferta_curso = ' . (int) $curso['idoferta_curso']
            );
            if (!$salvou) {
                return false;
            }
        }
        return true;
    }

    private function clonarDadosEscolas($idOferta, $idClone)
    {
        return $this->executaSql('
            insert into
                ofertas_escolas
                (
                    ativo,
                    data_cad,
                    idoferta,
                    idescola
                )
            select
                ativo,
                NOW(),
                ' . $idClone . ',
                idescola
            from
                ofertas_escolas
            where
                idoferta = ' . (int) $idOferta
        );
    }

    private function clonarTurma($idClone, $nomeTurma)
    {
        return $this->executaSql('
            insert into
                ofertas_turmas
                (
                    data_cad,
                    idoferta,
                    nome,
                    situacao_turma
                )
                values
                (
                    NOW(),
                    ' . $idClone . ',
                    "' . mysql_real_escape_string($nomeTurma) . '",
                    1
                ) '
        );
    }

    private function clonarDadosCursosEscolas($idOferta, $idClone)
    {
        return $this->executaSql('
            insert into
                ofertas_cursos_escolas
                (
                    idoferta,
                    idcurso,
                    idescola,
                    ativo,
                    data_cad,
                    idcurriculo
                )
            select
                ' . $idClone . ',
                idcurso,
                idescola,
                ativo,
                NOW(),
                idcurriculo
            from
                ofertas_cursos_escolas
            where
                idoferta = ' . (int) $idOferta
        );
    }

    private function clonarDadosCursosSindicatos($idOferta, $idClone)
    {
        return $this->executaSql('
            insert into
                ofertas_cursos_sindicatos
                (
                    idoferta,
                    idcurso,
                    idsindicato,
                    ativo,
                    data_cad,
                    limite
                )
            select
                ' . $idClone . ',
                idcurso,
                idsindicato,
                ativo,
                NOW(),
                limite
            from
                ofertas_cursos_sindicatos
            where
                idoferta = ' . (int) $idOferta
        );
    }

    private function clonarDadosCurriculosAvas($idOferta, $idClone)
    {
        return $this->executaSql('
            insert into
                ofertas_curriculos_avas
                (
                    idoferta,
                    idcurriculo,
                    iddisciplina,
                    idava,
                    ativo,
                    data_cad
                )
            select
                ' . $idClone . ',
                idcurriculo,
                iddisciplina,
                idava,
                ativo,
                data_cad
            from
                ofertas_curriculos_avas
            where
                idoferta = ' . (int) $idOferta
        );
    }

    public function atualizaFolhaRegistro($idOfertaCurso)
    {
        if (!$this->post["porcentagem_minima_disciplinas"]) {
            $this->post["porcentagem_minima_disciplinas"] = "null";
        }

        if (!$this->post["gerar_quantidade_dias"]) {
            $this->post["gerar_quantidade_dias"] = "null";
        }

        $sql = "UPDATE
                  ofertas_cursos
                SET
                  idfolha = '{$this->post["idfolha"]}',
                  gerar_quantidade_dias = {$this->post["gerar_quantidade_dias"]} ,
                  porcentagem_minima_disciplinas = {$this->post["porcentagem_minima_disciplinas"]} ";

        $sql .= " WHERE idoferta_curso = $idOfertaCurso ";

        $salvar = $this->executaSql($sql);

        if ($salvar) {
            $this->monitora_qual = $idOfertaCurso;
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_onde = 11;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

}
