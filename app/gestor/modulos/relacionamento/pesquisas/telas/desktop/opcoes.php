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
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
              <?php } ?>        
          </li>        
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/perguntas"> <i class="icon-question-sign"></i> <? echo $idioma["perguntas"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-question-sign icon-white"></i> <? echo $idioma["perguntas"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|20", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/corpo_email"> <i class="icon-envelope"></i> <? echo $idioma["corpo_email"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-envelope icon-white"></i> <? echo $idioma["corpo_email"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|17", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/fila"> <i class="icon-list-alt"></i> <? echo $idioma["fila"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-list-alt icon-white"></i> <? echo $idioma["fila"]; ?></a>
              <?php } ?>      
          </li> 
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|12", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/imagens"> <i class="icon-picture"></i> <? echo $idioma["imagens"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-picture icon-white"></i> <? echo $idioma["imagens"]; ?></a>
              <?php } ?>      
          </li>
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|10", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/layout"> <i class="icon-check"></i> <? echo $idioma["layout"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-check icon-white"></i> <? echo $idioma["layout"]; ?></a>
              <?php } ?>      
          </li>           
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|15", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/preview"> <i class="icon-chevron-down"></i> <? echo $idioma["preview"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-chevron-down icon-white"></i> <? echo $idioma["preview"]; ?></a>
              <?php } ?>      
          </li>
          
           <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|16", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/clonar" onclick="return confirmar();"> <i class="icon-share"></i> <? echo $idioma["clonar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-share icon-white"></i> <? echo $idioma["clonar"]; ?></a>
              <?php } ?>      
          </li>
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|5", NULL)){ ?>
          	      <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/alterar_situacao"> <i class="icon-pencil"></i> <? echo $idioma["alterar_situacao"]; ?></a>
              <? } else { ?>
              	  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>" data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-pencil icon-white"></i> <? echo $idioma["alterar_situacao"]; ?></a>
              <?php } ?>
          </li>
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|18", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/resultado"> <i class="icon-file"></i> <? echo $idioma["resultado"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-file icon-white"></i> <? echo $idioma["resultado"]; ?></a>
              <?php } ?>      
          </li>
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|19", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/reenviar"> <i class="icon-repeat"></i> <? echo $idioma["reenviar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-repeat icon-white"></i> <? echo $idioma["reenviar"]; ?></a>
              <?php } ?>      
          </li>
		 <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idpesquisa"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>      
          </li>		  
      </ul>    
  </section>