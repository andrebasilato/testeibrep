<?php
class Workflow extends Core
{

    public $tabela = null;
    public $tipos = array();
    public $flags = array();

    function retonarDados()
    {
        $dados = array();
        $dados['edicao'] = $GLOBALS['config']['workflow'];
        $dados['tipos'] = $this->tipos;
        $dados['flags'] = $this->flags;
        $this->limite = -1;

        $dados['opcoes'] = $GLOBALS['workflow_parametros_' . $this->tabela];

        $this->campos = ' * ';
        $this->ordem_campo = ' nome, sigla';
        $this->sql = 'SELECT ' . $this->campos . ' FROM ' . $this->tabela . '_workflow WHERE ativo="S"';
        $dados['blocos'] = $this->retornarLinhas();

        $this->campos = ' r.idrelacionamento, r.idsituacao_de, a1.idapp as idsituacao_de_app, r.idsituacao_para, a2.idapp as idsituacao_para_app ';
        $this->ordem_campo = ' r.idrelacionamento ';
        $this->sql = "SELECT
                        " . $this->campos . "
                      FROM
                        " . $this->tabela . "_workflow_relacionamentos r
                        inner join " . $this->tabela . "_workflow a1 on (a1.idsituacao = r.idsituacao_de)
                        inner join " . $this->tabela . "_workflow a2 on (a2.idsituacao = r.idsituacao_para)
                      WHERE
                        r.ativo = 'S' and
                        a1.ativo = 'S' and
                        a2.ativo = 'S' ";
        $dados["relacionamentos"] = $this->retornarLinhas();
        foreach ($dados["blocos"] as $ind => $bloco) {
            $this->campos = " idopcao ";
            $this->ordem_campo = " idacao";
            $this->sql = "SELECT " . $this->campos . " FROM " . $this->tabela . "_workflow_acoes WHERE idsituacao=" . $bloco["idsituacao"] . " and ativo='S'";
            $dados["blocos"][$ind]["acoes"] = $this->retornarLinhas();
        }

        foreach ($dados["relacionamentos"] as $ind => $relacionamento) {
            $this->campos = " awa.idacao, awa.idopcao ";
            $this->ordem_campo = " idopcao ";
            $this->sql = "SELECT " . $this->campos . " FROM " . $this->tabela . "_workflow_acoes awa WHERE awa.idrelacionamento=" . $relacionamento["idrelacionamento"] . " and awa.ativo='S'";
            $array_acoes = $this->retornarLinhas();
            if ($array_acoes) {
                foreach ($array_acoes as $acao) {
                    foreach ($GLOBALS['workflow_parametros_' . $this->tabela] as $op) {
                        if ($op['idopcao'] == $acao['idopcao']) {
                            $acao['tipo'] = $op['tipo'];
                            $acao['parametros'] = array();
                            $dados["relacionamentos"][$ind]["acoes"][] = $acao;
                        }
                    }
                }
            } else {
                $dados["relacionamentos"][$ind]["acoes"] = array();
            }
            foreach ($dados["relacionamentos"][$ind]["acoes"] as $indacao => $acao) {
                $this->campos = " awap.idparametro, awap.valor, rwa.idopcao, awap.idacao";
                $this->ordem_campo = " awap.idacao ";
                $this->sql = "SELECT " . $this->campos . " FROM " . $this->tabela . "_workflow_acoes_parametros awap INNER JOIN " . $this->tabela . "_workflow_acoes rwa ON (awap.idacao = rwa.idacao) WHERE awap.idacao=" . $acao["idacao"] . " and awap.ativo='S'";
                $array_acoes = $this->retornarLinhas();
                foreach ($array_acoes as $acao) {
                    foreach ($GLOBALS['workflow_parametros_' . $this->tabela] as $op) {
                        if ($op['idopcao'] == $acao['idopcao']) {
                            $dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"] = $op['parametros'];
                            if($acao["valor"]){
                                $dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"][0]["valor"] = $acao["valor"];
                            }
                        }
                    }
                }
                if (count($dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"])) {
                    foreach ($dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"] as $i => $v) {
                        if ($dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"][$i]["tipo"] == "checkbox") {
                            $dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"][$i]["valor"] = json_decode($dados["relacionamentos"][$ind]["acoes"][$indacao]["parametros"][$i]["valor"]);
                        }
                    }
                }
            }
        }
        return $dados;
    }

    function salvarDados()
    {

        if (get_magic_quotes_gpc()) {
            $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            while (list($key, $val) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process[$key][$k]);
                    if (is_array($v)) {
                        $process[$key][stripslashes($k)] = $v;
                        $process[] = & $process[$key][stripslashes($k)];
                    } else {
                        $process[$key][stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }

        //LOCAL
        $dados = json_decode($_POST["parametros"], TRUE);
        $dados = stripslashes_deep($dados);

        salvarLog('workflow_parametros', $_POST["parametros"]);
        $dados["tipos"] = $this->tipos;
        $dados["flags"] = $this->flags;
        salvarLog('workflow_dados', $dados);

        $log = array();
        mysql_query("begin");

        $sql = "update " . $this->tabela . "_workflow set ativo = 'N'";
        $log[] = $sql;
        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));

        $retorno = [];

        foreach ($dados["blocos"] as $ind => $valor) {
            if ($valor["idsituacao"]) {
                $sql = "update
                  " . $this->tabela . "_workflow
                set
                  ativo = 'S',
                  nome = '" . mysql_real_escape_string($valor["nome"]) . "',
                  sigla='" . mysql_real_escape_string($valor["sigla"]) . "',";
                foreach ($dados["flags"] as $flag => $nome) {
                    if(!$valor[$flag]) $valor[$flag] = 'N';
                    $sql .= " $flag = '" . mysql_real_escape_string($valor[$flag]) . "', ";
                }
                $sql .= " posicao_x = '" . mysql_real_escape_string($valor["posicao_x"]) . "',
                  posicao_y = '" . mysql_real_escape_string($valor["posicao_y"]) . "',
                  cor_bg = '" . mysql_real_escape_string(substr($valor["cor_bg"], -6)) . "',
                  cor_nome = '" . mysql_real_escape_string(substr($valor["cor_nome"], -6)) . "',
                  idapp = '" . mysql_real_escape_string($valor["idapp"]) . "',
                  ordem = '" . mysql_real_escape_string($valor["ordem"]) . "'
                WHERE
                  idsituacao='" . mysql_real_escape_string($valor["idsituacao"]) . "'";
                $log[] = $sql;
                $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                $idsituacao = $valor["idsituacao"];
            } else {
                $sql = "insert into
                  " . $this->tabela . "_workflow
                set
                  ativo = 'S',
                  data_cad = now(),
                  nome = '" . mysql_real_escape_string($valor["nome"]) . "',
                  sigla = '" . mysql_real_escape_string($valor["sigla"]) . "', ";
                foreach ($dados["flags"] as $flag => $nome) {
                    if(!$valor[$flag]) $valor[$flag] = 'N';
                    $sql .= " $flag='" . mysql_real_escape_string($valor[$flag]) . "', ";
                }
                $sql .= " posicao_x = '" . mysql_real_escape_string($valor["posicao_x"]) . "',
                  posicao_y = '" . mysql_real_escape_string($valor["posicao_y"]) . "',
                  cor_bg = '" . mysql_real_escape_string(substr($valor["cor_bg"], -6)) . "',
                  cor_nome = '" . mysql_real_escape_string(substr($valor["cor_nome"], -6)) . "',
                  idapp = '" . mysql_real_escape_string($valor["idapp"]) . "',
                  ordem = '" . mysql_real_escape_string($valor["ordem"]) . "'";
                $log[] = $sql;
                $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                $idsituacao = mysql_insert_id();
            }

            $retorno[$valor["idapp"]] = $idsituacao;

            $sql = "update " . $this->tabela . "_workflow_acoes set ativo = 'N' where idsituacao = '$idsituacao'";
            $log[] = $sql;
            $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));

            if ($valor["acoes"] && count($valor["acoes"]) > 0) {
                foreach ($valor["acoes"] as $indacao => $valoracao) {
                    // Verificar se existe
                    $sql = "select * from " . $this->tabela . "_workflow_acoes where idsituacao = '$idsituacao' and idopcao = '" . $valoracao["idopcao"] . "'";
                    $log[] = $sql;
                    $verifica = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    $opcao = mysql_fetch_array($verifica);

                    if ($opcao["idacao"]) {
                        $sql = "update " . $this->tabela . "_workflow_acoes set ativo = 'S' where idacao = '" . $opcao["idacao"] . "'";
                        $log[] = $sql;
                        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    } else {
                        $sql = "insert into
                      " . $this->tabela . "_workflow_acoes
                    set
                      ativo = 'S',
                      data_cad = now(),
                      idsituacao = $idsituacao,
                      idrelacionamento = NULL,
                      idopcao = " . $valoracao["idopcao"] . "";
                        $log[] = $sql;
                        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    }
                }
            }
        }

        $sql = "update " . $this->tabela . "_workflow_relacionamentos set ativo = 'N'";
        $log[] = $sql;
        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
        foreach ($dados["relacionamentos"] as $ind => $valor) {
            if ($valor["idsituacao_de"] && $valor["idsituacao_para"]) {
                $sql = "update
                  " . $this->tabela . "_workflow_relacionamentos
                set
                  ativo = 'S',
                  idsituacao_de = '" . mysql_real_escape_string($valor["idsituacao_de"]) . "',
                  idsituacao_para = '" . mysql_real_escape_string($valor["idsituacao_para"]) . "'
                where
                  idrelacionamento = '" . mysql_real_escape_string($valor["idrelacionamento"]) . "'";
                $log[] = $sql;
                $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                $idrelacionamento = $valor["idrelacionamento"];
            } else {
                //idsituacao_de_app
                $sql = "select * from " . $this->tabela . "_workflow where idapp = '" . mysql_real_escape_string($valor["idsituacao_de_app"]) . "'";
                $log[] = $sql;
                $verifica = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                $situacao_de = mysql_fetch_array($verifica);

                //idsituacao_para_app
                $sql = "select * from " . $this->tabela . "_workflow where idapp = '" . mysql_real_escape_string($valor["idsituacao_para_app"]) . "'";
                $log[] = $sql;
                $verifica = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                $situacao_para = mysql_fetch_array($verifica);

                if ($situacao_de["idsituacao"] && $situacao_para["idsituacao"]) {
                    $sql = "insert into
                    " . $this->tabela . "_workflow_relacionamentos
                  set
                    ativo = 'S',
                    data_cad = now(),
                    idsituacao_de = '" . $situacao_de["idsituacao"] . "',
                    idsituacao_para = '" . $situacao_para["idsituacao"] . "'";

                    $log[] = $sql;
                    $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    $idrelacionamento = mysql_insert_id();
                }
            } // fim do else

            // Parametros dos relacionamentos
            $sql = "update " . $this->tabela . "_workflow_acoes set ativo = 'N' where idrelacionamento = '$idrelacionamento'";
            $log[] = $sql;
            $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
            if ($valor["acoes"] && count($valor["acoes"]) > 0) {
                foreach ($valor["acoes"] as $indacao => $valoracao) {
                    // Verificar se existe
                    $sql = "select * from " . $this->tabela . "_workflow_acoes where idrelacionamento = '$idrelacionamento' and idopcao = '" . $valoracao["idopcao"] . "'";
                    $log[] = $sql;
                    $verifica = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    $opcao = mysql_fetch_array($verifica);

                    if ($opcao["idacao"]) {
                        $sql = "update " . $this->tabela . "_workflow_acoes set ativo = 'S' where idacao = '" . $opcao["idacao"] . "'";
                        $log[] = $sql;
                        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                        $idacao = $opcao["idacao"];
                    } else {
                        $sql = "insert into
                      " . $this->tabela . "_workflow_acoes
                    set
                      ativo = 'S',
                      data_cad = now(),
                      idsituacao = NULL,
                      idrelacionamento = $idrelacionamento,
                      idopcao = " . $valoracao["idopcao"] . "";
                        $log[] = $sql;
                        $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                        $idacao = mysql_insert_id();
                    }

                    // Parametros dos relacionamentos
                    $sql = "update " . $this->tabela . "_workflow_acoes_parametros set ativo = 'N' where idacao = '" . $idacao . "'";
                    $log[] = $sql;
                    $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                    foreach ($valoracao["parametros"] as $indaparametro => $valorparametro) {
                        // Verificar se existe
                        $sql = "select * from " . $this->tabela . "_workflow_acoes_parametros where idparametro = '" . $valorparametro["idparametro"] . "' and idacao = '" . $idacao . "'";
                        $log[] = $sql;
                        $verifica = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                        $opcao = mysql_fetch_array($verifica);

                        if ($opcao["idacaoparametro"]) {
                            $sql = "update " . $this->tabela . "_workflow_acoes_parametros set ativo = 'S', valor = '" . mysql_real_escape_string($valorparametro["valor"]) . "' where idacaoparametro = {$opcao['idacaoparametro']}";
                            $log[] = $sql;
                            $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                        } else {
                            $sql = "insert into " . $this->tabela . "_workflow_acoes_parametros set ativo = 'S', data_cad = now(), valor = '" . mysql_real_escape_string($valorparametro["valor"]) . "', idacao = {$idacao}, idparametro = {$valorparametro["idparametro"]}";
                            $log[] = $sql;
                            $executa = mysql_query($sql) or die($log[] = $sql . salvarLog('workflow_salvar', $log) . mysql_query("rollback"));
                        }
                    }
                }
            }
        }

        mysql_query("commit");
        salvarLog('workflow_LOG', $log);

       return $retorno;

    }

    function retornarSituacoes()
    {
        $this->retorno = array();
        $this->sql = "select * from " . $this->tabela . " where ativo = 'S' order by nome asc";
        $seleciona = mysql_query($this->sql);
        while ($situacao = mysql_fetch_assoc($seleciona)) {
            $this->retorno[] = $situacao;
        }
        return $this->retorno;
    }

    function salvarSituacoes()
    {
        foreach ($_POST["situacao"] as $idsituacao => $horas) {
            if (!$horas) {
                $horas = "null";
            }
            $this->sql = "update " . $this->tabela . " set sla = " . $horas . " where idsituacao = '" . $idsituacao . "'";
            $seleciona = mysql_query($this->sql);
        }
        return true;
    }
}
