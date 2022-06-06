<?php

set_time_limit(0);
require_once $caminhoApp . '/app/classes/pagseguro.class.php';
require_once $caminhoApp . '/app/classes/loja.pedidos.class.php';
require_once $caminhoApp . '/app/classes/escolas.class.php';
require_once $caminhoApp . '/app/classes/matriculas.class.php';

$_GET['q']['1|p.pagseguro'] = 'S';

$escolaObj = new Escolas();
$escolaObj->set('campos', 'p.idescola')
    ->set('ordem_campo', 'p.idescola')
    ->set('ordem', 'ASC')
    ->set('limite', -1);
$escolas = $escolaObj->listarTodas();

unset($_GET['q']['1|p.pagseguro']);

foreach ($escolas as $ind => $var) {
    $pagSeguroObj = new PagSeguro($var['idescola']);

    $dataInicial = (new DateTime)->modify('-6 days')->format('Y-m-d\TH:i');
    $dataFinal = (new DateTime)->format('Y-m-d\TH:i');

    $pagina = 1;
    $limite = 1000;

    //Retorna todas as transações realizadas no PagSeguro no perído de 7 dias
    while ($transacoesPagseguro = $pagSeguroObj->retornaTransacoesPorPeriodo($dataInicial, $dataFinal, $pagina, $limite)) {
        $paginaAtual = (int) $transacoesPagseguro['xml']->currentPage;
        $totalPaginas = (int) $transacoesPagseguro['xml']->totalPages;

        foreach ($transacoesPagseguro['xml']->transactions->transaction as $ind => $var) {
            //Se a referência for de um pedido e o status estiver como 3: Paga, 4: Disponível(o valor da transação está disponível para saque)
            if (substr($var->reference, 0, 4) == 'PED_') {
                $idPedido = str_replace('PED_', '', $var->reference);

                $lojaPedidoObj = new Loja_Pedidos;
                $pedido = $lojaPedidoObj->set('id', $idPedido)
                    ->set('campos', '*')
                    ->retornar();

                if (! empty($pedido['idpedido']) && $pedido['situacao'] == 'A') {
                    $post = [];

                    $post['idoferta'] = $pedido['idoferta'];
                    $post['idcurso'] = $pedido['idcurso'];
                    $post['idturma'] = $pedido['idturma'];
                    $post['idescola'] = $pedido['idescola'];
                    $post['idpedido'] = $pedido['idpedido'];
                    $post['idpessoa'] = $pedido['idpessoa'];
                    $post['pagamento']['tipo_pagamento'] = 'PS';
                    $post['pagamento']['codigo_transacao_pagseguro'] = (string) $var->code;

                    $matriculaObj = new Matriculas();
                    $matriculaObj->set('nao_monitara', true)
                        ->set('post', $post)
                        ->set('modulo', $pedido['modulo'])
                        ->cadastrar($idPedido);
                }
            } elseif (substr($var->reference, 0, 2) == 'C_') {
                $idConta = str_replace('C_', '', $var->reference);

                $post = [];
                $post['idconta'] = $idConta;
                $post['tipo_pagamento'] = 'PS';
                $post['codigo_transacao_pagseguro'] = $var->code;

                $pagSeguroObj->set('post', $post)
                    ->criarTransacao();
            }
        }

        $pagina++;

        if ($paginaAtual == $totalPaginas) {
            break;
        }
    }
}
