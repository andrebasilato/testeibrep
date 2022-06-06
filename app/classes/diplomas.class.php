<?php
/**
 * Diplomas
 */
class Diplomas extends Core
{
    const CURRENT_TABLE = 'diplomas';
    
    public function listarTodas()
    {
        $this->sql = sprintF(
            'SELECT %s FROM %s WHERE ativo = "S"',
            $this->campos,
            self::CURRENT_TABLE
        );
        
        $this->aplicarFiltrosBasicos();
        $this->groupby = "iddiploma";
        return $this->retornarLinhas();
    }


    public function retornar()
    {
        $this->sql = "select " . $this->campos . " from diplomas where ativo = 'S' and iddiploma = '" . $this->id . "'";
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
}