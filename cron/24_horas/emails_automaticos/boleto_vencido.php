<?php

$data = new DateTime;
$qtdDias = (int) $email['dia'];

$sql = 'SELECT
            e.email,
            e.nome_fantasia as nome
        FROM
            contas c
            INNER JOIN contas_workflow cw ON cw.idsituacao = c.idsituacao
            INNER JOIN escolas e ON e.idescola = c.idescola
        WHERE DATE_ADD(c.data_vencimento, INTERVAL ' . $qtdDias . ' DAY) <= NOW()
        AND cw.ativo = \'S\'
        AND cw.emaberto = \'S\'
        AND c.ativo = \'S\'
        AND e.ativo = \'S\'
        AND c.forma_pagamento = 1';

$resultado = mysql_query($sql);
