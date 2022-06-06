<?php
class CategoriasTiraDuvidas extends Core
{
    function ListarTodas()
    {
        $this->sql = "SELECT 
                        {$this->campos} 
                    FROM 
                        avas_tiraduvidas_categorias 
                    WHERE 
                        ativo = 'S'";

        $this->aplicarFiltrosBasicos()->set('groupby', 'idcategoria');
        return $this->retornarLinhas();
    }

    function Retornar()
    {
        $this->sql = "SELECT 
                        {$this->campos} 
                    FROM 
                        avas_tiraduvidas_categorias 
                    WHERE 
                        ativo = 'S' AND 
                        idcategoria = '".$this->id."'";
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

    function BuscarProfessor()
    {
        $this->sql = "SELECT
                            p.idprofessor AS 'key', 
                            p.nome AS value
                          FROM
                            professores p
                          WHERE
                             p.nome like '%".$_GET["tag"]."%' AND
                             p.ativo = 'S' AND
                             p.ativo_login = 'S' AND
                             NOT EXISTS (SELECT
                                                atcp.idprofessor
                                            FROM
                                                avas_tiraduvidas_categorias_professores atcp
                                            WHERE
                                                atcp.idprofessor = p.idprofessor AND
                                                atcp.idcategoria = '".$this->id."' AND
                                                atcp.ativo = 'S'
                                        )";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    public function listarProfessoresCategorias($idava = NULL)
    {
        $_GET['q']['1|ativo_painel'] = "S";
        $categorias = $this->ListarTodas();
        foreach ($categorias as $categoria) {
            $this->Set('id',$categoria['idcategoria'])->Set('campos',$this->campos_professor);
            $categoriasResultado[$categoria['idcategoria']] = $categoria;
            $categoriasResultado[$categoria['idcategoria']]['professores'] = $this->ListarProfessoresAssociados($idava, true);
        }
        return $categoriasResultado;
    }

    function ListarProfessoresAssociados($idava = NULL, $somenteAtivosPainelAluno = false)
    {
        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            professores p
                            INNER JOIN avas_tiraduvidas_categorias_professores atcp ON (atcp.idprofessor = p.idprofessor)";
        if($idava) {
            $condAtivoPainelAluno = ($somenteAtivosPainelAluno) ? " AND p.ativo_painel_aluno = 'S'" : '';
            $this->sql .= " INNER JOIN professores_avas pa on (p.idprofessor = pa.idprofessor AND pa.ativo = 'S')
                        WHERE
                            pa.idava = ".$idava." AND
                            atcp.idcategoria = '".(int)$this->id."' AND
                            atcp.ativo = 'S' AND 
                            p.ativo = 'S' AND 
                            p.ativo_login = 'S'"
                            .$condAtivoPainelAluno;
        } else {
            $this->sql .= " 
                        WHERE
                            atcp.idcategoria = '".(int)$this->id."' AND
                            atcp.ativo = 'S' AND 
                            p.ativo = 'S' AND 
                            p.ativo_login = 'S'";
        }

        $this->groupby = "atcp.idcategoria_professor";
        return $this->retornarLinhas();
    }

    function AssociarProfessores()
    {
        foreach ($this->post["professores"] as $ind => $idprofessor) {
            $this->sql = "SELECT 
                                count(idcategoria_professor) as total, 
                                idcategoria_professor 
                            FROM
                                avas_tiraduvidas_categorias_professores 
                            WHERE
                                idcategoria = '".$this->id."' and 
                                idprofessor = '".intval($idprofessor)."'";
            $totalAssociado = $this->retornarLinha($this->sql);

            if ($totalAssociado["total"] > 0) {
                $this->sql = "UPDATE 
                                    avas_tiraduvidas_categorias_professores
                                SET
                                    ativo = 'S'
                                WHERE
                                    idcategoria_professor = '".$totalAssociado["idcategoria_professor"]."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idcategoria_professor"];
            } else {
                $this->sql = "INSERT INTO
                                    avas_tiraduvidas_categorias_professores
                                SET
                                    ativo = 'S',
                                    data_cad = NOW(),
                                    idcategoria = '".$this->id."',
                                    idprofessor = '".intval($idprofessor)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 230;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

        }
        return $this->retorno;
    }

    function DesassociarProfessores()
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
            $this->sql = "UPDATE avas_tiraduvidas_categorias_professores SET ativo = 'N' WHERE idcategoria_professor = '".intval($this->post["remover"])."'";
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 230;
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