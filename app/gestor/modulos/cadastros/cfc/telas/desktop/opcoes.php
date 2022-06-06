<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo $linha["nome_fantasia"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/estados_cidades"> <i class="icon-globe"></i> <? echo $idioma["estados_cidades"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-globe icon-white"></i> <? echo $idioma["estados_cidades"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/mensagens"> <i class="icon-list-alt"></i> <? echo $idioma["mensagens"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["mensagens"]; ?></a>
      <?php } ?>
    </li>
    <li><?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6", NULL)) { ?>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/acessarcomo"
               target="_blank" onclick="return confirmaAcessoComo('<?php echo $linha["nome_fantasia"]; ?>');"> <i
                    class="icon-user"></i> <? echo $idioma["acessarcomo"]; ?></a>
        <?php } else { ?>
            <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
               data-placement="left" rel="tooltip" style="color:#999;"> <i
                    class="icon-user icon-white"></i> <? echo $idioma["acessarcomo"]; ?></a>
        <?php } ?>
    </li>
	<li>
          <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
              <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/contatos"> <i class="icon-list-alt"></i> <? echo $idioma["contatos"]; ?></a>
          <?php } else { ?>
              <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["contatos"]; ?></a>
          <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/contratos"> <i class="icon-ok-circle"></i> <? echo $idioma["contratos"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-ok-circle icon-white"></i> <? echo $idioma["contratos"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/acesso_bloqueado"> <i class="icon-ok-circle"></i> <? echo $idioma["acesso_bloqueado"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-ok-circle icon-white"></i> <? echo $idioma["acesso_bloqueado"]; ?></a>
      <?php } ?>
    </li>
    <li>
        <?php
        if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5", NULL)){ ?>
              <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idescola"]; ?>/valores_cursos"> <i class="icon-list-alt"></i> <?= $idioma["valores_cursos"]; ?></a>
        <?php
        } else { ?>
              <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <?= $idioma["valores_cursos"]; ?></a>
        <?php
        } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/pasta_virtual"> <i class="icon-share-alt"></i> <? echo $idioma["pasta_virtual"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["pasta_virtual"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idescola"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>
    </li>
  </ul>
</section>