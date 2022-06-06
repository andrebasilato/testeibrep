<?php
class Documentos_Matriculas extends Core
{
    var $condicoes_pagamento = array();
    var $idgestor = NULL;
    var $situacoesFiltro = array();

    function ListarTodas() {

        $idSituacaoCancelada = $this->retornarIdSituacaoCancelada();
        $idSituacaoFim = $this->retornarIdSituacaoFim();
        $idSituacaoInativa = $this->retornarIdSituacaoInativa();

        $this->sql = "SELECT
                        ".$this->campos."
                      FROM
                        matriculas ma
                        INNER JOIN matriculas_workflow mw ON (mw.idsituacao = ma.idsituacao)
                        INNER JOIN ofertas of ON (of.idoferta = ma.idoferta)
                        INNER JOIN cursos cu ON (cu.idcurso = ma.idcurso)
                        INNER JOIN escolas po ON (po.idescola = ma.idescola)
                        INNER JOIN pessoas pe ON (pe.idpessoa = ma.idpessoa)
                        INNER JOIN matriculas_documentos md ON (md.idmatricula = ma.idmatricula and md.situacao = 1 and md.ativo = 'S')
                        INNER JOIN tipos_documentos td ON (md.idtipo = td.idtipo)";

        $this->sql .= " WHERE ma.ativo = 'S'"; // and md.arquivo_servidor is not null

        if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and ma.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";

        if($idSituacaoCancelada["idsituacao"])
          $this->sql .= " AND ma.idsituacao <> ".$idSituacaoCancelada["idsituacao"];

        if($idSituacaoFim["idsituacao"])
          $this->sql .= " AND ma.idsituacao <> ".$idSituacaoFim["idsituacao"];

        if($idSituacaoInativa["idsituacao"])
          $this->sql .= " AND ma.idsituacao <> ".$idSituacaoInativa["idsituacao"];

        if($this->situacoesFiltro)
          $this->sql .= " AND ma.idsituacao NOT IN(".implode(',',$this->situacoesFiltro).")";

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif($campo[0] == 2)  {
                        $busca = str_replace("\\'","",$valor);
                        $busca = str_replace("\\","",$busca);
                        $busca = explode(" ",$busca);
                        foreach($busca as $ind => $buscar){
                            $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
                        }
                    } elseif($campo[0] == 3)  {
                        $this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
                    } elseif($campo[0] == 5)  {
                        $this->sql .= " and ".$campo[1]." = ".base64_decode($valor)." ";
                    }
                }
            }
        }
        //echo $this->sql;exit;

        $this->groupby = "ma.idmatricula";
        $matriculas = array();
        $matriculas = $this->retornarLinhas();
        //print_r2($matriculas,true);
        $retorno = array();
        $this->retorno = array();
        $limite = $this->limite;
        $total = $this->total;
        $ordem = $this->ordem;
        $ordem_campo = $this->ordem_campo;
        foreach($matriculas as $ind => $matricula) {
            $this->sql = "SELECT
                            mwa.idacao, mwa.idopcao
                        FROM
                            matriculas_workflow_acoes mwa
                        WHERE
                            mwa.idsituacao = '{$matricula["idsituacao"]}'
                        AND
                            mwa.ativo = 'S'";

            $this->ordem = "asc";
            $this->ordem_campo = "mwa.idacao";
            $this->limite = -1;
            $acoes = $this->retornarLinhas();
            foreach($acoes as $acao) {
              foreach($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
                if($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "visualizacao") {
                  $matricula["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;
                }
              }
            }

          $retorno[] = $matricula;
        }
        $this->limite = $limite;
        $this->total = $total;
        $this->ordem = $ordem;
        $this->ordem_campo = $ordem_campo;
        //print_r2($retorno,true);
        return $retorno;
    }

    function retornarIdSituacaoFim() {
      $sql = "SELECT * FROM matriculas_workflow WHERE fim = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
      $linha = $this->retornarLinha($sql);
      return $linha;
    }

    function retornarIdSituacaoCancelada() {
      $sql = "SELECT * FROM matriculas_workflow WHERE cancelada = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
      $linha = $this->retornarLinha($sql);
      return $linha;
    }

    function retornarIdSituacaoInativa() {
      $sql = "SELECT * FROM matriculas_workflow WHERE inativa = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
      $linha = $this->retornarLinha($sql);
      return $linha;
    }

    function AdicionarHistorico($idmatricula, $tipo, $acao, $de, $para, $iddocumento) {
      $this->sql = "INSERT
                      matriculas_historicos
                    SET
                      idmatricula = '".$idmatricula."',
                      data_cad = now(),
                      idusuario = '".$this->idusuario."',
                      tipo = '".$tipo."',
                      acao = '".$acao."'";

      if($de) {
        $this->sql .= ", de = '".$de."'";
      }

      if($para) {
        $this->sql .= ", para = '".$para."'";
      }

      if($iddocumento) {
        $this->sql .= ", id = '".$iddocumento."'";
      }

        if(($de || $para) && ($de == $para)) {
            return true;
        } else {
            return $this->executaSql($this->sql);
        }
    }

    public function retornarDocumento()
    {
        $this->sql = "SELECT
                            md.*,
                            m.data_cad as data_matricula
                        FROM
                            matriculas_documentos md
                            INNER JOIN matriculas m ON (md.idmatricula = m.idmatricula)
                        WHERE
                            md.iddocumento = '".$this->iddocumento."' AND
                            md.idmatricula = '".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    /**
     * Método para retornar o documento que está com a situação aguardando da biometria.
     * @param $idMatricula
     * @param $idDocumento
     * @return mixed
     */
    public function retornarDocumentoBiometria($idMatricula, $idDocumento)
    {
        try {
            if (is_numeric($idMatricula) && is_numeric($idDocumento)) {

                $sql = "SELECT
                        md.*
                    FROM
                        matriculas_documentos md
                    INNER JOIN tipos_documentos td ON (md.idtipo = td.idtipo)
                    WHERE md.iddocumento = $idDocumento
                    AND md.idmatricula = $idMatricula
                    AND td.documento_foto_oficial = 'S'
                    AND md.situacao = 'aguardando'
                    AND md.ativo = 'S'
                    AND td.ativo = 'S'";

                return $this->retornarLinha($sql);
            } else {
                throw new InvalidArgumentException('Parâmetro(s) tem que ser do tipo númerico.');
            }
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Método para atualizar porcentagem da biometria do datavalid.
     * @param $idMatricula
     * @param $porcentagem
     * @return void
     */
    public function atualizarPorcentagemBiometriaDataValid($idMatricula, $porcentagem)
    {
        try {
        if(is_numeric($idMatricula) && is_numeric($porcentagem))
        {
            $sql = "UPDATE matriculas_reconhecimentos
                    SET probabilidade_datavalid = $porcentagem
                    WHERE idmatricula = $idMatricula AND ativo = 'S' and ativo_painel = 'S'";

            $this->executaSql($sql);

        } else {
            throw new Exception('Parâmetros tem que ser do tipo númerico');
        }
        } catch (InvalidArgumentException $e)
        {
            echo $e->getMessage();
        }

    }

   public function aprovarDocumento()
    {
        $this->retorno = array();

        $this->sql = "SELECT
                            count(*) as total
                        FROM
                            matriculas m
                            INNER JOIN matriculas_workflow_acoes mwa ON (m.idsituacao = mwa.idsituacao AND mwa.idopcao = 8 AND mwa.ativo = 'S')
                        WHERE
                            m.idmatricula = '".$this->id."'";
        $visualizacao = $this->retornarLinha($this->sql);

        $documentoBiometria = $this->retornarDocumentoBiometria($this->id, $this->iddocumento);

        if($visualizacao["total"] > 0) {
            $this->sql = "UPDATE
                                matriculas_documentos
                            SET
                                situacao = '".$this->post["situacao"]."'";

            if ($this->post["situacao"] == "reprovado") {
                if (!$this->post["descricao_motivo_reprovacao"]) {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "documentos_matricula_reprovacao_descricao_vazio";
                    return $this->retorno;
                }

                if ($this->post["descricao_motivo_reprovacao"]) {
                    $this->sql .= ", descricao_motivo_reprovacao = '".$this->post["descricao_motivo_reprovacao"]."'";
                }
            }

            $this->sql .= " WHERE
                                iddocumento = '".$this->iddocumento."' AND
                                idmatricula = '" . $this->id."'";
            $salvar = $this->executaSql($this->sql);

            if ($salvar) {
                if ($this->post["situacao"] == "aprovado") {
                    $acao = "aprovou";
                    if($documentoBiometria)
                    {
                        $this->executaSql("
                        UPDATE matriculas
                        SET liberacao_temporaria_datavalid = 'N'                    
                        WHERE idmatricula = $this->id
                        ");
                        $this->atualizarPorcentagemBiometriaDataValid($this->id, 0.85);
                    }
                } else {
                    $acao = "reprovou";
                    if(count($documentoBiometria) > 0)
                    {
                        $this->executaSql("
                        UPDATE matriculas
                        SET envio_foto_documento_oficial = 'N', email_documento_biometria = 'N'
                        WHERE idmatricula = $this->id
                        ");
                    }
                }

                $this->AdicionarHistorico($this->id, "documento", $acao, NULL, NULL, $this->iddocumento);
                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "documentos_matricula_situacao_sucesso";
            } else {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "documentos_matricula_situacao_erro";
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_permissao_workflow";
        }

        return $this->retorno;
    }
}