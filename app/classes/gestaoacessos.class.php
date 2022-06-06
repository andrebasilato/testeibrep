<?php

class GestaoAcessos extends Core
{
    public function retornarAcessosDiarioMatricula($idMatricula)
    {
        $this->sql = "SELECT * 
            FROM pessoas_acessos_matriculas 
            WHERE 
                idmatricula = '".$idMatricula."' 
                AND data_competencia = '".date('Y-m-d')."'";
        $this->ordem_campo = 'idacesso DESC, fim';
        $this->ordem = 'DESC';
        $this->limite = -1;
        $acessos = array();
        $linhas = $this->retornarLinhas();
        foreach ($linhas as $acesso) 
            $acessos[$acesso['idacesso']][] = $acesso;

        return $acessos;
    }
    
    public function contabilizarAcessoMatricula($idPessoa, $idMatricula, $idAva)
    {

        $acessos = $this->retornarAcessosDiarioMatricula($idMatricula);
        if ($acessos){
            $acessosDiarios = array();
            foreach($acessos as $acessoAva)
                $acessosDiarios = array_merge($acessosDiarios, array_column($acessoAva, 'duracao'));

            $acessoDiarioTotal = array_reduce($acessosDiarios, 'somaDuasHoras');
            if($acessoDiarioTotal <= '08:00:00'){
                $ultimosAcessosAvas = array_shift($acessos);
                $ultimoAcesso = $ultimosAcessosAvas[0];
                $fimUltimoAcesso = new DateTime($ultimoAcesso['fim']);
                $horaServidor = new DateTime();
                $pausaIntervalo = $horaServidor->diff($fimUltimoAcesso)->h;
                $horasPausaObrigatoria = 1;

                $duracaoUltimosAcessosAvas = array_column($ultimosAcessosAvas, 'duracao');
                $ultimoAcessoTotal = array_reduce($duracaoUltimosAcessosAvas, 'somaDuasHoras');

                if ($ultimoAcessoTotal > '04:00:00' && $pausaIntervalo < $horasPausaObrigatoria){
                    $retorno['erro'] = array("acesso_bloqueado_4h.png", "Você já atingiu a carga-máxima de 4h/a de estudo ininterruptas, e precisa aguardar o intervalo mínimo de uma hora para retomar o estudo on-line, conforme determina a Resolução 802/2020 e Portaria Nº 4934/2019.
                        <br><br>Aguarda só um pouquinho que, a partir das <b>".$fimUltimoAcesso->modify("+ $horasPausaObrigatoria hour")->format('H:i')."</b>, você pode voltar a estudar!");
                } else {
                    if($idAcesso = $_SESSION['idacesso']){
                        foreach($ultimosAcessosAvas as $acesso){
                            if ($acesso['idava'] == $idAva) {
                                $ultimoAcessoAvaAtual = $acesso;
                                break;
                            }
                        }
    
                        if (!empty($ultimoAcessoAvaAtual)){
                            if ($ultimoAcessoAvaAtual['idacesso'] == $idAcesso){
                                $retorno['sucesso'] = $this->alterarAcessoMatricula($ultimoAcessoAvaAtual['idacessomatricula']);
                            } else $retorno['sucesso'] = $this->cadastrarAcessoMatricula(time(), $idPessoa, $idMatricula, $idAva);
                        } else $retorno['sucesso'] = $this->cadastrarAcessoMatricula($idAcesso, $idPessoa, $idMatricula, $idAva);
                    } else $retorno['sucesso'] = $this->cadastrarAcessoMatricula(time(), $idPessoa, $idMatricula, $idAva);
                }

            } else $retorno['erro'] = array("acesso_bloqueado_8h.png", "Você já atingiu a carga-máxima de 8h/a diárias, conforme determina a Resolução 802/2020 e Portaria Nº 4934/2019.<br><br>
            Amanhã tá liberado novamente o seu acesso para estudo, ok?");
        } else $retorno['sucesso'] = $this->cadastrarAcessoMatricula(time(), $idPessoa, $idMatricula, $idAva);

        return $retorno;
    }
    
    public function cadastrarAcessoMatricula($idAcesso, $idPessoa, $idMatricula, $idAva)
    {
        $_SESSION['idacesso'] = $idAcesso;
        $dadosdousuario = retornaSOBrowser();
        $this->sql = "INSERT INTO
                                pessoas_acessos_matriculas
                              SET
                                data_cad = now(),
                                data_competencia = '" . date('Y-m-d') . "',
                                idpessoa = " . $idPessoa . ",
                                idacesso = " . $idAcesso . ",
                                idmatricula = " . $idMatricula . ",
                                idava = " . $idAva . ",
                                inicio = now(),
                                fim = DATE_ADD(now(), INTERVAL 1 MINUTE),
                                duracao = TIMEDIFF(fim, inicio),
                                ip = '" . $dadosdousuario['ip'] . "',
                                navegador = '" . mysql_escape_string($dadosdousuario['navegador']) . "',
                                sistema_operacional = '" . mysql_escape_string($dadosdousuario['so']) . "',
                                navegador_versao = '" . mysql_escape_string($dadosdousuario['navegador_versao']) . "',
                                user_agent = '" . mysql_escape_string($dadosdousuario['user_agent']) . "'
            ";

        $salvar = $this->executaSql($this->sql);
        return $idAcesso;
    }
    
    public function alterarAcessoMatricula($idAcessoMatricula)
    {
        $this->sql = "UPDATE
                                pessoas_acessos_matriculas
                              SET
                                fim = now(),
                                duracao = TIMEDIFF(fim, inicio)
                                WHERE idacessomatricula = $idAcessoMatricula
            ";

        $salvar = $this->executaSql($this->sql);
        return $salvar;
    }

    public function logoutInatividade($idAcesso, $idpessoa)
    {
        $this->sql = "UPDATE
                        pessoas_acessos_matriculas
                      SET
                        fim = now(),
                        duracao = TIMEDIFF(fim, inicio),
                        inatividade = 'S'
                      WHERE 
                        idpessoa = '". $idpessoa ."'
                      AND idacesso = " . $idAcesso ;

        $salvar = $this->executaSql($this->sql);
    }

    /** Validação de múltiplas Sessões/Logins  */
    public function verificaSessao($idPessoa, $idSessao)
    {
        if ($_SESSION["outra_sessao"] == true) {

            /*
            * Primeira Validação
            * Se usuário estava logado recentemente, pergunta se deseja permancer logado na sessão anterior ou na atual.
            */
            echo "<script type='text/javascript'>
        if (window.confirm('Existe uma sessão ativa em outro dispositivo. Você será deslogado do outro dispositivo para que possa continuar por este. Deseja continuar?')) {
            window.location.href = '?opLogin=atualiza_sessao';
        }else {
            window.location.href = '?opLogin=sair';
        } </script>";

        } else {
            /*
            * Segunda Validação
            * Verifica se a sessão atual é a última salva no banco
            */

            $this->sql = "SELECT idsessao
                        FROM pessoas
                        WHERE
                        idpessoa = '$idPessoa'
                        AND idsessao = '$idSessao'";
            $sessao = $this->retornarLinha($this->sql);

            if (!$sessao['idsessao']) {
                //Desloga caso o idsessao atual seja diferente
                header("location: ?opLogin=sair");
            }
        }
    }
}
