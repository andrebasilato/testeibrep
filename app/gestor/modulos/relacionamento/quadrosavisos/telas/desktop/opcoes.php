  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["titulo"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
              <?php } ?>        
          </li>        
         <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/imagens"> <i class="icon-picture"></i> <? echo $idioma["imagem"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-picture icon-white"></i> <? echo $idioma["imagem"]; ?></a>
              <?php } ?>      
          </li> 
          <?php /* <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|9", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/arquivos"> <i class="icon-folder-open"></i> <? echo $idioma["arquivo"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-folder-open icon-white"></i> <? echo $idioma["arquivo"]; ?></a>
              <?php } ?>      
          </li>             
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/preview"> <i class="icon-chevron-down"></i> <? echo $idioma["preview"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-chevron-down icon-white"></i> <? echo $idioma["preview"]; ?></a>
              <?php } ?>      
          </li>  */ ?> 
		  <li>
			  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|6", NULL)){ ?>
				<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/quadro_escolas"> <i class="icon-share-alt"></i> <? echo $idioma["associar_escola"]; ?></a>
			  <? } else { ?>
				<a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share-alt icon-white"></i> <? echo $idioma["associar_escola"]; ?></a>
			  <?php } ?>
		  </li>
		  <li>
			  <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
				<a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/quadro_ofertas"> <i class="icon-share-alt"></i> <? echo $idioma["associar_oferta"]; ?></a>
			  <? } else { ?>
				<a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share-alt icon-white"></i> <? echo $idioma["associar_oferta"]; ?></a>
			  <?php } ?>
		  </li>
      <li>
        <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|8", NULL)){ ?>
        <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/quadro_cursos"> <i class="icon-share-alt"></i> <? echo $idioma["associar_curso"]; ?></a>
        <? } else { ?>
        <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share-alt icon-white"></i> <? echo $idioma["associar_curso"]; ?></a>
        <?php } ?>
      </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idquadro"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>      
          </li>     
      </ul>    
  </section>