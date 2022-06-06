<?php
class Extrato_Caixa extends Core
{

function ListarTodas() {
        $this->sql = "SELECT
                        ".$this->campos."
                    FROM
                        fechamentos_caixa fc
                        inner join usuarios_adm ua on fc.idusuario = ua.idusuario
                        INNER JOIN contas c ON fc.idfechamento = c.idfechamento
                        INNER JOIN contas_correntes cc ON c.idconta_corrente = cc.idconta_corrente
                        INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
                        LEFT JOIN mantenedoras m ON c.idmantenedora = m.idmantenedora
                        LEFT JOIN sindicatos i ON c.idsindicato = i.idsindicato
                        LEFT JOIN produtos p ON c.idproduto = p.idproduto
                        LEFT JOIN categorias cat ON c.idcategoria = cat.idcategoria
                        LEFT JOIN matriculas mat ON c.idmatricula = mat.idmatricula
                        LEFT JOIN pessoas pes_mat ON mat.idpessoa = pes_mat.idpessoa
                        LEFT JOIN fornecedores forn ON c.idfornecedor = forn.idfornecedor
                        LEFT JOIN pessoas pes ON c.idpessoa = pes.idpessoa
                    where
                        fc.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            if (!$_SESSION['adm_sindicatos'])
                $_SESSION['adm_sindicatos'] = 0;
            $this->sql .= ' and ( select count(1) from fechamentos_caixa_sindicatos fci where fci.idfechamento = fc.idfechamento and fci.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ) > 0';
        }

        $this->sql .= " GROUP BY i.idsindicato";

        //echo $this->sql;exit;
        $this->groupby = "i.idsindicato";
        return $this->retornarLinhas();
    }

    function retornarContas() {
        $this->sql = "select idsituacao from contas_workflow where pago = 'S' and ativo = 'S' ";
        $situacao_pago = $this->retornarLinha($this->sql);
        if(!$situacao_pago['idsituacao']) {
            $erros['erro'] = true;
            $erros['erros'][] = 'sem_workflow_vendido';
            return $erros;
        }

        $this->sql = "select idsituacao from contas_workflow where renegociada = 'S' and ativo = 'S' ";
        $situacao_renegociado = $this->retornarLinha($this->sql);

        $this->sql = "select idsituacao from contas_workflow where cancelada = 'S' and ativo = 'S' ";
        $situacao_cancelado = $this->retornarLinha($this->sql);

        $this->sql = "select idsituacao from contas_workflow where transferida = 'S' and ativo = 'S' ";
        $situacao_transferido = $this->retornarLinha($this->sql);


        //Trazer as receitas
        $retorno['receita'] = array();
        if(($_POST['tipo_data_receber'] == 'PER' && $_POST['periodo_inicio_receber'] && $_POST['periodo_final_receber']) || $_POST['tipo_data_receber'] != 'PER') {
            $this->sql = "select
                                        c.*,
                                        p.nome as pessoa,
                                        p.idpessoa,
                                        cc.nome as conta_corrente,
                                        cw.nome as situacao
                                    from
                                        contas c
                                        inner join contas_workflow cw on c.idsituacao = cw.idsituacao
                                        left join matriculas m on c.idmatricula = m.idmatricula
                                        left join pessoas p on m.idpessoa = p.idpessoa
                                        left join contas_correntes cc on c.idconta_corrente = c.idconta_corrente
                                    where
                                        c.tipo = 'receita' and
                                        c.ativo = 'S' and
                                        c.idsituacao <> '".$situacao_pago['idsituacao']."' and
                                        c.ativo_painel = 'S' ";

            if ($situacao_renegociado) {
                $this->sql .= " and c.idsituacao <> '".$situacao_renegociado['idsituacao']."'  ";
            }

            if ($situacao_cancelado) {
                $this->sql .= " and c.idsituacao <> '".$situacao_cancelado['idsituacao']."'  ";
            }

            if ($situacao_transferido) {
                $this->sql .= " and c.idsituacao <> '".$situacao_transferido['idsituacao']."'  ";
            }

            if($_POST['tipo_data_receber']) {
              if($_POST['tipo_data_receber'] == 'HOJ') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') = '".date("Y-m-d")."'";
              } else if($_POST['tipo_data_receber'] == 'SET') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') <= '".date("Y-m-d")."'
                                  and date_format(c.data_vencimento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
              } else if($_POST['tipo_data_receber'] == 'MAT') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m")."'";
              } else if($_POST['tipo_data_receber'] == 'MPR') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
              } else if($_POST['tipo_data_receber'] == 'MAN') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
              } else if($_POST['tipo_data_receber'] == 'PER') {
                if($_POST['periodo_inicio_receber'])
                  $this->sql .= " and DATE_FORMAT(c.data_vencimento,'%Y-%m-%d') >= '".formataData($_POST['periodo_inicio_receber'],'en',0)."' ";
                if($_POST['periodo_final_receber'])
                  $this->sql .= " and DATE_FORMAT(c.data_vencimento,'%Y-%m-%d') <= '".formataData($_POST['periodo_final_receber'],'en',0)."'    ";
              }
            }

            $idsindicato = implode(', ', $_POST['idsindicato']);
            if($idsindicato) {
                $this->sql .= " and c.idsindicato in (".$idsindicato.") ";
            } else {
                if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                    if (!$_SESSION['adm_sindicatos'])
                        $_SESSION['adm_sindicatos'] = 0;
                    $this->sql .= ' and c.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ';
                }
            }

            if ($_POST['forma_pagamento_receber'])
                $this->sql .= ' and c.forma_pagamento = ' . $_POST['forma_pagamento_receber'] . ' ';

            $this->sql .= " group by c.idconta ";

            if ($_POST['ordenacao_data_receber']) {
                $this->ordem_campo = $_POST['ordenacao_data_receber'];
            } else {
                $this->ordem_campo = 'c.idconta';
            }

            $this->limite = -1;
            $retorno['receita'] = $this->retornarLinhas();
        }

        //Trazer as despesas
        $retorno['despesa'] = array();
        if(($_POST['tipo_data_pagar'] == 'PER' && $_POST['periodo_inicio_pagar'] && $_POST['periodo_final_pagar']) || $_POST['tipo_data_pagar'] != 'PER') {
            $this->sql = "select c.*, f.nome as fornecedor, p.nome as produto, cc.nome as conta_corrente from contas c
                              left join fornecedores f on c.idfornecedor = f.idfornecedor
                              left join produtos p on p.idproduto = c.idproduto
                              left join contas_correntes cc on c.idconta_corrente = c.idconta_corrente
                          where c.tipo = 'despesa' and c.ativo = 'S' and c.idsituacao <> '".$situacao_pago['idsituacao']."' and c.ativo_painel = 'S' ";

            if ($situacao_renegociado) {
                $this->sql .= " and c.idsituacao <> '".$situacao_renegociado['idsituacao']."'  ";
            }

            if ($situacao_cancelado) {
                $this->sql .= " and c.idsituacao <> '".$situacao_cancelado['idsituacao']."'  ";
            }

            if($_POST['tipo_data_pagar']) {
              if($_POST['tipo_data_pagar'] == 'HOJ') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') = '".date("Y-m-d")."'";
              } else if($_POST['tipo_data_pagar'] == 'SET') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') <= '".date("Y-m-d")."'
                                  and date_format(c.data_vencimento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
              } else if($_POST['tipo_data_pagar'] == 'MAT') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m")."'";
              } else if($_POST['tipo_data_pagar'] == 'MPR') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
              } else if($_POST['tipo_data_pagar'] == 'MAN') {
                  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
              } else if($_POST['tipo_data_pagar'] == 'PER') {
                if($_POST['periodo_inicio_pagar'])
                  $this->sql .= " and DATE_FORMAT(c.data_vencimento,'%Y-%m-%d') >= '".formataData($_POST['periodo_inicio_pagar'],'en',0)."' ";
                if($_POST['periodo_final_pagar'])
                  $this->sql .= " and DATE_FORMAT(c.data_vencimento,'%Y-%m-%d') <= '".formataData($_POST['periodo_final_pagar'],'en',0)."'  ";
              }
            }

            $idsindicato = implode(', ', $_POST['idsindicato']);
            if($idsindicato) {
                $this->sql .= " and c.idsindicato in (".$idsindicato.") ";
            } else {
                if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                    if (!$_SESSION['adm_sindicatos'])
                        $_SESSION['adm_sindicatos'] = 0;
                    $this->sql .= ' and c.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ';
                }
            }

            $this->sql .= " group by c.idconta ";

            $this->ordem_campo = 'c.idconta';
            $this->limite = -1;
            $retorno['despesa'] = $this->retornarLinhas();
        }
        return $retorno;
    }

    function Retornar() {
        $this->sql = "SELECT ".$this->campos."
                      FROM
                        fechamentos_caixa fc
                        inner join usuarios_adm ua on fc.idusuario = ua.idusuario
                        INNER JOIN contas c ON fc.idfechamento = c.idfechamento
                        INNER JOIN contas_correntes cc ON c.idconta_corrente = cc.idconta_corrente
                        INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
                        LEFT JOIN mantenedoras m ON c.idmantenedora = m.idmantenedora
                        LEFT JOIN sindicatos i ON c.idsindicato = i.idsindicato
                        LEFT JOIN produtos p ON c.idproduto = p.idproduto
                        LEFT JOIN categorias cat ON c.idcategoria = cat.idcategoria
                        LEFT JOIN matriculas mat ON c.idmatricula = mat.idmatricula
                        LEFT JOIN pessoas pes_mat ON mat.idpessoa = pes_mat.idpessoa
                        LEFT JOIN fornecedores forn ON c.idfornecedor = forn.idfornecedor
                        LEFT JOIN pessoas pes ON c.idpessoa = pes.idpessoa
                      where ativo='S' and idfechamento='".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

}
