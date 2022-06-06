<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
</head>
<body>
<div class="container-fluid">
  <section id="global">
	<div class="page-header">
    	<h1><?= $linha["titulo"]; ?> &nbsp;</h1>
  	</div>
  </section>
  <div class="row-fluid">
  	<div class="">
        <div class="box-conteudo">
      		<div class="tabbable tabs-left">
                <?= $linha["descricao"]?>
            </div><strong></strong>
        </div>
    </div> 
  </div>
</div>
</body>
</html>