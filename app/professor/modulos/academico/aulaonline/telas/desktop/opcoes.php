<section id="global">
    <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
    <ul class="breadcrumb">
        <li><?php echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
    </ul>

    <ul class="nav nav-tabs nav-stacked">
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idaula"]; ?>/editar"> <i
                    class="icon-edit"></i> <?php echo $idioma["editar"]; ?></a>
        </li>
        <!--<li>
            <?php /*if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ */?>
        <li><a href="/<?/*= $url[0]; */?>/<?/*= $url[1]; */?>/<?/*= $url[2]; */?>/<?php /*echo $linha["idaula"]; */?>/remover"> <i class="icon-remove"></i> <?php /*echo $idioma["remover"]; */?></a></li>
        <?php /*} else { */?>
            <a href="javascript:void(0)" data-original-title="<?/*= $idioma["opcao_permissao"] */?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <?php /*echo $idioma["remover"]; */?></a>
        <?php /*} */?>
        </li>-->
    </ul>
</section>
