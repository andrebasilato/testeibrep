<?php

require_once $caminhoApp . '/app/classes/loja.pedidos.class.php';

$lojaPedidoObj = new Loja_Pedidos;
$lojaPedidoObj->cancelarPedidosSemMatricula();
