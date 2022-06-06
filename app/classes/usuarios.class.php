<?php
class Usuarios extends Core
{
    function ListarTodas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    usuarios_adm u
                    left outer join usuarios_adm_perfis p on (u.idperfil = p.idperfil)
                  where 
                    u.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "u.idusuario";
        return $this->retornarLinhas();
    }


    function Retornar()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    usuarios_adm u
                    left outer join usuarios_adm_perfis p ON (u.idperfil = p.idperfil) 
                    left outer join cidades c ON (c.idcidade = u.idcidade)
                    left outer join estados e ON (e.idestado = c.idestado)
                  where 
                    u.ativo = 'S' and 
                    u.idusuario = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        return $this->SalvarDados();
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }

    function Remover()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    function AtivarLogin($situacao)
    {
        if ($situacao <> "S" && $situacao <> "N") {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update usuarios_adm set ativo_login = '" . mysql_real_escape_string($situacao) . "' where idusuario='" . intval($this->id) . "'";
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($this->id);
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

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update usuarios_adm set senha = '" . $senha . "' where idusuario = " . intval($this->id);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($this->id);
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if ($modificou) {

            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $info["sucesso"] = true;
            $info["confirmacao"] = $confirmacao;
            $info["enviar_email"] = $enviarEmail;
            $info["exibir_nova_senha"] = $exibirNovaSenha;
            $info["nova_senha"] = $novaSenha;

            if ($enviarEmail) {
                $message = 'Algu&eacute;m, possivelmente voc&ecirc;, solicitou uma nova senha de acesso ao sistema.
                    <br />
                    <br />
                    <strong>Acesse:</strong> <a href="' . $this->config["urlSistema"] . '/gestor">' . $this->config["urlSistema"] . '/gestor</a>
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

    function listarTotalUsuarios()
    {
        $this->sql = "select
                    count(idusuario) as total
                  from
                    usuarios_adm
                  where 
                    ativo = 'S' ";
        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }

    function AlterarMeusdados()
    {
        //print_r2($this->post['avatar']);exit();
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
                $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/usuariosadm_avatar";
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

        $this->sql = "SELECT * FROM
                    usuarios_adm 
                WHERE idusuario = '" . $this->id . "'";
        $linhaAntiga = $this->retornarLinha($this->sql);

        if (senhaSegura($this->post["senha_antiga"], $this->config["chaveLogin"]) == $linhaAntiga["senha"]) {
            $this->sql = "UPDATE usuarios_adm
                        SET nome = '" . $this->post["nome"] . "',
                        email = '" . $this->post["email"] . "',
                        senha = '" . senhaSegura($this->post["senha"], $this->config["chaveLogin"]) . "',
                        ultima_senha = now()";
            if ($avatar) {
                $this->sql .= ', avatar_nome = "' . $avatar_nome . '",
                        avatar_servidor = "' . $nomeServidor . '",
                        avatar_tipo = "' . $avatar_tipo . '",
                        avatar_tamanho = ' . $avatar_tamanho;
            }
            $this->sql .= " WHERE idusuario = '" . $this->id . "'";
            $query = $this->executaSql($this->sql);

            $this->sql = "SELECT * FROM usuarios_adm
                    WHERE idusuario = '" . $this->id . "'";
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

    function AssociarSindicato()
    {
        foreach ($this->post["sindicatos"] as $idsindicato) {
            $this->sql = "select count(idusuario_sindicato) as total, idusuario_sindicato from usuarios_adm_sindicatos where idusuario = '" . $this->id . "' and idsindicato = '" . intval($idsindicato) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update usuarios_adm set relogin = 'S' where idusuario = " . $this->id;
                $relogin = $this->executaSql($this->sql);

                $this->sql = "update usuarios_adm_sindicatos set ativo = 'S' where idusuario_sindicato = " . $totalAssociado["idusuario_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idusuario_sindicato"];
            } else {
                $this->sql = "update usuarios_adm set relogin = 'S' where idusuario = " . $this->id;
                $relogin = $this->executaSql($this->sql);

                $this->sql = "insert into usuarios_adm_sindicatos set ativo = 'S', data_cad = now(), idusuario = '" . $this->id . "', idsindicato = '" . intval($idsindicato) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 69;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function AssociarCfc()
    {
        foreach ($this->post["cfcs"] as $idcfc) {
            $this->sql = "select count(idusuario_cfc) as total, idusuario_cfc from usuarios_adm_cfcs where idusuario = '" . $this->id . "' and idcfc = '" . intval($idcfc) . "'";
            $totalAssociado = $this->retornarLinha($this->sql);
            if ($totalAssociado["total"] > 0) {
                $this->sql = "update usuarios_adm set relogin = 'S' where idusuario = " . $this->id;
                $relogin = $this->executaSql($this->sql);

                $this->sql = "update usuarios_adm_cfcs set ativo = 'S' where idusuario_sindicato = " . $totalAssociado["idusuario_sindicato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAssociado["idusuario_cfc"];
            } else {
                $this->sql = "update usuarios_adm set relogin = 'S' where idusuario = " . $this->id;
                $relogin = $this->executaSql($this->sql);

                $this->sql = "insert into usuarios_adm_cfcs set ativo = 'S', data_cad = now(), idusuario = '" . $this->id . "', idcfc = '" . intval($idcfc) . "'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 69;
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
            $this->sql = "update usuarios_adm set relogin = 'S' where idusuario = " . $this->id;
            $relogin = $this->executaSql($this->sql);

            $this->sql = "update usuarios_adm_sindicatos set ativo = 'N' where idusuario_sindicato = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 69;
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

    function DesassociarCfc()
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
            $this->sql = "update usuarios_adm_cfcs set ativo = 'N' where idusuario_cfc = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 69;
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
                    usuarios_adm_sindicatos ui
                    inner join sindicatos i ON (ui.idsindicato = i.idsindicato)
                  where 
                    i.ativo = 'S' and 
                    ui.ativo= 'S' and 
                    ui.idusuario = " . intval($this->id);

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
                    i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' and
                    i.ativo = 'S' and
                    not exists (
                      select 
                        ui.idusuario 
                      from 
                        usuarios_adm_sindicatos ui 
                      where 
                        ui.idsindicato = i.idsindicato and 
                        ui.idusuario = '" . intval($this->id) . "' and
                        ui.ativo = 'S'
                    )";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function GestorSindicato($idusuario)
    {
        if (!$this->post["gestor_sindicato"])
            $this->post["gestor_sindicato"] = "N";

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($idusuario);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update usuarios_adm set gestor_sindicato = '" . $this->post["gestor_sindicato"] . "', relogin = 'S' where idusuario = " . intval($idusuario);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($idusuario);
        $linhaNova = $this->retornarLinha($this->sql);

        if ($modificou) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idusuario;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function GestorCfc($idusuario)
    {
        if (!$this->post["gestor_cfc"])
            $this->post["gestor_cfc"] = "N";

        $this->sql = "select * from usuarios_adm where idusuario = " . intval($idusuario);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update usuarios_adm set gestor_cfc = '" . $this->post["gestor_cfc"] . "', relogin = 'S' where idusuario = " . intval($idusuario);
        $modificou = $this->executaSql($this->sql);

        $this->sql = "select * from usuarios_adm where idusuario = " .intval($idusuario);
        $linhaNova = $this->retornarLinha($this->sql);

        if ($modificou) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_qual = $idusuario;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function retornarGruposAss($idusuario)
    {
        $this->sql = "select guau.idgrupo, gua.nome from grupos_usuarios_adm_usuarios guau
                    inner join grupos_usuarios_adm gua ON guau.idgrupo = gua.idgrupo
                where guau.ativo = 'S' and guau.idusuario = '" . $idusuario . "' ";
        return $this->retornarLinhas();
    }

    function AtivarEmails($situacao, $tipo)
    {
        if ($situacao <> "S" && $situacao <> "N") {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->sql = "SELECT * FROM usuarios_adm WHERE idusuario = " . (int)$this->id;
        $linhaAntiga = $this->retornarLinha($this->sql);

        if ($tipo == 'qtd_dias_sit') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_matricula_situacao = '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_matricula_situacao';
        }

        if ($tipo == 'recebe_email_homologacao') {
            $this->sql = "UPDATE usuarios_adm SET recebe_email_homologacao = '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'recebe_email_homologacao';
        }

        if ($tipo == 'relatorio_gerencial_segunda') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_segunda= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_segunda';
        }

        if ($tipo == 'relatorio_gerencial_terca') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_terca= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_terca';
        }

        if ($tipo == 'relatorio_gerencial_quarta') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_quarta= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_quarta';
        }

        if ($tipo == 'relatorio_gerencial_quinta') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_quinta= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_quinta';
        }

        if ($tipo == 'relatorio_gerencial_sexta') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_sexta= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_sexta';
        }

        if ($tipo == 'relatorio_gerencial_sabado') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_sabado= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_sabado';
        }

        if ($tipo == 'relatorio_gerencial_domingo') {
            $this->sql = "UPDATE usuarios_adm SET receber_email_relatorio_gerencial_domingo= '" . mysql_real_escape_string($situacao) . "' WHERE idusuario='" . (int)$this->id . "'";
            $opcaoEmail = 'receber_email_relatorio_gerencial_domingo';
        }

        $executa = $this->executaSql($this->sql);

        $this->sql = "SELECT * FROM usuarios_adm WHERE idusuario = " . (int)$this->id;
        $linhaNova = $this->retornarLinha($this->sql);

        $info = array();

        if (!$executa) {
            $info['sucesso'] = false;
            $info['situacao'] = $situacao;
            return json_encode($info);
        }

        $this->monitora_oque = 2;
        $this->monitora_qual = $this->id;
        $this->monitora_dadosantigos = $linhaAntiga;
        $this->monitora_dadosnovos = $linhaNova;
        $this->Monitora();

        $info['sucesso'] = true;
        $info['situacao'] = $linhaNova[$opcaoEmail];
        
        return json_encode($info);
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

    function ListarEscolasAssociadas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    usuarios_adm_cfcs c
                    inner join escolas e ON (c.idcfc = e.idescola)
                  where 
                    e.ativo = 'S' and 
                    c.ativo= 'S' and 
                    c.idusuario = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "c.idcfc";
        return $this->retornarLinhas();
    }

}
