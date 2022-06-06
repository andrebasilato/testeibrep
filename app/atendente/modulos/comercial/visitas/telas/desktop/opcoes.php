  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["idvisita"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		<li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
        </li>
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/mensagens"> <i class="icon-list-alt"></i> <? echo $idioma["mensagens"]; ?></a>
        </li>
		<li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/visitas"> <i class="icon-list-alt"></i> <? echo $idioma["visitas"]; ?></a>                
        </li>
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idvisita"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
        </li>
      </ul>    
  </section>