<?php
class Vendedores extends Core
{

    function ListarTodas()
    {
        $this->sql = "select " . $this->campos . " from vendedores v
            LEFT OUTER JOIN estados e on (v.idestado = e.idestado) 
            LEFT OUTER JOIN cidades c on (v.idcidade = c.idcidade) 
         where v.ativo = 'S'";

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    } elseif ($campo[0] == 4) {
                        $this->sql .= " and (
                                SELECT
                                    COUNT(ve.idvendedor_escola)
                                FROM
                                    vendedores_escolas ve
                                    INNER JOIN escolas es ON (ve.idescola = es.idescola)
                                WHERE
                                    ve.idvendedor = v.idvendedor AND
                                    ve.ativo = 'S' AND
                                    " . $campo[1] . " = '" . $valor . "'
                            ) > 0";
                    }
                }
            }
        }
        $this->mantem_groupby = true;
        $this->groupby = "v.idvendedor";
        return $this->retornarLinhas();
    }


    function Retornar()
    {
        $this->sql = "select " . $this->campos . " from vendedores where ativo = 'S' and idvendedor = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function retornarVendedorPadrao()
    {
        $this->sql = "select " . $this->campos . " from vendedores where ativo = 'S' and atendente_padrao = 'S'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        if ($this->post["documento_tipo"] == "cnpj") {
            $this->config["formulario"][1]["campos"][1]["validacao"] = $this->config["formulario"][1]["campos"][2]["validacao"];
            $this->post["documento"] = $this->post["documento_cnpj"];

            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][1]["campos"][2]);
        } else {
            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][1]["campos"][2]);
        }

        $this->config["formulario"][0]['campos'][]= array(
            "id" => "ultima_senha",
            "nome" => "ultima_senha",
            "nomeidioma" => "ultima_senha",
            "tipo" => "input",
            "valor" => "ultima_senha",
            "class" => "span2",
            "banco" => true,
            "banco_string" => true
        );
        return $this->SalvarDados();
    }

    function Modificar()
    {
        if ($this->post["documento_tipo"] == "cnpj") {
            $this->config["formulario"][1]["campos"][1]["validacao"] = $this->config["formulario"][1]["campos"][2]["validacao"];
            $this->post["documento"] = $this->post["documento_cnpj"];

            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][1]["campos"][2]);
        } else {
            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][1]["campos"][2]);
        }
        $this->config["formulario"][0]['campos'][]= array(
            "id" => "ultima_senha",
            "nome" => "ultima_senha",
            "nomeidioma" => "ultima_senha",
            "tipo" => "input",
            "valor" => "ultima_senha",
            "class" => "span2",
            "banco" => true,
            "banco_string" => true
        );
        return $this->SalvarDados();
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function AtivarLogin($situacao)
    {

        if ($situacao <> "S" && $situacao <> "N") {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update vendedores set ativo_login = '" . mysql_real_escape_string($situacao) . "' where idvendedor = '" . intval($this->id) . "'";
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if ($executa) {
            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $info['sucesso'] = true;
            $info['situacao'] = $linhaNova["ativo_login"];
        } else {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
        }

        return json_encode($info);
    }

    function ResetarSenha($confirmacao, $enviarEmail, $exibirNovaSenha)
    {

        if (!$confirmacao) {
            $info['sucesso'] = false;
            $info['confirmacao'] = $confirmacao;
            $info['enviar_email'] = $enviarEmail;
            $info['exibir_nova_senha'] = $exibirNovaSenha;
            $info['mensagem'] = "Erro ao tentar resetar a senha.";
            return json_encode($info);
        }

        $novaSenha = gerarNovaSenha();
        $senha = senhaSegura($novaSenha, $this->config["chaveLogin"]);

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update vendedores set senha = '" . $senha . "' where idvendedor = " . intval($this->id);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if ($modificou) {

            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $info['sucesso'] = true;
            $info['confirmacao'] = $confirmacao;
            $info['enviar_email'] = $enviarEmail;
            $info['exibir_nova_senha'] = $exibirNovaSenha;
            $info['nova_senha'] = $novaSenha;

            if ($enviarEmail) {
                $message = 'Algu&eacute;m, possivelmente voc&ecirc;, solicitou uma nova senha de acesso ao sistema.
                    <br />
                    <br />
                    <strong>Acesse:</strong> <a href="' . $this->config["urlSistema"] . '/atendente">' . $this->config["urlSistema"] . '/atendente</a>
                    <br />
                    <br />
                    <strong>E-mail de acesso:</strong> ' . $linhaNova["email"] . '
                    <br />
                    <strong>Senha de acesso:</strong> ' . $novaSenha;

                $nomePara = utf8_decode($linhaNova["nome"]);
                $emailPara = $linhaNova["email"];
                $assunto = utf8_decode("ESQUECI MINHA SENHA");

                $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                $emailDe = $GLOBALS["config"]["emailSistemaReserva"];

                if ($this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, "layout"))
                    $info['sucesso_email'] = 'sucesso_email';
            }
        } else {
            $info['sucesso'] = false;
            $info['confirmacao'] = $confirmacao;
            $info['enviar_email'] = $enviarEmail;
            $info['exibir_nova_senha'] = $exibirNovaSenha;
        }

        return json_encode($info);
    }

    function ListarTiposContatos()
    {
        $this->sql = "select * from tipos_contatos where ativo = 'S'";
        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "nome";
        return $this->retornarLinhas();
    }

    function ListarContatos()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    vendedores_contatos c
                    inner join tipos_contatos tc ON (c.idtipo = tc.idtipo)
                  where
                    c.ativo = 'S' and
                    c.idvendedor = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "c.idcontato";
        return $this->retornarLinhas();
    }

    function adicionarContato()
    {
        $this->retorno = array();
        $this->sql = "insert into
                    vendedores_contatos
                  set
                    data_cad = now(),
                    ativo = 'S',
                    idvendedor = '" . $this->id . "',
                    idtipo = '" . $this->post["idtipo"] . "',
                    valor = '" . $this->post["valor"] . "'";
        $cadastrar = $this->executaSql($this->sql);
        if ($cadastrar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 67;
            $this->monitora_qual = mysql_insert_id();
            $this->Monitora();
        } else {
            $this->retorno["sucesso"] = false;
        }
        return $this->retorno;
    }

    function RemoverContato()
    {
        $this->sql = "update vendedores_contatos set ativo = 'N' where idcontato = '" . intval($this->post["remover"]) . "' and idvendedor = " . intval($this->id);
        if ($this->executaSql($this->sql)) {
            $remover["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 67;
            $this->monitora_qual = $this->post["remover"];
            $this->Monitora();
        } else {
            $remover["sucesso"] = false;
        }

        return $remover;
    }

    function BloquearVendas($situacao)
    {

        if ($situacao <> "S" && $situacao <> "N") {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update vendedores set venda_bloqueada = '" . mysql_real_escape_string($situacao) . "' where idvendedor = '" . intval($this->id) . "'";
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from vendedores where idvendedor = " . intval($this->id);
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if ($executa) {

            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $info['sucesso'] = true;
            $info['situacao'] = $linhaNova["venda_bloqueada"];
        } else {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
        }

        return json_encode($info);
    }

    function AssociarSindicato()
    {
        foreach ($this->post["sindicatos"] as $idsindicato) {
            $this->sql = "select count(idvendedor_sindicato) as total, idvendedor_sindicato from vendedores_sindicatos where idvendedor = '" . $this->id . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update vendedores_sindicatos set ativo = 'S' where idvendedor_sindicato = " . $totalAssociado["idvendedor_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idvendedor_sindicato"];
            } else {
                $this->sql = "insert into vendedores_sindicatos set ativo = 'S', data_cad = now(), idvendedor = '" . $this->id . "', idsindicato = '" . intval($idsindicato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 68;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }


    function DesassociarSindicato()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update vendedores_sindicatos set ativo = 'N' where idvendedor_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 68;
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

    function ListarSindicatosAssociadas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    vendedores_sindicatos vi
                    inner join sindicatos i ON (vi.idsindicato = i.idsindicato)
                  where
                    i.ativo = 'S' and
                    vi.ativo= 'S' and
                    vi.idvendedor = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }

    function BuscarSindicatos()
    {
        $this->sql = "select
                    i.idsindicato as 'key',
                    i.nome_abreviado as value
                  from
                    sindicatos i
                  where
                    i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' AND
                    i.ativo = 'S' and
                    not exists (
                      select
                        vi.idvendedor
                      from
                        vendedores_sindicatos vi
                      where
                        vi.idsindicato = i.idsindicato and
                        vi.idvendedor = '" . intval($this->id) . "' and
                        vi.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function ListarCursosAss()
    {
        $this->sql = "SELECT
                        " . $this->campos . "
                      FROM
                        cursos c
                        INNER JOIN cursos_sindicatos ci ON ci.idcurso = c.idcurso
                        INNER JOIN vendedores_sindicatos vi ON (ci.idsindicato = vi.idsindicato)
                        INNER JOIN sindicatos i ON i.idsindicato = vi.idsindicato
                      WHERE
                        vi.ativo = 'S' and ci.ativo = 'S' and c.ativo = 'S' and c.ativo_painel = 'S' and
                        vi.idvendedor = " . intval($this->id);
        $this->groupby = "c.idcurso";
        return $this->retornarLinhas();
    }

    function retornarVendedoresSindicatos($idsindicato) {
        $sql = "select v.idvendedor, v.nome
                                from vendedores v
                                    inner join vendedores_sindicatos vi on (v.idvendedor = vi.idvendedor)
                                where vi.ativo = 'S' and v.ativo = 'S' and vi.idsindicato = " . $idsindicato;
        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    public function ListarEscolasAssociadas()
    {
        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        vendedores_escolas ve
                    INNER JOIN escolas e ON (ve.idescola = e.idescola)
                  where
                    e.ativo = 'S' and
                    ve.ativo= 'S' and
                    ve.idvendedor = " . intval($this->id);
        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "e.nome_fantasia";
        return $this->retornarLinhas();
    }

    public function BuscarEscolas()
    {
        $this->sql = "select
                    e.idescola as 'key',
                    e.nome_fantasia as value
                  from
                    escolas e
                  where
                    e.nome_fantasia LIKE '%" . $this->get["tag"] . "%' AND
                    e.ativo = 'S' and
                    not exists (
                      select
                        ve.idvendedor
                      from
                        vendedores_escolas ve
                      where
                        ve.idescola = e.idescola and
                        ve.idvendedor = '" . intval($this->id) . "' and
                        ve.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "e.nome_fantasia";
        $this->groupby = "e.idescola";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    public function AssociarEscola()
    {
        foreach ($this->post["escolas"] as $idescola) {
            $this->sql = "select count(idvendedor_escola) as total, idvendedor_escola from vendedores_escolas where idvendedor = '" . $this->id . "' and idescola = '" . intval($idescola) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update vendedores_escolas set ativo = 'S' where idvendedor_escola = " . $totalAssociado["idvendedor_escola"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idvendedor_escola"];
            } else {
                $this->sql = "insert into vendedores_escolas set ativo = 'S', data_cad = now(), idvendedor = '" . $this->id . "', idescola = '" . intval($idescola) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 68;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function DesassociarEscola()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update vendedores_escolas set ativo = 'N' where idvendedor_escola = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 68;
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

    public function retornarVendedoresEscolas($idescola) {
        $sql = "select v.idvendedor, v.nome
                                from vendedores v
                                    inner join vendedores_escolas ve on (v.idvendedor = ve.idvendedor)
                                where ve.ativo = 'S' and v.ativo = 'S' and ve.idescola = " . $idescola;
        $resultado = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    public function definirAtendentePadrao($idAtendente) {
        $this->sql = "update vendedores set atendente_padrao = 'N' where idvendedor != '" . intval($idAtendente) . "'";
        $executa = $this->executaSql($this->sql);
    }
    public function RemoverImgAvatar($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
	}
}
