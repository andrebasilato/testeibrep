<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["pagina_titulo"]; ?>
            <div class="pull-right">
                <a href="/<?= $url[0]; ?>/financeiro/pagamentos_compartilhados/<?= $arrayContasMatriculas[0]['idpagamento']; ?>/editar"
                   class="btn" target="_blank"><?= $idioma["btn_editar_pagamento"]; ?></a>
            </div>
            <br/>
            <small><?php echo $idioma["pagina_subtitulo"]; ?></small>
        </h1>
        <br/>
    </div>
</section>
<div class="span7">
    <div class="tabbable tabs-left">
        <div class="tab-content">
            <?php $linhaObj->GerarTabela($arrayContasMatriculas, $_GET["q"], $idioma, 'listagem_compartilhado', ''); ?>
        </div>
    </div>
</div>