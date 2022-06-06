  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["curso"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		  
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?php echo $linha["idoferta_curso"]; ?>/academico"> <i class="icon-edit"></i> <? echo $idioma["academico"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["academico"]; ?></a>
              <?php } ?>        
          </li> 
		  
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?php echo $linha["idoferta_curso"]; ?>/comercial"> <i class="icon-edit"></i> <? echo $idioma["comercial"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["comercial"]; ?></a>
              <?php } ?>        
          </li> 
		  
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?php echo $linha["idoferta_curso"]; ?>/financeiro"> <i class="icon-edit"></i> <? echo $idioma["financeiro"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["financeiro"]; ?></a>
              <?php } ?>        
          </li> 
        
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>/<?php echo $linha["idoferta_curso"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>        
          </li>
        
      </ul>    
  </section>