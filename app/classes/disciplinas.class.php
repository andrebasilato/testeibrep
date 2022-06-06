<?php
class Disciplinas extends Core
{

    function ListarTodas()
    {
        $this->sql = "SELECT " . $this->campos . " 
                    FROM disciplinas where ativo = 'S'";

        $this->aplicarFiltrosBasicos();
        $this->groupby = "iddisciplina";
        return $this->retornarLinhas();
    }


    function Retornar()
    {
        $this->sql = "SELECT  {$this->campos} 
                    FROM 
                        disciplinas WHERE ativo = 'S' AND 
                        iddisciplina = '{$this->id}'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
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

    function ListarCursosAssociados()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					cursos c
					INNER JOIN disciplinas_cursos dc 
                    ON (c.idcurso = dc.idcurso) 
				  WHERE 
					dc.ativo = 'S' AND 
					dc.iddisciplina = " . intval($this->id);

        $this->groupby = "dc.iddisciplina_curso";

        return $this->retornarLinhas();
    }

    function BuscarCurso()
    {
        $this->sql = "SELECT
					c.idcurso AS 'key', c.nome AS value 
				  FROM
					cursos c 
				  WHERE 
					 c.nome LIKE '%" . $_GET["tag"] . "%' AND
					 c.ativo = 'S' AND 
					 c.ativo_painel = 'S' AND 
					 NOT EXISTS (
                                    SELECT dc.iddisciplina 
                                    FROM 
                                        disciplinas_cursos dc 
                                    WHERE 
                                        dc.idcurso = c.idcurso AND 
                                        dc.iddisciplina = '" . $this->id . "' AND 
                                        dc.ativo = 'S'
                                )";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function AssociarCursos($iddisciplina, $arrayCursos)
    {
        foreach ($arrayCursos as $ind => $id) {
            $this->sql = "SELECT 
                                count(iddisciplina_curso) AS total, 
                                iddisciplina_curso 
                        FROM 
                            disciplinas_cursos 
                        WHERE 
                            iddisciplina = '" . (int)$iddisciplina . "' AND 
                            idcurso = '" . (int)$id . "' AND 
                            ativo = 'N' ";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "UPDATE 
                                    disciplinas_cursos 
                            SET 
                                ativo = 'S' 
                            WHERE 
                                iddisciplina_curso = " . $totalAss["iddisciplina_curso"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["iddisciplina_curso"];
            } else {
                $this->sql = "INSERT INTO 
                                    disciplinas_cursos 
                            SET 
                                ativo = 'S', 
                                data_cad = now(), 
                                iddisciplina = '" . (int)$iddisciplina . "', 
                                idcurso = '" . (int)$id . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }


            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 62;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function RemoverCursos()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update disciplinas_cursos set ativo = 'N' where iddisciplina_curso = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 62;
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

    function ListarDisciplinasPorCurso($idcurso)
    {
        $this->limite = "-1";
        $this->ordem = "asc";
        $this->ordem_campo = "nome";
        $this->campos = "d.*";

        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					disciplinas_cursos dc
					INNER JOIN disciplinas d 
                    ON (dc.iddisciplina = d.iddisciplina) 
					INNER JOIN cursos c 
                    ON (dc.idcurso = c.idcurso) 
				  WHERE 
					dc.ativo = 'S' AND 
					c.idcurso = " . intval($idcurso) . "
				  GROUP BY 
					dc.iddisciplina";

        return $this->retornarLinhas();
    }

    function listarTotalDisciplinas()
    {
        $this->sql = "SELECT
						COUNT( d.iddisciplina ) AS total
					  FROM 
					  	disciplinas d
					  WHERE 
					  	d.ativo =  'S' ";

        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }


    function ListarPerguntasAssociados()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					perguntas p
					INNER JOIN disciplinas_perguntas dp 
                    ON (p.idpergunta = dp.idpergunta) 
				  WHERE 
					dp.ativo = 'S' AND 
					dp.iddisciplina = " . intval($this->id);

        $this->groupby = "dp.iddisciplina_pergunta";

        return $this->retornarLinhas();
    }

    function BuscarPergunta()
    {
        $this->sql = "SELECT
					p.idpergunta AS 'key', p.nome AS value 
                  FROM
					perguntas p 
				  WHERE 
					 p.nome like '%" . $_GET["tag"] . "%' AND
					 p.ativo = 'S' AND 
					 p.ativo_painel = 'S' AND 
					 NOT EXISTS (
                                    SELECT dp.iddisciplina 
                                    FROM 
                                        disciplinas_perguntas dp 
                                    WHERE 
                                        dp.idpergunta = p.idpergunta AND 
                                        dp.iddisciplina = '" . $this->id . "' AND 
                                        dp.ativo = 'S'
                                )";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function AssociarPerguntas($iddisciplina, $arrayPerguntas)
    {
        foreach ($arrayPerguntas as $ind => $id) {
            $this->sql = "SELECT 
                                count(iddisciplina_pergunta) AS total, 
                                iddisciplina_pergunta 
                        FROM 
                            disciplinas_perguntas 
                        WHERE 
                            iddisciplina = '" . (int)$iddisciplina . "' AND 
                            idpergunta = '" . (int)$id . "' AND 
                            ativo = 'N' ";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "UPDATE 
                                disciplinas_perguntas 
                            SET 
                                ativo = 'S' 
                            WHERE 
                                iddisciplina_pergunta = " . $totalAss["iddisciplina_pergunta"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["iddisciplina_pergunta"];
            } else {
                $this->sql = "INSERT INTO 
                                disciplinas_perguntas 
                            SET 
                                ativo = 'S', 
                                data_cad = now(), 
                                iddisciplina = '" . (int)$iddisciplina . "', 
                                idpergunta = '" . (int)$id . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }


            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 118;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function RemoverPerguntas()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "UPDATE 
                            disciplinas_perguntas 
                        SET 
                            ativo = 'N' 
                        WHERE 
                            iddisciplina_pergunta = " . (int)$this->post["remover"];
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 118;
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


}