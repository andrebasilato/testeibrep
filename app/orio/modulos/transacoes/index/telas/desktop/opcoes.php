<section id="global">
    <div class="page-header">
	   <h1><?= $idioma["opcoes"]; ?> &nbsp;<small class="hidden-phone"><?= $idioma["pagina_subtitulo"] ?></small></h1>
	</div>
	<ul class="breadcrumb">
    	<li><?= $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?= $linha["idtransacao"]; ?></strong></li>
	</ul>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <a href="/<?= $url[0]; ?>/<?= $url[1] ?>/<?= $url[2] ?>/<?php echo $linha["idtransacao"] ?>/editar"> <i class="icon-edit"></i> <?= $idioma["editar"] ?></a>
        </li>
    </ul>    
</section>