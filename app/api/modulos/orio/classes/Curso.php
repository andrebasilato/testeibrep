<?php

class Curso extends Core
{
    private $funcoesComuns;
    private $acessoBanco;
    public $campos = '*';
    public $id;

    public function __construct(\OrIO\FuncoesComuns $funcoesComuns)
    {
        $this->funcoesComuns = $funcoesComuns;
        $this->ignorarTratamentoErro = true;
    }

    public function listarTodas()
    {
        $this->sql = "SELECT ".$this->campos."
            FROM cursos c
        WHERE c.ativo = 'S' AND c.ativo_painel = 'S'";

        $this->aplicarFiltrosBasicos();


        $this->groupby = "c.idcurso";

        return $this->retornarLinhas();
    }

    public function retornar()
    {
        $sql = "SELECT ".$this->campos."
            FROM cursos c
        WHERE c.ativo = 'S' AND c.ativo_painel = 'S' AND c.idcurso = '".$this->id."' ";

        echo $sql;

        return $this->retornarLinha($sql);
    }

    public function retornarPorNome($nome)
    {
        $this->sql = "SELECT ".$this->campos."
            FROM cursos c
        WHERE c.ativo = 'S' AND c.ativo_painel = 'S' AND c.nome like '%".$nome."%' ";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idcurso";

        return $this->retornarLinhas();
    }
}
