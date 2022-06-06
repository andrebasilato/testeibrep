<?php

class Escolas extends Core
{
    public function listarTodas($retornarEstadosCidades = false)
    {
        $this->sql = 'SELECT
               DISTINCT ' . $this->campos . '
            FROM
                escolas p
                INNER JOIN sindicatos i ON (i.idsindicato = p.idsindicato)
                LEFT OUTER JOIN estados e ON (e.idestado = p.idestado)
                LEFT OUTER JOIN cidades c ON (c.idcidade = p.idcidade)
            WHERE
                p.ativo = "S"';

        if ($this->idsindicato) {
            $this->sql .= ' AND p.idsindicato = ' . $this->idsindicato;
        }

        $this->aplicarFiltrosBasicos();

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);

                if (($valor || $valor === '0') && $valor <> 'todos') {
                    if ($campo[0] == 6) {
                        $this->sql .= ' AND (
                                SELECT
                                    COUNT(eec.idescola_estado_cidade)
                                FROM
                                    escolas_estados_cidades eec
                                WHERE
                                    eec.idescola = p.idescola AND
                                    eec.ativo = "S" AND
                                    ' . $campo[1] . ' = "' . $valor . '"
                            ) > 0 ';
                    }
                }
            }
        }

        $this->groupby = 'p.idescola';
        $this->mantem_groupby = true;
        $retorno = $this->retornarLinhas();

        if (!empty($retornarEstadosCidades)) {
            foreach ($retorno as $ind => $var) {
                $this->id = $var['idescola'];
                $this->campos = 'eec.idestado, eec.idcidade, e.nome AS estado, e.sigla AS uf, c.nome AS cidade';
                $retorno[$ind]['estados_cidades'] = $this->listarEstadosCidadesAssociadas();
            }
        }

        return $retorno;
    }

    public function retornar($retornarEstadosCidades = false)
    {
        $this->sql = 'SELECT
                ' . $this->campos . '
            FROM
                escolas p
                INNER JOIN sindicatos i ON (i.idsindicato = p.idsindicato)
                LEFT JOIN estados e ON (e.idestado = p.idestado)
                LEFT JOIN cidades c ON (c.idcidade = p.idcidade)
                LEFT JOIN escolas_estados_cidades eec ON (eec.idescola = p.idescola AND eec.ativo = "S")
                LEFT JOIN estados e2 ON (e2.idestado = eec.idestado)
                LEFT JOIN cidades c2 ON (c2.idcidade = eec.idcidade)
                LEFT OUTER JOIN logradouros l ON (l.idlogradouro = p.idlogradouro)
            WHERE
                p.ativo = "S"';

        if ($this->modulo != 'web') {
            $this->sql .= ' AND p.idescola = ' . $this->id;
        }

        if ($this->slug) {
            $this->sql .= ' AND p.slug = "' . $this->slug . '"';
        }

        if ($this->siglaUf) {
            $this->sql .= ' AND e2.sigla = "' . $this->siglaUf . '"';
        }

        if ($this->idcidade) {
            $this->sql .= ' AND c2.idcidade = ' . $this->idcidade;
        }

        if ($this->ativoPainel) {
            $this->sql .= ' AND p.ativo_painel = "' . $this->ativoPainel . '"';
        }

        $this->sql .= ' GROUP BY p.idescola';

        $retorno = $this->retornarLinha($this->sql);

        if (!empty($retornarEstadosCidades)) {
            $this->id = $retorno['idescola'];
            $this->campos = 'eec.idestado, eec.idcidade, e.nome AS estado, e.sigla AS uf, c.nome AS cidade';
            $retorno['estados_cidades'] = $this->listarEstadosCidadesAssociadas();
        }

        return $retorno;
    }

    function contratosNaoAceitos($idEscola)
    {
        try {
            if (gettype($idEscola) != "integer") {
                throw new InvalidArgumentException("Para realizar a consulta dos contratos não aceitos, o valor da escola precisa ser um inteiro!");
            } else {
                $this->sql = "SELECT aceito_cfc FROM escolas_contratos_gerados WHERE idescola = {$idEscola} AND ativo = 'S' AND aceito_cfc = 'N'";
                $this->ordem_campo = null;
                $this->mantem_groupby = false;
                return $this->retornarLinhas();
            }
        } catch (InvalidArgumentException $e) {
            echo "Ops! {$e->getMessage()}";
        }
    }

    public function aplicarRegrasFormulario($configForm)
    {
        $remover = [];

        if ($this->post['pagseguro'] == N) {
            $remover['apagar_post'][] = 'pagseguro_email';
            $remover['apagar_post'][] = 'pagseguro_token';
        }

        if ($this->post['fastconnect'] == N) {
            $remover['apagar_post'][] = 'fastconnect_client_code';
            $remover['apagar_post'][] = 'fastconnect_client_key';
        }

        return $this->removerValidacaoFormulario($configForm, $remover);
    }

    public function removerValidacaoFormulario($configForm, $arrayCampos = array())
    {
        foreach ($configForm as $ind_form => $parte_form) {
            foreach ($parte_form['campos'] as $ind_campo => $campo) {
                //Terá o campo, mas não será obrigatório
                if (in_array($campo['nome'], $arrayCampos['manter_post'])) {
                    unset($configForm[$ind_form]['campos'][$ind_campo]['validacao']);
                }

                //Não Terá o campo
                if (in_array($campo['nome'], $arrayCampos['apagar_post'])) {
                    unset($configForm[$ind_form]['campos'][$ind_campo]['validacao']);
                    unset($this->post[$campo['nome']]);
                }
            }
        }
        return $configForm;
    }

    public function cadastrar()
    {
        if ($this->post['documento_tipo'] == 'cnpj') {
            $this->config['formulario'][0]['campos'][3]['validacao'] = $this->config['formulario'][0]['campos'][4]['validacao'];
            $this->post['documento'] = $this->post['documento_cnpj'];

            unset($this->post['documento_cnpj']);
            unset($this->config['formulario'][0]['campos'][4]);
        } else {
            unset($this->post['documento_cnpj']);
            unset($this->config['formulario'][0]['campos'][4]);
        }

        $this->config['formulario'] = $this->aplicarRegrasFormulario($this->config['formulario']);

        $retorno = $this->SalvarDados();

        if ($retorno['sucesso']) {
            $this->modificarFormasPagamento(
                $retorno['id'],
                $this->post['forma_pagamento']
            );
        }

        return $retorno;
    }

    public function modificar()
    {
        unset($this->config['formulario'][0]['campos'][2]);
        unset($this->config['formulario'][0]['campos'][3]);
        unset($this->config['formulario'][0]['campos'][4]);

        $this->config['formulario'] = $this->aplicarRegrasFormulario($this->config['formulario']);

        $retorno = $this->SalvarDados();

        if ($retorno['sucesso']) {
            $this->modificarFormasPagamento(
                $this->post['idescola'],
                $this->post['forma_pagamento']
            );
        }

        return $retorno;
    }

    public function Remover()
    {
        return $this->RemoverDados();
    }

    public function modificarFormasPagamento(
        $idescola,
        $formasPagamento,
        $idCurso = null
    )
    {
        $idescola = intval($idescola);

        if ($idCurso) {
            $idCurso = intval($idCurso);
        }

        unset($this->monitora_dadosantigos);
        unset($this->monitora_dadosnovos);

        $this->executaSql('BEGIN');

        //Irá retornar as formas de pagamento que a escola tem e não foi selecionada para mantê-las
        $this->sql = 'SELECT
                idescola_forma_pagamento
            FROM
                escolas_formas_pagamento
            WHERE
                idescola = "' . $idescola . '"
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
                    escolas_formas_pagamento
                SET
                    ativo = "N"
                WHERE
                    idescola_forma_pagamento = ' . $var['idescola_forma_pagamento'] . '
            ';
            $desassociar = $this->executaSql($sql);

            if ($desassociar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = $GLOBALS['config']['monitoramento']['onde_escolas_formas_pagamento'];
                $this->monitora_qual = $var['idescola_forma_pagamento'];
                $this->Monitora();
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $this->sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }

        foreach ($formasPagamento as $ind => $var) {
            $this->sql = 'SELECT
                    idescola_forma_pagamento,
                    ativo
                FROM
                    escolas_formas_pagamento
                WHERE
                    idescola = "' . (int)$idescola . '"
                    AND forma_pagamento = "' . $var . '"
            ';

            if ($idCurso) {
                $this->sql .= ' AND idcurso = "' . (int)$idCurso . '" ';
            }

            $jaExiste = $this->retornarLinha($this->sql);

            if ($jaExiste['idescola_forma_pagamento']) {
                $this->sql = 'UPDATE
                        escolas_formas_pagamento
                    SET
                        ativo = "S"
                    WHERE
                        idescola_forma_pagamento = "' . $jaExiste['idescola_forma_pagamento'] . '"
                ';
                $associar = $this->executaSql($this->sql);

                $this->monitora_qual = $jaExiste['idescola_forma_pagamento'];
            } else {
                $this->sql = "INSERT INTO
                        escolas_formas_pagamento
                    SET
                        ativo = 'S',
                        data_cad = NOW(),
                        idescola = " . (int)$idescola . ",
                        forma_pagamento = '" . $var . "'
                ";

                if ($idCurso) {
                    $this->sql .= " , idcurso = " . (int)$idCurso;
                }

                $associar = $this->executaSql($this->sql);

                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = $GLOBALS['config']['monitoramento']['onde_escolas_formas_pagamento'];

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

    public function listarTiposContatos()
    {
        $this->retorno = array();
        $this->sql = "SELECT * FROM tipos_contatos where ativo = 'S' order by nome asc";
        $seleciona = mysql_query($this->sql);
        while ($tipo = mysql_fetch_assoc($seleciona)) {
            $this->retorno[] = $tipo;
        }
        return $this->retorno;
    }

    public function listarContatos()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
                            escolas_contatos c
                            INNER JOIN tipos_contatos tc ON (c.idtipo = tc.idtipo)
                        where c.ativo = 'S' and c.idescola = " . intval($this->id);

        $this->groupby = "c.idescola";
        return $this->retornarLinhas();
    }

    public function adicionarContato()
    {

        if (!$this->post["idtipo"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_tipo_vazio';
            return $this->retorno;
        }

        if (!$this->post["valor"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_valor_vazio';
            return $this->retorno;
        }

        $this->retorno = array();
        $this->sql = "insert into escolas_contatos set
                        data_cad=now(), ativo='S', idescola='" . $this->id . "', idtipo='" . $this->post["idtipo"] . "', valor='" . $this->post["valor"] . "'";
        $cadastrar = $this->executaSql($this->sql);
        if ($cadastrar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 70;
            $this->monitora_qual = mysql_insert_id();
            $this->Monitora();
        } else {
            $this->retorno["sucesso"] = false;
        }
        return $this->retorno;
    }

    public function RemoverContato()
    {
        $this->sql = "update escolas_contatos set ativo='N' where idcontato='" . intval($this->post["remover"]) . "' and idescola = " . intval($this->id);
        if ($this->executaSql($this->sql)) {
            $remover["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 70;
            $this->monitora_qual = $this->post["remover"];
            $this->Monitora();
        } else {
            $remover["sucesso"] = false;
        }

        return $remover;
    }

    public function ListarEscolasPorCidade()
    {
        $cidades = array();

        $this->sql = 'SELECT
                        c.idcidade,
                        c.nome
                    FROM
                        escolas p
                        INNER JOIN cidades c ON (p.idcidade = c.idcidade)
                    WHERE
                        p.ativo = "S"
                    GROUP BY c.idcidade, c.nome';

        $this->ordem_campo = 'c.nome';
        $this->ordem = 'ASC';
        $this->limite = -1;
        $cidades = $this->retornarLinhas();

        foreach ($cidades as $ind => $cidade) {
            $this->sql = 'SELECT
                            idescola,
                            nome_fantasia
                        FROM
                            escolas
                        WHERE
                            idcidade = ' . $cidade['idcidade'] . ' AND
                            ativo = "S"';
            $this->ordem_campo = 'nome_fantasia';
            $this->ordem = 'ASC';
            $this->limite = -1;
            $cidades[$ind]['escolas'] = $this->retornarLinhas();
        }

        return $cidades;
    }

    public function AlterarMeusdados()
    {
        $erros = $this->buscarErros();
        if ($erros) {
            $retorno['erro'] = true;
            $retorno['erros'] = $erros;
            return $retorno;
        }

        if ($this->post['avatar']["tmp_name"]) {
            $validar = $this->ValidarArquivo($this->post['avatar']);
            $extensao = strtolower(strrchr($this->post['avatar']["name"], "."));
            if ($validar || ($extensao != ".jpg" && $extensao != ".jpeg" && $extensao != ".gif" && $extensao != ".png" && $extensao != ".bmp")) {
                $retorno = array('erro' => true);
                if ($validar) {
                    $retorno["mensagem"] = $validar;
                } else {
                    $retorno["mensagem"] = "avatar_extensao_erro";
                }
                return $retorno;
            } else {
                $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/escolas_avatar";
                $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                $envio = move_uploaded_file($this->post['avatar']["tmp_name"], $pasta . "/" . $nomeServidor);
                chmod($pasta . "/" . $nomeServidor, 0777);
                if ($envio) {
                    $avatar = true;
                    $avatar_nome = $this->post['avatar']["name"];
                    $avatar_tipo = $this->post['avatar']["type"];
                    $avatar_tamanho = $this->post['avatar']["size"];
                } else {
                    $retorno = array('erro' => true, 'mensagem' => '');
                    return $retorno;
                }
            }
        }

        $this->sql = 'SELECT * FROM escolas WHERE idescola = ' . $this->id;
        $linhaAntiga = $this->retornarLinha($this->sql);

        if (senhaSegura($this->post["senha_antiga"], $this->config["chaveLogin"]) == $linhaAntiga["senha"]) {
            $this->sql = "UPDATE
                                escolas
                            SET
                                nome_fantasia = '" . $this->post["nome_fantasia"] . "',
                                email = '" . $this->post["email"] . "',
                                senha = '" . senhaSegura($this->post["senha"], $this->config["chaveLogin"]) . "',
                                ultima_senha = NOW()";

            if ($avatar) {
                $this->sql .= ', avatar_nome = "' . $avatar_nome . '",
                                avatar_servidor = "' . $nomeServidor . '",
                                avatar_tipo = "' . $avatar_tipo . '",
                                avatar_tamanho = ' . $avatar_tamanho;
            }

            $this->sql .= " WHERE idescola = " . $this->id;
            $query = $this->executaSql($this->sql);

            $this->sql = 'SELECT * FROM escolas WHERE idescola = ' . $this->id;
            $linhaNova = $this->retornarLinha($this->sql);

            if ($query) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 2;
                $this->monitora_qual = $this->id;
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->Monitora();
            } else {
                $this->retorno['sucesso'] = false;
                $this->retorno["erros"][] = "error";
            }
        } else {
            $this->retorno['sucesso'] = false;
            $this->retorno["erros"][] = "senha_antiga_invalida";
        }
        return $this->retorno;
    }

    public function retornarFormasPagamentoCfcCurso(
        $idCfc,
        $idCurso,
        $campos = '*'
    )
    {
        $idCfc = intval($idCfc);
        $idCurso = intval($idCurso);

        $sql = " SELECT
                {$campos}
            FROM
                escolas_formas_pagamento
            WHERE
                idescola = {$idCfc}
                AND idcurso = {$idCurso}
                AND ativo = 'S'
        ";

        return $this->retornarLinhasArray($sql);
    }

    public function salvarValoresCursos($idCfc, $arrayValores)
    {
        if (empty($arrayValores)) {
            return false;
        }

        $salvou = false;
        $post = [];
        $this->iniciaTransacao();

        foreach ($arrayValores as $ind => $valores) {
            if ($this->url[0] == 'gestor') {
                $post['disponivel_cfc'] = ($valores['disponivel_cfc'] == "on")
                    ? 'S'
                    : 'N';
            }

/*            $post['avista'] = $valores['avista'];
            $post['aprazo'] = $valores['aprazo'];*/
            $post['parcelas'] = $valores['parcelas'];
            $post['idcfc'] = $idCfc;
            $post['idcurso'] = $valores['idcurso'];
            $post['quantidade_matriculas'] = $valores['quantidade_matriculas'];
            $post['valor_por_matricula'] = $valores['valor_por_matricula'];
            $post['quantidade_matriculas_2'] = $valores['quantidade_matriculas_2'];
            $post['valor_por_matricula_2'] = $valores['valor_por_matricula_2'];
            $post['valor_excedente'] = $valores['valor_excedente'];
            $post['quantidade_faturas_ciclo'] = $valores['quantidade_faturas_ciclo'];
            $post['qtd_parcelas'] = $valores['qtd_parcelas'];
            $post['idvalor_curso'] = (!empty($valores['idvalor_curso'])
                ? $valores['idvalor_curso']
                : null);
            $this->set('post', $post);

            $salvou = $this->salvarDados();

            if ($salvou['sucesso']) {
                $this->modificarFormasPagamento(
                    $idCfc,
                    $valores['forma_pagamento'],
                    $valores['idcurso']
                );
            }
        }

        $this->finalizaTransacao();

        return $salvou;
    }

    public function adicionarArquivo()
    {
        $this->return = array();
        //print_r2($_FILES);
        //print_r2($this->post,1);
        $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/escolas_arquivos/' . $this->id;
        $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
        $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
        mkdir($pasta, 0777);
        chmod($pasta, 0777);
        $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
        chmod($pasta . '/' . $nomeServidor, 0777);
        $db = new Zend_Db_Select(new Zend_Db_MySql);
        if ($envio) {
            $insert = $db->insert('escolas_arquivos', array(
                'data_cad' => 'NOW()',
                'idescola' => $this->id,
                'arquivo_nome' => $db->quote($_FILES["documento"]["name"]),
                'arquivo_servidor' => $db->quote($nomeServidor),
                'arquivo_tipo' => $db->quote($_FILES["documento"]["type"]),
                'arquivo_tamanho' => $db->quote($_FILES["documento"]["size"])
            ));
            $salvar = $this->executaSql((string)$insert);
            if ($salvar) {
                $this->monitora_oque = 1;
                $this->monitora_onde = 212;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();

                $this->return["sucesso"] = true;
                $this->return["mensagem"] = "arquivos_escola_envio_sucesso";
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_escola_envio_erro";
            }
        } else {
            $this->return["sucesso"] = false;
            $this->return["mensagem"] = "arquivos_escola_envio_erro";
        }
        return $this->return;
    }

    public function removerArquivoPastaVirtual()
    {
        $this->retorno = array();
        $this->sql = "UPDATE escolas_arquivos SET ativo ='N' WHERE idarquivo = {$this->idarquivo} AND idescola = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->monitora_oque = 3;
            $this->monitora_onde = 212;
            $this->monitora_qual = $this->idarquivo;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "arquivo_escola_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivo_escola_remover_erro";
        }
        return $this->retorno;
    }

    public function retornarListaArquivos()
    {
        $this->sql = "SELECT *,idarquivo as iddocumento FROM escolas_arquivos
                    WHERE idescola = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarArquivo()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM escolas_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idescola = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function ListarContratosAssociadas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    escolas_contratos pi
                    inner join contratos i ON (pi.idcontrato = i.idcontrato)
                  where
                    i.ativo = 'S' and
                    pi.ativo= 'S' and
                    pi.idescola = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }

    public function AssociarContrato()
    {
        foreach ($this->post["contratos"] as $idcontrato) {
            $this->sql = "select count(idescola_contrato) as total, idescola_contrato from escolas_contratos where idescola = '" . $this->id . "' and idcontrato = '" . intval($idcontrato) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update escolas_contratos set ativo = 'S' where idescola_contrato = " . $totalAssociado["idescola_contrato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idescola_contrato"];
            } else {
                $this->sql = "insert into escolas_contratos set ativo = 'S', data_cad = now(), idescola = '" . $this->id . "', idcontrato = '" . intval($idcontrato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 174;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function DesassociarContrato()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update escolas_contratos set ativo = 'N' where idescola_contrato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 174;
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function BuscarContratos()
    {
        $this->sql = "select
                    i.idcontrato as 'key',
                    i.nome as value
                  from
                    contratos i
                  where
                                        i.nome LIKE '%" . $this->get["tag"] . "%' and
                    i.ativo = 'S' and
                    not exists (
                      select
                        pi.idescola
                      from
                        escolas_contratos pi
                      where
                        pi.idcontrato = i.idcontrato and
                        pi.idescola = '" . intval($this->id) . "' and
                        pi.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idcontrato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function ContratoPendenteExterno()
    {
        $sql = "SELECT
                    ec.*
                FROM
                    escolas_contratos_gerados ec
                WHERE
                    ec.idescola = '" . $this->id . "'
                    AND (ec.aceito_cfc = 'N' OR ec.aceito_cfc IS NULL)
                    AND ec.ativo = 'S'
                    AND ec.cancelado IS NULL
                ORDER BY ec.data_cad DESC";

        $contratoGerado = mysql_fetch_assoc(mysql_query($sql));
        return $contratoGerado;
    }

    public function ContratoPendente()
    {

        $sql = "SELECT
                    c.*
                FROM
                    contratos c
                WHERE
                    c.ativo = 'S'
                    AND c.gerar_proximo_acesso = 'S'
                    AND c.gerar_cfc = 'S'
                ORDER BY c.data_cad DESC";

        $query = mysql_query($sql);

        while ($linha = mysql_fetch_assoc($query)) {
            $contratos[] = $linha;
        }

        if (count($contratos) < 1) {
            return false;
        }

        foreach ($contratos as $key => $contrato) {

            $sql = "SELECT
                        ec.*
                    FROM
                        escolas_contratos_gerados ec
                    WHERE
                        ec.idescola = '" . $this->id . "'
                        AND ec.idcontrato = '" . $contrato['idcontrato'] . "'
                        AND ec.ativo = 'S'
                    ORDER BY ec.data_cad DESC";

            $contratoGerado = mysql_fetch_assoc(mysql_query($sql));

            if (!$contratoGerado['idescola_contrato'] || $contratoGerado['aceito_cfc'] != 'S') {
                break;
            }
        }

        $aceito = $contratoGerado['aceito_cfc'];

        if (!$contratoGerado['idescola_contrato']) {
            $post['idcontrato'] = $contrato['idcontrato'];
            $this->post = $post;
            $contratoGerado = $this->gerarContrato();
            $gerado = $contratoGerado['sucesso'];
        }

        if ($contratoGerado['idescola_contrato'] && $aceito != 'S') {
            $retorno['sucesso'] = true;
            $retorno['total_contratos'] = count($contratos);
            $retorno['idcontrato'] = $contrato['idcontrato'];
            $retorno['idescola_contrato'] = $contratoGerado['idescola_contrato'];
            return $retorno;
        }

        return false;
    }

    public function retornarContrato($idescola_contrato)
    {
        $this->sql = "SELECT
                           mc.*,
                           ct.nome as tipo,
                           c.nome as contrato,
                           m.data_cad as data_escola
                    FROM
                        escolas_contratos_gerados mc
                    INNER JOIN escolas m ON (m.idescola = mc.idescola)
                    LEFT OUTER JOIN contratos c ON (mc.idcontrato = c.idcontrato)
                    INNER JOIN contratos_tipos ct ON (c.idtipo = ct.idtipo or mc.idtipo = ct.idtipo)
                    WHERE
                        mc.idescola_contrato = '" . $idescola_contrato . "' AND
                        mc.idescola = '" . $this->id . "' AND
                        mc.ativo = 'S'";

        return $this->retornarLinha($this->sql);
    }

    public function gerarContrato($idioma = null)
    {
        $matriculaObj = new Matriculas();

        if ($this->post["idcontrato"]) {
            //CONTRATO
            $contract = new Contratos;
            $this->sql = "SELECT * FROM contratos where idcontrato = " . $this->post["idcontrato"];
            $contrato = $this->retornarLinha($this->sql);
            $documento = $contrato["contrato"];

            $documento = $contract->filtrar($documento);

            //CFC
            $this->sql = "SELECT
                                p.*,
                                e.nome AS estado,
                                c.nome AS cidade,
                                ge.nome AS gerente_estado,
                                gc.nome AS gerente_cidade,
                                re.nome AS responsavel_legal_estado,
                                rc.nome AS responsavel_legal_cidade,
                                s.nome AS sindicato
                            FROM
                                escolas p
                                INNER JOIN sindicatos s ON (s.idsindicato = p.idsindicato)
                                LEFT OUTER JOIN estados e ON (e.idestado = p.idestado)
                                LEFT OUTER JOIN cidades c ON (c.idcidade = p.idcidade)

                                LEFT OUTER JOIN estados ge ON (ge.idestado = p.gerente_idestado)
                                LEFT OUTER JOIN cidades gc ON (gc.idcidade = p.gerente_idcidade)

                                LEFT OUTER JOIN estados re ON (re.idestado = p.responsavel_legal_idestado)
                                LEFT OUTER JOIN cidades rc ON (rc.idcidade = p.responsavel_legal_idcidade)
                            WHERE
                                idescola = " . $this->id;
            $cfc = $this->retornarLinha($this->sql);
            foreach ($cfc as $ind => $val) {
                if ($ind == "responsavel_legal_data_nasc") {
                    $documento = str_ireplace("[[cfc][responsavel_legal_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][responsavel_legal_data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "gerente_data_nasc") {
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "diretor_ensino_data_nasc") {
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "diretor_ensino_idlogradouro") {
                    $documento = str_ireplace("[[cfc][diretor_ensino_logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][diretor_ensino_logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                } elseif ($ind == "gerente_idlogradouro") {
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                } elseif ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[cfc][logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                } else {
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                }
            }
            //FIM CFC

            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $documento = str_ireplace("[[data_geracao_contrato_extenso]]", utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))), $documento);

            //ALUNO
            $this->sql = "SELECT
                                p.*,
                                e.nome AS estado,
                                c.nome AS cidade
                            FROM
                                escolas p
                                LEFT OUTER JOIN estados e ON (e.idestado = p.idestado)
                                LEFT OUTER JOIN cidades c ON (c.idcidade = p.idcidade)
                            WHERE
                                idescola = " . $this->id;
            $aluno = $this->retornarLinha($this->sql);
            foreach ($aluno as $ind => $val) {
                if ($ind == "data_nasc") {
                    $documento = str_ireplace("[[aluno][data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cliente][data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "estado_civil") {
                    $documento = str_ireplace("[[aluno][estado_civil]]", $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$val], $documento);
                    $documento = str_ireplace("[[cliente][estado_civil]]", $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$val], $documento);
                } elseif ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[aluno][logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cliente][logradouro]]", $matriculaObj->retornarNomeLogradouro($val), $documento);
                } else {
                    $documento = str_ireplace("[[aluno][" . $ind . "]]", $val, $documento);
                    $documento = str_ireplace("[[cliente][" . $ind . "]]", $val, $documento);
                }
            }
            //FIM ALUNO

            foreach ($aluno as $ind => $val) {
                if ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[dev_solidario][logradouro]]", "NÃO APLICÁVEL", $documento);
                } else {
                    $documento = str_ireplace("[[dev_solidario][" . $ind . "]]", "NÃO APLICÁVEL", $documento);
                }
            }

            // CAMPOS ADICIONAIS
            $documento = str_ireplace("[[campo_adicional_local]]", nl2br($this->post["campo_adicional_local"]), $documento);
            $documento = str_ireplace("[[campo_adicional_1]]", nl2br($this->post["campo_adicional_1"]), $documento);
            $documento = str_ireplace("[[campo_adicional_2]]", nl2br($this->post["campo_adicional_2"]), $documento);
            $documento = str_ireplace("[[campo_adicional_3]]", nl2br($this->post["campo_adicional_3"]), $documento);
            $documento = str_ireplace("[[campo_adicional_4]]", nl2br($this->post["campo_adicional_4"]), $documento);
            //FIM CAMPOS ADICIONAIS

            $this->retorno = array();
            if ($documento) {
                $data_matricula = $this->retornarDataCadEscola();
                $data_matricula = new DateTime($data_matricula);

                $this->sql = "insert into escolas_contratos_gerados set data_cad = now(), idescola = " . $this->id . ", idcontrato = " . $contrato["idcontrato"] . ", arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";

                $this->sql .= ", idusuario = '" . $this->id . "'";


                $salvar = $this->executaSql($this->sql);
                $idcontratoMatricula = mysql_insert_id();
                if ($salvar) {

                    $pastaContratos = $_SERVER["DOCUMENT_ROOT"] . "/storage/escolas_contratos/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                    if (!is_dir($pastaContratos)) {
                        @mkdir($pastaContratos, 0777, true);
                    }
                    @chmod($pastaContratos, 0777);

                    $id = fopen($pastaContratos . "/" . $idcontratoMatricula . ".html", "w");
                    fwrite($id, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                     <html xmlns="http://www.w3.org/1999/xhtml">
                                     <head>
                                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                        <title>' . $contrato["nome"] . '</title>
                                        <style type="text/css">
                                         .quebra_pagina {
                                            page-break-after:always;
                                        }
                                    </style>
                                </head>
                                <!-- Gerado pelo Alfama Oráculo -->
                                <!-- www.alfamaoraculo.com.br -->
                                <!-- Gerado dia: ' . date("d/m/Y H:i:s") . ' -->
                                <body>');
                    fwrite($id, $documento);
                    fwrite($id, "</body></html>");
                    fclose($id);
                    $this->retorno["sucesso"] = true;
                    $this->retorno["idescola_contrato"] = $idcontratoMatricula;
                    $this->retorno["mensagem"] = "contrato_gerado_sucesso";
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "contrato_vazio";
        }
        return $this->retorno;
    }

    public function retornarDataCadEscola()
    {
        $this->sql = 'SELECT data_cad FROM escolas WHERE idescola = "' . $this->id . '" ';
        $resultado = $this->retornarLinha($this->sql);
        return $resultado['data_cad'];
    }

    public function aceitarContrato($contrato)
    {

        $dadosdousuario = retornaSOBrowser();

        $sql = "UPDATE
                    escolas_contratos_gerados
                SET
                    aceito_cfc = 'S' ,
                    aceito_cfc_data = NOW() ,
                    ip = '" . $dadosdousuario['ip'] . "',
                    navegador = '" . mysql_escape_string($dadosdousuario['navegador']) . "',
                    sistema_operacional = '" . mysql_escape_string($dadosdousuario['so']) . "',
                    navegador_versao = '" . mysql_escape_string($dadosdousuario['navegador_versao']) . "',
                    user_agent = '" . mysql_escape_string($dadosdousuario['user_agent']) . "'
                WHERE
                    idescola_contrato = '" . $contrato . "' ";

        $aceito = $this->executaSql($sql);

        return $aceito;
    }

    /**
     * Função para alterar valor da coluna contratos aceitos da tabela escolas
     * @access public
     * @param int $idescola
     * @param boolean $atualizar
     * @return boolean
     */

    public function alterarSituacaoContratosAceitos($idescola, $atualizar = false)
    {
        try {
            if (!is_numeric($idescola)) {
                throw new InvalidArgumentException('Parâmetro idcfc tem que ser do tipo inteiro.');
            } else {
                $this->sql = "SELECT aceito_cfc, aceito_cfc_data, cancelado FROM escolas_contratos_gerados WHERE idescola = {$idescola}";
                $contratos_gerados = $this->retornarLinhas();
                if (empty($contratos_gerados)) {
                    return false;
                }

                foreach ($contratos_gerados as $contrato_gerado) {
                    if ($contrato_gerado['cancelado']) {
                        continue;
                    } else {
                        if ($contrato_gerado['aceito_cfc'] == 'N') {
                            if ($atualizar) {
                                $this->sql = "UPDATE escolas SET contratos_aceitos = 'N' WHERE idescola = {$idescola}";
                                if (!mysql_query($this->sql)) {
                                    throw new Exception(mysql_error());
                                } else {
                                    return false;
                                }
                            }
                            return false;
                        }
                    }
                }

                $this->sql = "UPDATE escolas SET contratos_aceitos = 'S' WHERE idescola = {$idescola}";

                if (!mysql_query($this->sql)) {
                    throw new Exception(mysql_error());
                };
                return true;
            }
        } catch (InvalidArgumentException $i) {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        } catch (Exception $e) {
            die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => $e->getMessage())));
        }
    }

    public function RetornarContratos()
    {
        $this->sql = "SELECT
                            mc.*,
                            c.nome as contrato,
                            ct.nome as tipo
                          FROM
                            escolas_contratos_gerados mc
                            left outer join contratos c on (mc.idcontrato = c.idcontrato)
                            INNER JOIN contratos_tipos ct on (mc.idtipo = ct.idtipo or c.idtipo = ct.idtipo)
                          where
                            mc.idescola = " . $this->id . " and
                            mc.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function enviarContrato()
    {
        if (!$_FILES["contrato"]["tmp_name"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_arquivo_vazio";
            return $this->retorno;
        }
        if (!(int)$this->post["idtipo"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_tipo_vazio";
            return $this->retorno;
        }
        $this->retorno = array();
        $validar = $this->ValidarArquivo($_FILES["contrato"]);
        $extensao = strtolower(strrchr($_FILES["contrato"]["name"], "."));
        if ($validar || ($extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
            $this->retorno["erro"] = true;
            if ($validar) {
                $this->retorno["mensagem"] = $validar;
            } else {
                $this->retorno["mensagem"] = "contratos_escola_extensao_erro";
            }
            return $this->retorno;
        }
        $data_escola = $this->retornarDataCadEscola();
        $data_escola = new DateTime($data_escola);
        $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/escolas_contratos/" . $data_escola->format('Y') . "/" . $data_escola->format('m') . "/" . $this->id;
        $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
        if (!is_dir($pasta)) {
            @mkdir($pasta, 0777, true);
        }
        @chmod($pasta, 0777);
        $envio = move_uploaded_file($_FILES["contrato"]["tmp_name"], $pasta . "/" . $nomeServidor);
        @chmod($pasta . "/" . $nomeServidor, 0777);
        if (!$envio) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_envio_erro";
            return $this->retorno;
        }
        $this->sql = "INSERT INTO
                            escolas_contratos_gerados
                    SET
                         data_cad = now(),
                         idescola = " . $this->id . ",
                         idtipo = " . (int)$this->post["idtipo"] . ",
                         arquivo = '" . $_FILES["contrato"]["name"] . "',
                         arquivo_tipo = '" . $_FILES["contrato"]["type"] . "',
                         arquivo_tamanho = '" . $_FILES["contrato"]["size"] . "',
                         arquivo_servidor = '" . $nomeServidor . "',
                         arquivo_pasta = '" . $data_escola->format('Y') . "/" . $data_escola->format('m') . "'";
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario = '" . $this->idusuario . "'";
        }
        $salvar = $this->executaSql($this->sql);
        if (!$salvar) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_envio_erro";
            return $this->retorno;
        }
        $this->retorno["sucesso"] = true;
        $this->retorno["mensagem"] = "contratos_escola_envio_sucesso";
        return $this->retorno;
    }

    public function validarContrato($idescola_contrato, $situacao)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $validado = "now()";
            $nao_validado = "null";
        } else if ($situacao == 1) {
            $validado = "null";
            $nao_validado = "now()";
        }
        $this->sql = "update escolas_contratos_gerados set nao_validado = " . $nao_validado . ", validado = " . $validado;
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_validou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idescola_contrato = '" . $idescola_contrato . "' and idescola = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "validou";
            elseif ($situacao == 1)
                $acao = "desvalidou";
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_escola_validado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_validado_erro";
        }
        return $this->retorno;
    }

    public function assinarContrato($idescola_contrato, $situacao)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $assinado = "now()";
            $nao_assinado = "null";
        } else if ($situacao == 1) {
            $assinado = "null";
            $nao_assinado = "now()";
        }
        $this->sql = "update escolas_contratos_gerados set nao_assinado = " . $nao_assinado . ", assinado = " . $assinado;
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_assinou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idescola_contrato = '" . $idescola_contrato . "' and idescola = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "assinou";
            elseif ($situacao == 1)
                $acao = "desassinou";
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_escola_assinado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_assinado_erro";
        }
        return $this->retorno;
    }

    public function cancelarContrato($situacao, $justificativa, $idescola_contrato)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $cancelado = "now()";
        } else {
            $cancelado = "NULL";
        }
        $this->sql = "update escolas_contratos_gerados set cancelado = " . $cancelado . ", justificativa = '" . $justificativa . "'";
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_cancelou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idescola_contrato = '" . $idescola_contrato . "' and idescola = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "cancelou";
            elseif ($situacao == 1)
                $acao = "descancelou";
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_escola_cancelado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_escola_cancelado_erro";
        }
        return $this->retorno;
    }

    public function RetornarContratosGrupos()
    {
        $this->sql = "SELECT
        " . $this->campos . "
        FROM
        contratos c
        where
        c.ativo = 'S' and
        c.ativo_painel = 'S'

        union

        SELECT
        " . $this->campos_2 . "
        FROM
        contratos_grupos gc
        where
        gc.ativo = 'S' and
        gc.ativo_painel = 'S'";
        $this->groupby = "c.idcontrato";
        return $this->retornarLinhas();
    }

    public function enviarEmailBoletoDisponivel($idEscola)
    {
        $this->sql = 'SELECT nome_fantasia, email FROM escolas WHERE idescola = ' . $idEscola;
        $escola = $this->retornarLinha($this->sql);

        $nomePara = utf8_decode($escola["nome_fantasia"]);

//        $message  = "Ol&aacute; <strong>" . $nomePara . "</strong>,
//                    <br /><br />
//                    Seu boleto j&aacute; est&aacute; dispon&iacute;vel para pagamento.
//                    <br /><br />
//                    <a href=\"http://" . $GLOBALS['config']['urlSistemaFixa'] . "/cfc/" . "\">Clique aqui</a> para acessar o painel.";

        $message = "<p>Ol&aacute;, " . $nomePara . "! <br /><br /> Abaixo link do boleto das matr&iacute;culas efetuadas no per&iacute;odo, excluindo-se as matr&iacute;culas na situa&ccedil;&atilde;o &ldquo;pr&eacute;-matr&iacute;cula&rdquo; e &ldquo;cancelada&ldquo;. <br /> Poder&aacute; acessar tamb&eacute;m via " . $GLOBALS["config"]["tituloEmpresa"] . " clicando no menu <strong>Faturas</strong> ou <strong>Relat&oacute;rio de Transa&ccedil;&otilde;es das Faturas</strong>. <br /><br />Para visualizar o boleto,
                       <a href=\"https://" . $GLOBALS['config']['urlSistemaFixa'] . "/cfc/" . "\"  >clique aqui!</a>
                       &nbsp;<br /><br />Qualquer dificuldade conte com a gente pelo telefone <strong>" . $GLOBALS['config']['telefone'] . "</strong> ou e-mail <a href=\"mailto:" . $GLOBALS['config']['emailParceria'] . "\"> <strong> " . $GLOBALS['config']['emailParceria'] . " </strong> </a></p>";

        $emailPara = $escola["email"];
        $assunto = utf8_decode("Boleto disponível - " . date("d/m/Y"));

        $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
        $emailDe = $GLOBALS["config"]["emailSistema"];

        return $this->enviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
    }

    public function cfcBloqueado()
    {
        $sql = "SELECT acesso_bloqueado FROM escolas WHERE acesso_bloqueado = 'S' AND idescola = " . $this->id;
        return mysql_fetch_assoc(mysql_query($sql));
    }

    public function retornarHistoricoBloqueado()
    {
        $sql = "SELECT
                    e.*, u.nome
                FROM escolas_historico e
                LEFT JOIN usuarios_adm u ON (e.idusuario = u.idusuario)
                WHERE
                    e.idescola = " . $this->id . "
                ORDER BY e.data_cad DESC ";

        $query = mysql_query($sql);

        $historico = array();
        $retorno = array();
        while ($linha = mysql_fetch_assoc($query)) {
            $retorno['nome'] = ($linha['nome']) ? $linha['nome'] : 'Sistema';
            $retorno['quando'] = formataData($linha['data_cad'], 'br', 1);

            $retorno['situacao'] = '<span class="label btn-danger">Bloqueou</span>';
            if ($linha['acesso_bloqueado'] == 'N') {
                $retorno['situacao'] = '<span class="label btn-success">Liberou</span>';
            }

            $historico[] = $retorno;
        }

        return $historico;
    }

    public function bloquear($bloqueio)
    {
        $retorno['sucesso'] = false;
        $retorno['situacao'] = $bloqueio;

        $sql = "UPDATE escolas SET acesso_bloqueado = '" . $bloqueio . "' WHERE idescola = " . $this->id;
        $retorno['sucesso'] = mysql_query($sql);

        if ($retorno['sucesso']) {
            $sql = "INSERT INTO
                        escolas_historico
                    SET data_cad = NOW() , idusuario = " . $this->idusuario . " , acesso_bloqueado = '" . $bloqueio . "' , idescola = " . $this->id;
            $retorno['sucesso'] = mysql_query($sql);
        }

        return $retorno;
    }

    public function listarEstadosCidadesAssociadas()
    {
        $this->sql = 'SELECT
                ' . $this->campos . '
            FROM
                escolas_estados_cidades eec
                INNER JOIN estados e ON (e.idestado = eec.idestado)
                INNER JOIN cidades c ON (c.idcidade = eec.idcidade)
            WHERE
                eec.idescola = ' . intval($this->id) . ' AND
                eec.ativo = "S"';

        $this->ordem_campo = 'e.nome ASC, c.nome';
        $this->ordem = 'ASC';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function listarMensagens()
    {
        $this->sql = 'SELECT
                ' . $this->campos . '
            FROM
                cfc_mensagens cm
                left join usuarios_adm u on (cm.idusuario = u.idusuario)

            WHERE
                cm.idescola = ' . intval($this->id) . ' AND
                cm.ativo = "S"';

        $this->ordem_campo = 'data_cad';
        $this->ordem = 'DESC';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function removerMensagem($idmensagem)
    {


        $this->sql = "update
        cfc_mensagens
        set
        ativo = 'N'
        where
         idmensagem = '" . $idmensagem . "'
        AND idusuario = '" . $this->idusuario . "'";

        $remover = $this->executaSql($this->sql);

        if ($remover) {
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_removida_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_removida_erro";
        }
        return $this->retorno;
    }

    public function cadastrarMensagem()
    {

        $id = (int)$this->id;
        $message = addslashes($_POST['mensagem']);
        $this->sql = "INSERT INTO
                        cfc_mensagens
                     SET
                        data_cad = NOW(),

                        mensagem = '{$message}'
                        ";

        $this->sql .= ", idusuario = '{$this->idusuario}'";

        $this->sql .= ", idescola = '{$this->id}'";

        $salvar = $this->executaSql($this->sql);
        $idmensagem = mysql_insert_id();

        if ($salvar) {
            $this->retorno['sucesso'] = true;
            $this->retorno['id'] = $idmensagem;
            $this->retorno['mensagem'] = 'mensagem_adicionada_sucesso';
        } else {
            $this->retorno['sucesso'] = false;
            $this->retorno['mensagem'] = 'mensagem_adicionada_erro';
        }
        return $this->retorno;
    }


    public function buscarEstadosCidades()
    {
        $this->sql = 'SELECT
                CONCAT_WS("|", e.idestado, c.idcidade) AS "key",
                CONCAT_WS(" - ", e.nome, c.nome) AS value
            FROM
                estados e
                INNER JOIN cidades c ON (c.idestado = e.idestado)
            WHERE
                (e.nome LIKE "%' . $this->get['tag'] . '%" || c.nome LIKE "%' . $this->get['tag'] . '%") AND
                NOT EXISTS (
                    SELECT
                        eec.idescola_estado_cidade
                    FROM
                        escolas_estados_cidades eec
                    WHERE
                        eec.idescola = ' . intval($this->id) . ' AND
                        eec.idestado = e.idestado AND
                        eec.idcidade = c.idcidade AND
                        eec.ativo = "S"
                )';

        $this->ordem_campo = 'e.nome ASC, c.nome';
        $this->ordem = 'ASC';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function associarEstadosCidades()
    {
        foreach ($this->post['estados_cidades'] as $ind => $var) {
            list($idestado, $idcidade) = explode('|', $var);

            $sql = 'SELECT COUNT(idescola_estado_cidade) AS total, idescola_estado_cidade
                FROM escolas_estados_cidades WHERE idescola = ' . $this->id . ' AND
                idestado = ' . $idestado . ' AND idcidade = ' . $idcidade;
            $totalAssociado = $this->retornarLinha($sql);

            if ($totalAssociado['total'] > 0) {
                $sql = 'UPDATE escolas_estados_cidades SET ativo = "S"
                    WHERE idescola_estado_cidade = ' . $totalAssociado['idescola_estado_cidade'];
                $associar = $this->executaSql($sql);
                $this->monitora_qual = $totalAssociado['idescola_estado_cidade'];
            } else {
                $sql = 'INSERT INTO escolas_estados_cidades SET ativo = "S", data_cad = NOW(),
                    idescola = ' . $this->id . ', idestado = ' . $idestado . ', idcidade = ' . $idcidade;
                $associar = $this->executaSql($sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 285;
                $this->Monitora();
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }

        return $this->retorno;
    }

    public function desassociarEstadosCidades()
    {
        include_once '../includes/validation.php';
        $regras = array();

        $regras[] = 'required,remover,remover_vazio';
        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno['erro'] = true;
            $this->retorno['erros'] = $erros;
        } else {
            $this->sql = 'UPDATE escolas_estados_cidades SET ativo = "N" WHERE idescola_estado_cidade = ' . (int)$this->post['remover'];
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 285;
                $this->monitora_qual = (int)$this->post['remover'];
                $this->Monitora();
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $this->sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }
        return $this->retorno;
    }
}
