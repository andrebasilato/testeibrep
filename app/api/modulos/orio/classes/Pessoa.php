<?php

class Pessoa extends Core
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
        $this->sql = "SELECT
                    " . $this->campos . "
                  FROM
                    pessoas p
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa on (p.idpais = pa.idpais)
                  WHERE
                    p.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idpessoa";

        return $this->retornarLinhas();
    }

    public function listarAlunosTurma($idPessoa)
    {

        $this->sql = "SELECT
                    " . $this->campos . "
                  FROM
                    pessoas p
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa on (p.idpais = pa.idpais)
                  WHERE
                    p.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idpessoa";

        return $this->retornarLinhas();
    }

    public function cadastrar($dados)
    {
        if (!$dados['documentoaluno'] || !$dados['nome'] || !$dados['email'] || !$dados['celular']) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }

        if($this->buscarEmail($dados)){
            throw new Exception('E-mail já cadastrado no sistema!', 422);
        }

        if(!$this->funcoesComuns->validaCPF($dados['documentoaluno'])) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_documento_invalido';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }

        $senha = senhaSegura(str_replace(array(".", "-","/"),"",$dados['documentoaluno']), $this->funcoesComuns->acessoBanco->config["chaveLogin"]);

        $this->sql = "INSERT INTO
                          pessoas
                      SET
                          data_cad = now(),
                          nome = '".$dados['nome']."',
                          documento = '".$dados['documentoaluno']."',
                          email = '".$dados['email']."',
                          celular = '".$dados['celular']."',
                          senha = '".$senha."'";

        $salvar = $this->executaSql($this->sql);

        if($salvar) {
            $retorno['erro'] = false;
            $this->monitora_onde = 16;
            $this->monitora_oque = 1;
            $this->monitora_qual = $retorno['idpessoa'] =  mysql_insert_id();
            $this->Monitora();
        } else {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_inserir_banco';
            $retorno['erro']['codigo'] = '500';
        }
        return $retorno;
    }

    public function modificar($dados)
    {
        if (!$dados['documentoaluno'] || !$dados['nome'] || !$dados['email'] || !$dados['celular']) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }

        if($this->buscarEmail($dados)){
            throw new Exception('E-mail já cadastrado no sistema!', 422);
        }

        if(!$this->funcoesComuns->validaCPF($dados['documentoaluno'])) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_documento_invalido';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }

        $this->sql = "UPDATE
                          pessoas
                      SET
                          nome = '".$dados['nome']."',
                          documento = '".$dados['documentoaluno']."',
                          email = '".$dados['email']."',
                          celular = '".$dados['celular']."'
                      WHERE idpessoa = '".$dados['idpessoa']."'";

        $salvar = $this->executaSql($this->sql);

        if($salvar) {
            $retorno['erro'] = false;
            $this->monitora_onde = 16;
            $this->monitora_oque = 2;
            $this->monitora_qual = $retorno['idpessoa'] =  $dados['idpessoa'];
            $this->Monitora();
        } else {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_inserir_banco';
            $retorno['erro']['codigo'] = '500';
        }
        return $retorno;
    }

    public function retornar($mais_dados = true)
    {
        $sql = "SELECT
                    " . $this->campos . ", l.nome as logradouro, cep, endereco, numero, bairro
                FROM
                    pessoas p
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa ON (p.idpais = pa.idpais)
                    LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
                WHERE
                    p.idpessoa = '" . $this->id . "' AND
                    p.ativo = 'S'";

        $retorno = $this->retornarLinha($sql);

        if(!$retorno)
            return false;

        if($mais_dados)
            return $this->retornarMaisDados($retorno);
        else
            return $retorno;
    }

    public function retornarPorCPF($cpf)
    {
        $this->sql = "SELECT
                    " . $this->campos . ", l.nome as logradouro, cep, endereco, numero, bairro
                  FROM
                    pessoas p
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa ON (p.idpais = pa.idpais)
                    LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
                  WHERE
                    p.documento = '" . $cpf . "' AND
                    p.ativo = 'S'
                  ORDER BY idpessoa DESC
                  LIMIT 1";

        $retorno = $this->retornarLinha($this->sql);

        if(!$retorno)
            return false;

        return $this->retornarMaisDados($retorno);
    }

    public function retornarIdPorCPF($cpf)
    {
        $this->sql = "SELECT
                    idpessoa
                  FROM
                    pessoas
                  WHERE
                    documento = '" . $cpf . "'
                  ORDER BY idpessoa DESC
                  LIMIT 1";

        return $this->retornarLinha($this->sql);
    }

    public function retornarPorNome($nome)
    {
        $this->sql = "SELECT
                    " . $this->campos . ", l.nome as logradouro, cep, endereco, numero, bairro
                  FROM
                    pessoas p
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa ON (p.idpais = pa.idpais)
                    LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
                  WHERE
                    p.nome LIKE '%" . $nome . "%' AND
                    p.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "idpessoa";

        $retornoAux = $this->retornarLinhas();

        foreach($retornoAux as $aluno) {
            $retorno[] = $this->retornarMaisDados($aluno);
        }

        return $retorno;
    }

    private function retornarMaisDados($retorno) {

        $retorno["endereco"] = $retorno["logradouro"] . " " . $retorno["endereco"];
        unset($retorno["logradouro"]);

        $sql = "
            SELECT idmatricula
            FROM matriculas
            WHERE idpessoa='{$retorno['idpessoa']}'
            AND ativo='S'
            ";

        $resultado = mysql_query($sql);

        while ($matricula= mysql_fetch_assoc($resultado)) {
            $matriculas[] = $matricula['idmatricula'];
        }

        $retorno["matricula"] = implode(", ", $matriculas);

        $sql = "
            SELECT DISTINCT(m.idcurso), c.nome
            FROM matriculas m INNER JOIN cursos c ON (m.idcurso = c.idcurso)
            WHERE m.idpessoa='{$retorno['idpessoa']}'
            AND m.ativo='S'
            ";

        $resultado = mysql_query($sql);

        while ($curso= mysql_fetch_assoc($resultado)) {
            $cursos[] = $curso["nome"];
        }

        $retorno["curso"] = implode(", ", $cursos);

        $sql = "
            SELECT DISTINCT(m.idpolo), p.nome_fantasia
            FROM matriculas m INNER JOIN polos p ON (m.idpolo = p.idpolo)
            WHERE m.idpessoa='{$retorno['idpessoa']}'
            AND m.ativo='S'
            ";

        $resultado = mysql_query($sql);

        while ($polo= mysql_fetch_assoc($resultado)) {
            $polos[] = $polo['nome_fantasia'];
        }

        $retorno["polo"] = implode(", ", $polos);

        $sql = "
            SELECT DISTINCT(m.idturma), ot.nome
            FROM matriculas m INNER JOIN ofertas_turmas ot ON (m.idturma = ot.idturma)
            WHERE m.idpessoa='{$retorno['idpessoa']}'
            AND m.ativo='S'
            ";

        $resultado = mysql_query($sql);

        while ($turma= mysql_fetch_assoc($resultado)) {
            $turmas[] = $turma["nome"];
        }
        $retorno["turma"] = implode(", ", $turmas);

        unset($retorno["idpessoa"]);

        return $retorno;
    }

    private function retornarIdEstado($estado)
    {
       $sql = "SELECT
                    idestado
                  FROM
                    estados
                  WHERE
                    upper(sigla) = '" . $estado . "'
                  LIMIT 1";

        return $this->retornarLinha($sql)['idestado'];
    }
    private function buscarEmail($dados){
        if (!empty($dados['idpessoa'])) $condIdpessoa = ' AND idpessoa != ' . $dados['idpessoa'];
        $this->sql = "SELECT
                      email
                    FROM
                      pessoas
                    WHERE
                      email = '". $dados['email'] . "'
                      $condIdpessoa;";
        return $this->retornarLinha($this->sql);
    }
}
