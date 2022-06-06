<?php
require_once '../classes/detran.class.php';

class Matricula
{
    private $acessoBanco;
    public  $config;
    public  $idmatricula;
    public  $idpessoa;
    public  $idusuario;

    const ID_RIO_GRANDE_SUL = 21;
    const ID_PARANA = 16;
    const ID_MARANHAO = 10;
    const ID_MATO_GROSSO_DO_SUL = 12;

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function retornarSiglaEstado($idestado)
    {
        $sql = "select sigla FROM estados where idestado = '" . $idestado . "' ";
        $linha = $this->acessoBanco->retornarLinha($sql);
        return $linha['sigla'];
    }

    private function validarCampos($dados)
    {
        $retorno['codigo'] = 422;
        $retorno['mensagem'] = 'Existe campo(s) ou parâmetro(s) obrigatório(s) que não foi preenchido ou tem que ser do tipo númerico.';

        if(empty($dados['cpf']))
        {
            $retorno['campos']['cpf'] = 'O campo cpf é do tipo obrigatório.';
        }

        if(empty($dados['aluno']))
        {
            $retorno['campos']['aluno'] = 'O campo aluno é do tipo obrigatório.';
        }

        if(empty($dados['email']))
        {
            $retorno['campos']['email'] = 'O campo e-mail é do tipo obrigatório.';
        }

        if(empty($dados['renach']))
        {
            $retorno['campos']['renach'] = 'O campo renach é do tipo obrigatório.';
        }

        if(empty($dados['id_curso']))
        {
            $retorno['campos']['id_curso'] = 'O campo id_curso e do tipo obrigatório.';
        } else if(!is_numeric($dados['id_curso'])){
            $retorno['campos']['id_curso'] = 'O campo id_curso tem que ser do tipo númerico';
        }

        if(empty($dados['cfc_responsavel']))
        {
            $retorno['campos']['cfc_responsavel'] = 'O campo cfc_responsavel e do tipo obrigatório.';
        }

        if(empty($dados['municipio']))
        {
            $retorno['campos']['municipio'] = 'O campo municipio e do tipo obrigatório.';
        }

        if(empty($dados['uf']))
        {
            $retorno['campos']['uf'] = 'O campo uf e do tipo obrigatório.';
        }

        if(empty($_GET['oferta']))
        {
            $retorno['parametros']['oferta'] = 'O parâmetro oferta é do tipo obrigatório.';
        } else if(!is_numeric($_GET['oferta'])){
            $retorno['parametros']['oferta'] = 'O parâmetro oferta tem que ser do tipo númerico.';
        }

        if(empty($_GET['turma']))
        {
            $retorno['parametros']['turma'] = 'O parâmetro turma é do tipo obrigatório.';
        } else if(!is_numeric($_GET['turma']))
        {
            $retorno['parametros']['turma'] = 'O parâmetro turma tem que ser do tipo númerico.';
        }

        return $retorno;
    }

    public function cadastrar($dados, $gestor)
    {
        require_once 'Escola.php';

        if (empty($dados)) {
            throw new InvalidArgumentException('campos_vazio');
        } else {
            $resultado = $this->validarCampos($dados);
            if(count($resultado) > 2)
            {
                throw new InvalidArgumentException(json_encode($resultado, JSON_UNESCAPED_UNICODE));
            }
        }

        $escolaObj = new Escola($this->acessoBanco);

        $escola = $escolaObj->retornarEscola($dados['cfc_responsavel']);
        if(empty($escola))
        {
            throw new UnexpectedValueException("cfc_nao_encontrado");
        }


        $curso = $this->retornarCurso($dados['id_curso']);
        if (!$curso) {
            throw new \UnexpectedValueException("erro_curso_invalido");
        }

        $idoferta = $_GET['oferta'];
        $idturma = $_GET['turma'];

        $sindicato = $this->retornarSindicato($escola['idsindicato']);
        $mantenedora = $this->retornarMantenedora($sindicato["idmantenedora"]);


        $oferta = $this->retornarOferta($idoferta);
        if (empty($oferta)) {
            throw new \UnexpectedValueException("erro_oferta_invalida", 422);
        }

        $ofertaCursoEscola = $this->verificarMatriculasCursoEscola($idoferta, $dados['id_curso'], $escola['idescola'], $idturma);
        if(array_key_exists('erro', $ofertaCursoEscola)) {
            throw new \Exception($ofertaCursoEscola['erros']['mensagem'], $ofertaCursoEscola['erros']['codigo']);
        }

        if($ofertaCursoEscola['total'] == $ofertaCursoEscola['maximo_turma']) {
            throw new \Exception("erro_maximo_turma", 424);
        }

        $this->acessoBanco->config = $this->config;
        $this->acessoBanco->executaSql('BEGIN');

        require_once 'Pessoa.php';
        $pessoaObj = new Pessoa($this->acessoBanco);
        $pessoaObj->config = $this->config;
        $pessoaObj->idusuario = $this->idusuario;
        $pessoa = $pessoaObj->retornarIdPorCPF($dados['cpf']);
        if ($pessoa) {
            $pessoa = $pessoaObj->modificar($dados, $pessoa['idpessoa']);
        } else {
            $pessoa = $pessoaObj->cadastrar($dados);
        }

        $situacaoAtiva = $this->retornarSituacaoAtiva();

        $dataDiasParaAva = new DateTime();

        $oferta_curso_escola = $this->retornarOfertaCursoEscola($idoferta, $dados['id_curso'], $escola['idescola'], $idturma);

        if ($oferta_curso_escola['dias_para_ava']) {
            $dataDiasParaAva->modify('+ ' . $oferta_curso_escola['dias_para_ava'] . ' days');
        }

        $dataLimiteAva = NULL;
        if ($oferta_curso_escola['data_limite_ava']) {
            $dataLimiteAva = new DateTime($oferta_curso_escola['data_limite_ava']);
        }

        if ($dataDiasParaAva && $dataLimiteAva) {
            if ($dataDiasParaAva > $dataLimiteAva) {
                $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
            } else {
                $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
            }
        } elseif ($dataDiasParaAva) {
            $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
        } else {
            $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
        }



        $sql = "SELECT
                    COUNT(m.idmatricula) AS matricula_duplicada
                FROM
                    matriculas m
                    INNER JOIN matriculas_workflow mw ON m.idsituacao = mw.idsituacao
                WHERE
                    mw.inativa <> 'S' AND
                    mw.cancelada <> 'S' AND
                    m.ativo = 'S' AND
                    m.idpessoa = " . $pessoa["idpessoa"] . " AND
                    m.idoferta = " . $idoferta . " AND
                    m.idcurso = " . $dados["id_curso"] . " AND
                    m.idescola = " . $escola["idescola"];
        $matriculas = $this->acessoBanco->retornarLinha($sql);

        if ($matriculas['matricula_duplicada'] > 0) {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new \Exception('matricula_duplicada', 302);
        }

        /* Se o estado não tem integração, o mesmo já vai como Liberado */
        $detran = new Detran();
        $estado = $this->retornarSiglaEstado($escola['idestado']);
        $curso_integrado = in_array((int)$dados["id_curso"], array_keys($GLOBALS['detran_tipo_aula'][$estado]));
        $estado_integrado = $detran->obterSituacaoIntegracao((int)$escola['idestado']);
        $detranSituacao = 'LI';
        if ($estado_integrado && $curso_integrado)
        {
            $detranSituacao = 'AL';
        }

        $sql = "INSERT INTO
                        matriculas
                    SET
                        data_cad = now(),
                        data_matricula = now(),
                        data_prolongada = '" . $data_limite_acesso_ava . "',
                        detran_situacao = '" . $detranSituacao . "',
                        idmantenedora = '" . $mantenedora["idmantenedora"] . "',
                        idsindicato = '" . $sindicato["idsindicato"] . "',
                        idpessoa = " . $pessoa["idpessoa"] . ",
                        idoferta = " . $idoferta . ",
                        idcurso = " . $dados["id_curso"] . ",
                        idescola = " . $escola["idescola"] . ",
                        idturma = " . $idturma . ",
                        aprovado_comercial = 'N',
                        idsituacao = " . $situacaoAtiva["idsituacao"] . ",
                        modulo = 'gestor',
                        observacao = NULL,
                        data_registro = '" . date('Y-m-d') . "',
                        idusuario = " . $gestor['idusuario'];

        if ($this->acessoBanco->executaSql($sql)) {
            $this->idmatricula = mysql_insert_id();

            $sql = "INSERT INTO
                        matriculas_historicos
                    SET
                        idmatricula = '" . $this->idmatricula . "',
                        data_cad = now(),
                        tipo = 'situacao',
                        acao = 'modificou',
                        para = " . $situacaoAtiva['idsituacao'];

            $this->acessoBanco->executaSql($sql);

            $sql = 'SELECT
                        idpessoa_sindicato,
                        ativo
                    FROM
                        pessoas_sindicatos
                    WHERE
                        idpessoa = ' . $pessoa['idpessoa'] . ' AND
                        idsindicato = ' . $sindicato['idsindicato'] . ' ';
            $pessoa_sindicato = $this->acessoBanco->retornarLinha($sql);

            if ($pessoa_sindicato['idpessoa_sindicato']) {
                if ($pessoa_sindicato['ativo'] == 'N') {
                    $sql = 'UPDATE
                                pessoas_sindicatos
                            SET
                                ativo = "S"
                            WHERE
                                idpessoa_sindicato = ' . $pessoa_sindicato['idpessoa_sindicato'];

                    $this->acessoBanco->executaSql($sql);
                }
            } else {
                $sql = 'INSERT INTO
                            pessoas_sindicatos
                        SET
                            data_cad = NOW(),
                            idpessoa = ' . $pessoa['idpessoa'] . ',
                            idsindicato = ' . $sindicato['idsindicato'] . ' ';

                $this->acessoBanco->executaSql($sql);
            }
            $this->eviarEmailBoasVindas($this->idmatricula, $escola, $sindicato);
        } else {
            throw new \Exception('erro_cadastro_matricula', 400);
        }

        $this->acessoBanco->executaSql('COMMIT');
        $retorno["codigo"] = 201;
        $retorno["mensagem"] = "Matrícula cadastrada com sucesso!";

        return $retorno;
    }

    public function retornarOferta($idOferta)
    {
        $sql = 'SELECT
                    *
                FROM
                    ofertas
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND DATE_FORMAT(NOW(),"%Y-%m-%d") BETWEEN data_inicio_matricula AND data_fim_matricula
                    AND idoferta = ' . $idOferta;
        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarCurso($idCurso)
    {
        $sql = 'SELECT
                    *
                FROM
                    cursos
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND idcurso = ' . $idCurso;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarEscola($idEscola)
    {
        $sql = 'SELECT
                    *
                FROM
                    escolas
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND idescola = ' . $idEscola;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarAtendente($idAtendente)
    {
        $sql = "SELECT
                    nome
                FROM
                    vendedores
                WHERE
                    ativo = 'S'
                    AND ativo_login = 'S'
                    AND venda_bloqueada = 'N'
                    AND idvendedor = " . $idAtendente;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarSituacaoAtiva()
    {
        $sql = "SELECT
                    *
                FROM
                    matriculas_workflow
                WHERE
                    ativo = 'S'
                    AND ativa = 'S'
                ORDER BY
                    idsituacao DESC LIMIT 1";

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarSindicato($idSindicato)
    {
        $sql = "SELECT
                    *
                FROM
                    sindicatos
                WHERE
                    idsindicato = '" . $idSindicato . "' AND
                    ativo = 'S'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarMantenedora($idMantenedora)
    {
        $sql = "SELECT
                    *
                FROM
                    mantenedoras
                WHERE
                    idmantenedora='" . $idMantenedora . "' AND
                    ativo = 'S'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function verificarMatriculasCursoEscola($idOferta, $idCurso, $idEscola, $idTurma)
    {

        $sql_sindicato = 'SELECT
                                idsindicato
                            FROM
                                escolas
                            WHERE
                                idescola = ' . $idEscola;

        $sindicato = $this->acessoBanco->retornarLinha($sql_sindicato);

        $sql = 'SELECT
                    COUNT(1) AS total,
                    (SELECT
                        oci.limite
                     FROM
                        ofertas_cursos_escolas ocp
                        INNER JOIN escolas p ON ocp.idescola = p.idescola
                        INNER JOIN ofertas_cursos_sindicatos oci ON ocp.idoferta = oci.idoferta AND ocp.idcurso = oci.idcurso AND p.idsindicato = oci.idsindicato AND oci.ativo = "S"
                     WHERE
                        ocp.idoferta = ' . $idOferta . '
                        AND ocp.idcurso = ' . $idCurso . '
                        AND p.idescola = ' . $idEscola . '
                        AND ocp.ativo = "S") AS maximo_turma
                  FROM
                      matriculas
                  WHERE
                      idoferta = ' . $idOferta . '
                      AND idcurso = ' . $idCurso . '
                      AND idsindicato = ' . $sindicato['idsindicato'] . '
                      AND idturma = ' . $idTurma . '
                      AND ativo = "S" ';

        $resultado = $this->acessoBanco->executaSql($sql);
        if (! $resultado) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_verificar_matriculas';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }
        $matriculas = mysql_fetch_assoc($resultado);
        return $matriculas;
    }

    private function retornarOfertaCursoEscola($idOferta, $idCurso, $idEscola, $idTurma)
    {
        $sql = 'SELECT
                  *
              FROM
                ofertas_cursos_escolas oc
              INNER JOIN ofertas_turmas ot ON oc.idoferta = ot.idoferta
              WHERE
                oc.idcurso = ' . $idCurso . '
                AND oc.idescola = ' . $idEscola . '
                AND oc.idoferta = ' . $idOferta . '
                AND ot.idturma = ' . $idTurma . '
                AND oc.ativo = "S" ';

        $resultado = $this->acessoBanco->retornarLinha($sql);

        if(empty($resultado)){
            throw new InvalidArgumentException('erro_oferta_curso_escola');
        }
        return $resultado;
    }

    private function eviarEmailBoasVindas($idmatricula, $escola, $sindicato)
    {
        $sql = 'SELECT
                    *
                FROM
                    matriculas
                WHERE
                    idmatricula = ' . $idmatricula;

        $matricula = $this->acessoBanco->retornarLinha($sql);
        if ($matricula['idmatricula']) {
            $sql = 'SELECT
                        *
                    FROM
                        pessoas
                    WHERE
                        idpessoa = ' . $matricula['idpessoa'];
            $pessoa = $this->acessoBanco->retornarLinha($sql);

            $sql = 'SELECT
                        *
                    FROM
                        ofertas
                    WHERE
                        idoferta = ' . $matricula['idoferta'];
            $oferta = $this->acessoBanco->retornarLinha($sql);

            $sql = "SELECT
                        c.*,
                        ci.email_boas_vindas_sindicato,
                        ci.sms_boas_vindas_sindicato
                    FROM
                        cursos c
                        LEFT JOIN cursos_sindicatos ci ON c.idcurso = ci.idcurso AND ci.ativo = 'S' AND ci.idsindicato = '" . $matricula['idsindicato'] . "'
                    WHERE
                        c.idcurso = " . $matricula['idcurso'];
            $curso = $this->acessoBanco->retornarLinha($sql);

            if (array_key_exists('email_boas_vindas_instituicao', $curso))
                $emailBoasVindas = $curso['email_boas_vindas_instituicao'];
            else
                $emailBoasVindas = $curso['email_boas_vindas'];

            if ($emailBoasVindas) {
                $emailBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[POLO]]", ($escola['nome_fantasia']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[INSTITUICAO]]", ($sindicato['nome']), $emailBoasVindas);

                $emailBoasVindas = utf8_decode($emailBoasVindas);

                $nomeDe = utf8_decode($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
                if ($curso['email']) {
                    $emailDe = $curso['email'];
                } else {
                    $emailDe = $GLOBALS['config']['emailSistema'];
                }
                $assunto = 'BEM VINDO AO CURSO';
                $nomePara = utf8_decode($pessoa['nome']);
                $emailPara = $pessoa['email'];

                $this->acessoBanco->enviarEmail($nomeDe, $emailDe, $assunto, $emailBoasVindas, $nomePara, $emailPara);

            }
        }
    }
}
