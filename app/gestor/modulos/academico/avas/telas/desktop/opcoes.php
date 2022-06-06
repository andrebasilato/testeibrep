<section id="global">
  <div class="page-header"><h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?php echo $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?php echo $idioma["usuario_selecionado"]; ?></li>
    <li class="active"><strong><?php echo strlen($linha["nome"]) > 30 ? mb_strimwidth($linha["nome"], 0, 50, "...") : $linha["nome"]; ?></strong></li>
  </ul>
  <ul class="nav nav-tabs nav-stacked">
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/disciplinas"> <i class="icon-edit"></i> <? echo $idioma["disciplinas"]; ?></a>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["disciplinas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/rotasdeaprendizagem"> <i class="icon-refresh"></i> <? echo $idioma["rotas"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-refresh icon-white"></i> <? echo $idioma["rotas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/conteudos"> <i class="icon-list-alt"></i> <? echo $idioma["conteudos"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["conteudos"]; ?></a>
      <?php } ?>
    </li>
    <li>
    <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|40", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/objetosdivisores"> <i class="icon-list-alt"></i> <? echo $idioma["objetosdivisores"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["objetosdivisores"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/videos"> <i class="icon-facetime-video"></i> <? echo $idioma["videos"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-facetime-video icon-white"></i> <? echo $idioma["videos"]; ?></a>
      <?php } ?>
    </li>
    <li>
        <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/aulaonline"> <i class="icon-facetime-video"></i> <? echo $idioma["aulaonline"]; ?></a>
        <? } else { ?>
            <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-facetime-video icon-white"></i> <? echo $idioma["aulaonline"]; ?></a>
        <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/audios"> <i class="icon-music"></i> <? echo $idioma["audios"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-music icon-white"></i> <? echo $idioma["audios"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/downloads"> <i class="icon-download"></i> <? echo $idioma["downloads"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-download icon-white"></i> <? echo $idioma["downloads"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|22", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/links"> <i class="icon-tasks"></i> <? echo $idioma["links"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-tasks icon-white"></i> <? echo $idioma["links"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|25", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/foruns"> <i class="icon-folder-open"></i> <? echo $idioma["foruns"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-folder-open icon-white"></i> <? echo $idioma["foruns"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/perguntas"> <i class="icon-pencil"></i> <? echo $idioma["perguntas"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["perguntas"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|51", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/enquetes"> <i class="icon-pencil"></i> <? echo $idioma["enquetes"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["enquetes"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/exercicios"> <i class="icon-book"></i> <? echo $idioma["exercicios"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-book icon-white"></i> <? echo $idioma["exercicios"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|28", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/avaliacoes"> <i class="icon-book"></i> <? echo $idioma["avaliacoes"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-book icon-white"></i> <? echo $idioma["avaliacoes"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|31", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/simulados"> <i class="icon-exclamation-sign"></i> <? echo $idioma["simulados"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-exclamation-sign icon-white"></i> <? echo $idioma["simulados"]; ?></a>
      <?php } ?>
    </li>
    <?php /*?><li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|34", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/tiraduvidas"> <i class="icon-bullhorn"></i> <? echo $idioma["tira_duvidas"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-bullhorn icon-white"></i> <? echo $idioma["tira_duvidas"]; ?></a>
      <?php } ?>
    </li><?php */?>
    <li>
	  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|37", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/chats"> <i class="icon-comment"></i> <? echo $idioma["chats"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-comment icon-white"></i> <? echo $idioma["chats"]; ?></a>
      <?php } ?>
    </li>
    <li>
	  <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/discovirtual"> <i class="icon-remove"></i> Disco Virtual</a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> Disco Virtual</a>
      <?php } ?>
	  <li>
      <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|57", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/faqs"> <i class="icon-question-sign"></i> <? echo $idioma["faqs"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-question-sign icon-white"></i> <? echo $idioma["faqs"]; ?></a>
      <?php } ?>
    </li>
 	  <li>
      <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|60", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/clonarava"> <i class="icon-magnet"></i> <? echo $idioma["clonar"]; ?></a>
      <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-magnet icon-white"></i> <? echo $idioma["clonar"]; ?></a>
      <?php } ?>
    </li>
    <?php if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idava"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a></li>
      <?php } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["remover"]; ?></a>
      <?php } ?>


   </li>
  </ul>
</section>
