<?php
/**
 * Class Turmas
 */
class Turmas extends Core
{
    /**
     * Pega dados de uma turma.
     *
     * @param $idturma
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getTurma($idturma)
    {
        if (!is_numeric($idturma)) {
            throw new InvalidArgumentException('O primeiro parâmetro tem que ser um valor numérico.');
        }

        $this->sql = 'SELECT * FROM ofertas_turmas WHERE idturma = ' . $idturma;

        return $this->retornarLinha($this->sql);
    }

    /**
     * @return array
     */
    public function retornar()
    {
        return $this->getTurma($this->get('id'));
    }

    /**
     * Lista todas as turmas
     *
     * @return array
     */
    public function listarTodas()
    {
        $this->sql = "SELECT " . $this->campos . " from ofertas o inner join ofertas_turmas ot on (o.idoferta=ot.idoferta) where o.ativo = 'S' and ot.ativo='S' ";
        //echo $this->sql;exit;
        $this->aplicarFiltrosBasicos();
        $this->groupby = "idturma";

        return $this->retornarLinhas();
    }
}