<?php

class Escola
{
    private $acessoBanco;
    public  $config;
    public  $idescola;

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function autenticar($email, $senha)
    {
        $sql = "SELECT
                    *
                FROM
                    escolas
                WHERE
                    email='{$email}' AND
                    senha='{$senha}'";

        $escola = $this->acessoBanco->retornarLinha($sql);

        if (empty($escola))
        {
            throw new \Exception("erro_senha_incorreta", 401);
        } else {
            if ($escola['ativo'] != 'S')
            {
                throw new \Exception("erro_cfc_desativado", 401);
            }

            if ($escola['ativo_painel'] != 'S')
            {
                throw new \Exception("erro_cfc_painel_desativado", 401);
            }

            if ($escola['acesso_bloqueado'] == 'S')
            {
                throw new \Exception("erro_cfc_bloqueado", 401);
            }

            return $escola;
        }
    }

    /**
     * @access public
     * @param string $cfc_responsavel
     * @return array
     */

    public function retornarEscola($cfc_responsavel)
    {
        if(!validaCPF($cfc_responsavel))
        {
            throw new InvalidArgumentException('cfc_responsavel_invalido');
        }
      $sql = "SELECT * FROM escolas WHERE responsavel_legal_cpf = $cfc_responsavel";

      return $this->acessoBanco->retornarLinha($sql);
    }

}
