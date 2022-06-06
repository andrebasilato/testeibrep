<?php
class Checklist extends Core
{
    public $layout_horizontal = false;

    public function listarTodas()
    {
        $this->sql = "SELECT {$this->campos} FROM checklists where ativo='S'";

        return $this->aplicarFiltrosBasicos()
                    ->set('groupby', 'idchecklist')
                    ->retornarLinhas();
    }


    public function retornar()
    {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                             checklists WHERE ativo='S' AND idchecklist='".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    public function cadastrar()
    {
        return $this->salvarDados();
    }

    public function modificar()
    {
        return $this->salvarDados();
    }

    public function remover()
    {
        return $this->removerDados();
    }

    public function listarOpcoes()
    {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                              checklists_opcoes WHERE idchecklist = '".$this->id."'AND ativo='S'";
        $this->aplicarFiltrosBasicos();
        $this->groupby = "idopcao";
        return $this->retornarLinhas();
    }

    public function retornaOpcoes($idchecklist)
    {
        $this->sql = "SELECT * FROM checklists_opcoes where ativo = 'S' AND idchecklist = ".$idchecklist;
        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idchecklist";
        $this->groupby = "idchecklist";
        $dados = $this->retornarLinhas();
        return $dados;
    }

    public function removerOpcao($idopcao, $idchecklist)
    {
        $this->sql = "UPDATE checklists_opcoes SET ativo='N' where idopcao = ".$idopcao." and idchecklist = ".$idchecklist;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 87;
            $this->monitora_oque = 3;
            $this->monitora_qual = $idopcao;
            $this->monitora();
        }

        return $this->retorno;
    }
}