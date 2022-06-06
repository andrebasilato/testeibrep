<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php incluirLib("head",$config,$usuario); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        body {
            background-color: #FFF !important;
            background-image:none;
            padding-top:0px !important;
        }
        body {
            min-width: 500px;
        }
        .container-fluid {
            min-width: 500px;
        }
        .status {
            cursor:pointer;  
            color:#FFF;
            font-size:9px;
            font-weight:bold;
            padding:5px;
            text-transform: uppercase;
            white-space: nowrap;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin-right:5px;
            line-height:30px;
        }
        .ativo {
            font-size:15px;     
        }
        .inativo {
            background-color:#838383;   
        }
    </style>
</head>
<body>
    <div class="row-fluid" >
        <div class="span12">
            <legend><?= $idioma['label_titulo']; ?></legend>
            <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemTamanho">
                <tr>
                    <td bgcolor="#F4F4F4"><strong><?= $idioma['tabela_campo']; ?></strong></td>
                    <td bgcolor="#F4F4F4"><strong><?= $idioma['tabela_valor']; ?></strong></td>
                </tr>
                <?php
                if (! empty($transacao) && count($transacao) > 0) {
                    if ($conta['forma_pagamento'] == 10) {//PagSeguro
                        $transacaoIdioma = 'pagseguro';

                        $transacao['type'] .= (isset($transacao['type'])) ? ' (' . $tipoTransacaoPagSeguro[$config['idioma_padrao']][$transacao['type']] . ')' : null;
                        $transacao['status'] .= (isset($transacao['status'])) ? ' (' . $statusTransacaoPagSeguro[$config['idioma_padrao']][$transacao['status']] . ')' : null;
                        $transacao['cancellationSource'] .= (isset($transacao['cancellationSource'])) ? ' (' . $origemCancelamentoPagSeguro[$config['idioma_padrao']][$transacao['cancellationSource']] . ')' : null;
                        $transacao['paymentMethod_type'] .= (isset($transacao['paymentMethod_type'])) ? ' (' . $tipoMeioPagamentoPagSeguro[$config['idioma_padrao']][$transacao['paymentMethod_type']] . ')' : null;
                        $transacao['paymentMethod_code'] .= (isset($transacao['paymentMethod_code'])) ? ' (' . $codigoIdentificadorMeioPagamentoPagSeguro[$config['idioma_padrao']][$transacao['paymentMethod_code']] . ')' : null;
                        $transacao['shipping_type'] .= (isset($transacao['shipping_type'])) ? ' (' . $tipoFretePagSeguro[$config['idioma_padrao']][$transacao['shipping_type']] . ')' : null;
                        
                        $transacao['grossAmount'] = (isset($transacao['grossAmount'])) ? number_format($transacao['grossAmount'], 2, ',', '.') : null;
                        $transacao['discountAmount'] = (isset($transacao['discountAmount'])) ? number_format($transacao['discountAmount'], 2, ',', '.') : null;
                        $transacao['feeAmount'] = (isset($transacao['feeAmount'])) ? number_format($transacao['feeAmount'], 2, ',', '.') : null;
                        $transacao['netAmount'] = (isset($transacao['netAmount'])) ? number_format($transacao['netAmount'], 2, ',', '.') : null;
                        $transacao['extraAmount'] = (isset($transacao['extraAmount'])) ? number_format($transacao['extraAmount'], 2, ',', '.') : null;
                        $transacao['installmentFeeAmount'] = (isset($transacao['installmentFeeAmount'])) ? number_format($transacao['installmentFeeAmount'], 2, ',', '.') : null;
                        $transacao['operationalFeeAmount'] = (isset($transacao['operationalFeeAmount'])) ? number_format($transacao['operationalFeeAmount'], 2, ',', '.') : null;
                        $transacao['intermediationRateAmount'] = (isset($transacao['intermediationRateAmount'])) ? number_format($transacao['intermediationRateAmount'], 2, ',', '.') : null;
                        $transacao['intermediationFeeAmount'] = (isset($transacao['intermediationFeeAmount'])) ? number_format($transacao['intermediationFeeAmount'], 2, ',', '.') : null;
                        $transacao['items_item_amount'] = (isset($transacao['items_item_amount'])) ? number_format($transacao['items_item_amount'], 2, ',', '.') : null;
                        $transacao['shipping_cost'] = (isset($transacao['shipping_cost'])) ? number_format($transacao['shipping_cost'], 2, ',', '.') : null;

                        if (isset($transacao['date'])) {
                            $date = new DateTime($transacao['date']);
                            $date->setTimezone(new DateTimeZone('America/Bahia'));
                            $transacao['date'] = sprintf($idioma['data_hora'], $date->format('d/m/Y'), $date->format('H:i:s'));
                        }

                        if (isset($transacao['lastEventDate'])) {
                            $date = new DateTime($transacao['lastEventDate']);
                            $date->setTimezone(new DateTimeZone('America/Bahia'));
                            $transacao['lastEventDate'] = sprintf($idioma['data_hora'], $date->format('d/m/Y'), $date->format('H:i:s'));
                        }

                        if (isset($transacao['escrowEndDate'])) {
                            $date = new DateTime($transacao['escrowEndDate']);
                            $date->setTimezone(new DateTimeZone('America/Bahia'));
                            $transacao['escrowEndDate'] = sprintf($idioma['data_hora'], $date->format('d/m/Y'), $date->format('H:i:s'));
                        }
                    }

                    foreach ($transacao as $ind => $var) {
                        ?>
                        <tr>
                            <td><?= (! empty($idioma[$transacaoIdioma][$ind])) ? $idioma[$transacaoIdioma][$ind] : $ind; ?></td>
                            <td><?= $var; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>