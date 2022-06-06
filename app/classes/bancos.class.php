<?php
class Bancos extends Core {

  function ListarTodas() {
    $this->sql = "select ".$this->campos." from bancos where ativo = 'S'";

    $this->aplicarFiltrosBasicos()
        ->set('groupby', 'idbanco');
    return $this->retornarLinhas();
  }


  function Retornar() {
    $this->sql = "select ".$this->campos." from bancos where ativo = 'S' and idbanco = '".$this->id."'";
    return $this->retornarLinha($this->sql);
  }

  function Cadastrar() {
    return $this->SalvarDados();
  }

  function Modificar() {
    return $this->SalvarDados();
  }

  function Remover() {
    return $this->RemoverDados();
  }

}

?>