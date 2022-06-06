<?php

class AulasOnLine extends Core
{

    public function cadastrar()
    {
        if($this->idprofessor)
            $this->post['idprofessor'] = $this->idprofessor;
        else
            $this->post['idgestor'] = $this->idusuario;

        return $this->SalvarDados();
    }

    public function listarTodas()
    {
        $this->sql = "select ao.*, d.nome as disciplina, p.nome as professor
        from aulas_online ao inner join disciplinas d on d.iddisciplina = ao.iddisciplina
        inner join professores p on ao.idprofessor = p.idprofessor where ao.ativo_painel = 'S'";

        $this->aplicarFiltrosBasicos();
        //$this->ordem_campo = "ao.nome";
        $this->groupby = "ao.idaula";
        return $this->retornarLinhas();
    }

    public function listarTodasProfessor()
    {
        $this->sql = "select ao.*, d.nome as disciplina, p.nome as professor
        from aulas_online ao inner join disciplinas d on d.iddisciplina = ao.iddisciplina
        inner join professores p on ao.idprofessor = p.idprofessor
        where ao.idprofessor = '" . $this->idprofessor . "' and ao.ativo_painel = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "ao.idaula";
        return $this->retornarLinhas();
    }

    public function retornar()
    {
        $this->sql = "select " . $this->campos . " from aulas_online where idaula = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    public function remover()
    {
        $this->sql = 'UPDATE aulas_online SET ativo_painel = "N" WHERE idaula = ' . $this->post["remover"];
        if ($this->executaSql($this->sql)) {
            $retorno['id'] = $this->post["remover"];
            $retorno['sucesso'] = true;
        } else {
            $retorno['erro'] = true;
            $retorno['erros'][] = $this->sql;
            $retorno['erros'][] = $this->retornarErrorQuery();
        }
        return $retorno;
    }

    public function editar()
    {
        return $this->salvarDados();
    }

    public function verificaPermissaoProfessor($aula)
    {
        $this->sql = "SELECT * FROM aulas_online
        where idaula = '".$aula['idaula']."'
        AND idprofessor = '". $aula['idprofessor'] ."'
        AND ativo = true";

        if ($this->executaSql($this->sql)) {
            return true;
        } else {
            return false;
        }
    }

    function listarTodasAulasOnLine()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas a                    
                    inner join avas_aulas_online aao on (a.idava = aao.idava)
                    inner join aulas_online ao on (aao.idaula_online = ao.idaula)
                    inner join disciplinas d on (ao.iddisciplina = d.iddisciplina)
                  where
                    aao.ativo = 'S' and a.idava = " . intval($this->idava);
        $this->groupby = "aao.idavas_aulas_online";
        //print_r2($this->sql);die();
        return $this->retornarLinhas();
    }

}
