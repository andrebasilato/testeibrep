<?php

class Cursos extends Core
{
    public function listarTodas()
    {
        $this->sql = "SELECT 
                                                $this->campos 
                                            FROM 
                                                cursos c 
                                                LEFT JOIN cfcs_valores_cursos cvc
                                                        ON (cvc.idcurso = c.idcurso AND cvc.ativo = 'S' ";

        if ($this->idcfc) {
            $this->sql .= " AND cvc.idcfc = $this->idcfc ) ";
        } else {
            $this->sql .= " ) ";
        }

        $this->sql .= " WHERE c.ativo = 'S'";


        if ($this->idusuario)
            $this->sql .= " and (     
                  (
                          (     select count(1) 
                                  from cursos_sindicatos ci
                                  where ci.idcurso = c.idcurso and ci.ativo = 'S'                 
                          ) = 0
                  )
                  or
                  (     select ua.idusuario 
                          from usuarios_adm ua
                                  left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                  left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
                          where ua.idusuario = " . $this->idusuario . "                                                                            
                                  and (     ua.gestor_sindicato = 'S' 
                                                  or 
                                                  (     ci.idcurso = c.idcurso and 
                                                          uai.idusuario is not null and 
                                                          ci.idsindicato is not null
                                                  ) 
                                          )
                          limit 1
                  ) is not null
          )             ";
        if ($_GET['q']['2|tipo']) {
            $_GET['q']['2|tipo'] = substr($_GET['q']['2|tipo'], 0, 3);
        }
        $this->aplicarFiltrosBasicos();

        $this->mantem_groupby = true;
        $this->distinct = "DISTINCT ";
        $this->groupby = "c.idcurso";
        $retornar = $this->retornarLinhas();

        return $retornar;
    }


    public function retornar($trazerValorCurso = false, $idcurso = null)
    {
        if($idcurso !== null)
            $this->set('id', $idcurso);
        $this->sql = 'SELECT ' . $this->campos . ' FROM cursos c';

        if ($trazerValorCurso) {
            $cfc = '';
            if ($this->idcfc) {
                $cfc = " AND cvc.idcfc = {$this->idcfc}";
            }

            $this->sql .= ' LEFT JOIN cfcs_valores_cursos cvc ON (cvc.idcurso = c.idcurso AND cvc.ativo = "S" ' . $cfc . ')
                         LEFT JOIN sindicatos_valores_cursos svc ON (svc.idcurso = c.idcurso AND svc.ativo = "S")';
        }
        $this->sql .= " WHERE c.ativo = 'S'";
        if($this->id){
            $this->sql .= " AND c.idcurso = {$this->id}";
        }
        if ($this->idusuario) {
            $this->sql .= " AND (     
                        (
                                (     select count(1) 
                                        from cursos_sindicatos ci
                                        where ci.idcurso = c.idcurso and ci.ativo = 'S'                 
                                ) = 0
                        )
                        or                    
                        (     select ua.idusuario 
                                from usuarios_adm ua
                                        left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                        left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
                                where ua.idusuario = " . $this->idusuario . "                                                                            
                                        and (     ua.gestor_sindicato = 'S' 
                                                        or 
                                                        (     ci.idcurso = c.idcurso and 
                                                                uai.idusuario is not null and 
                                                                ci.idsindicato is not null
                                                        ) 
                                                )
                                limit 1
                        ) is not null
                )";
        }

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

    public function ListarAreasAssociadas()
    {
        $this->sql = "SELECT 
                                        " . $this->campos . " 
                                    FROM
                                        areas a
                                        INNER JOIN cursos_areas ca ON (a.idarea = ca.idarea) 
                                    WHERE 
                                        ca.ativo = 'S' and 
                                        ca.idcurso = " . intval($this->id);

        $this->groupby = "ca.idcurso_area";

        return $this->retornarLinhas();
    }

    public function BuscarArea()
    {
        $this->sql = "select 
                                        a.idarea as 'key', a.nome as value 
                                    from
                                        areas a 
                                    where 
                                         a.nome like '%" . $_GET["tag"] . "%' AND 
                                         a.ativo = 'S' AND 
                                         a.ativo_painel = 'S' AND 
                                         NOT EXISTS (SELECT ca.idcurso FROM cursos_areas ca WHERE ca.idarea = a.idarea AND ca.idcurso = '" . $this->id . "' AND ca.ativo = 'S')";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    public function AssociarAreas($idcurso, $arrayAreas)
    {
        foreach ($arrayAreas as $ind => $id) {

            $this->sql = "select count(idcurso_area) as total, idcurso_area from cursos_areas where idcurso = '" . intval($idcurso) . "' and idarea = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update cursos_areas set ativo = 'S' where idcurso_area = " . $totalAss["idcurso_area"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idcurso_area"];
            } else {
                $this->sql = "insert into cursos_areas set ativo = 'S', data_cad = now(), idcurso = '" . intval($idcurso) . "', idarea = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 9;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function RemoverAreas()
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
            $this->sql = "update cursos_areas set ativo = 'N' where idcurso_area = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 9;
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

    public function ListarSindicatosAssociadas()
    {
        $this->sql = "SELECT 
                                        " . $this->campos . " 
                                    FROM
                                        sindicatos i
                                        INNER JOIN cursos_sindicatos ci ON (i.idsindicato = ci.idsindicato)
                                        inner join usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
                                        left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
                                    WHERE 
                                        (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
                                        ci.ativo = 'S' and 
                                        ci.idcurso = " . intval($this->id);

        $this->groupby = "ci.idcurso_sindicato";

        return $this->retornarLinhas();
    }

    public function BuscarSindicato()
    {
        $this->sql = "select 
                                        i.idsindicato as 'key', i.nome as value 
                                    from
                                        sindicatos i
                                        inner join usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
                                        left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
                                    where 
                                         (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
                                         i.nome like '%" . $_GET["tag"] . "%' AND 
                                         i.ativo = 'S' AND 
                                         i.ativo_painel = 'S' AND 
                                         NOT EXISTS (SELECT ci.idcurso FROM cursos_sindicatos ci WHERE ci.idsindicato = i.idsindicato AND ci.idcurso = '" . $this->id . "' AND ci.ativo = 'S')";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    public function AssociarSindicato($idcurso, $arraySindicatos)
    {
        foreach ($arraySindicatos as $ind => $id) {

            $this->sql = "select count(idcurso_sindicato) as total, idcurso_sindicato from cursos_sindicatos where idcurso = '" . intval($idcurso) . "' and idsindicato = '" . intval($id) . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update cursos_sindicatos set ativo = 'S' where idcurso_sindicato = " . $totalAss["idcurso_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idcurso_sindicato"];
            } else {
                $this->sql = "insert into cursos_sindicatos set ativo = 'S', data_cad = now(), idcurso = '" . intval($idcurso) . "', idsindicato = '" . intval($id) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 66;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function RemoverSindicato()
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
            $this->sql = "update cursos_sindicatos set ativo = 'N' where idcurso_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 66;
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

    //ALTERANDO CORPO DO EMAIL---------------------
    public function alterarEmailBoasVindas()
    {
        $this->sql = "SELECT email_boas_vindas, sms_boas_vindas FROM cursos WHERE idcurso = '" . $this->id . "'";
        $this->linha_antiga = $this->retornarLinha($this->sql);

        $this->sql = "update cursos set email_boas_vindas = '" . $this->post["corpo_email"] . "', sms_boas_vindas = '" . $this->post["corpo_sms"] . "'    where idcurso = '" . $this->id . "'";
        $this->query = $this->executaSql($this->sql);

        $this->sql = "SELECT email_boas_vindas, sms_boas_vindas FROM cursos WHERE idcurso = '" . $this->id . "'";
        $linha_nova = $this->retornarLinha($this->sql);

        if ($this->query) {
            $this->retorno["sucesso"] = true;
            if ($this->linha_antiga) {
                $this->monitora_oque = 2;
                $this->monitora_dadosantigos = $this->linha_antiga;
                $this->monitora_dadosnovos = $linha_nova;
            } else {
                $this->monitora_oque = 1;
            }
            $this->monitora_qual = $this->id;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    public function RetornarTodosCursos()
    {
        $this->sql = "SELECT 
                                                " . $this->campos . "
                                            FROM
                                                cursos c 
                                            WHERE c.ativo = 'S' AND c.ativo_painel = 'S'                                    
                                                and (     select ua.idusuario 
                                                                        from usuarios_adm ua
                                                                                left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                                                                left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = 'S'
                                                                        where ua.idusuario = " . $this->idusuario . "                                                                            
                                                                                and (     ua.gestor_sindicato = 'S' 
                                                                                                or 
                                                                                                (     ci.idcurso = c.idcurso and 
                                                                                                        uai.idusuario is not null and 
                                                                                                        ci.idsindicato is not null
                                                                                                ) 
                                                                                        )
                                                                        limit 1
                                                        ) is not null
                                            ";

        $this->limite = -1;
        $this->ordem = "ASC";
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.nome";
        return $this->retornarLinhas($this->sql);
    }

    public function listarTotalCursos($idsindicato = false, $idcurso = false)
    {
        $this->sql = "select 
                                                count(distinct c.idcurso) as total 
                                         from 
                                                cursos c 
                                                inner join cursos_sindicatos ci on (c.idcurso = ci.idcurso and ci.ativo = 'S')
                                         where 
                                                c.ativo = 'S' and
                                                c.ativo_painel = 'S'";
        if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and ci.idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";
        if ($idsindicato)
            $this->sql .= " and ci.idsindicato = " . $idsindicato;
        if ($idcurso)
            $this->sql .= " and c.idcurso = " . $idcurso;

        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }

    public function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    public function retornarCursosUsuario()
    {
        $this->sql = "select 
                                                c.idcurso, c.nome
                                         from 
                                                cursos c 
                                                inner join cursos_sindicatos ci on (c.idcurso = ci.idcurso and ci.ativo = 'S')
                                         where 
                                                c.ativo = 'S' and
                                                c.ativo_painel = 'S'";
        if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and ci.idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";

        $this->sql .= " group by c.idcurso, c.nome";

        $this->limit = -1;
        $this->ordem_campo = 'c.nome';
        $this->ordem = 'asc';
        return $this->retornarLinhas();
    }

    public function salvarCertificado()
    {
        foreach ($this->post['sindicato_curso'] as $idcurso_sindicato => $informacoes) {
            foreach ($informacoes as $informacao) {

                $this->sql = 'select idcurso_sindicato from cursos_sindicatos where idcurso_sindicato = ' . intval($idcurso_sindicato);
                $linhaAntiga = $this->retornarLinha($this->sql);

                if (!$informacao['certificado'])
                    $informacao['certificado'] = 'NULL';

                $this->sql = 'update 
                                                                cursos_sindicatos 
                                                            set 
                                                                idcertificado = ' . $informacao['certificado'] . ',
                                                                fundamentacao = "' . $informacao['fundamentacao'] . '",
                                                                fundamentacao_legal = "' . $informacao['fundamentacao_legal'] . '",
                                                                autorizacao = "' . $informacao['autorizacao'] . '",
                                                                perfil = "' . $informacao['perfil'] . '",
                                                                regulamento = "' . $informacao['regulamento'] . '"
                                                            where 
                                                                idcurso_sindicato = ' . intval($idcurso_sindicato);

                if ($this->executaSql($this->sql)) {
                    $this->sql = 'select idcurso_sindicato from cursos_sindicatos where idcurso_sindicato = ' . intval($idcurso_sindicato);
                    $linhaNova = $this->retornarLinha($this->sql);

                    $this->retorno["sucesso"] = true;

                    $this->monitora_oque = 2;
                    $this->monitora_onde = 66;
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->monitora_qual = intval($idcurso_sindicato);
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            }
        }

        return $this->retorno;
    }

    public function RetornarCursoSindicato()
    {
        $this->sql = 'SELECT
                                                        ' . $this->campos . '
                                                FROM
                                                        cursos_sindicatos ci 
                                                WHERE
                                                        ci.idcurso_sindicato = ' . $this->id . ' AND
                                                        ci.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function ModificarCursoSindicato()
    {
        if (!$this->post['certificado_ava'] || $this->post['certificado_ava'] == 'N') {
            $this->post['renach_obrigatorio'] = 'N';
        }

        return $this->SalvarDados();
    }

    public function retornarValoresCursoSindicato(
        $idSindicato,
        $campos = '*',
        $idCurso = false
    ) {
        $idSindicato = intval($idSindicato);

        $sql = "SELECT
                {$campos}
            FROM
                sindicatos_valores_cursos
            WHERE
                idsindicato = {$idSindicato}
                AND ativo = 'S'
        ";
        
        if ($idCurso) {
            $idCurso = intval($idCurso);

            $sql .= " AND idcurso = {$idCurso} ";

            return $this->retornarLinha($sql);
        }

        return $this->retornarLinhasArray($sql);
    }

    public function retornarValoresCursoCfc($idCfc, $campos = '*')
    {
        $idCfc = intval($idCfc);

        $sql = "SELECT
                {$campos}
            FROM
                cfcs_valores_cursos
            WHERE
                idcfc = {$idCfc}
                AND ativo = 'S'
        ";

        return $this->retornarLinhasArray($sql);
    }

    public function retornarValoresPorCursoCfc(
        $idCfc,
        $campos = '*',
        $idCurso = false
    ) {
        $idCfc = intval($idCfc);

        $sql = "SELECT
                {$campos}
            FROM
            cfcs_valores_cursos
            WHERE
                idcfc = {$idCfc}
                AND ativo = 'S'
        ";
        
        if ($idCurso) {
            $idCurso = intval($idCurso);
            $sql .= " AND idcurso = {$idCurso} ";   
        }

        return $this->retornarLinha($sql);
    }
}
