<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, "...") : $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/areas"> <i class="icon-ok-circle"></i> <? echo $idioma["curso_areas"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["curso_areas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/sindicatos"> <i class="icon-list"></i> <? echo $idioma["curso_sindicatos"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list icon-white"></i> <? echo $idioma["curso_sindicatos"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/emailboasvindas"> <i class="icon-envelope"></i> <? echo $idioma["curso_email_boasvindas"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-envelope icon-white"></i> <? echo $idioma["curso_email_boasvindas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/ava"> <i class="icon-envelope"></i> <? echo $idioma["curso_ava"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-envelope icon-white"></i> <? echo $idioma["curso_ava"]; ?></a>
      <?php } ?>
    </li>	
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurso"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>
    </li>
  </ul>    
</section>