<?php

class Login
{
    private $funcoesComuns;
    private $acessoBanco;

    public function __construct(\OrIO\FuncoesComuns $funcoesComuns)
    {
        $this->acessoBanco = $funcoesComuns->acessoBanco;
        $this->funcoesComuns = $funcoesComuns;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function realizarLogin($usuario, $senha)
    {

        $sql = "
            SELECT token_app, ultimo_token, idpessoa, p.nome, data_nasc, sexo, l.nome as logradouro, cep, endereco, numero, bairro, c.nome as cidade, e.nome as estado, documento, telefone, email, documento_tipo, estado_civil
            FROM pessoas p
                INNER JOIN cidades c ON p.idcidade = c.idcidade
                INNER JOIN estados e ON p.idestado = e.idestado
                LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
            WHERE p.email='{$usuario}'
                AND p.senha='{$senha}'
                AND p.ativo='S'
                AND p.ativo_login = 'S'
            ";

        $pessoa = $this->acessoBanco->retornarLinha($sql);

        if (!$pessoa) {
            throw new \Exception("erro_senha_incorreta", 2);
        }

        $sql = "
            SELECT m.idmatricula as id, c.livre as curso_tti
            FROM matriculas m 
                INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
                AND m.ativo='S'
            ";

        $resultado = mysql_query($sql);

        while ($matricula= mysql_fetch_assoc($resultado)) {
            $matriculas[]["id"] = array(
                "id" => $matricula['id'],
                "tti" => ($matricula['curso_tti'] == 'N') ? "Sim" : "Não"
            );
        }

        $pessoa["matricula"] = $matriculas;

        $sql = "
            SELECT DISTINCT(m.idcurso) as id, c.nome
            FROM matriculas m 
                INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
            AND m.ativo='S'
            ORDER BY id
            ";

        $resultado = mysql_query($sql);

        while ($curso= mysql_fetch_assoc($resultado)) {
            $cursos[] = array("id" => $curso['id'], "nome" => $curso['nome']);
        }

        $pessoa["curso"] = $cursos;

        $sql = "
            SELECT DISTINCT(d.iddisciplina) as id, d.nome
            FROM matriculas m 
                INNER JOIN disciplinas_cursos dc ON (m.idcurso = dc.idcurso)
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
                INNER JOIN disciplinas d ON (d.iddisciplina = dc.iddisciplina)
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
            AND m.ativo='S'
            ORDER BY id
            ";

        $resultado = mysql_query($sql);
        while ($disciplina = mysql_fetch_assoc($resultado)) {
            $disciplinas[] = array("id" => $disciplina['id'], "nome" => $disciplina['nome']);
        }

        $pessoa["disciplina"] = $disciplinas;

        $sql = "
            SELECT DISTINCT(m.idpolo) as id, p.nome_fantasia
            FROM matriculas m 
                INNER JOIN polos p ON (m.idpolo = p.idpolo)
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
            AND m.ativo='S'
            ORDER BY id
            ";

        $resultado = mysql_query($sql);
        while ($polo = mysql_fetch_assoc($resultado)) {
            $polos[] = array("id" => $polo['id'], "nome" => $polo['nome_fantasia']);
        }

        $pessoa["polo"] = $polos;

        $sql = "
            SELECT DISTINCT(m.idturma) as id, ot.nome
            FROM matriculas m 
                INNER JOIN ofertas_turmas ot ON (m.idturma = ot.idturma)
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
            AND m.ativo='S'
            ORDER BY id
            ";

        $resultado = mysql_query($sql);

        while ($turma= mysql_fetch_assoc($resultado)) {
            $turmas[] = array("id" => $turma['id'], "nome" => $turma['nome']);
        }
        $pessoa["turma"] = $turmas;

        $retorno = [
            'idpessoa' =>$pessoa['idpessoa'],
            'nome' => $pessoa['nome'],
            'data_nasc' => $pessoa['data_nasc'],
            'sexo' => $pessoa['sexo'],
            'endereco' => $pessoa['logradouro'] . " " . $pessoa['endereco'],
            'numero' => $pessoa['numero'],
            'bairro' => $pessoa['bairro'],
            'cidade' => $pessoa['cidade'],
            'estado' => $pessoa['estado'],
            'documento' => $pessoa['documento'],
            'matricula' => $pessoa['matricula'],
            'documento_tipo' => strtoupper($pessoa['documento_tipo']),
            'estado_civil' => !empty($pessoa['estado_civil']) ? $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$pessoa['estado_civil']] : "",
            'telefone' => $pessoa['telefone'],
            'email' => $pessoa['email'],
            'curso' => $pessoa['curso'],
            'disciplina' => $pessoa["disciplina"],
            'turma' => $pessoa['turma'],
            'polo' => $pessoa['polo'],
        ];

        $this->funcoesComuns->validateToken($pessoa['token_app'], $pessoa);

        return $retorno;
    }

    public function realizarLoginV2($usuario, $senha)
    {

        $sql = "
            SELECT token_app, ultimo_token, idpessoa, p.nome, data_nasc, sexo, l.nome as logradouro, cep, endereco, numero, bairro, c.nome as cidade, e.nome as estado, documento, telefone, email, documento_tipo, estado_civil
            FROM pessoas p
                INNER JOIN cidades c ON p.idcidade = c.idcidade
                INNER JOIN estados e ON p.idestado = e.idestado
                LEFT OUTER JOIN logradouros l ON p.idlogradouro = l.idlogradouro
            WHERE p.email='{$usuario}'
                AND p.senha='{$senha}'
                AND p.ativo='S'
                AND p.ativo_login = 'S'
            ";

        $pessoa = $this->acessoBanco->retornarLinha($sql);

        if (!$pessoa) {
            throw new \Exception("erro_senha_incorreta", 2);
        }

        $sql = "
            SELECT m.idmatricula as id
            FROM matriculas m 
                INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
            WHERE m.idpessoa='{$pessoa['idpessoa']}'
            AND m.ativo='S'
            ORDER BY id
            ";

        $resultado = mysql_query($sql);

        $matriculasAux = array();
        while ($matricula= mysql_fetch_assoc($resultado)) {
            $matriculasAux["id"] = $matricula['id'];
            $sql = "
                SELECT m.idcurso as id, c.nome, c.livre as tti
                FROM matriculas m 
                    INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
                    INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                WHERE m.idpessoa='{$pessoa['idpessoa']}'
                    AND m.ativo='S'
                    AND m.idmatricula = '{$matricula['id']}'
                ";

            $curso = $this->acessoBanco->retornarLinha($sql);

            $sql = "
                SELECT d.iddisciplina as id, d.nome
                FROM matriculas m 
                    INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
                    INNER JOIN disciplinas_cursos dc ON (m.idcurso = dc.idcurso)
                    INNER JOIN disciplinas d ON (d.iddisciplina = dc.iddisciplina)
                WHERE m.idpessoa = '{$pessoa['idpessoa']}'
                    AND m.ativo='S'
                    AND m.idmatricula = '{$matricula['id']}'
                ORDER BY id
                ";

            $resultado_disciplinas = mysql_query($sql);
            $disciplinas = array();
            while ($disciplina = mysql_fetch_assoc($resultado_disciplinas)) {
                $disciplinas[] = array("id" => $disciplina['id'], "nome" => $disciplina['nome']);
            }

            $matriculasAux["curso"] = array(
                "id" => $curso["id"],
                "nome" => $curso["nome"],
                "tti" => ($curso["tti"] == "N") ? "Sim" : "Não",
                "disciplina" => $disciplinas
            );

            $sql = "
                SELECT m.idturma as id, ot.nome
                FROM matriculas m 
                    INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
                    INNER JOIN ofertas_turmas ot ON (m.idturma = ot.idturma)
                WHERE m.idpessoa='{$pessoa['idpessoa']}'
                AND m.ativo='S'
                AND m.idmatricula = '{$matricula['id']}'
                ";

            $matriculasAux["turma"] = $this->acessoBanco->retornarLinha($sql);

            $sql = "
                SELECT m.idpolo as id, p.nome_fantasia
                FROM matriculas m 
                    INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao AND mw.cancelada = 'N')
                    INNER JOIN polos p ON (m.idpolo = p.idpolo)
                WHERE m.idpessoa='{$pessoa['idpessoa']}'
                    AND m.ativo='S'
                    AND m.idmatricula = '{$matricula['id']}'
                ";

            $matriculasAux["polo"] = $this->acessoBanco->retornarLinha($sql);

            $matriculas[] = $matriculasAux;
        }

        $pessoa["matricula"] = $matriculas;

        $retorno = [
            'idpessoa' =>$pessoa['idpessoa'],
            'nome' => $pessoa['nome'],
            'data_nasc' => $pessoa['data_nasc'],
            'sexo' => $pessoa['sexo'],
            'endereco' => $pessoa['logradouro'] . " " . $pessoa['endereco'],
            'numero' => $pessoa['numero'],
            'bairro' => $pessoa['bairro'],
            'cidade' => $pessoa['cidade'],
            'estado' => $pessoa['estado'],
            'documento' => $pessoa['documento'],
            'matricula' => $pessoa['matricula'],
            'documento_tipo' => strtoupper($pessoa['documento_tipo']),
            'estado_civil' => !empty($pessoa['estado_civil']) ? $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$pessoa['estado_civil']] : "",
            'telefone' => $pessoa['telefone'],
            'email' => $pessoa['email']
        ];

        $this->funcoesComuns->validateToken($pessoa['token_app'], $pessoa);

        return $retorno;
    }
}
