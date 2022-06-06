<div class="row-fluid m-box">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <i class="closed-x" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
                <h1><?php echo '#'.$solicitacao['idsolicitacao_declaracao'].' - '.$solicitacao['nome']; ?></h1>
                <p><?php echo $idioma['data_solicitacao'];?> <strong><?php echo formataData($solicitacao['data_solicitacao'],'br',1); ?></strong></p>
                <p><?php echo $idioma['situacao'];?> <strong><?php echo $status_solicitacao_declaracao[$config['idioma_padrao']][$solicitacao['situacao']]; ?></strong></p>
                <div class="message-box">
                    <div class="span12 box-gray extra-align no-margin">
                        <?php echo nl2br($solicitacao['motivo_cancelamento']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>