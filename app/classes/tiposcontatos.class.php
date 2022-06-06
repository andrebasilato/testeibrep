<?php

class TiposContatos extends Core
{

    function ListarTodas()
    {
        $this->sql = "select " . $this->campos . " from tipos_contatos where ativo = 'S'";
        $this->aplicarFiltrosBasicos()->set('groupby', 'idtipo');
        return $this->retornarLinhas();
    }


    function Retornar()
    {
        $this->sql = "select " . $this->campos . " from tipos_contatos where ativo = 'S' and idtipo = '" . $this->id . "'";
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

}

