<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["opcoes"]; ?> &nbsp;
            <small><?php echo $idioma["opcoes_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><?php echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
    </ul>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idtipo"]; ?>/editar"> <i
                        class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idtipo"]; ?>/cursos"> <i
                        class="icon-share-alt"></i> <? echo $idioma["cursos"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-share-alt icon-white"></i> <? echo $idioma["cursos"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|7", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idtipo"]; ?>/sindicatos">
                    <i class="icon-share-alt"></i> <? echo $idioma["sindicatos"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-share-alt icon-white"></i> <? echo $idioma["sindicatos"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idtipo"]; ?>/sindicatos_agendamento">
                    <i class="icon-share-alt"></i> <? echo $idioma["sindicatos_agendamento"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-share-alt icon-white"></i> <? echo $idioma["sindicatos_agendamento"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idtipo"]; ?>/remover"> <i
                    class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
        <?php } else { ?>
            <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left"
               rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?>
            </a>
        <?php } ?>
        </li>
    </ul>
</section>