<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["pagina_titulo"]; ?> &nbsp;
            <small><?php echo $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
</section>
<div class="span12">
    <div class="tabbable tabs-left">
        <div class="tab-content">
            <?php $linhaObj->GerarTabela($arrayContas, $_GET["q"], $idioma, $listagem); ?>
        </div>
    </div>
</div>
