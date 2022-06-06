<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["sindicatos"]; ?> &nbsp;
            <small><?php echo $idioma["sindicatos_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><?php echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
    </ul>
    <ul class="nav nav-tabs nav-stacked">
        <?php 
        if(count($associacoesArray) > 0) {
            foreach ($associacoesArray as $sindicato) { ?>
                <li>
                    <a href="#" style="cursor:default;">
                    <i class="icon-edit"></i> <? echo $sindicato["nome_abreviado"]; ?></a>
                </li>
            <?php }
        } else { ?>
            <li>
                <a href="#" style="cursor:default;">
                <i class="icon-edit"></i> <? echo $idioma["nenhuma_sindicato"]; ?></a>
            </li>
        <?php } ?>
    </ul>
</section>