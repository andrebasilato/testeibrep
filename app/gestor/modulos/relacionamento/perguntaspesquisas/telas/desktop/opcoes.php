  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["pergunta_selecionada"]; ?></li>
    	<li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpergunta"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
              <?php } ?>        
          </li>  
          
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <?php if ($linha["tipo"] == 'O') { ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpergunta"]; ?>/editar_opcoes"> <i class="icon-pencil"></i> <? echo $idioma["editar_opcoes"]; ?></a>
                  <?php } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao_tipo"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["editar_opcoes"]; ?></a>
                  <?php } ?>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["editar_opcoes"]; ?></a>
              <?php } ?>        
          </li>
          
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <?php if ($linha["tipo"] == 'O') { ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpergunta"]; ?>/grafico_respostas"> <i class="icon-signal"></i> <? echo $idioma["grafico_respostas"]; ?></a>
                  <?php } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao_tipo"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-signal icon-white"></i> <? echo $idioma["grafico_respostas"]; ?></a>
                  <?php } ?>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-signal icon-white"></i> <? echo $idioma["grafico_respostas"]; ?></a>
              <?php } ?>        
          </li>      
        
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpergunta"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>      
          </li>
        
      </ul>    
  </section>