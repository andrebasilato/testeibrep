<?php
class Inicio extends Core
{
    public function set($variavel, $valor)
    {
        if (isset($valor)) {
            $this->$variavel = $valor;
        }
        return $this;
    }

    public function totalTransacoes($situacao = null, $tipo = null)
    {
        $hoje = new DateTime();
        $mes_passado = $hoje->modify('-1 month')->format('Y-m-d 00:00:00');

        $this->sql = "SELECT
                        COUNT(idtransacao) AS total
                    FROM orio_transacoes
                    WHERE
                        ativo = 'S' AND data_cad > '$mes_passado'";
        if ($situacao) {
            $this->sql .= " AND situacao = '" . $situacao . "' ";
        }
        if ($tipo) {
            $this->sql .= " AND tipo = '" . $tipo . "' ";
        }
        return $this->retornarLinha($this->sql)['total'];
    }

    public function totalTransacoesDiario($dias, $usuario = false)
    {
        $valores = null;
        $this->sql = "SELECT
                    DATE_FORMAT(data_cad, '%d/%m/%Y') as data,
                    count(idtransacao) as total
                FROM orio_transacoes
                WHERE
                    data_cad >= '" . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - ($dias - 1), date('Y'))) . "' and
                    ativo = 'S'
                GROUP BY DATE_FORMAT(data_cad, '%d/%m/%Y')";

        $this->orderBy = 'data';

        $valoresAux = $this->retornarLinhas();

        foreach ($valoresAux as $ind => $linha) {
            $valores['banco'][$linha['data']] = $linha['total'];
        }

        $valores['datas'] = array();
        $valores['totais'] = array();
        $valores['totais_geral'] = array();
        $valores['cont_total'] = 0;
        for ($i = 0; $i <= ($dias); $i++) {
            $data = date('d/m/Y', mktime(0, 0, 0, date('m'), (date('d') - $dias) + $i, date('Y')));
            $valores['datas'][] = '"' . $data . '"';
            if (isset($valores['banco'][$data])) {
                $valores['cont_total'] += $valores['banco'][$data];
            }
            $valores['totais_geral'][] = $valores['cont_total'];
            if (empty($valores['banco'][$data])) {
                $valores['totais'][] = 0;
            } else {
                $valores['totais'][] = $valores['banco'][$data];
            }
        }

        return $valores;
    }
}
