<?php

class Pessoa
{
    private $acessoBanco;
    public  $config;
    public  $id;
    public $campos = '*';

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function cadastrar($dados)
    {

        if($this->buscarEmail($dados)){
            $this->acessoBanco->executaSql('ROLLBACK');
          throw new Exception('e-mail_cadastrado');
        }

        if (!validaCPF($dados['cpf']))
        {
            $this->acessoBanco->executaSql('ROLLBACK');
           throw new InvalidArgumentException('erro_documento_invalido');
        }

        $estado = $this->retornarIdEstado($dados['uf']);
        if (empty($estado))
        {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new UnexpectedValueException('erro_estado_invalido');
        }

        $cidade = $this->retornarIdCidade($dados['municipio'], $estado['idestado']);
        if (empty($cidade))
        {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new UnexpectedValueException('erro_cidade_invalida');
        }


        $this->acessoBanco->config = $this->config;

        $senha = senhaSegura(str_replace(array(".", "-","/"),"", $dados['cpf']), $this->config["chaveLogin"]);

        $this->acessoBanco->sql = "INSERT INTO
                          pessoas
                      SET
                          data_cad = now(),
                          nome = '" . $dados['aluno'] . "',
                          documento_tipo = 'cpf',
                          ativo = 'S',
                          ativo_login = 'S',
                          rg = '".$dados['rg']."',
                          documento = '".$dados['cpf']."',
                          data_nasc = '" . formataData($dados['data_nascimento'], 'en', 0) . "',
                          email = '" . $dados['EMAIL'] . "',
                          telefone = '" . $dados['ddd_telefone'] . $dados['telefone'] . "',
                          cep = '" . $dados['cep'] . "',
                          endereco = '" . $dados['logradouro'] . "',
                          numero = '" . $dados['numero'] . "',
                          bairro = '" . $dados['bairro'] . "',
                          idestado = '" . $estado['idestado'] . "',
                          idcidade = '" . $cidade['idcidade'] . "',
                          senha = '" . $senha . "',
                          ato_punitivo = '" . $dados['renach'] ."'";

        $salvar = $this->acessoBanco->executaSql($this->acessoBanco->sql);
        if($salvar) {
            $retorno['idpessoa'] =  mysql_insert_id();
            $this->acessoBanco->idusuario = $this->idusuario;
            $this->acessoBanco->monitora_onde = 16;
            $this->acessoBanco->monitora_oque = 1;
            $this->acessoBanco->monitora_qual = $retorno['idpessoa'];
            $this->acessoBanco->Monitora();
        } else {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new Exception('erro_inserir_banco', 500);
        }
        return $retorno;
    }

    public function modificar($dados, $idpessoa)
    {

        if (!validaCPF($dados['cpf'])) {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new InvalidArgumentException('erro_documento_invalido');
        }

        if($this->buscarEmail($dados)){
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new Exception('e-mail_cadastrado');
        }

        $estado = $this->retornarIdEstado($dados['uf']);
        if (empty($estado)) {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new UnexpectedValueException('erro_estado_invalido');
        }

        $cidade = $this->retornarIdCidade($dados['municipio'], $estado['idestado']);
        if (empty($cidade)) {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new UnexpectedValueException('erro_cidade_invalida');
        }

        $this->acessoBanco->config = $this->config;

        $arrDados = array();
        if (!empty($dados['data_nascimento'])) $arrDados[] = "data_nasc = '" . formataData($dados['data_nascimento'], 'en', 0) . "'";
        if (!empty($dados['telefone'])) $arrDados[] = "telefone = '".$dados['ddd_telefone'].$dados['telefone']."'";
        if (!empty($dados['rg'])) $arrDados[] = "rg = '".$dados['rg']."'";
        if (!empty($dados['cep'])) $arrDados[] = "cep = '".$dados['cep']."'";
        if (!empty($dados['logradouro'])) $arrDados[] = "endereco = '".$dados['logradouro']."'";
        if (!empty($dados['numero'])) $arrDados[] = "numero = '".$dados['numero']."'";
        if (!empty($dados['bairro'])) $arrDados[] = "bairro = '".$dados['bairro']."'";
        if (!empty($dados['renach'])) $arrDados[] = "ato_punitivo = '".$dados['renach']."'";

        $condDados = (!empty($arrDados)) ? ','.implode(',', $arrDados) : '';
        $this->acessoBanco->sql = "UPDATE
                          pessoas
                      SET
                          ativo = 'S',
                          documento = '" . $dados['cpf'] . "',
                          nome = '" . $dados['aluno'] . "',
                          email = '" . $dados['email'] . "',
                          idestado = '" . $estado['idestado'] . "',
                          idcidade = '" . $cidade['idcidade'] . "',
                          idpais = '" . $pais['idpais'] . "'
                          ".$condDados."
                      WHERE idpessoa = '".$idpessoa."'";

        $salvar = $this->acessoBanco->executaSql($this->acessoBanco->sql);

        if ($salvar) {
            $retorno['idpessoa'] =  $idpessoa;
            $this->acessoBanco->idusuario = $this->idusuario;
            $this->acessoBanco->monitora_onde = 16;
            $this->acessoBanco->monitora_oque = 2;
            $this->acessoBanco->monitora_qual = $retorno['idpessoa'];
            $this->acessoBanco->Monitora();
        } else {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new Exception('erro_inserir_banco', 500);
        }
        return $retorno;
    }

    public function retornarIdPorCPF($cpf)
    {
        $this->acessoBanco->sql = "SELECT
                    idpessoa
                  FROM
                    pessoas
                  WHERE
                    documento = '" . $cpf . "'
                  ORDER BY idpessoa DESC
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdEstado($estado)
    {
        $this->acessoBanco->sql = "SELECT
                    idestado
                  FROM
                    estados
                  WHERE
                    upper(sigla) = '" . $estado . "'
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdCidade($cidade, $idestado)
    {
        $this->acessoBanco->sql = "SELECT
                    idcidade
                  FROM
                    cidades
                  WHERE
                    nome LIKE '" . $cidade . "'
                    AND idestado = {$idestado}
                  LIMIT 1";
        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdPais($pais)
    {
        $this->acessoBanco->sql = "SELECT
                    idpais
                  FROM
                    paises
                  WHERE
                    nome LIKE '" . $pais . "'
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function buscarEmail($dados){
      if (!empty($dados['idpessoa'])) $condIdpessoa = ' AND idpessoa != ' . $dados['idpessoa'];
      $this->acessoBanco->sql = "SELECT
                    email
                  FROM
                    pessoas
                  WHERE
                    email = '". $dados['email'] . "'
                    $condIdpessoa;";

      return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }
}
