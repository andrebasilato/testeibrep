  <section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
    </div>
    <ul class="breadcrumb">
        <li><? echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["idvisita"]; ?></strong></li>
    </ul>

      <ul class="nav nav-tabs nav-stacked">
            <li>
                <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
                <? } else { ?>
                    <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
                <?php } ?>
            </li>
            <li>
                <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/mensagens"> <i class="icon-list-alt"></i> <? echo $idioma["mensagens"]; ?></a>
                <? } else { ?>
                    <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["mensagens"]; ?></a>
                <?php } ?>
            </li>
			<li>
                <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5", NULL)){ ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/visitas"> <i class="icon-list-alt"></i> <? echo $idioma["visitas"]; ?></a>
                <? } else { ?>
                    <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["visitas"]; ?></a>
                <?php } ?>
            </li>
            <li>
                <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
                <? } else { ?>
                    <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
                <?php } ?>
            </li>
      </ul>    
  </section>