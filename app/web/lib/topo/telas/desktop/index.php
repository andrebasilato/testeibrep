<div class="container-fluid topo">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-8 marca">
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $_SESSION['dados_escola']['slug']; ?>" title="Home">
                    <img src="/assets/loja/img/marca-matricula.png" alt="Home" title="Home">
                </a>
                <?php
/*                if ($_SESSION['cliente_email'] && $_SESSION['cliente_senha']) {
                    */?><!--
                    <span class="negativo"><?php /*printf($idioma['usuario_logado'], $_SESSION['cliente_nome']); */?></span>
                    <a href="?opLogin=sair" class="btSair"><?/*= $idioma['sair']; */?></a>
                    --><?php
/*                }
                */?>
            </div>
            <?php
/*            if ($url[1] != 'login' && $_SESSION['loja_passo'] >= 1) {
                */?><!--
                <div class="col-sm-4 col-md-6">
                    <div class="row text-right">
                    <a href="?etapa=<?/*= $_SESSION['loja_etapa_voltar']; */?>" class="btTopo mt30"><?/*= $idioma['escolher_outro'][$_SESSION['loja_etapa_voltar']]; */?></a>
                    </div>
                </div>
                --><?php
/*            }
            */?>
        </div>
    </div>
</div>