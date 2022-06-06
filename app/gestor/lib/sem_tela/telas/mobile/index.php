<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
<?php incluirLib("topo",$config,$usuario); ?>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $idioma["pagina_titulo"]; ?></h1>
  	</div>
  </section>
  <div class="row-fluid">
  	<div class="span12">
        <div class="box-conteudo">
        
        <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td><img src="/assets/img/Mobile.png" width="128" height="128" /></td>
          </tr>
          <tr>
            <td><H3 style="text-transform:uppercase;"><?= $idioma["pagina_subtitulo"]; ?></H3></td>
          </tr>
          <tr>
            <td><p><a href="javascript:history.back();">Clique aqui para voltar à página anterior.</a>&nbsp;</p></td>
          </tr>
        </table>
<div class="clearfix"></div>                                  
        </div>
    </div>
  </div>
  <? incluirLib("rodape",$config,$usuario); ?>
</div>
</body>
</html>