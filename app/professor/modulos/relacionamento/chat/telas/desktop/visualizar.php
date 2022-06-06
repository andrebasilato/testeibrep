<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib('head', $config, $usu_professor); ?>
</head>
<body>
  <?php incluirLib('topo', $config, $usu_professor); ?>
  <div class="container-fluid">
    <section id="global">
       <div class="page-header">
         <h1><?php echo  $idioma['pagina_titulo']; ?> &nbsp;<small><?php echo  $idioma['pagina_subtitulo']; ?></small></h1>
     </div>
     <ul class="breadcrumb">
         <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
         <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
         <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["pagina_titulo"]; ?></a> <span class="divider">/</span></li>
         <li class="active"><?php echo $linha["titulo"]; ?></li>
         <span class="pull-right" style="color:#999"><?php echo $idioma["hora_servidor"]; ?> <?php echo date("d/m/Y H\hi"); ?></span>
     </ul>
 </section>
 <div class="row-fluid">
   <div class="span9">
      <div class="box-conteudo">

        <form action="" method="post" enctype="multipart/form-data">
            <?php
                if ( $_POST ) {
                    $linhaObj->gerarFormulario( 'formulario_pessoas', $_POST, $idioma );
                } else {
                    $linhaObj->gerarFormulario( 'formulario_pessoas', $linha, $idioma );
                }
            ?>
            <input type="hidden" value="novochat" name="acao">
            <input type="submit" value="Enviar" class="btn btn-primary">
        </form>

        <div class="section-body">
        <div id="blog-posts">
            <h4 class="post-title"><?php echo $linha['titulo']; ?></a></h4><small class="muted"><?php echo formataData($linha['data_cad'],'pt',1); ?></small><br /><br />
            <p><?php echo $linha['descricao']; ?></p>
        </div>
    </div>
    <div class="clearfix"></div>
    <a href="javascript:void(0);" class="btn btn-primary" onclick="MM_goToURL('parent','/<?php echo  $url[0]; ?>/<?php echo  $url[1]; ?>/<?php echo  $url[2]; ?>');"><i class="icon-arrow-left icon-white"></i><?php echo  $idioma["btn_voltar"]; ?></a>
</div>
</div>
<div class="span3">
  <?php incluirLib('sidebar_mural', $config, $usu_professor); ?>
</div>
</div>
<?php incluirLib('rodape', $config, $usu_professor); ?>
</div>
</body>
</html>