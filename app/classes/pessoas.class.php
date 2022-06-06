<?php
class Pessoas extends Core
{

    var $idpessoa = null;

    function ListarTodas()
    {
        $this->sql = "select
            " . $this->campos . "
        from
            pessoas p
        left outer join paises pa on (p.idpais = pa.idpais)
        where
            p.ativo = 'S'";
        $this->aplicarFiltrosBasicos();
        $this->groupby = "idpessoa";
        return $this->retornarLinhas();
    }

    function retornar()
    {
        $this->sql = 'SELECT
    	        ' . $this->campos . '
    	    FROM
    			pessoas p
                LEFT OUTER JOIN paises pa ON (p.idpais = pa.idpais)
                LEFT OUTER JOIN estados est ON (est.idestado = p.idestado)
                LEFT OUTER JOIN cidades cid ON (cid.idcidade = p.idcidade)
                LEFT OUTER JOIN logradouros l ON (l.idlogradouro = p.idlogradouro)
    	    WHERE
    			p.idpessoa = "' . $this->id . '" AND
                p.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    function RetornarPorCPF($cpf)
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					pessoas p
					left outer join paises pa on (p.idpais = pa.idpais)
				  where
					p.documento = " . $cpf . " and
					p.ativo = 'S'
				  order by idpessoa desc
				  limit 1";
        return $this->retornarLinha($this->sql);
    }

    function retornarPorEmailCpf($email, $cpf)
    {
        $auxSql = null;
        if(!empty($email)){
            $auxSql .= " AND p.email = '" . $email . "'";
        }

        $cpfSql = null;
        if(!empty($cpf)){
            $auxSql .= " AND p.documento = '" . $cpf . "'";
        }

        if(!empty($cpf) && !empty($email)){
            $auxSql = "AND (p.email = '" . $email . "' OR p.documento = '" . $cpf . "')";
        }


        $this->sql = "select
                    " . $this->campos . "
                  from
                    pessoas p
                    left outer join paises pa on (p.idpais = pa.idpais)
                  where
                    p.ativo = 'S'" . $auxSql . "
                  order by idpessoa desc
                  limit 1";

        return $this->retornarLinha($this->sql);
    }

    public function retornarCidades($idestado = null)
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    cidades ci
                    inner join estados es on (es.idestado = ci.idestado)
                    inner join paises pa on (es.idpais = pa.idpais) ";

        $this->sql.= !empty($idestado) ? " where ci.idestado = ".$idestado : "";

        $this->retorno = $this->retornarLinhas($this->sql);

        return $this->retorno;
    }

    function Cadastrar()
    {
        $this->post['documento'] = str_replace(array(".", "-","/"),"",$this->post['documento']);
        return $this->SalvarDados();
    }

    function Modificar()
    {
        if (verificaPermissaoAcesso(true)) {
            return $this->SalvarDados();
        }
        return false;
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    function RetornarPaises()
    {
        $this->sql = "select idpais as 'key', nome as value from paises where nome like '%" . $_GET["tag"] . "%'";
        $this->limite = -1;
        $this->ordem_campo = "nome";
        $this->groupby = "nome";
        $dados = $this->retornarLinhas();

        return json_encode($dados);
    }

    function AtivarLogin($situacao)
    {

        if ($situacao <> "S" && $situacao <> "N") {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->sql = "select * from pessoas where idpessoa = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update pessoas set ativo_login = '" . mysql_real_escape_string($situacao) . "' where idpessoa = '" . intval($this->id) . "'";
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from pessoas where idpessoa = " . intval($this->id);
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

    /**
     * [gerarSenhaLoja]
     * @return [srting]
     */
    public function gerarSenhaLoja() {
        $this->sql = "SELECT documento FROM pessoas WHERE idpessoa = " . intval($this->id);
        $novaSenha = $this->retornarLinha($this->sql)['documento'];
        $senha = senhaSegura($novaSenha, $this->config["chaveLogin"]);

        $this->sql = "UPDATE pessoas SET senha = '" . $senha . "' WHERE idpessoa = " . intval($this->id);
        $modificou = $this->executaSql($this->sql);

        return $novaSenha;
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

        $this->sql = "select * from pessoas where idpessoa = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update pessoas set senha = '" . $senha . "' where idpessoa = " . intval($this->id);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from pessoas where idpessoa = " . intval($this->id);
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
					<strong>Acesse:</strong> <a href="' . $this->config["urlSistema"] . '/aluno">' . $this->config["urlSistema"] . '/aluno</a>
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

    //FUNCOES DE CONTATO DA PESSOA
    function ListarTiposContatos()
    {
        $this->retorno = array();
        $this->sql = "SELECT * FROM tipos_contatos where ativo = 'S' order by nome asc";
        $seleciona = mysql_query($this->sql);
        while ($tipo = mysql_fetch_assoc($seleciona)) {
            $this->retorno[] = $tipo;
        }
        return $this->retorno;
    }

    function ListarContatos()
    {
        $this->sql = "SELECT " . $this->campos . " FROM
							pessoas_contatos c
							INNER JOIN tipos_contatos tc ON (c.idtipo = tc.idtipo)
						where c.ativo = 'S' and c.idpessoa = " . intval($this->id);

        $this->groupby = "c.idpessoa";
        return $this->retornarLinhas();
    }

    function adicionarContato()
    {
        $this->retorno = array();
        $this->sql = "insert into pessoas_contatos set
						data_cad=now(), ativo='S', idpessoa='" . $this->id . "', idtipo='" . $this->post["idtipo"] . "', valor='" . $this->post["valor"] . "'";
        $cadastrar = $this->executaSql($this->sql);
        if ($cadastrar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 63;
            $this->monitora_qual = mysql_insert_id();
            $this->Monitora();
        } else {
            $this->retorno["sucesso"] = false;
        }
        return $this->retorno;
    }

    function RemoverContato()
    {
        $this->sql = "update pessoas_contatos set ativo='N' where idcontato='" . intval($this->post["remover"]) . "' and idpessoa = " . intval($this->id);
        if ($this->executaSql($this->sql)) {
            $remover["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 63;
            $this->monitora_qual = $this->post["remover"];
            $this->Monitora();
        } else {
            $remover["sucesso"] = false;
        }

        return $remover;

    }

    function BuscarPessoa()
    {

        $this->sql = "select
						p.idpessoa as 'key', concat(p.nome, ' (', p.documento, ')') as value
					  from
						pessoas p
					  where
					  	(p.nome like '%" . $_GET["tag"] . "%' or p.documento like '%" . $_GET["tag"] . "%') AND p.ativo = 'S' AND
					  NOT EXISTS (SELECT pa.idpessoa FROM pessoas_associacoes pa WHERE pa.idpessoa_associada = '" . $this->id . "' AND pa.idpessoa = p.idpessoa AND pa.ativo = 'S') AND
					  NOT EXISTS (SELECT paa.idpessoa_associada FROM pessoas_associacoes paa WHERE paa.idpessoa = '" . $this->id . "' AND  paa.idpessoa_associada = p.idpessoa AND paa.ativo = 'S')
					  AND p.idpessoa <> '" . $this->id . "'";

        //$this->sql = "select idempreendimento as 'key', nome as value from empreendimentos where nome like '%".$_GET["tag"]."%'";
        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        //$this->groupby = "plidpessoa";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    function AssociarPessoas($idpessoa, $arrayPessoas, $tipoAssociacao)
    {
        foreach ($arrayPessoas as $ind => $idpessoaAssociada) {
            $this->sql = "select count(idpessoa_associacao) as total, idpessoa_associacao from pessoas_associacoes where ((idpessoa = '" . intval($idpessoa) . "' and idpessoa_associada = '" . intval($idpessoaAssociada) . "') or (idpessoa = '" . intval($idpessoaAssociada) . "' and idpessoa_associada = '" . intval($idpessoa) . "')) and ativo = 'S'";
            $totalAssAtivo = $this->retornarLinha($this->sql);
            if ($totalAssAtivo["total"] == 0) {
                $this->sql = "select count(idpessoa_associacao) as total, idpessoa_associacao from pessoas_associacoes where ((idpessoa = '" . intval($idpessoa) . "' and idpessoa_associada = '" . intval($idpessoaAssociada) . "') or (idpessoa = '" . intval($idpessoaAssociada) . "' and idpessoa_associada = '" . intval($idpessoa) . "')) and idtipo = '" . $tipoAssociacao . "'";
                $totalAss = $this->retornarLinha($this->sql);
                if ($totalAss["total"] > 0) {
                    $this->sql = "update pessoas_associacoes set ativo = 'S' where idpessoa_associacao = " . $totalAss["idpessoa_associacao"];
                    $associar = $this->executaSql($this->sql);
                    $this->monitora_qual = $totalAss["idpessoa_associacao"];
                } else {
                    // se por alguma eventualidade uma pessoa
                    // não cadastrada tentar ser associada
                    $idpessoaAssociada = (int)$idpessoaAssociada;
                    if (0 == $idpessoaAssociada) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'asdas';
                        $this->retorno["erros"][] = 'asdasdasdasd';
                        return $this->retorno;
                    }
                    $this->sql = "insert into pessoas_associacoes set ativo = 'S', data_cad = now(), idpessoa = '" . intval($idpessoa) . "', idpessoa_associada = '" . intval($idpessoaAssociada) . "', idtipo = '" . $tipoAssociacao . "'";
                    $associar = $this->executaSql($this->sql);
                    $this->monitora_qual = mysql_insert_id();
                }
                if ($associar) {
                    $this->retorno["sucesso"] = true;
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 65;
                    $this->Monitora();
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $this->sql;
                    $this->retorno["erros"][] = mysql_error();
                }
            } else {
                $this->retorno["sucesso"] = true;
            }

        }
        return $this->retorno;
    }

    /*function ListarPessoasAss()
    {
        $this->sql = "(SELECT " . $this->campos . " FROM
							pessoas p
							INNER JOIN pessoas_associacoes pa ON (p.idpessoa = pa.idpessoa)
							INNER JOIN pessoas pp ON (pa.idpessoa_associada = pp.idpessoa)
							INNER JOIN tipos_associacoes pta ON (pa.idtipo = pta.idtipo)
						where pa.ativo='S' and p.idpessoa = " . intval($this->id) . ")
						UNION ALL
						(SELECT " . $this->campos . " FROM
							pessoas p
							INNER JOIN pessoas_associacoes pa ON (p.idpessoa = pa.idpessoa_associada)
							INNER JOIN pessoas pp ON (pa.idpessoa = pp.idpessoa)
							INNER JOIN tipos_associacoes pta ON (pa.idtipo = pta.idtipo)
						where pa.ativo='S' and p.idpessoa = " . intval($this->id) . ")";

        $this->groupby = "pa.idpessoa_associacao";
        return $this->retornarLinhas();
    }*/

    function DesassociarPessoas()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update pessoas_associacoes set ativo = 'N' where idpessoa_associacao = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 65;
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

    function verificaCadastro($json = false, $idpessoa = NULL)
    {
        if ($_POST["cpf"]) $this->post["cpf"] = $_POST["cpf"];
        if ($_POST["cnpj"]) $this->post["cnpj"] = $_POST["cnpj"];
        if ($_POST["campos"]) $this->campos = $_POST["campos"];

        if ($this->post["cpf"]) {

            $documento = str_replace("-", "", str_replace(".", "", $this->post["cpf"]));
            $documento_tipo = "cpf";
        } elseif ($this->post["cnpj"]) {
            $documento = str_replace("/", "", str_replace("-", "", str_replace(".", "", $this->post["cnpj"])));
            $documento_tipo = "cnpj";
        } elseif ($this->post["documento"]) {
            $documento = str_replace("/", "", str_replace("-", "", str_replace(".", "", $this->post["documento"])));
            $documento_tipo = "cpf";
        } else {
            $erros[] = "campos_vazio";
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
            $this->retorno["sucesso"] = false;
            return $this->retorno;
        }

        $this->sql = "SELECT " . $this->campos . "
							FROM
							 pessoas p
							 LEFT OUTER JOIN paises pa ON (pa.idpais=p.idpais)
							 LEFT OUTER JOIN pessoas_associacoes pta ON (p.idpessoa = pta.idpessoa)
							 WHERE p.documento_tipo = '" . $documento_tipo . "' AND p.documento='" . $documento . "' AND p.ativo='S' AND (pta.ativo = 'S' OR pta.ativo IS NULL)";

        $this->retorno = $this->retornarLinha($this->sql);

        if (count($this->retorno) > 0) {
            $this->retorno["sucesso"] = true;

            if ($this->retorno["idpessoa"] == $idpessoa) {
                $this->retorno["sucesso"] = false;
                $this->retorno["negado"] = true;
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["negado"] = false;
        }

        if ($json) {
            $this->retorno["renda_familiar"] = number_format($this->retorno["renda_familiar"], 2, ",", ".");
            $this->retorno = json_encode($this->retorno);
        }

        return $this->retorno;
    }

    function listarTotalPessoas($idsindicato = false, $idcurso = false, $idregiao = false)
    {
        $this->sql = "select
						count(distinct p.idpessoa) as total
					  from
						pessoas p
						inner join pessoas_sindicatos pi on (p.idpessoa = pi.idpessoa and pi.ativo = 'S') ";
        if ($idregiao)
            $this->sql .= " inner join sindicatos i on pi.idsindicato = i.idsindicato
							left outer join estados e on i.idestado = e.idestado ";

        if ($idcurso)
            $this->sql .= " inner join matriculas m on (p.idpessoa = m.idpessoa and m.idcurso = " . $idcurso . " and m.ativo = 'S') ";

        $this->sql .= " where
					  	p.ativo = 'S' ";

        if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and pi.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";

        if ($idsindicato)
            $this->sql .= " and pi.idsindicato = " . $idsindicato;

        if ($idregiao)
            $this->sql .= " and e.idregiao = " . $idregiao;

        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }

    function verificarEmailCadastrado()
    {
        $this->sql = "SELECT count(idpessoa) as total FROM pessoas where ativo='S' and email = '" . $_POST['email'] . "'";
        if ($_POST['idpessoa'] != -1) {
            $this->sql .= " AND idpessoa <> '" . $_POST['idpessoa'] . "'";
        }
        $dados = $this->retornarLinha($this->sql);
        if ($dados['total'] > 0) {
            return false;
        } else
            return true;
    }

    function verificarEmailLojaVendedor()
    {
        $this->sql = "SELECT documento, email FROM pessoas where ativo='S' and email = '" . $_POST['email'] . "' LIMIT 1";

        $dados = $this->retornarLinha($this->sql);
        if ($dados['documento']) {
            return $dados;
        } else
            return false;
    }

    public function retornarNomePais($idpais)
    {
        $this->sql = "SELECT nome FROM paises WHERE idpais = '" . $idpais . "'";
        $dados = $this->retornarLinha($this->sql);
        return $dados['nome'];
    }

    public function retornarNomeCidade($idcidade)
    {
        $sql = "select nome from cidades where idcidade = '" . $idcidade . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarNomeEstado($idestado)
    {
        $sql = "select nome from estados where idestado = '" . $idestado . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarNomeLogradouro($idlogradouro)
    {
        $sql = "select nome from logradouros where idlogradouro = '" . $idlogradouro . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    function AssociarSindicato()
    {
        foreach ($this->post["sindicatos"] as $idsindicato) {
            $this->sql = "select count(idpessoa_sindicato) as total, idpessoa_sindicato from pessoas_sindicatos where idpessoa = '" . $this->id . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update pessoas_sindicatos set ativo = 'S' where idpessoa_sindicato = " . $totalAssociado["idpessoa_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idpessoa_sindicato"];
            } else {
                $this->sql = "insert into pessoas_sindicatos set ativo = 'S', data_cad = now(), idpessoa = '" . $this->id . "', idsindicato = '" . intval($idsindicato) . "'";
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
            $this->sql = "update pessoas_sindicatos set ativo = 'N' where idpessoa_sindicato = " . intval($this->post["remover"]);
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

    function ListarSindicatosAssociadas()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					pessoas_sindicatos pi
					inner join sindicatos i ON (pi.idsindicato = i.idsindicato)
				  where
					i.ativo = 'S' and
					pi.ativo= 'S' and
					pi.idpessoa = " . intval($this->id);

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
					inner join usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
				    left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
				  where
				    (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and
					i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' and
					i.ativo = 'S' and
					not exists (
					  select
						pi.idpessoa
					  from
						pessoas_sindicatos pi
					  where
						pi.idsindicato = i.idsindicato and
						pi.idpessoa = '" . intval($this->id) . "' and
						pi.ativo = 'S'
					)";

        $this->limite = -1;
        $this->ordem_campo = "i.nome_abreviado";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function verificaSindicatosUsuario($idpessoa)
    {
        $this->sql = '
		select idsindicato
			from pessoas_sindicatos
			where idpessoa = ' . $idpessoa . ' and ativo = "S"';
        $sindicatos_pessoas = $this->retornarLinhas();

        if (!count($sindicatos_pessoas))
            return true;
        else {
            foreach ($sindicatos_pessoas as $linha) {
                $sindicatos[] = $linha['idsindicato'];
            }
        }

        $sql = '
		select ua.idusuario
			from usuarios_adm ua
				left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = "S"
				left join cursos_sindicatos ci on ci.idsindicato = uai.idsindicato and ci.ativo = "S"
			where ua.idusuario = ' . $this->idusuario . '
				and (	ua.gestor_sindicato = "S"
						or
						(
							uai.idusuario is not null and
							ci.idsindicato in (' . implode(',', $sindicatos) . ')
						)
					) ';
        $resultado = $this->retornarLinha($sql);
        if ($resultado['idusuario'])
            return true;
        return false;
    }

    public function buscarPaises()
    {
        $this->sql = "SELECT idpais, nome FROM paises";
        $this->limite = -1;
        $this->ordem_campo = "nome";
        $this->ordem = "asc";
        $this->groupby = "nome";
        return $this->retornarLinhas();
    }

    public function retornarEstados()
    {
        $this->sql = "select * from estados";
        $this->ordem_campo = "nome";
        $this->ordem = "asc";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarEstado($idestado){
        $retorno = [];

        if (!empty($idestado)){

            $this->sql = 'SELECT * FROM estados WHERE idestado = ' . (int) $idestado;
            $retorno = $this->retornarLinha($this->sql);
        }

        return $retorno;
    }


    public function retornarCidade($idcidade){
        $retorno = [];

        if (!empty($idcidade)){
            $this->sql = 'SELECT * FROM cidades WHERE idcidade = ' . (int) $idcidade;
            $retorno = $this->retornarLinha($this->sql);
        }

        return $retorno;
    }

    public function retornarLogradouros()
    {
        $this->sql = "select * from logradouros";
        $this->ordem_campo = "nome";
        $this->ordem = "asc";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function ModificarAluno($idpessoa)
    {
        if (verificaPermissaoAcesso(true)) {
            $retorno["sucesso"] = true;

            $erros = array();

            if (!$this->post["email"]) {
                $erros[] = 'email_vazio';
            } else {
                $this->sql = "select count(email) as total from pessoas where idpessoa <> " . intval($idpessoa) . " and email = '" . $this->post["email"] . "'";
                $verifica = $this->retornarLinha($this->sql);
                if ($verifica['total'] > 0) {
                    $erros[] = 'email_duplicado';
                }
            }
            if (!$this->post["cep"]) {
                $erros[] = 'cep_vazio';
            } else {
                $this->post["cep"] = str_replace('-', '', $this->post["cep"]);
            }
            if (!$this->post["endereco"]) {
                $erros[] = 'endereco_vazio';
            }
            if (!$this->post["bairro"]) {
                $erros[] = 'bairro_vazio';
            }
            if (!$this->post["numero"]) {
                $erros[] = 'numero_vazio';
            }
            if (!$this->post["idestado"]) {
                $erros[] = 'estado_vazio';
            }
            if (!$this->post["idcidade"]) {
                $erros[] = 'cidade_vazio';
            }
            if ($this->post["disponivel_interacao"]) {
                $this->post['disponivel_interacao'] = 'S';
            } else {
                $this->post['disponivel_interacao'] = 'N';
            }

            if ($this->post["nova_senha"]) {
                if (senhaSegura($this->post["senha"], $GLOBALS['config']['chaveLogin']) != $_SESSION['cliente_senha']) {
                    $erros[] = 'senha_erro';
                }
                if ($this->post["nova_senha"] != $this->post['confirma_nova_senha']) {
                    $erros[] = 'nova_senha_confirmacao';
                }
            }

            if (!empty($erros)) {
                $retorno["sucesso"] = false;
                $retorno["erros"] = $erros;
            } else {

                $this->executaSql('begin');

                $this->sql = "select * from pessoas where idpessoa = " . intval($idpessoa);
                $linhaAntiga = $this->retornarLinha($this->sql);

                $this->sql = "update
    							pessoas
    						  set
    							email = '" . $this->post["email"] . "',
                                telefone = '" . $this->post["telefone"] . "',
                                celular = '" . $this->post["celular"] . "',
    							cep = '" . $this->post["cep"] . "',
    							idlogradouro = '" . $this->post["idlogradouro"] . "',
    							endereco = '" . $this->post["endereco"] . "',
    							bairro = '" . $this->post["bairro"] . "',
    							numero = '" . $this->post["numero"] . "',
    							complemento = '" . $this->post["complemento"] . "',
    							idestado = " . $this->post["idestado"] . ",
    							idcidade = " . $this->post["idcidade"] . ",
    							disponivel_interacao = '" . $this->post["disponivel_interacao"] . "',
    							facebook = '" . $this->post["facebook"] . "'";
                if ($this->post["nova_senha"]) {
                    $_SESSION['cliente_senha'] = senhaSegura($this->post["nova_senha"], $GLOBALS['config']['chaveLogin']);
                    $this->sql .= ", senha = '" . $_SESSION['cliente_senha'] . "'";
                }
                $this->sql .= " where idpessoa = " . intval($idpessoa);

                if ($this->executaSql($this->sql)) {
                    $this->sql = "select * from pessoas where idpessoa = " . intval($idpessoa);
                    $linhaNova = $this->retornarLinha($this->sql);

                    $sqlHistorico = "insert into matriculas_historicos (idmatricula, idpessoa, data_cad, tipo, acao, de, para) values ";
                    $values = array();
                    if ($linhaAntiga['email'] != $linhaNova['email']) {
                        $_SESSION['cliente_email'] = $linhaNova['email'];
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'email_aluno', 'modificou', '" . $linhaAntiga['email'] . "', '" . $linhaNova['email'] . "')";
                    }
                    if ($linhaAntiga['cep'] != $linhaNova['cep']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'cep_aluno', 'modificou', '" . $linhaAntiga['cep'] . "', '" . $linhaNova['cep'] . "')";
                    }
                    if ($linhaAntiga['idlogradouro'] != $linhaNova['idlogradouro']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'logradouro_aluno', 'modificou', '" . $linhaAntiga['idlogradouro'] . "', '" . $linhaNova['idlogradouro'] . "')";
                    }
                    if ($linhaAntiga['endereco'] != $linhaNova['endereco']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'endereco_aluno', 'modificou', '" . $linhaAntiga['endereco'] . "', '" . $linhaNova['endereco'] . "')";
                    }
                    if ($linhaAntiga['bairro'] != $linhaNova['bairro']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'bairro_aluno', 'modificou', '" . $linhaAntiga['bairro'] . "', '" . $linhaNova['bairro'] . "')";
                    }
                    if ($linhaAntiga['numero'] != $linhaNova['numero']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'numero_aluno', 'modificou', '" . $linhaAntiga['numero'] . "', '" . $linhaNova['numero'] . "')";
                    }
                    if ($linhaAntiga['complemento'] != $linhaNova['complemento']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'complemento_aluno', 'modificou', '" . $linhaAntiga['complemento'] . "', '" . $linhaNova['complemento'] . "')";
                    }
                    if ($linhaAntiga['idestado'] != $linhaNova['idestado']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'estado_aluno', 'modificou', '" . $linhaAntiga['idestado'] . "', '" . $linhaNova['idestado'] . "')";
                    }
                    if ($linhaAntiga['idcidade'] != $linhaNova['idcidade']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", now(), 'cidade_aluno', 'modificou', '" . $linhaAntiga['idcidade'] . "', '" . $linhaNova['idcidade'] . "')";
                    }
                    if (count($values) > 0) {
                        $sqlHistorico .= implode(', ', $values);

                        $this->sql = "select idmatricula from matriculas where idpessoa = " . intval($idpessoa);
                        $this->ordem_campo = "idmatricula";
                        $this->ordem = "asc";
                        $this->limite = -1;
                        $matriculas = $this->retornarLinhas();
                        foreach ($matriculas as $matricula) {
                            $this->sql = str_replace('[[idmatricula]]', $matricula['idmatricula'], $sqlHistorico);
                            if (!$this->executaSql($this->sql)) {
                                $this->executaSql('rollback');
                                $retorno["sucesso"] = false;
                                $retorno["erros"][] = $this->sql;
                                $retorno["erros"][] = mysql_error();
                            }
                        }
                    }

                    $this->monitora_oque = 2;
                    $this->monitora_qual = $idpessoa;
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->Monitora();

                } else {
                    $this->executaSql('rollback');
                    $retorno["sucesso"] = false;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }

                if ($retorno["sucesso"]) {
                    $this->executaSql('commit');
                }

            }
            return $retorno;
        }
    }

    public function validarCamposLoja()
    {
        $erros = array();

        include_once("../includes/validation.php");
        $regras = array();
        $regras[] = 'required,nome,nome_vazio';
        $regras[] = 'required,sobrenome,sobrenome_vazio';
        $regras[] = 'required,cnh,cnh_vazio';
        $regras[] = 'required,celular,celular_vazio';
        $regras[] = 'required,cep,cep_vazio';
        $regras[] = 'required,idestado,idestado_vazio';
        $regras[] = 'required,idcidade,idcidade_vazio';
        $regras[] = 'required,idlogradouro,idlogradouro_vazio';
        $regras[] = 'required,endereco,endereco_vazio';
        $regras[] = 'required,bairro,bairro_vazio';
        $regras[] = 'required,numero,numero_vazio';

        return validateFields($this->post, $regras);
    }

    function ModificarAlunoLoja($idpessoa)
    {
        if (verificaPermissaoAcesso(true)) {
            $retorno['sucesso'] = true;

            //VALIDANDO FORMULARIO
            $erros = $this->validarCamposLoja();

            if (!empty($erros)) {
                $retorno["sucesso"] = false;
                $retorno["erros"] = $erros;
            } else {
                $this->executaSql('BEGIN');

                $this->post = array_map('mysql_escape_string', $this->post);

                if ($this->post['sexo']) {
                    $this->post['sexo'] = "'".$this->post['sexo']."'";
                } else {
                    $this->post['sexo'] = 'NULL';
                }

                if ($this->post['telefone']) {
                    $this->post['telefone'] = "'".$this->post['telefone']."'";
                } else {
                    $this->post['telefone'] = 'NULL';
                }

                if ($this->post['celular']) {
                    $this->post['celular'] = "'".$this->post['celular']."'";
                } else {
                    $this->post['celular'] = 'NULL';
                }

                if ($this->post['idlogradouro']) {
                    $this->post['idlogradouro'] = "'".$this->post['idlogradouro']."'";
                } else {
                    $this->post['idlogradouro'] = 'NULL';
                }

                if ($this->post['endereco']) {
                    $this->post['endereco'] = "'".$this->post['endereco']."'";
                } else {
                    $this->post['endereco'] = 'NULL';
                }

                if ($this->post['bairro']) {
                    $this->post['bairro'] = "'".$this->post['bairro']."'";
                } else {
                    $this->post['bairro'] = 'NULL';
                }

                if ($this->post['numero']) {
                    $this->post['numero'] = "'".$this->post['numero']."'";
                } else {
                    $this->post['numero'] = 'NULL';
                }

                if ($this->post['complemento']) {
                    $this->post['complemento'] = "'".$this->post['complemento']."'";
                } else {
                    $this->post['complemento'] = 'NULL';
                }

                if ($this->post['idestado']) {
                    $this->post['idestado'] = "'".$this->post['idestado']."'";
                } else {
                    $this->post['idestado'] = 'NULL';
                }

                if ($this->post['idcidade']) {
                    $this->post['idcidade'] = "'".$this->post['idcidade']."'";
                } else {
                    $this->post['idcidade'] = 'NULL';
                }

                if ($this->post['cep']) {
                    $this->post['cep'] = "'".str_replace('-', '', $this->post['cep'])."'";
                } else {
                    $this->post['cep'] = 'NULL';
                }

                if ($this->post['estado_civil']) {
                    $this->post['estado_civil'] = "'".$this->post['estado_civil']."'";
                } else {
                    $this->post['estado_civil'] = 'NULL';
                }

                if ($this->post['data_nasc']) {
                    $this->post['data_nasc'] = "'".formataData($this->post['data_nasc'], "en", 0)."'";
                } else {
                    $this->post['data_nasc'] = 'NULL';
                }

                if ($this->post['nacionalidade']) {
                    $this->post['nacionalidade'] = "'".$this->post['nacionalidade']."'";
                } else {
                    $this->post['nacionalidade'] = 'NULL';
                }

                if ($this->post['naturalidade']) {
                    $this->post['naturalidade'] = "'".$this->post['naturalidade']."'";
                } else {
                    $this->post['naturalidade'] = 'NULL';
                }

                if ($this->post['rg']) {
                    $this->post['rg'] = "'".$this->post['rg']."'";
                } else {
                    $this->post['rg'] = 'NULL';
                }

                if ($this->post['rg_orgao_emissor']) {
                    $this->post['rg_orgao_emissor'] = "'".$this->post['rg_orgao_emissor']."'";
                } else {
                    $this->post['rg_orgao_emissor'] = 'NULL';
                }

                if ($this->post['rg_data_emissao']) {
                    $this->post['rg_data_emissao'] = "'".formataData($this->post['rg_data_emissao'], 'en', 0)."'";
                } else {
                    $this->post['rg_data_emissao'] = 'NULL';
                }

                if ($this->post['rne']) {
                    $this->post['rne'] = "'".$this->post['rne']."'";
                } else {
                    $this->post['rne'] = 'NULL';
                }

                if ($this->post['cnh']) {
                    $this->post['cnh'] = "'".$this->post['cnh']."'";
                } else {
                    $this->post['cnh'] = 'NULL';
                }

                if ($this->post['categoria']) {
                    $this->post['categoria'] = "'".$this->post['categoria']."'";
                } else {
                    $this->post['categoria'] = 'NULL';
                }

                if ($this->post['data_primeira_habilitacao']) {
                    $this->post['data_primeira_habilitacao'] = "'".formataData($this->post['data_primeira_habilitacao'], 'en', 0)."'";
                } else {
                    $this->post['data_primeira_habilitacao'] = 'NULL';
                }

                if ($this->post['cnh_data_emissao']) {
                    $this->post['cnh_data_emissao'] = "'".formataData($this->post['cnh_data_emissao'], 'en', 0)."'";
                } else {
                    $this->post['cnh_data_emissao'] = 'NULL';
                }

                if ($this->post['data_validade']) {
                    $this->post['data_validade'] = "'".formataData($this->post['data_validade'], 'en', 0)."'";
                } else {
                    $this->post['data_validade'] = 'NULL';
                }

                if ($this->post['profissao']) {
                    $this->post['profissao'] = "'".$this->post['profissao']."'";
                } else {
                    $this->post['profissao'] = 'NULL';
                }

                if ($this->post['filiacao_mae']) {
                    $this->post['filiacao_mae'] = "'".$this->post['filiacao_mae']."'";
                } else {
                    $this->post['filiacao_mae'] = 'NULL';
                }

                if ($this->post['filiacao_pai']) {
                    $this->post['filiacao_pai'] = "'".$this->post['filiacao_pai']."'";
                } else {
                    $this->post['filiacao_pai'] = 'NULL';
                }

                if ($this->post['ato_punitivo']) {
                    $this->post['ato_punitivo'] = "'".$this->post['ato_punitivo']."'";
                } else {
                    $this->post['ato_punitivo'] = 'NULL';
                }

                $this->sql = "SELECT * FROM pessoas WHERE idpessoa = '".intval($idpessoa)."'";
                $linhaAntiga = $this->retornarLinha($this->sql);

                $this->sql = 'UPDATE
                                    pessoas
                                SET
                                    nome = "' . $this->post["nome"] . '",
                                    sexo = ' . $this->post["sexo"] . ',
                                    telefone = ' . $this->post["telefone"] . ',
                                    celular = ' . $this->post["celular"] . ',
                                    cep = ' . $this->post["cep"] . ',
                                    idlogradouro = ' . $this->post["idlogradouro"] . ',
                                    endereco = ' . $this->post["endereco"] . ',
                                    bairro = ' . $this->post["bairro"] . ',
                                    numero = ' . $this->post["numero"] . ',
                                    complemento = ' . $this->post["complemento"] . ',
                                    profissao = ' . $this->post["profissao"] . ',
                                    idestado = ' . $this->post["idestado"] . ',
                                    idcidade = ' . $this->post["idcidade"] . ',
                                    estado_civil = ' . $this->post["estado_civil"] . ',
                                    data_nasc = ' . $this->post["data_nasc"] . ',
                                    nacionalidade = ' . $this->post["nacionalidade"] . ',
                                    ato_punitivo = ' . $this->post["ato_punitivo"] . ',
                                    naturalidade = ' . $this->post["naturalidade"] . ',
                                    rg = ' . $this->post["rg"] . ',
                                    rg_orgao_emissor = ' . $this->post["rg_orgao_emissor"] . ',
                                    rg_data_emissao = ' . $this->post["rg_data_emissao"] . ',
                                    rne = ' . $this->post["rne"] . ',
                                    cnh = ' . $this->post["cnh"] . ',
                                    categoria = ' . $this->post["categoria"] . ',
                                    data_primeira_habilitacao = ' . $this->post["data_primeira_habilitacao"] . ',
                                    cnh_data_emissao = ' . $this->post["cnh_data_emissao"] . ',
                                    data_validade = ' . $this->post["data_validade"] . ',
                                    filiacao_mae = ' . $this->post["filiacao_mae"] . ',
                                    filiacao_pai = ' . $this->post["filiacao_pai"] . '
                                WHERE
                                    idpessoa = ' . (int) $idpessoa;

                if ($this->executaSql($this->sql)) {
                    $this->sql = 'SELECT * FROM pessoas WHERE idpessoa = ' . (int) $idpessoa;
                    $linhaNova = $this->retornarLinha($this->sql);

                    $sqlHistorico = "INSERT INTO matriculas_historicos (idmatricula, idpessoa, data_cad, tipo, acao, de, para) VALUES ";
                    $values = array();

                    $linhaAntiga = array_map('mysql_escape_string', $linhaAntiga);
                    $linhaNova = array_map('mysql_escape_string', $linhaNova);

                    if ($linhaAntiga['nome'] != $linhaNova['nome']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'nome_aluno', 'modificou', '" . $linhaAntiga['nome'] . "', '" . $linhaNova['nome'] . "')";
                    }

                    if ($linhaAntiga['email'] != $linhaNova['email']) {
                        $_SESSION['cliente_email'] = $linhaNova['email'];
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'email_aluno', 'modificou', '" . $linhaAntiga['email'] . "', '" . $linhaNova['email'] . "')";
                    }

                    if ($linhaAntiga['cep'] != $linhaNova['cep']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'cep_aluno', 'modificou', '" . $linhaAntiga['cep'] . "', '" . $linhaNova['cep'] . "')";
                    }

                    if ($linhaAntiga['idlogradouro'] != $linhaNova['idlogradouro']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'logradouro_aluno', 'modificou', '" . $linhaAntiga['idlogradouro'] . "', '" . $linhaNova['idlogradouro'] . "')";
                    }

                    if ($linhaAntiga['endereco'] != $linhaNova['endereco']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'endereco_aluno', 'modificou', '" . $linhaAntiga['endereco'] . "', '" . $linhaNova['endereco'] . "')";
                    }

                    if ($linhaAntiga['bairro'] != $linhaNova['bairro']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'bairro_aluno', 'modificou', '" . $linhaAntiga['bairro'] . "', '" . $linhaNova['bairro'] . "')";
                    }

                    if ($linhaAntiga['numero'] != $linhaNova['numero']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'numero_aluno', 'modificou', '" . $linhaAntiga['numero'] . "', '" . $linhaNova['numero'] . "')";
                    }

                    if ($linhaAntiga['complemento'] != $linhaNova['complemento']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'complemento_aluno', 'modificou', '" . $linhaAntiga['complemento'] . "', '" . $linhaNova['complemento'] . "')";
                    }

                    if ($linhaAntiga['idestado'] != $linhaNova['idestado']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'estado_aluno', 'modificou', '" . $linhaAntiga['idestado'] . "', '" . $linhaNova['idestado'] . "')";
                    }

                    if ($linhaAntiga['idcidade'] != $linhaNova['idcidade']) {
                        $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'cidade_aluno', 'modificou', '" . $linhaAntiga['idcidade'] . "', '" . $linhaNova['idcidade'] . "')";
                    }

                    if ($linhaAntiga['sexo'] != $linhaNova['sexo']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'sexo_aluno', 'modificou', '" . $linhaAntiga['sexo'] . "', '" . $linhaNova['sexo'] . "')";
                    }

                    if ($linhaAntiga['telefone'] != $linhaNova['telefone']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'telefone_aluno', 'modificou', '" . $linhaAntiga['telefone'] . "', '" . $linhaNova['telefone'] . "')";
                    }

                    if ($linhaAntiga['celular'] != $linhaNova['celular']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'celular_aluno', 'modificou', '" . $linhaAntiga['celular'] . "', '" . $linhaNova['celular'] . "')";
                    }

                    if ($linhaAntiga['estado_civil'] != $linhaNova['estado_civil']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'estado_civil_aluno', 'modificou', '" . $linhaAntiga['estado_civil'] . "', '" . $linhaNova['estado_civil'] . "')";
                    }

                    if ($linhaAntiga['data_nasc'] != $linhaNova['data_nasc']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'data_nasc_aluno', 'modificou', '" . $linhaAntiga['data_nasc'] . "', '" . $linhaNova['data_nasc'] . "')";
                    }

                    if ($linhaAntiga['nacionalidade'] != $linhaNova['nacionalidade']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'nacionalidade_aluno', 'modificou', '" . $linhaAntiga['nacionalidade'] . "', '" . $linhaNova['nacionalidade'] . "')";
                    }

                    if ($linhaAntiga['naturalidade'] != $linhaNova['naturalidade']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'naturalidade_aluno', 'modificou', '" . $linhaAntiga['naturalidade'] . "', '" . $linhaNova['naturalidade'] . "')";
                    }

                    if ($linhaAntiga['rg'] != $linhaNova['rg']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'rg_aluno', 'modificou', '" . $linhaAntiga['rg'] . "', '" . $linhaNova['rg'] . "')";
                    }

                    if ($linhaAntiga['rg_orgao_emissor'] != $linhaNova['rg_orgao_emissor']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'rg_orgao_emissor_aluno', 'modificou', '" . $linhaAntiga['rg_orgao_emissor'] . "', '" . $linhaNova['rg_orgao_emissor'] . "')";
                    }

                    if ($linhaAntiga['rg_data_emissao'] != $linhaNova['rg_data_emissao']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'rg_data_emissao_aluno', 'modificou', '" . $linhaAntiga['rg_data_emissao'] . "', '" . $linhaNova['rg_data_emissao'] . "')";
                    }

                    if ($linhaAntiga['rne'] != $linhaNova['rne']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'rne_aluno', 'modificou', '" . $linhaAntiga['rne'] . "', '" . $linhaNova['rne'] . "')";
                    }

                    if ($linhaAntiga['filiacao_mae'] != $linhaNova['filiacao_mae']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'filiacao_mae_aluno', 'modificou', '" . $linhaAntiga['filiacao_mae'] . "', '" . $linhaNova['filiacao_mae'] . "')";
                    }

                    if ($linhaAntiga['filiacao_pai'] != $linhaNova['filiacao_pai']) {
                          $values[] = "([[idmatricula]], " . $idpessoa . ", NOW(), 'filiacao_pai_aluno', 'modificou', '" . $linhaAntiga['filiacao_pai'] . "', '" . $linhaNova['filiacao_pai'] . "')";
                    }

                    if (count($values) > 0) {
                        $sqlHistorico .= implode(', ', $values);

                        $this->sql = "SELECT idmatricula FROM matriculas WHERE idpessoa = '".intval($idpessoa)."'";
                        $this->ordem_campo = "idmatricula";
                        $this->ordem = "asc";
                        $this->limite = -1;
                        $matriculas = $this->retornarLinhas();
                        foreach ($matriculas as $matricula) {
                            $this->sql = str_replace('[[idmatricula]]', $matricula['idmatricula'], $sqlHistorico);

                            if (!$this->executaSql($this->sql)) {
                                $this->executaSql('ROLLBACK');
                                $retorno["sucesso"] = false;
                                $retorno["erros"][] = $this->sql;
                                $retorno["erros"][] = mysql_error();
                            }
                        }
                    }

                    //Seta as configurações da tabela de monitoramento
                    $this->config['tabela_monitoramento'] = 'monitora_pessoa';
                    $this->config["tabela_monitoramento_primaria"] = 'idpessoa';
                    $this->config['tabela_monitoramento_log'] = 'monitora_pessoa_log';

                    $this->idpessoa = $idpessoa;
                    $this->monitora_oque = 2;
                    $this->monitora_onde = 16;
                    $this->monitora_qual = $idpessoa;
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->Monitora();

                } else {
                    $this->executaSql('ROLLBACK');
                    $retorno["sucesso"] = false;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }

                if ($retorno["sucesso"]) {
                    $this->executaSql('COMMIT');
                }

            }
            $retorno["id"] = $idpessoa;
            return $retorno;
        }
    }

    //Lista todos os alunos do ava do professor
    function ListarTodasProfessor()
    {
        $this->campos = "DISTINCT(p.idpessoa), ".$this->campos;

        $this->sql = "SELECT
                    {$this->campos}
                FROM
                    pessoas p
                    INNER JOIN matriculas m ON (m.idpessoa = p.idpessoa AND m.ativo = 'S')
                    INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = m.idoferta AND ocp.idcurso = m.idcurso AND ocp.idescola = m.idescola AND ocp.ativo = 'S')
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = m.idoferta AND oca.idcurriculo = ocp.idcurriculo AND oca.ativo = 'S')
                    INNER JOIN professores_avas pav ON (pav.idava = oca.idava AND pav.ativo = 'S')
                  WHERE
                    pav.idprofessor = '".$this->idprofessor."' AND
                    p.ativo = 'S'";
        $this->aplicarFiltrosBasicos();

        $this->groupby = "DISTINCT(p.idpessoa)";
        return $this->retornarLinhas();
    }

    public function removerHash($hash)
    {
        $sql = 'DELETE FROM
                    solicitacoes_cadastros_portal
                WHERE
                    hash = "' . $hash . '" AND
                    tipo = "corretor"';
        return $this->executaSql($sql);
    }

    public function removerValidacaoFormulario(&$configForm, $arrayCampos = array())
    {
        foreach ($configForm as $ind_form => $parte_form) {
            foreach ($parte_form['campos'] as $ind_campo => $campo) {
                if (in_array($campo['nome'], $arrayCampos)) {
                    unset($configForm[$ind_form]['campos'][$ind_campo]['validacao']);
                }
            }
        }
    }
}
