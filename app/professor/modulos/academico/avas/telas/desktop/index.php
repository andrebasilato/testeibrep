<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php incluirLib("head",$config,$usu_professor); ?>
</head>
<body>
  <?php incluirLib("topo",$config,$usu_professor); ?>
  <div class="container-fluid">
    <section id="global">
      <div class="page-header">
        <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
      </div>
      <ul class="breadcrumb">
        <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
        <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
        <li class="active"><?= $idioma["pagina_titulo"]; ?></li>
        <? if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
        <span class="pull-right" style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
      </ul>
    </section>
    <div class="row-fluid">
      <div class="span12">
        <div class="box-conteudo box-ava">
          <? if($_POST["msg"]) { ?>
            <div class="alert alert-success fade in">
              <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
              <strong><?= $idioma[$_POST["msg"]]; ?></strong>
            </div>
          <? } ?> 
          <div id="listagem_informacoes"> 		  
            <? printf($idioma["informacoes"],$linhaObj->Get("total")); ?>
            <br />
            <? printf($idioma["paginas"],$linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>

          </div>
          <?php $linhaObj->GerarTabela($dadosArray,$_GET["q"],$idioma); ?>
          <div id="listagem_form_busca">
            <div class="input">
              <div class="inline-inputs"> 
				<?= $idioma["registros"]; ?>
                <form action="" method="get" id="formQtd">
                  <? if($_GET["buscarpor"] && $_GET["buscarem"]) { ?>
                    <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?= $_GET["buscarpor"]; ?>">
                    <input name="buscarem" type="hidden" id="buscaremQtd" value="<?= $_GET["buscarem"]; ?>">
                  <? } ?>
                  <? if(is_array($_GET["q"])){
                    foreach($_GET["q"] as $ind => $valor){
                    ?>
                      <input id="q[<?=$ind?>]" type="hidden" value="<?=$valor;?>" name="q[<?=$ind?>]" />
                    <? } 
                  } ?>
                  <? if($_GET["cmp"]){?>
                    <input id="cmp" type="hidden" value="<?=$_GET["cmp"];?>" name="cmp" />
                  <? } ?>
                  <? if($_GET["ord"]){?>
                    <input id="ord" type="hidden" value="<?=$_GET["ord"];?>" name="ord" />
                  <? } ?>
                  <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?= $linhaObj->Get("limite"); ?>" />
                  <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?= $idioma["exibir"]; ?></a> 
                </form>
              </div>
            </div>
          </div>
          <? if($linhaObj->Get("paginas") > 1) { ?>
            <div class="pagination"><ul><?= $linhaObj->GerarPaginacao($idioma); ?></ul></div>
          <? } ?>
          <div class="clearfix"></div>                                  
        </div>
      </div>
    </div>
    <? incluirLib("rodape",$config,$usu_professor); ?>
    <script language="javascript" type="text/javascript">
	  jQuery(document).ready(function($) {
		$("#qtd").keypress(isNumber);
		$("#qtd").blur(isNumberCopy);
	  });
    </script>
  </div>
</body>
</html>