<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["opcoes"]; ?> &nbsp;
            <small><?php echo $idioma["opcoes_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><?php echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo formataData($url[4], 'pt', 0); ?></strong></li>
    </ul>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/visualizamatriculas">
                    <i class="icon-edit"></i> <? echo $idioma["visualizar"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
            <?php } ?>
        </li>
        <? /*<li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idconta"]; ?>/historico"> <i class="icon-ok"></i> <? echo $idioma["historico"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-itle="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-ok icon-white"></i> <? echo $idioma["historico"]; ?></a>
      <?php } ?>
    </li> */
        ?>
    </ul>
</section>