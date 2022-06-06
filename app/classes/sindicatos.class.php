<?php

class Sindicatos extends Core
{
    public function getSindicato($idsindicato)
    {
        $this->sql = "SELECT * FROM sindicatos WHERE idsindicato = {$idsindicato}";

        return $this->retornarLinha($this->sql);
    }

    public function ListarTodas()
    {
        $this->sql = 'select
                    ' . $this->campos . "
                  from
                    sindicatos i
                    inner join mantenedoras m on (i.idmantenedora = m.idmantenedora)
                  where
                    i.ativo = 'S'";

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode('|', $campo);
                $valor = str_replace("'", '', $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === '0') and $valor != 'todos') {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= ' and ' . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", '', $valor);
                        $busca = str_replace('\\', '', $busca);
                        $busca = explode(' ', $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= ' and ' . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= ' and date_format(' . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            $this->sql .= ' and i.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ';
        }

        $this->groupby = 'i.idsindicato';

        return $this->retornarLinhas();
    }

    public function Retornar()
    {
        $this->sql = 'select
                    ' . $this->campos . "
                  from
                    sindicatos i
                    inner join mantenedoras m on (i.idmantenedora = m.idmantenedora)
                    left join cidades c on c.idcidade = i.idcidade
                    left join estados e on e.idestado = c.idestado
                  where
                    i.ativo = 'S' and
                    i.idsindicato = '" . $this->id . "'";

        return $this->retornarLinha($this->sql);
    }

    public function verificaFormasPagamento()
    {
        if (!in_array('CC', $this->post['forma_pagamento'])) {
            $this->post['forma_pagamento'][] = 'CC';
        }
    }

    public function cadastrar()
    {
        $this->verificaFormasPagamento();

        return $this->salvarDados();
    }

    public function modificar()
    {
        $this->verificaFormasPagamento();

        return $this->salvarDados();
    }

    public function remover()
    {
        return $this->RemoverDados();
    }

    public function removerArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    public function listarSindicatosNaoGestor($idusuario)
    {
        $this->sql = "select
                        i.nome
                      from
                        usuarios_adm_sindicatos uai
                        inner join sindicatos i on uai.idsindicato = i.idsindicato
                      where
                        uai.ativo = 'S' and uai.idusuario = '" . $idusuario . "' ";
        $this->limit = -1;

        return $this->retornarLinhas();
    }

    public function retornarSindicatosUsuario($idusuario, $retornarRegiao = false)
    {
        $campos = 'i.idsindicato,
                   i.nome,
                   i.nome_abreviado,
                   m.idmantenedora,
                   m.nome_fantasia as mantenedora';

        if ($retornarRegiao) {
            $campos .= ', r.idregiao, r.nome as regiao';
        }

        $this->sql = 'select
                        ' . $campos . '
                    from
                        sindicatos i
                        inner join mantenedoras m on i.idmantenedora = m.idmantenedora';

        if ($retornarRegiao) {
            $this->sql .= ' left outer join estados e on i.idestado = e.idestado
                            left outer join regioes r on e.idregiao = r.idregiao';
        }

        $this->sql .= ' where
                        i.ativo = "S" and
                        i.ativo_painel = "S"';

        if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            if (!$_SESSION['adm_sindicatos']) {
                $_SESSION['adm_sindicatos'] = 0;
            }
            $this->sql .= ' and idsindicato in (' . $_SESSION['adm_sindicatos'] . ')';
        }

        $this->limit = -1;
        $this->ordem_campo = 'i.nome_abreviado';
        $this->ordem = 'asc';

        return $this->retornarLinhas();
    }

    public function retornarUsuariosSindicato($idsindicato)
    {
        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        usuarios_adm ua
                        LEFT JOIN usuarios_adm_sindicatos uai ON (uai.idusuario = ua.idusuario AND uai.ativo = 'S')
                      WHERE ua.ativo = 'S' AND (ua.gestor_sindicato = 'S' OR uai.idsindicato = $idsindicato) GROUP BY ua.idusuario";

        $this->limite = -1;
        $this->groupby = 'ua.idusuario';
        $this->ordem_campo = 'ua.nome';
        $this->ordem = 'ASC';

        return $this->retornarLinhas();
    }

    public function ativarAcesso($situacao)
    {
        if ($situacao != 'S' && $situacao != 'N') {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;

            return json_encode($info);
        }

        $this->sql = 'SELECT * FROM sindicatos WHERE idsindicato = ' . (int) $this->id;
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = 'UPDATE sindicatos SET acesso_ava = "' . mysql_real_escape_string($situacao) . '" WHERE idsindicato = ' . (int) $this->id;
        $executa = $this->executaSql($this->sql);

        $this->sql = 'SELECT * FROM sindicatos WHERE idsindicato = ' . (int) $this->id;
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if ($executa) {
            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $info['sucesso'] = true;
            $info['situacao'] = $linhaNova['acesso_ava'];
        } else {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
        }

        return json_encode($info);
    }

    public function retornarListaArquivos()
    {
        $this->sql = "SELECT * ,idarquivo as iddocumento FROM sindicatos_arquivos sd WHERE idsindicato = {$this->id} AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = 'data_cad';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function adicionarArquivo()
    {
        $this->return = array();
        if ($_FILES['documento']['error'] === 0) {
            $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/sindicatos_arquivos/' . $this->id;
            $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
            $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
            mkdir($pasta, 0777);
            chmod($pasta, 0777);
            $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
            chmod($pasta . '/' . $nomeServidor, 0777);
            if ($envio) {
                $this->sql = 'INSERT INTO
                    sindicatos_arquivos
                    set
                    data_cad = now(),
                    idsindicato = ' . $this->id . ',
                    arquivo_nome = "' . $_FILES['documento']['name'] . '",
                    arquivo_servidor = "' . $nomeServidor . '",
                    arquivo_tipo = "' . $_FILES['documento']['type'] . '",
                    arquivo_tamanho = "' . $_FILES['documento']['size'] . '"';

                $salvar = $this->executaSql($this->sql);
                if ($salvar) {
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 283;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();

                    $this->return['sucesso'] = true;
                    $this->return['mensagem'] = 'arquivos_sindicato_envio_sucesso';
                } else {
                    $this->return['sucesso'] = false;
                    $this->return['mensagem'] = 'arquivos_sindicato_envio_erro';
                }
            } else {
                $this->return['sucesso'] = false;
                $this->return['mensagem'] = 'arquivos_sindicato_envio_erro';
            }
        }

        return $this->return;
    }

    public function removerPastaVirtual()
    {
        $this->retorno = array();
        $this->sql = "UPDATE sindicatos_arquivos SET ativo ='N' WHERE idarquivo = {$this->idarquivo} AND idsindicato = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->monitora_oque = 3;
            $this->monitora_onde = 283;
            $this->monitora_qual = $this->idarquivo;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "arquivo_sindicato_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivo_sindicato_remover_erro";
        }
        return $this->retorno;
    }

    public function retornarFormasPagamentoSindicatoCurso(
        $idSindicato,
        $idCurso,
        $campos = '*'
    ) {
        $idSindicato = intval($idSindicato);
        $idCurso = intval($idCurso);

        $sql = " SELECT
                {$campos}
            FROM
                sindicatos_formas_pagamento
            WHERE
                idsindicato = {$idSindicato}
                AND idcurso = {$idCurso}
                AND ativo = 'S'
        ";

        return $this->retornarLinhasArray($sql);
    }


    public function retornarArquivo()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM sindicatos_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idsindicato = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function modificarFormasPagamento(
        $idsindicato,
        $formasPagamento,
        $idCurso = null
    ) {
        $idsindicato = intval($idsindicato);

        if ($idCurso) {
            $idCurso = intval($idCurso);
        }

        unset($this->monitora_dadosantigos);
        unset($this->monitora_dadosnovos);

        $this->executaSql('BEGIN');

        //Irá retornar as formas de pagamento que a escola tem e não foi selecionada para mantê-las
        $this->sql = 'SELECT
                idsindicato_forma_pagamento
            FROM
                sindicatos_formas_pagamento
            WHERE
                idsindicato = "' . $idsindicato . '"
                AND ativo = "S"
        ';

        if ($idCurso) {
            $this->sql .= ' AND idcurso = "' . $idCurso . '" ';
        }

        if (count($formasPagamento)) {
            $this->sql .= ' AND forma_pagamento NOT IN ("' . implode('","', $formasPagamento) . '") ';
        }

        $removidos = $this->retornarLinhas();

        //Irá remover as formas de pagamento que tinha antes da modificação e não foi selecionada
        foreach ($removidos as $ind => $var) {
            $sql = 'UPDATE
                    sindicatos_formas_pagamento
                SET
                    ativo = "N"
                WHERE
                    idsindicato_forma_pagamento = ' . $var['idsindicato_forma_pagamento'] . '
            ';
            $desassociar = $this->executaSql($sql);

            if ($desassociar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = $GLOBALS['config']['monitoramento']['onde_sindicatos_formas_pagamento'];
                $this->monitora_qual = $var['idsindicato_forma_pagamento'];
                $this->Monitora();
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $this->sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }

        foreach ($formasPagamento as $ind => $var) {
            $this->sql = 'SELECT
                    idsindicato_forma_pagamento,
                    ativo
                FROM
                    sindicatos_formas_pagamento
                WHERE
                    idsindicato = "' . (int) $idsindicato . '"
                    AND forma_pagamento = "' . $var . '"
            ';

            if ($idCurso) {
                $this->sql .= ' AND idcurso = "' . (int) $idCurso . '" ';
            }

            $jaExiste = $this->retornarLinha($this->sql);

            if ($jaExiste['idsindicato_forma_pagamento']) {
                $this->sql = 'UPDATE
                        sindicatos_formas_pagamento
                    SET
                        ativo = "S"
                    WHERE
                        idsindicato_forma_pagamento = "' . $jaExiste['idsindicato_forma_pagamento'] . '"
                ';
                $associar = $this->executaSql($this->sql);

                $this->monitora_qual = $jaExiste['idsindicato_forma_pagamento'];
            } else {
                $this->sql = "INSERT INTO
                        sindicatos_formas_pagamento
                    SET
                        ativo = 'S',
                        data_cad = NOW(),
                        idsindicato = " . (int) $idsindicato . ",
                        forma_pagamento = '" . $var . "'
                ";

                if ($idCurso) {
                    $this->sql .= " , idcurso = " . (int) $idCurso;
                }

                $associar = $this->executaSql($this->sql);

                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = $GLOBALS['config']['monitoramento']['onde_sindicatos_formas_pagamento'];

                //Só faz o monitoramento se não exisitia ainda a forma de pagamento para a escola
                if ($jaExiste['ativo'] != 'S') {
                    $this->Monitora();
                }
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $this->sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }

        if ($this->retorno['sucesso']) {
            $this->executaSql('COMMIT');
        } else {
            $this->executaSql('ROLLBACK');
        }

        return $this->retorno;
    }

    public function salvarValoresCursos($idSindicato, $arrayValores)
    {
        if (empty($arrayValores)) {
            return false;
        }

        $salvou = false;
        $post = [];
        $this->iniciaTransacao();

        foreach ($arrayValores as $ind => $valores) {
/*            $post['avista'] = $valores['avista'];
            $post['aprazo'] = $valores['aprazo'];*/
            $post['parcelas'] = $valores['parcelas'];
            $post['max_parcelas'] = $valores['max_parcelas'];
            $post['idsindicato'] = $idSindicato;
            $post['idcurso'] = $valores['idcurso'];
            $post['quantidade_matriculas'] = $valores['quantidade_matriculas'];
            $post['valor_por_matricula'] = $valores['valor_por_matricula'];
            $post['quantidade_matriculas_2'] = $valores['quantidade_matriculas_2'];
            $post['valor_por_matricula_2'] = $valores['valor_por_matricula_2'];
            $post['valor_excedente'] = $valores['valor_excedente'];
            $post['quantidade_faturas_ciclo'] = $valores['quantidade_faturas_ciclo'];
            $post['idvalor_curso'] = (!empty($valores['idvalor_curso'])
                ? $valores['idvalor_curso']
                : null);

            $this->set('post', $post);
            $salvou = $this->salvarDados();

            if ($salvou['sucesso']) {
                $this->modificarFormasPagamento(
                    $idSindicato,
                    $valores['forma_pagamento'],
                    $valores['idcurso']
                );
            }
        }

        $this->finalizaTransacao();

        return $salvou;
    }

    public function listarSindicatosValoresCursos($campos = '*')
    {
        $retorno = [];
        if ($this->id) {
            $andSvc = " AND svc.idsindicato = {$this->id} ";
            $andCi = " AND ci.idsindicato = {$this->id} ";
        }

        $sql = "SELECT
                {$campos}
            FROM
                sindicatos i
                INNER JOIN cursos_sindicatos ci ON (i.idsindicato = ci.idsindicato)
                INNER JOIN cursos c ON (c.idcurso = ci.idcurso)
                INNER JOIN usuarios_adm ua ON (ua.idusuario = " . $this->idusuario . ")
                LEFT JOIN usuarios_adm_sindicatos uai ON (
                    i.idsindicato = uai.idsindicato
                    AND uai.ativo = 'S'
                    AND uai.idusuario = ua.idusuario
                )
                LEFT JOIN sindicatos_valores_cursos svc ON (
                    svc.idcurso = c.idcurso
                    AND svc.ativo = 'S'
                    {$andSvc}
                )
              WHERE
                (ua.gestor_sindicato = 'S' OR uai.idusuario IS NOT NULL)
                AND ci.ativo = 'S'
                {$andCi}
              GROUP BY c.idcurso";

        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }
}
