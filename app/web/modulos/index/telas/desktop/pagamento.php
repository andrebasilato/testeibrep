<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>
    <?php incluirTela('inc_passos', $config, $usuario); ?>

    <div class="container mt50">
        <?php
        if (count($pagSeguro['erro_pagseguro']) > 0) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span><?= $idioma['erro_pagseguro'] . ': ' . $pagSeguro['erro_pagseguro']['code']; ?></span><br />
            </div>
            <?php
        }

        incluirTela('inc_curso', $config, $curso);
        ?>

        <div class="col-sm-8 itemForm">
            <div class="col-sm-12 itemForm cardPagamento">
                <?php
                if (count($GLOBALS['mensagens']) > 0) {
                    ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>
                            <?php
                            foreach ($GLOBALS['mensagens'] as $ind => $val) {
                                echo $idioma[$val] . '<br />';
                            }
                            ?>
                        </strong>
                    </div>
                    <?php
                }
                ?>
                <h3><?= $idioma['selecione_forma_pagamento']; ?></h3>
                <div class="bg">
                    <p><?= $idioma['descricao_pagamento']; ?></p>
                    <?php
                    if ($pagSeguro['sucesso']) {
                        $_SESSION['pagseguro']['retorno'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3];
                        ?>
                        <form id="formPagseguro" method="post" style="margin:0;">
                            <input type="hidden" id="acao" name="acao" value="finalizar">
                            <input type="hidden" id="tipo_pagamento" name="tipo_pagamento" value="PS">
                            <input type="hidden" id="codigo_transacao_pagseguro" name="codigo_transacao_pagseguro" value="">
                            <input type="button" id="btnPagseguro" value="<?= $idioma['efetuar_pagamento_pagseguro']; ?>">
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php incluirLib('rodape', $config, $usuario); ?>

    <?php
    if ($pagSeguro['sucesso']) {
        ?>
        <!-- SCRIPT PAGSEGURO -->
        <script type="text/javascript" src="<?= $config['pagSeguro']['urlStc']; ?>/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
        <script type="text/javascript">
            $('#btnPagseguro').click(function() {
                code = '<?= $pagSeguro['code']; ?>';
                isOpenLightbox = PagSeguroLightbox({
                    code: code
                }, {
                    success : function(transactionCode) {
                        //Caso continue o pagamento com sucesso
                        $('#codigo_transacao_pagseguro').val(transactionCode);
                        $('#formPagseguro').submit();
                    },
                    abort : function(transactionCode) {
                        //Se fechar o modal do pagseguro sem efetuar o pagamento
                    }
                });

                if (! isOpenLightbox) {
                    location.href = '<?= $config['pagSeguro']['url']; ?>/v2/checkout/payment.html?code=' + code;
                }
            });
        </script>
        <?php
    }
    ?>
</body>
</html>
