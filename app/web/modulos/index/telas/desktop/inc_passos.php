<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 passos">
            <div class="col-sm-3 col-xs-3 item item1 <?= ($_SESSION['loja_passo'] >= 1) ? 'active' : ''; ?>">
                <span>1</span>
                <p><?= $idioma['cadastro']; ?></p>
            </div>
            <?php
            $passoConcluido = 2;
            if ($_SESSION['dados_escola']['pagseguro'] == 'S') {
                $passoConcluido = 3;
                ?>
                <div class="col-sm-3 col-xs-3 item item3 <?= ($_SESSION['loja_passo'] >= 2) ? 'active' : ''; ?>">
                    <span>2</span>
                    <p><?= $idioma['pagamento']; ?></p>
                </div>
                <?php
            }
            ?>
            <div class="col-sm-3 col-xs-3 item item4 <?= ($_SESSION['loja_passo'] >= 4) ? 'active' : ''; ?>">
                <span><?= $passoConcluido; ?></span>
                <p><?= $idioma['concluida']; ?></p>
            </div>
        </div>
    </div>
</div>