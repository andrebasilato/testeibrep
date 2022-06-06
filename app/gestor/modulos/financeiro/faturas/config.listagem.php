<?php

$config['listagem'] = array(
    array(
        'id' => 'idconta',
        'variavel_lang' => 'tabela_idconta',
        'tipo' => 'php',
        'coluna_sql' => 'c.idconta',
        'valor' => '$diferenca = dataDiferenca($linha["data_cad"], date("Y-m-d H:i:s"), "H");
                    if ($diferenca > 24) {
                        return "<span title=\"$diferenca\">".$linha["idconta"]."</span>";
                    } else {
                        return "<span title=\"$diferenca\">".$linha["idconta"]."</span> <i class=\"novo\"></i>";
                    }',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 80
    ),
    array(
        'id' => 'escola',
        'variavel_lang' => 'tabela_escola',
        'tipo' => 'banco',
        'coluna_sql' => 'c.idescola',
        'valor' => 'escola',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto select231',
        'busca_tipo' => 'select',
        'busca_sql' => 'SELECT e.idescola,
                            CONCAT(e.idescola, " - ", e.nome_fantasia) AS nome
                        FROM
                            escolas e
                        WHERE
                            e.ativo = "S" AND
                            e.ativo_painel = "S"',
        'busca_sql_valor' => 'idescola',
        'busca_sql_label' => 'nome',
        'busca_metodo' => 1
    ),
    array(
        'id' => 'valor',
        'variavel_lang' => 'tabela_valor',
        'tipo' => 'php',
        'coluna_sql' => 'c.valor',
        'valor' => 'return "R$ " . number_format($linha["valor"], 2, ",", ".");',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 4
    ),
    array(
        'id' => 'valor_corrigido',
        'variavel_lang' => 'tabela_valor_corrigido',
        'tipo' => 'php',
        'coluna_sql' => 'c.valor',
        'valor' => '
            if (! empty($linha["valor_corrigido"])) {
                return "<span style=\"color:#FF0000\">
                        R$ " . number_format($linha["valor_corrigido"], 2, ",", ".") . "
                    </span>";
            }',
        'nao_ordenar' => true,
        'busca' => false
    ),
    array(
        'id' => 'data_vencimento',
        'variavel_lang' => 'tabela_data_vencimento',
        'tipo' => 'php',
        'coluna_sql' => 'c.data_vencimento',
        'valor' => '
            $vencido = null;
            if (
                (new DateTime($linha["data_vencimento"]))->format("Y-m-d") < (new DateTime())->format("Y-m-d")
                && $linha["pago"] == "N"
            ) {
                $vencido = "color:#FF0000";
            }

            return "<span style=\"". $vencido . "\">
                    " . formataData($linha["data_vencimento"], "br", 0) . "
                </span>";',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'qnt_matriculas',
        'variavel_lang' => 'tabela_qnt_matriculas',
        'tipo' => 'banco',
        'coluna_sql' => 'c.qnt_matriculas',
        'valor' => 'qnt_matriculas',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1
    ),
    array(
        'id' => 'situacao',
        'variavel_lang' => 'tabela_situacao',
        'tipo' => 'php',
        'coluna_sql' => 'c.idsituacao',
        'valor' => '
            return "<span
                data-original-title=\"" . $linha["situacao"] . "\"
                class=\"label\"
                style=\"background:#" . $linha["situacao_cor_bg"] . "; color:#" . $linha["situacao_cor_nome"] . "\"
                data-placement=\"left\"
                rel=\"tooltip\">
                " . $linha["situacao"] . "
            </span>";',
        'busca' => true,
        'busca_sql' => 'SELECT idsituacao, nome FROM contas_workflow WHERE ativo = "S"',
        'busca_sql_valor' => 'idsituacao',
        'busca_sql_label' => 'nome',
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1
    ),
    array(
        'id' => 'data_modificacao_fatura',
        'variavel_lang' => 'tabela_data_modificacao_fatura',
        'tipo' => 'php',
        'coluna_sql' => 'c.data_modificacao_fatura',
        'valor' => 'return formataData($linha["data_modificacao_fatura"], "br", 1);',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'tipo' => 'php',
        'coluna_sql' => 'c.data_cad',
        'valor' => 'return formataData($linha["data_cad"], "br", 1);'
    ),
    array(
        'id' => 'pagarme_id',
        'variavel_lang' => 'tabela_pagarme_id',
        'tipo' => 'banco',
        'coluna_sql' => 'p.id',
        'valor' => 'pagarme_id',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 6,
        'nao_ordenar' => true,
        'tamanho' => 80
    ),
    array(
        'id' => 'statusPagarme',
        'variavel_lang' => 'tabela_statusPagarme',
        'tipo' => 'php',
        'coluna_sql' => 'p.status',
        'valor' => '
            if ($linha["statusPagarme"]) {
                return "
                    <span
                    data-original-title=\"" . $GLOBALS["statusTransacaoPagarme"][$GLOBALS["config"]["idioma_padrao"]][$linha["statusPagarme"]] . "\"
                    class=\"label\"
                    style=\"background:" . $GLOBALS["statusTransacaoPagarmeCor"][$linha["statusPagarme"]] . "; color:#FFF\"
                    data-placement=\"left\"
                    rel=\"tooltip\">
                        " . $GLOBALS["statusTransacaoPagarmeSigla"][$GLOBALS["config"]["idioma_padrao"]][$linha["statusPagarme"]] . "
                    </span>";
            }',
        'busca' => true,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_array' => 'statusTransacaoPagarme',
        'busca_metodo' => 6,
        'nao_ordenar' => true,
        'tamanho' => 120
    ),
    array(
        'id' => 'pagar',
        'variavel_lang' => 'tabela_pagar',
        'tipo' => 'php',
        'valor' => '
            if (
                $GLOBALS["config"]["pagarme"]["encryption_key"] &&
                $GLOBALS["config"]["pagarme"]["api_key"] &&
                $linha["emaberto"] == "S" &&
                $linha["valor"] > 0
            ) {
                $telefone = str_replace(array("(", ")", "-", " "), "", $linha["telefone"]);

                $proximoDiaUtilVencimento = proximoDiaUtil((new DateTime($linha["boleto_expiration_date"]))->format("Y-m-d"));
                
                $pagarme = "";

                $pagarme = "
                    <form method=\"post\" action=\"\">
                        <input type=\"hidden\" id=\"acao\" name=\"acao\" value=\"baixa_manual\" />
                        <input type=\"hidden\" id=\"idconta\" name=\"idconta\" value=\"" . $linha["idconta"] . "\" />
                        <a data-original-title=\"" . $idioma["tabela_baixa_manual_tooltip"] . "\" data-placement=\"left\" rel=\"tooltip\">
                            <input class=\"pagarme-checkout-btn btn btn-mini\" type=\"submit\" value=\"" . $idioma["tabela_baixa_manual"] . "\">
                        </a>
                    </form>
                ";

                if (
                    $linha["payment_method"] == "boleto"
                    && $linha["boleto_url"]
                    && $proximoDiaUtilVencimento->format("Y-m-d") >= (new DateTime())->format("Y-m-d")
                ) {
                    $pagarme .= "
                        <a
                        class=\"btn btn-mini\"
                        data-original-title=\"" . $idioma["tabela_boleto_tooltip"] . "\"
                        href=\"" . $linha["boleto_url"] . "\"
                        target=\"_blank\"
                        data-placement=\"left\"
                        rel=\"tooltip\">
                            " . $idioma["tabela_boleto"] . "
                        </a>";
                } elseif (empty($GLOBALS["config"]["pagarme"]["habilitar_checkout"])) {
                    $pagarme .= "
                        <form method=\"post\" action=\"\">
                            <input type=\"hidden\" id=\"acao\" name=\"acao\" value=\"criar_boleto_pagarme\" />
                            <input type=\"hidden\" id=\"idconta\" name=\"idconta\" value=\"" . $linha["idconta"] . "\" />
                            <a data-original-title=\"" . $idioma["tabela_criar_boleto_tooltip"] . "\" data-placement=\"left\" rel=\"tooltip\">
                                <input class=\"pagarme-checkout-btn btn btn-mini\" type=\"submit\" value=\"" . $idioma["tabela_pagar"] . "\">
                            </a>
                        </form>";
                }

                if (
                    ! empty($GLOBALS["config"]["pagarme"]["habilitar_checkout"])
                    && ($linha["totalPagamentosAbertos"] == 0 || $linha["payment_method"] == "boleto")
                ) {
                    $qtdParcelas = 1;
                    if (! empty($linha["qtd_parcelas"])) {
                        $qtdParcelas = $linha["qtd_parcelas"];
                    }

                    $payment_methods = array();
                    foreach ($linha["formas_pagamento"] as $ind => $var) {
                        $payment_methods[] = $GLOBALS["forma_pagamento_pagarme"][$var];
                    }

                    $pagarme .= "
                        <form method=\"post\" action=\"\">
                            <input type=\"hidden\" id=\"acao\" name=\"acao\" value=\"capturar_pagarme\" />
                            <input type=\"hidden\" id=\"idconta\" name=\"idconta\" value=\"" . $linha["idconta"] . "\" />
                            <a data-original-title=\"" . $idioma["tabela_pagar_tooltip"] . "\" data-placement=\"left\" rel=\"tooltip\">
                                <script type=\"text/javascript\"
                                    src=\"https://assets.pagar.me/checkout/checkout.js\"
                                    data-encryption-key=\"" . $GLOBALS["config"]["pagarme"]["encryption_key"] . "\"
                                    data-button-text=\"" . $idioma["tabela_pagar"] . "\"
                                    data-button-class=\"btn btn-mini\"
                                    data-header-text=\"Total a pagar {price_info}\"
                                    data-ui-color=\"#1a6ee1\"
                                    data-create-token=\"true\"
                                    data-postback-url=\"" . $GLOBALS["config"]["pagarme"]["postback_url"] . "\"
                                    data-amount=\"" . str_replace(".", "", $linha["valor"]) . "\"
                                    data-payment-methods=\"" . implode(",", $payment_methods) . "\"
                                    data-card-brands=\"visa,mastercard,amex,aura,jcb,diners,elo\"
                                    data-max-installments=\"" . $qtdParcelas . "\"
                                    data-interest-rate=\"0\"
                                    data-free-installments=\"" . $qtdParcelas . "\"
                                    data-default-installment=\"1\"
                                    data-customer-name=\"" . $linha["escola"] . "\"
                                    data-customer-document-number=\"" . $linha["documento"] . "\"
                                    data-customer-data=\"false\"";

                    if (
                        $linha["email"] &&
                        $linha["endereco"] &&
                        $linha["numero"] &&
                        $linha["bairro"] &&
                        $linha["cidade"] &&
                        $linha["uf"] &&
                        $linha["cep"] &&
                        substr($telefone, 0, 2) &&
                        substr($telefone, 2)
                    ) {
                        $pagarme .= "data-customer-email=\"" . $linha["email"] . "\"
                            data-customer-address-street=\"" . $linha["endereco"] . "\"
                            data-customer-address-street-number=\"" . $linha["numero"] . "\"
                            data-customer-address-complementary=\"" . $linha["complemento"] . "\"
                            data-customer-address-neighborhood=\"" . $linha["bairro"] . "\"
                            data-customer-address-city=\"" . $linha["cidade"] . "\"
                            data-customer-address-state=\"" . $linha["uf"] . "\"
                            data-customer-address-zipcode=\"" . $linha["cep"] . "\"
                            data-customer-phone-ddd=\"" . substr($telefone, 0, 2) . "\"
                            data-customer-phone-number=\"" . substr($telefone, 2) . "\"";
                    }

                    $pagarme .= ">
                            </script>
                        </a>
                    </form>";
                }

                return $pagarme;
            }',
        'nao_ordenar' => true,
        'tamanho' => 100
    ),
    array(
        'id' => 'ficha',
        'variavel_lang' => 'tabela_opcoes',
        'tipo' => 'php',
        'valor' => '
                $botoes = "<a
                class=\"btn btn-mini\"
                data-original-title=\"" . $idioma["tabela_ficha_tooltip"] . "\"
                href=\"/" . $this->url[0] . "/" . $this->url[1] . "/" . $this->url[2] . "/" . $linha["idconta"] . "/ficha\"
                target=\"_blank\"
                data-placement=\"left\"
                rel=\"tooltip\">
                    " . $idioma["tabela_ficha"] . "
                </a>";
            
            if($linha["emaberto"] == "S"){
                $botoes .= "<a
                class=\"btn btn-mini\"
                data-original-title=\"" . $idioma["tabela_cancelar_tooltip"] . "\"
                href=\"/" . $this->url[0] . "/" . $this->url[1] . "/" . $this->url[2] . "/" . $linha["idconta"] . "/removerFatura\"
                target=\"_blank\"
                data-placement=\"left\"
                rel=\"tooltip\"
                onclick=\"confirmaCancelamento()\" >
                    " . $idioma["tabela_cancelar"] . "                  
                </a>"; 
             }
             
            return $botoes; ',
        'busca_botao' => true,
        'tamanho' => 65
    )
);

$config['listagem_ficha'] = array(
    array(
        'id' => 'idmatricula',
        'variavel_lang' => 'tabela_idmatricula', 
        'tipo' => 'banco', 
        'valor' => 'idmatricula',
    ),
    
    array(
        'id' => 'idaluno',
        'variavel_lang' => 'tabela_nome', 
        'tipo' => 'banco', 
        'valor' => 'nome',
    ),

    array(
        'id' => 'idcurso',
        'variavel_lang' => 'tabela_curso',
        'tipo' => 'banco',
        'valor' => 'curso',

    ),

    array(
        'id' => 'idcfc',
        'variavel_lang' => 'tabela_cfc',
        'tipo' => 'banco',
        'valor' => 'cfc',

    ),

    array(
        'id' => 'data_cad', 
        'variavel_lang' => 'tabela_data_cad', 
        'tipo' => 'php', 
        'valor' => 'return formataData($linha["data_cad"], "br", 0);'
    ),

    array(
        'id' => 'parcela', 
        'variavel_lang' => 'tabela_parcela', 
        'tipo' => 'banco', 
        'valor' => 'parcela'
    ),

    array(
        'id' => 'total_parcelas', 
        'variavel_lang' => 'tabela_total_parcelas', 
        'tipo' => 'banco', 
        'valor' => 'total_parcelas'
    ),

    array(
        'id' => 'valor_fatura', 
        'variavel_lang' => 'tabela_valor_fatura', 
        'tipo' => 'php', 
        'valor' => 'return "R$ " . number_format($linha["valor_fatura"], 2, ",", ".") . "</span>";'
    ),

    array(
        'id' => 'valor_total', 
        'variavel_lang' => 'tabela_valor_total', 
        'tipo' => 'php', 
        'valor' => 'return "R$ " . number_format($linha["valor_total"], 2, ",", ".") . "</span>";'
    ),
    array(
        'id' => 'data_venc',
        'variavel_lang' => 'tabela_vencimento',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["vencimento"], "br", 0);'
    )

);
