<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["modificacoes"]; ?> &nbsp;
            <small><?= $idioma["pagina_subtitulo"]; ?></small>
        </h1>
    </div>
    <div class="span7" style="margin:0">
        <div style="overflow: auto; max-height: 400px;">
            <? $linhaObj->GerarTabela($log, NULL, $idioma, "listagem_log"); ?>
        </div>
    </div>
</section>