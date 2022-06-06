<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
  
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/dadosgerais"> <i class="icon-align-center"></i> <? echo $idioma["dados_gerais"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-align-center icon-white"></i> <? echo $idioma["dados_gerais"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/avaliacoes"> <i class="icon-list-alt"></i> <? echo $idioma["avaliacoes"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["avaliacoes"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/blocos"> <i class="icon-th-large"></i> <? echo $idioma["blocos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-large icon-white"></i> <? echo $idioma["blocos"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/disciplinas"> <i class="icon-th-list"></i> <? echo $idioma["disciplinas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["disciplinas"]; ?></a>
      <?php } ?>
    </li>
	<li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/arquivos_cursos"> <i class="icon-th-list"></i> <? echo $idioma["arquivos_cursos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["arquivos_cursos"]; ?></a>
      <?php } ?>
    </li>
	<li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/tipos_notas"> <i class="icon-th-list"></i> <? echo $idioma["tipos_notas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["tipos_notas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idcurriculo"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>
    </li>
  </ul>    
</section>