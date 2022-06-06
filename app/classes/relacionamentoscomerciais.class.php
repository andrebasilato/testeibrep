<?php
class RelacionamentosComerciais extends Core {

    var $idvendedor      = NULL;

  function ListarTodas() {
    $this->sql = "SELECT ".$this->campos.",
                     (
                        select rcm.proxima_acao
                        from relacionamentos_comerciais_mensagens rcm
                        where rcm.idrelacionamento = rc.idrelacionamento
                        order by rcm.idmensagem desc limit 1
                     ) as proxima_acao
                  FROM
                        relacionamentos_comerciais rc
                  WHERE rc.ativo = 'S'";

    $this->aplicarFiltrosBasicos();

    if (is_array($_GET["q"])) {
        foreach ($_GET["q"] as $campo => $valor) {

            $campo = explode("|", $campo);
            $valor = str_replace("'", "", $valor);

            if (($valor || $valor === "0") and $valor <> "todos") {

                if ($campo[0] == 7) {
                    $this->sql .= " and date_format((
                        select max(rcm.proxima_acao)
                        from relacionamentos_comerciais_mensagens rcm
                        where rcm.idrelacionamento = rc.idrelacionamento
                     ),'%d/%m/%Y') = '" . $valor . "' ";
                }

            }
        }
    }

    //$this->ordem = "desc";
    //$this->ordem_campo = "rc.idrelacionamento";
    $this->groupby = "rc.idrelacionamento";
    return $this->retornarLinhas();
  }

  function Retornar() {
    $this->sql = "SELECT ".$this->campos."
                FROM relacionamentos_comerciais
                WHERE ativo = 'S' AND idrelacionamento = '".$this->id."'
                ";
    return $this->retornarLinha($this->sql);
  }

    public function verificaExisteRelacionamento($email) {
        $this->sql = "SELECT idrelacionamento
                FROM relacionamentos_comerciais
                WHERE
                    ativo = 'S' AND
                    email_pessoa = '{$email}'
                LIMIT 1";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar() {
        $this->sql = "SELECT idrelacionamento FROM
                            relacionamentos_comerciais
                        WHERE
                            email_pessoa = '{$this->post['email_pessoa']}' AND
                            ativo ='S'";
        $verifica_email = $this->retornarLinha($this->sql);
        if ($verifica_email['idrelacionamento']) {
            $this->retorno['sucesso'] = false;
            $this->retorno['erro'] = true;
            $this->retorno['erros'][] = 'erro_email_utilizado';
            $this->retorno['idrelacionamento'] = $verifica_email['idrelacionamento'];
            return $this->retorno;
        }
        $salvar = $this->SalvarDados();
        if($salvar['sucesso']) {
            $this->id = $salvar['id'];
            if($this->modulo == 'atendente') {
                $idusuario = $this->vendedor;
            } else {
                $idusuario = $this->idusuario;
            }
            $this->AdicionarHistorico($idusuario, "relacionamento", "cadastrou", NULL, $this->id, NULL);
            if ($this->post['mensagem']) {
                $this->id = $salvar['id'];
                $this->adicionarMensagem();
            }
        }
        return $salvar;
    }

  function Modificar() {
    return $this->SalvarDados();
  }

  function Remover() {
    return $this->RemoverDados();
  }

    public function ListarProximas()
    {
        $dataAtual = date('Y-m-d');
        $this->sql = "SELECT ".$this->campos."
                    FROM
                        relacionamentos_comerciais_mensagens rcm
                    INNER JOIN
                        relacionamentos_comerciais rc
                        ON (rcm.idrelacionamento = rc.idrelacionamento)
                    WHERE
                        rc.ativo = 'S' AND
                        rcm.ativo = 'S' AND
                        rcm.proxima_acao >= '" . $dataAtual."' ";

        if ($this->idvendedor) {
            $this->sql .= " AND (rcm.idvendedor = " . $this->idvendedor." OR rcm.idusuario IS NOT NULL)";
        }
        $this->ordem = "ASC";
        $this->ordem_campo = "rcm.proxima_acao";
        $this->groupby = "rc.idrelacionamento";
        return $this->retornarLinhas();
    }

  //Para pesquisa de matriculas
  function BuscarMatricula() {
    $this->sql = "SELECT
                    p.idpessoa AS 'key', CONCAT(p.nome,' - ', p.idpessoa,' - ', p.documento) AS value
                  FROM
                    pessoas p
                  WHERE
                     (p.nome LIKE '%".$_GET["tag"]."%' OR p.idpessoa LIKE '%".$_GET["tag"]."%' OR p.documento LIKE '%".$_GET["tag"]."%') AND
                     p.ativo = 'S'";
    $this->limite = -1;
    $this->ordem_campo = "value";
    $this->groupby = "value";

    $dados = $this->retornarLinhas();
    return json_encode($dados);
  }

    /**
    * Get Messages from 'relacionamentos_comerciais_mensagens' by 'idrelacionamento'
    *
    * @param  integer      $idRelationship
    * @return array
    */
    public function retornarMensagensRelacionamento(){
        $this->sql = "SELECT {$this->campos}
                FROM
                    relacionamentos_comerciais_mensagens rcm
                LEFT OUTER JOIN
                    usuarios_adm ua ON (ua.idusuario = rcm.idusuario)
                LEFT OUTER JOIN
                    vendedores v ON (v.idvendedor = rcm.idvendedor)
                WHERE
                    rcm.ativo = 'S' AND
                    rcm.idrelacionamento = '{$this->id}'";

        return $this->retornarLinhas();
    }

    public function editarDadosRelacionamento() {
        $this->retorno = array();
        $erro = array();


        if (!$this->post['email_pessoa']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "email_pessoa_vazio";
            return $this->retorno;
        }
        if (!$this->post['nome_pessoa']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "email_pessoa_vazio";
            return $this->retorno;
        }
        if (!$this->post['ativo_painel']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "ativo_painel_vazio";
            return $this->retorno;
        }

        $this->sql = "SELECT idrelacionamento FROM
                        relacionamentos_comerciais
                    WHERE
                        email_pessoa = '{$this->post['email_pessoa']}' AND
                        idrelacionamento <> '{$this->id}' AND
                        ativo = 'S'";
        $verifica_email = $this->retornarLinha($this->sql);

        if ($verifica_email['idrelacionamento']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "email_pessoa_existente";
            return $this->retorno;
        }


        $sql = "SELECT * FROM
                    relacionamentos_comerciais
                WHERE
                    idrelacionamento = {$this->id}";
        $linhaAntiga = $this->retornarLinha($sql);

        $this->sql = "UPDATE
                          relacionamentos_comerciais
                        SET
                            email_pessoa = '{$this->post["email_pessoa"]}',
                            nome_pessoa = '{$this->post["nome_pessoa"]}',
                            ativo_painel = '{$this->post["ativo_painel"]}'
                        WHERE
                          idrelacionamento = '{$this->id}'";
        $salvar = $this->executaSql($this->sql);
        if (!$salvar) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "editar_dados_matricula_erro";
            return $this->retorno;
        }

        $this->sql = "SELECT * FROM
                        relacionamentos_comerciais
                    WHERE idrelacionamento = '{$this->id}'";
        $linhaNova = $this->retornarLinha($this->sql);

        if ($this->modulo == 'atendente') {
            $this->idusuario = $this->idvendedor;
        }

        $this->AdicionarHistorico($this->idusuario, "email_pessoa", "modificou", $linhaAntiga["email_pessoa"], $linhaNova["email_pessoa"], NULL);
        $this->AdicionarHistorico($this->idusuario, "nome_pessoa", "modificou", $linhaAntiga["nome_pessoa"], $linhaNova["nome_pessoa"], NULL);
        $this->AdicionarHistorico($this->idusuario, "ativo_painel", "modificou", $linhaAntiga["ativo_painel"], $linhaNova["ativo_painel"], NULL);

        $this->monitora_onde = 156;
        $this->monitora_oque = 2;
        $this->monitora_qual = $this->id;
        $this->monitora_dadosantigos = $linhaAntiga;
        $this->monitora_dadosnovos = $linhaNova;
        $this->Monitora();

        $this->retorno["sucesso"] = true;
        $this->retorno["mensagem"] = "editar_dados_matricula_sucesso";
        return $this->retorno;
    }

  function adicionarMensagem(){
        if (!$this->post["mensagem"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_vazio";
            return $this->retorno;
        }
        if (!$this->post["proxima_acao"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "proxima_acao_vazio";
            return $this->retorno;
        }
        $this->sql = "INSERT INTO
                        relacionamentos_comerciais_mensagens
                    SET
                        data_cad = NOW(),
                        idrelacionamento = {$this->id},
                        ativo = 'S',
                        mensagem = '".$this->post["mensagem"]."',
                        proxima_acao = '".formataData($this->post["proxima_acao"], "en", 0)."', ";
        if ($this->idvendedor) {
            $this->sql.="idvendedor = '".$this->idvendedor."'";
        } else {
            $this->sql.="idusuario = '".$this->idusuario."'";
        }
    $salvar = $this->executaSql($this->sql);

    if($salvar) {
        $idmensagem = mysql_insert_id();
        if ($this->post['enviar_email']) {

            $sql_pessoa = 'select nome_pessoa, email_pessoa from relacionamentos_comerciais where idrelacionamento = ' . $this->id;
            $pessoa = $this->retornarLinha($sql_pessoa);

            $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa']);
            $emailDe = $GLOBALS['config']['emailSistema'];

            $nomePara = utf8_decode($pessoa['nome_pessoa']);
            $emailPara = $pessoa['email_pessoa'];
            $assunto = utf8_decode('Relacionamento Comercial');

            $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                            <br /><br />
                            Foi enviada no relacionamento comercial a seguinte mensagem:
                            <br /><br />";

            $message .= utf8_decode(nl2br($this->post['mensagem']));
            $message = html_entity_decode($message);

            $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
        }

       $this->retorno["sucesso"] = true;
       $this->retorno["mensagem"] = "mensagem_adicionada_sucesso";
        if ($this->modulo == 'atendente') {
            $this->idusuario = $this->idvendedor;
        }

        $this->monitora_onde = 193;
        $this->monitora_oque = 1;
        $this->monitora_qual = $idmensagem;
        $this->Monitora();
        $this->AdicionarHistorico($this->idusuario, "mensagem", "cadastrou", NULL, NULL, $idmensagem);
    } else {
      $this->retorno["sucesso"] = false;
      $this->retorno["mensagem"] = "mensagem_adicionada_erro";
    }
    return $this->retorno;
  }

  function removerMensagem($idmensagem) {
    $this->sql = "UPDATE
                    relacionamentos_comerciais_mensagens
                  SET
                    ativo = 'N'
                  WHERE
                    idmensagem = '".$idmensagem."'";
    $remover = $this->executaSql($this->sql);

    if($remover) {
       $this->retorno["sucesso"] = true;
       $this->retorno["mensagem"] = "mensagem_removida_sucesso";

        if ($this->modulo == 'atendente') {
            $this->idusuario = $this->idvendedor;
        }
        $this->monitora_onde = 193;
        $this->monitora_oque = 3;
        $this->monitora_qual = $this->id;
        $this->Monitora();
        $this->AdicionarHistorico($this->idusuario, "mensagem", "removeu", NULL, NULL, $idmensagem);

    } else {
      $this->retorno["sucesso"] = false;
      $this->retorno["mensagem"] = "mensagem_removida_erro";
    }
    return $this->retorno;
  }
    public function AdicionarHistorico($idusuario, $tipo, $acao, $de, $para, $id) {

        $this->sql = "INSERT
                        relacionamentos_comerciais_historicos
                      SET
                        idrelacionamento = '{$this->id}',
                        data_cad = now(),
                        tipo = '{$tipo}',
                        acao = '{$acao}'";
        if($this->modulo == "gestor" && $idusuario) {
            $this->sql .= ", idusuario = '".$idusuario."'";
        } elseif($this->modulo == "atendente" && $idusuario) {
            $this->sql .= ", idvendedor = '".$idusuario."'";
        }
        if($de)
            $this->sql .= ", de = '".$de."'"; else  $de = uniqid();
        if($para)
            $this->sql .= ", para = '".$para."'"; else  $para = uniqid();
        if($id)
            $this->sql .= ", id = '".$id."'";

        if($de != $para)
          return $this->executaSql($this->sql);
        else
          return true;
    }
    public function RetornarHistoricos() {
    $retorno = array();

    $this->sql = "SELECT * FROM
                    relacionamentos_comerciais_historicos
                WHERE idrelacionamento = ".$this->id;
    $this->limite = -1;
    $this->ordem = "desc";
    $this->ordem_campo = "data_cad";
    $historicos = $this->retornarLinhas();
    foreach($historicos as $historico) {
      $historico["modulo"] = "Sistema";

        if ($historico["idusuario"]) {
            $this->sql = "SELECT * FROM usuarios_adm WHERE idusuario = ".$historico["idusuario"];
            $historico["usuario"] = $this->retornarLinha($this->sql);

            $historico["modulo"] = "Gestor";
        } elseif ($historico["idvendedor"]) {
            $this->sql = "SELECT * FROM vendedores WHERE idvendedor = ".$historico["idvendedor"];
            $historico["usuario"] = $this->retornarLinha($this->sql);

            $historico["modulo"] = "Atendente";
        } else {
            $historico["usuario"]["nome"] = "--";
        }
        switch ($historico["tipo"]) {
            case "relacionamento":
              switch ($historico["acao"]) {
                case "cadastrou":
                  $historico["descricao"] = "Cadastrou o relacionamento comercial.<br>";
                break;
              }
            case "email_pessoa":
              switch ($historico["acao"]) {
                case "modificou":
                  $historico["descricao"] = "Modificou o e-mail da pessoa.<br><span style=\"color:#666666\">De ".$historico["de"]." para ".$historico["para"]."</span>";
                break;
              }
            break;
            case "nome_pessoa":
              switch ($historico["acao"]) {
                case "modificou":
                  $historico["descricao"] = "Modificou o nome da pessoa.<br><span style=\"color:#666666\">De ".$historico["de"]." para ".$historico["para"]."</span>";
                break;
              }
            break;
            case "mensagem":
              $this->sql = "SELECT * FROM
                                relacionamentos_comerciais_mensagens
                            WHERE idmensagem = ".$historico["id"];
              $historico["mensagem"] = $this->retornarLinha($this->sql);
              switch ($historico["acao"]) {
                case "cadastrou":
                   $historico["descricao"] = "Cadastrou a mensagem:<br><span style=\"color:#666666\">\"".strip_tags($historico["mensagem"]["mensagem"])."\"</span>";
                break;
                case "removeu":
                   $historico["descricao"] = "Removeu a mensagem:<br><span style=\"color:#666666\">\"".strip_tags($historico["mensagem"]["mensagem"])."\"</span>";
                break;
              }
            break;
        }
      $retorno[] = $historico;
    }

    return $retorno;
  }

    public function retornarHistoricoTabela($historicos, $idioma) {
        $retorno = '
        <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover" width="900" style="width:900px;">
         <tr>
          <td width="100" bgcolor="#F4F4F4"><strong>'.$idioma["historico_modulo"].'</strong></td>
          <td width="200" bgcolor="#F4F4F4"><strong>'.$idioma["historico_usuario"].'</strong></td>
          <td width="140" bgcolor="#F4F4F4"><strong>'.$idioma["historico_data"].'</strong></td>
          <td bgcolor="#F4F4F4"><strong>'.$idioma["historico_descricao"].'</strong></td>
      </tr>
      <tbody>
          <tr>
            <td colspan="4" style="padding:0px;">
             <div style="height:400px; overflow:auto;">
               <table border="0" cellspacing="0" width="100%">';
                foreach($historicos as $historico) {
                  $retorno .= '
                  <tr>
                   <td width="100">'. $historico["modulo"].'</td>
                   <td width="200">'. $historico["usuario"]["nome"].'</span></td>
                   <td width="140">'. formataData($historico["data_cad"],'br',1).'<br /><span style="color:#999;">'.$idioma["historico_matricula_id"].' '. $historico["idhistorico"] .'</span></td>
                   <td width=""> '. $historico["descricao"] .'</td>
               </tr>';
           }
           $retorno .= '
       </table>
    </div>
    </td>
    </tr>
    </tbody>
    </table>';

    return $retorno;
    }

}

?>