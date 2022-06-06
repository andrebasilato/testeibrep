<?php

class Areas extends Core {

    public function listarTodas()
    {
        $this->sql = sprintf('SELECT %s FROM areas WHERE ativo = "S"', $this->campos);
        return $this->aplicarFiltrosBasicos()->set('groupby', 'idarea')->retornarLinhas();
    }

    public function retornar()
    {
        $this->sql = "select " . $this->campos . " from areas where ativo = 'S' and idarea = '" . $this->id . "'";
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

    public function listarTotalAreas()
    {
        $this->sql = "SELECT COUNT(a.idarea) AS total FROM areas a WHERE a.ativo = 'S'";
        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }
}