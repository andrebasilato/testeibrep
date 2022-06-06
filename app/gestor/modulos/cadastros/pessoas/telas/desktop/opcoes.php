<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/acessarcomo" target="_blank" onclick="return confirmaAcessoComo('<?php echo $linha["nome"]; ?>');"> <i class="icon-user"></i> <? echo $idioma["acessarcomo"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-user icon-white"></i> <? echo $idioma["acessarcomo"]; ?></a>
      <?php } ?>
    </li>    
    <li>
          <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9", NULL)){ ?>
              <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/contatos"> <i class="icon-list-alt"></i> <? echo $idioma["contatos"]; ?></a>
          <? } else { ?>
              <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["contatos"]; ?></a>
          <?php } ?>
    </li>
<!--    <li>
          <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
              <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/associacoes"> <i class="icon-share-alt"></i> <? echo $idioma["associacoes"]; ?></a>
          <? } else { ?>
              <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share-alt icon-white"></i> <? echo $idioma["associacoes"]; ?></a>
          <?php } ?>
    </li>-->
    <li>
      <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/desativar_login"> <i class="icon-off"></i> <? echo $idioma["desativar_login"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-off icon-white"></i> <? echo $idioma["desativar_login"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/resetar_senha"> <i class="icon-ok-circle"></i> <? echo $idioma["resetar_senha"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-ok-circle icon-white"></i> <? echo $idioma["resetar_senha"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|11", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/sindicatos"> <i class="icon-ok-circle"></i> <? echo $idioma["sindicatos"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-ok-circle icon-white"></i> <? echo $idioma["sindicatos"]; ?></a>
      <?php } ?>
    </li>
    <li>
      <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpessoa"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>
    </li>
  </ul>    
</section>
