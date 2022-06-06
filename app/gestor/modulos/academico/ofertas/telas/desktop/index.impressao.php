<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
  <? incluirLib("topo",$config,$usuario); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header"><h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1></div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idava"]; ?>/editar"><?= $linha["nome"]; ?></a> <span class="divider">/</span> </li>
        <li class="active"><?= $idioma["pagina_titulo_interno"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo">
		  <div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
            	<h2 class="tituloEdicao"><?= $linha["nome"]; ?> <? /* <small>(<?= $linha["email"]; ?>)</small> */ ?></h2>
          <div class="tabbable tabs-left">
            <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_editar">
					<h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>
					                
							<form class="well" method="post" id="form">
    							<p><?= $idioma["form_associar"]; ?></p>   
                                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/impressao" target="_blank" class="btn">   <?= $idioma["btn_adicionar"]; ?> </a>                             
    						</form>
							
				
				
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
    <? incluirLib("rodape",$config,$usuario); ?>
	
	<script src="/assets/plugins/fcbkcomplete/jquery.fcbkcomplete.min.js"></script>
    <script type="text/javascript">		
		function remover(id) {
			confirma = confirm('<?= $idioma["confirmar_remocao"]; ?>');
			if(confirma) {
				document.getElementById("remover").value = id;
				document.getElementById("remover_turma").submit();
			} 
		}
	</script>
	
  </div>
</body>
</html>