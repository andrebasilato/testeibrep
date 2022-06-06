  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linhaAva["nome"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		<li>
            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/pessoas?q[1|oca.idava]=<?= $linhaAva["idava"]?>"> <i class="icon-edit"></i> <?= $idioma["alunos"]; ?></a>
        </li>
        <!-- <li>
            <a href="javascript:void();" onclick="window.open('/<?= $url["0"] ?>/<?= $url["1"] ?>/<?=$url["2"]?>/<?= $linhaAva["idava"]?>/mensagem_instantanea','<?= $linhaAva['idava']?>','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=600');" > <i class="icon-list-alt"></i> <? echo $idioma["mensagens_instantaneas"]; ?></a>
        </li> -->
      </ul>    
  </section>