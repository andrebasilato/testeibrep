<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Conteudo -->
<div class="content">
    <p class="texto-index"></p>
</div>
<div class="content">
    <div class="box-bg">
        <span class="top-box box-azul">
            <h1><?= $idioma['contas']; ?></h1>
            <i class="icon-folder-open"></i>            
        </span>
        <h2 class="ball-icon">&bull;</h2>
        <div class="clear"></div>
        <!-- Atendimentos --> 
        <div class="row-fluid">
            <div class="span12 abox box-item extra-align">
                <?php
                if($_POST['msg']) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong><?= $idioma[$_POST['msg']]; ?></strong>
                    </div>
                    <?php
                }

                foreach ($matriculas as $matricula) { 
                    ?>
                    <div class="row-fluid">
                        <div class="span12 border-box">
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="imagem-item"><img src="/api/get/imagens/cursos_imagem_exibicao/168/114/<?= $matricula["imagem_exibicao_servidor"]; ?>" alt="Curso" /></div>
                                </div>
                                <div class="span10">
                                    <div class="row-fluid show-grid">
                                        <div class="span12 description-item r-margin">
                                            <div class="span8">
                                                <h1><?= $matricula['curso']; ?></h1>
                                                <p><?= $idioma['carga_horaria']; ?> <strong><?= $matricula['carga_horaria_total']; ?></strong></p>
                                                <p><?= $idioma['matricula']; ?> <strong><?= $matricula['idmatricula']; ?></strong></p>
                                                <p><?= $idioma['andamaento_curso']; ?> <strong><?= number_format($matricula['porcentagem'],2,',','.'); ?>%</strong></p>
                                            </div>                            
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                        <thead class="a-table">
                                            <tr bgcolor="#e6e6e6">
                                                <td align="center"><?= $idioma['vencimento']; ?></td>
                                                <td align="center"><?= $idioma['valor']; ?></td>
                                                <td align="center"><?= $idioma['forma_pagamento']; ?></td>
                                                <td align="center"><?= $idioma['status']; ?></td>
                                                <td align="center"><?= $idioma['status_transacao']; ?></td>
                                                <td align="center"><?= $idioma['observacoes']; ?></td>
                                            </tr>
                                        </thead>
                                        <?php if(count($matricula["contas"])) { ?>
                                            <tbody class="a-table">
                                                <?php
                                                foreach($matricula['contas'] as $conta){ 
                                                    $observacao = '';
                                                    if($conta['forma_pagamento'] == 2 || $conta['forma_pagamento'] == 3) {//Se for 2 = cartão de crédito ou 3 = cartão de débito
                                                        if ($conta["tid"]) {
                                                            $observacao .= $idioma["bandeira"]." ".$forma_pagamento_loja[$GLOBALS["config"]["idioma_padrao"]][$conta["bandeira"]]."<br>";
                                                            $observacao .= $idioma["tid"]." ".$conta["tid"]."<br>";
                                                            $observacao .= $idioma["autorizacao_cartao"]." ".$conta["autorizacao_cartao"]."<br>";
                                                            $observacao .= $idioma["data_pagamento"]." ".formataData($conta["data_pagamento"], "br", 0)."<br>"; 
                                                        } else {
                                                            if ($conta["bandeira_cartao"]) {
                                                                $observacao .= $idioma["bandeira_cartao"]." ".$conta["bandeira_cartao"]."<br>";
                                                            }

                                                            if ($conta["autorizacao_cartao"]) {
                                                                $observacao .= $idioma["autorizacao_cartao"]." ".$conta["autorizacao_cartao"];
                                                            }
                                                        }
                                                    } elseif ($conta["forma_pagamento"] == 4) {//Se for 4 = cheque
                                                        $observacao .= $idioma["banco"]." ".$conta["banco"]."<br>";
                                                        $observacao .= $idioma["agencia_cheque"]." ".$conta["agencia_cheque"]."<br>";
                                                        $observacao .= $idioma["cc_cheque"]." ".$conta["cc_cheque"]."<br>";
                                                        $observacao .= $idioma["numero_cheque"]." ".$conta["numero_cheque"]."<br>";
                                                        $observacao .= $idioma["emitente_cheque"]." ".$conta["emitente_cheque"];
                                                    }

                                                    $strike = '';
                                                    if ($situacaoRenegociadaConta['idsituacao'] == $conta['idsituacao'] || $situacaoCanceladaConta['idsituacao'] == $conta['idsituacao']) {
                                                      $strike = 'style="text-decoration:line-through;"';
                                                    }
                                                    ?>
                                                    <tr class="tx-capslock tabela-resultado" >
                                                        <td>
                                                            <?= formataData($conta["data_vencimento"], "br", 0); ?>
                                                        </td>
                                                        <td <?= $strike; ?>>
                                                            R$ <?= number_format($conta["valor"],2,",","."); ?>
                                                        </td>
                                                        <td>
                                                            <?= $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$conta["forma_pagamento"]]; ?>
                                                        </td>
                                                        <td style="color:#<?= $conta["cor_bg"]; ?>;">
                                                            <?= $conta["situacao"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($conta['pagSeguro']['status']) {
                                                                ?>
                                                                <span
                                                                data-original-title="<?= $GLOBALS['statusTransacaoPagSeguro'][$GLOBALS['config']['idioma_padrao']][$conta['pagSeguro']['status']]; ?>"
                                                                class="label"
                                                                style="background:<?= $GLOBALS['statusTransacaoPagSeguroCor'][$conta['pagSeguro']['status']]; ?>; color:#FFF;"
                                                                data-placement="left"
                                                                rel="tooltip">
                                                                    <?= $GLOBALS['statusTransacaoPagSeguroSigla'][$GLOBALS['config']['idioma_padrao']][$conta['pagSeguro']['status']]; ?>
                                                                </span>
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($conta['fastConnect']['idsituacao']) {
                                                                ?>
                                                                <span
                                                                        data-original-title="<?= $GLOBALS['situacoesTransacaoFastConnect'][$GLOBALS['config']['idioma_padrao']][$conta['fastConnect']['idsituacao']]; ?>"
                                                                        class="label"
                                                                        style="background:<?= $GLOBALS['statusTransacaoPagSeguroCor'][$conta['pagSeguro']['status']]; ?>; color:#FFF;"
                                                                        data-placement="left"
                                                                        rel="tooltip">
                                                                    <?= $GLOBALS['situacoesTransacaoFastConnect'][$GLOBALS['config']['idioma_padrao']][$conta['fastConnect']['idsituacao']]; ?>
                                                                </span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="tx-left">
                                                            <?php
                                                            if (
                                                                $conta['situacao_paga'] == 'N'
                                                                && $conta['situacao_cancelada'] == 'N'
                                                                && $conta['valor'] > 0
                                                            ) {
                                                                if (
                                                                    $conta['fastConnect']['fastconnect_client_code']
                                                                    && $conta['fastConnect']['fastconnect_client_key']
                                                                    && $conta['forma_pagamento'] == 11
                                                                ) {
                                                                    $textofastConnect = '';

                                                                    //Se o método de pagamento for 2:Boleto e tiver o link do boleto
                                                                    if ($conta['fastConnect']['tipo'] == "boleto" && $conta['fastConnect']['link_pagamento'] && $conta['situacao_fastConnect'] == 'N') {
                                                                        $textofastConnect .= '<a
                                                                            class="btn btn-azul"
                                                                            data-original-title="' . $idioma['boleto_tooltip'] . '"
                                                                            href="' . $conta['fastConnect']['link_pagamento'] . '"
                                                                            target="_blank"
                                                                            data-placement="left"
                                                                            rel="tooltip">
                                                                                ' . $idioma['boleto'] . '
                                                                            </a>';
                                                                    }

                                                                    if ($conta['totalPagamentosAbertosFastConnect'] == 0 && $conta['fastconnect_url_link'] && $conta['situacao_fastConnect'] == "N") {

                                                                        $textofastConnect .= '<a class="btn btn-verde" data-original-title="' . $idioma['pagar_tooltip'] . '" data-placement="left" rel="tooltip" href="'. $conta['fastconnect_url_link'] .'" target="_blank">' . $idioma['pagar'] . '</a>';
                                                                    }

                                                                    echo $textofastConnect;
                                                                }
                                                            }
                                                            ?>
                                                            <p align="center"> <?= $observacao; ?> </p>
                                                        </td>
                                                    </tr>
                                                  <?php } ?> 
                                            </tbody>
                                        <?php } else { ?>
                                            <tbody class="b-table">
                                                <tr>
                                                    <td colspan="5"><i><?= $idioma['nenhuma_conta']; ?></i></td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                <?php } ?>
            </div>
        </div>  
        <!-- Atendimentos --> 
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script src="/assets/js/validation.js"></script>
<script type="text/javascript">
function descerScroll() {  
    var objScrDiv = document.getElementById("divScroll");  
    objScrDiv.scrollTop = objScrDiv.scrollHeight;  
} 

$(document).ready(function() {  
    // Support for AJAX loaded modal window.
    // Focuses on first input textbox after it loads the window.
    $('.abrirModal').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var atendimento = url.split('/')[4];
        if (url.indexOf('#') == 0) {
            $(url).modal('open').on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
        } else {
            $.get(url, function(data) {
                $('<div class="modal hide fade text-side-two extra-align" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+data+'</div>').modal().on('shown', function () { descerScroll(); }).on("hidden", function () { $(this).remove(); });
            }).success(function() { 
                $('input:text:visible:first').focus();
            });
        }
    });
});
</script>

<?php
if ($config['pagSeguro']['urlStc']) {
    $_SESSION['pagseguro']['retorno'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2];
    ?>
    <form id="formPagseguro" method="post">
        <input type="hidden" id="acao" name="acao" value="salvar_pagamento" />
        <input type="hidden" id="idconta" name="idconta" value="" />
        <input type="hidden" id="tipo_pagamento" name="tipo_pagamento" value="PS">
        <input type="hidden" id="codigo_transacao_pagseguro" name="codigo_transacao_pagseguro" value="">
    </form>

    <!-- SCRIPT PAGSEGURO -->
    <script type="text/javascript" src="<?= $config['pagSeguro']['urlStc']; ?>/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
    <script type="text/javascript">
        $('.pagseguro').click(function() {
            var idconta = $(this).attr('conta');
            var code = $(this).attr('code');

            if (idconta && code) {
                isOpenLightbox = PagSeguroLightbox({
                    code: code
                }, {
                    success : function(transactionCode) {
                        //Caso continue o pagamento com sucesso
                        $('#formPagseguro #codigo_transacao_pagseguro').val(transactionCode);
                        $('#formPagseguro #idconta').val(idconta);
                        $('#formPagseguro').submit();
                    },
                    abort : function(transactionCode) {
                        //Se fechar o modal do pagseguro sem efetuar o pagamento
                    }
                });
            }
        });
    </script>
    <?php
}
?>
</body>
</html>