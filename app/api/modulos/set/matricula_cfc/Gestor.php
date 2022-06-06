<?php


class Gestor
{
    private $acessoBanco;

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    /**
     * @access public
     * @param string $email
     * @param string $senha
     * @return array
     */

    public function autenticar($email, $senha)
    {
        $sql = "SELECT
                    idusuario, ativo, ativo_login
                FROM
                    usuarios_adm
                WHERE
                    email='{$email}' AND
                    senha='{$senha}'";

        $gestor = $this->acessoBanco->retornarLinha($sql);

        if (empty($gestor)) {
            throw new \Exception("erro_dados_incorreta", 401);
        }

        if ($gestor['ativo'] != 'S' || $gestor['ativo_login'] != 'S') {
            throw new \Exception("usuario_desativado", 401);
        }
        return $gestor;

    }

}
