<?php
class Tipos_Notas extends Core
{

    function ListarTodas()
    {
        $this->sql = "SELECT {$this->campos} FROM matriculas_notas_tipos WHERE ativo = 'S'";

        $this->aplicarFiltrosBasicos()->set('groupby', 'idtipo');
        return $this->retornarLinhas();
    }


    function Retornar()
    {
        $this->sql = "select " . $this->campos . " from matriculas_notas_tipos where ativo = 'S' and idtipo = '" . $this->id . "'";
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

    function retornarTiposPorCurriculo($idcurriculo)
    {
        $sql = 'select t.*
				from matriculas_notas_tipos t
				inner join curriculos_notas_tipos cnt on t.idtipo = cnt.idtipo and cnt.ativo = "S"
			where t.ativo = "S" and t.ativo_painel = "S" and cnt.idcurriculo = "' . $idcurriculo . '"';
        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

}
