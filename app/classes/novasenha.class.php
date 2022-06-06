<?php
class NovaSenha extends Core {

  var $modulo = null;
  var $id = null;
  var $hash = null;

  function Retornar() {
    $this->sql = "select
                    ".$this->campos."
                  from
                    solicitacoes_senhas ss";

    if($this->modulo == "gestor") {
      $this->sql .= " inner join usuarios_adm ua on (ss.id = ua.idusuario)";
    } elseif($this->modulo == "professor") {
      $this->sql .= " inner join professores p on (ss.id = p.idprofessor)";
    } elseif($this->modulo == "aluno") {
      $this->sql .= " inner join pessoas p on (ss.id = p.idpessoa)";
    } elseif($this->modulo == "vendedor") {
      $this->sql .= " inner join vendedores v on (ss.id = v.idvendedor)";
    } elseif($this->modulo == "escola") {
        $this->sql .= " inner join escolas e on (ss.id = e.idescola)";
    }

    $this->sql .= " where
                      ss.modulo = '".$this->modulo."' and
                      ss.id = '".$this->id."' and
                      ss.hash = '".$this->hash."'";
    return $this->retornarLinha($this->sql);
  }

  function alterarSenha() {
    $solicitacao = $this->Retornar();
    if($solicitacao["idsolicitacao_senha"] && $solicitacao["ativo"] == "S" && !$solicitacao["data_modificacao"]) {
      $this->executaSql("begin");
      $this->sql = "update solicitacoes_senhas set data_modificacao = now(), ativo = 'N' where idsolicitacao_senha = '".$solicitacao["idsolicitacao_senha"]."'";
      $modificou = $this->executaSql($this->sql);
      if($modificou) {
        if($this->modulo == "gestor") {
          $tabela = "usuarios_adm";
          $id = "idusuario";
        } elseif($this->modulo == "professor") {
          $tabela = "professores";
          $id = "idprofessor";
        } elseif($this->modulo == "aluno") {
          $tabela = "pessoas";
          $id = "idpessoa";
        } elseif($this->modulo == "vendedor") {
          $tabela = "vendedores";
          $id = "idvendedor";
        } elseif($this->modulo == "escola") {
            $tabela = "escolas";
            $id = "idescola";
        }

        $senha = senhaSegura($this->post["senha"],$this->config["chaveLogin"]);

        $this->sql = "update ".$tabela." set senha = '".$senha."', ultima_senha = now() where ".$id." = '".$solicitacao["id"]."'";
        $modificou = $this->executaSql($this->sql);
        if($modificou) {
          $this->executaSql("commit");
        } else {
          $this->executaSql("rollback");
        }
      }
    }
    return $modificou;
  }

}

?>
