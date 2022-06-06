<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
      <li class="active"><strong><?php echo strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, "...") : $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
<!--    <li>-->
<!--      < ?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1", NULL)){ ?>-->
<!--        <a href="/< ?= $url[0]; ?>/< ?= $url[1]; ?>/< ?= $url[2]; ?>/< ?php echo $linha["idoferta"]; ?>/resumo"> <i class="icon-align-justify"></i> < ? echo $idioma["resumo"]; ?></a>-->
<!--      < ?php } else { ?>-->
<!--        <a href="javascript:void(0)" data-original-title="< ?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-align-justifyicon-white"></i> < ? echo $idioma["resumo"]; ?></a>-->
<!--      < ?php } ?>-->
<!--    </li>-->
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/cursos"> <i class="icon-th-list"></i> <? echo $idioma["oferta_cursos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_cursos"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/cfc"> <i class="icon-th-list"></i> <? echo $idioma["oferta_escolas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_escolas"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/turmas"> <i class="icon-th-list"></i> <? echo $idioma["oferta_turmas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_turmas"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/turmas_sindicatos"> <i class="icon-th-list"></i> <? echo $idioma["oferta_turmas_sindicatos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_turmas_sindicatos"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/cursos_cfc"> <i class="icon-th-list"></i> <? echo $idioma["oferta_cursos_escolas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_cursos_escolas"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/cursos_sindicatos"> <i class="icon-th-list"></i> <? echo $idioma["oferta_cursos_sindicatos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_cursos_sindicatos"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/curriculos_avas"> <i class="icon-th-list"></i> <? echo $idioma["oferta_curriculos_avas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["oferta_curriculos_avas"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/impressao" target="_blank"> <i class="icon-th-list"></i> <? echo $idioma["gerar_impressao"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["gerar_impressao"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/clonar"> <i class="icon-th-list"></i> <? echo $idioma["clonar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-th-list icon-white"></i> <? echo $idioma["clonar"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idoferta"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>
    </li>
  </ul>
</section>