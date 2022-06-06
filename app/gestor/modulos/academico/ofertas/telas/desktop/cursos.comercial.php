<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usuario); ?>
  <link href="/assets/css/menuVertical.css" rel="stylesheet" />
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
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/editar"><? echo $linha["oferta"]; ?></a> <span class="divider">/</span> </li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $linha["idoferta"]; ?>/cursos"><?= $idioma["pagina_titulo_interno"]; ?></a> <span class="divider">/</span></li>
        <li><?php echo $linha["curso"]; ?> <span class="divider">/</span></li>
        <li class="active"><?php echo $idioma["titulo_opcao_remover"]; ?></li>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <div class="tabbable tabs-left">
          
            <div class="pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>" class="btn btn-small"><i class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a></div>
		    <h2 class="tituloEdicao"><?php echo $linha["oferta"]; ?></h2>
            <?php incluirTela("inc_menu_edicao",$config,$linha); ?>
            
            <div class="tab-content">
			<div class="ava-conteudo"> 
              
			  
                        <div class="cabecalho-subsecao">
                        	<div class=" pull-right"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/<?= $url[4]; ?>" class="btn btn-mini"> <?= $idioma["btn_sair_curso"]; ?> <i class="icon-remove"></i></a></div>
                      		<small><?= $idioma["voce_esta_no_curso"]; ?></small>	
    						<h4 class="tituloEdicao" style="padding-left:0px;"><?= $linha["curso"]; ?> </h4>
                            <?php include("inc_submenu_cursos.php"); ?>
                        
                        </div>    
                        <h2 class="tituloOpcao"><?= $idioma["titulo_opcao_remover"]; ?></h2>  
              
              <div class="tab-pane active" id="tab_editar">
                <? if(count($salvar["erros"]) > 0){ ?>
                  <div class="alert alert-error fade in">
                    <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                    <strong><?= $idioma["form_erros"]; ?></strong>
                    <? foreach($salvar["erros"] as $ind => $val) { ?>
                      <br />
                      <?php echo $idioma[$val]; ?>
                    <? } ?>
                  </div>
                <? } ?>
              </div>
			  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<? incluirLib("rodape",$config,$usuario); ?>
  </div>
</body>
</html>