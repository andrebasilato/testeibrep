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
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?php echo $linha["idconta"]; ?>/editar">
                    <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
            <?php } ?>
        </li>
        <li>  <!--NOVA OPÇÃO DETALHAR-->
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <a data-placement="left" rel="tooltip facebox" href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/dia/<?php echo $linha["data_vencimento"]; ?>/faturas?<?php echo http_build_query($_GET);?>/">
                    <i class="icon-edit"></i> <? echo $idioma["detalhar"]; ?> </a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;">
                    <i class="icon-edit icon-white"></i> <? echo $idioma["detalhar"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?php echo $linha["idconta"]; ?>/centros_custos">
                    <i class="icon-list-alt"></i> <? echo $idioma["centros_custos"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-itle="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-list-alt icon-white"></i> <? echo $idioma["centros_custos"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|1", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?php echo $linha["idconta"]; ?>/historico">
                    <i class="icon-list-alt"></i> <? echo $idioma["historico"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-itle="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-list-alt icon-white"></i> <? echo $idioma["historico"]; ?></a>
            <?php } ?>
        </li>
		<li>
		  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
			<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?php echo $linha["idconta"]; ?>/pastavirtual"> <i class="icon-share-alt"></i> <? echo $idioma["pastavirtual"]; ?></a>
		  <? } else { ?>
			<a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share-alt icon-white"></i> <? echo $idioma["pastavirtual"]; ?></a>
		  <?php } ?>
		</li>
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3", NULL)){ ?>
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?php echo $linha["idconta"]; ?>/remover">
                <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
        <?php } else { ?>
            <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left"
               rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?>
            </a>
        <?php } ?>
        </li>
    </ul>
</section>

<script>
    $('a[rel*=facebox]').facebox();
    $('a[rel*=tooltip]').tooltip();
</script>