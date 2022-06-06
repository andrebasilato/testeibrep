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
       <h1><?php echo $idioma["pagina_titulo"]; ?> &nbsp;<small><?php echo $idioma["pagina_subtitulo"]; ?></small></h1>
     </div>
     <ul class="breadcrumb">
       <li><a href="/<?php echo $url[0]; ?>"><?php echo $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
       <li><a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>"><?php echo $idioma["nav_modulo"]; ?></a> <span class="divider">/</span></li>
       <li class="active"><?php echo $idioma["pagina_titulo"]; ?></li>
       <?php if($_GET["q"]) { ?><li><span class="divider">/</span> <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>"><?php echo $idioma["nav_resetarbusca"]; ?></a></li><? } ?>
       <span class="pull-right" style="color:#999"><?php echo $idioma["hora_servidor"]; ?> <?php echo date("d/m/Y H\hi"); ?></span>
     </ul>
   </section>
   <div class="row-fluid">
     <div class="span12">


        <div class="box-conteudo">

        <? if($_POST["msg"]) { ?>
        <div class="alert alert-success fade in">
          <?php /*?><button class="close" data-dismiss="alert">×</button><?php */?>
          <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
          <strong><?php echo $idioma[$_POST["msg"]]; ?></strong>
        </div>
        <? } ?>
        <div id="listagem_informacoes">
         <? printf($idioma["informacoes"], $linhaObj->Get("total")); ?>
         <br />
         <? printf($idioma["paginas"], $linhaObj->Get("pagina"),$linhaObj->Get("paginas")); ?>
       </div>
       <?php $linhaObj->GerarTabela($linhaObj->getResult(),$_GET["q"],$idioma); ?>
       <div id="listagem_form_busca">
        <div class="input">
          <div class="inline-inputs"> <?php echo $idioma["registros"]; ?>
            <form action="" method="get" id="formQtd">
              <? if($_GET["buscarpor"] && $_GET["buscarem"]) { ?>
              <input name="buscarpor" type="hidden" id="buscarporQtd" value="<?php echo $_GET["buscarpor"]; ?>">
              <input name="buscarem" type="hidden" id="buscaremQtd" value="<?php echo $_GET["buscarem"]; ?>">
              <? } ?>
              <? if ( is_array( $_GET['q'] ) ) {
                foreach($_GET["q"] as $ind => $valor){
                  ?>
                  <input id="q[<?php echo$ind?>]" type="hidden" value="<?php echo$valor;?>" name="q[<?php echo$ind?>]" />
                  <? } } ?>
                  <? if ( $_GET['cmp'] ) {?>
                  <input id="cmp" type="hidden" value="<?php echo$_GET["cmp"];?>" name="cmp" />
                  <? } ?>
                  <? if ( $_GET['ord'] ) {?>
                  <input id="ord" type="hidden" value="<?php echo$_GET["ord"];?>" name="ord" />
                  <? } ?>
                  <input name="qtd" type="text" class="span1" id="qtd" maxlength="4" value="<?php echo $linhaObj->Get("limite"); ?>" />
                  <a href="javascript:document.getElementById('formQtd').submit();" class="btn small"><?php echo $idioma["exibir"]; ?></a>
                </form>
              </div>
            </div>
          </div>
          <? if($linhaObj->Get("paginas") > 1) { ?>
          <div class="pagination">
            <ul><?php echo $linhaObj->GerarPaginacao($idioma); ?></ul>
          </div>
          <? } ?>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <?php incluirLib('rodape', $config, $usu_professor); ?>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
    <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
    <script language="javascript" type="text/javascript">
    jQuery(document).ready(function($) {
        $("#qtd").keypress(isNumber);
        $("#qtd").blur(isNumberCopy);
        $("input[name='q[3|re.data_cad]']").datepicker($.datepicker.regional["pt-BR"]);
        $("input[name='q[3|re.vencimento]']").datepicker($.datepicker.regional["pt-BR"]);
    });
    </script>
  </div>
</body>
</html>