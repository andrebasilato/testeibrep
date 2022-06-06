  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
              <?php } ?>        
          </li>        
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/corpo_email"> <i class="icon-envelope"></i> <? echo $idioma["corpo_email"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-envelope icon-white"></i> <? echo $idioma["corpo_email"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/imagens"> <i class="icon-picture"></i> <? echo $idioma["imagens"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-picture icon-white"></i> <? echo $idioma["imagens"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|14", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/fila"> <i class="icon-list-alt"></i> <? echo $idioma["fila"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["fila"]; ?></a>
              <?php } ?>      
          </li> 
		            
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/preview"> <i class="icon-chevron-down"></i> <? echo $idioma["preview"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-chevron-down icon-white"></i> <? echo $idioma["preview"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/alterar_situacao"> <i class="icon-pencil"></i> <? echo $idioma["alterar_situacao"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["alterar_situacao"]; ?></a>
              <?php } ?>
          </li>
           <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|13", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/clonar" onclick="return confirmar();"> <i class="icon-share"></i> <? echo $idioma["clonar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share icon-white"></i> <? echo $idioma["clonar"]; ?></a>
              <?php } ?>      
          </li>
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/reenviar"> <i class="icon-repeat"></i> <? echo $idioma["reenviar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-repeat icon-white"></i> <? echo $idioma["reenviar"]; ?></a>
              <?php } ?>      
          </li>
		 <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>      
          </li>		  
      </ul>    
  </section>